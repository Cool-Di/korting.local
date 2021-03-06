<?

class Intranet
{
	//Здесь хранится экземпляр класса
	private static $_Instance;
	
	public $PRODUCT_IBLOCK_ID	= 22;
	public $CITYSHOP_IBLOCK_ID	= 23;
	public $REPORT_IBLOCK_ID	= 24;
	public $SALE_PLAN_IBLOCK_ID	= 25;
	public $COMMENT_IBLOCK_ID	= 26;
    public $PERIOD_IBLOCK_ID	= 27;
	
	private $USER_GROUP_ACCESS_LEVEL	= array(
											1 => 100, 
											6 => 100, //пользователь панели управления
                                            10 => 100, //модератор
											8 => 10
										);
	public $USER_GROUP					= array(
											'SELLER' => 8, 
											'ADMIN' => 1,
                                            'MODERATOR' => 10
										);

    /**
     * Массив с спосоставлением статусов и их XML_ID
     * Запрос срабатывает только 1 раз, при обращении повторно выдаётся уже полученный результат
     * @var array
     */
	private $reportStatuses = [];

	/*
	 * ID текущего периода, который определяется по текущей дате
	 */
	private $currentPeriodId;
    private $currentPeriod;
	
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
	
	
	private function __construct()
	{
		//parent::__construct();
		CModule::IncludeModule('iblock');
	}
   
	private function __clone()
	{
    }

    private function __wakeup()
    {
    } 
    
    public function IsSeller()
    {
		global $USER;

		
		$arGroups	= array();
		
		if(intval($USER->GetID()))
		{
			$arGroups = CUser::GetUserGroup($USER->GetID());
			
			$seller_group_id = Intranet::getInstance()->USER_GROUP['SELLER'];
			
			if(in_array($seller_group_id, $arGroups))
				return 1;
		}

		return 0;
    }
    
    public function GetMonthName($month_number)
    {
	    $month_name = array(1 => "январь", 2 => "февраль", 3 => "март", 4 => "апрель", 5 => "май", 6 => "июнь", 7 => "июль", 8 => "август", 9 => "сентябрь", 10 => "октябрь", 11 => "ноябрь", 12 => "декабрь");
	    
    	if(intval($month_number))
		    return $month_name[$month_number];
		else
			return '';
    }
	
	public function GetAllIntranetUsers()
	{
		global $USER;
		
		$users		= array();
		
		$cUser 		= new CUser; 
		$sort_by 	= "LAST_NAME";
		$sort_ord 	= "ASC";

		$user_groups		= Intranet::getInstance()->USER_GROUP;
		$seller_group_id 	= Intranet::getInstance()->USER_GROUP['SELLER'];
		$arFilter 			= array("GROUPS_ID" => $seller_group_id);
		
		$dbUsers 		= $cUser->GetList($sort_by, $sort_ord, $arFilter, array("SELECT"=>array("UF_*")));
		while ($arUser = $dbUsers->Fetch()) 
		{
			$arUser['FIO']	= ($arUser['NAME'] != '' || $arUser['LAST_NAME'] != '') ? $arUser['LAST_NAME'].' '.$arUser['NAME'] : $arUser['LOGIN'];
			$users[$arUser['ID']]	= $arUser;
		}
		
		return $users;
	}
	
	public function GetUserAccessLevel()
	{
		global $USER;

		$access_level	= 0;

		$arGroups	= array();
		if(intval($USER->GetID()))
		{
			$arGroups = CUser::GetUserGroup($USER->GetID());
		}

		foreach($arGroups as $gid)
		{
			if(array_key_exists($gid, $this->USER_GROUP_ACCESS_LEVEL) && intval($this->USER_GROUP_ACCESS_LEVEL[$gid]))
			{
				if($this->USER_GROUP_ACCESS_LEVEL[$gid] > $access_level)
					$access_level	= $this->USER_GROUP_ACCESS_LEVEL[$gid];
			}
		}

		return $access_level;	
	}
	
	public function GetUserArr($user_id	= 0)
	{
		global $USER;
	
		if($USER->IsAuthorized() && $user_id == 0)
		{
			if(!$this->user_data)
			{
				$rsUser				= CUser::GetByID($USER->GetId());
				$arUser 			= $rsUser->Fetch();
				$arUser['FIO']	= ($arUser['NAME'] != '' || $arUser['LAST_NAME'] != '') ? $arUser['LAST_NAME'].' '.$arUser['NAME'] : $arUser['LOGIN'];
				$this->user_data	= $arUser;
			}
			return $this->user_data;
		}
		elseif(intval($user_id))
		{
			$rsUser				= CUser::GetByID($user_id);
			$arUser 			= $rsUser->Fetch();
			$arUser['FIO']	= ($arUser['NAME'] != '' || $arUser['LAST_NAME'] != '') ? $arUser['LAST_NAME'].' '.$arUser['NAME'] : $arUser['LOGIN'];
			return $arUser;
		}
		
		return 0;
	}
	
	public function GetProductList()
	{
		$products		= array();
		
		$arSelect 	= Array("ID", "NAME", "PROPERTY_EAN", "PROPERTY_ARTICLE");
		$arFilter 	= Array("IBLOCK_ID"=>Intranet::getInstance()->PRODUCT_IBLOCK_ID, "ACTIVE"=>"Y");
		$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>5000), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			
			$products[$arFields['ID']]	= $arFields;
		}
		
		return $products;		
	}	
	
	public function GetShopList()
	{
		$shops		= array();
		
		$arSelect 	= Array("ID", "NAME", "CODE");
		$arFilter 	= Array("IBLOCK_ID"=>Intranet::getInstance()->CITYSHOP_IBLOCK_ID, "ACTIVE"=>"Y");
		$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>500), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			
			$shops[$arFields['ID']]	= $arFields;
		}
		
		return $shops;		
	}
	
	public function GetCityShopList()
	{
		$cities		= array();
		$arSelect	= array('ID', 'NAME');
		$arFilter 	= array('IBLOCK_ID' => Intranet::getInstance()->CITYSHOP_IBLOCK_ID); // выберет потомков без учета активности
		$rsSect 	= CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);
		while ($arSect = $rsSect->GetNext())
		{
			$cities[$arSect['ID']]	= $arSect;
		}
		
		$arSelect 	= Array("ID", "NAME", "IBLOCK_SECTION_ID");
		$arFilter 	= Array("IBLOCK_ID"=>Intranet::getInstance()->CITYSHOP_IBLOCK_ID, "ACTIVE"=>"Y");
		$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>500), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			
			$cities[$arFields['IBLOCK_SECTION_ID']]['SHOPS'][]	= $arFields;
		}
		
		return $cities;
	}
	
	public function GetUserCityShop($user_id = 0)
	{
		global $USER;
		
		$arUser	= Intranet::getInstance()->GetUserArr($user_id);
		
		$arUser['UF_SHOP_ID'];
		
		if(intval($arUser['UF_SHOP_ID']))
		{
			$arSelect 	= Array("ID", "NAME", "IBLOCK_SECTION_ID");
			$arFilter 	= Array("IBLOCK_ID" => Intranet::getInstance()->CITYSHOP_IBLOCK_ID, "ACTIVE" => "Y", "ID" => $arUser['UF_SHOP_ID']);
			$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>500), $arSelect);
			if($ob = $res->GetNextElement())
			{
				$arFields = $ob->GetFields();
				
				$shop	= $arFields;
				
				$cities		= array();
				$arSelect	= array('ID', 'NAME');
				$arFilter 	= array('IBLOCK_ID' => Intranet::getInstance()->CITYSHOP_IBLOCK_ID, 'ID' => $shop['IBLOCK_SECTION_ID']);
				$rsSect 	= CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);
				if ($arSect = $rsSect->GetNext())
				{
					$city	= $arSect;
				}
			
				$city_shop	= array('city' => $city, 'shop' => $shop);
				return $city_shop;
			}
		}
		
		return 0;
	}
	
	public function GetUserSalePlan($month = 0, $year = 0, $user_id = 0)
	{
		global $USER;
		
		if(!intval($month))
			$month = date('m');
			
		if(!intval($year))
			$year = date('Y');	
			
		if(!intval($user_id))
			$user_id = $USER->GetID();
		
		$sale_plan 	= 0;
			
		$arSelect 	= Array("ID", "NAME", "PROPERTY_USER_ID", "PROPERTY_MONTH", "PROPERTY_YEAR", "PROPERTY_SALE_PLAN");
		$arFilter 	= Array("IBLOCK_ID" => Intranet::getInstance()->SALE_PLAN_IBLOCK_ID, "PROPERTY_USER_ID" => $user_id, "PROPERTY_MONTH" => $month, "PROPERTY_YEAR" => $year);
		$res 		= CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			
			$sale_plan 	= $arFields['PROPERTY_SALE_PLAN_VALUE'];
		}
		
		return $sale_plan;
	}
	
	public function GetMonthSale($month = 0, $year = 0,  $user_id = 0)
	{
		global $USER;
		
		if(!intval($user_id))
			$user_id = $USER->GetID();
		
		if(!intval($month))
			$month	= date('m');
			
		if(!intval($year))
			$year	= date('Y');

		$year_month	= $year.'.'.str_pad($month, 2, '0', STR_PAD_LEFT);
		
		
		$adopted_sum		= 0;
		$unadopted_sum		= 0;
		$reports			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", 'PROPERTY_PRICE', 'PROPERTY_MONTH', 'PROPERTY_ADOPTED');
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, 'PROPERTY_USER_ID' => $user_id, 'PROPERTY_MONTH' => $month, 'PROPERTY_YEAR' => $year);
		$res 				= CIBlockElement::GetList(Array('PROPERTIES_WEEK' => 'asc'), $arFilter, false, Array("nTopCount"=>100), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			//$arFields['PROPERTIES']	= $ob->GetProperties();
			
			if($arFields['PROPERTY_ADOPTED_VALUE'] == 'Да')
				$adopted_sum	+= $arFields['PROPERTY_PRICE_VALUE'];
			else
				$unadopted_sum	+= $arFields['PROPERTY_PRICE_VALUE'];
				
				
			$reports[]				= $arFields;
		}
		
		$month_sale	= array('adopted' => $adopted_sum, 'unadopted' => $unadopted_sum);
		
		return $month_sale;
	}
	
	public function GetAllReports()
	{
		$reports			= array();
		$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", 'PROPERTY_PRICE', 'PROPERTY_MONTH', 'PROPERTY_ADOPTED');
		$arFilter 			= Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID);
		$res 				= CIBlockElement::GetList(Array('PROPERTY_YEAR' => 'DESC', 'PROPERTY_WEEK' => 'DESC'), $arFilter, false, Array("nTopCount"=>10), $arSelect);
		
		
		while($ob = $res->GetNextElement())
		{
			$arFields 				= $ob->GetFields();
			//$arFields['PROPERTIES']	= $ob->GetProperties();
				
			$reports[]				= $arFields;
		}
        echo 'ddd';
		return $reports;
	}

    /**
     * Поулчение id статуса по его xml_id
     * Запрос на полчение статусов выполняется 1 раз, при повторном обращении используются уже полученные данные
     * @param $xml_id
     * @return array
     * @throws Exception
     */
    public function getReportStatusIdByXmlId($xml_id)
    {
        if (empty($this->reportStatuses)) {
            $this->initReportStatuses();
        }

        if ($this->reportStatuses[$xml_id]) {
            return $this->reportStatuses[$xml_id];
        } else {
            throw new \Exception('Статус не найден');
        }
    }

    /**
     * Запрос на заполнение массива статусов продажи
     */
    private function initReportStatuses()
    {
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => $this->REPORT_IBLOCK_ID, "CODE" => "STATUS"));
        while ($enum_fields = $property_enums->GetNext()) {
            $this->reportStatuses[$enum_fields['XML_ID']] = $enum_fields['ID'];
        }
    }

    public function getCurrentPeriodId() {
        if($this->currentPeriodId) {
            return $this->currentPeriodId;
        } else {
            $arSelect = Array("ID", "NAME", "ACTIVE_FROM", "ACTIVE_TO", "PROPERTY_LAST_DAY");
            $arFilter = Array(
                "IBLOCK_ID" => Intranet::getInstance()->PERIOD_IBLOCK_ID,
                "ACTIVE_DATE" => "Y",
            );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            if ($period = $res->Fetch()) {
                $this->currentPeriodId = $period['ID'];
            } else {
                throw new \Exception('В БД не заведён отчетный период, соответстующий текущей дате');
            }
            return $this->currentPeriodId;
        }
    }

    public function getCurrentPeriod() {
        if($this->currentPeriod) {
            return $this->currentPeriod;
        } else {
            $arSelect = Array("ID", "NAME", "ACTIVE_FROM", "ACTIVE_TO", "PROPERTY_LAST_DAY");
            $arFilter = Array(
                "IBLOCK_ID" => Intranet::getInstance()->PERIOD_IBLOCK_ID,
                "ACTIVE_DATE" => "Y",
            );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            if ($period = $res->Fetch()) {
                $this->currentPeriod = $period;
            } else {
                throw new \Exception('В БД не заведён отчетный период, соответстующий текущей дате');
            }
            return $this->currentPeriod;
        }
    }

    /**
     * Получение следующего периода по ID предыдущего
     * @param int $prevPeriodId
     * @param bool $desc - если false то берётся следующий период, если true берётся предыдущий период
     * @return null
     */
    public static function getNextPeriod(int $prevPeriodId, $desc = false)
    {
        $isFoundPrev = false;
        $nextPeriod = null;
        if($desc) {
            $sortType = 'DESC';
        } else {
            $sortType = 'ASC';
        }
        //Отчётные периоды должны быть отсортированы в порядке возврастания, если сортировка одинаковая, то по возрастанию ID
        $arSelect = Array("ID", "NAME", "ACTIVE_FROM", "ACTIVE_TO", "PROPERTY_BONUS_DAYS");
        $arFilter = Array(
            "IBLOCK_ID" => Intranet::getInstance()->PERIOD_IBLOCK_ID,
            "ACTIVE" => "Y"
        );
        $res = CIBlockElement::GetList(Array("SORT" => $sortType, "ID" => $sortType), $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            if($isFoundPrev) {
                $nextPeriod = $arFields;
                break;
            } elseif($prevPeriodId == $arFields["ID"]) {
                $isFoundPrev = true;
            }
        }

        return $nextPeriod;
    }
}



