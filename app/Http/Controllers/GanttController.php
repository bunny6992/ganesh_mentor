<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Task;
use App\Link;

class GanttController extends Controller
{

    public function index()
    {
        $projectName = Input::get('projectName');

        $projectId = Input::get('projectId');
        
        $tasks = new Task;

        return view('gantt')
        ->with('taskData', $tasks->all())
        ->with('projectId', $projectId)
        ->with('projectName', $projectName);

    }

    public function get()
    {
        $userId = Input::get('userId');
        $projectId = Input::get('projectId');

        $tasks = new Task();
        $links = new Link();
        return response()->json([
            "data" => $tasks->where('user_id', '=', $userId)->where('project_id', '=', $projectId)->get(),
            "links" => $links->all()
        ]);
    }
}
