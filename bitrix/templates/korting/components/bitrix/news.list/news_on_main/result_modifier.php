<?			
	$arResult["NEW"][] = $arResult['ITEMS'][0];
	$arResult["NEW"][] = $arResult['ITEMS'][1];
	$arResult["NEW"][] = $arResult['ITEMS'][2];
	
	$arResult["OLD"][] = $arResult['ITEMS'][3];
	$arResult["OLD"][] = $arResult['ITEMS'][4];
	$arResult["OLD"][] = $arResult['ITEMS'][5];
/*

	$IDs = array();
	$arSelect = Array("ID");
	$arFilter = Array("IBLOCK_ID"=>13, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array("DATE_ACTIVE_FROM"=>"DESC"), $arFilter, false, Array("nPageSize"=>3), $arSelect);
	while($ob = $res->GetNext()){
	  $IDs[] = $ob["ID"];
	}

	
	$NEW = array();
	$arSelect = Array("*");
	$arFilter = Array("IBLOCK_ID"=>13, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$IDs);
	$res = CIBlockElement::GetList(Array("DATE_ACTIVE_FROM"=>"DESC"), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNext()){
	  $NEW[] = $ob;
	}
	$OLD = array();
	$arSelect = Array("*");
	$arFilter = Array("IBLOCK_ID"=>13, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "!ID"=>$IDs);
	$res = CIBlockElement::GetList(Array("DATE_ACTIVE_FROM"=>"DESC"), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNext()){
	  $OLD[] = $ob;
	}

	$arResult["NEW"] = $NEW;
	$arResult["OLD"] = $OLD;
*/
	
	//echo "<pre>".print_r($OLD,1)."</pre>";
?>