<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/login",
     *   tags={"Public"},
     *   @OA\Parameter(
     *     name="email",
     *     description="Email",
     *     in="path",
     *     required=true,
     *     @OA\Property(property="file", type="string", format="binary"),
     *     @OA\Schema(
     *        type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="password",
     *     description="Password",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   ),
     *   @OA\Response(response="200",
     *     description="Login success",
     *   )
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $authenticated_user = Auth::user();

            $user = User::find($authenticated_user->id);

            $token = $user->createToken('admin')->accessToken;

            // Cookie authentication
            $cookie = \cookie('jwt', $token, 3600);

            return \response([
                'token' => $token
            ])->withCookie($cookie);
        }

        return response([
            'error' => 'Invalid credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Post(
     *   path="/logout",
     *   tags={"Public"},
     *   @OA\Response(response="200",
     *     description="Logout",
     *   )
     * )
     */
    public function logout()
    {
        $cookie = \Cookie::forget('jwt');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    /**
     * @OA\Post(
     *   path="/register",
     *   tags={"Public"},
     *   @OA\Parameter(
     *     name="first_name",
     *     description="First Name",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="last_name",
     *     description="Last Name",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="email",
     *     description="Email",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   ),
     *   @OA\Response(response="200",
     *     description="Register",
     *   )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email') +
            ['password' => \Hash::make($request->input('password'))]
        );

        return response($user, Response::HTTP_CREATED);
    }
}
