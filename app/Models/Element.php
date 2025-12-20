<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Element
 *
 * @property int $id
 * @property int $section_id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $item
 * @property int $sequence
 * @property string $when_replied
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Element extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'name', 'slug', 'title', 'item', 'sequence'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Section::class);
    }
}
