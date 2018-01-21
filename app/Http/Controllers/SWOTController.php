<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Swot as Swot;

class SWOTController extends Controller
{
	public function index () 
	{
		return view("SWOT.SWOTAnalysis");
	}

	public function save (Request $request) 
	{
		$retunData['success'] = false;
		$swot = $request->input('swot');
		if (!empty($swot['id'])) {
			$swotModel = SWOT::find($swot['id']);
		} else {
			$swotModel = new SWOT;
		}

		$validatedData = $request->validate([
	        'swot.title' => ['required']
	    ]);

		$swotModel->title = (isset($swot['title'])) ? $swot['title'] : '';
		$swotModel->strength = (isset($swot['strength'])) ? $swot['strength'] : '';
		$swotModel->weakness = (isset($swot['weakness'])) ? $swot['weakness'] : '';
		$swotModel->opportunities = (isset($swot['opportunities'])) ? $swot['opportunities'] : '';
		$swotModel->threats = (isset($swot['threats'])) ? $swot['threats'] : '';
		if ($swotModel->save()) {
			$retunData['success'] = true;
		}
		$retunData['swot'] = $swotModel;
		return response()->json($retunData);
	}

	public function retrieve ()
	{
		$retunData['swots'] = SWOT::all();
		return response()->json($retunData);
	}
}
