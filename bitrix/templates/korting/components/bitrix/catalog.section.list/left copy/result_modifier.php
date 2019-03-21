<?
	$arItems = array();
	foreach($arResult["SECTIONS"] as $key=>$Section){ 
		$arFilter = Array("IBLOCK_ID" => 14, "SECTION_ID" => $Section["ID"], "GLOBAL_ACTIVE" => "Y");
		$arSelect = Array("*");
		$arResult["SECTIONS"][$key]["ITEMS"] = array();
		$db_list = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ar_result = $db_list->GetNext()){
			$arResult["SECTIONS"][$key]["ITEMS"][] = $ar_result; 
		}
	}
?>