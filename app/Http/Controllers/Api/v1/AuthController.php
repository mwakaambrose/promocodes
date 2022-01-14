<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{

    /**
     * Authenticates an existing user and return a bearer
     * token for use with other endpoints
     *
     * @param LoginFormRequest $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), User::loginRules());
        if ($validation->fails()) {
            return $this->failed([
                "errors" => $validation->errors()
            ]);
        }

        $user = User::where('email', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $data = [
                'message' => 'The given credentials are incorrect. Please double check and try again.'
            ];
            return $this->failed($data);
        }
        // Revoke all existing tokens and create a new one
        $user->tokens()->delete();
        $data = [
            "token" => $user->createToken($request->ip())->plainTextToken,
        ];

        return $this->success($data);
    }

    /**
     * Logs out the user by revoking their access
     * token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        $data = [
            'message' => 'Logout successful'
        ];
        return $this->success($data);
    }
}
