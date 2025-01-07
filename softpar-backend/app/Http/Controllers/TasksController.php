<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function index(){
        return Tasks::all();
    }

    public function store(Request $req){
        Tasks::create([
            "title" => $req->title,
            "description" => $req->description,
            "time_creation" => $req->time_creation,
            "time_conclusion" => $req->time_conclusion,
            "status" => $req->status,
            "parent_task_id" => $req->parent_task_id,
        ]);

        return response(["ok", 200]);
    }

    public function update(Request $req){
        $task = Tasks::find($req->id);

        $task->title = $req->title;
        $task->description = $req->description;
        $task->time_creation = $req->time_creation;
        $task->time_conclusion = $req->time_conclusion;
        $task->status = $req->status;
        $task->parent_task_id = $req->parent_task_id;

        $task->save();

        return (["Alteração feita!", 200]);
    }

    public function delete(Request $req){
        $produto = Tasks::find($req->id);

        $produto->delete();

        return response("Task removida", 200);
    }
}
