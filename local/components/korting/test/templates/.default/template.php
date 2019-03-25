<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h3 class="test">Тест</h3>
<?
	if(sizeof($arResult['ERRORS']) > 0)
	{
		foreach($arResult['ERRORS'] as $error)
		{
		?>
			<?=$error?> <br/>
		<?
		}
	}
	elseif(isset($arResult['TIME_LEFT']) && $arResult['TIME_LEFT'] < 0)
	{
		?>
			Время отведенное на прохождение теста истекло.
		<?
	}
	else
	{
?>
		
		<h1><?=$arResult["SECTION"]["NAME"]?></h1>
		<?=$arResult["SECTION"]["DESCRIPTION"]?>
		<? if($arResult['SECTION']['UF_TIME_LIMIT'] > 0) { ?>
			Время прохождения теста <strong><?=$arResult['SECTION']['UF_TIME_LIMIT']?> минут</strong>
			,
			осталось <?=floor($arResult['TIME_LEFT']/60)?> минут <?=($arResult['TIME_LEFT']%60)?> сек.
		
		<? } ?>
		<hr>
		<form action="<?=$arResult["SECTION"]['SECTION_PAGE_URL']?>" method="post">
		<input type="hidden" name="question" value="<?=($arResult["QUESTION_NUMBER"]+1)?>" />
		<input type="hidden" name="question_id" value="<?=$arResult['QUESTION']['ID']?>" />
		
		<? if(is_array($arResult['ANSWERS']) && sizeof($arResult['ANSWERS']) > 0) { ?>
			<? foreach($arResult['ANSWERS'] as $questionID => $answer) { ?>
				<input type="hidden" name="answers[<?=$questionID?>]" value="<?=$answer?>" />
			<? } ?>
		<? } ?>
		
		<? if(is_array($arResult['RESULT'])) { ?>
			<h2>Ваш результат:</h2>
			Вы набрали: <?=$arResult['RESULT']['true_percent']?>%.
			<br/>
			Всего вопросов: <?=$arResult['RESULT']['quest_count']?>
			<br/>
			Правильных ответов: <?=$arResult['RESULT']['true_count']?>
			<br/>
			
			<ol class="question_result">
			<? foreach($arResult['QUESTION_RESULT'] as $k => $q) { ?>
				<li><?=$q['NAME']?> - <strong><?=($q['TRUE'] ? 'правильно' : 'не правильно')?></strong></li>
			<? } ?>
			</ol>
			
		<? } else { ?>
			<div class="questions"  id="question">
				<? // echo "<pre>".print_r($arResult["SECTION"], 1)."</pre>"; ?>
				<span class="title">
					<span class="number"><?=($arResult["QUESTION_NUMBER"]+1)?>.</span>
					<?=$arResult['QUESTION']["NAME"]?>
				</span>
				
				<? 
					if(!empty($arResult['QUESTION']['PREVIEW_PICTURE']))
					{
						$picture	= CFile::GetFileArray($arResult['QUESTION']['PREVIEW_PICTURE']);
						?>
						<img style="max-width:80%;" src="<?=$picture['SRC']?>" />
						<?
					}
				?>
				
				<ul class="answer">
					<? foreach($arResult['QUESTION']["PROPERTIES"]['QUESTION']['VALUE'] as $k => $QuestionValue):?>
						<li><input type="radio" class="checkbox" name="answers[<?=$arResult['QUESTION']['ID']?>]" value="<?=$k?>"><?=$QuestionValue?></li>
					<? endforeach;?>
				</ul>
			
				<? if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
					<?=$arResult["NAV_STRING"]?>
				<? endif;?>
				
				<? if($arResult["QUESTION_NUMBER"] < (sizeof($arResult['QUESTIONS']) -1)) { ?>
				<span class="btn_grey_blue">
					<input  class="next_question" type="submit" value="Следующий вопрос" />
				</span>
			<? } else {?>
				<span class="btn_grey_blue">
					<input type="hidden" name="act" value="result" />
					<input  class="next_question" type="submit" value="Узнать результат" />
				</span>	
			<? } ?>
				
				Вы ответили на 
				<span class="answer_complite"><?=$arResult["QUESTION_NUMBER"]?></span>
				из 
				<?=sizeof($arResult['QUESTIONS'])?>
				вопросов
		</div>
		<? } ?>
		</form>
<? } ?>