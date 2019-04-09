<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>
<?
	$month = array("1"=>"Январь", "2"=>"Февраль", "3"=>"Март", "4"=>"Апрель", "5"=>"Май", "6"=>"Июнь", "7"=>"Июль", "8"=>"Август", "9"=>"Сентябрь", "10"=>"Октябрь", "11"=>"Ноябрь", "12"=>"Декабрь");
?>
<h3>Список продаж</h3>
<br/>
<form class="form-inline plan_filter" role="form">
	<div class="form-group">
		<label class="" for="FILTERS[YEAR_MONTH]">Месяц</label>
		<select name="FILTERS[YEAR_MONTH]" class="form-control">
			<option value="0">-</option>
			<?
				$end_timestamp	= strtotime("2014-01-01");
				$prev_timestamp	= strtotime("+2 month");
				while($prev_timestamp >= $end_timestamp)
				{ 
				
				?>
					<option value="<?=date('Y.n', $prev_timestamp);?>" <?=(date('Y-n', $prev_timestamp) == $arResult['FILTERS']['YEAR'].'-'.$arResult['FILTERS']['MONTH'] ? 'selected="selected"' : '')?>><?=date('Y', $prev_timestamp);?> <?=$month[date('n', $prev_timestamp)]?></option>
					
				<? 
					$prev_timestamp = strtotime("-1 month", $prev_timestamp);
				} 
			?>
			
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
		<label class="">&nbsp;</label>
		<button type="submit" class="btn btn-default">Применить</button>
	</div>
</form>
<br/>


<form role="form" method="post" style="width:1140px;">
	<table class="table table-striped plans_table">
	<thead>
	  <tr>
	    <th>Город</th>
	    <th>Магазин</th>
	    <th>Менеджер</th>
	    <th class="text-right">Ожидает</th>
	    <th class="text-right">Подтверждено</th>
	    <th>План</th>
	    <th></th>
	  </tr>
	</thead>
	<tbody>
	<? foreach($arResult['USERS'] as $user) {?>
	  <tr>
	    <td><?=$arResult['CITIES'][$user['CITY_ID']]['NAME']?></td>
	    <td><?=$arResult['SHOPS'][$user['SHOP_ID']]['NAME']?></td>
	    <td><?=$user['FIO']?></td>
	    <td class="text-right"><?=number_format($user['MONTH_SALE']['unadopted'], 0, ',', ' ');?></td>
	    <td class="text-right"><?=number_format($user['MONTH_SALE']['adopted'], 0, ',', ' ');?></td>
	    <td width="120">
			<input type="number" class="form-control text-right" name="PLAN[<?=$user['ID']?>][<?=$arResult['FILTERS']['YEAR']?>.<?=$arResult['FILTERS']['MONTH']?>]" value="<?=$arResult['PLANS'][$user['ID']]['PROPERTY_SALE_PLAN_VALUE']?>" />
	    </td>
	    <td><button type="submit" class="btn btn-default btn-success" name="set_plan" value="1"><span class="glyphicon glyphicon-ok"></span></button></td>
	  </tr>
	<? } ?>
	
	</tbody>
	</table>
	<div class="text-right">
		<button type="submit" class="btn btn-default btn-success" name="set_plan" value="1"><span class="glyphicon glyphicon-ok"></span> Применить</button>
	</div>
</form>

	