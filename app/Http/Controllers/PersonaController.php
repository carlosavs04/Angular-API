<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    public function create(Request $request)
    {
        $validacion = Validator::make ($request->all(), [
            'nombre' => 'required|string|max:40',
            'apellido' => 'required|string|max:40',
            'email' => 'required|string|email:rfc,dns|max:80|unique:personas',
            'edad' => 'required|integer',
        ],
        [
            'nombre.required' => 'El campo :attribute es obligatorio.',
            'nombre.string' => 'El campo :attribute debe ser una cadena de texto.',
            'nombre.max' => 'El campo :attribute debe tener un máximo de :max caracteres.',
            'apellido.required' => 'El campo :attribute es obligatorio.',
            'apellido.string' => 'El campo :attribute debe ser una cadena de texto.',
            'apellido.max' => 'El campo :attribute debe tener un máximo de :max caracteres.',
            'email.required' => 'El campo :attribute es obligatorio.',
            'email.string' => 'El campo :attribute debe ser una cadena de texto.',
            'email.email' => 'El campo :attribute debe ser un correo electrónico válido.',
            'email.max' => 'El campo :attribute debe tener un máximo de :max caracteres.',
            'email.unique' => 'El campo :attribute no puede repetirse, debe ser único.',
        ]);

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Error en las validaciones',
                'errors' => $validacion->errors(),
                'data' => []
            ], 400);
        }

        $persona = Persona::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'edad' => $request->edad,
        ]);

        if($persona->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Datos almacenados exitosamente',
                'errors' => [],
                'data' => $persona
            ], 200);
        }

        else {
            return response()->json([
                'status' => 400,
                'message' => 'Error al almacenar los datos',
                'errors' => [],
                'data' => []
            ], 400);
        }
    }

    public function showPersons() {
        return Persona::all();
    }
}
