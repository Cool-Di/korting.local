<?
define('NEED_AUTH', 1);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Таблица баллов");
error_reporting('E_ALL');
?>

<?
	$APPLICATION->IncludeComponent(
		"it:transfer.list",
		"",
		[],
	false
	);
?>

<?
$APPLICATION->IncludeComponent(
	"it:transfer.request",
	"",
	[],
	false
);
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>