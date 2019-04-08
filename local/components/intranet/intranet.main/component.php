<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
error_reporting(E_ALL ^ E_NOTICE);
//$this->IncludeComponentTemplate();



CModule::IncludeModule('iblock');

IntranetComp::getInstance()->Run($this);

function getDaysFromWeekMonth($week = false, $month = 0, $year = 0, $format = 'd.m.Y')
{
	if(strlen($week) < 2)
		$week = '0'.$week;
		
	if(!(intval($year)) || $year <= 0)
		$year = date('Y');
	
	if(intval($month) && date('n', strtotime($year.'-W'.$week.'-1')) != date('n', strtotime($year.'-W'.$week.'-7')))
	{ 
		if($month == date('n', strtotime($year.'-W'.$week.'-1')))
		{
			$result	= date($format, strtotime($year.'W'.$week)).' - '.date($format, strtotime('last day of '.$year.'-'.$month));
		}
		elseif($month == date('n', strtotime($year.'-W'.$week.'-7')))
		{
			$result	= date($format, strtotime('first day of '.$year.'-'.$month)).' - '.date($format, strtotime($year.'-W'.$week.'-7'));
		}
	}
	else
		$result	= date($format, strtotime($year.'-W'.$week.'-1')).' - '.date($format, strtotime($year.'-W'.$week.'-7'));
	return $result;
}

function getDaysFromWeek($week = false, $year = 0, $format = 'd.m.Y')
{
	if(strlen($week) < 2)
		$week = '0'.$week;
		
	if(!(intval($year)) || $year <= 0)
		$year = date('Y');
		
	$result	= date($format, strtotime($year.'W'.$week)).' - '.date($format, strtotime($year.'W'.$week) + 60*60*24*6.5);
	return $result;
}

class IntranetComp
{
	//Здесь хранится экземпляр класса
	private static $_Instance;
	
	public $PRODUCT_IBLOCK_ID	=22;
	
	//переменная для хранения имени шаблона, на разных этапах используются разные шаблоны
	private $templatePage = '';
	
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
	
	public function Run($component = 0)
	{
		global $APPLICATION;
		
		$action	= '';
		if(isset($component->arParams['action']))
			$action	= $component->arParams['action'];
		if(isset($_REQUEST['action']))
			$action	= $_REQUEST['action'];
		
		$access_level			= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level <= 0)
		{
			$component->arResult['ERRORS'][] 	= 'Недостаточно прав доступа';
			$this->templatePage	= 'access_denied';
		}
		else
		{
			switch($action)
			{
				case 'add_comment':
					$component->arResult	= $this->AddComment();
					$this->templatePage	= 'report_detail';
				break;
				case 'report_send_success':
					$this->templatePage	= 'report_send_success';
				break;
				case 'add_report':
					$APPLICATION->AddChainItem("Добавить отчет", "/intranet/?action=add_report");
					$component->arResult	= $this->ReportForm();
					$this->templatePage	= 'report_form';
				break;
				case 'reports': 
					$APPLICATION->AddChainItem("Отчеты", "/intranet/reports/");
					$component->arResult	= $this->ReportsPage();
					$this->templatePage	= 'reports';
				break;
				case 'reports_by_month': 
					$APPLICATION->AddChainItem("Статистика", "/intranet/statistics/");
					$component->arResult	= $this->ReportsPageByMonth();
					$this->templatePage	= 'reports_by_month';
				break;
				case 'report_detail':
					$APPLICATION->AddChainItem("Отчеты", "/intranet/reports/");
					$APPLICATION->AddChainItem("Просмотр отчета");
					$component->arResult	= $this->ReportDetailPage();
					$this->templatePage	= 'report_detail';
				break;
				case 'report_detail_user':
					$APPLICATION->AddChainItem("Отчеты", "/intranet/reports/");
					$APPLICATION->AddChainItem("Просмотр отчета");
					$component->arResult	= $this->ReportDetailUserPage();
					$this->templatePage	= 'report_detail_user';
				break;
				case 'adopted_report':
					$component->arResult	= $this->AdoptedReport();
					$this->templatePage		= 'report_detail';
				break;
				case 'adopted_all_report':
					$component->arResult	= $this->AdoptedAllReport();
					$component->arResult	= $this->ReportsPage();
					$this->templatePage		= 'reports';
				break;
				case 'plans':
					$APPLICATION->AddChainItem("Планы", "/intranet/plans/");
					$component->arResult	= $this->PlansPage();
					$this->templatePage	= 'plans';
				break;
				default: 
					$component->arResult	= $this->MainPage();
				break;
			}
		}
		

		
		if($component)
			$component->IncludeComponentTemplate($this->templatePage);
	}
	
	public function AddComment()
	{
		global $USER;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			//$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			//return $arResult;
		}
		
		
		if(isset($_REQUEST['report_id']) && intval($_REQUEST['report_id']))
			$report_id	= intval($_REQUEST['report_id']);
		else
		{
			$arResult['ERRORS'][] = 'Не задан ID отчета';
			return $arResult;
		}	

		
		$report_ar			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, "ID" => $report_id);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>1), $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			//$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			$report_ar				= $arFields;
			
			if($report_ar['PROPERTY_USER_ID_VALUE'] == $USER->GetID() || $access_level >= 100)
			{		
				if(!isset($_REQUEST['cooment_text']) || trim($_REQUEST['cooment_text']) == '')
					$arResult['ERRORS'][] = 'Не задан текст комментария';
				else
					$comment_text	= trim($_REQUEST['cooment_text']);

				$user_author_ar			= Intranet::getInstance()->GetUserArr($USER->GetID());
				$comment_name			= $report_id.' - '.$user_author_ar['FIO'].' - '.date('d.m.Y');		
						
				$PROP 					= array();
				$PROP['USER_ID'] 		= intval($USER->GetID());
				$PROP['REPORT_ID']		= $report_id;
				
				$el = new CIBlockElement;
				$arLoadProductArray = Array(
					"MODIFIED_BY"    	=> $USER->GetID(),
					"IBLOCK_SECTION_ID" => 0,
					"IBLOCK_ID"      	=> Intranet::getInstance()->COMMENT_IBLOCK_ID,
					"PROPERTY_VALUES"	=> $PROP,
					"NAME"           	=> $comment_name,
					'DETAIL_TEXT_TYPE' 	=> 'text',
					'DETAIL_TEXT' 		=> $comment_text,
					"ACTIVE"         	=> "Y"
				);
				//dump($arLoadProductArray);exit();
				if(sizeof($arResult['ERRORS']) <= 0)
				{
					//dump($arLoadProductArray);
					if($PRODUCT_ID = $el->Add($arLoadProductArray))
					{
						//echo "New ID: ".$PRODUCT_ID;
						LocalRedirect($_REQUEST['back_url']);
					}
				}
			}
			else
			{
				$arResult['ERRORS'][] = 'Недостаточно прав доступа';
				return $arResult;
			}
		}
		
		$erros_string	= implode(', ', $arResult['ERRORS']);
		LocalRedirect($_REQUEST['back_url'].'&comment_errors='.$erros_string);
		exit();
	}
	
	public function PlansPage()
	{
		global $USER;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		
		$users				= Intranet::getInstance()->GetAllIntranetUsers();
		$arResult['USERS']	= $users;
		
		$cities				= Intranet::getInstance()->GetCityShopList();
		$arResult['CITIES']	= $cities;
		
		$shops	= array();
		foreach($arResult['CITIES'] as $city)
		{
			if(isset($city['SHOPS']) && is_array($city['SHOPS']))
			{
				foreach($city['SHOPS'] as $shop)
				{
					$shops[$shop['ID']]	= $shop;
				}
			}
		}
		$arResult['SHOPS']	= $shops;
		
		//Определение свойств по которым можно фильтровать
		$filter_property		= array("PROPERTY_CITY_ID", "PROPERTY_SHOP_ID");
		$arResult['FILTERS']	= array();
		foreach($filter_property as $fp)
		{
			if(isset($_REQUEST['FILTERS'][$fp]) && intval($_REQUEST['FILTERS'][$fp]))
				$arResult['FILTERS'][$fp]	= intval($_REQUEST['FILTERS'][$fp]);
		}
		
		$arResult['FILTERS']['START_YEAR']	= '2014';
		$arResult['FILTERS']['START_MONTH']	= '1';
		$arResult['FILTERS']['YEAR']	= date('Y');
		$arResult['FILTERS']['MONTH']	= date('n');
		
		
		//--Сохранение плана
		if(isset($_REQUEST['set_plan']) && $_REQUEST['set_plan'] == 1 && is_array($_REQUEST['PLAN']))
		{
			foreach($_REQUEST['PLAN'] as $user_id => $date_plan)
			{
				foreach($date_plan as $plan_date => $plan_value)
				{
					//echo $user_id.' -- '.$plan_date.' -- '.$plan_value.'<br/>';
					
					$plan_date	= explode('.', $plan_date);
					$plan_year	= $plan_date[0];
					$plan_month	= intval($plan_date[1]);
					
					$plan_value	= intval(str_replace(" ", "", $plan_value));

					$plan_name =  $plan_year.'.'.$plan_month.', '.$arResult['USERS'][$user_id]['FIO'];
									
					//Проверка добавлен ли план на указанный 
					$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID", "PROPERTY_YEAR", "PROPERTY_MONTH", "PROPERTY_SALE_PLAN");
					$ar_plans_filter 	= Array("IBLOCK_ID" => Intranet::getInstance()->SALE_PLAN_IBLOCK_ID, "PROPERTY_YEAR" => $plan_year, "PROPERTY_MONTH" => $plan_month, "PROPERTY_USER_ID" => $user_id);
					$res 				= CIBlockElement::GetList(Array('PROPERTY_USER_ID' => 'DESC'), $ar_plans_filter, false, Array("nTopCount"=>1), $arSelect);
					if($ob = $res->GetNextElement())
					{
						$arFields 									= $ob->GetFields();
						
						CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->SALE_PLAN_IBLOCK_ID, $plan_value, 'SALE_PLAN');
					}
					else
					{
						$el = new CIBlockElement;
										
						$PROP 					= array();
						$PROP['USER_ID'] 		= intval($user_id);
						$PROP['YEAR']			= $plan_year;
						$PROP['MONTH']			= $plan_month;
						$PROP['SALE_PLAN']		= $plan_value;
						
						$arLoadProductArray = Array(
							"MODIFIED_BY"    	=> $USER->GetID(),
							"IBLOCK_SECTION_ID" => 0,
							"IBLOCK_ID"      	=> Intranet::getInstance()->SALE_PLAN_IBLOCK_ID,
							"PROPERTY_VALUES"	=> $PROP,
							"NAME"           	=> $plan_name,
							"ACTIVE"         	=> "Y"
						);
						
						//dump($arLoadProductArray);
						if($PRODUCT_ID = $el->Add($arLoadProductArray))
						{
							//echo "New ID: ".$PRODUCT_ID;
						}
					}

				}
			}
			
			LocalRedirect('/intranet/plans/?'.DeleteParam(array()));
		}
		//---
		
		
		
		if(isset($_REQUEST['FILTERS']['YEAR_MONTH']))
		{
			$plans_date				= explode('.', $_REQUEST['FILTERS']['YEAR_MONTH']);
			$arResult['FILTERS']['YEAR']	= $plans_date[0];
			$arResult['FILTERS']['MONTH']	= intval($plans_date[1]);
		}
		
		//--Получение пользователей
		$ar_user_filter	= array();
		if(isset($_REQUEST['FILTERS']['PROPERTY_SHOP_ID']) && intval($_REQUEST['FILTERS']['PROPERTY_SHOP_ID']))
			$ar_user_filter['UF_SHOP_ID']	= intval($_REQUEST['FILTERS']['PROPERTY_SHOP_ID']);
		elseif(isset($_REQUEST['FILTERS']['PROPERTY_CITY_ID']) && intval($_REQUEST['FILTERS']['PROPERTY_CITY_ID']))
		{
			$city_id	= intval($_REQUEST['FILTERS']['PROPERTY_CITY_ID']);
			foreach($arResult['CITIES'][$city_id]['SHOPS'] as $shop)
			{
				$ar_user_filter['UF_SHOP_ID'][] = $shop['ID'];
			}
		}
		$users		= array();
		$cUser 		= new CUser; 
		$sort_by 	= "UF_SHOP_ID";
		$sort_ord 	= "ASC";
		$seller_group_id 				= Intranet::getInstance()->USER_GROUP['SELLER'];
		$ar_user_filter["GROUPS_ID"] 	= $seller_group_id;
		$dbUsers 						= $cUser->GetList($sort_by, $sort_ord, $ar_user_filter, array("SELECT"=>array("UF_*")));
		while ($arUser = $dbUsers->Fetch()) 
		{
			$arUser['FIO']		= ($arUser['NAME'] != '' || $arUser['LAST_NAME'] != '') ? $arUser['NAME'].' '.$arUser['LAST_NAME'] : $arUser['LOGIN'];
			$arUser['SHOP_ID']	= $arUser['UF_SHOP_ID'];
			$arUser['CITY_ID']	= $arResult['SHOPS'][$arUser['UF_SHOP_ID']]['IBLOCK_SECTION_ID'];
			//$arUser['CITY']	= $arUser['UF_SHOP_ID'];
			
			$arUser['MONTH_SALE']	= Intranet::getInstance()->GetMonthSale($arResult['FILTERS']['MONTH'], $arResult['FILTERS']['YEAR'], $arUser['ID']);
			
			$users[$arUser['ID']]	= $arUser;
		}
		$arResult['USERS']	= $users;
	//dump($ar_user_filter);
		//---
		
//		dump($arResult['FILTERS']);
//		dump($_REQUEST);
		$plans			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID", "PROPERTY_YEAR", "PROPERTY_MONTH", "PROPERTY_SALE_PLAN");
		$ar_plans_filter 	= Array("IBLOCK_ID" => Intranet::getInstance()->SALE_PLAN_IBLOCK_ID, "PROPERTY_YEAR" => $arResult['FILTERS']['YEAR'], "PROPERTY_MONTH" => $arResult['FILTERS']['MONTH']);
//		dump($ar_plans_filter);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_USER_ID' => 'DESC'), $ar_plans_filter, false, Array("nTopCount"=>100), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 									= $ob->GetFields();
			$plans[$arFields['PROPERTY_USER_ID_VALUE']]	= $arFields;
		}
		$arResult['PLANS']	= $plans;
		
		$reports			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);
		$arFilter			= array_merge($arFilter, $arResult['FILTERS']);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>20), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			
			$reports[]				= $arFields;
		}

		$arResult['REPORTS']		= $reports;
		
		
		return $arResult;
	}
	
	public function AdoptedReport()
	{
		global $USER, $DB;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		
		if(isset($_REQUEST['report_id']) && intval($_REQUEST['report_id']))
			$report_id	= intval($_REQUEST['report_id']);
		else
		{
			$arResult['ERRORS'][] = 'Не задан ID отчета';
			return $arResult;
		}	
		
		$report_ar			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, "ID" => $report_id);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>1), $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			
			$report_ar				= $arFields;
			
			
			$adopted_value	= 4;
			if(isset($_REQUEST['repel']) && $_REQUEST['repel'] == 1)
				$adopted_value = 5;
			
			CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->REPORT_IBLOCK_ID, $adopted_value, 'ADOPTED');
			CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->REPORT_IBLOCK_ID, $USER->GetID(), 'ADOPTED_USER');
			CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->REPORT_IBLOCK_ID, date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT"))), 'ADOPTED_DATE');
		}
		else
		{
			$arResult['ERRORS'][] = 'Отчет с заданным ID не найден';
		}

		$arResult['REPORT']		= $report_ar;
		
		LocalRedirect('/intranet/reports/');
		
		return $arResult;
		
	}
	
	
	public function AdoptedAllReport()
	{
		global $USER, $DB;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		
		if(!isset($_REQUEST['FIELDS']['REPORT_ID']) || !is_array($_REQUEST['FIELDS']['REPORT_ID']) || sizeof($_REQUEST['FIELDS']['REPORT_ID']) <= 0)
		{
			$arResult['ERRORS'][] = 'Не выбрано ни одного отчета';
			return $arResult;
		}
		else
			$reports_to_adopted	= $_REQUEST['FIELDS']['REPORT_ID'];
		
//	dump($_REQUEST['FIELDS']['REPORT_ID']);
//	exit();
		$i					= 0;
		$report_ar			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID", "PROPERTY_ADOPTED");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, "PROPERTY_ADOPTED" => false, "ID" => $reports_to_adopted);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>100), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
//			dump($arFields);
//			$arFields['PROPERTIES']	= $ob->GetProperties();
//			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);			
			$report_ar				= $arFields;
			
			$adopted_value	= 4;
			if(isset($_REQUEST['repel']) && $_REQUEST['repel'] == 1)
				$adopted_value = 5;
			
			CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->REPORT_IBLOCK_ID, $adopted_value, 'ADOPTED');
			CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->REPORT_IBLOCK_ID, $USER->GetID(), 'ADOPTED_USER');
			CIBlockElement::SetPropertyValues($arFields['ID'], Intranet::getInstance()->REPORT_IBLOCK_ID, date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT"))), 'ADOPTED_DATE');
			
			$i++;
		}

		$arResult['REPORT']		= $report_ar;
		
		LocalRedirect('/intranet/reports/');
		
		return $arResult;
		
	}
	

	public function ReportDetailUserPage()
	{
		global $USER;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		/*
if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
*/
		
		if(isset($_REQUEST['report_id']) && intval($_REQUEST['report_id']))
			$report_id	= intval($_REQUEST['report_id']);
		else
		{
			$arResult['ERRORS'][] = 'Не задан ID отчета';
			return $arResult;
		}
		
		$cities				= Intranet::getInstance()->GetCityShopList();
		$arResult['CITIES']	= $cities;
		
			
		
		$report_ar			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, "ID" => $report_id, 'PROPERTY_USER_ID' => $USER->GetID());
		$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>1), $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			
			$report_ar				= $arFields;
		}
		else
		{
			$arResult['ERRORS'][] = 'Отчет с заданным ID не найден';
		}

		$arResult['REPORT']		= $report_ar;
		
		
		
		$comments			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "DATE_CREATE_UNIX", "NAME", "DETAIL_TEXT", "PROPERTY_USER_ID", "PROPERTY_REPORT_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->COMMENT_IBLOCK_ID, "PROPERTY_REPORT_ID" => $report_id);
		$res 				= CIBlockElement::GetList(Array('created' => 'desc'), $arFilter, false, Array("nTopCount"=>40), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTY_USER_ID_VALUE']);
			
			$comments[]				= $arFields;
		}
		//dump($comments);
		
		$arResult['COMMENTS']		= $comments;
		
		return $arResult;
	}	
	
	
	public function ReportDetailPage()
	{
		global $USER;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		
		if(isset($_REQUEST['report_id']) && intval($_REQUEST['report_id']))
			$report_id	= intval($_REQUEST['report_id']);
		else
		{
			$arResult['ERRORS'][] = 'Не задан ID отчета';
			return $arResult;
		}
		
		$cities				= Intranet::getInstance()->GetCityShopList();
		$arResult['CITIES']	= $cities;
		
			
		
		$report_ar			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, "ID" => $report_id);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>1), $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			
			$report_ar				= $arFields;
		}
		else
		{
			$arResult['ERRORS'][] = 'Отчет с заданным ID не найден';
		}

		$arResult['REPORT']		= $report_ar;
		
		
		$comments			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "DATE_CREATE_UNIX", "NAME", "DETAIL_TEXT", "PROPERTY_USER_ID", "PROPERTY_REPORT_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->COMMENT_IBLOCK_ID, "PROPERTY_REPORT_ID" => $report_id);
		$res 				= CIBlockElement::GetList(Array('created' => 'desc'), $arFilter, false, Array("nTopCount"=>40), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTY_USER_ID_VALUE']);
			
			$comments[]				= $arFields;
		}
		//dump($comments);
		
		$arResult['COMMENTS']		= $comments;
		
		return $arResult;
	}	
	
	public function ReportsPage()
	{
		global $USER;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		
		$users				= Intranet::getInstance()->GetAllIntranetUsers();
		$arResult['USERS']	= $users;
		
		$cities				= Intranet::getInstance()->GetCityShopList();
		$arResult['CITIES']	= $cities;	
		
		$yaer_month			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID", 'PROPERTY_YEAR', 'PROPERTY_MONTH');
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);
		$res 				= CIBlockElement::GetList(false, $arFilter, array('PROPERTY_YEAR', 'PROPERTY_MONTH'), Array("nTopCount"=>300), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 		= $ob->GetFields();
			
			$tmp_year_month = array(
								'YEAR' => $arFields['PROPERTY_YEAR_VALUE'],
								'MONTH' => $arFields['PROPERTY_MONTH_VALUE'],
								'MONTH_NAME' => Intranet::getInstance()->GetMonthName($arFields['PROPERTY_MONTH_VALUE']),
							);
			$yaer_month[$arFields['PROPERTY_YEAR_VALUE'].'.'.$arFields['PROPERTY_MONTH_VALUE']]	= $tmp_year_month;
		}
		krsort($yaer_month);
		$arResult['YEAR_MONTH']	= $yaer_month;	
		
		
		//Определение свойств по которым можно фильтровать
		$filter_property		= array("PROPERTY_CITY_ID", "PROPERTY_SHOP_ID", "PROPERTY_USER_ID");
		$arResult['FILTERS']	= array();
		foreach($filter_property as $fp)
		{
			if(isset($_REQUEST['FILTERS'][$fp]) && intval($_REQUEST['FILTERS'][$fp]))
				$arResult['FILTERS'][$fp]	= intval($_REQUEST['FILTERS'][$fp]);
		}
		
		if(isset($_REQUEST['FILTERS']['PROPERTY_MONTH']) && $_REQUEST['FILTERS']['PROPERTY_MONTH'] != '')
		{
			$filter_yaer_month	= explode('.', $_REQUEST['FILTERS']['PROPERTY_MONTH']);
			
			$arResult['FILTERS']['PROPERTY_YEAR']	= $filter_yaer_month[0];
			$arResult['FILTERS']['PROPERTY_MONTH']	= $filter_yaer_month[1];
		}

		if(!isset($arResult['FILTERS']['PROPERTY_MONTH']))
		{
			//$arResult['FILTERS']['PROPERTY_MONTH']	= date('n');
			//$arResult['FILTERS']['PROPERTY_YEAR']	= date('Y');
		}

		$reports			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);
		$arFilter			= array_merge($arFilter, $arResult['FILTERS']);
		
		//dump($arFilter);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_YEAR' => 'DESC', 'PROPERTY_MONTH' => 'DESC', 'PROPERTY_WEEK' => 'DESC', 'ID' => 'DESC'), $arFilter, false, Array("nTopCount"=>300), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			
			$reports[]				= $arFields;
		}

		$arResult['REPORTS']		= $reports;
		
		
		return $arResult;
	}
	
	
	public function ReportsPageByMonth()
	{
		global $USER;
		
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();

		if($access_level < 100)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		
		$users				= Intranet::getInstance()->GetAllIntranetUsers();
		$arResult['USERS']	= $users;
		
		$cities				= Intranet::getInstance()->GetCityShopList();
		$arResult['CITIES']	= $cities;
		
		$yaer_month			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID", 'PROPERTY_YEAR', 'PROPERTY_MONTH');
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);
		$res 				= CIBlockElement::GetList(false, $arFilter, array('PROPERTY_YEAR', 'PROPERTY_MONTH'), Array("nTopCount"=>300), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 		= $ob->GetFields();
			
			$tmp_year_month = array(
								'YEAR' => $arFields['PROPERTY_YEAR_VALUE'],
								'MONTH' => $arFields['PROPERTY_MONTH_VALUE'],
								'MONTH_NAME' => Intranet::getInstance()->GetMonthName($arFields['PROPERTY_MONTH_VALUE']),
							);
			$yaer_month[$arFields['PROPERTY_YEAR_VALUE'].'.'.$arFields['PROPERTY_MONTH_VALUE']]	= $tmp_year_month;
		}
		krsort($yaer_month);
		$arResult['YEAR_MONTH']	= $yaer_month;

		
		
		//Определение свойств по которым можно фильтровать
		$filter_property		= array("PROPERTY_CITY_ID", "PROPERTY_SHOP_ID", "PROPERTY_USER_ID");
		$arResult['FILTERS']	= array();
		foreach($filter_property as $fp)
		{
			if(isset($_REQUEST['FILTERS'][$fp]) && intval($_REQUEST['FILTERS'][$fp]))
				$arResult['FILTERS'][$fp]	= intval($_REQUEST['FILTERS'][$fp]);
		}
		
		if(isset($_REQUEST['FILTERS']['PROPERTY_MONTH']) && $_REQUEST['FILTERS']['PROPERTY_MONTH'] != 0)
		{
			$filter_yaer_month	= explode('.', $_REQUEST['FILTERS']['PROPERTY_MONTH']);
			
			$arResult['FILTERS']['PROPERTY_YEAR']	= $filter_yaer_month[0];
			$arResult['FILTERS']['PROPERTY_MONTH']	= $filter_yaer_month[1];
		}

		if(!isset($arResult['FILTERS']['PROPERTY_MONTH']))
		{
			//$arResult['FILTERS']['PROPERTY_MONTH']	= date('n');
			//$arResult['FILTERS']['PROPERTY_YEAR']	= date('Y');
		}

		$reports			= array();
		$reports_by_month	= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);
		$arFilter			= array_merge($arFilter, $arResult['FILTERS']);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_YEAR' => 'DESC', 'PROPERTY_WEEK' => 'DESC', 'ID' => 'DESC'), $arFilter, false, Array("nTopCount"=>300), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
			
			//$reports[]				= $arFields;
//echo $arFields['PROPERTIES']['YEAR']['VALUE'].'.'.$arFields['PROPERTIES']['WEEK']['VALUE'].'<br/>';
			$tmp_year_month_user	= $arFields['PROPERTIES']['YEAR']['VALUE'].'.'.$arFields['PROPERTIES']['MONTH']['VALUE'].'.'.$arFields['PROPERTIES']['USER_ID']['VALUE'];
			$reports_by_month[$tmp_year_month_user][]	= $arFields;
		}

		$test	= Intranet::getInstance()->GetUserSalePlan(7, 2014, 105);

		foreach($reports_by_month as $year_month_key => $rm)
		{
			$report = array();
			
			$report['USER']			= $rm[0]['USER'];
			$report['REPORT_COUNT'] = sizeof($rm);
			$report['MONTH']		= $rm[0]['PROPERTIES']['MONTH']['VALUE'];
			$report['YEAR']			= $rm[0]['PROPERTIES']['YEAR']['VALUE'];
			$report['CITY']			= $rm[0]['PROPERTIES']['CITY']['VALUE'];
			$report['SHOP']			= $rm[0]['PROPERTIES']['SHOP']['VALUE'];
			
			$report['MONTH_PLAN']	= Intranet::getInstance()->GetUserSalePlan($report['MONTH'], $report['YEAR'], $report['USER']['ID']);
			
			$price				= 0;
			$adopted_price		= 0;
			$unadopted_price	= 0;
			$product 			= array();
			$adopted_count		= 0;
			foreach($rm as $arReport)
			{
				if($arReport['PROPERTIES']['ADOPTED']['VALUE'] == 'Да')
					$adopted_price 		+= $arReport['PROPERTIES']['PRICE']['VALUE'];
				else
					$unadopted_price 	+= $arReport['PROPERTIES']['PRICE']['VALUE'];
					
				
				$tmp_product	= unserialize($arReport['PROPERTIES']['PRODUCTS']['~VALUE']);
				
				if(is_array($tmp_product))
				{
					$product 	= array_merge($product, $tmp_product);
				}
				
				if($arReport['PROPERTIES']['ADOPTED']['VALUE'] == 'Да')
					$adopted_count++;
					
			}
			
			$report['ADOPTED_COUNT']	= $adopted_count;
			$report['PRICE_ADOPTED']	= $adopted_price;
			$report['PRICE_UNADOPTED']	= $unadopted_price;
			$report['PRODUCTS']			= $product;
			
			$reports[]					= $report;
		}

//		dump($reports);
		$arResult['REPORTS']		= $reports;
		
		
		return $arResult;
	}
	
	public function MainPage()
	{
		global $USER;
		
		$access_level		= Intranet::getInstance()->GetUserAccessLevel();
		if($access_level < 10)
		{
			$arResult['ERRORS'][] = 'Недостаточно прав доступа';
			return $arResult;
		}
		elseif(!Intranet::getInstance()->IsSeller())
			LocalRedirect('/intranet/reports/');
		
		$user_data	= Intranet::getInstance()->GetUserArr();
		Intranet::getInstance()->GetMonthSale(1, 2014);
		if(!intval($user_data['ID']))
			return false;
			
		$reports			= array();
		
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTIES_WEEK");
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, 'PROPERTY_USER_ID' => $user_data['ID']);

		$res 				= CIBlockElement::GetList(Array('PROPERTY_YEAR' => 'DESC', 'PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>20), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			$arFields['PROPERTIES']	= $ob->GetProperties();
			
			$reports[]				= $arFields;
		}
		
		$arResult['REPORTS']		= $reports;
		
		return $arResult;
	}
	
	public function ReportForm($report_id = 0)
	{
		//dump($_REQUEST);
		global $USER;
	
		$arResult			= array();
		$arResult['ERRORS']	= array();
		
		//Получение типов товаров
		$sections_ids	= array();
		$arSelect		= array('ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL');
		$arFilter		= array("IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID, );
		$ar_result		= CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter, false, $arSelect);
		while($res = $ar_result->GetNext())
		{
			$sections_ids[$res['ID']]	= $res;
			
			if($res['DEPTH_LEVEL'] == 1)
				$sections[]	= &$sections_ids[$res['ID']];
			else
				$sections_ids[$res['IBLOCK_SECTION_ID']]['CHILD'][]	= &$sections_ids[$res['ID']];
		}
		
		//Получение товаров
		$products	= array();
		$arSelect 	= Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_ARTICLE");
		$arFilter 	= Array("IBLOCK_ID" => Intranet::getInstance()->PRODUCT_IBLOCK_ID);
		$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>9999), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 							= $ob->GetFields();
			$products[$arFields['ID']]			= $arFields;
            $arResult['JSON_PRODUCTS'][] = [
                "value" => $arFields['ID'],
                "label" => trim($arFields["NAME"]), //trim на всякий случай
                "article" => $arFields["PROPERTY_ARTICLE_VALUE"]
            ];
			$sections_ids[$arFields['IBLOCK_SECTION_ID']]['products'][]	= $arFields;
		}
		
		$user_data	= Intranet::getInstance()->GetUserArr();
		$city_shop	= Intranet::getInstance()->GetUserCityShop();

		$arResult['CITY']	= $city_shop['city'];
		$arResult['SHOP']	= $city_shop['shop'];
		
		$arResult['FIO']	= ($user_data['NAME'] != '' || $user_data['LAST_NAME'] != '') ? $user_data['LAST_NAME'].' '.$user_data['NAME'] : $user_data['LOGIN'];

				
		$arResult['SECTIONS']	= $sections;
		$arResult['PRODUCTS']	= $products;
		
		if(isset($_REQUEST['report_id']) && intval($_REQUEST['report_id']))
			$report_id	= intval($_REQUEST['report_id']);

		
		if(intval($report_id))
		{
			$report_ar			= array();
			$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
			$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, "ID" => $report_id, 'PROPERTY_USER_ID' => $USER->GetID());
			$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>1), $arSelect);
			if($ob = $res->GetNextElement())
			{
				$arFields 				= $ob->GetFields();
				$arFields['PROPERTIES']	= $ob->GetProperties();
				
				$arFields['USER']		= Intranet::getInstance()->GetUserArr($arFields['PROPERTIES']['USER_ID']['VALUE']);
				
				$report_ar				= $arFields;

				if($arFields['PROPERTIES']['ADOPTED']['VALUE'] == 'Да')
				{
					$arResult['ERRORS'][] = 'Отчет уже принят, редактирование запрещено';
				}
				else
				{					
					$arResult['REPORT']		= $report_ar;	
					
					//$arResult['FIELDS']['REPORT_ID']	= $arResult['REPORT']['ID'];
					$arResult['FIELDS']['COMMENT']		= $arResult['REPORT']['PROPERTIES']['COMMENT']['VALUE'];
					$arResult['FIELDS']['MARKETING']	= $arResult['REPORT']['PROPERTIES']['MARKETING']['VALUE'];
					
					$arResult['FIELDS']['REPORT_DATE']	= $arResult['REPORT']['PROPERTIES']['YEAR']['VALUE'].'.'.$arResult['REPORT']['PROPERTIES']['MONTH']['VALUE'].'.'.$arResult['REPORT']['PROPERTIES']['WEEK']['VALUE'];
					
					$arResult['FIELDS']['PRODUCTS']		= unserialize($arResult['REPORT']['PROPERTIES']['PRODUCTS']['~VALUE']);
				}
			}
			else
			{
				$arResult['ERRORS'][] = 'Отчет с заданным ID не найден';
			}
		}
		
		if(isset($_POST) && sizeof($_POST) > 0)
		{
			$arResult['FIELDS']		= array();
			
			if(isset($_POST['FIELDS']['COMMENT']))
				$arResult['FIELDS']['COMMENT']	= $_POST['FIELDS']['COMMENT'];
			else
				$arResult['FIELDS']['COMMENT']	= '';
				
			if(isset($_POST['FIELDS']['MARKETING']))
				$arResult['FIELDS']['MARKETING']	= $_POST['FIELDS']['MARKETING'];
			else
				$arResult['FIELDS']['MARKETING']	= '';
				
			$arResult['REPORT_DATE']			= $_POST['FIELDS']['REPORT_DATE'];
			$arResult['FIELDS']['REPORT_DATE']	= $_POST['FIELDS']['REPORT_DATE'];
			$report_date				= explode('.', $arResult['REPORT_DATE']);
			$arResult['REPORT_YEAR']	= $report_date[0];
			//$arResult['WEEK_NUMBER']	= str_pad($report_date[1], 2, '0', STR_PAD_LEFT);
			$arResult['MONTH_NUMBER']	= intval($report_date[1]);
			$arResult['WEEK_NUMBER']	= intval($report_date[2]);
		
			if(isset($arResult['REPORT']['ID']) && intval($arResult['REPORT']))
			{
				
			}
			else
			{
				//Проверка не добавлялся ли уже отчет за указанный период 
				$report_ar			= array();
				$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID", "PROPERTY_YEAR", "PROPERTY_MONTH", "PROPERTY_WEEK");
				$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, 'PROPERTY_USER_ID' => $USER->GetID(), "PROPERTY_YEAR" => $arResult['REPORT_YEAR'], "PROPERTY_MONTH" => $arResult['MONTH_NUMBER'], "PROPERTY_WEEK" => $arResult['WEEK_NUMBER']);
				$res 				= CIBlockElement::GetList(Array('PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>1), $arSelect);//dump($arFilter);
				if($ob = $res->GetNextElement())
				{
					$arFields 				= $ob->GetFields();
					//$arFields['PROPERTIES']	= $ob->GetProperties();
					$arResult['ERRORS'][]	= 'Отчет за указанную дату уже добавлялся';
				}
				//---
			}
		
			$arResult['PRICE_SUM']	= 0;
			
			foreach($_POST['FIELDS']['PRODUCT_ID'] as $k => $pid)
			{
				if(!array_key_exists($pid, $arResult['PRODUCTS']))
					continue;
					
				
				$product_section_id = $products[$pid]['IBLOCK_SECTION_ID'];
				if(intval($sections_ids[$product_section_id]['IBLOCK_SECTION_ID']))
					$product_cat_name = $sections_ids[$sections_ids[$product_section_id]['IBLOCK_SECTION_ID']]['NAME'];
				else
					$product_cat_name = $sections_ids[$product_section_id]['IBLOCK_SECTION_ID']['NAME'];
					
				//Первая буква заглавная
				$product_cat_name = mb_strtoupper(mb_substr($product_cat_name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product_cat_name, 1, mb_strlen($product_cat_name), 'UTF-8');
				
				$product 					= array();
				$product['ID']				= $pid;
				$product['NAME']			= $arResult['PRODUCTS'][$pid]['NAME'];
				$product['CATEGORY_NAME']	= $product_cat_name;
				$product['ARTICLE']			= $arResult['PRODUCTS'][$pid]['PROPERTY_ARTICLE_VALUE'];
				$product['PRICE']			= $_POST['FIELDS']['PRODUCT_PRICE'][$k];
				$product['COUNT']			= $_POST['FIELDS']['PRODUCT_COUNT'][$k];
				
				$arResult['FIELDS']['PRODUCTS'][]	= $product;
				
				$arResult['PRICE_SUM']	+= $product['PRICE'] * $product['COUNT'];
				
				//echo $arResult['PRICE_SUM'].'<br/>';
			}
			
//			echo serialize($arResult['FIELDS']['PRODUCTS']);
			//dump($arResult['FIELDS']['PRODUCTS']);
			//dump($_REQUEST);
			
			$product_string	= '';
			foreach($arResult['FIELDS']['PRODUCTS'] as $product)
			{
				$product_section_id = $products[$product['ID']]['IBLOCK_SECTION_ID'];
				if(intval($sections_ids[$product_section_id]['IBLOCK_SECTION_ID']))
					$product_cat_name = $sections_ids[$sections_ids[$product_section_id]['IBLOCK_SECTION_ID']]['NAME'];
				else
					$product_cat_name = $sections_ids[$product_section_id]['IBLOCK_SECTION_ID']['NAME'];
					
				//Первая буква заглавная
				$product_cat_name = mb_strtoupper(mb_substr($product_cat_name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product_cat_name, 1, mb_strlen($product_cat_name), 'UTF-8');
					
				$product_string .= ucfirst($product_cat_name).' '.$product['NAME'].', '.$product['COUNT'].'шт.'.';'."\r\n";
			}
			
			//echo $product_string;
			if(sizeof($arResult['ERRORS']) <= 0)
			{
				$el = new CIBlockElement;

				$report_name =  $arResult['CITY']['NAME'].'/'.$arResult['SHOP']['NAME'].', '
									.Intranet::getInstance()->GetMonthName($arResult['MONTH_NUMBER']).' '.$arResult['WEEK_NUMBER'].' неделя '.$arResult['REPORT_YEAR'].', '. getDaysFromWeek($arResult['WEEK_NUMBER']).', '.$arResult['FIO'];
				
				$PROP 					= array();
				$PROP['FIO'] 			= $arResult['FIO'];
				$PROP['USER_ID']		= $USER->GetID();
				$PROP['CITY']			= $arResult['CITY']['NAME'];
				$PROP['CITY_ID']		= $arResult['CITY']['ID'];
				$PROP['SHOP']			= $arResult['SHOP']['NAME'];
				$PROP['SHOP_ID']		= $arResult['SHOP']['ID'];
				$PROP['PRODUCTS']		= serialize($arResult['FIELDS']['PRODUCTS']);
				$PROP['PRICE']			= $arResult['PRICE_SUM'];
				$PROP['COMMENT']		= $arResult['FIELDS']['COMMENT'];
				$PROP['MARKETING']		= $arResult['FIELDS']['MARKETING'];
				
				$PROP['PRODUCTS_TEXT'][0] = Array("VALUE" => Array ("TEXT" => $product_string, "TYPE" => "text"));
				
				//$PROP['MONTH']		= date('Y.m', strtotime(date('Y').'W'.$week));
				//$PROP['WEEK']			= $arResult['REPORT_YEAR'].'.'.$arResult['WEEK_NUMBER'];
				
				$PROP['YEAR']			= $arResult['REPORT_YEAR'];
				$PROP['MONTH']			= $arResult['MONTH_NUMBER'];
				$PROP['WEEK']			= $arResult['WEEK_NUMBER'];
				//$PROP['MONTH']		= date('n', strtotime(date('Y').'W'.$week));
				//$PROP['YEAR']			= date('Y', strtotime(date('Y').'W'.$week));
				
				//$PROP['MONTH']		= date('n', strtotime(date('Y').'W'.$PROP['WEEK']));
				//$PROP['YEAR']			= date('Y', strtotime(date('Y').'W'.$PROP['WEEK']));
				//TODO: Заменить на !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Иначе может не правильно подставится год
				//$PROP['MONTH']		= date('n', strtotime($arResult['REPORT_YEAR'].'W'.$PROP['WEEK']));
				//$PROP['YEAR']			= date('Y', strtotime($arResult['REPORT_YEAR'].'W'.$PROP['WEEK']));
				
				
				$arLoadProductArray = Array(
					"MODIFIED_BY"    	=> $USER->GetID(),
					"IBLOCK_SECTION_ID" => 0,
					"IBLOCK_ID"      	=> Intranet::getInstance()->REPORT_IBLOCK_ID,
					"PROPERTY_VALUES"	=> $PROP,
					"NAME"           	=> $report_name,
					"ACTIVE"         	=> "Y",
					"PREVIEW_TEXT"   	=> "",
					"DETAIL_TEXT"    	=> "",
					"DETAIL_TEXT_TYPE" 	=> "text",
				);
//				dump($arLoadProductArray);exit();

				if(isset($arResult['REPORT']['ID']) && intval($arResult['REPORT']))
				{
					
					if($el->Update($arResult['REPORT']['ID'], $arLoadProductArray))
					{
						LocalRedirect('/intranet/?action=report_send_success');
					}
					else
						$arResult['ERRORS'][] = $el->LAST_ERROR;
				}
				else
				{
					if($PRODUCT_ID = $el->Add($arLoadProductArray))
					{
						echo "New ID: ".$PRODUCT_ID;
						$arResult['SUCCESSFUL']	= 1;
						
						
						$email_to = COption::GetOptionString("main", "email_from", "N"); 
						$arFields = Array(
								"REPORT_NAME" => $report_name,
								"FIO" => $arResult['FIO'],
								"CITY" => $arResult['CITY']['NAME'],
								"SHOP" => $arResult['SHOP']['NAME'],
								"PRODUCT_STRING" => $product_string,
								"PRICE" => $arResult['PRICE_SUM'],
								"COMMENT" => $arResult['FIELDS']['COMMENT'],
								"MARKETING" => $arResult['FIELDS']['MARKETING'],
								"WEEK" => $arResult['WEEK_NUMBER'],
								"YEAR" => $PROP['YEAR'],
								
							);
						CEvent::Send("NEW_REPORT", 's1', $arFields);
						
	
						LocalRedirect('/intranet/?action=report_send_success');
					}
					else
						$arResult['ERRORS'][] = $el->LAST_ERROR;
				}

				
				
			}
		}

		return $arResult;
	}
	
}