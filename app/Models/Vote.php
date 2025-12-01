<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vote
 *
 * @property int $id
 * @property int $member_id
 * @property int $motion_id    <-- Added: Based on controller usage
 * @property string $vote       <-- Added: Based on controller usage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Vote extends Model
{
    // These fillable properties ensure mass assignment security and reflect usage in the controller.
    protected $fillable = ['member_id', 'motion_id', 'vote'];
}
