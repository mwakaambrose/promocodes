<?php

namespace App\Repositories\PromoCodes;

use App\Models\PromoCode;
use App\Repositories\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class PromoCodeRepository implements Repositories
{
    /**
     * Get all the active promo codes.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return PromoCode::whereIsActive(true)->get();
    }

    /**
     * Create a new promo code.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return PromoCode::create($data);
    }

    /**
     * Update the given promo code properties
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->find($id)->update($data);
    }

    /**
     * Find the promo code by the given id.
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return PromoCode::find($id);
    }

    public function findByCode(mixed $code)
    {
        return PromoCode::whereCode($code)->first();
    }
}
