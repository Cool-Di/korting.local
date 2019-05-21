<h3>Запрос на снятие денежных средств</h3>
<? if($arResult['BALANCE'] <= 0) {?>
    Недостаточно средств для отправки запроса
<?} elseif($arResult['MONEY_REQUESTED']) {?>
    Запрос уже отправлен
<?} else {?>
    <form class="form-horizontal report_form" role="form" method="post" name="report_form" enctype='multipart/form-data'>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-3 control-label">
                Комментарий
            </label>
            <div class="col-sm-5">
                <textarea class="form-control" name="COMMENT" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-10">
                <input type="hidden" name="MONEY_REQUEST" value="Y"/>
                <button type="submit" class="btn btn-default btn-primary">Отправить</button>
            </div>
        </div>
    </form>
<?}?>

