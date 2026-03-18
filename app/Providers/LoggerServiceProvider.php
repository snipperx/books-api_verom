<?php

namespace App\Providers;

use App\Logging\DatabaseLoggerStrategy;
use App\Logging\FileLoggerStrategy;
use App\Logging\Logger;
use App\Logging\LoggerStrategy;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Available logging strategies.
     */
    private array $strategies = [
        'database' => DatabaseLoggerStrategy::class,
        'file'     => FileLoggerStrategy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerStrategy();
        $this->registerLogger();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/logger.php' => config_path('logger.php'),
        ], 'logger-config');
    }

    /**
     * Register the logger strategy.
     */
    private function registerStrategy(): void
    {
        $this->app->bind(LoggerStrategy::class, function ($app) {
            $strategy = config('logger.api_strategy', 'file');

            if (!array_key_exists($strategy, $this->strategies)) {
                throw new InvalidArgumentException(
                    "Unsupported logging strategy [{$strategy}]. " .
                    "Supported strategies are: " . implode(', ', array_keys($this->strategies))
                );
            }

            return $app->make($this->strategies[$strategy]);
        });
    }

    /**
     * Register the logger as a singleton.
     */
    private function registerLogger(): void
    {
        $this->app->singleton(Logger::class, function ($app) {
            return new Logger($app->make(LoggerStrategy::class));
        });

        // Register alias for easier resolution
        $this->app->alias(Logger::class, 'api.logger');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            Logger::class,
            LoggerStrategy::class,
            'api.logger',
        ];
    }
}
