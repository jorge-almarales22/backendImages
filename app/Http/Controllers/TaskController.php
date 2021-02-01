<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    
    public function index()
    {
        $tasks = Task::orderBy('id', 'DESC')->get();
        return $tasks;
    }

 
    public function store(Request $request)
    {
        $task = new Task();
        $task->name = $request->name;
        $task->content = $request->content;
        $task->url = $request->url;
        $task->save();
        return $task;
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        $task->name = $request->name;
        $task->content = $request->content;
        $task->url = $request->url;
        $task->save();
        return $task;
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
    }
}
