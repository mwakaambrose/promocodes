<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * For IDE and PHP docs
 * @method static whereIsActive(bool $true)
 * @method static create(array $data)
 * @method static find(int $id)
 * @method static whereCode(string $code)
 * @property mixed $id
 * @property mixed|string $code
 */
class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        "event_id",
        "amount",
        "expires_at",
        "is_active",
        "radius",
        "radius_unit"
    ];

    public static function boot()
    {
        parent::boot();
        parent::saving(function (PromoCode $model) {
            $model->code = $model->generateCode();
        });
    }

    public static function storeRules(): array
    {
        return [
            "event_id" => "required|numeric",
            "amount" => "required|numeric",
            "expires_at" => "required|date",
            "radius" => "required|numeric",
            "radius_unit" => "required|in:m,km,miles"
        ];
    }

    public static function updateRules(): array
    {
        return [];
    }

    public static function redeemRules(): array
    {
        return [
            "code" => "required|string",
            "origin_latitude" => "required|string",
            "origin_longitude" => "required|string",
            "destination_latitude" => "required|string",
            "destination_longitude" => "required|string",
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    private function generateCode(): string
    {
        do{
            $code = strtoupper($this->generateRandomString(6));
        }while(PromoCode::whereCode($code)->exists());
        return $code;
    }

    private function generateRandomString($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
