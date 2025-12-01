<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Import Builder

/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property string $year
 * @property string $name
 * @property string|null $picture_file
 * @property string|null $summary
 * @property string|null $when
 * @property string|null $venue_name  <-- Used in the controller instead of 'where'
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Announcement newModelQuery()
 * @method static Builder|Announcement newQuery()
 * @method static Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static Announcement findOrFail(mixed $id) <-- Added for clarity
 * @method static Announcement find(mixed $id)       <-- Added for clarity
 * @mixin Builder
 */
class Announcement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year',
        'name',
        'picture_file',
        'summary',
        'when',
        'venue_name',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Ensure 'year' is treated as a string or integer if it represents a year value
        'year' => 'string',
        // 'when' likely stores a time or datetime string
        'when' => 'string',
    ];

    /**
     * Define the relationship to the Venue model (if applicable, e.g., if venue_name is a foreign key).
     * This relationship is speculative based on the controller usage of venue_name.
     * If 'venue_name' actually links to a 'title' column on the 'venues' table, this method would establish that link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_name', 'title');
    }
}
