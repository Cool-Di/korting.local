<h3>Списание денежных средств</h3>
<form class="form-horizontal report_form" role="form" method="post" name="report_form" enctype='multipart/form-data'>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Сумма списания</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="inputEmail3" name="MONEY" placeholder="Сумма" value="">
        </div>
    </div>
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
            <button type="submit" class="btn btn-default btn-primary">Отправить</button>
        </div>
    </div>
</form>