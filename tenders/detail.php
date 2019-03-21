<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Детальная конкурсы");
?>
<?
	CModule::IncludeModule('iblock');
	
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	
	if(sizeof($arUser) < 0)
	{
		LocalRedirect('/auth/');
	}	
	
	$arSelect 	= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_COMPANY");
	$arFilter 	= Array("IBLOCK_ID" => 21, "ID" => $_REQUEST["ID"]);
	$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
	if($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		if(intval($arFields['PROPERTY_COMPANY_VALUE']))
		{
			if($arUser['UF_WORK_COMPANY'] != $arFields['PROPERTY_COMPANY_VALUE'])
				LocalRedirect('/tenders/');
			
		}
	}

?>
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"news",
	Array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "korting",
		"IBLOCK_ID" => "21",
		"ELEMENT_ID" => $_REQUEST["ID"],
		"ELEMENT_CODE" => "",
		"CHECK_DATES" => "Y",
		"FIELD_CODE" => array("NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "ACTIVE_FROM"),
		"PROPERTY_CODE" => array(),
		"IBLOCK_URL" => "",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"USE_PERMISSIONS" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Страница",
		"PAGER_TEMPLATE" => "",
		"PAGER_SHOW_ALL" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N"
	),
false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>