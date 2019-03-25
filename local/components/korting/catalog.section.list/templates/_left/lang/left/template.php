<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="left_menu">
<?
$CURRENT_DEPTH=$arResult["SECTION"]["DEPTH_LEVEL"]+1;
foreach($arResult["SECTIONS"] as $arSection):
	//echo "<pre>".print_r($arSection, 1)."</pre>";
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
?>
	<?if($arSection["DEPTH_LEVEL"] == 1):?>
		<?if($_REQUEST["SECTION_ID"] == $arSection["ID"])
			$class = "active";
		else
			$class = "";
		?>
		<li class="top">
			<a class="<?=$class?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
		<?if(count($arSection["ITEMS"]) == 0):?>
			</li>	
		<?endif;?>
		<?if(count($arSection["ITEMS"]) > 0):?>
			<ul class="cat_menu <?=$class?>">
				<?foreach($arSection["ITEMS"] as $Item):?>
					<?//print_r($arSection["ID"]);?>
					<li>
						<table>
							<tr>
								<td>
									<?if($Item["PREVIEW_PICTURE"]!=""):?>
										<img src="<?=CFile::GetPath($Item["PREVIEW_PICTURE"])?>"/>
									<?endif;?>
								</td>
								<td class="right"><a class="cat" href="<?=$Item["DETAIL_PAGE_URL"]?>&SECTION_ID=<?=$arSection["ID"]?>"><?=$Item["NAME"]?></a></td>
							</tr>
						</table>
					</li>
				<?endforeach;?>
			</ul>	
			</li>
		<?endif;?>
	<?endif;?>								
<?endforeach?>
</ul>