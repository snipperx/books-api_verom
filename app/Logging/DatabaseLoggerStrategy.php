<?php

namespace App\Logging;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DatabaseLoggerStrategy implements LoggerStrategy
{
    public function log(Request $request, Response $response): void
    {
        try {
            $log = new Log();
            $log->method      = $request->getMethod();
            $log->url         = $request->fullUrl();
            $log->request     = json_encode($request->all());
            $log->response    = $response->getContent();
            $log->status_code = $response->getStatusCode();
            $log->created_at  = now();
            $log->save();
        } catch (\Exception $e) {
            throw new RuntimeException("Failed to write log to database: " . $e->getMessage());
        }
    }

    public function getLogs(): array
    {
        return DB::table('logs')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}
