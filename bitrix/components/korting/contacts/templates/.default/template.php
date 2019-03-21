
<div id="content_full">
  <div class="feedback">
    <h1>Обратная связь</h1>
  
    <div><? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?> <? } else { ?> 
      <br />
    <? if(isset($_GET['success'])) { ?> 
      <br />
    <? if(sizeof($arResult['ERRORS']) > 0) { ?>
      <div id="content_full">
        <div class="feedback">
          <h1><? foreach($arResult['ERRORS'] as $error) { ?><?=$error?></h1>
        </div>
      </div>
    </div>
   
    <div> 
      <br />
     </div>
   		 		  
<a name="success"></a>
 
    <h2>Ваше сообщение успешно отправлено!</h2>
   
    <br />
   
    <div class="desc"> 				Мы всегда рады помочь. 
      <br />
     	Просто оставьте свои контактные данные ниже, а также 
      <br />
     свой вопрос, и мы вскоре свяжемся с Вами. 			</div>
   		 	 
    <div class="error">   
      <br />
     		 		<? } ?> 		 	</div>
   		 	<? } ?> 		 	 			<form class="feedback" action="" method="post"> 				 
      <div class="fieldset"> 					 
        <div class="title">Имя</div>
       
        <div class="field"><input type="text" class="text" name="name" value="&lt;img id=" bxid_223143"=" src=" bitrix="" images="" fileman="" htmledit2="" php.gif"="" border="0" />&quot; border=&quot;0&quot; /&gt;</div>
       				</div>
     
      <div class="fieldset"> 					 
        <div class="title">Фамилия</div>
       
        <div class="field"><input type="text" class="text" name="last_name" value="&lt;img id=" bxid_206397"=" src=" bitrix="" images="" fileman="" htmledit2="" php.gif"="" border="0" />&quot; border=&quot;0&quot; /&gt;</div>
       				</div>
     
      <div class="fieldset"> 					 
        <div class="title">Email</div>
       
        <div class="field"><input type="text" class="text" name="email" value="&lt;img id=" bxid_279955"=" src=" bitrix="" images="" fileman="" htmledit2="" php.gif"="" border="0" />&quot; border=&quot;0&quot; /&gt;</div>
       				</div>
     
      <div class="fieldset"> 					 
        <div class="title">Контактный телефон</div>
       
        <div class="field"><input type="text" class="text" name="phone" value="&lt;img id=" bxid_609424"=" src=" bitrix="" images="" fileman="" htmledit2="" php.gif"="" border="0" />&quot; border=&quot;0&quot; /&gt;</div>
       				</div>
     
      <div class="fieldset"> 					 
        <div class="title">Сообщение</div>
       
        <div class="field"><textarea name="message">&lt;img id=&quot;bxid_933615&quot; src=&quot;/bitrix/images/fileman/htmledit2/php.gif&quot; border=&quot;0&quot;/&gt;</textarea></div>
       				</div>
     
      <div class="fieldset"> 					 
        <div class="title">&nbsp; </div>
       
        <div class="field"><span class="btn_grey"><input type="submit" value="Отправить сообщение" /></span></div>
       				</div>
     			</form> 		 	 		 	 		<? } ?> 		 		 		 	</div>
 
  <div class="map"> 		 
    <h1>Контактная информация</h1>
   
    <div class="desc"> 			Единый информационный центр Korting: +7(495)6629541         
      <br />
     			Вопросы и пожелания по работе сайта, а также запрос на проведение обучения: <a href="mailto:academy@korting.ru" >academy@korting.ru</a> 			 
<!-- <div id="yandex_map"></div>-->
 			 		</div>
   	</div>
 
  <div class="clear"></div>
 	 </div>
