<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\DTO\BookData;

class GoogleBooksService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl= config('services.google_books.url');
    }

    // Search a book by its ISBN
    public function searchByIsbn(string $isbn): ?BookData
    {
        try{

            return Cache::remember($isbn, 3600, function() use ($isbn){
                $response = Http::get($this->baseUrl, [
                    'q' => 'isbn:' . $isbn,
                    'key' => config('services.google_books.key'),
                ]);

                // If API call fail
                if($response->failed()){
                    return null;
                }

                $data = $response->json();

                // If API call return empty data
                if (empty($data['items'][0]['volumeInfo'])) {
                    return null;
                }

                $book = $data['items'][0]['volumeInfo'];

                return BookData::googleBooks($book);

            });
        }catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection issues
            logger()->warning('Connection error', [
                'isbn' => $isbn,
                'error' => $e->getMessage(),
            ]);

            return null;

        } catch (Exception $e) {
            // Generic exception
            logger()->error('Unexpected error calling Google Books', [
                'isbn' => $isbn,
                'error' => $e->getMessage(),
            ]);

            return null;

        }
    }
}
