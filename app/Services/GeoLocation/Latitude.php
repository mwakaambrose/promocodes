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
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
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
