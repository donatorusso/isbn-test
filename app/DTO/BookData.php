<?php
namespace App\DTO;

class BookData{
    public function __construct(
        public ?string $title,
        public array $authors,
        public ?string $publishedDate,
        public ?string $description,
        public ?string $isbn,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authors' => $this->authors,
            'published_date' => $this->publishedDate,
            'description' => $this->description,
            'isbn' => $this->isbn,
        ];
    }

    public static function googleBooks(array $book): self
    {
        return new self(
            $book['title'] ?? null,
            $book['authors'] ?? [],
            $book['publishedDate'] ?? null,
            $book['description'] ?? null,
            $book['isbn'] ?? null,
        );
    }
}
