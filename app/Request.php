<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
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
