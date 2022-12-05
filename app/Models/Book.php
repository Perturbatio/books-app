<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @see \Database\Factories\BookFactory
 */
class Book extends Model // implements FilterableModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'price',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany('Author');
    }

    public function scopeAuthorName(Builder $query, string $name): Builder
    {
        return $query->whereHas(
            'authors',
            fn (Builder $authorQuery) => $authorQuery->where('full_name', 'LIKE', "%$name%")
        );
    }
//
//    public function getAllowedFilters(): array
//    {
//        return [
//            'authorName',
//        ];
//    }
//
//    public function applyFilters(array $filters): Builder
//    {
//        // TODO: Implement applyFilters() method.
//    }
}
