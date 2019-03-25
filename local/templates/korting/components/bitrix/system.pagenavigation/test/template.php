<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

//echo "<pre>"; print_r($arResult);echo "</pre>";

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

?>

<?if ($arResult["NavPageNomer"] > 1):?>
	<?if($arResult["bSavePage"]):?>
		<?/*<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev")?></a>*/?>
		<span class="btn_grey_blue"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Назад</a></span>
	<?else:?>
	
		<?if ($arResult["NavPageNomer"] > 2):?>
			<?/*<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev")?></a>*/?>
			<span class="btn_grey_blue"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Назад</a></span>
		<?else:?>
			<?/*<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>*/?>
			<span class="btn_grey_blue"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">Назад</a></span>
		<?endif?>
		
	<?endif?>

<?else:?>
	
<?endif?>

<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
	<?$arResult["nStartPage"]++?>
<?endwhile?>

<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
	<?/*<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_next")?></a>*/?>
	<span class="btn_grey_blue"><a class="next_question" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>#question">Следующий вопрос</a></span>
<?else:?>
	<span class="btn_grey_blue"><a class="next_question" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>#question">Узнать результат</a></span>	
<?endif?>


