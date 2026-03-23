<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

use App\DTO\BookData;
use App\DTO\ApiResponse;

class GoogleBooksService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl= config('services.google_books.url');
    }

    // Search a book by its ISBN
    public function searchByIsbn(string $isbn): ?ApiResponse
    {


        $attempt = 'Search-attempt-'.request()->ip;

        // Rate limiter: max 10 attampts per minute
        if(RateLimiter::tooManyAttempts($attempt, $perMinute = 10)){
            return ApiResponse::error('Too many requests! Please try again later.', null);
        }

        RateLimiter::hit($attempt, 60);

        try{

            return Cache::remember($isbn, 3600, function() use ($isbn){
                $response = Http::get($this->baseUrl, [
                    'q' => 'isbn:' . $isbn,
                    'key' => config('services.google_books.key'),
                ]);

                // If API call fail
                if($response->failed()){
                    return ApiResponse::error("Something went wrong. Please try again later.", null);
                    Cache::forget($isbn);
                }

                $data = $response->json();

                // If API call return empty data
                if (empty($data['items'][0]['volumeInfo'])) {
                    return ApiResponse::error("Book not foundd.", null);
                    Cache::forget($isbn);
                }

                $book = $data['items'][0]['volumeInfo'];

                return ApiResponse::success('Book found', BookData::googleBooks($book));

            });
        }catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection issues
            logger()->warning('Connection error', [
                'isbn' => $isbn,
                'error' => $e->getMessage(),
            ]);

            return ApiResponse::error('Connection error. Please try again later.', null);

        } catch (Exception $e) {
            // Generic exception
            logger()->error('Unexpected error calling Google Books', [
                'isbn' => $isbn,
                'error' => $e->getMessage(),
            ]);

            return ApiResponse::error('An unexpected error occurred. Please try again later.', null);

        }
    }
}
