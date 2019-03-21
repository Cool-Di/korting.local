<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Курсы");
?>
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>
<div class="article">
<?$APPLICATION->IncludeComponent("bitrix:news.detail", "cource", array(
	"IBLOCK_TYPE" => "korting",
	"IBLOCK_ID" => "14",
	"ELEMENT_ID" => $_REQUEST["ID"],
	"ELEMENT_CODE" => "",
	"CHECK_DATES" => "Y",
	"FIELD_CODE" => array(
		0 => "ID",
		1 => "CODE",
		2 => "XML_ID",
		3 => "NAME",
		4 => "TAGS",
		5 => "SORT",
		6 => "PREVIEW_TEXT",
		7 => "PREVIEW_PICTURE",
		8 => "DETAIL_TEXT",
		9 => "DETAIL_PICTURE",
		10 => "DATE_ACTIVE_FROM",
		11 => "ACTIVE_FROM",
		12 => "DATE_ACTIVE_TO",
		13 => "ACTIVE_TO",
		14 => "SHOW_COUNTER",
		15 => "SHOW_COUNTER_START",
		16 => "IBLOCK_TYPE_ID",
		17 => "IBLOCK_ID",
		18 => "IBLOCK_CODE",
		19 => "IBLOCK_NAME",
		20 => "IBLOCK_EXTERNAL_ID",
		21 => "DATE_CREATE",
		22 => "CREATED_BY",
		23 => "CREATED_USER_NAME",
		24 => "TIMESTAMP_X",
		25 => "MODIFIED_BY",
		26 => "USER_NAME",
		27 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "PROPERTY_TEST",
		1 => "",
	),
	"IBLOCK_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"META_KEYWORDS" => "-",
	"META_DESCRIPTION" => "-",
	"BROWSER_TITLE" => "-",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "Y",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"USE_PERMISSIONS" => "N",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "Страница",
	"PAGER_TEMPLATE" => "",
	"PAGER_SHOW_ALL" => "Y",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"USE_SHARE" => "N",
	"AJAX_OPTION_ADDITIONAL" => "",
	"USER_COMPANY_ID"	=> Korting::getInstance()->GetUserCompanyID()
	),
	false
);?>
</div>
<?$APPLICATION->AddChainItem($ItemName);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>