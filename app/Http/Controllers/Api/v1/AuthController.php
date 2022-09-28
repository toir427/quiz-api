<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'register',
            ],
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->validated())) {
            return response()->json([
                                        'error' => ['Incorrect email or password'],
                                        'success' => false,
                                        'status' => 401,
                                        'result' => null,
                                    ], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create(
            array_merge(
                $request->except(['password_confirmation']),
                ['password' => bcrypt($request->get('password_confirmation')), 'status' => User::STATUS_ACTIVE]
            )
        );
        $user->assignRole(User::ROLE_USER);
        $token = auth()->login($user);

        return $this->createNewToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
                                    'error' => null,
                                    'success' => true,
                                    'status' => 200,
                                    'result' => null,
                                ]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
                                    'error' => null,
                                    'success' => true,
                                    'status' => 200,
                                    'result' => [
                                        'token' => $token,
                                        'token_type' => 'bearer',
                                        'expire' => time() + (auth()->factory()->getTTL() * 60), // an hour
                                    ],
                                ]);
    }

}
