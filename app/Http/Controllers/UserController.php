<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function registerUser(Request $req)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'photo' => 'nullable'
        ];
    
        $validator = Validator::make($req->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }
    
        try {
            $user = new User();
            $user->first_name = $req->first_name;
            $user->last_name = $req->last_name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->photo = $req->photo;
            $user->save();
    
            return response()->json(['status' => 'success', 'message' => 'Data Inserted Successfully', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    function updateUser(Request $req, $id)
    {
        $rules = [
            'email' => 'email|unique:users,email,' . $id
        ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }

        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            $user->first_name = $req->first_name;
            $user->last_name = $req->last_name;
            $user->email = $req->email;

            if ($req->has('password')) {
                $user->password = Hash::make($req->password);
            }

            $user->photo = $req->photo;
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'User updated successfully', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }



    function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            $user->delete();

            return response()->json(['status' => 'success', 'message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }



    function viewUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            return response()->json(['status' => 'success', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    function viewAllUsers()
    {
        try {
            $users = User::all();

            return response()->json(['status' => 'success', 'data' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }



}
