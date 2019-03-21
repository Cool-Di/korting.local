<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>

	<div class="wrap">
		<div class="form">
			<div class="inner_wrap auth">
				<table width="100%">
					<tr>
						<td width="30%" valign="top" class="left"><img src="/media/images/logo.png"></td>
						<td class="right">
							<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
							<?
							if (strlen($arResult["BACKURL"]) > 0)
							{
							?>
								<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
							<?
							}
							?>
								<input type="hidden" name="AUTH_FORM" value="Y">
								<input type="hidden" name="TYPE" value="SEND_PWD">
								<p>
								<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
								</p>
							
							<table class="data-table bx-forgotpass-table">
								<thead>
									<tr> 
										<td colspan="2"><b><?=GetMessage("AUTH_GET_CHECK_STRING")?></b></td>
									</tr>
								</thead>
								<tbody>
									<?/*<tr>
										<td><?=GetMessage("AUTH_LOGIN")?></td>
										<td><input type="text" class="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />&nbsp;<?=GetMessage("AUTH_OR")?>
										</td>
									</tr>*/?>
									<tr> 
										<td><?=GetMessage("AUTH_EMAIL")?></td>
										<td>
											<input type="text" class="text" name="USER_EMAIL" maxlength="255" />
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr> 
										<td colspan="2">
											<span class="btn_grey_test">
												<input type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
											</span>
										</td>
									</tr>
								</tfoot>
							</table>
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
