<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>

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
			"г.Москва, Старопетровский проезд, д.7а, стр.25", {
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
				//alert(res.geoObjects.get(0).geometry.getCoordinates());
            	myMap.panTo(res.geoObjects.get(0).geometry.getCoordinates());
            	var coords = res.geoObjects.get(0).geometry.getCoordinates();
			    map.zoomRange.get(coords).then(function (range) {
			        map.setCenter(coords, range[1]);
			    });

			}
		);
		
		
	}
	</script>
<div id="content_full">
	<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
		"START_FROM" => "0", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),false
);?>

<?/*$APPLICATION->IncludeComponent(
	"korting:main.feedback",
	"feedback",
	Array(
		"USE_CAPTCHA" => "Y",
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"EMAIL_TO" => "anton-ht@yandex.ru",
		"REQUIRED_FIELDS" => "",
		"EVENT_MESSAGE_ID" => "прапрпар"
	),
false
);*/?>
	
	<div class="feedback">
		<h1>Обратная связь</h1>
		<div class="desc">
			Мы всегда рады помочь. <br>
Просто оставьте свои контактные данные ниже, а также <br> свой вопрос, и мы вскоре свяжемся с Вами.
		</div>
		<form class="feedback">
			<div class="fieldset">
				<div class="title">Имя</div>
				<div class="field"><input type="text" class="text"></div>
			</div>
			<div class="fieldset">
				<div class="title">Фамилия</div>
				<div class="field"><input type="text" class="text"></div>
			</div>
			<div class="fieldset">
				<div class="title">Email</div>
				<div class="field"><input type="text" class="text"></div>
			</div>
			<div class="fieldset">
				<div class="title">Контактный телефон</div>
				<div class="field"><input type="text" class="text"></div>
			</div>
			<div class="fieldset">
				<div class="title">Сообщение</div>
				<div class="field"><textarea></textarea></div>
			</div>
			<div class="fieldset">
				<div class="title">&nbsp; </div>
				<div class="field"><span class="btn_grey"><input type="submit" value="Отправить сообщение"></span></div>
			</div>
		</form>
	</div>
	
	<div class="map">
		<h1>Контактная информация</h1>
		<div class="desc">

			Единый информационный центр Korting: 8(800)333-0180<br>
			Вопросы и пожелания по работе сайта, а также запрос на проведение обучения: <a href="mailto:academy@korting.ru">academy@korting.ru</a>

			<!-- <div id="yandex_map"></div>-->
			
		</div>
	</div>
	
	<div class="clear"></div>
	
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>