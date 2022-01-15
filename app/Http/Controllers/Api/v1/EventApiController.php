<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Events\EventRepository;

class EventApiController extends ApiController
{
    private EventRepository $event_repository;

    public function __construct(EventRepository $event_repository)
    {
        $this->event_repository = $event_repository;
    }

    /**
     * Creates a new resource
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), Event::rules());
        if ($validation->fails()){
            return $this->failed(["errors" => $validation->errors()]);
        }

        try {
            $event = $this->event_repository->create($request->all());
            if (!$event){
                throw new Exception("Failed to create the event. Please try again later");
            }
            return $this->success($event);
        } catch (\Throwable $throwable) {
            return $this->error($throwable->getMessage());
        }
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        try {
            $updated = $this->event_repository
                ->update($event->id, $request->all());
            if (!$updated){
                throw new Exception("Failed to update the event. Please try again later");
            }
            $event->refresh();
            return $this->success($event);
        } catch (\Throwable $throwable){
            return $this->error($throwable->getMessage());
        }
    }
}
