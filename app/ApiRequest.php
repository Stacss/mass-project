<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'status',
        'message',
        'comment',
        'created_at',
        'updated_at'];
}
