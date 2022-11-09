<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee;

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
                $this->setRole($user);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'token' => $user->createToken('token')->plainTextToken,
                'user' => $user,
            ], 200);

            $request->session()->regenerate();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'These credentials not match our records',
            ], 401);
        }
    }

    private function setRole(User $user)
    {
        $unit = $user->ldap->getFirstAttribute('department');
        $role = Employee::where('PERNR', $user->username)
                        ->orWhere('EMAIL', $user->email)
                        ->first()
                        ->JABATAN;

        if ($unit == 'TPR') {
            $user->role_id = 1;
        } else if (in_array($unit, ['TPW','TPY','TPX']) || ($role == 'Key Account Manager')) {
            $user->role_id = 5;
        } else {
            $user->role_id = 6;
        }

        $user->push();
        $user->assignRole(User::ROLES[$user->role_id]);
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
