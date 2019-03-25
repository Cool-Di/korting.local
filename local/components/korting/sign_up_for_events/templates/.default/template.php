<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="wrap_form">
	<a href="#" class="write">Записаться</a>
	<div class="write_form">
		<div class="result"></div>
		<form action="" method="POST">
			<input type="hidden" name="ajax"/>
			<input type="hidden" name="event_name" value="<?=$arParams['EVENT_NAME']?>"/>
			<input type="hidden" name="time" value="<?=$arParams['TIME']?>"/>
			<input type="hidden" name="location" value="<?=$arParams['LOCATION']?>"/>
			<table width="100%">
				<tr>
					<td>Имя:</td>
					<td><input type="text" name="name" value="<?=$_POST['name']?>"/></td>
				</tr>
				<tr>
					<td>Фамилия:</td>
					<td><input type="text" name="last_name" value="<?=$_POST['last_name']?>"/></td>
				</tr>
				<tr>
					<td>Отчество:</td>
					<td><input type="text" name="second_name" value="<?=$_POST['second_name']?>"/></td>
				</tr>
				<tr>
					<td>Компания:</td>
					<td><input type="text" name="company" value="<?=$_POST['company']?>"/></td>
				</tr>
				<tr>
					<td>Должность:</td>
					<td><input type="text" name="post" value="<?=$_POST['post']?>"/></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" name="email" value="<?=$_POST['email']?>"/></td>
				</tr>
				<tr>
					<td>Контактный телефон:</td>
					<td><input type="text" name="phone"/></td>
				</tr>
				<tr>
					<td colspan="2" align="left"><a href="#" class="post">Отправить</a></td>
				</tr>
			</table>
		</form>
	</div>
</div>

<?/*
<h1 class="question">Задать вопрос</h1>

<? if(isset($_GET['success'])) { ?>
		<a name="success"></a>
		<h2>Ваше сообщение успешно отправлено!</h2><br/>
	<? } else { ?>
  	<? if(sizeof($arResult['ERRORS']) > 0) { ?>
  	<div class="error">
  		<? foreach($arResult['ERRORS'] as $error) { ?> 
  			<?=$error?><br/>
  		<? } ?>
  	</div>
  	<? } ?>
  	
	<form class="feedback" action="" method="post">
		<div class="fieldset">
			<div class="title">Имя <span class="mf-req">*</span></div>
			<div class="field"><input type="text" class="text" name="name" value="<?=$_POST['name']?>"></div>
		</div>
		<div class="fieldset">
			<div class="title">Email <span class="mf-req">*</span></div>
			<div class="field"><input type="text" class="text" name="email" value="<?=$_POST['email']?>" ></div>
		</div>
		<div class="fieldset">
			<div class="title">Контактный телефон</div>
			<div class="field"><input type="text" class="text" name="phone" value="<?=$_POST['phone']?>" ></div>
		</div>
		<div class="fieldset">
			<div class="title">Сообщение <span class="mf-req">*</span></div>
			<div class="field"><textarea name="message"><?=$_POST['message']?></textarea></div>
		</div>
		<div class="fieldset">
			<div class="title">&nbsp; </div>
			<div class="field"><span class="btn_grey"><input type="submit" value="Отправить сообщение"></span></div>
		</div>
	</form>
  	
  	
<? } ?>

<div class="clear"></div>

*/?>