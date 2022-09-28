<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return UserResource::collection(
            User::whereNotIn('id', [
                \auth()->id()
            ])->paginate($request->get('per_page'))
        )->additional([
            'status' => 200,
            'success' => true,
            'error' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $request->password = Hash::make($request->password);
        $user = auth()->user()->create($request->all());

        return (new UserResource($user))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return (new UserResource($user))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UserRequest $request, User $user)
    {
        // Cannot change email
        $user->update($request->only(['name', 'birthday', 'status', 'gender']));

        return (new UserResource($user))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => null,
        ]);
    }

    /**
     * Update Profile
     *
     * @param UpdateUserPasswordRequest $request
     * @param User $user
     * @return UserResource
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $user->update([
            'password' => Hash::make($request->password_confirmation),
        ]);

        return (new UserResource($user))
            ->additional([
                'status' => 200,
                'message' => 'success',
                'error' => null,
            ]);
    }
}
