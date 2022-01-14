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
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
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
