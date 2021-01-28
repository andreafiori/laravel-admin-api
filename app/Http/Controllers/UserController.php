<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     *  @OA\Get(
     *     path="/users",
     *     operationId="getUsersList",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users",
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *       name="email",
     *       description="Email",
     *       in="path",
     *       required=true,
     *       @OA\Property(property="file", type="string", format="binary"),
     *       @OA\Schema(
     *         type="string"
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="Pagination page",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *  )
     */
    public function index()
    {
        \Gate::authorize('view', 'users');

        $users = User::paginate();

        return UserResource::collection($users);
    }

    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      summary="Get single user",
     *      tags={"Users"},
     *      description="Return single user",
     *      security={{"bearer_token": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User"
     *      )
     *  )
     */
    public function show($id)
    {
        \Gate::authorize('edit', 'users');

        $user = User::find($id);

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *      path="/users",
     *      summary="Create user",
     *      tags={"Users"},
     *      description="Create user",
     *      security={{"bearer_token": {}}},

     *      @OA\Response(
     *          response=201,
     *          description="User"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
     *     )
     *  )
     */
    public function store(UserCreateRequest $request)
    {
        \Gate::authorize('edit', 'users');

        $user = User::create(
            $request->only('first_name', 'last_name', 'email', 'role_id') +
            [
                'password' => Hash::make( 1234 ),
            ]
        );

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      summary="Update a user",
     *      tags={"Users"},
     *      description="Update a user",
     *      security={{"bearer_token": {}}},
     *      @OA\Response(
     *          response=202,
     *          description="User"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     )
     *  )
     */
    public function update(UserUpdateRequest $request, $id)
    {
        \Gate::authorize('edit', 'users');

        $user = User::find($id);

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      summary="Delete User",
     *      tags={"Users"},
     *      description="Delete a user",
     *      security={{"bearer_token": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="User"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      )
     *  )
     */
    public function destroy($id)
    {
        \Gate::authorize('edit', 'users');

        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Put(
     *      path="/user",
     *      summary="Authenticated user",
     *      description="Show authenticated user info",
     *      tags={"Profile"},
     *      security={{"bearer_token": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="User"
     *      )
     *  )
     */
    public function user()
    {
        // Get current logged user
        $user = \Auth::user();

        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions(),
            ],
        ]);
    }

    /**
     * @OA\Put(
     *      path="/users/info",
     *      summary="User update info",
     *      description="Show authenticated user info",
     *      tags={"Profile"},
     *      security={{"bearer_token": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="User"
     *      )
     *  )
     */
    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = \Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Put(
     *      path="/users/password",
     *      summary="User update password",
     *      description="Update current user's password",
     *      tags={"Profile"},
     *      security={{"bearer_token": {}}},
     *      @OA\Response(
     *          response=204,
     *          description="User"
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdatePasswordRequest")
     *     )
     *  )
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = \Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
