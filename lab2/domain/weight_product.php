<?php
include_once('base.php');
class WeightProduct extends Product
{
    protected function calculateFinalPrice($weightKg) {
        return $this->basePrice * $weightKg;
    }
}