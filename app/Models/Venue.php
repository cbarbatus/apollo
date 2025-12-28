<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Venue
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $address
 * @property string $map_link
 * @property string $directions
 * @property string $driving        <-- RE-ADDED: Required for AnnouncementController::elements
 * @property string $map            <-- RE-ADDED: Required for AnnouncementController::elements
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Venue query()
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \App\Models\Venue findOrFail(mixed $id)
 * @method static \App\Models\Venue find(mixed $id)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Venue extends Model
{
    protected $fillable = [
        'name',
        'title',
        'address',
        'map_link',    // Corrected from map_url
        'directions',
    ];

    public function ritual(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ritual::class);
    }
}
