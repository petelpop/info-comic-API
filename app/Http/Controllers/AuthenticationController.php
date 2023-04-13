<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'account' => ['The provided credentials are incorrect'],
            ]);
        }

        return $user->createToken('user login')->plainTextToken;

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Berhasil!'
        ]);
    }

    public function me(){
        $user = Auth::user();
        return response()->json($user);
    }

    public function register(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = User::create([
            'username'  => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        //return response JSON user is created
        if($user) {
            return response()->json([
                'success' => true,
                'user'    => $user,
                'token'    => $user->createToken('registered!')->plainTextToken  
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }
}
