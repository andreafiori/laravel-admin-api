<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::with('role')->paginate();
    }

    public function show($id)
    {
        return User::with('role')->find($id);
    }

    public function store(UserCreateRequest $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email', 'role_id') +
            [
                'password' => Hash::make( 1234 ),
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $user = User::destroy($id);

        return response($user, Response::HTTP_NO_CONTENT);
    }

    public function user($id)
    {
        return \Auth::user();
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = \Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdateInfoRequest $request)
    {
        $user = \Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response($user, Response::HTTP_ACCEPTED);
    }
}
