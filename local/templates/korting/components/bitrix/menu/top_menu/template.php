<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo "<pre>".print_r($arResult, 1)."</pre>";?>
<? if (!empty($arResult)):?>
<ul class="top_menu">
	<?
	foreach($arResult as $k=>$arItem):
		if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
			continue;
		$class = '';
		if($arItem["SELECTED"])
			$class .= 'active';
		if($k == (sizeof($arResult)-1))
			$class .= ' last';
	?>
		<li class="<?=$class?>"><span class="all"><span class="wrap"><span class="left"></span><a class="<?=$arItem["PARAMS"]["class"]?>" href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a><span class="right"></span></span></span></li>
		
	<?endforeach?>
</ul>
<div class="clear"></div>
<?endif?>