<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property mixed $starts_at
 * @property mixed $ends_at
 * @property mixed $id
 * @method static find(int $id)
 * @method static create(array $data)
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "latitude",
        "longitude",
        "starts_at",
        "ends_at",
    ];

    /**
     * Validation rules
     *
     * @return string[]
     */
    public static function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "latitude" => "required|string",
            "longitude" => "required|string",
            "starts_at" => "required|date",
            "ends_at" => "required|date",
        ];
    }


    /**
     * Check to see if this even is still
     * active
     */
    public function isOnGoing(): bool
    {
        return Carbon::now()->greaterThanOrEqualTo($this->starts_at) &&
            Carbon::now()->lessThanOrEqualTo($this->ends_at);
    }

    public function promo_codes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }
}
