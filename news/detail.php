<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Детальная новости");
?>
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>
<? $APPLICATION->IncludeComponent(
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
		"IBLOCK_ID" => "13",
		"ELEMENT_ID" => $_REQUEST["ID"],
		"ELEMENT_CODE" => "",
		"CHECK_DATES" => "Y",
		"FIELD_CODE" => array("NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "ACTIVE_FROM"),
		"PROPERTY_CODE" => array("COMPANY"),
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
		"AJAX_OPTION_HISTORY" => "N",
		"USER_COMPANY_ID"	=> Korting::getInstance()->GetUserCompanyID()
	),
false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>