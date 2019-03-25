<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?> <? if(sizeof($arResult['ERRORS']) > 0 ) { ?> 
<div class="errors"> 	<? foreach($arResult['ERRORS'] as $error) { ?> 		<?=$error?> 
  <br />
 	<? } ?> </div>
 <? } ?> <? if(isset($_GET['success'])) { ?> 	 
<br />
 
<br />
 
<br />
 	 
<a name="success"></a>
 	 
<h2>Спасибо! Ваша заявка отправлена.</h2>
 
<br />
 <? } else { ?> 
<br />
 
<br />
 
<p><b><font color="#262626" face="Arial" size="2">Вам интересна другая тема или другое время? 
      <br />
     </font><font color="#262626" face="Arial" size="2"> Напишите нам заявку.</font></b></p>
 <form class="questionn" method="post"> 	 	 
  <div class="fieldset"> 		 
    <div class="title">Имя:</div>
   		 
    <div class="field"><input type="text" class="text" name="name" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">Фамилия:</div>
   		 
    <div class="field"><input type="text" class="text" name="last_name" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">Компания:</div>
   		 
    <div class="field"><input type="text" class="text" name="company" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">Должность:</div>
   		 
    <div class="field"><input type="text" class="text" name="work_post" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">Email:</div>
   		 
    <div class="field"><input type="text" class="text" name="email" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">Удобное время:</div>
   		 
    <div class="field"><input type="text" class="text" name="time" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">Интересующая тема:</div>
   		 
    <div class="field"><input type="text" class="text" name="theme" size="40" /></div>
   	</div>
 	 
  <div class="fieldset"> 		 
    <div class="title">&nbsp; </div>
   		 
    <div class="field"><span class="btn_grey"><input type="submit" value="Записаться" /></span></div>
   	</div>
 </form> <? } ?> 
<div class="clear"></div>
