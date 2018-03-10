<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;

class TaskController extends Controller
{
    public function store(Request $request){
     
        $task = new Task();
        
        $task->user_id = $request->user_id;
        $task->project_id = $request->project_id;
        $task->text = $request->text;
        $task->type = $request->type;
        $task->start_date = $request->start_date;
        $task->end_date = $request->end_date;
        $task->duration = $request->duration;
        $task->progress = $request->progress;
        $task->resources = $request->resources;
        $task->priority = $request->priority;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "inserted",
            "tid" => $task->id
        ]);
    }
 
    public function update($id, Request $request){
        $task = Task::find($id);
 
        $task->text = $request->text;
        $task->type = $request->type;
        $task->start_date = $request->start_date;
        $task->end_date = $request->end_date;
        $task->duration = $request->duration;
        $task->progress = $request->progress;
        $task->resources = $request->resources;
        $task->priority = $request->priority;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "updated"
        ]);
    }
 
    public function destroy($id){
        $task = Task::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
}
