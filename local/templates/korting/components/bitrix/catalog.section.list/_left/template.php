<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="left_menu">
<? //dump($arResult["SECTIONS"][0]);
$CURRENT_DEPTH=$arResult["SECTION"]["DEPTH_LEVEL"]+1;
foreach($arResult["SECTIONS"] as $arSection):
//echo "<pre>".print_r($arSection,1)."</pre>";
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
	if($CURRENT_DEPTH<$arSection["DEPTH_LEVEL"])
		echo "<ul class='cat_menu'>";
	elseif($CURRENT_DEPTH>$arSection["DEPTH_LEVEL"])
		echo str_repeat("</ul></li>", $CURRENT_DEPTH - $arSection["DEPTH_LEVEL"]);
	$CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];
?>

	<? if($_REQUEST["SECTION_ID"] == $arSection["ID"])
		$child_class = "active";
	else
		$child_class = "";
	?>
	
	<?if($arSection["DEPTH_LEVEL"] == 1):?>
		<li class="top">
			<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="<?=$child_class?>"><?=$arSection["NAME"]?></a>
	<?else:?>
		<li>
			<table>
			<tr>
				<td>
					<?if($arSection["PICTURE"]["SRC"]!=""):?>
						<img src="<?=$arSection["PICTURE"]["SRC"]?>"/>
					<?endif;?>
				</td>
				<td class="right"><a class="cat <?=($arSection["ID"] == $_REQUEST["SECTION_ID"] ? 'child_active' : '')?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></td>
			</tr>
		</table>
	<?endif;?>							
		
		
		
<?endforeach?>
</ul>