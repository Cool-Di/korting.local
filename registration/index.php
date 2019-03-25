<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates/korting/header2.php");
$APPLICATION->SetTitle("Регистрация");
?>

<?if(isset($_GET["forgot_password"])):?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.forgotpasswd",
	".default",
	Array()
	);?>	
<?else:?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"",
	Array(
		"USER_PROPERTY_NAME" => "",
		"SHOW_FIELDS" =>  Array("NAME", "LAST_NAME", "SECOND_NAME", "PERSONAL_CITY", "UF_WORK_COMPANY", "UF_WORK_POSITION", "PERSONAL_PHONE", "EMAIL", "PASSWORD", "CONFIRM_PASSWORD" ),
		"REQUIRED_FIELDS" => Array("NAME", "LAST_NAME", "SECOND_NAME"),
		"AUTH" => "N",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => "/registration/?success=yes",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array("UF_WORK_COMPANY", "UF_WORK_POSITION")
	),
false
);?>
<?endif;?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates/korting/footer2.php");?>