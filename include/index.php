<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/auth_description.php",
		"EDIT_TEMPLATE" => ""
	),
false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>