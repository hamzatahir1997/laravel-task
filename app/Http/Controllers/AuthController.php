<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request){

        $rules=array(
            'email' => 'required|exists:users,email',
            'password' => 'required|string'
        );

        $validator=Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return response()->json(['status' => 'error','message' =>$validator->errors()], 200);
        }

        // check email 
        $user = User::where('email', $request->email)->first();
        
        //check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'status' => 'error',
                'message' => 'Email or password is wrong'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => 'success',
            'data' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }
}
