<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nopeg', 'LIKE', "%{$search}%")
                    ->orWhere('unit', 'LIKE', "%{$search}%")
                    ->orWhere('role_id', 'LIKE', "%{$search}%");
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
            'email' => $user_active->email
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:users',
            'username'    => 'required|unique:users',
            'email'       => 'required|unique:users|email',
            'role_id'     => 'required',
            'password'    => 'required|min:8',
            're_password' => 'required|same:password',
        ]);

        $register = User::create([
            'name'       => $request->get('name'),
            'username'   => $request->get('username'),
            'role_id'    => $request->get('role_id'),
            'email'      => $request->get('email'),
            'password'   => password_hash($request->get('password'), PASSWORD_DEFAULT),
        ]);

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
                'name'        => 'required|unique:users,name,' . $id . '|max:255',
                'username'    => 'required|unique:users,username,' . $id . '|max:255',
                'email'       => 'required|unique:users,email,' . $id . '|max:255',
                'role_id'     => 'required',
                'password'    => 'required|min:8|max:255',
            ]);

            $user->update($request->all());

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
