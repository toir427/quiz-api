<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\ProfileResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Get Login UserController
     *
     * @return ProfileResource
     */
    public function me()
    {
        // Get data of Logged user
        $user = Auth::user();

        return (new ProfileResource($user))
            ->additional([
                'status' => 200,
                'message' => 'success',
                'error' => null,
            ]);
    }


    /**
     * Update Profile
     *
     * @param UpdateProfileRequest $request
     * @return ProfileResource
     */
    public function update(UpdateProfileRequest $request)
    {
        // Get data of Logged user
        $user = Auth::user();

        // Update UserController
        // Cannot change email
        $user->update($request->only('name', 'gender', 'birthday'));

        return (new ProfileResource($user))
            ->additional([
                'status' => 200,
                'message' => 'success',
                'error' => null,
            ]);
    }

    /**
     * Update Profile
     *
     * @param UpdatePasswordRequest $request
     * @return ProfileResource
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password_confirmation),
        ]);

        return (new ProfileResource($user))
            ->additional([
                'status' => 200,
                'message' => 'success',
                'error' => null,
            ]);
    }
}
