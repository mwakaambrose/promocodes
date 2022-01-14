<?php

use App\Http\Controllers\Api\v1\ApiController;
use function Pest\Laravel\post;
use function PHPUnit\Framework\assertTrue;

test("user with correct credentials can login and gets token back", function () {
    $credentials = [
        "username" => "ambrose@theonehq.com",
        "password" => "somesecretpassword",
    ];
    post('/api/v1/login', $credentials)
        ->assertStatus(ApiController::OK)
        ->assertJson([
            "status" => ApiController::OK,
            "message" => "Success",
            // "data" => [] we can't assert the token because it's
            // only shown once and saved in the database as a hash
        ])
    ->assertJsonStructure([
        "status",
        "message",
        "data" => [
            "token"
        ]
    ]);
});

test("login fails for user with wrong credentials", function () {
    $credentials = [
        "username" => "ambrose@theonehq.com",
        "password" => "wrong password",
    ];
    post('/api/v1/login', $credentials)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => [
                "message" => "The given credentials are incorrect. Please double check and try again."
            ]
        ]);
});

test("login fails for incomplete credentials", function () {
    $credentials = [
        "username" => "ambrose@theonehq.com",
    ];
    post('/api/v1/login', $credentials)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => [
                "errors" => [
                    "password" => [
                        "The password field is required." // Since it's the password missing
                    ]
                ]
            ]
        ]);
});
