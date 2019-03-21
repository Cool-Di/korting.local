<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Задать вопрос");
?> 
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>
<?$APPLICATION->IncludeComponent(
	"korting:main.feedback",
	"question",
	Array(
		"USE_CAPTCHA" => "N",
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"EMAIL_TO" => "anton-ht@yandex.ru",
		"REQUIRED_FIELDS" => "",
		"EVENT_MESSAGE_ID" => ""
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>