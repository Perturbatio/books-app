<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @see \Tests\Feature\Http\Requests\Book\CreateBookRequestTest
 */
class CreateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'isbn' => 'required|isbn|unique:books,isbn',
            'title' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0|max:9999999999.99',
        ];
    }

    public function messages()
    {
        return [
            'isbn.isbn' => 'The ISBN you have provided is not a valid ISBN10 or ISBN13 format',
            'isbn.unique' => 'The ISBN you have provided is already in the database',
            'price.regex' => 'Please provide a valid currency amount to a maximum of two decimal places',
        ];
    }
}
