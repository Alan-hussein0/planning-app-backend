<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\LabelResource;
use App\Models\Label;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Validator;

class LabelController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

   
    public function store(Request $request, Task $task)
    {
        $errorMessage = [];
        if ($task->board->user_id !== Auth::user()->id) {
            return $this->sendError('unauthorized ', $errorMessage);
        }

        $input = $request->all();
        $validator = Validator::make($input,[
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validate error', $validator->errors());
        }
        
        if (Label::where('task_id',$task->id)->where('title','like',$request->title)->exists()) {
            return $this->sendError('the label Already selected');
        }
        $input['task_id'] = $task->id;
        $label = Label::create($input);

        return $this->sendResponse(['label' => new LabelResource($label)],'label has been created successfully!');
    }

    public function show(Task $task)
    {
        $errorMessage = [];
        if ($task->board->user_id !== Auth::user()->id) {
            return $this->sendError('unauthorized ', $errorMessage);
        }
        $label = $task->label;
        return $this->sendResponse(LabelResource::collection($label), 'Labels of the specific task has been retrieved successfully!');
    }

    public function update(Request $request, Label $label)
    {
        $errorMessage = [];
        if ($label->task->board->user_id !== Auth::user()->id) {
            return $this->sendError('unauthorized ', $errorMessage);
        }

        $input = $request->all();
        $validator = Validator::make($input,[
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validate error', $validator->errors());
        }
        
        if ($label->where('title','like',$request->title)->exists()) {
            return $this->sendError('the label Already have this title');
        }
        $label->title = $input['title'];
        $label->save();

        return $this->sendResponse(['label' => new LabelResource($label)],'label has been updated successfully!');
    }


    public function destroy(Label $label)
    {
        $errorMessage = [];
        if ($label->task->board->user_id !== Auth::user()->id) {
            return $this->sendError('unauthorized ', $errorMessage);
        }

        $label_cpy = $label;
        $label->delete();
        return $this->sendResponse(['label' => new LabelResource($label_cpy)],'label has been deleted successfully!');

    }
}
