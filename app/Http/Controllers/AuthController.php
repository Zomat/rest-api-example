<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

/**
 * @group User authentication
 */

class AuthController extends Controller
{
    /**
     * Register new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $response = (new AuthService)->register($data);

        return response()->json($response, 201);
    }

    /**
     * Login user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $response = (new AuthService)->login($data);

        return response()->json($response['data'], $response['status']);
    }


    /**
     * Logout user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logged out.'
        ], 200);
    }
}
