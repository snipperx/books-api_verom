<?php

namespace App\Logging;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RuntimeException;

class FileLoggerStrategy implements LoggerStrategy
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('logs/api.log');
        $this->ensureLogFileExists();
    }

    public function log(Request $request, Response $response): void
    {
        $log = [
            'request' => [
                'method'  => $request->getMethod(),
                'url'     => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'body'    => $request->all(),
            ],
            'response' => [
                'status_code' => $response->getStatusCode(),
                'headers'     => $response->headers->all(),
                'body'        => $response->getContent(),
            ],
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->logToFile($log);
    }

    public function getLogs(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $logs = file_get_contents($this->filePath);
        $logs = explode(PHP_EOL, $logs);
        $logs = array_filter($logs);

        return array_map(function ($log) {
            return json_decode($log, true);
        }, $logs);
    }

    private function logToFile(array $log): void
    {
        $logString = json_encode($log) . PHP_EOL;

        if (file_put_contents($this->filePath, $logString, FILE_APPEND | LOCK_EX) === false) {
            throw new RuntimeException("Failed to write to log file: {$this->filePath}");
        }
    }

    private function ensureLogFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            if (!touch($this->filePath)) {
                throw new RuntimeException("Failed to create log file: {$this->filePath}");
            }
        }
    }
}
