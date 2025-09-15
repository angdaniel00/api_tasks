<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $keywords = Keyword::query()->where('removed', '=', false)->get();
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
     * @param Keyword $keyword
     * @return JsonResponse
     */
    public function show(Keyword $keyword)
    {
        $id = $keyword->id;
        $record = Keyword::where('id', '=', $id)->where('removed', '=', false)->firstOrFail();
        if ($record) {
            return response()->json(['success' => true, 'status' => 'success', 'value' => $record], 200);
        }
        return response()->json(['success' => false, 'status' => 'error', 'message' => 'Keyword no encontrada!'], 404);
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
        $keyword = new Keyword([
            'name' => $request->get('name'),
            'removed' => false,
        ]);

        $result = $keyword->save();
        if ($result) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Keyword guardada con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Error al guardar la nueva Keyword!',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Keyword $keyword
     * @return JsonResponse
     */
    public function update(Request $request, Keyword $keyword)
    {
        $request->validate([
            'name' => 'required',
        ]);
        if (Keyword::where('id','=', $keyword->id)->where('removed', '=', false)->exists()) {
            $record = Keyword::find($keyword->id);
            $record->name = $request->get('name');
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Keyword actualizada con éxito!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                "status" => "error",
                "message" => "Un error impidio actualizar la Keyword seleccionada!"
            ], 500);
        }
    }

    public function destroy(Keyword $keyword)
    {
        if(Keyword::where('id', $keyword->id)->exists()) {
            $record = Keyword::find($keyword->id);
            $record->removed = true;
            $record->save();
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Keyword eliminada con éxito!',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Un error impidio eliminar la Keyword seleccionada!',
            ], 404);
        }
    }
}
