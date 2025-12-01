<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Slideshow
 *
 * @property int $id
 * @property string $year
 * @property string $name
 * @property string $title
 * @property string $google_id
 * @property string $sequence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Slideshow newModelQuery()
 * @method static Builder|Slideshow newQuery()
 * @method static Builder|Slideshow query()
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static Slideshow findOrFail(mixed $id)
 * @method static Slideshow find(mixed $id)
 * @mixin Builder
 */

class Slideshow extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year',
        'name',
        'title',
        'google_id',
        'sequence',
    ];
}
