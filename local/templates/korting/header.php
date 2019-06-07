<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); $GLOBALS["APPLICATION"]->RestartBuffer();?>
<?
	if (!$USER->IsAuthorized())
	{
		LocalRedirect('/auth/');
	} else {
        LocalRedirect('/intranet/'); //скрываем раздел с академией, всех перекидываем на интранет всегда4554к5
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=$APPLICATION->ShowTitle()?></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="/media/js/scripts.js?ver=07062019"></script>
	
	<?$APPLICATION->ShowHead();?>
</head>
<body>
<?$APPLICATION->ShowPanel()?>
<div id="wrapper">
<div id="onTop"><span>↑ Наверх</span></div>
<div id="wrap_header">
	<div id="header">	
		<div class="top">
			<?//echo "<pre>".print_r($USER->GetFullName(), 1)."</pre>";?>
			
			<?if ($USER->IsAuthorized()):?>
				<a id="exit" href="/?logout=yes"></a>
				<div class="hello">Добро пожаловать,<br/><b><?=$USER->GetFullName()?></b></div>
			<?else:?>
				<a id="enter" href="/auth"></a>
			<?endif;?>
			<div class="clear"></div>
		</div>
		<div class="bottom">
			<a href="/" class="logo"><img src="/media/images/logo.png"/></a>
			<? $APPLICATION->IncludeComponent(
				"bitrix:menu",
				"top_menu",
				Array(
					"ROOT_MENU_TYPE" => "top",
					"MAX_LEVEL" => "1",
					"CHILD_MENU_TYPE" => "left",
					"USE_EXT" => "N",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
					"MENU_CACHE_TYPE" => "N",
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array()
				),
			false
			);?>
		</div>
	</div>
</div><!-- #header-->
<div id="middle">
<div id="container">
	<?if($APPLICATION->sDirPath != "/contacts/" && $APPLICATION->sDirPath != "/calendar/"):?>
		<div id="content">
	<?endif;?>	