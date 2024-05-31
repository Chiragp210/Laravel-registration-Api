<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiController extends Controller
{
     // Register API (POST, formdata)

     public function register(Request $request) {
        
        //Data validation
        $request -> validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "mobile" => "required|unique:users",
            "city"=> "required",
            "password" => "required"
        ]);


        //Data save
        User::create([
            "name" => $request -> name,
            "email" => $request -> email,
            "mobile" => $request -> mobile,
            "city" => $request-> city,
            "password" => Hash::make($request -> password),
        ]);

        // Response 
        return response()->json([
            "status" => true,
            "message" => "User Created Seccessfully"
        ]);

    }

    // Login API (POST, formdata)
    public function login(Request $request){
        // Data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);


        // JWTAuth and attempt 
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){
            // Reaponse 
            return response() -> json([
                "status" => true,
                "message" => "User logged in successfully",
                "token" => $token
            ]);
        }

        return response() -> json([
            "status" => false,
            "message" => "Invalid login details",
        ]);
        

    }


    // Proile API (GET)
    public function profile(){
        $userData = auth() -> user();

        return response() -> json([
            "status" => true,
            "message" => "Profile data",
            "user" => $userData
        ]);
    }

    // Refresh Token API (GET)
    public function refreshToken(){

        $newToken = auth() -> refresh();

        return response() -> json([
            "status" => true,
            "message" => "New Access token generated",
            "token" => $newToken
        ]);

    }

    // Logout API (GET)
    public function logout(){
        auth()->logout();

        return response() -> json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }
}
