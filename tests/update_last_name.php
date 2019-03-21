<?
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$TEST_RESULT_IBLOCK_ID 	= 19;

$arSelect 			= Array("ID", "NAME", "PROPERTY_USER", "PROPERTY_USER_LAST_NAME");
$arFilter 			= Array("IBLOCK_ID"=>IntVal($TEST_RESULT_IBLOCK_ID));
$res 				= CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>999), $arSelect);

while($ob = $res->GetNextElement())
{
	$arFields	 			= $ob->GetFields();
	
	if(intval($arFields['PROPERTY_USER_VALUE']))
	{
		//Получение данных о пользователе
		$rsUser		= CUser::GetByID($arFields['PROPERTY_USER_VALUE']);
		$arUser		= $rsUser->Fetch();
		
		$last_name	= '';
		if(isset($arUser['LAST_NAME']) && !empty($arUser['LAST_NAME']))
			$last_name	= $arUser['LAST_NAME'];
		elseif(isset($arUser['NAME']) && !empty($arUser['NAME']))
			$last_name	= $arUser['NAME'];
		elseif(isset($arUser['LOGIN']) && !empty($arUser['LOGIN']))
			$last_name	= $arUser['LOGIN'];
			
		if($last_name != '')
		{
			CIBlockElement::SetPropertyValueCode($arFields['ID'], "USER_LAST_NAME", $last_name);
		}
		//dump($arUser);
		
	}
	dump($arFields);
}