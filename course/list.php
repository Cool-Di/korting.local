<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Курсы");

global $course_filter;
$course_filter						= array();
$course_filter['PROPERTY_COMPANY']	= array(false, Korting::getInstance()->GetUserCompanyID());

?> 
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>
<?
$APPLICATION->IncludeComponent("bitrix:catalog.section", "course", array(
	"IBLOCK_TYPE" => "",
	"IBLOCK_ID" => 14,
	"SECTION_ID" => $_REQUEST["SECTION_ID"],
	"SECTION_CODE" => "",
	"SECTION_USER_FIELDS" => array(
		0 => "",
		1 => "",
	),
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	"FILTER_NAME" => "course_filter",
	"INCLUDE_SUBSECTIONS" => "Y",
	"SHOW_ALL_WO_SECTION" => "Y",
	"PAGE_ELEMENT_COUNT" => "10",
	"LINE_ELEMENT_COUNT" => "1",
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"OFFERS_FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"OFFERS_PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"OFFERS_SORT_FIELD" => "sort",
	"OFFERS_SORT_ORDER" => "asc",
	"SECTION_URL" => "",
	"DETAIL_URL" => "",
	"BASKET_URL" => "/personal/basket.php",
	"ACTION_VARIABLE" => "action",
	"PRODUCT_ID_VARIABLE" => "id",
	"PRODUCT_QUANTITY_VARIABLE" => "quantity",
	"PRODUCT_PROPS_VARIABLE" => "prop",
	"SECTION_ID_VARIABLE" => "SECTION_ID",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "N",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"META_KEYWORDS" => "-",
	"META_DESCRIPTION" => "-",
	"BROWSER_TITLE" => "-",
	"ADD_SECTIONS_CHAIN" => "Y",
	"DISPLAY_COMPARE" => "N",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "N",
	"CACHE_FILTER" => "Y",
	"PRICE_CODE" => array(
	),
	"USE_PRICE_COUNT" => "Y",
	"SHOW_PRICE_COUNT" => "1",
	"PRICE_VAT_INCLUDE" => "Y",
	"USE_PRODUCT_QUANTITY" => "Y",
	"OFFERS_CART_PROPERTIES" => array(
	),
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>