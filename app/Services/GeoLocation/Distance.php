<?php

namespace App\Services\GeoLocation;

interface Distance
{
    public function getValue(): float;
    public function setValue(float $value): Distance;
    public function getDistance(): string;
}
