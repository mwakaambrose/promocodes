<?php

namespace App\Services\GeoLocation;

use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class Coordinate
{
    private Latitude $latitude;
    private Longitude $longitude;

    /**
     * @param Latitude $latitude
     * @param Longitude $longitude
     */
    public function __construct(Latitude $latitude, Longitude $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return Latitude
     */
    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }

    /**
     * @param Latitude $latitude
     */
    public function setLatitude(Latitude $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return Longitude
     */
    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }

    /**
     * @param Longitude $longitude
     */
    public function setLongitude(Longitude $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * Get the distance between this coordinate and
     * another coordinate
     *
     * @param Coordinate $coordinate
     * @return Distance
     */
    public function distanceTo(Coordinate $coordinate, Distance $in): Distance
    {
        $lat1 = $this->getLatitude()->getLatitude();
        $lon1 = $this->getLongitude()->getLongitude();
        $lat2 = $coordinate->getLatitude()->getLatitude();
        $lon2 = $coordinate->getLongitude()->getLongitude();

        $distance =  GeoFacade::setPoint([$lat1, $lon1])
            ->setOptions(['units' => ["km", "m", "mile"]])
            ->setPoint([$lat2, $lon2])
            ->getDistance();
        return $in->setValue($distance['1-2'][$in->getUnit()]);
    }

    public function __toString()
    {
        return $this->latitude . ',' . $this->longitude;
    }
}
