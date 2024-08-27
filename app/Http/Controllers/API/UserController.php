<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /* 
    3 utilisateurs random
     */
    public function getRandomUsers()
    {
        $users = User::inRandomOrder()->take(3)->get();
        return response()->json($users);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); 
        return response()->json($users);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pseudo' => 'required|min:5|max:20|unique:users',
            'email' => 'required|email|max:50|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        $user = User::create([
            'pseudo' => $request->pseudo,
            'email' => $request->email,
            'image' => $request->image,
            'password' => Hash::make($request->password),
        ]);

        // Génération du token pour l'utilisateur inscrit
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'pseudo' => 'sometimes|required|string|max:255|unique:users,pseudo,' . $user->id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        if ($request->has('pseudo')) {
            $user->pseudo = $validated['pseudo'];
        }

        if ($request->has('email')) {
            $user->email = $validated['email'];
        }

        if ($request->has('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Utilisateur modifié avec succès',
            'user' => $user
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
