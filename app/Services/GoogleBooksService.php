<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl= config('services.google_books.url');
    }

    // Search a book by its isbn
    public function searchByIsbn(string $isbn): ?array
    {
        $response = Http::get($this->baseUrl, [
            'q' => 'isbn:' . $isbn,
            'key' => config('services.google_books.key'),
        ]);

        if($response->failed()){
            return null;
        }

        $data = $response->json();

        if (empty($data['items'][0]['volumeInfo'])) {
            return null;
        }

        $book = $data['items'][0]['volumeInfo'];

        return [
            'title' => $book['title'] ?? null,
            'authors' => $book['authors'] ?? [],
            'publisher' => $book['publisher'] ?? null,
            'published_date' => $book['publishedDate'] ?? null,
            'description' => $book['description'] ?? null,
            'isbn' => $isbn,
        ];
    }
}
