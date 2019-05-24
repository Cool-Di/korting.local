<?
/*
You can place here your functions and event handlers

AddEventHandler("module", "EventName", "FunctionName");
function FunctionName(params)
{
	//code
}
*/
error_reporting(E_ALL | E_STRICT); 
ini_set('display_errors',0);
ini_set('display_startup_errors',1); 

include_once('intranet.php');

#
# AutoLoader
#
require_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/lib/loader.php');

require_once('agent.php');

class Korting
{	
	//Здесь хранится экземпляр класса
	private static $_Instance;
	
	public static function getInstance()
	{
		//Проверяем был ли создан объект ранее
		if (!self::$_Instance)
		{
			//Если нет, то создаем его
			self::$_Instance = new self();
		}
		//Возвращаем объект
		return self::$_Instance;
	}
	
	public function GetUserCompanyID()
	{
		global $USER;
		
		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();
		
		return $arUser['UF_WORK_COMPANY'];
	}
};


AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterIBlockElementAddHandler");

function OnBeforeUserUpdateHandler(&$arFields)
{	
	//if($arFields["ACTIVE"] == "Y" && $arFields["UF_STATUS"] == "" || $arFields["UF_STATUS"] != "active"){
		$rsUser = CUser::GetByLogin($arFields["LOGIN"]);
		$arUser = $rsUser->Fetch();
		$email_from = COption::GetOptionString("main", "email_from", "N"); 

		$_arFields = Array(
			"EMAIL_TO" => $arUser["EMAIL"],
			"EMAIL" => $email_from,
			"LOGIN" =>$arUser["LOGIN"],
		);
		CEvent::Send("USER_ACTIVATE", 's1', $_arFields); 
		//$arFields["UF_STATUS"] = "active";
	//}
}

function OnAfterIBlockElementAddHandler(&$arFields)
{
	if($arFields["IBLOCK_ID"] == 20){ // Добалвение элемента в ИБ "Почтовые шаблоны"
	//echo "<pre>".print_r($arFields, 1)."</pre>";die();
		$cUser = new CUser; 
		$arFilter = array("ACTIVE" => "Y");
		$dbUsers = $cUser->GetList($arFilter);
		$email_from = COption::GetOptionString("main", "email_from", "N"); 
		while ($arUser = $dbUsers->Fetch()){
			$arFields = Array(
				"EMAIL_TO" => $arUser["EMAIL"],
				"EMAIL" => $email_from,
				"MESSAGE" =>$arFields["DETAIL_TEXT"],
			);
			//CEvent::Send("MAIL_DELIVERY", 's1', $arFields);  //11.05.2019 скрою, пока не понятн для чего отправка этих писем
		}  
	} 
}

function OnBeforeUserRegisterHandler(&$arFields) 
{ 
	$COMPANY_IBLOCK_ID	= 18;
	
	$arFields["ACTIVE"]	= 'N';
	
	$arFields["LOGIN"] = $arFields["EMAIL"];
	//Если указывается новая компания добавляем её в инфоблок
	if($arFields['UF_WORK_COMPANY'] == -1 && isset($_POST['new_company']) && !empty($_POST['new_company']))
	{
		CModule::IncludeModule("iblock");
		
		//Проверяем нет ли такой компании
		$arSelect = Array("ID", "NAME");
		$arFilter = Array("IBLOCK_ID"=>IntVal($COMPANY_IBLOCK_ID), "NAME"=>$_POST['new_company']);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		if($ob = $res->GetNextElement())
		{
		  $arCompFields = $ob->GetFields();
		  $arFields['UF_WORK_COMPANY'] = $arCompFields['ID'];
		}
		//Если нет то создаем
		else
		{
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
			  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
			  "IBLOCK_ID"      => $COMPANY_IBLOCK_ID,
			  "NAME"           => $_POST['new_company'],
			  );
			
			if($company_id = $el->Add($arLoadProductArray))
				$arFields['UF_WORK_COMPANY'] = $company_id;
		}
	}
	
	return $arFields;
}

function OnAfterUserRegisterHandler(&$arFields)
{
	if($arFields['UF_WORK_COMPANY'] == -1 && isset($_POST['new_company']))
	{
	
	}
}

function dump($array)
{
	echo '<pre>'.print_r($array, 1).'</pre>';
}

//Функция для дебага
function debugmessage($message, $title = false, $color = "#008B8B")
{
    echo '<table class="debugmessage" border="0" cellpadding="5" cellspacing="0" style="border:1px solid '.$color.';margin:2px;background: #ffffff; text-align:left;"><tr><td>';
    if (strlen($title)>0)
    {
        echo '<p style="color: '.$color.';font-size:11px;font-family:Verdana;">['.$title.']</p>';
    }

    if (is_array($message) || is_object($message))
    {
        echo '<pre style="color:'.$color.';font-size:11px;font-family:Verdana;">'; print_r($message); echo '</pre>';
    }
    else
    {
        echo '<p style="color:'.$color.';font-size:11px;font-family:Verdana;">'.$message.'</p>';
    }

    echo '</td></tr></table>';
}

error_reporting (E_ALL ^ E_NOTICE);

//define('BX_COMPRESSION_DISABLED',true);
?>