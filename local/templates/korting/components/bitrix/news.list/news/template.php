<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //print_r($arResult["ITEMS"][0]) ?>
<div class="news_on_main">
	<span class="title">Новости</span>
	<ul class="new">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li>
				<div class="photo" style="background: url(/timthumb.php?src=<?=CFile::GetPath($arItem["PREVIEW_PICTURE"]['SRC'])?>&h=118&w=126&zc=1) no-repeat scroll 50% 50% transparent;"></div>
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
			<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li><span class="date">19/05/12</span><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></li>
		<?endforeach;?>
	</ul>
</div>	

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>


