<?php

namespace App\Services\GeoLocation;

class Latitude
{
    private string $latitude;

    /**
     * @param string $latitude
     */
    public function __construct(string $latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return (float) $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function __toString()
    {
        return $this->latitude;
    }
}
