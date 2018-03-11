<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\ShareItem;

class GymingTools extends Controller
{
    public function index()
    {
    	return view("business-canvas.index");
    }

    public function shareProject(Request $request, $id)
    {
    	if (Auth::check()) {
		    $userId = Auth::user()->id;
	    	if ($userId == $id) {
	    		return redirect("/gyming-tools");
	    	}
		}

    	$request->session()->flush();
    	$receiver = ShareItem::where('receiver_user_id', $id)->first();

    	if (count($receiver) == 0) {
    		return "Invalid URL";
    	}

    	if ($receiver->receiver_registered) {
    		return redirect(route('login'));
    	} else {
    		return view('auth.register')->with('receiver', $receiver);
    	}
    }
}
