<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>


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