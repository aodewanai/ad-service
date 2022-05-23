<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function users()
    {
        return User::all();
    }

    public function ban(Request $request)
    {
        if (User::where('email', $request->email)->update(array('status' => 'banned'))) {
            return response([
                'message' => 'user with email ' . $request->email . ' was banned',
            ], 200);
        } else {
            return response([
                'message' => 'user doesnt exixst',
            ], 404);
        }

    }
    public function unban(Request $request)
    {
        if (User::where('email', $request->email)->update(array('status' => 'active'))) {
            return response([
                'message' => 'user with email ' . $request->email . ' was unbanned',
            ], 200);
        } else {
            return response([
                'message' => 'user doesnt exixst',
            ], 404);
        }
        
    }
}
