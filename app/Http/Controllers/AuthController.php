<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function username()
    {
        return 'username';
    }

    public function credentials(Request $request)
    {
        return [
            'samaccountname' => $request->username,
            'password' => $request->password,
            'fallback' => [
                'username' => $request->username,
                'password' => $request->password,
            ],
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($this->credentials($request))) {
            $user = Auth::user();

            if (!$user->role_id) {
                $user->role_id = 6;
                $user->push();
                $user->assignRole(User::ROLES[$user->role_id]);
            }
            
            // $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'token' => $user->createToken('token')->plainTextToken,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'These credentials not match our records',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully!',
        ], 200);
    }

    public function me(Request $request)
    {
        $user = User::with('role.permissions.permission')
                    ->where('id', Auth::id())
                    ->first();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $user,
        ], 200);
    }
}
