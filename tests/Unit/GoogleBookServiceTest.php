<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\support\Facades\Http;
use App\Services\GoogleBooksService;

class GoogleBookServiceTest extends TestCase
{
    /* Test if Http request works and return test data */
    public function test_return_valid_api_response()
    {
        Http::fake([
            '*' => Http::response([
                'items' => [
                    [
                        'volumeInfo' => [
                            'title' => 'Test',
                            'authors' => ['Donato Russo','John Doe'],
                            'publishedDate' => '2026',
                            'description' => 'Test description',
                            'isbn' => '1234567890'
                        ]
                    ]
                ]
            ], 200)

        ]);

        $service = new GoogleBooksService();

        $response = $service->searchByIsbn('1234567890');

        $this->assertNotNull($response);
        $this->assertTrue($response->isSuccess());

        $result = $response->data->toArray();

        $this->assertEquals('Test', $result['title']);
        $this->assertEquals(['Donato Russo','John Doe'], $result['authors']);
        $this->assertEquals('2026', $result['published_date']);
        $this->assertEquals('Test description', $result['description']);
        $this->assertEquals('1234567890', $result['isbn']);
    }
}

