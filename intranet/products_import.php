<?
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

echo(setlocale(LC_ALL, 'en_US.utf8'));

$PRODUCT_IBLOCK_ID	= 22;

$sections	= array();
$row = 1;
$handle = fopen("products.csv", "r");
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
{
	if($data[0] == '')
		continue;
		
	$sections[$data[3]][$data[4]]['products'][]	= 	$data;	
}

foreach($sections as $parent_section_name => $parent_section)
{
	$bs = new CIBlockSection;
	$arFields = Array(
	  "ACTIVE" => 'Y',
	  "IBLOCK_SECTION_ID" => 0,
	  "IBLOCK_ID" => $PRODUCT_IBLOCK_ID,
	  "NAME" => $parent_section_name,
	  "SORT" => 100
	  );

	$parent_group_id 	= $bs->Add($arFields);
	$res 				= ($parent_group_id > 0);
	if(!$res)
		echo $bs->LAST_ERROR;
	
	foreach($parent_section as $sub_section_name => $sub_section)
	{
		$bs = new CIBlockSection;
		$arFields = Array(
		  "ACTIVE" => 'Y',
		  "IBLOCK_SECTION_ID" => $parent_group_id,
		  "IBLOCK_ID" => $PRODUCT_IBLOCK_ID,
		  "NAME" => $sub_section_name,
		  "SORT" => 100
		  );
		$sub_group_id 	= $bs->Add($arFields);
		$res 			= ($sub_group_id > 0);
		if(!$res)
		{
			echo $bs->LAST_ERROR;
			$sub_group_id	= $parent_group_id;
		}  

		foreach($sub_section['products'] as $product)
		{
			$el = new CIBlockElement;
			
			$PROP = array();
			$PROP['ARTICLE'] 	= $product[1];
			
			$arLoadProductArray = Array(
				"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
				"IBLOCK_SECTION_ID" => $sub_group_id,          // элемент лежит в корне раздела
				"IBLOCK_ID"      => $PRODUCT_IBLOCK_ID,
				"PROPERTY_VALUES"=> $PROP,
				"NAME"           => $product[0],
				"ACTIVE"         => "Y",
			);
			print_r($arLoadProductArray);
			if($PRODUCT_ID = $el->Add($arLoadProductArray))
			{
				echo "New ID: ".$PRODUCT_ID;
			}

		}
	}
}

dump($sections);