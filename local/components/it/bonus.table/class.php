<?
/*
 * Компонент с таблицей наград за бонусы
 */

use IT\Intranet\Entity\BonusEntity;

class BonusListComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     */
    public function executeComponent()
    {
        $obBonus =  new BonusEntity();
        $hlBonus = $obBonus->getEntity();

        $bonusTable = $hlBonus::getList([
            "order" => ["UF_POINTS" => "ASC"]
        ]);
        while ($bonuses = $bonusTable->Fetch()) {
            $this->arResult["BONUSES"][] = $bonuses;
        }


        //Получение разделов товаров
        $sections_ids	= array();
        $arSelect		= array('ID', 'NAME', 'IBLOCK_SECTION_ID', "DEPTH_LEVEL");
        $arFilter		= array("IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID, "ACTIVE" => "Y");
        $ar_result		= CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter, false, $arSelect);
        while($res = $ar_result->GetNext())
        {
            $res["NAME"] = mb_convert_case($res["NAME"], MB_CASE_TITLE, "UTF-8");
            $sections_ids[$res['ID']]	= $res;
        }

        //Получение товаров
        $products	= array();
        $arSelect 	= Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_ARTICLE", "PROPERTY_POINTS", "PROPERTY_ARTICLE");
        $arFilter 	= Array("IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID);
        $res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>9999), $arSelect);
        while($ob = $res->GetNextElement())
        {
            $arFields 							= $ob->GetFields();
            $products[$arFields['ID']]			= $arFields;
            $sections_ids[$arFields['IBLOCK_SECTION_ID']]['products'][]	= $arFields;
        }

        //debugmessage($sections_ids);
        $this->arResult["SECTIONS"] = $sections_ids;

        $this->IncludeComponentTemplate();
    }
}


?>
