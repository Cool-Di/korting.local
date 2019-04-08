<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>
<?//=date('n', strtotime(date('Y').'W'.'40'));?>
<h3>Список отчетов</h3>
<br/>
<form class="form-inline reports_form" role="form">
	<div class="form-group">
		<label class="" for="FILTERS[PROPERTY_CITY_ID]">Месяц</label>
		<select name="FILTERS[PROPERTY_MONTH]" class="form-control">
			<option value="">-</option>
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
	<div class="form-group pull-right">
		<label>&nbsp;</label>
		<button type="submit" class="btn btn-default">Применить</button>
	</div>
</form>
<br/>
<?
	$filter_param	= '';
	
	if(isset($_REQUEST['FILTERS']['PROPERTY_MONTH']) && !empty($_REQUEST['FILTERS']['PROPERTY_MONTH']))
		$filter_param .= '&PROPERTY_MONTH='.$_REQUEST['FILTERS']['PROPERTY_MONTH'];
		
	if(isset($_REQUEST['FILTERS']['PROPERTY_CITY_ID']) && !empty($_REQUEST['FILTERS']['PROPERTY_CITY_ID']))
		$filter_param .= '&PROPERTY_CITY_ID='.$_REQUEST['FILTERS']['PROPERTY_CITY_ID'];
		
	if(isset($_REQUEST['FILTERS']['PROPERTY_SHOP_ID']) && !empty($_REQUEST['FILTERS']['PROPERTY_SHOP_ID']))
		$filter_param .= '&PROPERTY_SHOP_ID='.$_REQUEST['FILTERS']['PROPERTY_SHOP_ID'];
		
	if(isset($_REQUEST['FILTERS']['PROPERTY_USER_ID']) && !empty($_REQUEST['FILTERS']['PROPERTY_USER_ID']))
		$filter_param .= '&PROPERTY_USER_ID='.$_REQUEST['FILTERS']['PROPERTY_USER_ID'];
?>
<div class="text-right">
<a href="/intranet/excel.php?<?=$filter_param?>" class="">Скачать excel</a> | 
<a href="/intranet/excel_product.php?<?=$filter_param?>" class="">Скачать excel c разбивкой по товарам</a>
</div>
<br/>

<form method="post">
	<table class="table table-striped reports_table">
	<thead>
	  <tr>
	    <th>Город / Магазин</th>
	    <th>Менеджер</th>
	    <th>Месяц</th>
	    <th width="100">Дата</th>
	    <th>Товары</th>
	    <th>Сумма</th>
	    <th>Принят</th>
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
	    <td><?=$report['PROPERTIES']['CITY']['VALUE']?> /<br/><?=$report['PROPERTIES']['SHOP']['VALUE']?></td>
	    <td><?=$report['PROPERTIES']['FIO']['VALUE']?></td>
	    <? /*<td><?=$report['PROPERTIES']['WEEK']['VALUE']?></td>*/?>
	    <td><?=Intranet::getInstance()->GetMonthName($report['PROPERTIES']['MONTH']['VALUE'])?></td>
	    <td><?=getDaysFromWeekMonth($report['PROPERTIES']['WEEK']['VALUE'], $report['PROPERTIES']['MONTH']['VALUE'], $report['PROPERTIES']['YEAR']['VALUE']);?></td>
	    <td class="products">
	    	<? 
	    	if(is_array($products))
			{
				foreach($products as $product)
				{
				?>
				<?=$product['CATEGORY_NAME']?> <?=$product['NAME']?> - <?=$product['COUNT']?>шт.<br/>
				<?
				}
			}
			?>
	    </td>
	    <td class="price"><?=number_format($report['PROPERTIES']['PRICE']['VALUE'], 0, ',', ' ');?> руб</td>
	    <td>
	    	<? if(empty($report['PROPERTIES']['ADOPTED']['VALUE'])) { ?> 
		    	<input type="checkbox" name="FIELDS[REPORT_ID][]" value="<?=$report['ID']?>">
	    	<? } else { ?>
		    	<?=$report['PROPERTIES']['ADOPTED']['VALUE']?>
	    	<? } ?>
	    	
	    </td>
	    <td><a href="/intranet/reports?action=report_detail&report_id=<?=$report['ID']?>" class="btn btn-default btn-success"><span class="glyphicon glyphicon-search"></span></a></td>
	  </tr>
	<? } ?>
	<tr>
		<td colspan="6">
			<input type="hidden" name="action" value="adopted_all_report" />
			<button  class="btn btn-default btn-success "><span class="glyphicon glyphicon-ok"></span> Принять отмеченные отчеты</button>
		</td>
		<td colspan="2">
			<a href="#" class="btn btn-default btn-warning checked_all_report btn-sm"><span class="glyphicon glyphicon-ok"></span> Отметить все</a>
		</td>
	</tr>
	
	</tbody>
	</table>

	<div class="pull-left">
		
		<? /* <a href="/intranet/reports/?action=adopted_all_report" class="btn btn-default btn-warning" onclick="return confirm('Вы подтверждаете принятие всех отчетов?')"><span class="glyphicon glyphicon-ok"></span> Принять все отчеты</a> */?>
	</div>
	<div class="pull-right">		
		
		
	</div>

</form>
	