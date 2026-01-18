<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
/**
 * App\Models\Member
 *
 * @property int $id
 * @property string $first_name
 * @property string $mid_name
 * @property string $last_name
 * @property string $rel_name
 * @property string $status
 * @property string $category
 * @property string $address
 * @property string $pri_phone
 * @property string $alt_phone
 * @property string $email
 * @property string $joined
 * @property string $adf
 * @property string $adf_join
 * @property string $adf_renew
 * @property int $user_id      <-- Explicitly needed by VoteController::index()
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Member where(string $column, string $operator = null, mixed $value = null)
 * @method static \Illuminate\Database\Eloquent\Collection|\App\Models\Member[] all($columns = ['*'])
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Member extends Model
{
    // Added Trait for factory functionality
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'mid_name',
        'last_name',
        'rel_name',
        'status',
        'category',
        'address',
        'pri_phone',
        'alt_phone',
        'email',
        'joined',
        'adf',
        'adf_join',
        'adf_renew',
        'user_id',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */

    /**
     * Get the user associated with the member.
     */
    public function user()
    {
        // If the ID is 0, we know there is no user, so don't even look.
        if ($this->user_id === 0) {
            return null;
        }

        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($member) {
            // If it's an empty string, dump and die to see the culprit
            if ($member->adf_join === '') {
                dd([
                    'message' => 'Caught the Bad Guy!',
                    'value' => $member->adf_join,
                    'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10)
                ]);
            }
        });
    }

    /**
     * Handle adf_join date conversion
     */
    protected function adfJoin(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->parseAdfDate($value),
        );
    }

    /**
     * Handle adf_renew date conversion
     */
    protected function adfRenew(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->parseAdfDate($value),
        );
    }

    /**
     * Internal helper to catch empty strings and format human dates for MySQL
     */
    private function parseAdfDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Carbon is smart enough to handle "20 December, 2026"
            // and "12/25/2025" automatically.
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            // If it's truly unparseable garbage, return null to avoid a crash
            return null;
        }
    }
}
