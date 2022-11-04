<?php

namespace App\Http\Controllers;

use App\Models\User as LocalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User as LDAPUser;
use App\Models\HRM\Employee as HRMUser;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username'    => 'required',
            'password'    => 'required',
        ]);

        $username = trim($request->username);
        $password = trim($request->password);

        $ldap_conn = Container::getConnection('default');
        $ldap_user = LDAPUser::where('samaccountname', $username)->first();
        $local_user = LocalUser::where('username', $username)->first();
        
        if ($ldap_user) {
            if ($ldap_conn->auth()->attempt($ldap_user->getDn(), $password)) {
                $hrm_user = HRMUser::where('PERNR', $username)->first();

                if (!$local_user) {
                    if ($hrm_user) {
                        $local_user = LocalUser::create([
                            'name' => $hrm_user->EMPLNAME,
                            'username' => $hrm_user->PERNR,
                            'nopeg' => $hrm_user->PERNR,
                            'unit' => $hrm_user->UNIT,
                            'email' => $hrm_user->EMAIL,
                            'password' => Hash::make($password),
                        ]);
                    } else {
                        $local_user = LocalUser::create([
                            'name' => $ldap_user->name[0],
                            'username' => $ldap_user->samaccountname[0],
                            'nopeg' => $ldap_user->samaccountname[0],
                            'unit' => $ldap_user->description[0] ?? null,
                            'email' => $ldap_user->mail[0] ?? null,
                            'password' => Hash::make($password),
                        ]);
                    }
                }

                if (!Hash::check($password, $local_user->password)) {
                    $local_user->password = Hash::make($password);
                    $local_user->push();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully',
                    'token' => $local_user->createToken('token')->plainTextToken,
                    'user' => $local_user,
                ], 200);
                $request->session()->regenerate();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Your LDAP credentials are invalid',
                ], 401);
            }
        } else {
            if ($local_user) {
                if (Auth::attempt($request->only(['username', 'password']))) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Logged in successfully',
                        'token' => $local_user->createToken('token')->plainTextToken,
                        'user' => $local_user,
                    ], 200);
                    $request->session()->regenerate();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your GSMART credentials are invalid',
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'These credentials do not match any records',
                ], 401);
            }
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()->tokens()->delete()) {
            return response()->json([
                'message' => 'Logged out successfully!',
            ], 200);
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }
}
