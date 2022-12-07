<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\CreateBookRequest;
use App\Http\Requests\Book\IndexBooksRequest;
use App\Http\Resources\Book as BookResource;
use App\Http\Resources\BookCollection as BookResourceCollection;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @see \Tests\Feature\Http\Controllers\Book\
 */
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexBooksRequest $request)
    {
        $query = Book::query();

        //TODO: extract this to an action, needs to be generic enough to handle new filters in different formats
        $request->whenHas('filters.authorName', fn ($authorName) => $query->authorName($authorName));
        $request->whenHas('filters.categoryName', fn ($category) => $query->categoryName($category));

        return new BookResourceCollection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateBookRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function store(CreateBookRequest $request)
    {
        // TODO: extract to an action
        $book = new Book($request->only(['title', 'isbn', 'price']));
        $book->saveOrFail();

        $book->authors()->attach($request->get('authors'));
        $book->categories()->attach($request->get('categories'));

        return response(new BookResource($book), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return response(new BookResource($book), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
