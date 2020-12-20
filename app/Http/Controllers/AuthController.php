<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $authenticated_user = Auth::user();

            $user = User::find($authenticated_user->id);

            $token = $user->createToken('admin')->accessToken;

            return [
                'token' => $token
            ];
        }

        return response([
            'error' => 'Invalid credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
