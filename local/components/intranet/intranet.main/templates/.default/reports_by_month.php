<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>

<h3>Статистика</h3>
<br/>
<form class="form-inline reports_form" role="form">
	<div class="form-group">
		<label class="" for="FILTERS[PROPERTY_CITY_ID]">Месяц</label>
		<select name="FILTERS[PROPERTY_MONTH]" class="form-control">
			<option value="0">-</option>
			<? foreach($arResult['YEAR_MONTH'] as $year_month) { ?>
				<option value="<?=$year_month['YEAR']?>.<?=$year_month['MONTH']?>" <?=($year_month['MONTH'] == $arResult['FILTERS']['PROPERTY_MONTH'] && $year_month['YEAR'] == $arResult['FILTERS']['PROPERTY_YEAR'] ? 'selected="selected"' : '')?>><?=$year_month['YEAR']?> <?=Intranet::getInstance()->GetMonthName($year_month['MONTH'])?></option>
			<? } ?>
		</select>
	</div>
	<div class="form-group">
		<label class="" for="FILTERS[PROPERTY_CITY_ID]">Город</label>
		<select name="FILTERS[PROPERTY_CITY_ID]" class="form-control">
			<option value="0">-</option>
			<? foreach($arResult['CITIES'] as $city) { ?>
				<option value="<?=$city['ID']?>" <?=($city['ID'] == $arResult['FILTERS']['PROPERTY_CITY_ID'] ? 'selected="selected"' : '')?>><?=$city['NAME']?></option>
			<? } ?>
		</select>
	</div>
	<div class="form-group">
		<label class="" for="FILTERS[PROPERTY_SHOP_ID]">Магазин</label>
		<select name="FILTERS[PROPERTY_SHOP_ID]" class="form-control">
			<option value="0">-</option>
			<? foreach($arResult['CITIES'] as $city) { ?>
				<? foreach($city['SHOPS'] as $shop) { ?>
					<option value="<?=$shop['ID']?>" <?=($shop['ID'] == $arResult['FILTERS']['PROPERTY_SHOP_ID'] ? 'selected="selected"' : '')?>><?=$shop['NAME']?></option>
				<? } ?>
			<? } ?>
		</select>
	</div>
	<div class="form-group">
		<label class="" for="FILTERS[PROPERTY_USER_ID]">Пользователь</label>
		<select name="FILTERS[PROPERTY_USER_ID]" class="form-control">
			<option value="0">-</option>
			<? foreach($arResult['USERS'] as $user) { ?>
				<option value="<?=$user['ID']?>" <?=($user['ID'] == $arResult['FILTERS']['PROPERTY_USER_ID'] ? 'selected="selected"' : '')?>><?=$user['FIO']?></option>
			<? } ?>
			
		</select>
	</div>
	<div class="form-group">
		<label class="">&nbsp;</label>
		<button type="submit" class="btn btn-default">Применить</button>
	</div>
</form>
<br/>
<div class="text-right">
<a href="/intranet/excel.php" class="">Скачать excel</a> | 
<a href="/intranet/excel_product.php" class="">Скачать excel c разбивкой по товарам</a>
</div>
<br/>

<table class="table table-striped reports_table">
<thead>
  <tr>
    <th>Город / Магазин</th>
    <th>Менеджер</th>
    <th>Месяц</th>
    <th>Товары</th>
    <th>Сумма</th>
    <th>Принятая сумма</th>
    <th>План</th>
    <th>Кол-во продаж</th>
    <th>Принято</th>
    <th></th>
  </tr>
</thead>
<tbody>
<? foreach($arResult['REPORTS'] as $report) {// dump($report['PROPERTIES']);
	$week_number	= explode('.', $report['PROPERTIES']['WEEK']['VALUE']);
	$week_number	= $week_number[1];
	
	$products		= unserialize($report['PROPERTIES']['PRODUCTS']['~VALUE']);
//	dump($products);
	$product_count	= 0;
//	dump($products);
	if(is_array($products))
	{
		foreach($products as $product)
		{
			$product_count += $product['COUNT'];
		}
	}
?>
  <tr>
    <td><?=$report['CITY']?> /<br/><?=$report['SHOP']?></td>
    <td><?=$report['USER']['FIO']?></td>
    <td><?=$report['YEAR']?> <?=Intranet::getInstance()->GetMonthName($report['MONTH'])?></td>
    <td class="products">
    	<? 
    	if(is_array($report['PRODUCTS']))
		{
			foreach($report['PRODUCTS'] as $product)
			{
			?>
			<?=$product['CATEGORY_NAME']?> <?=$product['NAME']?> - <?=$product['COUNT']?>шт.<br/>
			<?
			}
		}
		?>
    </td>
    <td class="price"><?=number_format($report['PRICE_UNADOPTED'] + $report['PRICE_ADOPTED'], 0, ',', ' ');?> руб</td>
    <td class="price"><?=number_format($report['PRICE_ADOPTED'], 0, ',', ' ');?> руб</td>
    <td class="price"><?=number_format($report['MONTH_PLAN'], 0, ',', ' ');?> руб</td>
    <td><?=$report['REPORT_COUNT']?></td>
    <td><?=$report['ADOPTED_COUNT']?></td>
    <td><a target="_blank" href="/intranet/reports?FILTERS[PROPERTY_MONTH]=<?=$report['YEAR']?>.<?=$report['MONTH']?>&FILTERS[PROPERTY_USER_ID]=<?=$report['USER']['ID']?>" class="btn btn-default btn-success"><span class="glyphicon glyphicon-search"></span></a></td>
  </tr>
<? } ?>

</tbody>
</table>
	