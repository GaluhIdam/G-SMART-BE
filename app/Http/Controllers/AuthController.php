<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'        => 'required|unique:users|max:20',
                'username'    => 'required|unique:users|max:10',
                'email'       => 'required|unique:users|email',
                'password'    => 'required',
                're-password' => 'required|same:password',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $register = User::create([
            'name'       => $request->get('name'),
            'username'   => $request->get('username'),
            'role_id'    => $request->get('role_id'),
            'email'      => $request->get('email'),
            'password'   => password_hash($request->get('password'), PASSWORD_DEFAULT),
        ]);

        $token =  $register->createToken('token')->accessToken;
        return response()->json([
            'message' => 'Register has successfully!',
            'data'    => $register,
            'token'   => $token
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->get('username'), 'password' => $request->get('password')])) {
            $user = Auth::user();
            $token =  $user->createToken('token')->accessToken;
            return response()->json([
                'messeage' => 'Authorized',
                'token'    => $token,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
