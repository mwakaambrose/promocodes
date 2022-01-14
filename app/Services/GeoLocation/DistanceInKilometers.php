<?php

namespace App\Services\GeoLocation;

class DistanceInKilometers implements Distance, Unit
{
    const UNIT = 'km';
    private ?float $distance;

    /**
     * @param float|null $distance
     */
    public function __construct(float $distance = null)
    {
        $this->distance = $distance;
    }


    public function getUnit(): string
    {
        return self::UNIT;
    }

    public function getValue(): float
    {
        return $this->distance;
    }

    public function getDistance(): string
    {
        return $this->distance . ' ' . self::UNIT;
    }

    public function setValue(float $value): DistanceInKilometers
    {
        $this->distance = $value;
        return $this;
    }
}
