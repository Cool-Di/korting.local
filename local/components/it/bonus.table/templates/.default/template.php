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

<h3>Таблица товаров</h3>
<table class="table table-striped reports_table">
    <thead>
    <tr>
        <th>Товар</th>
        <th>Код</th>
        <th>Баллы</th>
    </tr>
    </thead>
    <tbody>
    <?foreach($arResult['SECTIONS'] as $section) {?>
        <tr>
            <? if($section['DEPTH_LEVEL'] == 1) {?>
                <td colspan="3" class="product_section"><?=$section['NAME']?></td>
            <?} else {?>
                <td colspan="3" class="product_subsection"><?=$section['NAME']?></td>
            <?}?>
        </tr>
        <?foreach($section['products'] as $product) {?>
            <tr>
                <td><?=$product['NAME']?></td>
                <td><?=$product['PROPERTY_ARTICLE_VALUE']?></td>
                <td><?=$product['PROPERTY_POINTS_VALUE']?></td>
            </tr>
         <?}?>
    <?}?>
    </tbody>
</table>

