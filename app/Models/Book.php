<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * This is crucial for protecting against mass assignment vulnerabilities
     * when using methods like Book::create($request->all()).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'link',
        'pix',
        'remarks',
        'sequence',
    ];

    /**
     * The attributes that should be cast.
     *
     * Ensures that the 'sequence' field, which is used for ordering, is always
     * treated as an integer type.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sequence' => 'integer',
    ];
}
