<?php

use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('tasks', [TasksController::class, "index"]);
Route::post('tasks', [TasksController::class, "store"]);
Route::put('tasks/{id}', [TasksController::class, "update"]);
Route::delete('tasks/{id}', [TasksController::class, "delete"]);
