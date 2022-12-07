<?php

namespace Tests\Feature\Http\Requests\Book;

use App\Http\Requests\Book\CreateBookRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @group Requests
 * @group Book
 *
 * @see \App\Http\Requests\Book\CreateBookRequest
 */
class CreateBookRequestTest extends TestCase
{
    use WithFaker;
    use AdditionalAssertions;

    private CreateBookRequest $formRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formRequest = new CreateBookRequest();
    }

    // if the validation rules have changed, it's good to know this
    public function testValidationRulesHaveNotChanged()
    {
        $this->assertExactValidationRules([
            'isbn' => 'required|isbn|unique:books,isbn|regex:/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/',
            'title' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0|max:9999999999.99',
            'authors.*' => 'required|exists:authors,id',
            'categories.*' => 'required|exists:categories,id',
        ], $this->formRequest->rules());
    }

    public function testAuthorization()
    {
        // we don't use authentication for now so this should be authorized
        $this->assertTrue($this->formRequest->authorize());
    }

    /**
     * @dataProvider getValidationRuleData
     */
    public function testRules(array $data, bool $shouldPass, array $expected)
    {
        $validator = Validator::make($data, $this->formRequest->rules());
        $this->assertEquals($shouldPass, $validator->passes());
        if (! empty($expected)) {
            $this->assertEquals($expected, $validator->errors()->toArray());
        }
    }

    public function getValidationRuleData()
    {
        yield [ // valid result expected
            'data' => [
                'isbn' => '978-1491918661',
                'title' => ':TEST_TITLE:',
                'price' => '12.99',
            ],
            'shouldPass' => true,
            'expected' => [
            ],
        ];

        yield [ // invalid data provided
            'data' => [
                'isbn' => '978-INVALID-ISBN-1491905012',
                'title' => 123,
                'price' => '£12',
            ],
            'shouldPass' => false,
            'expected' => [
                'isbn' => [
                    'The isbn format is invalid.',
                ],
                'title' => [
                    'The title must be a string.',
                ],
                'price' => [
                    'The price must be a number.',
                    'The price format is invalid.',
                ],
            ],
        ];

        yield [ // no data
            'data' => [],
            'shouldPass' => false,
            'expected' => [
                'isbn' => [
                    'The isbn field is required.',
                ],
                'title' => [
                    'The title field is required.',
                ],
                'price' => [
                    'The price field is required.',
                ],
            ],
        ];

        yield [ // invalid price (too low)
            'data' => [
                'isbn' => '978-1491918661',
                'title' => ':TEST_TITLE:',
                'price' => '-12.99',
            ],
            'shouldPass' => false,
            'expected' => [
                'price' => [
                    'The price format is invalid.',
                    'The price must be at least 0.',
                ],
            ],
        ];

        yield [ // invalid price (too large)
            'data' => [
                'isbn' => '978-1491918661',
                'title' => ':TEST_TITLE:',
                'price' => PHP_INT_MAX,
            ],
            'shouldPass' => false,
            'expected' => [
                'price' => [
                    'The price must not be greater than 9999999999.99.',
                ],
            ],
        ];
    }
}
