<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="webinars_on_detail">
	<h1>Другие вебинары за ближайший месяц</h1>
	<ul class="webinars">
	<? foreach($arResult["ITEMS"] as $arItem)
		{
			$arFile = CFile::GetFileArray($arItem['PROPERTIES']['FILE']['VALUE']);
	?>
		<li>
			<span class="title"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem["NAME"]?></a></span>
			<span class="desc"><?=$arItem["PREVIEW_TEXT"]?></span>
		</li>
	<? } ?>
	<? if(sizeof($arResult["ITEMS"]) < 1) {?>
		<br/><br/>
		В данной категории пока нет вебинаров.
	<? } ?>
	</ul>
</div>