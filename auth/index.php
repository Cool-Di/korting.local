<?
//define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates/korting/header2.php");
//if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
//	LocalRedirect($backurl);
//$USER->Authorize(1);
$APPLICATION->SetTitle("Авторизация");
?>
<?if($USER->IsAuthorized()):?>
<?LocalRedirect("/");?>
<?endif;?>

<?if(isset($_GET["change_password"])):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.changepasswd",
	".default",
	Array()
	);?>
<?else:?>
<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"",
	Array(
		"REGISTER_URL" => "/registration/",
		"FORGOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "",
		"SHOW_ERRORS" => "N"
	),
false
);?>
<?endif;?>