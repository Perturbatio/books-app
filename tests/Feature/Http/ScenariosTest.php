<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ScenariosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Given I  am an api consumer
     * When I  filter by author “Robin Nixon”
     * Then I  should receive a  200 response
     * And the body should contain two results
     * And the body should contain “978-1491918661”
     * And the body should contain “978-0596804848”
     */
    public function testAuthorRobinNixon()
    {
        $response = $this->get(route('books.index', ['filters' => ['authorName' => 'Robin Nixon']]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    ['isbn' => '978-1491918661'],
                    ['isbn' => '978-0596804848'],
                ],
            ]);
    }

    /**
     * Given I  am an api consumer
     * When I  filter by author "Christopher Negus"
     * Then I  should receive a  200 response
     * And the body should contain one result
     * And the body should contain "978-1118999875"
     */
    public function testAuthorChristopherNegus()
    {
        $response = $this->get(route('books.index', ['filters' => ['authorName' => 'Christopher Negus']]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    ['isbn' => '978-1118999875'],
                ],
            ]);
    }

    /**
     * Given I am an api consumer
     * When I query the api for a list of categories
     * Then I should receive a 200 response
     * And the body should contain three results
     * And the body should contain “PHP”
     * And the body should contain “Javascript”
     * And the body should contain “Linux
     */
    public function testCategoryIndex()
    {
        $response = $this->get(route('categories.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['name' => 'PHP'],
                    ['name' => 'Javascript'],
                    ['name' => 'Linux'],
                ],
            ]);
    }

    /**
     * Given I am an api consumer
     * When I filter (books) by category “Linux”
     * Then I should receive a  200 response
     * And the body should contain two results
     * And the body should contain "978-0596804848"
     * And the body should contain "978-1118999875"
     */
    public function testCategoryLinux()
    {
        $response = $this->get(route('books.index', ['filters' => ['categoryName' => 'Linux']]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    ['isbn' => '978-0596804848'],
                    ['isbn' => '978-1118999875'],
                ],
            ]);
    }

    /**
     * Given I am an api consumer
     * When I filter by category "PHP"
     * Then I should receive a 200 response
     * And the body should contain one result
     * And the body should contain "978-1491918661"
     */
    public function testCategoryPhp()
    {
        $response = $this->get(route('books.index', ['filters' => ['categoryName' => 'PHP']]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    ['isbn' => '978-1491918661'],
                ],
            ]);
    }

    /**
     * Given I am an api consumer
     * When I filter by author “Robin Nixon”
     * And I filter by category “Linux”
     * Then I should receive a  200 response
     * And the body should contain one result
     * And the body should contain “978-0596804848”
     */
    public function testCategoryLinuxAuthorRobinNixon()
    {
        $response = $this->get(route('books.index', [
            'filters' => [
                'categoryName' => 'Linux',
                'authorName' => 'Robin Nixon',
            ],
        ]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    ['isbn' => '978-0596804848'],
                ],
            ]);
    }

    public function testCreateBook()
    {
        // create the author
        $authorName = 'Josh Lockhart';
        $authorResponse = $this->postJson(route('authors.store', [
            'full_name' => $authorName,
        ]));

        $isbn = '978-1491905012';
        $title = 'Modern PHP: New Features and Good Practices';
        $price = '18.99';

        $response = $this->postJson(route('books.store', [
            'isbn' => $isbn,
            'title' => $title,
            'price' => $price,
            'authors' => [
                $authorResponse->json('id'),
            ],
        ]));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'isbn' => $isbn,
                'title' => $title,
                'price' => $price,
                'authors' => [
                    ['full_name' => $authorName],
                ],
            ]);
    }

    /**
     * Given I am an api consumer
     * When I create a book with an invalid ISBN
     * I should receive a 4(xx) response
     * The body should contain "Invalid ISBN"
     */
    public function testCreateInvalidBook()
    {
        // create the author
        $authorName = 'Josh Lockhart';
        $authorResponse = $this->postJson(route('authors.store', [
            'full_name' => $authorName,
        ]));

        $isbn = '978-INVALID-ISBN-1491905012';
        $title = 'Modern PHP: New Features and Good Practices';
        $price = '18.99';

        $response = $this->postJson(route('books.store', [
            'isbn' => $isbn,
            'title' => $title,
            'price' => $price,
            'authors' => [
                $authorResponse->json('id'),
            ],
        ]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    'isbn' => [
                        'Invalid ISBN: The ISBN must contain only numbers or hyphens.',
                    ],
                ],
            ]);
    }
}
