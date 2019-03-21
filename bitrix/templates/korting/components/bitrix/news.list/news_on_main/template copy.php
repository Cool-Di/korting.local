<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="news_on_main">
	<span class="title">Новости</span>
	<ul class="new">
		<?foreach($arResult["NEW"] as $arItem):?>
			<li>
				<div class="photo" style="background: url(/timthumb.php?src=<?=CFile::GetPath($arItem["PREVIEW_PICTURE"])?>&h=118&w=126&zc=1) no-repeat scroll 50% 50% transparent;"></div>
				<div class="txt">
					<div class="date"><?=$arItem['ACTIVE_FROM']?></div>
					<div class="link"><a class="act" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
					<div class="descr">
						<?=$arItem["PREVIEW_TEXT"]?>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		<?endforeach;?>	
	</ul>
	<ul class="old">
		<?foreach($arResult["OLD"] as $arItem):?>
			<li><span class="date"><?=$arItem['ACTIVE_FROM']?></span><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></li>
		<?endforeach;?>
	</ul>
	<a href="/news/" class="all_news">Показать все новости</a>
</div>	

<?/*if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
*/?>
