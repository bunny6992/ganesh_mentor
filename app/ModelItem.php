<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelItem extends Model
{
    public function model()
    {
    	return $this->belongsTo('App\ProjectModel');
    }
}
