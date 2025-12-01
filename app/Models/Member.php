<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
