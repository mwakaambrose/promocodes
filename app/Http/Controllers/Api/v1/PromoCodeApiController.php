<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Event;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\GeoLocation\Latitude;
use App\Services\GeoLocation\Longitude;
use App\Services\GeoLocation\Coordinate;
use Illuminate\Support\Facades\Validator;
use App\Services\GeoLocation\DistanceInKilometers;
use App\Repositories\PromoCodes\PromoCodeRepository;

class PromoCodeApiController extends ApiController
{
    private PromoCodeRepository $promo_code_repository;

    public function __construct(PromoCodeRepository $promo_code_repository)
    {
        $this->promo_code_repository = $promo_code_repository;
    }

    /**
     * Show all resources from storage
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $promo_codes = $this->promo_code_repository->all();
        return $this->success($promo_codes);
    }

    /**
     * Show active resources from storage
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function active(Request $request): JsonResponse
    {
        $promo_codes = $this->promo_code_repository->getActive();
        return $this->success($promo_codes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), PromoCode::storeRules());
        if ($validation->fails()){
            return $this->failed(["errors" => $validation->errors()]);
        }

        try {
            $promo_code = $this->promo_code_repository->create($request->all());
            return $this->success($promo_code);
        } catch (\Throwable $throwable){
            return $this->error($throwable->getMessage());
        }
    }

    /**
     * Show the specified resource.
     *
     * @param PromoCode $promo_code
     * @return JsonResponse
     */
    public function show(PromoCode $promo_code): JsonResponse
    {
        return $this->success($promo_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PromoCode $promo_code
     * @return JsonResponse
     */
    public function deactivate(Request $request, PromoCode $promo_code): JsonResponse
    {
        $validation = Validator::make($request->all(), PromoCode::deactivationRules());

        if ($validation->fails()){
            return $this->failed(["errors" => $validation->errors()]);
        }

        $updated = $this->promo_code_repository
            ->update($promo_code->id, [
                "is_active" => $request->activate
            ]);

        if (!$updated) {
            return $this->error("Failed to update the promo code");
        }

        $promo_code->refresh();

        return $this->success($promo_code);
    }

    /**
     * Attempts to redeem a give promo code for
     * a given event with the users' destination
     * coordinate.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function redeem(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), PromoCode::redeemRules());
        if ($validation->fails()){
            return $this->failed(["errors" => $validation->errors()]);
        }

        $rides_origin_coordinate = new Coordinate(
            new Latitude($request->origin_latitude),
            new Longitude($request->origin_longitude)
        );

        $rides_destination_coordinate = new Coordinate(
            new Latitude($request->destination_latitude),
            new Longitude($request->destination_longitude)
        );

        // 1- Check that the promo code is active
        $promo_code = $this->promo_code_repository
            ->findByCode($request->code);

        if (!$promo_code) {
            return $this->failed("Promo code is invalid");
        }

        if (!$promo_code->is_active || $promo_code->isExpired()) {
            return $this->failed("Promo code is not active yet or has expired");
        }

        // 2- Promo code belongs to an event, check that the event is active
        $event = $promo_code->event;
        if (!$event->isOnGoing()){
            return $this->failed("Promo code is invalid. The event has ended.");
        }

        $event_coordinate = new Coordinate(
            new Latitude($event->latitude),
            new Longitude($event->longitude)
        );

        // 3- Check that the destination coordinate is within the event's
        // - Get the distance between the event and the destination coordinates
        // - Check that the distance is less than the event's radius
        // - if true, then the promo can be used for the event
        // - if false, then the promo cannot be used for the event return error
        $distance = $event_coordinate->distanceTo($rides_destination_coordinate, new DistanceInKilometers());

        if ($distance->getValue() > $promo_code->radius) {
            return $this->failed("Promo code is invalid. The destination is outside the event's radius.");
        }
        // 4- Create a response with distance and polyline
        $data = [
            "polyline" => [
                "points" => [
                    (string)$rides_origin_coordinate,
                    (string)$rides_destination_coordinate,
                    (string)$event_coordinate,
                ]
            ]
        ];
        return $this->success($data);
    }
}
