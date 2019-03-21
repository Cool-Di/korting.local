<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	if(isset($arResult['PROPERTIES']['COMPANY']['VALUE']) && intval($arResult['PROPERTIES']['COMPANY']['VALUE']) 
		&& $arResult['PROPERTIES']['COMPANY']['VALUE'] != $arParams['USER_COMPANY_ID'])
	{
		
	}
	else
	{
?>
<div class="news_detail">
	<h1><?=$arResult['NAME']?></h1>
	
	<?=($arResult['DETAIL_TEXT'] ? $arResult['DETAIL_TEXT'] : $arResult['PREVIEW_TEXT'])?>
</div>
<? } ?>