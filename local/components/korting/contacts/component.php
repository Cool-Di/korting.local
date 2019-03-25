<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

//переменная для хранения имени шаблона, на разных этапах используются разные шаблоны
$templatePage = '';

$arResult 			= array();
$arResult['ERRORS']	= array();

$script = '
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
	<script type="text/javascript">
	// Как только будет загружен API и готов DOM, выполняем инициализацию
	ymaps.ready(init);
	
	function init () {
		// Создание экземпляра карты и его привязка к контейнеру с
		// заданным id ("map")
		var myMap = new ymaps.Map("yandex_map", {
		        // При инициализации карты, обязательно нужно указать
		        // ее центр и коэффициент масштабирования
		        center: [55.825558,37.500881], // Москва
		        zoom: 15,
		    });
		// Добавление стандартного набора кнопок
		myMap.controls.add("mapTools");
		myMap.controls.add("zoomControl", { top: 45, left: 5 });
		
		var myGeocoder = ymaps.geocode(
			// Строка с адресом, который нужно геокодировать
			"Старопетровский проезд, д.7а, стр.25 ", {
				// - требуемое количество результатов
				results: 1
			}
		);
		
		/* После того, как поиск вернул результат, вызывается
		callback-функция */
		myGeocoder.then(
			function (res) {
			
				/* Размещение полученной коллекции 
				геообъектов на карте */
				myMap.geoObjects.add(res.geoObjects);
				// Центрирование карты на добавленном объекте
				//var point = res.geoObjects.get(0);
            	myMap.panTo(res.geoObjects.get(0).geometry.getCoordinates());
            	var coords = res.geoObjects.get(0).geometry.getCoordinates();
			    map.zoomRange.get(coords).then(function (range) {
			        map.setCenter(coords, range[1]);
			    });

			}
		);
		
		
	}
	</script>

';

//CUtil::InitJSCore(array("ajax"));
$GLOBALS['APPLICATION']->AddHeadString($script, true);

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
					"LAST_NAME" => $_POST["last_name"],
					"EMAIL" => $_POST["email"],
					"PHONE" => $_POST["phone"],
					"EMAIL_TO" => $email_to,
					"MESSAGE" => $_POST["message"],
				);
			CEvent::Send("FEEDBACK", 's1', $arFields);
			
			LocalRedirect("/contacts/?success=1#success");
			$arResult['SUCCESS'] = 1;
		}
	}
	else
	  	$arResult['ERRORS'][] = 'Проверочный код введен не правильно!';

}

$arResult['captcha_code']	= $APPLICATION->CaptchaGetCode();

$this->IncludeComponentTemplate($templatePage);