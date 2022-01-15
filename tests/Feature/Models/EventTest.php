<?php

use Carbon\Carbon;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\Api\v1\ApiController;
use function Pest\Laravel\put;
use function Pest\Laravel\post;
use function Pest\Laravel\assertDatabaseHas;

test("fails to create when incomplete data is given ", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "latitude" => "0.4004731",
        "longitude" => "32.6413863",
        "starts_at" => "2022-01-13 12:00:00",
        "ends_at" => "2022-01-13 18:30:00"
    ];
    post("/api/v1/events", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => [
                "errors" => [
                    "name" => ["The name field is required."],
                ]
            ]
        ]);
});

test("events can be created", function () {
    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "name" => "Kira Health Center III",
        "latitude" => "0.4004731",
        "longitude" => "32.6413863",
        "starts_at" => Carbon::now()->toDateTimeString(),
        "ends_at" => Carbon::now()->addHours(5)->toDateTimeString()
    ];
    post("/api/v1/events", $data)
        ->assertStatus(ApiController::OK)
        ->assertJson([
            "status" => ApiController::OK,
            "message" => "Success",
            "data" => $data
        ]);

    assertDatabaseHas("events", $data);
});

