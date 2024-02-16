<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => ['El correo o password es incorrecto.'],
            ]);
        }

        return response()->json([
            'message' => 'Autenticacion exitosa',
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // hack para validar automaticamente el usuario.
        $user->email_verified_at = new DateTime('now');

        $user->save();

        return response()->json([
            'message' => 'Usuario registrado exitosamente.'
        ]);
    }

    public function logout(Request $request)
    {
        // Eliminamos el token de acceso
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesion exitoso'
        ]);
    }
}
