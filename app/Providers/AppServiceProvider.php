<?php

namespace App\Providers;

use App\Interfaces\BookServiceInterface;
use App\Services\GoogleBooksService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the BookServiceInterface to GoogleBooksService
        $this->app->bind(BookServiceInterface::class, function(){
            return new GoogleBooksService(
                config('services.google_books.url'),
                config('services.google_books.key')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
