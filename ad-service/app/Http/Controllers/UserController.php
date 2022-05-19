<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function login(Request $request)
    {
        /** @todo валідація */

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
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
            return response([
                'message' => 'you already have a token'
            ], 400);
        }
    }
}
