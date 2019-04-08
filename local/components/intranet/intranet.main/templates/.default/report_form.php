<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>
<? error_reporting(E_ALL ^ E_NOTICE);

$this->addExternalJS("/local/components/intranet/intranet.main/templates/.default/report_form.js");

function add_offer_show_select($sections, $active_section = '',  $level = '')
{
	foreach($sections as $section)
	{
		if(is_array($section['CHILD']) && sizeof($section['CHILD']) > 0)
		{
			$result .= '<optgroup label="'.$level.$section['NAME'].'">';
			$result .= add_offer_show_select($section['CHILD'], $active_section, ($level.'&nbsp;&nbsp;'));
			$result .= '</optgroup>';
		}
		elseif(is_array($section['products']) && sizeof($section['products']) > 0)
		{	
			$result .= '<optgroup label="'.$level.$section['NAME'].'">';
			$level .= '&nbsp;&nbsp;';
			foreach($section['products'] as $product)
			{
				$result .= '<option value="'.$product['ID'].'" article="'.$product['PROPERTY_ARTICLE_VALUE'].'" '.($product['ID'] == $active_section ? 'selected="selected"' : '').'>'.$level.$product['NAME'].'</option>';
			}
			$result .= '</optgroup>';
		}
	}
	
	return $result;
}
?>
<script>
    jsonProducts = <?=json_encode($arResult["JSON_PRODUCTS"]);?>;
</script>

<? if(sizeof($arResult['ERRORS']) > 0) { ?>
	<div class="errors">
	<? foreach($arResult['ERRORS'] as $error) { ?>
		<span class="error"><?=$error?></span><br/>
	<? } ?>
	</div>
	<br/>
<? } ?>
<?//=strftime('%d.%m.%Y',strtotime("2014W401"));?>
<?//=date('d.m.Y', strtotime('2014-10'))?>
<?//=date('d.m.Y', strtotime('last day of 2014-10'))?>
<form class="form-horizontal report_form" role="form" method="post">
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">ФИО</label>
		<div class="col-sm-5">
			<input type="email" class="form-control" id="inputEmail3" placeholder="ФИО" value="<?=$arResult['FIO']?>" readonly="readonly">
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Город</label>
		<div class="col-sm-5">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Город" value="<?=$arResult['CITY']['NAME']?>" readonly="readonly">
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Магазин</label>
		<div class="col-sm-5">
			<input type="email" class="form-control" id="inputEmail3" placeholder="Магазин" value="<?=$arResult['SHOP']['NAME']?>" readonly="readonly">
		</div>
	</div>
	<? /*
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Отчетный период</label>
		<div class="col-sm-5">
			<select name="__FIELDS[REPORT_DATE]" class="form-control">
				
				<? for($i = date('W'); $i >= 1; $i--) { ?>
					
					<? if(date('n', strtotime(date('Y').'-W'.$i.'-1')) != date('n', strtotime(date('Y').'-W'.$i.'-7'))) { ?>
						<option value="<?=date('Y')?>.<?=date('n', strtotime(date('Y').'-W'.$i.'-7'))?>.<?=$i?>" <?=(date('Y').'.'.date('n', strtotime(date('Y').'-W'.$i.'-7')).'.'.$i == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>><?=Intranet::getInstance()->GetMonthName(date('n', strtotime(date('Y').'-W'.$i.'-7')))?>, <?=$i?> неделя, <?=getDaysFromWeekMonth($i, date('n', strtotime(date('Y').'-W'.$i.'-7')));?></option>
					<? } ?>
					
					<option value="<?=date('Y')?>.<?=date('n', strtotime(date('Y').'-W'.$i.'-1'))?>.<?=$i?>" <?=(date('Y').'.'.date('n', strtotime(date('Y').'-W'.$i.'-1')).'.'.$i == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>><?=Intranet::getInstance()->GetMonthName(date('n', strtotime(date('Y').'-W'.$i.'-1')))?>, <?=$i?> неделя, <?=getDaysFromWeekMonth($i, date('n', strtotime(date('Y').'-W'.$i.'-1')));?></option>
				
				<? } ?>
			</select>
		</div>
	</div>*/?>
	
	
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">
			Отчетный период
		</label>
		<div class="col-sm-5">
			<?
				$day_of_week	= date('w') != 0 ? date('w') : 7;
				$day_of_week--;
//				echo $day_of_week;
			?>
			<select name="FIELDS[REPORT_DATE]" class="form-control">
				<? for($i = $day_of_week; $i <= 105; $i+=7) { ?>
					<?
						$year	= date('Y', strtotime('-'.$i.' day'));
						$month	= date('n', strtotime('-'.$i.' day'));
						$week	= intval(date('W', strtotime('-'.$i.' day')));
						
						if($week == '01' && $month != 1)
						{
							$week	= date('W', strtotime('-'.($i+7).' day')) + 1;
						}
						
					?>
					<? if(date('n', strtotime($year.'-W'.$week.'-1')) != date('n', strtotime($year.'-W'.$week.'-7')) && $month == 12) { 
						
						$new_year	= date('Y', strtotime($year.'-W'.$week.'-7'));
						$new_month	= date('n', strtotime($year.'-W'.$week.'-7'));
						$new_week	= intval(date('W', strtotime($year.'-W'.$week.'-7')));
						$new_week	= intval(date('W', strtotime(date('Y.n.d', strtotime($year.'-W'.$week.'-7')))));
						
					?>
						<option value="<?=$new_year?>.<?=$new_month?>.<?=$new_week?>" <?=($new_year.'.'.($new_month).'.'.$new_week == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>>
							<?=Intranet::getInstance()->GetMonthName($new_month)?>, <?=$new_week?> неделя, <?=getDaysFromWeekMonth($new_week, ($new_month), $new_year);?>
						</option>
					<? } elseif(date('n', strtotime($year.'-W'.$week.'-1')) != date('n', strtotime($year.'-W'.$week.'-7')) && $month != 12) { ?>
						<option value="<?=$year?>.<?=$month+1?>.<?=$week?>" <?=($year.'.'.($month+1).'.'.$week == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>>
							<?=Intranet::getInstance()->GetMonthName($month + 1)?>, <?=$week?> неделя, <?=getDaysFromWeekMonth($week, ($month + 1), $year);?>
						</option>
					<? } ?>
					<option value="<?=$year?>.<?=$month?>.<?=$week?>" <?=($year.'.'.$month.'.'.$week == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>>
						<?=Intranet::getInstance()->GetMonthName($month)?>, <?=$week?> неделя, <?=getDaysFromWeekMonth($week, $month, $year);?>
					</option>
				<? } ?>
				
				
				<? /*
for($i = date('W'); $i >= 1; $i--) { ?>
					
					<? if(date('n', strtotime(date('Y').'-W'.$i.'-1')) != date('n', strtotime(date('Y').'-W'.$i.'-7'))) { ?>
						<option value="<?=date('Y')?>.<?=date('n', strtotime(date('Y').'-W'.$i.'-7'))?>.<?=$i?>" <?=(date('Y').'.'.date('n', strtotime(date('Y').'-W'.$i.'-7')).'.'.$i == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>><?=Intranet::getInstance()->GetMonthName(date('n', strtotime(date('Y').'-W'.$i.'-7')))?>, <?=$i?> неделя, <?=getDaysFromWeekMonth($i, date('n', strtotime(date('Y').'-W'.$i.'-7')));?></option>
					<? } ?>
					
					<option value="<?=date('Y')?>.<?=date('n', strtotime(date('Y').'-W'.$i.'-1'))?>.<?=$i?>" <?=(date('Y').'.'.date('n', strtotime(date('Y').'-W'.$i.'-1')).'.'.$i == $arResult['FIELDS']['REPORT_DATE'] ? 'selected="selected"' : '')?>><?=Intranet::getInstance()->GetMonthName(date('n', strtotime(date('Y').'-W'.$i.'-1')))?>, <?=$i?> неделя, <?=getDaysFromWeekMonth($i, date('n', strtotime(date('Y').'-W'.$i.'-1')));?></option>
				
				<? }
*/ ?>
			</select>
		</div>
	</div>
	
	<?/*
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Продукт</label>
		<div class="col-sm-5">
			<select class="form-control product_section">
				<? foreach($arResult['SECTIONS'] as $section) { ?>
					<option class="opt_<?=$section['ID']?>" value="<?=$section['ID']?>"><?=$section['NAME']?></option>
				<? } ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Категория</label>
		<div class="col-sm-5">
			<select class="form-control  category_section">
				
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Модель</label>
		<div class="col-sm-5">
			<select class="form-control  model_section">
				
			</select>
		</div>
		<div class="col-sm-1">
			<button type="button" class="btn btn-success add_product">Добавить</button>
		</div>
	</div>
*/?>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Модель</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="modelAutocomplite" placeholder="Модель">
        </div>
    </div>

	
	<? /*
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">Модель</label>
		<div class="col-sm-5">
			<select class="form-control product_list">
				<?=add_offer_show_select($arResult['SECTIONS'])?>
			</select>
		</div>
		<div class="col-sm-1">
			<button type="button" class="btn btn-success add_product">Добавить</button>
		</div>
	</div>
	*/ ?>
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label"> </label>
		<div class="col-sm-9">
		<table class="table table-hover product_list">
			<thead>
				<tr>
					<th>Название</th>
					<th>Код</th>
					<th>Цена, руб</th>
					<th>Кол-во</th>
					<th class="text-right">Стоимость</th>
					<th class="col-xs-1">Удалить</th>
				</tr>
			</thead>
			<tbody>
				<tr class="p_template" style="display:none;">
					<input type="hidden" name="FIELDS[PRODUCT_ID][]" value="{ID}" />
					<td class="p_name">{NAME}</td>
					<td class="p_code">{ARTICLE}</td>
					<td class="col-xs-2">
						<input type="text" class="form-control input-sm" name="FIELDS[PRODUCT_PRICE][]" placeholder="Цена" value="" />
					</td>
					<td class="col-xs-2">
						<input type="number" class="form-control input-sm" name="FIELDS[PRODUCT_COUNT][]" value="1" />
					</td>
					<td class="p_price text-right"></td>
					<td><button type="button" class="close" aria-hidden="true">&times;</button></td>
				</tr>
				<?
					$result_price	= 0;
					$result_count	= 0;
					if(is_array($arResult['FIELDS']['PRODUCTS']) && sizeof($arResult['FIELDS']['PRODUCTS']) > 0)
					{	
						foreach($arResult['FIELDS']['PRODUCTS'] as $product)
						{ 
							$result_price 	+= $product['PRICE']*$product['COUNT'];
							$result_count	+= $product['COUNT'];
						?>
							<tr>
								<input type="hidden" name="FIELDS[PRODUCT_ID][]" value="<?=$product['ID']?>" />
								<td class="p_name"><?=$product['NAME']?></td>
								<td class="p_code"><?=$product['ARTICLE']?></td>
								<td class="col-xs-2">
									<input type="text" class="form-control input-sm" name="FIELDS[PRODUCT_PRICE][]" placeholder="Цена" value="<?=$product['PRICE']?>" />
								</td>
								<td class="col-xs-2">
									<input type="number" class="form-control input-sm" name="FIELDS[PRODUCT_COUNT][]" value="<?=$product['COUNT']?>" />
								</td>
								<td class="p_price text-right"><?=($product['PRICE']*$product['COUNT'])?></td>
								<td><button type="button" class="close" aria-hidden="true">&times;</button></td>
							</tr>
						<? } 
					}
				?>
				<tfoot>
				<tr class="result_tr">
					
					<td colspan="3">Итого:</td>
					<td class="result_count"><?=$result_count?></td>
					<td class="p_price text-right result_price"><?=$result_price?></td>
					<td></td>
				</tr>
				</tfoot>
			</tbody>
		</table>
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">
			Комментарии к работе
			<span class="help-block h6"> (все устраивает, хорошо работает менеджер, слишком много товара, высокая проходимость и тп.)</span>
		</label>
		<div class="col-sm-5">
			<textarea class="form-control" name="FIELDS[COMMENT]" rows="3"><?=$arResult['FIELDS']['COMMENT']?></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-3 control-label">
			Маркетинговые активности
			<span class="help-block h6"> (акции, ценовые предложения конкурентов)</span>
		</label>
		<div class="col-sm-5">
			<textarea class="form-control" name="FIELDS[MARKETING]" rows="3"><?=$arResult['FIELDS']['MARKETING']?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-10">
		  <button type="submit" class="btn btn-default btn-primary">Отправить</button>
		</div>
	</div>
</form>


