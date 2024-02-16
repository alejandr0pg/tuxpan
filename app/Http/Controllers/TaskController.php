<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::all();

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'assigned_at' => 'required',
        ]);

        // Validamos que el usuario asignado exista
        $userAssigned = User::find($request->assigned_at);

        if (!$userAssigned) {
            return response()->json([
                'message' => 'Error: usuario asignado inexistente'
            ]);
        }

        $task = new Task();
        $task->name = $request->title;
        $task->description = $request->description;
        $task->assigned_at = $request->assigned_at;
        $task->created_by = $request->user()->id;
        $task->save();

        return response()->json([
            'message' => 'Task creada exitosamente.',
            'data' => $task
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json([
            'data' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Validamos que el usuario asignado exista
        $userAssigned = User::find($request->assigned_at);

        if (!$userAssigned) {
            return response()->json([
                'message' => 'Error: usuario asignado inexistente'
            ]);
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Task modificada exitosamente.',
            'data' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Eliminamos la task
        $task->delete();

        return response()->json([
            'message' => 'Task eliminada exitosamente.'
        ]);
    }

    /**
     * Assing
     */
    public function assigneTo(Request $request, Task $task)
    {
        $request->validate([
            'assigned_at' => 'required',
        ]);

        // Validamos que el usuario asignado exista
        $userAssigned = User::find($request->assigned_at);

        if (!$userAssigned) {
            return response()->json([
                'message' => 'Error: usuario asignado inexistente'
            ]);
        }

        $task->assigned_at = $request->assigned_at;
        $task->save();

        return response()->json([
            'message' => 'Task asignada exitosamente.',
            'data' => $task
        ]);
    }

    public function comment(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment();
        $comment->task_id = $task->id;
        $comment->comment = $request->comment;
        $comment->user_id = $request->user()->id;
        $comment->save();

        return response()->json([
            'message' => 'Comentario creado exitosamente.',
            'data' => $comment
        ]);
    }
}
