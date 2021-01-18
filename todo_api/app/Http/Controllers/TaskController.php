<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Validator;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // logic to get all tasks goes here
       $tasks = Task::get(['id', 'name']);
       return response($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // logic to create a task record goes here

        $rules=['name'=>['required','regex:/(^[a-zA-Z ]*$)/']];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return response()->json(["message" => "Task record created",
        "data"=>["name"=>$task->name]], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // logic to get a task record goes here
        if (Task::where('id', $id)->exists()) {
            $task = Task::where('id', $id)->get(['id', 'name']);
            return response($task, 200);
        } else {
            return response()->json(["message" => "Task not found"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // logic to update a task record goes here
         if (Task::where('id', $id)->exists()) {
            $task = Task::find($id);

            $task->name = is_null($request->name) ? $task->name : $request->name;
            $task->save();

            return response()->json(["message" => "records updated successfully",
            "data"=>["name"=>$task->name]], 200);
        } else {
            return response()->json(["message" => "Task not found"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // logic to delete a task record goes here
        if(Task::where('id', $id)->exists()) {
            $task = Task::find($id);
            $task->delete();

            return response()->json(["message" => "records deleted successfully",
            "data"=>["name"=>$task->name]], 202);
        } else {
            return response()->json(["message" => "Task not found"], 404);
        }
    }
}
