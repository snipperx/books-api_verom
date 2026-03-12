<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;

/**
 * @mixin NewAccessToken
 */
final class AuthTokenResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /*
            | The plaintext token is ONLY available immediately after creation.
            | It is never retrievable again — the client must store it securely.
            | We include an explicit note in the response to make this clear.
            */
            'access_token' => $this->plainTextToken,
            'token_type'   => 'Bearer',
            'abilities'    => $this->accessToken->abilities,
            'expires_at'   => $this->accessToken->expires_at?->toIso8601String(),
            'note'         => 'Store this token securely. It will not be shown again.',
        ];
    }
}
