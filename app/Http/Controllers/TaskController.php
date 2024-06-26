<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }

    public function index()
    {
        if (Auth::user()->tasks)
            $tasks = Auth::user()->tasks;
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->only(['title', 'description', 'status']));

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        return response()->json($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $request->validated($request->only(['title', 'description', 'status']));
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }

    public function viewUserTasks($userId)
    {
        // Admins can only view all tasks of particular user.
        if (Auth::user()->role->name !== 'admin') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $tasks = Task::where('user_id', $userId)->get();
        return response()->json($tasks);
    }
}
