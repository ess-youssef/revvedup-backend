<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UsersController extends Controller
{
    public function register(Request $request) {
        $userData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|max:255',            
        ]);
        User::create($userData);
        return response()->json([
            "message" => "registration successful",
        ], 201);
    }

    public function show(User $user) {
        return $user;
    }
    
    public function edit(Request $request, User $user) {
        if (auth()->user()->id != $user->id){
            abort(403, "You are not allowed to edit other users");
        }  
        $userData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'username' => 'required|unique:users|max:255',
            'bio' => 'max:255',            
        ]);
        $user->update($userData);
        return $user;
    }
}
