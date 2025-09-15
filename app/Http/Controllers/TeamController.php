<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $keywords = Team::query()->where('removed', '=', false)->get();
        if ($keywords) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'value' => $keywords,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'No hay registro de Keywords!'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function show(Team $team)
    {
        $id = $team->id;
        $record = Team::where('id', '=', $id)->where('removed', '=', false)->firstOrFail();
        if ($record) {
            return response()->json(['success' => true, 'status' => 'success', 'value' => $record], 200);
        }
        return response()->json(['success' => false, 'status' => 'error', 'message' => 'Equipo no encontrado!'], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $team = new Team([
            'name' => $request->get('name'),
            'removed' => false,
        ]);

        $result = $team->save();
        if ($result) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Equipo guardado con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Error al guardar el nuevo Equipo!',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Team $team
     * @return JsonResponse
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required',
        ]);
        if (Team::where('id','=', $team->id)->where('removed', '=', false)->exists()) {
            $record = Team::find($team->id);
            $record->name = $request->get('name');
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Equipo actualizado con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar el Equipo seleccionado!"
            ], 500);
        }
    }

    public function destroy(Team $team)
    {
        if(Team::where('id', $team->id)->exists()) {
            $record = Team::find($team->id);
            $record->removed = true;
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Equipo eliminado con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Un error impidio eliminar el Equipo seleccionado!',
            ], 404);
        }
    }

    public function addUser(Request $request)
    {
        if (Team::where('id','=', $request->get('id'))->where('removed', '=', false)->exists() &&
            User::where('id','=', $request->get('id'))->where('removed', '=', false)->exists()) {
            $record = User::find($request->get('user'));
            $record->team_id = $request->get('team', null);
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Usuario añadido al Equipo con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar el Equipo seleccionada!"
            ], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        if (Team::where('id','=', $request->get('id'))->where('removed', '=', false)->exists() &&
            User::where('id','=', $request->get('id'))->where('removed', '=', false)->exists()) {
            $record = User::find($request->get('user'));
            $record->team_id = null;
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Usuario eliminado del Equipo con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar el Equipo seleccionada!"
            ], 500);
        }
    }
}
