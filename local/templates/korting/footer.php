			<?if($APPLICATION->sDirPath != "/contacts/"&& $APPLICATION->sDirPath != "/calendar/"):?>
			</div><!-- #content-->
			<?endif;?>
		</div><!-- #container-->
		
		<?if($APPLICATION->sDirPath != "/contacts/" && $APPLICATION->sDirPath != "/calendar/"):?>
			<div class="sidebar" id="sideLeft">
				<div class="wrap_left_menu">
					
					<? if($APPLICATION->sDirPath == "/question/" || $APPLICATION->sDirPath == "/tests/") { ?>
						<span class="title">Тесты</span>
						<?
							$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "_left", array(
							"IBLOCK_TYPE" => "",
							"IBLOCK_ID" => 15,
							"SECTION_ID" => "",
							"SECTION_CODE" => "",
							"COUNT_ELEMENTS" => "Y",
							"TOP_DEPTH" => "3",
							"SECTION_FIELDS" => array(),
							"SECTION_USER_FIELDS" => array(),
							"SECTION_URL" => "",
							"CACHE_TYPE" => "N",
							"CACHE_TIME" => "3600",
							"CACHE_GROUPS" => "N",
							"ADD_SECTIONS_CHAIN" => "N"
							),
							false
						);
						?>
					<? } elseif(strpos($APPLICATION->GetCurDir(), '/materials/') !== false) { ?>
						<span class="title">Материалы</span>
						<?
							$APPLICATION->IncludeComponent("korting:catalog.section.list", "_left", array(
							"IBLOCK_TYPE" => "",
							"IBLOCK_ID" => 16,
							"SECTION_ID" => "",
							"SECTION_CODE" => "",
							"COUNT_ELEMENTS" => "Y",
							"TOP_DEPTH" => "2",
							"SECTION_FIELDS" => array(),
							"SECTION_USER_FIELDS" => array(),
							"SECTION_URL" => "",
							"CACHE_TYPE" => "N",
							"CACHE_TIME" => "3600",
							"CACHE_GROUPS" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"USER_COMPANY_ID"	=> Korting::getInstance()->GetUserCompanyID(),
							"ADD_FILTER"	=> array(
								        "LOGIC" => "OR",
								        array("UF_MATERIAL_COMPANY" => false),
								        array("UF_MATERIAL_COMPANY" => Korting::getInstance()->GetUserCompanyID()),
								    )
							),
							false
						);
						?>
					<? } elseif(strpos($APPLICATION->GetCurDir(), '/webinars/') !== false) { ?>
						<span class="title">Вебинары</span>
						<?
							$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "_left", array(
							"IBLOCK_TYPE" => "",
							"IBLOCK_ID" => 17,
							"SECTION_ID" => "",
							"SECTION_CODE" => "",
							"COUNT_ELEMENTS" => "Y",
							"TOP_DEPTH" => "1",
							"SECTION_FIELDS" => array(),
							"SECTION_USER_FIELDS" => array(),
							"SECTION_URL" => "",
							"CACHE_TYPE" => "N",
							"CACHE_TIME" => "3600",
							"CACHE_GROUPS" => "N",
							"ADD_SECTIONS_CHAIN" => "N"
							),
							false
						);
						?>
					<? } else { ?>		
						<span class="title">Курсы</span>
						<?
							$APPLICATION->IncludeComponent("korting:catalog.section.list", "left", array(
							"IBLOCK_TYPE" => "",
							"IBLOCK_ID" => 14,
							"SECTION_ID" => "",
							"SECTION_CODE" => "",
							"COUNT_ELEMENTS" => "Y",
							"TOP_DEPTH" => "3",
							"SECTION_FIELDS" => array(),
							"SECTION_USER_FIELDS" => array('UF_COURSE_COMPANY'),
							"SECTION_URL" => "",
							"CACHE_TYPE" => "N",
							"CACHE_TIME" => "3600",
							"CACHE_GROUPS" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"USER_COMPANY_ID"	=> Korting::getInstance()->GetUserCompanyID(),
							"ADD_FILTER"	=> array(
								        "LOGIC" => "OR",
								        array("UF_COURSE_COMPANY" => false),
								        array("UF_COURSE_COMPANY" => Korting::getInstance()->GetUserCompanyID()),
								    )
							),
							false
						);
						?>
					<? } ?>	
				</div>
			</div>	
		
		<div class="sidebar" id="sideRight">
			<a id="calendar_events" href="/calendar">
				<?$mounth = array(
					"01"=>"Январь",
					"02"=>"Февраль",
					"03"=>"Март",
					"04"=>"Апрель",
					"05"=>"Май",
					"06"=>"Июнь",
					"07"=>"Июль",
					"08"=>"Август",
					"09"=>"Сентябрь",
					"10"=>"Октябрь",
					"11"=>"Ноябрь",
					"12"=>"Декабрь",
					);
				?>
				<div class="date">
					<div class="mounth">
					<?echo $mounth[date('m')];?>
					</div>
					<div class="day"><?=date('d')?></div>
				</div>
			</a>
			<a id="ask_question" class="<?if($APPLICATION->sDirPath == "/question/") echo 'active_question';?>" href="/question"></a>
			<? if(CModule::IncludeModule("iblock")):?>
					<?
						/*
$Tests = array();
						$arFilter = array("DEPTH_LEVEL" => 2);
						$sections = GetIBlockSectionList(15, false, array(), 10, $arFilter);
						while($arSection = $sections->GetNext())
						{
							$Tests[] = $arSection; 
						
						}
*/
						
						$arTests	= array();
						$arFilter	= Array('IBLOCK_ID' =>15, 'GLOBAL_ACTIVE'=>'Y', 'DEPTH_LEVEL' => 2);
						//проверка усли тест привязан к какой то компании
						$arFilter[]	= array(
										"LOGIC" => "OR",
										array('UF_COMPANY' => Korting::getInstance()->GetUserCompanyID()),
										array('UF_COMPANY' => false)
									);
									
						$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true, array('UF_COMPANY'), array('nPageSize' => 10));
						while($ar_result = $db_list->GetNext())
						{
							$arTests[]	= $ar_result;
						}
						shuffle($arTests);
					?>
					<? if(count($arTests) > 0):?>
						<div class="news_on_main">
							<span class="title test"><a href="/tests" title="Тесты">Тесты</a></span>
							<ul class="cat_menu" style="display: block;">
						<? foreach($arTests as $Test):?>
							<li>
								<table>
									<tr>
										<td><img src="<?=CFile::GetPath($Test["PICTURE"])?>"/></td>
										<td class="right"><a class="cat" href="<?=$Test["SECTION_PAGE_URL"]?>"><?=$Test["NAME"]?></a></td>
									</tr>
								</table>
							</li>
						<? endforeach;?>
							<ul/>
						</div>	
					<?endif;?>		
			<?endif;?>
		</div>
	
		<?endif;?>
	</div><!-- #middle-->

</div><!-- #wrapper -->

<div id="footer">
	<div class="left">©&nbsp;2013&nbsp;Korting&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Разработка сайта&nbsp;<a href="#">Sparkle Design Studio</a></div>
	<div class="right">
		<? $APPLICATION->IncludeComponent(
				"bitrix:menu",
				"bottom_menu",
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
	<div class="clear"></div>
</div><!-- #footer -->

</body>
</html>