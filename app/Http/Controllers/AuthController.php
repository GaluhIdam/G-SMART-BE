<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username'    => 'required',
            'password'    => 'required',
        ]);
        if (Auth::attempt($request->all())) {
            return response()->json([
                'message' => 'Authorized',
                'token' => Auth::user()->createToken('token')->plainTextToken,
                'user' => Auth::user(),
            ], 200);
            $request->session()->regenerate();
        } else {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()->tokens()->delete()) {
            return response()->json([
                'message' => 'Logout has successfully!',
            ], 200);
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }
}
