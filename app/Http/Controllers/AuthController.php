<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // register
    public function register(Request $request){

        $validation = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;

        return Response::json([
            'user' => $user,
            'token' => $token,
        ],200);
    }

    public function login(Request $request){
        $validation = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email',$request->email)->first();

        if(empty($user) || !Hash::check($request->password,$user->password)){
            return Response::json([
                'message' => 'Credential do not match!',
            ],200);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return Response::json([
            'user' => $user,
            'token' => $token,
        ],200);
    }

    // logout
    public function logout(){
        auth()->user()->tokens()->delete();

        return Response::json([
            'message' => 'logout success...',
        ],200);
    }
}
