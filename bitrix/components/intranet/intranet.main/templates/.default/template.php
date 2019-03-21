<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>
<?

?>
<h3>Список отчетов</h3>

<table class="table table-striped reports_table">
<thead>
  <tr>
    <th>Месяц</th>
    <th>Неделя</th>
    <th>Дата</th>
    <th>Товары</th>
    <th>Кол-во</th>
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
	
	$product_count	= 0;
	if(is_array($products))
	{
		foreach($products as $product)
		{
			$product_count += $product['COUNT'];
		}
	}
?>
  <tr>
    <td><?=Intranet::getInstance()->GetMonthName($report['PROPERTIES']['MONTH']['VALUE'])?></td>
    <td><?=$report['PROPERTIES']['WEEK']['VALUE']?></td>
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
    <td><?=$product_count?> шт.</td>
	<td class="price"><?=number_format($report['PROPERTIES']['PRICE']['VALUE'], 0, ',', ' ');?> руб</td>
    <td><?=$report['PROPERTIES']['ADOPTED']['VALUE']?></td>
    <td><a href="/intranet/?action=report_detail_user&report_id=<?=$report['ID']?>" class="btn btn-default btn-success"><span class="glyphicon glyphicon-search"></span></a></td>
  </tr>
<? } ?>

</tbody>
</table>
	