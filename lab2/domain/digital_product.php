<?php
include_once('base.php');
class DigitalProduct extends Product
{
    protected function calculateFinalPrice($quantity) {
        return ($this->basePrice / 2) * $quantity;
    }
}