<?
	
	$arItems = array();
	foreach($arResult["SECTIONS"] as $key=>$Section)
	{ 
//		dump($Section['UF_COURSE_COMPANY']);
	
		$arFilter 						= Array("IBLOCK_ID" => 14, "SECTION_ID" => $Section["ID"], "GLOBAL_ACTIVE" => "Y");
		$arFilter['PROPERTY_COMPANY']	= array(false, $arParams['USER_COMPANY_ID']);

		$arSelect = Array("*");
		$arResult["SECTIONS"][$key]["ITEMS"] = array();
		$db_list = CIBlockElement::GetList(Array("sort" => "asc"), $arFilter, false, false, $arSelect);
		while($ar_result = $db_list->GetNext())
		{
			$arResult["SECTIONS"][$key]["ITEMS"][] = $ar_result; 
		}
		
		if($Section["DEPTH_LEVEL"] == 2)
		{
			if($_REQUEST["SECTION_ID"] == $Section["ID"])
				$arResult["SECTIONS"][$Section['IBLOCK_SECTION_ID']]['SELECTED'] = 'Y';
				
			$arResult["SECTIONS"][$Section['IBLOCK_SECTION_ID']]['CHILDREN'][] = $arResult["SECTIONS"][$key];
		}
	}
	//echo '<pre>'.print_r($arResult,1).'</pre>';
	
?>