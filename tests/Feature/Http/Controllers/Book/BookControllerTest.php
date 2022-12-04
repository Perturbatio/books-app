<?php

namespace Tests\Feature\Http\Controllers\Book;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @group Book
 *
 * @see \App\Http\Controllers\BookController
 */
class BookControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testThatBooksCanBeIndexed()
    {
        Book::factory(10)->create();

        $response = $this->get('/api/books');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => []])
            ->assertJsonCount(10, 'data');
    }

    public function testThatABookCanBeRetrievedById()
    {
        $author = Author::factory()->createOne(['full_name' => 'Test Author']);
        $book = Book::factory()
            ->hasAttached([$author])
            ->createOne();

        $response = $this->get('/api/books/' . $book->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $book->id,
                'isbn' => $book->isbn,
                'title' => $book->title,
                'price' => $book->price,
                'authors' => [
                    [
                        'id' => $author->id,
                        'full_name' => $author->full_name
                    ]
                ]
            ]);
    }
}
