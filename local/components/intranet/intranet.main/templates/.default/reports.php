<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>

<h3>Список продаж</h3>
<br/>
<form class="form-inline reports_form" role="form">
    <div class="form-group">
        <label class="" for="FILTERS[PROPERTY_PERIOD_ID]">Период</label>
        <select name="FILTERS[PROPERTY_PERIOD_ID]" class="form-control">
            <option value="">-</option>
            <? foreach($arResult['PERIODS'] as $period) { ?>
                <option value="<?=$period['ID']?>" <?=($period['ID'] == $arResult['FILTERS']['PROPERTY_PERIOD_ID'] ? 'selected="selected"' : '')?>><?=$period['NAME']?></option>
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
	    <th>Период</th>
	    <th width="100">Дата</th>
	    <th>Товары</th>
	    <th>Баллы</th>
	    <th>Подтвердить</th>
	    <th></th>
	  </tr>
	</thead>
	<tbody>
	<? foreach($arResult['REPORTS'] as $report) {
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
        <? if($report['PROPERTIES']['IS_SYSTEM']['VALUE']) {?>
            <tr>
                <td></td>
                <td></td>
                <td><?=$report['PROPERTY_PERIOD_ID_NAME']?></td>
                <td></td>
                <td class="products"><?=$report['NAME']?> (ID <?=$report['ID']?>)</td>
                <td class="price"><?=$report['PROPERTIES']['PRICE']['VALUE'];?></td>
                <td><?=$report['PROPERTIES']['STATUS']['VALUE']?></td>
                <td></td>
            </tr>
        <?} else {?>
          <tr>
            <td><?=$report['PROPERTIES']['CITY']['VALUE']?> /<br/><?=$report['PROPERTIES']['SHOP']['VALUE']?> </td>
            <td><?=$report['PROPERTIES']['FIO']['VALUE']?></td>
            <td><?=$report['PROPERTY_PERIOD_ID_NAME']?></td>
            <td><?=$report['PROPERTIES']['SALE_DATE']['VALUE']?></td>
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
            <td class="price"><?=$report['PROPERTIES']['PRICE']['VALUE'];?></td>
            <td>
                <? if($report['PROPERTIES']['STATUS']['VALUE_XML_ID'] == 'AWAITING') { ?>
                    <input type="checkbox" name="FIELDS[REPORT_ID][]" value="<?=$report['ID']?>">
                <? } else { ?>
                    <?=$report['PROPERTIES']['STATUS']['VALUE']?>
                <? } ?>

            </td>
            <td><a href="/intranet/reports?action=report_detail&report_id=<?=$report['ID']?>" class="btn btn-default btn-success"><span class="glyphicon glyphicon-search"></span></a></td>
          </tr>
        <?}?>
	<? } ?>
	<tr>
		<td colspan="6">
			<input type="hidden" name="action" value="adopted_all_report" />
			<button  class="btn btn-default btn-success "><span class="glyphicon glyphicon-ok"></span> Принять отмеченные продажи</button>
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
<? if($arResult['IS_USER_PERIOD_PAGE']) {?>
    <form method="post">
        <table class="table table_bonus" style="width: 400px;">
            <tbody>
            <tr>
                <td class="title_bonus">Баллов подтвреждено:</td>
                <td class="price_bonus"><?=$arResult['CURRENT_BONUS']['accepted']?></td>
            </tr>
            <tr>
                <td class="title_bonus">Будет списано:</td>
                <td class="price_bonus"><?=(int)$arResult['CURRENT_BONUS']['usedPoints']?> (<?=$arResult['CURRENT_BONUS']['reward']?> руб.)</td>
            </tr>
            <tr>
                <td class="title_bonus">Баллов осталось:</td>
                <td class="price_bonus"><?=(int)$arResult['CURRENT_BONUS']['balance']?></td>
            </tr>
            </tbody>
        </table>
        <? if($arResult["EXIST_TRANSFER"]) {?>
            Период уже закрыт
        <?} elseif($arResult["HAVE_AWAITING"]){?>
            Нельзя списать баллы, пока есть необработанные отчёты
        <?} else {?>
            <input type="hidden" name="transferBonus" value="1">
            <button class="btn btn-default btn-success">Записать баллы на счёт</button>
        <?}?>
    </form>
<?}?>