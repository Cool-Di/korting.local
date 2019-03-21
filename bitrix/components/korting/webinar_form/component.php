<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

//переменная для хранения имени шаблона, на разных этапах используются разные шаблоны
$templatePage = '';

$arResult 			= array();
$arResult['ERRORS']	= array();

if(is_array($_POST) && sizeof($_POST) > 0)
{
	//$res = CIBlockElement::GetByID(intval($arParams["ELEMENT_ID"]));

	//if ($ar_res = $res->GetNext()/* $APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_sid"]) */)
	{    	
		if(!isset($_POST["name"]) || empty($_POST["name"]))
			$arResult['ERRORS'][] = 'Не указано имя!';
		if(!isset($_POST["last_name"]) || empty($_POST["last_name"]))
			$arResult['ERRORS'][] = 'Не указана фамилия!';
		if(!isset($_POST["email"]) || empty($_POST["email"]))
			$arResult['ERRORS'][] = 'Не указан E-mail!';
		if(!isset($_POST["company"]) || empty($_POST["company"]))
			$arResult['ERRORS'][] = 'Не указана компания!';
		
		if(sizeof($arResult['ERRORS']) <= 0)
		{
			$email_to = COption::GetOptionString("main", "email_from", "N"); 
			$arFields = Array(
					"NAME" => $_POST['name'],
					"LAST_NAME" => $_POST['last_name'],
					"EMAIL" => $_POST['email'],
					"COMPANY" => $_POST['company'],
					"WORK_POST" => $_POST['work_post'],
					"TIME" => $_POST['time'],
					"THEME" => $_POST['theme'],
					"EMAIL_TO" => $email_to,
					//"WEBINAR" => $ar_res['NAME'],
					//"WEBINAR_ID" => $ar_res['ID'],
				);
			CEvent::Send("WEBINAR", 's1', $arFields);
			
			//LocalRedirect('/webinars/'.$ar_res['IBLOCK_SECTION_ID'].'/'.$arParams["ELEMENT_ID"].'?success=1#success');
			LocalRedirect('/webinars/?success=1#success');
			$arResult['SUCCESS'] = 1;
		}
	}
	//else
	//  	$arResult['ERRORS'][] = 'Не найден данный вебинар';

}

$arResult['captcha_code']	= $APPLICATION->CaptchaGetCode();

$this->IncludeComponentTemplate($templatePage);