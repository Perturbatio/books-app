<?php

namespace App\Http\Actions\Book;

use App\Models\Book;
use Illuminate\Support\Collection;

class IndexAction
{
    public function handle(array $filters): Collection
    {
        $query = Book::query();

        // key name will be the same as the scope name, so we can invoke it here
        collect($filters)
            ->each(fn ($value, $key) => $query->{$key}($value));

        return $query->get();
    }
}
