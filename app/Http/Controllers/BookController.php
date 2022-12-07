<?php

namespace App\Http\Controllers;

use App\Http\Actions\Book\IndexAction;
use App\Http\Actions\Book\StoreAction;
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
    public function index(IndexBooksRequest $request, IndexAction $action)
    {
        $filters = data_get($request->only(['filters.authorName', 'filters.categoryName']), 'filters', []);

        return new BookResourceCollection($action->handle($filters));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateBookRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function store(CreateBookRequest $request, StoreAction $action)
    {
        return response(new BookResource(
            $action->handle($request->only(['title', 'isbn', 'price', 'authors', 'categories']))
        ), Response::HTTP_CREATED);
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
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO
    }
}
