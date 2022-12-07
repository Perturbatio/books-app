<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @see \Tests\Feature\Http\Requests\Book\CreateBookRequestTest
 * @see \App\Http\Controllers\BookController
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
            // the intervention ISBN validator strips out invalid chars before checking,
            // so a regex here helps ensure that there are no stray characters before validating fully
            'isbn' => 'required|isbn|unique:books,isbn|regex:/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/',
            'title' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0|max:9999999999.99',
            'authors.*' => 'required|exists:authors,id',
            'categories.*' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'isbn.regex' => 'Invalid ISBN: The ISBN must contain only numbers or hyphens.',
            'isbn.unique' => 'The ISBN you have provided is already in the database.',
            'price.regex' => 'Please provide a valid currency amount to a maximum of two decimal places.',
        ];
    }
}
