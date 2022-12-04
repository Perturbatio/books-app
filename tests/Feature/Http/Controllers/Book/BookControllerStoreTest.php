<?php

namespace Tests\Feature\Http\Controllers\Book;

use App\Http\Controllers\BookController;
use App\Http\Requests\CreateBookRequest;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @group Book
 *
 * @see \App\Http\Controllers\BookController
 */
class BookControllerStoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use AdditionalAssertions;

    public function testCreateRouteUsesFormRequest()
    {
        $this->assertRouteUsesFormRequest('books.store', CreateBookRequest::class);
    }

    public function testStoreMethodUsesFormRequest()
    {
        $this->assertActionUsesFormRequest(BookController::class, 'store', CreateBookRequest::class);
    }

    public function testThatBooksCanBeAdded()
    {
        $data = [
            'title' => ':TEST_TITLE:',
            'isbn' => '978-0-13-601970-1',
            'price' => 22.99,
        ];

        $response = $this->post('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);

        $book = Book::find($response->json('id'));
        $this->assertNotEmpty($book);
    }

    public function testThatBookWithDuplicateIsbnCannotBeAdded()
    {
        $data = [
            'title' => ':TEST_TITLE:',
            'isbn' => '978-0-13-601970-1',
            'price' => 22.99,
        ];
        $existingBook = Book::factory(1, $data);

        $response = $this->post('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);

        $book = Book::find($response->json('id'));
        $this->assertNotEmpty($book);
    }

    public function testCreateBookRequestValidation()
    {
        $data = [
            'title' => ':TEST_TITLE:',
            'isbn' => '978-0-13-601970-1',
            'price' => 22.99,
        ];

        $response = $this->post('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);
    }
}