<?
define('NEED_AUTH', 1);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Интранет");
error_reporting('E_ALL');
//dump($_SERVER);
//if($_SERVER['REMOTE_ADDR'] != '109.161.96.49')
//	exit();


?>

<?
	$APPLICATION->IncludeComponent(
		"intranet:intranet.main",
		"",
		Array(),
	false
	);
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>