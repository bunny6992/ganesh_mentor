<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Project;
use App\User;
use App\ShareItem;
use App\ProjectModel;
use App\ModelItem;
use Mail;

class GymingTools extends Controller
{
    public function addNewProject(Request $request)
    {
   
    	$project = new Project;
    	$project->name = $request->input('name');
    	$project->user_id = Auth::user()->id;
    	$project->save();

    	$data['success'] = true;
    	$data['project'] = $project;
    	return response()->json($data);
    }

    public function addNewProjectModel(Request $request)
    {
   
    	$project = new ProjectModel;
    	$project->name = $request->input('name');
    	$project->project_id = $request->input('projectId');
    	$project->type = $request->input('type');
    	$project->save();

    	$data['success'] = true;
    	$data['model'] = $project;
    	return response()->json($data);
    }

    public function addNewModelItem(Request $request)
    {
    	$item = new ModelItem;
    	$item->title = $request->input('title');
    	$item->body = $request->input('body');
    	$item->model_id = $request->input('modelId');
    	$item->type = $request->input('type');
    	$item->save();

    	$data['success'] = true;
    	$data['item'] = $item;
    	return response()->json($data);
    }

    public function updateModelItem(Request $request)
    {
    	$data['success'] = false;
    	$item = ModelItem::find($request->input('modelId'));
    	if ($item) {
    		$item->type = $request->input('type');
    		$item->save();
    		$data['success'] = true;
    		$data['item'] = $item;
    	}

    	return response()->json($data);
    }

    public function getProjects(Request $request)
    {	
    	$projects = Project::where(
			    'user_id', Auth::user()->id
			)->get();
    	$data = [];
    	foreach ($projects as $project) {
    		$items = ProjectModel::where('project_id', $project->id)->get();
    		$data[] = [
    			'name'   => $project->name,
    			'id'     => $project->id,
    			'models' => $items
    		];
    	}
    	return response()->json($data);
    }

    public function getCanvas(Request $request)
    {	
    	$items = ModelItem::where('model_id', $request->input('id'))->get();
    	return response()->json($items);
    }

    public function getSWOTProjects(Request $request)
    {	
    	$projects = Project::where([
			    'user_id', '=', Auth::user()->id
			])->get();
    	$data = [];
    	foreach ($projects as $project) {
    		$items = ProjectModel::where('project_id', $project->id)->get();
    		$data[] = [
    			'name'   => $project->name,
    			'id'     => $project->id,
    			'models' => $items
    		];
    	}
    	return response()->json($data);
    }

    public function saveModelItem(Request $request)
    {	
    	$modelItem = ModelItem::find($request->input('id'));
    	$data['success'] = false;
    	if ($modelItem) {
    		$modelItem->title = $request->input('title');
    		$modelItem->body = $request->input('body');
    		$modelItem->save();
    		$data['success'] = true;
    	}

    	return response()->json($data);
    }
    
    public function deleteItem(Request $request)
    {	
    	$modelItem = ModelItem::find($request->input('id'));
    	$data['success'] = false;
    	if ($modelItem) {
    		$modelItem->delete();
    		$data['success'] = true;
    	}

    	return response()->json($data);
    }

    public function shareProject(Request $request)
    {

       $shareProject = $request->input('shareProject');
       if (count($shareProject['models']) == 0 ) {
            return 'No items selected to share.';
       }
       // $models = $request->input('models');
       // $projectId = $request->input('id');

       foreach ($shareProject['emails'] as $email) {
           $user = User::where('email', $email)->first();
           $senderId = Auth::user()->id;
           if (count($user) > 0) {
                $data = 'user exists';
                $receiverId = $user->id;
                $receiverRegistered = 1;
           } else {
                $data = "user doesn't exist";
                $receiverId = time();
                $receiverRegistered = 0;
           }

           foreach ($shareProject['models'] as $model) {
               $shareModel = new ShareItem;
               $shareModel->sender_user_id = $senderId;
               $shareModel->receiver_user_id = $receiverId;
               $shareModel->model_item_id = $model['id'];
               $shareModel->project_id = $shareProject['id'];
               $shareModel->receiver_registered = $receiverRegistered;
               $shareModel->receiver_email = $email['value'];
               $shareModel->can_write = $model['permission'];
               $shareModel->save();
           }

       }
        // Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message)
        // {
        //     $message->subject('Mailgun and Laravel are awesome!');
        //     $message->from('no-reply@website_name.com', 'Website Name');
        //     $message->to('bunny6992+62@gmail.com');
        // });
       return 'Shared sucessfully';
    }
}
