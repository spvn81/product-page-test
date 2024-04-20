<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
            if($validatedData){
            $user_check = User::where(['email'=>$request->input('email')])->get();
            if(empty($user_check)){
                $user = new User();
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = Hash::make($request->input('password'));
                $user->save();
                $token = $user->createToken($user->email)->plainTextToken;
                return response()->json(['user' => $user, 'token' => $token], 201);
            }else{
                return response()->json(['message' => 'user already exist'], 200);

            }
        }else{
            return response()->json(['error' => 'Could not create user.'], 500);

        }


    }

}
