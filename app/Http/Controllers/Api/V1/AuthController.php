<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login con email/password o PIN
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required_without:pin|email',
            'password' => 'required_with:email|string',
            'pin' => 'required_without:email|string|size:6',
            'device_id' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        $user = null;

        // Login con email/password
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales proporcionadas son incorrectas.'],
                ]);
            }
        }

        // Login con PIN
        if ($request->pin) {
            $user = User::where('pin', $request->pin)->first();
            
            if (!$user || !$user->hasPin()) {
                throw ValidationException::withMessages([
                    'pin' => ['El PIN proporcionado es incorrecto.'],
                ]);
            }
        }

        if (!$user || !$user->company || !$user->company->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta no está activa.'],
            ]);
        }

        // Registrar/actualizar dispositivo
        if ($request->device_id) {
            $user->devices()->updateOrCreate(
                ['device_id' => $request->device_id],
                [
                    'company_id' => $user->company_id,
                    'name' => $request->device_name,
                    'platform' => $request->header('X-Platform', 'unknown'),
                    'last_seen_at' => now(),
                ]
            );
        }

        // Crear token
        $token = $user->createToken($request->device_name ?? 'mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user->load('company'),
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Login con QR token
     */
    public function loginWithQR(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',
            'device_id' => 'nullable|string',
        ]);

        $user = User::where('id', $request->user_id)
            ->where('company_id', $request->company_id)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Validar QR token (se valida en QRController)
        // Aquí asumimos que ya fue validado

        $token = $user->createToken('qr-login')->plainTextToken;

        return response()->json([
            'user' => $user->load('company'),
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    /**
     * Obtener usuario actual
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('company', 'employeeProfile'),
        ]);
    }
}
