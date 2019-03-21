<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Календарь");

require_once 'strptimefix.php';

// Получение данных из Google calendar, формирование массива с событиями
require_once 'getCalendar.php';

$EventsColors = array();
foreach ((array)$COLORS->calendar as $key => $color){
	$EventsColors[$key] = $color;
}
$KortingEvents = array();
$EventsArray = (array)$EVENTS->items;
foreach($EventsArray as $event){
	$Start = strptime($event->start->dateTime, "%Y-%m-%dT%H:%M");
	$End = strptime($event->end->dateTime, "%Y-%m-%dT%H:%M");

	$StartDataStr = $Start["tm_mday"].($Start["tm_mon"]+1).($Start["tm_year"]+1900);
	$EndDataStr = $Start["tm_mday"].($Start["tm_mon"]+1).($Start["tm_year"]+1900);
	
	//echo date('H:i', strtotime($event->start->dateTime))."<br/>";
	//$date = DateTime::createFromFormat('Y-m-d\TH:i\Z', $event->start->dateTime);
	//if($date)
	//echo "<pre>".print_r($Start, 1)."</pre>";
	//echo  date("j");
	$ColorID = $event->colorId;
	if(isset($ColorID)){
		$COLOR = $EventsColors[$event->colorId];
	}
	if($Start["tm_min"]>=0 && $Start["tm_min"]<=9 )
		$Start["tm_min"] = "0".$Start["tm_min"];
	if($End["tm_min"]>=0 && $End["tm_min"]<=9 )
		$End["tm_min"] = "0".$End["tm_min"];
		
	$KortingEvents[$StartDataStr][] = array(
		"NAME" => (string)$event->summary,
		"LOCATION" => (string)$event->location,
		"DESCRIPTION" => (string)$event->description,
		"COLOR" => $COLOR->background,
		"END" => (string)$EndDataStr,
		"HOUR_START" => $Start["tm_hour"],
		"MIN_START" => $Start["tm_min"],
		"HOUR_END" => $End["tm_hour"],
		"MIN_END" => $End["tm_min"]
	);
	//date('H', strtotime($event->start->dateTime)+14400), 
	
}
?>
<?	// Класс для работы с календарем
	Class KortingCalendar{
	
		var $DayOfWeekArray = array("Пн","Вт","Ср","Чт","Пт","Сб","Вc");	
		var $MonthArray = array("1"=>"Январь","2"=>"Февраль","3"=>"Март","4"=>"Апрель","5"=>"Май","6"=>"Июнь","7"=>"Июль","8"=>"Август","9"=>"Сентябрь","10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");
		var $ClockArray = array("0:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00");
		var $Month;
		var $Year;
		var $NextYear;
		var $PrevYear;
		var $NextMonth;
		var $Day;
		var $NumDaysInMonth;
		var $startDayInMonth;
		var $Week;
		var $CurWeek;
		var $CurHour;
		var $CurMin;
		var $PrevDay;
		var $WeekDay;
		var $APPLICATION;
		
		function __construct($APPLICATION){
			$this->APPLICATION = $APPLICATION;
		}
		
		// Текущий час
		public function getCurrentHour(){
			$this->CurHour = date("H");
			return $this->CurHour;
		}
		// Текущая минута
		public function getCurrentMin(){
			$this->CurMin = date("i");
			return $this->CurMin;
		}
		// Текущий день
		public function getCurrentDay(){
			$this->Day = date("j");
			return $this->Day;
		}
		// Получить день если !$day то текущий день 
		public function getDay($day = ''){
			if(isset($day) && $day != ''){
				$this->Day = $day;
			}else{
				$this->Day = date("j");
			}
			return $this->Day;
		}
		// Ссылка "назад"
		public function getPrev($name, $day, $month, $year, $week = ""){	
			switch ($name) {
				case "day":
					if($day == 1){	
						if($month == 1)
							$month = 12;
						else 
							$month = $month - 1;
						if($month == 12)
							$year = $year - 1;
						$day = 	date("t", mktime(0,0,0,$month,1,$year));
					}else{
						$day = $day - 1;
					}	
					return "?d=".$day."&m=".$month."&y=".$year."&calendar=day";
				case "month":
					if($month == 1)
						$month = 12;
					else 
						$month = $month - 1;
					if($month == 12)
						$year = $year - 1;
					return "?m=".$month."&y=".$year."&calendar=month";
				case "week":
					if($week == 1){
						$week = 52;
						$year = $year - 1;
					}	
					else	
						$week = $week - 1 ;
					
					return "?w=".$week."&y=".$year."&calendar=week";
			}
		}
		// Ссылка "вперед"
		public function getNext($name, $day, $month, $year, $week = ""){	
			switch ($name) {
				case "day":
					if($day == $this->getNumDaysInMonth($month, $year)){	
						$day = 1;
						if($month == 12){
							$month = 1;
							$year = $year + 1;
						}else{
							$month = $month + 1;
						
						}
					}else{
						$day = $day + 1;
					}	
					return "?d=".$day."&m=".$month."&y=".$year."&calendar=day";
				case "month":
					if($month == 12){
						$month = 1;
						$year = $year + 1;
					}
					else 
						$month = $month + 1;

					return "?&m=".$month."&y=".$year."&calendar=month";	
				case "week":
					if($week == 52){
						$week = 1;
						$year = $year + 1;
					}	
					else	
						$week = $week + 1 ;
					
					return "?w=".$week."&y=".$year."&calendar=week";	
			}
		}
		// Текущий месяц
		public function getCurrentMonth(){
			$this->Day = date("n");
			return $this->Day;
		}
		// Текущий год
		public function getCurrentYear(){
			$this->Year = date("Y");
			return $this->Year;
		}
		// Получить неделю, если !$week то текущая неделя 
		public function getWeek($week){
			if(isset($week)){
				$this->Week = $week;
			}else{
				$this->Week = date("W");
			}
			return $this->Week;
		}
		// Получить текущий день недели 
		public function getCurrentWeek(){
			$this->CurWeek = date("w");
			return $this->CurWeek;
		}
		// Получить год, если !$year то текущий год 
		public function getYear($year){
			if(isset($year)){
				$this->Year = $year;
			}else{
				$this->Year = date("Y");
			}
			return $this->Year;
		}
		// Количество дней в месяце
		public function getNumDaysInMonth($month, $year){
			if(!isset($month) || !isset($year))
				return false;
			else{	
				$this->NumDaysInMonth = date("t", mktime(0,0,0,$month,1,$year));
				return $this->NumDaysInMonth;
			}	
		}
		// Порядковый номер дня недели в месяце
		public function getStartDayInMonth($month, $year){
			if(!isset($month) || !isset($year))
				return false;
			else{
				$this->startDayInMonth = date("w", mktime(0,0,0,$month,1,$year));
				return $this->startDayInMonth;
			}	
		}
		// Предыдущий год
		public function getPossiblePrevYear($prev_month, $year){
			if($prev_month == 12)
				$this->PrevYear = $year - 1;
			else
				$this->PrevYear = $year;
			return $this->PrevYear;
		}
		// Следующий год
		public function getPossibleNextYear($next_month,$year){
			if($next_month == 1)
				$this->NextYear = $year + 1;
			else
				$this->NextYear = $year;
			return $this->NextYear;
		}
		// Получить месяц если !$month то текущий месяц 
		public function getMonth($month){
			if(isset($month)){
				$this->Month = $month;
			}else{
				$this->Month = date("n");
			}
			return $this->Month;
		}
		// Предыдузий месяц
		public function getPrevMonth($month){
			if($month == 1)
				$this->PrevMonth = 12;
			else
				$this->PrevMonth = $month - 1;
			return $this->PrevMonth;
		}
		// Следующий месяц
		public function getNextMonth($month){
			if($month == 12)
				$this->NextMonth = 1;
			else
				$this->NextMonth = $month + 1;
			
			return $this->NextMonth;
		}
		// Получить дни предыдущего месяца, которые будут отображаться на календаре
		public function getPrevMonthDays($prev_month, $year, $start_day_in_month){
			$arr = array();
			$num_days = $this->getNumDaysInMonth($prev_month, $year);
			for($i=1;$i<=$num_days;$i++){
					$arr[] = $i;
			}
			return  array_slice($arr, $num_days - $start_day_in_month + 1, $num_days);
		}
		// Получить дни следующего месяца, которые будут отображаться на календаре
		public function getNextMonthDays($next_month, $year){
			$arr = array();
			$num_days = $this->getNumDaysInMonth($next_month, $year);
			for($i=1;$i<=$num_days;$i++){
					$arr[] = $i;
			}
			return  $arr;
		}
		// Получить HTML код события в таблице дня
		public function getHtmlEventInDay($day, $month, $year, $korting_events){
			$key = $day.$month.$year;
			if (array_key_exists($key, $korting_events)){
				$html = '<div class="wrap day">';
				foreach($korting_events[$key] as $event){
					$hour = $event["HOUR_START"]*41 + $event["MIN_START"]*41/60;
					$height = ((float)($event["HOUR_END"].".".$event["MIN_END"]) - (float)($event["HOUR_START"].".".$event["MIN_START"]))*41;
						
					$html .=	'
						<div class="wrap_event" style="top:'.$hour.'px; height: '.$height.'px;">
						<div class="detail">
							<b class="name">'.strtoupper($event["NAME"]).'</b>
							'.$event["DESCRIPTION"].'
							<table width="100%">
								<tr>
									<td class="title"><b>Время проведения:</b></td><td>'.$event["HOUR_START"].':'.$event["MIN_START"].' - '.$event["HOUR_END"].':'.$event["MIN_END"].'</td>
								</tr>
								<tr>		
									<td class="title"><b>Место проведения:</b></td><td>'.$event["LOCATION"].'</td>
								</tr>
								<tfoot>
									<tr>
										<td colspan="2">
											<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q='.$event["LOCATION"].'">Посмотреть на карте google</a>
										</td>
									</tr>
									<tr>
										<td colspan="2">
										'.$this->APPLICATION->IncludeComponent(
											"korting:question",
											"sign_up_for_event",
											Array(),
											false
											).'
										</td>
									</tr>
								</tfoot>
							</table>	
							<a href="#" class="close">X</a>
							<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
						</div> 	
						<a href="#" class="event">'.$event["NAME"].'</a>
						</div>';
				}
				$html .= '</div>';				
				return $html;
			}
		}
		// Получить HTML код события в таблице месяца
		public function getHtmlEventInMonth($day, $month, $year, $korting_events){
			$key = $day.$month.$year;
			
			$html =	'<div class="wrap month">
				<div class="data"><b>'.$day.'</b></div>';
					if (array_key_exists($key, $korting_events)){
						foreach($korting_events[$key] as $event){
							$html.='<div class="wrap_event">
								<div class="detail">
									<b class="name">'.strtoupper($event["NAME"]).'</b>
									'.$event["DESCRIPTION"].'
									<table width="100%">
										<tr>
											<td class="title"><b>Время проведения:</b></td><td>'.$event["HOUR_START"].':'.$event["MIN_START"].' - '.$event["HOUR_END"].':'.$event["MIN_END"].'</td>
										</tr>
										<tr>		
											<td class="title"><b>Место проведения:</b></td><td>'.$event["LOCATION"].'</td>
										</tr>
										<tfoot>
											<tr>
												<td colspan="2">
													<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q='.$event["LOCATION"].'">Посмотреть на карте google</a>
												</td>
											</tr>
											<tr>
												<td colspan="2">
												'.$this->APPLICATION->IncludeComponent(
											"korting:question",
											"sign_up_for_event",
											Array(),
											false
											).'
												</td>
											</tr>
										</tfoot>
									</table>	
									<a href="#" class="close">X</a>
									<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
								</div> 
								<a href="#" class="event">		
									<div class="time">'.$event["HOUR_START"].':'.$event["MIN_START"].' - '.$event["HOUR_END"].':'.$event["MIN_END"].'</div>
									<div class="name">'.$event["NAME"].'</div>
								</a>
							</div>';
						}
					}	
			$html.= '</div>';
			return $html;
		}
		// Получить дату дня недели
		public function getDataWeekDay($day, $week_number, $year){
			$this->WeekDay = date('jnY', strtotime($year.'W'.$week_number.$day)); 
			return $this->WeekDay;
		}
		// Получить первый и последний день недели, месяц
		public function getLastWeekDayAndMonth($week_number, $year){ 
			//if($week_number < 10)
			//	$week_number = "0".$week_number;
	
			$start_day = date('j', strtotime($year.'W'.$week_number.'1'));
			$end_day = date('j', strtotime($year.'W'.$week_number.'7'));
			$start_month = date('n', strtotime($year.'W'.$week_number.'1'));
			$end_month = date('n', strtotime($year.'W'.$week_number.'7'));
			$start_year = date('Y', strtotime($year.'W'.$week_number.'1'));
			$end_year = date('Y', strtotime($year.'W'.$week_number.'7'));

			$arr = array(
				"START_DAY" => $start_day,
				"START_MONTH" => $start_month,
				"END_DAY" => $end_day,
				"END_MONTH" => $end_month,
				"START_YEAR" => $start_year,
				"END_YEAR" => $end_year,
				
			);
			
			return $arr;
		}
		// Получить HTML код события в таблице недели
		public function getHtmlEventInWeek($key, $korting_events){
			if (array_key_exists($key, $korting_events)){
				$html = '<div class="wrap week">';
					$i = 70000; // для z-index
					foreach($korting_events[$key] as $event){
						$hour = $event["HOUR_START"]*41 + $event["MIN_START"]*41/60;
						$height = ((float)($event["HOUR_END"].".".$event["MIN_END"]) - (float)($event["HOUR_START"].".".$event["MIN_START"]))*41;
						
						$html .=	'
						<div class="wrap_event" style="top:'.$hour.'px; height: '.$height.'px; z-index: '.$i.';">
						<div class="detail">
							<b class="name">'.strtoupper($event["NAME"]).'</b>
							'.$event["DESCRIPTION"].'
							<table width="100%">
								<tr>
									<td class="title"><b>Время проведения:</b></td><td>'.$event["HOUR_START"].':'.$event["MIN_START"].' - '.$event["HOUR_END"].':'.$event["MIN_END"].'</td>
								</tr>
								<tr>		
									<td class="title"><b>Место проведения:</b></td><td>'.$event["LOCATION"].'</td>
								</tr>
								<tfoot>
									<tr>
										<td colspan="2">
											<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q='.$event["LOCATION"].'">Посмотреть на карте google</a>
										</td>
									</tr>
									<tr>
										<td colspan="2">
										'.$this->APPLICATION->IncludeComponent(
											"korting:question",
											"sign_up_for_event",
											Array(),
											false
											).'
										</td>
									</tr>
								</tfoot>
							</table>	
							<a href="#" class="close">X</a>
							<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
						</div> 	
						<a href="#" class="event">'.$event["NAME"].'</a>
						</div>';
						$i = $i - 10000;
					}
				$html .= '';
			}
			return $html;
		}
		// Получить день и месяц в неделе
		public function getDayMonthWeekDay($day, $week_number, $year){
			return date('j/n', strtotime($year.'W'.$week_number.$day)); 
		}
	}

	$Calendar = new KortingCalendar($APPLICATION);
	
	
	$MONTH = $Calendar->getMonth($_GET["m"]);
	$YEAR  = $Calendar->getYear($_GET["y"]);
	$DAY = $Calendar->getDay($_GET["d"]);
	$WEEK = $Calendar->getWeek($_GET["w"]);
	
	$MONTH_NAME = $Calendar->MonthArray[$MONTH];
	$DAY_NAME = $Calendar->DayOfWeekArray;
	
	$START_DAY_IN_MONTH = $Calendar->getStartDayInMonth($MONTH, $YEAR);
	$PREV_MONTH = $Calendar->getPrevMonth($MONTH);
	$NEXT_MONTH = $Calendar->getNextMonth($MONTH);
	$POSSIBLE_PREV_YEAR = $Calendar->getPossiblePrevYear($PREV_MONTH, $YEAR);
	$POSSIBLE_NEXT_YEAR = $Calendar->getPossibleNextYear($NEXT_MONTH, $YEAR);
//	if($START_DAY_IN_MONTH > 1){
	if($START_DAY_IN_MONTH == 0)
		$START_DAY_IN_MONTH = 7;
	if($START_DAY_IN_MONTH > 1){
		$PREV_MONTH_DAY_ARRAY = $Calendar->getPrevMonthDays($PREV_MONTH, $POSSIBLE_PREV_YEAR, $START_DAY_IN_MONTH);
	}
	$NEXT_MONTH_DAY_ARRAY = $Calendar->getNextMonthDays($NEXT_MONTH, $POSSIBLE_NEXT_YEAR);
	$CURRENT_DAY = $Calendar->getDay();
	$CURRENT_MONTH = $Calendar->getCurrentMonth();
	$CURRENT_YEAR = $Calendar->getCurrentYear();
	$_DAY = 1; // Счетчик по дням
	$NUM_DAYS_IN_MONTH = $Calendar->getNumDaysInMonth($MONTH, $YEAR);
	
	$CURRENT_HOUR = $Calendar->getCurrentHour();
	$CURRENT_MIN = $Calendar->getCurrentMin();
	$CURRENT_DAY = $Calendar->getCurrentDay();
	$CURRENT_WEEK_DAY_NUM = $Calendar->getCurrentWeek();
	
	$WEEK_DAY_MONTH_ARRAY = $Calendar->getLastWeekDayAndMonth($WEEK, $YEAR);
?>	

<div id="calendar">
	<div class="wrap_calendar">
		<ul class="tab">
			<li class="<?if($_GET["calendar"] == "month" || !isset($_GET["calendar"])):?>active<?endif;?>"><a class="item" href="?calendar=month">Месяц</a></li>
			<li class="<?if($_GET["calendar"] == "week"):?>active<?endif;?>"><a class="item" href="?calendar=week">Неделя</a></li>
			<li class="<?if($_GET["calendar"] == "day"):?>active<?endif;?>"><a class="item" href="?calendar=day">День</a></li>
		</ul>
		<div class="clear"></div>
		
		<!-- Таблица месяца -->
		<div class="wrap_table <?if($_GET["calendar"] == "month" || !isset($_GET["calendar"])):?>active<?endif;?>">
			<div class="scrolling"><a href="<?=$Calendar->getPrev("month", $DAY, $MONTH, $YEAR)?>">&#8592;</a><?=$MONTH_NAME?>, <?=$YEAR?><a href="<?=$Calendar->getNext("month", $DAY, $MONTH, $YEAR)?>">&#8594;</a></div>
			<table width="100%" class="calendar">
				<tr>
					<?foreach($DAY_NAME as $day):?>
					<th><?=$day?></th>
					<?endforeach;?>
				</tr>
				<?for($i = 0; $i < 6; $i++):?>
					<tr>
						<?for($j = 1; $j <= 7; $j++):?>
							<?if(count($PREV_MONTH_DAY_ARRAY) > 0):?>
								<?$PDAY = $PREV_MONTH_DAY_ARRAY[0];?>
								<td class="month_cell">
									<?//=$Calendar->getHtmlEventInMonth($PDAY, $PREV_MONTH, $POSSIBLE_PREV_YEAR, $KortingEvents);?>
										<div class="wrap month">
											<div class="data"><b><?=$PDAY?></b></div>
												<?	$key = $PDAY.$PREV_MONTH.$POSSIBLE_PREV_YEAR;
													if (array_key_exists($key, $KortingEvents)):?>
													<?foreach($KortingEvents[$key] as $event):?>
														<div class="wrap_event">
															<div class="detail">
																<b class="name"><?=strtoupper($event["NAME"])?></b>
																<?=$event["DESCRIPTION"]?>
																<table width="100%">
																	<tr>
																		<td class="title"><b>Время проведения:</b></td><td><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></td>
																	</tr>
																	<tr>		
																		<td class="title"><b>Место проведения:</b></td><td><?=$event["LOCATION"]?></td>
																	</tr>
																	<tfoot>
																		<tr>
																			<td colspan="2">
																				<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q=<?=$event["LOCATION"]?>">Посмотреть на карте google</a>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																			<?$APPLICATION->IncludeComponent(
																				"korting:sign_up_for_events",
																				"",
																				Array("EVENT_NAME" => $event["NAME"], 
																					"LOCATION" => $event["LOCATION"],
																					"TIME" => $event["HOUR_START"].":".$event["MIN_START"]." - ".$event["HOUR_END"].":".$event["MIN_END"] 
																					),
																				false
																			);?>
																			</td>
																		</tr>
																	</tfoot>
																</table>	
																<a href="#" class="close">X</a>
																<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
															</div> 
															<a href="#" class="event">		
																<div class="time"><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></div>
																<div class="name"><?=$event["NAME"]?></div>
															</a>
														</div>
													<?endforeach;?>
											<?endif;?>	
										</div>
								</td>
								<?array_shift($PREV_MONTH_DAY_ARRAY);?>
								<?$PDAY = '';?>
							<?elseif(count($NEXT_MONTH_DAY_ARRAY) > 0 && $_DAY>$NUM_DAYS_IN_MONTH):?>
								<?$NDAY = $NEXT_MONTH_DAY_ARRAY[0];?>
								<td class="month_cell">
									<?//=$Calendar->getHtmlEventInMonth($NDAY, $NEXT_MONTH, $POSSIBLE_NEXT_YEAR, $KortingEvents);?>
									<div class="wrap month">
											<div class="data"><b><?=$NDAY?></b></div>
												<?	$key = $NDAY.$NEXT_MONTH.$POSSIBLE_NEXT_YEAR;
													if (array_key_exists($key, $KortingEvents)):?>
													<?foreach($KortingEvents[$key] as $event):?>
														<div class="wrap_event">
															<div class="detail">
																<b class="name"><?=strtoupper($event["NAME"])?></b>
																<?=$event["DESCRIPTION"]?>
																<table width="100%">
																	<tr>
																		<td class="title"><b>Время проведения:</b></td><td><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></td>
																	</tr>
																	<tr>		
																		<td class="title"><b>Место проведения:</b></td><td><?=$event["LOCATION"]?></td>
																	</tr>
																	<tfoot>
																		<tr>
																			<td colspan="2">
																				<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q=<?=$event["LOCATION"]?>">Посмотреть на карте google</a>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																			<?$APPLICATION->IncludeComponent(
																				"korting:sign_up_for_events",
																				"",
																				Array("EVENT_NAME" => $event["NAME"], 
																					"LOCATION" => $event["LOCATION"],
																					"TIME" => $event["HOUR_START"].":".$event["MIN_START"]." - ".$event["HOUR_END"].":".$event["MIN_END"] 
																					),
																				false
																			);?>
																			</td>
																		</tr>
																	</tfoot>
																</table>	
																<a href="#" class="close">X</a>
																<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
															</div> 
															<a href="#" class="event">		
																<div class="time"><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></div>
																<div class="name"><?=$event["NAME"]?></div>
															</a>
														</div>
													<?endforeach;?>
											<?endif;?>	
									</div>
								</td>
								<?array_shift($NEXT_MONTH_DAY_ARRAY);?>
								<?$NDAY = '';?>
							<?else:?>	
								<?if($_DAY<=$NUM_DAYS_IN_MONTH):?>
									<?if($_DAY == $CURRENT_DAY && $MONTH == $CURRENT_MONTH && $YEAR == $CURRENT_YEAR) $class="current"; else $class="";?>
									<td class="<?=$class?> month_cell">
										<?//=$Calendar->getHtmlEventInMonth($_DAY, $MONTH, $YEAR, $KortingEvents);?>
										
										<div class="wrap month">
											<div class="data"><b><?=$_DAY?></b></div>
												<?	$key = $_DAY.$MONTH.$YEAR;
													if (array_key_exists($key, $KortingEvents)):?>
													<?foreach($KortingEvents[$key] as $event):?>
														<div class="wrap_event">
															<div class="detail">
																<b class="name"><?=strtoupper($event["NAME"])?></b>
																<?=$event["DESCRIPTION"]?>
																<table width="100%">
																	<tr>
																		<td class="title"><b>Время проведения:</b></td><td><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></td>
																	</tr>
																	<tr>		
																		<td class="title"><b>Место проведения:</b></td><td><?=$event["LOCATION"]?></td>
																	</tr>
																	<tfoot>
																		<tr>
																			<td colspan="2">
																				<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q=<?=$event["LOCATION"]?>">Посмотреть на карте google</a>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																			<?$APPLICATION->IncludeComponent(
																				"korting:sign_up_for_events",
																				"",
																				Array("EVENT_NAME" => $event["NAME"], 
																					"LOCATION" => $event["LOCATION"],
																					"TIME" => $event["HOUR_START"].":".$event["MIN_START"]." - ".$event["HOUR_END"].":".$event["MIN_END"] 
																					),
																				false
																			);?>
																			</td>
																		</tr>
																	</tfoot>
																</table>	
																<a href="#" class="close">X</a>
																<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
															</div> 
															<a href="#" class="event">		
																<div class="time"><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></div>
																<div class="name"><?=$event["NAME"]?></div>
															</a>
														</div>
													<?endforeach;?>
											<?endif;?>	
									</div>
									</td>
									<?$_DAY++;?>
								<?endif;?>
							<?endif;?>
							
						<?endfor;?>
					</tr>
				<?endfor;?>		
			</table>	
		</div>
		<!-- Таблица недели -->
		<div class="wrap_table <?if($_GET["calendar"] == "week"):?>active<?endif;?>">
			<div class="scrolling"><a href="<?=$Calendar->getPrev("week", $DAY, $MONTH, $WEEK_DAY_MONTH_ARRAY["START_YEAR"], $WEEK)?>">&#8592;</a>
				<?=$WEEK_DAY_MONTH_ARRAY["START_DAY"]?> - <?=$WEEK_DAY_MONTH_ARRAY["END_DAY"]?>, 
				<?if($WEEK_DAY_MONTH_ARRAY["START_MONTH"] == $WEEK_DAY_MONTH_ARRAY["END_MONTH"]):?>
					<?=$Calendar->MonthArray[$WEEK_DAY_MONTH_ARRAY["START_MONTH"]]?>
				<?else:?>
				<?=$Calendar->MonthArray[$WEEK_DAY_MONTH_ARRAY["START_MONTH"]]?> - <?=$Calendar->MonthArray[$WEEK_DAY_MONTH_ARRAY["END_MONTH"]]?>
				<?endif;?>, 
				<?if($WEEK_DAY_MONTH_ARRAY["START_YEAR"] == $WEEK_DAY_MONTH_ARRAY["END_YEAR"]):?>
					<?=$WEEK_DAY_MONTH_ARRAY["START_YEAR"]?>
				<?else:?>
				<?=$WEEK_DAY_MONTH_ARRAY["START_YEAR"]?> - <?=$WEEK_DAY_MONTH_ARRAY["END_YEAR"]?>
				<?endif;?>
					
			<a href="<?=$Calendar->getNext("week", $DAY, $MONTH, $WEEK_DAY_MONTH_ARRAY["START_YEAR"], $WEEK)?>">&#8594;</a></div>
			<table width="100%" class="calendar">
				<tr>
					<th>&nbsp;</th>
					<?foreach($DAY_NAME as $key=>$day):?>
						<th class="<?if($CURRENT_WEEK_DAY_NUM==$key+1):?>current<?endif;?>"><?=$day?>&nbsp;<?=$Calendar->getDayMonthWeekDay($key+1, $WEEK, $YEAR)?></th>
					<?endforeach;?>
					
				</tr>
				<tr>
					<td class="clock">
						<?	$HourNum = count($Calendar->ClockArray)-1;
							foreach($Calendar->ClockArray as $key=>$clock):?>
							<div class="<?if($key == $CURRENT_HOUR):?>cur_hour<?endif;?><?if($key == $HourNum):?> last <?endif;?>">
								<?if($key == $CURRENT_HOUR):?>
									<img class="cur_min" src="/media/images/cur_min.png" style="top:<?=$CURRENT_MIN*40/60-4?>px;"/>
								<?endif;?>
								<?=$clock?>
							</div>
						<?endforeach;?>
					</td>
					<?for($j = 1; $j <= 7; $j++):?>
					<td class="day_of_week <?if($j==7):?>last<?endif;?> <?if($CURRENT_WEEK_DAY_NUM==$j):?>current<?endif;?>">
						<?//=$Calendar->getHtmlEventInWeek($Calendar->getDataWeekDay($j, $WEEK, $YEAR), $KortingEvents);?>
						<?	$key = $Calendar->getDataWeekDay($j, $WEEK, $YEAR);
							if (array_key_exists($key, $KortingEvents)):?>
							<div class="wrap week">
								<?$i = 70000; // для z-index ?>
								<?foreach($KortingEvents[$key] as $event):?>
									<?$hour = $event["HOUR_START"]*41 + $event["MIN_START"]*41/60;
									$height = ((float)($event["HOUR_END"].".".$event["MIN_END"]) - (float)($event["HOUR_START"].".".$event["MIN_START"]))*41;
									?>

									<div class="wrap_event" style="top:<?=$hour?>px; height: <?=$height?>px; z-index: <?=$i?>;">
									<div class="detail">
										<b class="name"><?=strtoupper($event["NAME"])?></b>
										<?=$event["DESCRIPTION"]?>
										<table width="100%">
											<tr>
												<td class="title"><b>Время проведения:</b></td><td><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></td>
											</tr>
											<tr>		
												<td class="title"><b>Место проведения:</b></td><td><?=$event["LOCATION"]?></td>
											</tr>
											<tfoot>
												<tr>
													<td colspan="2">
														<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q=<?=$event["LOCATION"]?>">Посмотреть на карте google</a>
													</td>
												</tr>
												<tr>
													<td colspan="2">
													<?$APPLICATION->IncludeComponent(
														"korting:sign_up_for_events",
														"",
														Array("EVENT_NAME" => $event["NAME"], 
															"LOCATION" => $event["LOCATION"],
															"TIME" => $event["HOUR_START"].":".$event["MIN_START"]." - ".$event["HOUR_END"].":".$event["MIN_END"] 
															),
														false
													);?>
													</td>
												</tr>
											</tfoot>
										</table>	
										<a href="#" class="close">X</a>
										<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
									</div> 	
									<a href="#" class="event"><?=$event["NAME"]?></a>
									</div>
									<? $i = $i - 10000;?>
								<?endforeach;?>
						<?endif;?>
						<?foreach($Calendar->ClockArray as $key=>$clock):?>
							<div class="markercell <?if(count($Calendar->ClockArray)-1 == $key):?>last<?endif;?>">
								<?if($key == $CURRENT_HOUR):?>
									<div class="cur_min" style="top:<?=$CURRENT_MIN*40/60?>px;"></div>
								<?endif;?>
								<div class="innermarker"></div>
							</div>
						<?endforeach;?>
					</td>
					<?endfor;?>
				</tr>
			</table>
		</div>
		<!-- Таблица дня -->
		<div class="wrap_table <?if($_GET["calendar"] == "day"):?>active<?endif;?>">
			<div class="scrolling"><a href="<?=$Calendar->getPrev("day", $DAY, $MONTH, $YEAR)?>">&#8592;</a><?=$DAY?>, <?=$MONTH_NAME?>, <?=$YEAR?><a href="<?=$Calendar->getNext("day", $DAY, $MONTH, $YEAR)?>">&#8594;</a></div>
			<table width="100%" class="calendar">
				<tr>
					<td class="clock">
						<?	$HourNum = count($Calendar->ClockArray)-1;
							foreach($Calendar->ClockArray as $key=>$clock):?>
							<div class="<?if($key == $CURRENT_HOUR):?>cur_hour<?endif;?><?if($key == $HourNum):?> last <?endif;?>">
								<?if($key == $CURRENT_HOUR):?>
									<img class="cur_min" src="/media/images/cur_min.png" style="top:<?=$CURRENT_MIN*40/60-4?>px;"/>
								<?endif;?>
								<?=$clock?>
							</div>
						<?endforeach;?>
					</td>
					<td class="day <?if($CURRENT_DAY == $DAY && $CURRENT_MONTH == $MONTH && $CURRENT_YEAR == $YEAR):?>current<?endif;?>">
					<?//=$Calendar->getHtmlEventInDay($DAY, $MONTH, $YEAR, $KortingEvents);?>
					<? $key = $DAY.$MONTH.$YEAR;
					if (array_key_exists($key, $KortingEvents)):?>
						<div class="wrap day">
						<?foreach($KortingEvents[$key] as $event):?>
							<? $hour = $event["HOUR_START"]*41 + $event["MIN_START"]*41/60;
								$height = ((float)($event["HOUR_END"].".".$event["MIN_END"]) - (float)($event["HOUR_START"].".".$event["MIN_START"]))*41;
							?>
								<div class="wrap_event" style="top:<?=$hour?>px; height: <?=$height?>px;">
								<div class="detail">
									<b class="name"><?=strtoupper($event["NAME"])?></b>
									<?=$event["DESCRIPTION"]?>
									<table width="100%">
										<tr>
											<td class="title"><b>Время проведения:</b></td><td><?=$event["HOUR_START"]?>:<?=$event["MIN_START"]?> - <?=$event["HOUR_END"]?>:<?=$event["MIN_END"]?></td>
										</tr>
										<tr>		
											<td class="title"><b>Место проведения:</b></td><td><?=$event["LOCATION"]?></td>
										</tr>
										<tfoot>
											<tr>
												<td colspan="2">
													<a target="_blank" href="http://maps.google.ru/maps?hl=ru&q=<?=$event["LOCATION"]?>">Посмотреть на карте google</a>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<?$APPLICATION->IncludeComponent(
														"korting:sign_up_for_events",
														"",
														Array("EVENT_NAME" => $event["NAME"], 
															"LOCATION" => $event["LOCATION"],
															"TIME" => $event["HOUR_START"].":".$event["MIN_START"]." - ".$event["HOUR_END"].":".$event["MIN_END"] 
															),
														false
													);?>
												</td>
											</tr>
										</tfoot>
									</table>	
									<a href="#" class="close">X</a>
									<i class="tail"><i class="tail_inner"></i></i><i class="shadow"></i><span></span>
								</div> 	
								<a href="#" class="event"><?=$event["NAME"]?></a>
								</div>
							<?endforeach;?>	
						</div>
						<?endif;?>		

						<?foreach($Calendar->ClockArray as $key=>$clock):?>
							<div class="markercell <?if(count($Calendar->ClockArray)-1 == $key):?>last<?endif;?>">
								<?if($key == $CURRENT_HOUR):?>
									<div class="cur_min" style="top:<?=$CURRENT_MIN*40/60?>px;"></div>
								<?endif;?>
								<div class="innermarker"></div>
							</div>
						<?endforeach;?>
					</td>
				</tr>
			</table>
		</div>
	</div>	
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>