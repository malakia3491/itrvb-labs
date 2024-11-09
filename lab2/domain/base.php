<?php
abstract class Product
{
    protected $name;
    protected $basePrice;
    protected $salesIncome = 0;

    public function __construct($name, $basePrice) {
        $this->name = $name;
        $this->basePrice = $basePrice;
    }
    
    abstract protected function calculateFinalPrice($quantity);
    
    public function recordSale($quantity) {
        $finalPrice = $this->calculateFinalPrice($quantity);
        $this->salesIncome += $finalPrice;
        return $finalPrice;
    }

    public function getSalesIncome() {
        return $this->salesIncome;
    }
}