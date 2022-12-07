<?php

namespace Tests\Http\Actions\Book;

use App\Http\Actions\Book\IndexAction;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexActionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function getFilterData()
    {
        yield [
            'filters' => ['authorName' => 'Test Author'],
            'expectedCount' => 1,
        ];

        yield [
            'filters' => ['authorName' => ':SHOULD NOT EXIST:'],
            'expectedCount' => 0,
        ];

        yield [
            'filters' => ['categoryName' => ':CATEGORY 2:'],
            'expectedCount' => 1,
        ];

        yield [
            'filters' => ['categoryName' => ':NON-EXISTENT CATEGORY:'],
            'expectedCount' => 0,
        ];

        yield [
            'filters' => [
                'authorName' => 'Test Author',
                'categoryName' => ':CATEGORY 1:',
            ],
            'expectedCount' => 1,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $author = Author::factory()->createOne(['full_name' => 'Test Author']);
        $otherAuthor = Author::factory()->createOne(['full_name' => 'Another author']);
        $categories = Category::factory()->createMany([
            ['name' => ':CATEGORY 1:'],
            ['name' => ':CATEGORY 2:'],
        ]);

        Book::factory()
            ->hasAttached([$author])
            ->hasAttached($categories->first())
            ->createOne(['title' => 'Book for test author']);

        Book::factory()
            ->hasAttached([$otherAuthor])
            ->hasAttached($categories->last())
            ->createOne(['title' => 'Book for another author']);
    }

    public function testHandle()
    {
        $action = new IndexAction();
        $res = $action->handle([]);
        $this->assertCount(2, $res);
    }

    /**
     * @dataProvider getFilterData
     */
    public function testFilters(array $filters, int $expectedCount)
    {
        $action = new IndexAction();

        $res = $action->handle($filters);

        $this->assertCount($expectedCount, $res);
    }
}
