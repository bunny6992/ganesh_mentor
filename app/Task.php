<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    
    protected $appends = ["open"];
 
    public function getOpenAttribute(){
        return true;
    }
}
