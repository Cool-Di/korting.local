<?php


namespace IT\Intranet\Exchange\Application;

use CIBlockElement;
use Intranet;
use IT\Intranet\Exchange\CSV\ProductBonusCsvFile;

class ProductBonusCsv
{
    private $fileData = [];

    public function __construct()
    {
        $fileCSV = new ProductBonusCsvFile();
        $this->fileData = $fileCSV->loadData();
    }

    public function updateBonus() {
        foreach($this->fileData as $row) {
            if((int)$row['code'] <= 0)
                continue;

            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "XML_ID", "PROPERTY_POINTS");
            $arFilter = [
                "IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID,
                "XML_ID" => $row['code']
            ];
            $res = CIBlockElement::GetList(['ID' => 'DESC'], $arFilter, false, false, $arSelect);
            if($product = $res->Fetch()) {
                CIBlockElement::SetPropertyValuesEx($product['ID'], Intranet::getInstance()->PRODUCT_IBLOCK_ID, array("POINTS" => $row['points']));
            }
        }
    }
}