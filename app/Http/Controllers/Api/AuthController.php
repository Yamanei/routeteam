<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(StoreUserRequest $storeUserRequest)
    {
        return User::create($storeUserRequest->all());
    }


    public function login(LoginUserRequest $loginUserRequest)
    {
        if (!Auth::attempt($loginUserRequest->only(['email', 'password']))) {
            return response()->json([
                'message' => "Не авторизован"
            ], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('Token')->plainTextToken,
        ], 200);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout']);
    }
}
