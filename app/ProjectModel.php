<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    public function project()
    {
    	return $this->belongsTo('App\Project');
    }

    public function items()
    {
    	return $this->hasMany('App\ModelItem');
    }
}
