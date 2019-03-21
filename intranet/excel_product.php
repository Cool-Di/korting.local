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
$arExcel['MODEL']			= 'Модель';
$arExcel['EAN']				= 'EAN';
$arExcel['CODE_1C']			= 'CODE_1C';
$arExcel['CATEGORY']		= 'Категория';
$arExcel['COUNT']			= 'Количество';
$arExcel['PRICE']			= 'Стоимость';
$arExcel['WEEK']			= 'Неделя';
$arExcel['MONTH']			= 'Месяц';
$arExcel['YAER']			= 'Год';
$arExcel['FIO']				= 'Пользователь';
$arExcel['CITY']			= 'Город';
$arExcel['SHOP']			= 'Магазин';
$arExcel['ID']				= 'ID';
$arExcel['UF_PERSONAL_ID']	= 'Табельный номер';
$arExcel['SHOP_ID']			= 'ID магазина';
$arExcel['SHOP_CODE']		= 'Код магазина';

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

$res 				= CIBlockElement::GetList(Array('PROPERTY_YEAR' => 'DESC', 'PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>2000), $arSelect);

$shop_list		= Intranet::getInstance()->GetShopList();
$product_list	= Intranet::getInstance()->GetProductList();

while($ob = $res->GetNextElement())
{
	$arFields 				= $ob->GetFields();
	//$arFields['PROPERTIES']	= $ob->GetProperties();
	
	$arUser					= Intranet::getInstance()->GetUserArr($arFields['PROPERTY_USER_ID_VALUE']);

	$products	= unserialize($arFields['~PROPERTY_PRODUCTS_VALUE']);

	foreach($products as $product)
	{
		$arExcel					= array();
		$arExcel['MODEL']			= $product['NAME'];
		$arExcel['EAN']				= $product_list[$product['ID']]['PROPERTY_EAN_VALUE'];
		$arExcel['CODE_1C']			= $product_list[$product['ID']]['PROPERTY_ARTICLE_VALUE'];
		$arExcel['CATEGORY']		= $product['CATEGORY_NAME'];
		$arExcel['COUNT']			= $product['COUNT'];;
		$arExcel['PRICE']			= $product['PRICE'];
		$arExcel['WEEK']			= $arFields['PROPERTY_WEEK_VALUE'];
		$arExcel['MONTH']			= $arFields['PROPERTY_MONTH_VALUE'];
		$arExcel['YAER']			= $arFields['PROPERTY_YEAR_VALUE'];
		$arExcel['FIO']				= $arFields['PROPERTY_FIO_VALUE'];
		$arExcel['FIO']				= $arUser['FIO'];
		$arExcel['CITY']			= $arFields['PROPERTY_CITY_VALUE'];
		$arExcel['SHOP']			= $arFields['PROPERTY_SHOP_VALUE'];
		$arExcel['ID']				= $arUser['ID'];
		$arExcel['UF_PERSONAL_ID']	= $arUser['UF_PERSONAL_ID'];
		$arExcel['SHOP_ID']			= $arFields['PROPERTY_SHOP_ID_VALUE'];
		$arExcel['SHOP_CODE']		= $shop_list[$arFields['PROPERTY_SHOP_ID_VALUE']]['CODE'];
		
		$reports_excel[]			= $arExcel;
	}
	
}


$filename	= 'reports_p';
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

