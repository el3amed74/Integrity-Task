<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email',$request->email)->first();

        if(!empty($user)){
            if(Hash::check($request->password,$user->password)){
                $token = $user->createToken('api_token')->plainTextToken;
                return response()->json([
                    'message'=>'login successfully',
                    'token' => $token
                ],200);
            }
            else{
                return response()->json(['message' => 'Invalid credentials'],401);
            }
        }
    }
}
