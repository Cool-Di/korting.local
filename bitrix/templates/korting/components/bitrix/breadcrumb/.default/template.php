<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '<ul class="breadcrumb">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
	if($index > 0)
		$strReturn .= '<li>/</li>';

	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	
	
	/*if($arResult[$index]["LINK"] <> "")
		$strReturn .= '<li><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a></li>';
	else
		$strReturn .= '<li>'.$title.'</li>';
	*/	
		
	if(str_replace('&amp;', '&', CMain::GetCurUri()) != str_replace('&amp;', '&', $arResult[$index]["LINK"]) && $arResult[$index]["LINK"] <> "")
		$strReturn .= '<li><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a></li>';
	else
		$strReturn .= '<li>'.$title.'</li>';		
			
		
		
}

$strReturn .= '</ul><div class="clear"></div>';
return $strReturn;
?>

