<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с заявками через API.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $message
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @package App
 */
class ApiRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'status',
        'message',
        'comment',
        'created_at',
        'updated_at'
    ];

    protected $table = 'api_requests';
}
