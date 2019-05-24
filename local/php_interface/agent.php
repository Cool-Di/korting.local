<?php

use IT\Intranet\Exchange\Application\ProductBonusCsv;

function loadProductsBonus()
{
    mail('cool-di@mail.ru', 'Агент', 'Агент битрикс');
    $productBonus = new ProductBonusCsv();
    $productBonus->updateBonus();
    return "loadProductsBonus();";
}