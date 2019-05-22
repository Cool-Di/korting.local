<h3>Списание денежных средств</h3>
<?if(!empty($arResult["ERRORS"])){?>
    <div class="errors">
        <? foreach($arResult['ERRORS'] as $error) { ?>
            <span class="error"><?=$error?></span><br/>
        <? } ?>
    </div>
    <br/>
<?}?>

<form class="form-horizontal report_form" role="form" method="post" name="report_form" enctype='multipart/form-data'>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Сумма списания</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="inputEmail3" name="MONEY" placeholder="Сумма" value="<?=$arResult["MONEY"]?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">
            Комментарий
        </label>
        <div class="col-sm-5">
            <textarea class="form-control" name="COMMENT" rows="3"><?=$arResult["COMMENT"]?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
            <button type="submit" class="btn btn-default btn-primary">Отправить</button>
        </div>
    </div>
</form>