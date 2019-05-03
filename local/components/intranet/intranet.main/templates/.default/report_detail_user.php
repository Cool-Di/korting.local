<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();  ?>
<?

?>
<h3>Просмотр продажи</h3>
<br/>
<? if(sizeof($arResult['ERRORS']) > 0) { ?>
	<div class="errors">
	<? foreach($arResult['ERRORS'] as $error) { ?>
		<span class="error"><?=$error?></span><br/>
	<? } ?>
	</div>
	<br/>
<? } ?>
<div class="report_detail">
	<table class="table table-bordered table-striped">
		<tr>
			<td class="col-md-3"><strong>ФИО</strong></td>
			<td><?=$arResult['REPORT']['PROPERTIES']['FIO']['VALUE']?></td>
		</tr>
		<tr>
			<td><strong>Город</strong></td>
			<td><?=$arResult['REPORT']['PROPERTIES']['CITY']['VALUE']?></td>
		</tr>
		<tr>
			<td><strong>Магазин</strong></td>
			<td><?=$arResult['REPORT']['PROPERTIES']['SHOP']['VALUE']?></td>
		</tr>
		<tr>
			<td><strong>Отчетный период</strong></td>
			<td>
				<?/*=$arResult['REPORT']['PROPERTIES']['WEEK']['VALUE']?> неделя,
				<?=getDaysFromWeekMonth($arResult['REPORT']['PROPERTIES']['WEEK']['VALUE'], $arResult['REPORT']['PROPERTIES']['MONTH']['VALUE'], $arResult['REPORT']['PROPERTIES']['YEAR']['VALUE'])*/?>
			    <?=$arResult['REPORT']["PROPERTY_PERIOD_ID_NAME"]?>
            </td>
		</tr>
		<tr>
			<td><strong>Продукты</strong></td>
			<td>
				<?
					$products	= unserialize($arResult['REPORT']['PROPERTIES']['PRODUCTS']['~VALUE']);
					//dump($products);
				?>
				<table class="table  table-bordered">
				<thead>
				  <tr>
				    <th>Продукт</th>
				    <th>Название</th>
				    <th>Артикул</th>
				    <th>Баллы</th>
				    <th>Количество</th>
				  </tr>
				</thead>
				<? 
		    	if(is_array($products))
				{
					$product_count 	= 0;
					$product_price	= 0;
					foreach($products as $product)
					{
					$product_count += $product['COUNT'];
					$product_price += $product['PRICE'] * $product['COUNT'];
					?>
					<tr>
						<td><?=$product['CATEGORY_NAME']?></td>
						<td><?=$product['NAME']?></td>
						<td><?=$product['ARTICLE']?></td>
						<td><?=number_format($product['PRICE'], 0, ',', ' ');?> </td>
						<td><?=$product['COUNT']?>шт.</td>
					</tr>
					<?
					}
				}
				?>
				</table>
				
			</td>
		</tr>
		<tr>
			<td><strong>Количество товаров</strong></td>
			<td><?=$product_count?></td>
		</tr>
		<tr>
			<td><strong>Сумма баллов</strong></td>
			<td><?=number_format($product_price, 0, ',', ' ');?> </td>
		</tr>
		<tr>
			<td><strong>Комментарий для менеджера</strong></td>
			<td><?=$arResult['REPORT']['PROPERTIES']['COMMENT']['VALUE']?></td>
		</tr>
		<tr>
			<td><strong>Статус</strong></td>
			<td>
                <strong><?=$arResult['REPORT']['PROPERTIES']['STATUS']['VALUE']?> </strong>
                <?
                if($arResult['REPORT']['PROPERTIES']['STATUS']['VALUE_XML_ID'] != 'AWAITING')
                {
                    $adopted_user = Intranet::getInstance()->GetUserArr($arResult['REPORT']['PROPERTIES']['ADOPTED_USER']['VALUE']);
                    ?>
                    (дата - <strong><?=$arResult['REPORT']['PROPERTIES']['ADOPTED_DATE']['VALUE']?></strong>,
                                                                                                            пользователем - <strong><?=$adopted_user['FIO']?></strong> (<?=$arResult['REPORT']['PROPERTIES']['ADOPTED_USER']['VALUE']?>))

                    <? if(strlen($arResult['REPORT']['PROPERTIES']['ADOPTED_REASON']['VALUE']) > 0) {?>
                    <div>
                        <strong>Причина отказа: </strong> <?=$arResult['REPORT']['PROPERTIES']['ADOPTED_REASON']['VALUE']?>
                    </div>
                <?}?>
                <?}?>
			</td>
		</tr>
        <? if(!empty($arResult["FILES"])){?>
        <tr>
            <td><strong>Прикреплённые файлы</strong></td>
            <td>
                <?foreach($arResult["FILES"] as $file) {?>
                    <div>
                        <a href="<?=$file["SRC"]?>"><?=$file["ORIGINAL_NAME"]?></a>
                    </div>
                <?}?>
            </td>
        </tr>
        <?}?>
	</table>
</div>
<div class="pull-left">
	<a href="/intranet" class="btn btn-default btn-success"><span class="glyphicon glyphicon-arrow-left"></span> назад</a>
	<? if($arResult['REPORT']['PROPERTIES']['ADOPTED']['VALUE'] != 'Да') { ?>
		<a href="/intranet?action=add_report&report_id=<?=$arResult['REPORT']['ID']?>" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-edit"></span> редактировать</a>
	<? } ?>
</div>
<div class="clear"></div>
<h3>Комментарии</h3>
<div class="row">
	<div class="col-md-7">
		<? if(isset($_REQUEST['comment_errors'])) { ?>
			<div class="errors">
				<span class="error"><?=$_REQUEST['comment_errors']?></span><br/>
			</div>
			<br/>
		<? } ?>
		<form role="form" method="post">
			<input type="hidden" name="back_url" value="<?=$_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="action" value="add_comment" />
			<input type="hidden" name="report_id" value="<?=$arResult['REPORT']['ID']?>" />
			<div class="form-group">
				<label for="cooment_text">Сообщение</label>
				<textarea class="form-control" name="cooment_text" id="cooment_message" rows="3"></textarea>
			</div>
		
			<button type="submit" class="btn btn-default">Отправить</button>
		</form>
	</div>
</div>
<br/>
<div class="row">
	<div class="col-md-7">
		<ul class="media-list">
			<? foreach($arResult['COMMENTS'] as $comment) { ?> 
			<li class="media">
				<div class="col-md-4">
					<div class="pull-left" href="#">
						<span class="glyphicon glyphicon-user"></span>
						<?=$comment['USER']['FIO']?>
						<p class="text-muted"><?=date('d.m.Y', $comment['DATE_CREATE_UNIX'])?></p>
					</div>
				</div>
				<div class="media-body">
					<h4 class="media-heading"></h4>
					<?=$comment['DETAIL_TEXT']?>
				</div>
			</li>
			<? } ?>
		</ul>
	</div>
</div>


