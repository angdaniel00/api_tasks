<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskKeyword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $tasks = Task::with('user', 'keywords')->where('removed', '=', false);
        $user = $request->user();
        if(!$user->is_superuser){
            $tasks->where('user_id', '=', $user->id);
        }
        if ($tasks) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'value' => $tasks->get(),
            ], 200);
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'No hay registro de Tareas!'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task)
    {
        $id = $task->id;
        $record = Task::with('user', 'keywords')->where('id', '=', $id)->where('removed', '=', false)->firstOrFail();
        if ($record) {
            return response()->json(['success' => true, 'status' => 'success', 'value' => $record], 200);
        }
        return response()->json(['success' => false, 'status' => 'error', 'message' => 'Tarea no encontrado!'], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $team = new Task([
            'title' => $request->get('title'),
            'is_done' => $request->get('is_done', false),
            'user_id' => $request->get('user', $request->user()->id),
            'removed' => false,
        ]);

        $result = $team->save();
        if ($result) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Tarea guardada con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Error al guardar la nueva Tarea!',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        if (Task::where('id','=', $task->id)->where('removed', '=', false)->exists()) {
            $record = Task::find($task->id);
            $record->title = $request->get('title');
            $record->is_done = $request->get('is_done', false);
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Tarea actualizada con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar la Tarea seleccionada!"
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        if(Task::where('id', $task->id)->exists()) {
            $record = Task::find($task->id);
            $record->removed = true;
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Tarea eliminada con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Un error impidio eliminar el Equipo seleccionado!',
            ], 404);
        }
    }

    public function addKeyword(Request $request)
    {
        if (Task::where('id','=', $request->get('id'))->where('removed', '=', false)->exists()) {
            $record = Task::find($request->get('id'));
            $record->keywords()->attach([$request->get('keyword')]);
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Keyowrd añadida a Tarea con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar la Tarea seleccionada!"
            ], 500);
        }
    }

    public function deleteKeyword(Request $request)
    {
        if (Task::where('id','=', $request->get('id'))->where('removed', '=', false)->exists()) {
            $record = Task::find($request->get('id'));
            $record->keywords()->detach([$request->get('keyword')]);
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Keyowrd añadida a Tarea con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar la Tarea seleccionada!"
            ], 500);
        }
    }

    public function getDoneTask(Request $request){
        $task = Task::findOrFail($request->get('id'));
        $task->is_done = !$task->is_done;
        $task->save();
        return response([
            "success" => true,
            "value" => $task
        ], 200);
    }
}
