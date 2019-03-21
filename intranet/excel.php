<?
define('NEED_AUTH', 1);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/intranet/media/class-excel-xml.inc.php');

CModule::IncludeModule('iblock');

error_reporting('E_ALL');

$access_level		= Intranet::getInstance()->GetUserAccessLevel();
if($access_level < 100)
{
	die('Недостаточно прав');
}

$reports_excel		= array();

$arExcel					= array();
$arExcel['ID']				= 'ID';
$arExcel['UF_PERSONAL_ID']	= 'Табельный номер';
$arExcel['FIO']				= 'ФИО';
$arExcel['CITY']			= 'Город';
$arExcel['SHOP']			= 'Магазин';
$arExcel['SHOP_CODE']		= 'Код магазина';
$arExcel['YAER']			= 'Год';
$arExcel['MONTH']			= 'Месяц';
$arExcel['WEEK']			= 'Неделя';
$arExcel['COUNT']			= 'Кол-во';
$arExcel['PRICE']			= 'Общая сумма';
$arExcel['PLAN']			= 'План';
$arExcel['PRODUCTS_TEXT']	= 'Товары';
$arExcel['COMMENT']			= 'Комментарий';
$arExcel['MARKETING']		= 'Маркетинговая активность';
$arExcel['ADOPTED']			= 'Принят';

$reports_excel[]			= $arExcel;

$reports			= array();
$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", 'PROPERTY_PRICE', 'PROPERTY_MONTH', 'PROPERTY_ADOPTED', 'PROPERTY_USER_ID', 'PROPERTY_FIO', 'PROPERTY_SHOP', 'PROPERTY_SHOP_ID', 'PROPERTY_CITY', 'PROPERTY_PERIOD', 'PROPERTY_PRODUCTS', 'PROPERTY_PRICE', 'PROPERTY_PRODUCTS_TEXT', 'PROPERTY_COMMENT', 'PROPERTY_MARKETING', 'PROPERTY_ADOPTED');
$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);

//Определение свойств по которым можно фильтровать
$filter_property		= array("PROPERTY_CITY_ID", "PROPERTY_SHOP_ID", "PROPERTY_USER_ID");
foreach($filter_property as $fp)
{
	if(isset($_REQUEST[$fp]) && intval($_REQUEST[$fp]))
		$arFilter[$fp]	= intval($_REQUEST[$fp]);
}
	
if(isset($_REQUEST['PROPERTY_MONTH']) && $_REQUEST['PROPERTY_MONTH'] != '')
{
	$filter_yaer_month	= explode('.', $_REQUEST['PROPERTY_MONTH']);
	
	$arFilter['PROPERTY_YEAR']	= $filter_yaer_month[0];
	$arFilter['PROPERTY_MONTH']	= $filter_yaer_month[1];
}

$res 				= CIBlockElement::GetList(Array('PROPERTY_YEAR' => 'DESC', 'PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>10000), $arSelect);

$shop_list	= Intranet::getInstance()->GetShopList();

while($ob = $res->GetNextElement())
{
	$arFields 				= $ob->GetFields();
	//$arFields['PROPERTIES']	= $ob->GetProperties();
	$arUser						= Intranet::getInstance()->GetUserArr($arFields['PROPERTY_USER_ID_VALUE']);
	
	$total_count 	= 0;
	$products		= unserialize($arFields['~PROPERTY_PRODUCTS_VALUE']);
	foreach($products as $product)
	{
		$total_count += $product['COUNT'];
	}
		
	$arExcel					= array();
	//$arExcel['FIO']				= $arFields['PROPERTY_FIO_VALUE'];
	$arExcel['ID']				= $arUser['ID'];
	$arExcel['UF_PERSONAL_ID']	= $arUser['UF_PERSONAL_ID'];
	$arExcel['FIO']				= $arUser['FIO'];
	$arExcel['CITY']			= $arFields['PROPERTY_CITY_VALUE'];
	$arExcel['SHOP']			= $arFields['PROPERTY_SHOP_VALUE'];
	$arExcel['SHOP_CODE']		= $shop_list[$arFields['PROPERTY_SHOP_ID_VALUE']]['CODE'];
	$arExcel['YAER']			= $arFields['PROPERTY_YEAR_VALUE'];
	$arExcel['MONTH']			= $arFields['PROPERTY_MONTH_VALUE'];
	$arExcel['WEEK']			= $arFields['PROPERTY_WEEK_VALUE'];
	$arExcel['COUNT']			= $total_count;
	$arExcel['PRICE']			= $arFields['PROPERTY_PRICE_VALUE'];
	$arExcel['PLAN']			= Intranet::getInstance()->GetUserSalePlan($arFields['PROPERTY_MONTH_VALUE'], $arFields['PROPERTY_YEAR_VALUE'], $arUser['ID']);
	$arExcel['PRODUCTS_TEXT']	= $arFields['PROPERTY_PRODUCTS_TEXT_VALUE']['TEXT'];
	$arExcel['COMMENT']			= $arFields['PROPERTY_COMMENT_VALUE'];
	$arExcel['MARKETING']		= $arFields['PROPERTY_MARKETING_VALUE'];
	$arExcel['ADOPTED']			= $arFields['PROPERTY_ADOPTED_VALUE'];
	
	$reports_excel[]			= $arExcel;
}


$filename	= 'reports_';
//Определение свойств по которым можно фильтровать
$filter_property		= array('PROPERTY_MONTH', "PROPERTY_CITY_ID", "PROPERTY_SHOP_ID", "PROPERTY_USER_ID");
foreach($filter_property as $fp)
{
	if(isset($_REQUEST[$fp]) && !empty($_REQUEST[$fp]))
	{
		$prefix		= str_replace('PROPERTY_', '', $fp);
		$filename .= ''.strtolower($prefix[0]).$_REQUEST[$fp];
	}
}


$excel	= new Excel_XML;
$excel->addArray($reports_excel);
$excel->generateXML($filename);

//dump($reports_excel[0]);

exit();

$test_array	= array(array('a', 'b', 'c'), array('a', 'b', 'c'), array('a', 'b', 'c'));
echo 'dddddddd';
$excel	= new Excel_XML;
echo 'dddddddd';
$excel->addArray($test_array);

$excel->generateXML('test');

