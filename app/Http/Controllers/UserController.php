<?php

namespace App\Http\Controllers;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $keywords = User::with(['team', 'tasks'])->where('removed', '=', false)
            ->get(["id", "name", "email", "is_superuser", "team_id"]);
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
            'message' => 'No hay registro de Usuarios!'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        $id = $user->id;
        $record = User::select(["id", "name", "email", "is_superuser", "team_id"])->with(['team', 'tasks'])
            ->where('id', '=', $id)->where('removed', '=', false)->firstOrFail();
        if ($record) {
            return response()->json(['success' => true, 'status' => 'success', 'value' => $record], 200);
        }
        return response()->json(['success' => false, 'status' => 'error', 'message' => 'Usuario no encontrado!'], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'is_superuser' => false,
            'removed' => false,
        ]);

        $result = $user->save();
        if ($result) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Usuario guardado con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Error al guardar el nuevo Usuario!',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if (User::where('id','=', $user->id)->where('removed', '=', false)->exists()) {
            $record = User::find($user->id);
            $record->name = $request->get('name');
            $record->email = $request->get('email');
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Usuario actualizado con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar el Usuario seleccionado!"
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        if(User::where('id', $user->id)->exists()) {
            $record = User::find($user->id);
            $record->removed = true;
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Usuario eliminado con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Un error impidió eliminar el Usuario seleccionado!',
            ], 404);
        }
    }

    public function changePassword(Request $request)
    {
        if(User::where('id', $request->get('id'))->where('deleted', '=', false)->exists()) {
            $record = User::find($request->get('id'));
            $record->password = bcrypt($request->get('password'));
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Contraseña de usuario cambiada con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Un error impidió cambiar la contraseña del Usuario seleccionado!',
            ], 404);
        }
    }
}
