<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тесты");
?>
<?
	$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "tests_page", array(
	"IBLOCK_TYPE" => "",
	"IBLOCK_ID" => 15,
	"SECTION_ID" => "",
	"SECTION_CODE" => "",
	"COUNT_ELEMENTS" => "N",
	"TOP_DEPTH" => "3",
	"SECTION_FIELDS" => array(),
	"SECTION_USER_FIELDS" => array(),
	"SECTION_URL" => "",
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "3600",
	"CACHE_GROUPS" => "N",
	"ADD_SECTIONS_CHAIN" => "N"
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>