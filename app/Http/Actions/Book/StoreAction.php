<?php

namespace App\Http\Actions\Book;

use App\Models\Book;

class StoreAction
{
    public function handle(array $data): Book
    {
        $book = new Book([
            'title' => $data['title'],
            'isbn' => $data['isbn'],
            'price' => $data['price'],
        ]);

        $book->saveOrFail();

        $book->authors()->attach($data['authors']);
        $book->categories()->attach($data['categories']);

        return $book;
    }
}
