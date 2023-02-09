<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request) {
        $validacion = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users|email:rfc,dns',
            'password' => 'required|string|min:8',
        ],
        [
            'name.required' => 'El campo :attribute es obligatorio.',
            'name.string' => 'El campo :attribute debe ser una cadena de texto.',
            'email.required' => 'El campo :attribute es obligatorio.',
            'email.string' => 'El campo :attribute debe ser una cadena de texto.',
            'email.unique' => 'El campo :attribute ya está en uso.',
            'email.email' => 'El campo :attribute debe ser un correo electrónico válido.',
        ]);

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Error en las validaciones.',
                'errors' => $validacion->errors(),
                'data' => []
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Usuario registrado correctamente.',
                'errors' => [],
                'data' => $user
            ], 200);
        } 
        
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Error al registrar el usuario.',
                'errors' => [],
                'data' => []
            ], 400);
        }
    }

    public function login(Request $request) {
        $validacion = Validator::make($request->all(), [
            'email' => 'required|string|email:rfc,dns',
            'password' => 'required|string|min:8',
        ],
        [
            'email.required' => 'El campo :attribute es obligatorio.',
            'email.string' => 'El campo :attribute debe ser una cadena de texto.',
            'email.email' => 'El campo :attribute debe ser un correo electrónico válido.',
            'password.required' => 'El campo :attribute es obligatorio.',
            'password.string' => 'El campo :attribute debe ser una cadena de texto.',
            'password.min' => 'El campo :attribute debe tener al menos :min caracteres.',
        ]);

        if ($validacion->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Error en las validaciones.',
                'errors' => $validacion->errors(),
                'data' => []
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 400,
                'message' => 'Credenciales de usuario incorrectas.',
                'errors' => [],
                'data' => []
            ], 400);
        }
        
        else {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'Usuario autenticado correctamente.',
                'errors' => [],
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
        }
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Sesión cerrada correctamente.',
            'errors' => [],
            'data' => []
        ], 200);
    }
}
