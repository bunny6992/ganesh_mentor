<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessCanvas extends Controller
{
    public function index()
    {
    	return view("business-canvas.index");
    }

}
