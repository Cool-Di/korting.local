<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$bWasSelect = false;

/*?>
<select class="company_list" name="<?=$arParams["arUserField"]["FIELD_NAME"]?>">
<option value="-1"<?=($_POST['UF_COMPANY'] == -1? ' selected="selected"' : '')?>>Другая</option>
<?
foreach ($arParams["arUserField"]["USER_TYPE"]["FIELDS"] as $key => $val)
{
	$bSelected = in_array($key, $arResult["VALUE"]) && 
	(	(!$bWasSelect) || ($arParams["arUserField"]["MULTIPLE"] == "Y") );
	
	$bWasSelect = $bWasSelect || $bSelected;

	?>
	
	<option value="<?=$key?>"<?=($bSelected? " selected" : "")?>><?=$val?></option><?	
}
?>
</select> */?>
<input type="hidden" name="<?=$arParams["arUserField"]["FIELD_NAME"]?>" value="-1" />
<input type="text" class="text" name="new_company" value="<?=$_POST['new_company']?>"/>