<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Task::query();

            $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

            return TaskResource::collection($tasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch tasks', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     *  Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create task', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task not found', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $task->update($request->validated());
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update task', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete task', 'error' => $e->getMessage()], 500);
        }
    }
}
