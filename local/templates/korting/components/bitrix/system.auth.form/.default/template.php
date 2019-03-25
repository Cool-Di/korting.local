<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["FORM_TYPE"] == "login"):?>

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>
<div class="wrap">
	<div class="form">
		<div class="inner_wrap auth">
			<table width="100%">
				<tbody>
					<tr>
						<td valign="top" class="left" width="30%"><img src="/media/images/logo.png"></td>
						<td class="right">
								<!--form class="questionn">
									<div class="fieldset">
										<div class="title">Логин, e-mail:</div>
										<div class="field"><input type="text" class="text"></div>
									</div>
									<div class="fieldset">
										<div class="title">Пароль:</div>
										<div class="field"><input type="text" class="text"></div>
									</div>
									
									<div class="fieldset">
										<div class="title">&nbsp;</div>
										<div class="field checkbox"><input type="checkbox">
										Запомнить меня на 2 недели
										</div>
									</div>
									<div class="fieldset">
										<div class="title"><a href="#" class="remember">Забыли пароль?</a></div>
										<div class="field"><span class="btn_grey_test"><input type="submit" value="Войти"></span></div>
									</div>
								</form-->
								
								<form  class="questionn" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
										<?if($arResult["BACKURL"] <> ''):?>
											<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
										<?endif?>
										<?foreach ($arResult["POST"] as $key => $value):?>
											<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
										<?endforeach?>
											<input type="hidden" name="AUTH_FORM" value="Y" />
											<input type="hidden" name="TYPE" value="AUTH" />
											<div class="fieldset">
												<div class="title"><?=GetMessage("AUTH_LOGIN")?>:</div>
												<div class="field"><input type="text" class="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" /></div>
											</div>	
											<div class="fieldset">
												<div class="title"><?=GetMessage("AUTH_PASSWORD")?></div>
												<div class="field"><input type="password" class="text" name="USER_PASSWORD" maxlength="50" size="17" /></div>
											</div>	
										<?/*if($arResult["SECURE_AUTH"]):?>
														<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
															<div class="bx-auth-secure-icon"></div>
														</span>
														<noscript>
														<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
															<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
														</span>
														</noscript>
										<script type="text/javascript">
										document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
										</script>
										<?endif*/?>
													
										<?/*if ($arResult["STORE_PASSWORD"] == "Y"):?>
												
													<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />
													<label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
											
										<?endif*/?>
										<?/*if ($arResult["CAPTCHA_CODE"]):?>
												
													<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:
													<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
													<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
													<input type="text" name="captcha_word" maxlength="50" value="" />
												
										<?endif*/?>
										<div class="fieldset">
											<div class="title">&nbsp;</div>
											<div class="field checkbox"><input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />
												<?echo GetMessage("AUTH_REMEMBER_SHORT")?>
											</div>
										</div>	
										<div class="fieldset">
											<div class="title"><a class="remember" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></div>
											<div class="field"><span class="btn_grey_test"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></span></div>
										</div>		
										</form>
								<div class="clear"></div>
							<a href="/registration/" rel="nofollow" class="btn_reg">Регистрация</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>	
	</div>
	<div class="about">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => "/include/auth_description.php",
			"EDIT_TEMPLATE" => ""
		),
	false
	);?>
	</div>
</div>
<?endif;?>
<? /*
<div class="bx-system-auth-form">
<?if($arResult["FORM_TYPE"] == "login"):?>

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>

<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<table width="95%">
		<tr>
			<td colspan="2">
			<?=GetMessage("AUTH_LOGIN")?>:<br />
			<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" /></td>
		</tr>
		<tr>
			<td colspan="2">
			<?=GetMessage("AUTH_PASSWORD")?>:<br />
			<input type="password" name="USER_PASSWORD" maxlength="50" size="17" />
<?if($arResult["SECURE_AUTH"]):?>
				<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
</script>
<?endif?>
			</td>
		</tr>
<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
		<tr>
			<td valign="top"><input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" /></td>
			<td width="100%"><label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label></td>
		</tr>
<?endif?>
<?if ($arResult["CAPTCHA_CODE"]):?>
		<tr>
			<td colspan="2">
			<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
			<input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
<?endif?>
		<tr>
			<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
		</tr>
<?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
		<tr>
			<td colspan="2"><noindex><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a></noindex><br /></td>
		</tr>
<?endif?>

		<tr>
			<td colspan="2"><noindex><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></noindex></td>
		</tr>
<?if($arResult["AUTH_SERVICES"]):?>
		<tr>
			<td colspan="2">
				<div class="bx-auth-lbl"><?=GetMessage("socserv_as_user_form")?></div>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"SUFFIX"=>"form",
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>
			</td>
		</tr>
<?endif?>
	</table>
</form>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"AUTH_URL"=>$arResult["AUTH_URL"],
		"POST"=>$arResult["POST"],
		"POPUP"=>"Y",
		"SUFFIX"=>"form",
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>

<?
//if($arResult["FORM_TYPE"] == "login")
else:
?>

<form action="<?=$arResult["AUTH_URL"]?>">
	<table width="95%">
		<tr>
			<td align="center">
				<?=$arResult["USER_NAME"]?><br />
				[<?=$arResult["USER_LOGIN"]?>]<br />
				<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
			</td>
		</tr>
		<tr>
			<td align="center">
			<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="logout" value="yes" />
			<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
			</td>
		</tr>
	</table>
</form>
<?endif?>
</div>*/?>