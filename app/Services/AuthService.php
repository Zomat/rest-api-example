<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService{

    /**
     * Register user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $response;
    }

    /**
     * Login user
     *
     * @param array $data
     * @return void
     */
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {

            $response = [
                'message' => 'Bad credentials.'
            ];

            $status = 401;
        } else {

            // Delete old tokens if exists
            if ($user->tokens()->where('name', 'myapptoken')->count() > 0) {
                $user->tokens()->where('name', 'myapptoken')->delete();
            }

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            $status = 200;
        }

        return [
            'data' => $response,
            'status' => $status
        ];
    }

}
