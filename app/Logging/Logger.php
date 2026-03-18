<?php

namespace App\Logging;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Logger
{
    private LoggerStrategy $strategy;

    public function __construct(LoggerStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(LoggerStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function log(Request $request, Response $response): void
    {
        $this->strategy->log($request, $response);
    }

    public function getLogs(): array
    {
        return $this->strategy->getLogs();
    }
}
