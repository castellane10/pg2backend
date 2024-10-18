<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Requests;
use Illuminate\Support\Facades\Validator;

class requestController extends Controller
{
    public function index() {
        $requests = Requests::all();

        return $requests->isNotEmpty()
            ? response()->json($requests, 200)
            : response()->json(['message' => 'No se encontraron solicitudes'], 404);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'descripcion' => 'required',
            'estado' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $requestData = $request->only(['titulo', 'descripcion', 'estado']);
        $newRequest = Requests::create($requestData);

        if (!$newRequest) {
            return response()->json(['message' => 'Error al crear la solicitud'], 500);
        }

        return response()->json([
            'request' => $newRequest,
            'status' => 201
        ], 201);
    }

    public function show($id) {
        $requests = Requests::find($id);

        if(!$requests){
            return response()->json(['message' => 'Request not found'], 404);
        }
        return response()->json($requests, 200);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }
    
        $requestModel = Requests::findOrFail($id);
    
        $requestModel->estado = $request->input('estado');
        $requestModel->save();
    
        return response()->json([
            'message' => 'Estado actualizado exitosamente',
            'status' => 200
        ], 200);
    }    


}
