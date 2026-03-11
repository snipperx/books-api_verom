<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\BookRepositoryInterface;
use App\Contracts\BookServiceInterface;
use App\Events\BookBorrowed;
use App\Events\BookReturned;
use App\Listeners\LogBorrowActivity;
use App\Repositories\BookRepository;
use App\Services\BookService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);
    }

    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->registerEventListeners();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('read', function (Request $request): Limit {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('write', function (Request $request): Limit {
            return Limit::perMinute(20)->by($request->ip());
        });
    }

    private function registerEventListeners(): void
    {
        $listener = LogBorrowActivity::class;

        Event::listen(BookBorrowed::class, [$listener, 'handleBorrowed']);
        Event::listen(BookReturned::class, [$listener, 'handleReturned']);
    }
}
