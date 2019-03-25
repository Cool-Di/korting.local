<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	$arIBlockSection = GetIBlockSection($_GET['SECTION_ID']);
	$arResult["SECTION"] = $arIBlockSection;
	$arSelect = Array("ID");
	$arFilter = Array("IBLOCK_ID"=>15, "SECTION_ID"=>$_GET['SECTION_ID'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$tests = $res->SelectedRowsCount();
	$arResult["TESTS_ALL_COUNT"] = $tests;
	
	
	foreach($arResult["ITEMS"] as $arItem){
		//$arResult["ITEMS"][$key]["NUM"] = $i;
		//echo "<pre>".print_r($arResult["ITEMS"][$key], 1)."</pre>";
		//$i++;
	}
	
	//echo "<pre>".print_r($arResult["SECTION"], 1)."</pre>";
?>