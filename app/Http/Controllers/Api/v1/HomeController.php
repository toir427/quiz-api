<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|moderator');
    }

    /**
     * @return JsonResponse
     */
    public function all()
    {
        $data = [
            'users' => User::count(),
            'surveys' => Survey::count(),
        ];

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => null,
            'result' => $data
        ]);
    }
}
