<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Section
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $sequence
 * @property int $showit
 * @property string $slug           <-- ADDED: Required as it's in $fillable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder|Section findOrFail(mixed $id)
 * @method static \Illuminate\Database\Eloquent\Builder|Section orderBy(string $column, string $direction = 'asc')
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'title', 'sequence', 'item', 'sequence'];

    public function elements(): HasMany
    {
        return $this->hasMany(\App\Models\Element::class);
    }
}
