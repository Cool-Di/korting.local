<?php

use IT\Intranet\Exchange\Application\ProductBonusCsv;

function loadProductsBonus()
{
    $productBonus = new ProductBonusCsv();
    $productBonus->updateBonus();
    return "loadProductsBonus();";
}