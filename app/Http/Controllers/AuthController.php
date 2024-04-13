<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $login_data = $request->validate([
            'email' => 'required|max:255',
            'password' => 'required|max:255',            
        ]);
        $user = User::where("email", $login_data['email'])->first();
        if (!$user || !Hash::check($login_data['password'], $user->password)) {
            return response()->json([
                "message" => "Invalid credentials"
            ], 401);
        }
        $token = $user->createToken($user->username)->plainTextToken;
        return [
            "message" => "Logged in successfully",
            "token" => $token
        ];
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return [
            "message" => "Logged out successfully"   
        ];
    }
}
