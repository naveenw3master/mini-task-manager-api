<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->latest()->get();

        return response()->json([
            'message' => 'Tasks fetched successfully',
            'data' => $tasks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'message' => 'Task created successfully',
            'data'    => $task,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $taskId)
    {
        $task = $request->user()->tasks()->findOrFail($taskId);

        return response()->json([
            'message' => 'Task fetched successfully',
            'data'    => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $taskId)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->findOrFail($taskId);
        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'data'    => $task,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($taskId)
    {
        $task = $request->user()->tasks()->findOrFail($taskId);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }

    /**
     * Mark the specified task as completed.
     */
    public function markAsCompleted(Request $request, $taskId)
    {
        $task = $request->user()->tasks()->findOrFail($taskId);
        $task->update(['status' => "completed"]);

        return response()->json([
            'message' => 'Task marked as completed successfully',
            'data'    => $task,
        ]);
    }
}
