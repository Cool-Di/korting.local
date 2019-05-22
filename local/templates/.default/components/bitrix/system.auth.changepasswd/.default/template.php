<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx-auth">

<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>

<div class="wrap">
	<div class="form">
		<div class="inner_wrap auth">
			<table width="100%">
					<tr>
						<td width="30%" valign="top" class="left"><img src="/media/images/logo.png"></td>
						<td class="right">
										<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
											<?if (strlen($arResult["BACKURL"]) > 0): ?>
											<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
											<? endif ?>
											<input type="hidden" name="AUTH_FORM" value="Y">
											<input type="hidden" name="TYPE" value="CHANGE_PWD">
											<input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input" />
											<? /*<b><?=GetMessage("AUTH_CHANGE_PASSWORD")?></b> */?>
											<div class="fieldset">
													<div class="title"><?=GetMessage("AUTH_LOGIN")?>*</div>
													<div class="field"><input type="text" name="USER_LOGIN" class="text" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" /></div>
											</div>
											<div class="fieldset">	
														<div class="title"><?=GetMessage("AUTH_NEW_PASSWORD_REQ")?>*</div>
														<div class="field"><input type="password" class="text" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input" /></div>
											</div>			
										<?if($arResult["SECURE_AUTH"]):?>
														<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
															<div class="bx-auth-secure-icon"></div>
														</span>
														<noscript>
														<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
															<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
														</span>
														</noscript>
										<script type="text/javascript">
										document.getElementById('bx_auth_secure').style.display = 'inline-block';
										</script>
										<?endif?>
										
										<div class="fieldset">
											<div class="title"><?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?></div>
											<div class="field"><input type="password" class="text" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input"  /></div>
										</div>
										<div class="clear"></div>
										<div>
											<span class="btn_grey_test">		
												<input type="submit" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" />
											</span>
										</div>		
										
										<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
										<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>
										<p>
										<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a>
										</p>
										
										</form>
					</td>						
				</tr>	
			</table>						
		</div>
	</div>
</div>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
</div>