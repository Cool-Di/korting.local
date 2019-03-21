<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?

global $newsonmain_filter;

$newsonmain_filter						= array();
$newsonmain_filter['PROPERTY_COMPANY']	= array(false, Korting::getInstance()->GetUserCompanyID());

?>
<? $APPLICATION->IncludeComponent("bitrix:news.list","news_on_main",Array(
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_MODE" => "Y",
	"IBLOCK_TYPE" => "",
	"IBLOCK_ID" => "13",
	"NEWS_COUNT" => "6",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "newsonmain_filter",
	"FIELD_CODE" => Array("ID"),
	"PROPERTY_CODE" => Array("DESCRIPTION"),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "Y",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
	"ADD_SECTIONS_CHAIN" => "Y",
	"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600",
	"CACHE_FILTER" => "Y",
	"CACHE_GROUPS" => "Y",
	"DISPLAY_TOP_PAGER" => "Y",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => "",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"AJAX_OPTION_SHADOW" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "N",
	"AJAX_OPTION_HISTORY" => "N",
	"AJAX_OPTION_ADDITIONAL" => ""
)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
