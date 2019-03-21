<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>

<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
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
		        zoom: 13,
		    });
		// Добавление стандартного набора кнопок
		myMap.controls.add("mapTools");
		myMap.controls.add("zoomControl", { top: 45, left: 5 });
		
		var myGeocoder = ymaps.geocode(
			// Строка с адресом, который нужно геокодировать
			 "г.Москва, Выборгская улица, д.22", {
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

	<div class="map">
		<h1>Контактная информация</h1>
		<div class="desc">
			По всем вопросам организации обучения и работы сайта: <br><br>
Единый информационный центр Korting: +7 (495) 662 95 41<br>
			academy@korting.ru <br><br>


			
			<div id="yandex_map"></div>
		</div>
	</div>
	<div class="clear"></div>

</div>

	<script type="text/javascript" 
	src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" 
	data-dojo-config="usePlainJson: true, isDebug: false"></script><script 
	type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { 
	L.start({"baseUrl":"mc.us12.list-manage.com","uuid":"664e18df9d9b04eb664a68dea","lid":"451dc9e961"}) })
	</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>