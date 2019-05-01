<h3>Таблица перевода баллов в рубли</h3>
<table class="table table-striped reports_table">
    <thead>
    <tr>
        <th>Баллы</th>
        <th>Деньги</th>
    </tr>
    </thead>
    <tbody>
    <?foreach($arResult['BONUSES'] as $bonus) {?>
        <tr>
            <td><?=$bonus['UF_POINTS']?></td>
            <td><?=$bonus['UF_REWARD']?> руб.</td>
        </tr>
    <?}?>
    </tbody>
</table>

