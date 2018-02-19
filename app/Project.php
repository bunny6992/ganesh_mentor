<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function models()
    {
    	return $this->hasMany('App\ModelItem');
    }
}
