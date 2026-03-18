<?php

namespace App\Logging;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface LoggerStrategy
{
    /**
     * Log a request and response.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function log(Request $request, Response $response): void;

    /**
     * Get the logs.
     *
     * @return array
     */
    public function getLogs(): array;
}
