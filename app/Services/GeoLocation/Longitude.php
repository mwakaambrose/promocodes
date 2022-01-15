<?php

namespace App\Services\GeoLocation;

class Longitude
{
    private string $longitude;

    /**
     * @param string $longitude
     */
    public function __construct(string $longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return (float) $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function __toString(): string
    {
        return $this->longitude;
    }
}
