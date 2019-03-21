<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

//переменная для хранения имени шаблона, на разных этапах используются разные шаблоны
$templatePage = '';

$arResult 			= array();
$arResult['ERRORS']	= array();
if(is_array($_POST) && sizeof($_POST) > 0)
{
	if (1/* $APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_sid"]) */)
	{    	
		if(!isset($_POST["name"]) || empty($_POST["name"]))
			$arResult['ERRORS'][] = 'Не указано имя!';
		if(!isset($_POST["email"]) || empty($_POST["email"]))
			$arResult['ERRORS'][] = 'Не указан E-mail!';
		if(!isset($_POST["message"]) || empty($_POST["message"]))
			$arResult['ERRORS'][] = 'Не указано сообщение!';
		
		if(sizeof($arResult['ERRORS']) <= 0)
		{
			$email_to = COption::GetOptionString("main", "email_from", "N"); 
			$arFields = Array(
					"NAME" => $_POST["name"],
					"EMAIL" => $_POST["email"],
					"PHONE" => $_POST["phone"],
					"LAST_NAME" => $_POST["last_name"],
					"SECOND_NAME" => $_POST["second_name"],
					"COMPANY" => $_POST["company"],
					"POST" => $_POST["post"],
					"EMAIL_TO" => $email_to,
					"MESSAGE" => $_POST["message"],
				);
			CEvent::Send("QUESTION", 's1', $arFields);
			
			if(!isset($_POST["sign_up_for_events"]))
				LocalRedirect("/question/?success=1#success");
			
			$arResult['SUCCESS'] = 1;
		}
	}
	else
	  	$arResult['ERRORS'][] = 'Проверочный код введен не правильно!';

}

$arResult['captcha_code']	= $APPLICATION->CaptchaGetCode();

if(!isset($_POST["sign_up_for_events"]))
	$this->IncludeComponentTemplate($templatePage);