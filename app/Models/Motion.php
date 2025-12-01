<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Motion
 *
 * @property int $id
 * @property string $status
 * @property int $member_id
 * @property string $item
 * @property string $motion_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name           <-- ADDED: Fixes the property.notFound error on line 207
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Motion where(string $column, string $operator = null, mixed $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Motion find(mixed $id)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Motion extends Model
{
    protected $fillable = ['member_id', 'item', 'motion_date'];
}
