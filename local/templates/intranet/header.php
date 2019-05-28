<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);

/*if($_REQUEST['testtest'])
	CUser::Authorize(198);*/
if (!$USER->IsAuthorized())
{
    LocalRedirect('/auth/');
}

$obAsset = \Bitrix\Main\Page\Asset::getInstance();
?>
<!DOCTYPE html>
<html>
<head>
	<?$APPLICATION->ShowHead();?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $obAsset->addJs('https://code.jquery.com/jquery.js');
    $obAsset->addJs('/assets/js/vendor/jquery-ui-1.12.1/jquery-ui.min.js');
    $obAsset->addJs('/assets/js/vendor/jquery-ui-1.12.1/datepicker-ru.js');
    $obAsset->addJs('/intranet/media/bootsrtap/js/bootstrap.min.js'); //Include all compiled plugins (below), or include individual files as needed

    $obAsset->addJs('/intranet/media/fancyapps/source/jquery.fancybox.pack.js?v=2.1.5'); //Add fancyBox

    $obAsset->addJs('/intranet/media/js/main.js');
    ?>
    <!-- Bootstrap -->
    <link href="/intranet/media/bootsrtap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery-ui -->
    <link rel="stylesheet" href="/assets/js/vendor/jquery-ui-1.12.1/jquery-ui.min.css" type="text/css" media="screen" />

    <!-- Add fancyBox -->
	<link rel="stylesheet" href="/intranet/media/fancyapps/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />

</head>

<body style="">

<?$APPLICATION->ShowPanel()?>

<div id="wrap_header">
	<div id="header">	
		<div class="top">
			<?//echo "<pre>".print_r($USER->GetFullName(), 1)."</pre>";?>
			
			<?if ($USER->IsAuthorized()):?>
				<a id="exit" href="/?logout=yes"></a>
				<div class="hello">Добро пожаловать,<br/><b><?=$USER->GetFullName()?></b></div>
			<?else:?>
				<a id="enter" href="/auth/"></a>			
			<?endif;?>
			<div class="clear"></div>
		</div>
		<div class="bottom">
			<a href="/intranet/" class="logo"><img src="/media/images/logo.png"/></a>
			<? $APPLICATION->IncludeComponent(
				"bitrix:menu",
				"top_menu_korting",
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

<? /*
	<div class="navbar navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Korting</a>
        </div>
        <div class="collapse navbar-collapse">
			<?
			if ($USER->IsAuthorized())
			{
				$APPLICATION->IncludeComponent("bitrix:menu", 
				   "top_menu", 
				   array("ROOT_MENU_TYPE" => "top",
				         "MENU_CACHE_TYPE" => "N",
				         "MENU_CACHE_TIME" => "3600",
				         "MENU_CACHE_USE_GROUPS" => "Y",
				         "MENU_CACHE_GET_VARS" => array(),
				         "MAX_LEVEL" => "1",
				         "CHILD_MENU_TYPE" => "left",
				         "USE_EXT" => "N",
				         "DELAY" => "N",
				         "ALLOW_MULTI_SELECT" => "N"
				        ), 
				   false);
			}
			?>
			<?
				$user_arr	= Intranet::getInstance()->GetUserArr();
			?>
			<ul class="nav navbar-nav navbar-right">
				<li><a><?=$user_arr['FIO']?></a></li>
				<li><a href="/?logout=yes">Выход</a></li>
			</ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->
<? */ ?>
    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <?/*
 <div class="jjumbotron" style="padding:20px;background: url('/media/images/header_bg.png');">
            <!-- <h2>Intranet Korting</h2> -->
            <a href="/" class="logo"><img src="/media/images/logo.png"></a>
            <h4>Система он-лайн отчетности</h4>
          </div>
*/?> 
          <div class="row">
            <div class="col-12 col-sm-12 col-lg-12">
				 <?$APPLICATION->IncludeComponent(
					"bitrix:breadcrumb",
					"",
					Array(
						"START_FROM" => "1",
						"PATH" => "",
						"SITE_ID" => "-"
					),
				false
				);?>

       

<?if($USER->IsAdmin()):?>



<?endif?>