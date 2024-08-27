<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['sometimes', 'boolean'], 
        ]);

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], $credentials['remember'] ?? false)) {
            $user = Auth::user();

            $token = $user->createToken('authToken')->plainTextToken;
            
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Vous êtes connecté',
            ]);
        } else {
            return response()->json([
                'errors' => 'L\'utilisateur n\'existe pas ou le mot de passe est incorrect',
            ], 401);
        }
    }

    /**
     * Déconnecter l'utilisateur actuel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Invalider le token de l'utilisateur connecté
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Déconnexion réussie',
        ]);
    }
}
