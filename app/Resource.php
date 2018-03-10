<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resources';

    protected $fillable = [
        'name', 'email', 'phone', 'groupName', 'user_id', 'project_id'
    ];
}
