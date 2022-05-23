<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function login(LoginRequest $request)
    {
        /** @todo валідація  */

        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response([
                'message' => 'such user does not exist'
            ], 404);
        }
        if ($user->tokens()->count() == 0) {
            $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [
                'token' => $token
            ];

            return response($response, 200);
        } else {
            //$user->tokens()
            return response([
                'message' => 'you already have a token',
                'token' => $user->tokens()
            ], 200);
        }
    }
}
