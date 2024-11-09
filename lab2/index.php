<?php
include('./domain/digital_product.php');
include('./domain/physical_product.php');
include('./domain/weight_product.php');

// Пример использования
// Создаем товары
$digitalProduct = new DigitalProduct("Электронная книга", 100);
$physicalProduct = new PhysicalProduct("Смартфон", 500);
$weightedProduct = new WeightProduct("Яблоки", 3);

// Покупка цифрового товара
$digitalSale = $digitalProduct->recordSale(1);
echo "Доход с продажи цифрового товара: $digitalSale";

// Покупка штучного товара
$physicalSale = $physicalProduct->recordSale(2);
echo "Доход с продажи штучного товара: $physicalSale";

// Покупка весового товара
$weightedSale = $weightedProduct->recordSale(5);
echo "Доход с продажи весового товара: $weightedSale";

// Проверка общего дохода с продаж
echo "Общий доход цифрового товара: " . $digitalProduct->getSalesIncome();
echo "Общий доход штучного товара: " . $physicalProduct->getSalesIncome();
echo "Общий доход весового товара: " . $weightedProduct->getSalesIncome();
