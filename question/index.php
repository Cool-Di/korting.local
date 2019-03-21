<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Задать вопрос");
?> 
<? $APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>
<?
	$APPLICATION->IncludeComponent(
	"korting:question",
	"",
	Array(),
	false
	);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>