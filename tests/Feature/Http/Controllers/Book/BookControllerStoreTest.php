<?php

namespace Tests\Feature\Http\Controllers\Book;

use App\Http\Controllers\BookController;
use App\Http\Requests\Book\CreateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
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
        $author = Author::factory()->createOne();
        $category = Category::factory()->createOne();

        $data = [
            'title' => ':TEST_TITLE:',
            'isbn' => '978-0-13-601970-1',
            'price' => 22.99,
            'authors' => [$author->id],
            'categories' => [$category->id],
        ];

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'title' => ':TEST_TITLE:',
                'isbn' => '978-0-13-601970-1',
                'price' => 22.99,
                'authors' => [
                    [
                        'id' => $author->id,
                        'full_name' => $author->full_name,
                    ],
                ],
                'categories' => [
                    [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                ],
            ]);

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

        Book::factory()
            ->has(Author::factory()->count(1))
            ->createOne($data);

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    'isbn' => [
                        'The ISBN you have provided is already in the database.',
                    ],
                ],
            ]);
    }

    public function testCreateBookRequestValidation()
    {
        $data = [
            'title' => ':TEST_TITLE:',
            'isbn' => '978-0-13-601970-1',
            'price' => 22.99,
        ];

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);
    }
}
