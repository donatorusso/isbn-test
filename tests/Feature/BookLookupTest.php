<?php

namespace Tests\Feature;

use App\DTO\ApiResponse;
use App\DTO\BookData;
use App\Livewire\IsbnBookLookup;
use Tests\TestCase;
use App\Interfaces\BookServiceInterface;
use Illuminate\Database\Eloquent\Attributes\Boot;
use Livewire\Livewire;

class BookLookupTest extends TestCase
{
    public function test_book_found_succesfully()
    {
        $book = new BookData(
            title: 'Test book',
            authors: ['Donato Russo'],
            publishedDate: '2026',
            description: 'Test description',
            isbn: '1234567890'
        );

        // Service mock
        $this->mock(BookServiceInterface::class, function ($mock) use ($book){
            $mock->shouldReceive('searchByIsbn')
                ->once()
                ->andReturn(
                    ApiResponse::success('Success', $book)
                );
        });

        Livewire::test(IsbnBookLookup::class)
            ->set('isbn','1234567890')
            ->call('lookup')
            ->assertSet('book.title','Test book')
            ->assertSet('book.authors', ['Donato Russo'])
            ->assertSet('error',null);

    }

    public function test_book_not_found()
    {
        $this->mock(BookServiceInterface::class, function($mock){
            $mock->shouldReceive('searchByIsbn')
            ->once()
            ->andReturn(
                ApiResponse::error('Book not found', null)
            );
        });

        Livewire::test(IsbnBookLookup::class)
            ->set('isbn','1122334455')
            ->call('lookup')
            ->assertSet('error','Book not found')
            ->assertSet('book', null);
    }

}
