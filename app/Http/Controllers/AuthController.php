<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $user = User::where('email', '=',$request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response(
                ['details' => 'Credenciales incorrectos']
                , 401);
            }
            $token = $user->createToken('Task Access Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'Bearer ',

            ]);
        } catch (Exception $e) {
            return response([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Sesión terminada con éxito!'
        ]);
    }

    public function user(Request $request)
    {
        $id = $request->user()->id;
        $user = User::query()->where('id', '=', $id)->first();
        return response()->json($user);
    }
}
