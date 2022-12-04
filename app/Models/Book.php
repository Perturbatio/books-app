<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'price',
    ];

//    public function authors(): BelongsToMany
//    {
//        return $this->belongsToMany(Author::class);
//    }
}
