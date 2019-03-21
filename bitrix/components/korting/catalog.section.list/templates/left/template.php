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
		<? if($_REQUEST["SECTION_ID"] == $arSection["ID"] || $arSection['SELECTED'] == 'Y')
			$class = "active";
		else
			$class = "";
		?>
		<li class="top">
			<a class="<?=$class?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
			
			<? if(sizeof($arSection['CHILDREN'])) { ?>
				<ul class="cat_menu children_section <?=$class?>">
				<? foreach($arSection['CHILDREN'] as $section):?>
					
					<? if($_REQUEST["SECTION_ID"] == $section["ID"])
						$child_class = "active";
					else
						$child_class = "";
					?>
					
					<?//print_r($arSection["ID"]);?>
					<li>
						<table>
							<tr>
								<td>
									<? if($section["PICTURE"]['SRC'] != ""):?>
										<img src="<?=$section["PICTURE"]['SRC']?>"/>
									<? endif;?>
								</td>
								<td class="right"><a class="cat <?=$child_class?>" href="<?=$section["SECTION_PAGE_URL"]?>"><?=$section["NAME"]?></a></td>
							</tr>
						</table>
						
						<? if(sizeof($section['ITEMS']) > 0) { ?>
							<ul class="children_item <?=$child_class?>">
								<? foreach($section['ITEMS'] as $Item) { ?>
								
								<? if($_REQUEST["ID"] == $Item["ID"])
									$child_class2 = "active";
								else
									$child_class2 = "";
								?>
								<li>
									- <a class="cat <?=$child_class2?>" href="<?=$Item["DETAIL_PAGE_URL"]?>&SECTION_ID=<?=$section["ID"]?>"><?=$Item['NAME']?></a>
								</li>
								<? } ?>
							</ul>
						<? } ?>
						
					</li>
					<?endforeach;?>
				</ul>	
			<? } ?>
		
			<?if(count($arSection["ITEMS"]) > 0):?>
				<ul class="cat_menu items <?=$class?>">
					<?foreach($arSection["ITEMS"] as $Item):?>
						<?//print_r($arSection["ID"]);?>
						<? if($_REQUEST["ID"] == $Item["ID"])
									$child_class3 = "active2";
								else
									$child_class3 = "";
								?>
						<li>
							<table>
								<tr>
									<td>
										<?if($Item["PREVIEW_PICTURE"]!=""):?>
											<img src="<?=CFile::GetPath($Item["PREVIEW_PICTURE"])?>"/>
										<?endif;?>
									</td>
									<td class="right"><a class="cat <?=$child_class3?>" href="<?=$Item["DETAIL_PAGE_URL"]?>&SECTION_ID=<?=$arSection["ID"]?>"><?=$Item["NAME"]?></a></td>
								</tr>
							</table>
						</li>
					<?endforeach;?>
				</ul>	
			<?endif;?>
		
		</li>
	<?endif;?>								
<?endforeach?>
</ul>