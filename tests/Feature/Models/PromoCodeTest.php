<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\PromoCode;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\Api\v1\ApiController;
use App\Repositories\PromoCodes\PromoCodeRepository;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Pest\Laravel\post;
use function PHPUnit\Framework\assertTrue;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test("can get all promo codes", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    get("/api/v1/promo-codes/all")
        ->assertStatus(ApiController::OK)
        ->assertJson([
            "status" => ApiController::OK,
            "message" => "Success",
            "data" => PromoCode::all()->toArray()
        ]);
});

test("can get all active promo codes", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    get("/api/v1/promo-codes/active")
        ->assertStatus(ApiController::OK)
        ->assertJson([
            "status" => ApiController::OK,
            "message" => "Success",
            "data" => (new PromoCodeRepository())->getActive()->toArray()
        ]);
});

test("promo codes can be created", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "event_id" => 1,
        "amount" => "5000",
        "radius" => "1",
        "radius_unit" => "km",
        "expires_at" => Carbon::now()->toDateTimeString(),
    ];
    post("/api/v1/promo-codes", $data)
        ->assertStatus(ApiController::OK)
        ->assertJson([
            "status" => ApiController::OK,
            "message" => "Success",
            "data" => $data
        ]);

    assertDatabaseHas("promo_codes", $data);
});

test("promo codes can be deactivated", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "activate" => false,
    ];

    $promo_code = PromoCode::factory()->create();

    put("/api/v1/promo-codes/{$promo_code->id}", $data)
        ->assertStatus(ApiController::OK);

    $promo_code->refresh();
    assertTrue($promo_code->is_active == $data["activate"]);
});

it("fails when incomplete data is given", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "event_id" => 1,
        "amount" => "5000",
        "radius_unit" => "km",
        "expires_at" => Carbon::now()->toDateTimeString(),
    ];
    post("/api/v1/promo-codes", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => [
                "errors" => [
                    "radius" => ["The radius field is required."]
                ]
            ]
        ]);

    assertDatabaseMissing("promo_codes", $data);
});

test("fails when data is incomplete", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        "destination_latitude" => "0.4010102",
        "destination_longitude" => "32.6394255",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => [
                "errors" => [
                    "code" => ["The code field is required."]
                ]
            ]
        ]);
});


test("can not redeem using bad promo codes", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $data = [
        "code" => "BAD PROMO CODE",
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        "destination_latitude" => "0.4010102",
        "destination_longitude" => "32.6394255",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => "Promo code is invalid"
        ]);
});

test("can not redeem using inactive promo codes", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $promo_code = PromoCode::factory()->create([
        "is_active" => false
    ]);

    $data = [
        "code" => $promo_code->code,
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        "destination_latitude" => "0.4010102",
        "destination_longitude" => "32.6394255",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => "Promo code is not active yet or has expired"
        ]);
});

test("can not redeem using expired promo codes", function () {

    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $promo_code = PromoCode::factory()->create([
        "expires_at" => Carbon::now()->subDays(1)->toDateTimeString()
    ]);

    $data = [
        "code" => $promo_code->code,
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        "destination_latitude" => "0.4010102",
        "destination_longitude" => "32.6394255",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => "Promo code is not active yet or has expired"
        ]);
});

test("can not redeem when the event has ended", function () {
    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $promo_code = PromoCode::factory()->create();

    $event = $promo_code->event;
    $event->ends_at = Carbon::now()->subDays(1)->toDateTimeString();
    $event->save();

    $data = [
        "code" => $promo_code->code,
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        "destination_latitude" => "0.4010102",
        "destination_longitude" => "32.6394255",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => "Promo code is invalid. The event has ended."
        ]);
});

test("can not redeem when destination is outside the event radius", function () {
    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $promo_code = PromoCode::factory()->create();

    $data = [
        "code" => $promo_code->code,
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        // Najera police
        "destination_latitude" => "0.3861977",
        "destination_longitude" => "32.632012",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::BAD_REQUEST)
        ->assertJson([
            "status" => ApiController::BAD_REQUEST,
            "message" => "Failed",
            "data" => "Promo code is invalid. The destination is outside the event's radius."
        ]);
});

test("promo codes can be redeemed for an event", function () {
    Sanctum::actingAs(
        User::first(),
        ['*']
    );

    $promo_code = PromoCode::factory()->create();

    $event = $promo_code->event;
    $event->save();

    $data = [
        "code" => $promo_code->code,
        "origin_latitude" => "0.4121508",
        "origin_longitude" => "32.6414086",
        "destination_latitude" => "0.4010102",
        "destination_longitude" => "32.6394255",
    ];
    post("/api/v1/promo-codes/redeem", $data)
        ->assertStatus(ApiController::OK)
        ->assertJson([
            "status" => ApiController::OK,
            "message" => "Success",
            "data" => [
                "promo_code" => $promo_code->getAttributes(),
                "polyline" => [
                    "points" => [
                        "0.4121508,32.6414086",
                        "0.4010102,32.6394255",
                    ]
                ]
            ]
        ]);
});
