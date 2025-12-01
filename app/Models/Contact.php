<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Contact
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property string $status
 * @property string $when_replied
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|static where(string $column, string $operator = null, mixed $value = null) <-- FIX: Added static return type
 * @method static \App\Models\Contact|null first($columns = ['*'])
 * @method static \App\Models\Contact findOrFail(mixed $id)
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> orderBy(string $column, string $direction = 'asc')
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'message',
        'status',
        'when_replied',
    ];
}
