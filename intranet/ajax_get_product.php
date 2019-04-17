<?
define('NEED_AUTH', 1);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

error_reporting('E_ALL');

switch($_REQUEST['action'])
{
	case 'get_product':
		get_product();
	break;
	default:
		get_product_category();
	break;
}

function get_product()
{
	//Получение товаров
	$products	= array();
	$arSelect 	= Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_ARTICLE", "PROPERTY_POINTS");
	$arFilter 	= Array("IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID);
	
	if(isset($_REQUEST['section_id']) && intval($_REQUEST['section_id']))
	{
		$arFilter['SECTION_ID']	= intval($_REQUEST['section_id']);
	}

	$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>9999), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields 							= $ob->GetFields();
		$products[$arFields['ID']]			= $arFields;
	}
	
	?>
	<?/*
 <div>
		<div class="content">
*/ ?>
			<? foreach($products as $product) { ?>
				<option value="<?=$product['ID']?>" data-article="<?=$product['PROPERTY_ARTICLE_VALUE']?>" data-points="<?=$product['PROPERTY_POINTS_VALUE']?>"><?=$product['NAME']?></option>
			<? } ?>
		<? /*
</div>
	</div>
*/ ?>
	<?
}

function get_product_category()
{
	//Получение типов товаров
	$sections_ids	= array();
	$arSelect		= array('ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL');
	$arFilter		= array("IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID );
	
	if(isset($_REQUEST['section_id']) && intval($_REQUEST['section_id']))
	{
		$arFilter['SECTION_ID']	= intval($_REQUEST['section_id']);
	}
	
	$ar_result		= CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter, false, $arSelect);
	while($res = $ar_result->GetNext())
	{
		$sections_ids[$res['ID']]	= $res;
		
		$sections[]	= &$sections_ids[$res['ID']];
	}
	?>
	<?/*
 <div>
		<div class="content">
*/ ?>
			<? foreach($sections as $sect) { ?>
				<option value="<?=$sect['ID']?>"><?=$sect['NAME']?></option>
			<? } ?>
		<? /*
</div>
	</div>
*/ ?>
	<?
}


/*
$APPLICATION->IncludeComponent(
	"intranet:intranet.main",
	"",
	Array(),
false
);
*/