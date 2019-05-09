<h3>История движения денежных средств</h3>
<table class="table table-striped reports_table">
    <thead>
    <tr>
        <th>Деньги</th>
        <th>Пользователь</th>
        <th>Период</th>
        <th>Дата</th>
        <th>Комментарий</th>
    </tr>
    </thead>
    <tbody>
    <? foreach($arResult['TRANSFER_LIST'] as $transfer){?>
        <tr>
            <td><?=$transfer["UF_MONEY"]?></td>
            <td><?=$transfer["USER_LAST_NAME"]?> <?=$transfer["USER_NAME"]?></td>
            <td><?=$transfer["PERIOD_NAME"]?></td>
            <td><?=$transfer["UF_DATE_ADDED"]->format("d.m.Y");?></td>
            <td><?=$transfer["UF_COMMENT"]?></td>
        </tr>
    <?}?>
    </tbody>
</table>
<div style="width:300px; font-weight: bold;">
    <table class="table table_bonus">
        <tbody>
        <tr>
            <td class="title_bonus">Остаток:</td>
            <td class="price_bonus"><?=$arResult['BALANCE']?></td>
        </tr>
        </tbody>
    </table>
</div>

