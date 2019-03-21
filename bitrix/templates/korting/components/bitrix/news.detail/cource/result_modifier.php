<?

	if(intval($arResult["PROPERTIES"]["TEST"]["VALUE"]))
	{
		$arFilter	= Array('IBLOCK_ID' =>15, 'ID' => $arResult["PROPERTIES"]["TEST"]["VALUE"]);
		$arFilter[]	= array(
						"LOGIC" => "OR",
						array('UF_COMPANY' => $arParams['USER_COMPANY_ID']),
						array('UF_COMPANY' => false)
					);
		$db_list 	= CIBlockSection::GetList(Array($by=>$order), $arFilter, true, array('UF_COMPANY', 'UF_COUNT_TRY'), array('nPageSize' => 10));
		
		if($ar_result = $db_list->GetNext())
		{
			$arResult['TEST_SECTION']	= $ar_result;
			
			$arResult["TEST_LINK"] 		= $ar_result["SECTION_PAGE_URL"];
			$arResult["TEST_PICTURE"] 	= $ar_result["PICTURE"];
			$arResult["TEST_NAME"] 		= $ar_result["NAME"];
		}
	}
	
	/*
$arIBlockSection 			= GetIBlockSection($arResult["PROPERTIES"]["TEST"]["VALUE"]);
	$arResult["TEST_LINK"] 		= $arIBlockSection["SECTION_PAGE_URL"];
	$arResult["TEST_PICTURE"] 	= $arIBlockSection["PICTURE"];
	$arResult["TEST_NAME"] 		= $arIBlockSection["NAME"];
*/
	//echo "<pre>".print_r($arIBlockSection, 1)."</pre>";
	
	//dump($arResult);
	

?>