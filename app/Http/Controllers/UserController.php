<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($request->get('order') && $request->get('by')) {
            $order = $request->get('order');
            $by = $request->get('by');
        } else {
            $order = 'id';
            $by = 'desc';
        }

        if ($request->get('paginate')) {
            $paginate = $request->get('paginate');
        } else {
            $paginate = 10;
        }

        $user = User::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%$search%")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('nopeg', 'LIKE', "%$search%")
                    ->orWhere('unit', 'LIKE', "%$search%")
                    ->orWhere('role_id', 'LIKE', "%$search%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $user->appends($query_string);

        $user_active = Auth::user();

        return response()->json([
            'message' => 'success',
            'data' => $user,
            'user' => $user_active->name,
            'email' => $user_active->email,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'username'    => 'required|string|unique:users',
            'role_id'     => 'required|integer|exists:roles,id',
            'email'       => 'required|string|unique:users,email|email',
            'nopeg'       => 'required|integer|unique:users',
            'unit'        => 'required|string',
            'password'    => 'required|string|min:3',
            // 're_password' => 'required|same:password',
        ]);
        // $register = User::create($request->all());
        $user = new User;
        $user->name = $request->name,
        $user->nopeg = $request->nopeg,
        $user->username = $request->username,
        $user->role_id = $request->role_id,
        $user->email = $request->email,
        $user->unit = $request->unit,
        $user->password = Hash::make($request->password),
        $user->email_verified_at = Carbon::now(),
        $user->save();

        return response()->json([
            'message' => 'User created has successfully!',
            'data'    => $register,
        ], 201);
    }

    public function show($id)
    {
        if ($user = User::find($id)) {
            return response()->json([
                'message' => 'Success!',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($user = User::find($id)) {
            $request->validate([
                'name'        => 'required|string',
                'role_id'     => 'required|integer|exists:roles,id',
                'email'       => 'required|string|email',
                'nopeg'       => 'required|integer|unique:users',
                'unit'        => 'required|string',
                'password'    => 'required|string|min:3',
            ]);

            $user->name = $request->name,
            $user->nopeg = $request->nopeg,
            $user->role_id = $request->role_id,
            $user->email = $request->email,
            $user->unit = $request->unit,
            $user->password = Hash::make($request->password),
            $user->push();

            return response()->json([
                'message' => 'User has been updated successfully!',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($user = User::where('id', $id)->first()) {
            $user->delete();
            return response()->json([
                'message' => 'User has been deleted successfully!',
                'data'    => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }
}
