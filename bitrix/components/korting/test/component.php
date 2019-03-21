<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

//переменная для хранения имени шаблона, на разных этапах используются разные шаблоны
$templatePage = '';

$arResult 			= array();
$arResult['ERRORS']	= array();

$TEST_IBLOCK_ID 		= 15;
$TEST_RESULT_IBLOCK_ID 	= 19;

CModule::IncludeModule("iblock");

$arResult['ERRORS']	= array();

if(isset($_REQUEST['SECTION_ID']) && intval($_REQUEST['SECTION_ID']))
	$section_id 	= $_REQUEST['SECTION_ID'];
else
	$section_id = 0;
	

if(isset($_REQUEST['question']) && intval($_REQUEST['question']))
	$question_number 	= $_REQUEST['question'];
else
	$question_number 	= 0;


if(is_array($_POST) && sizeof($_POST) > 0)
{
	$answers	= array();
	
	if(is_array($_POST['answers']))
		$answers = $_POST['answers'];
}

//$res = CIBlockSection::GetByID($section_id);
//if($section = $res->GetNext())
//	$arResult['SECTION']	= $section;
  
$arFilter	= Array('IBLOCK_ID' =>15, 'ID' => $section_id);
$arFilter[]	= array(
				"LOGIC" => "OR",
				array('UF_COMPANY' => Korting::getInstance()->GetUserCompanyID()),
				array('UF_COMPANY' => false)
			);
$db_list 	= CIBlockSection::GetList(Array($by=>$order), $arFilter, true, array('UF_COMPANY', 'UF_COUNT_TRY', 'UF_TIME_LIMIT'), array('nPageSize' => 10));
if($ar_result = $db_list->GetNext())
{
	$arResult['SECTION']	= $ar_result;
}
else
{
	$arResult['ERRORS'][]	= 'Указанный тест не найден. Или не хватает прав доступа.';
}



//Проверка имеет ли пользователь право проходить этот тест
if($question_number == 0)
{
	//Получение результатов тестирования данного пользователя
	//что бы узнать сколько раз пользователь проходил тест
	$user_test_result	= array();
	$arSelect 			= Array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_QUESTION");
	$arFilter 			= Array("IBLOCK_ID"=>IntVal($TEST_RESULT_IBLOCK_ID), "ACTIVE"=>"Y",
							"PROPERTY_TEST" => $section_id, "PROPERTY_USER" => $USER->GetID());
	$res 				= CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>999));
	while($ob = $res->GetNextElement())
	{
		$arFields	 			= $ob->GetFields();
		$user_test_result[]		= $arFields;
	}
	
	if(intval($arResult['SECTION']['UF_COUNT_TRY']) > 0 && sizeof($user_test_result) >= $arResult['SECTION']['UF_COUNT_TRY'])
	{
			$arResult['ERRORS'][]	= 'Вы исчерпали допустимое число попыток.';
	}
	
	$_SESSION['TESTS']['TIME_START_'.$section_id]	= time();
	
	//dump($arResult['SECTION']);
}
//--

if($arResult['SECTION']['UF_TIME_LIMIT'] > 0)
{
	$time_left	= $_SESSION['TESTS']['TIME_START_'.$section_id] + $arResult['SECTION']['UF_TIME_LIMIT'] * 60 - time();
	$arResult['TIME_LEFT']	= $time_left;
}

if(sizeof($arResult['ERRORS']) == 0)
{
	$questions	= array();
	$arSelect 	= Array("ID", "NAME", "PROPERTY_QUESTION");
	$arFilter 	= Array("IBLOCK_ID"=>IntVal($TEST_IBLOCK_ID), "ACTIVE"=>"Y", "SECTION_ID" => $section_id);
	$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>999));
	while($ob = $res->GetNextElement())
	{
		$arFields	 			= $ob->GetFields();
	  	$arFields['PROPERTIES'] = $ob->GetProperties();
	  	$questions[]			= $arFields;
	}
	$arResult['QUESTIONS']	= $questions;

	if($question_number < 0)
	{
		$question_number = 0;
	
	}
	elseif($question_number >= sizeof($questions))
	{
		if($question_number == sizeof($questions) && isset($_POST['act']) && $_POST['act'] == 'result')
		{
			$result		= array();
			
			$text_result			= '';
			$question_result		= array();
			$result['true_count']	= 0;
			$result['quest_count']	= sizeof($questions);
			$result['quest_result']	= array();
	
			foreach($questions as $q)
			{
				$true = 1;
				
				if($q['PROPERTIES']['QUESTION']['DESCRIPTION'][$answers[$q['ID']]] != 'Правильно')
					$true = 0;
				
				//echo "<pre>".print_r($q['PROPERTIES']['QUESTION']['DESCRIPTION'], 1)."</pre>";
				/*
	foreach($q['PROPERTIES']['QUESTION']['DESCRIPTION'] as $k => $answer)
				{
					if(
						($answer == 'Правильно' && !isset($answers[$q['ID']][$k])) 
						|| 
						($answer == '' && isset($answers[$q['ID']][$k])) 
						)
						$true = 0;
					
				}
	*/
				$result['true_count']				+= $true;
				$result['quest_result'][$q['ID']] 	= $true;
				
				$q['TRUE']			= $true;
				$question_result[]	= $q;
				
				if($true)
					$text_result.= $q['NAME']." - правильно \r\n";
				else
					$text_result.= $q['NAME']." - не правильно \r\n";
			}

			$result['true_percent']	= intval($result['true_count']/$result['quest_count']*100);
			
			$arResult['RESULT']				= $result;
			$arResult['QUESTION_RESULT']	= $question_result;
			$arResult['TEXT_RESULT']		= $text_result;
			
			$text_result = "Правильных ответов: ".$result['true_count']."\r\n" 
							."Всего ответов: ".$result['quest_count']."\r\n\r\n".$text_result;
			
			//Получение данных о пользователе
			$rsUser		= CUser::GetByID($USER->GetID());
			$arUser		= $rsUser->Fetch();
			$res 		= CIBlockElement::GetByID($arUser['UF_WORK_COMPANY']);
			$arCompany 	= $res->GetNext();
			
			//Добавление результатов в инфоблок
			$el = new CIBlockElement;
	
			$PROP = array();
			$PROP['USER'] 			= $USER->GetID();
			$PROP['TEST']			= $_REQUEST['SECTION_ID'];
			$PROP['RESULT']			= $result['true_percent'];
			$PROP['COMPANY']		= $arUser['UF_WORK_COMPANY'];
			$PROP['USER_LAST_NAME']		= $arUser['LAST_NAME'];
			
			$PROP['TEXT_RESULT'] 	= array('VALUE'=>array('TEXT'=>$text_result, 'TYPE'=>'text'));
			$PROP['SERIALIZE']		= array('VALUE'=>array('TEXT' => serialize($answers), 'TYPE'=>'text'));
			
			$name = $USER->GetLogin().' ('.$USER->GetID().' - '.$arCompany['NAME'].') '.' - '.$arResult['SECTION']['NAME'].' - '.$result['true_percent'].'%';
			
			$arLoadProductArray = Array(
			  "MODIFIED_BY"    => $USER->GetID(), 	// элемент изменен текущим пользователем
			  "IBLOCK_SECTION_ID" => false,      	// элемент лежит в корне раздела
			  "IBLOCK_ID"      => $TEST_RESULT_IBLOCK_ID,
			  "PROPERTY_VALUES"=> $PROP,
			  "NAME"           => $name,
			  "ACTIVE"         => "Y",            	// активен
			  );
			
			if($PRODUCT_ID = $el->Add($arLoadProductArray))
			{
			//	echo "New ID: ".$PRODUCT_ID;
			}
			else
			{
			//	echo "Error: ".$el->LAST_ERROR;
			}
			//
			
			//echo "<pre>".print_r($result, 1)."</pre>";
			
			//---Обновление количества правильных ответов			
			$test_result		= array();
			$all_test_result 	= array();
			$arSelect 			= Array("ID", "NAME", "PROPERTY_SERIALIZE");
			$arFilter 			= Array("IBLOCK_ID"=>IntVal($TEST_RESULT_IBLOCK_ID), "PROPERTY_TEST" => $section_id);
			$res 				= CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>999), $arSelect);
			while($ob = $res->GetNextElement())
			{
				$arFields	 		= $ob->GetFields();
				if(trim($arFields['PROPERTY_SERIALIZE_VALUE']['TEXT']) != '')
				{
					$answer_data		= unserialize(htmlspecialchars_decode($arFields['PROPERTY_SERIALIZE_VALUE']['TEXT']));

					if(is_array($answer_data) && sizeof($answer_data) > 0)
					{
						$all_test_result[]	= $answer_data;
						
						foreach($answer_data as $question_id => $one_test_answer)
							$test_result[$question_id][$one_test_answer]	+= 1;
					}
				}
			}
			
			foreach($questions as $q_key => $q)
			{	
				if($q['PROPERTIES']['QUESTION']['DESCRIPTION'][$answers[$q['ID']]] != 'Правильно')
					$true = 0;
					
				$true_answer	= 0;
				$false_answer	= 0;
				
				foreach($q['PROPERTIES']['QUESTION']['DESCRIPTION'] as $k => $is_true)
				{
					if($is_true == 'Правильно')
						$true_answer	+= $test_result[$q['ID']][$k];
					else
						$false_answer	+= $test_result[$q['ID']][$k];
				}
				
				//echo $q['ID'].' = '.intval(($true_answer / sizeof($all_test_result)) * 100).'% ('.$true_answer.' / '.sizeof($all_test_result).')<br/>';
				
				$true_answer_string	= intval(($true_answer / sizeof($all_test_result)) * 100).'% ('.$true_answer.' / '.sizeof($all_test_result).')';
				CIBlockElement::SetPropertyValueCode($q['ID'], "TRUE_ANSWER", $true_answer_string);
					
					
			}

			//---
		}
		else
			$question_number = sizeof($questions) - 1;
	}
}
$arResult['QUESTION_NUMBER']	= $question_number;
$arResult['QUESTION']			= $questions[$arResult['QUESTION_NUMBER']];


//echo "<pre>".print_r($_POST['answers'], 1)."</pre>";

$arResult['ANSWERS']	= $answers;

$arResult['captcha_code']	= $APPLICATION->CaptchaGetCode();

$this->IncludeComponentTemplate($templatePage);