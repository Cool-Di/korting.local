<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h1><?=$arResult['NAME']?></h1>
<ul class="materials">
<? foreach($arResult["ITEMS"] as $arItem)
	{
		$arFile = CFile::GetFileArray($arItem['PROPERTIES']['FILE']['VALUE']);
?>
	<li>
		<table class="materials">
		<tr>
			<?
					$class = '';
					if($arItem["PROPERTIES"]["TYPE"]["VALUE"] == "Аудио"){
						$class = 'audio';
					}elseif($arItem["PROPERTIES"]["TYPE"]["VALUE"] == "Видео"){
						$class = 'video';
					}elseif($arItem["PROPERTIES"]["TYPE"]["VALUE"] == "Pdf"){
						$class = 'pdf';
					}
				?>
			<td class="title <?=$class?>">
				<a href="<?=$arFile['SRC']?>"><?=$arItem["NAME"]?></a>
			</td>
			<? if($arFile) { ?>
				<td class="download"><span class="btn_grey_blue"><a href="<?=$arFile['SRC']?>">Скачать</a></span></td>
			<? } ?>
		</tr>
		</table>
	</li>
<? } ?>
</ul>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
