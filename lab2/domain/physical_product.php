<?php
include_once('base.php');
class PhysicalProduct extends Product
{
    protected function calculateFinalPrice($quantity) {
        return $this->basePrice * $quantity;
    }
}