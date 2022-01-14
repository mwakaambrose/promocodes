<?php

namespace App\Repositories\Events;

use App\Models\Event;
use App\Repositories\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements Repositories
{

    /**
     * Fetch all events
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Event::all();
    }

    /**
     * Find an event by its id
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return Event::find($id);
    }

    /**
     * Create a new event
     *
     * @param array $data
     * @return Model|bool
     */
    public function create(array $data): Model|bool
    {
        return Event::create($data);
    }

    /**
     * Update the event with the given
     * id and data
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }
}
