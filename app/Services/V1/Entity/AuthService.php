<?php

namespace App\Services\V1\Entity;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Common\SharedMessage;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;


class AuthService{
    public function register(Request $request)
    {

        $user = User::create([
            "first_name"            => strip_tags($request['first_name']),
            "last_name"             => strip_tags($request['last_name']),
            "phone"                 => strip_tags($request['phone']),
            "type_id"               => strip_tags($request['type_id']),
            "email"                 => strip_tags($request['email']),
            "password"              => bcrypt($request['password']),
            "role_id"               => Role::where('name','user')->first()->id,
        ]);

        $token = $user->createToken('secret')->plainTextToken;

        return new SharedMessage(__('registered_successful'), ['user' => new UserResource($user), 'token' => $token], true, null, 201);
    }

    public function login(Request $request)
    {
        if(Auth::attempt([
            'phone' => $request->phone,
            'password' => $request->password
        ])){

            $token = auth()->user()->createToken('secret')->plainTextToken;

            return new SharedMessage(__('login_successful'), ['user' => new UserResource(auth()->user()), 'token' => $token], true, null, 200);
        }else{
            return new SharedMessage(__('Invalid_credentials'), [], true, null, 403);
        }
    }

    public function logout(Request $request)
    {
        if (auth()->check()) {
            $token = $request->user()->currentAccessToken()->delete();
            if ($token) {
                return new SharedMessage(__('Logout_successfully'), [], true, null, 200);
            } else {
                return new SharedMessage(__('Invalid_token'), [], true, null, 498);
            }
        }
        else {
            return new SharedMessage(__('Unauthorized'), [], true, null, 401);

        }
    }
}
