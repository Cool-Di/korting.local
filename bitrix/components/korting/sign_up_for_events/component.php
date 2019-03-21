<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

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
		if(isset($_POST["email"]) && check_email($_POST["email"]) === false && $_POST["email"]!="")
			$arResult['ERRORS'][] = 'Проверьте правильность ввода E-mail';	
		if(!isset($_POST["phone"]) || empty($_POST["phone"]))
			$arResult['ERRORS'][] = 'Не указан телефон!';
		if(!isset($_POST["last_name"]) || empty($_POST["last_name"]))
			$arResult['ERRORS'][] = 'Не указана фамилия!';
		if(!isset($_POST["second_name"]) || empty($_POST["second_name"]))
			$arResult['ERRORS'][] = 'Не указано отчество!';
		if(!isset($_POST["company"]) || empty($_POST["company"]))
			$arResult['ERRORS'][] = 'Не указана компания!';
		if(!isset($_POST["post"]) || empty($_POST["post"]))
			$arResult['ERRORS'][] = 'Не указана должность!';
		
		if(sizeof($arResult['ERRORS']) <= 0)
		{//print_r($arParams["EVENT_NAME"]); die();
		
			$email_to = COption::GetOptionString("main", "email_from", "N"); 
			//echo $email_to; die();
			$arFields = Array(
					"NAME" => $_POST["name"],
					"EMAIL" => $_POST["email"],
					"PHONE" => $_POST["phone"],
					"LAST_NAME" => $_POST["last_name"],
					"SECOND_NAME" => $_POST["second_name"],
					"COMPANY" => $_POST["company"],
					"POST" => $_POST["post"],
					"EMAIL_TO" => $email_to,
					"EVENT_NAME" => $_POST["event_name"],
					"TIME" => $_POST["time"],
					"LOCATION" => $_POST["location"]
				);
			CEvent::Send("SIGN_UP_FOR_EVENTS", 's1', $arFields);
			
			
			//LocalRedirect("/question/?success=1#success");
			
			$arResult['SUCCESS'] = 1;
			//echo "Спасибо, вы записаны на данное мероприятие!";
		}else{
			echo json_encode($arResult['ERRORS']);
		}
	}
	else
	  	$arResult['ERRORS'][] = 'Проверочный код введен не правильно!';
}
//$arResult['captcha_code']	= $APPLICATION->CaptchaGetCode();
if(!isset($_POST["ajax"]))
$this->IncludeComponentTemplate($templatePage);