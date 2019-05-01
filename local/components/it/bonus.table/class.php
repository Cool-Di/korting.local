<?
/*
 * Компонент с таблицей наград за бонусы
 */

use IT\Intranet\Models\Bonus;

class BonusListComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     */
    public function executeComponent()
    {
        $obBonus =  new Bonus();
        $hlBonus = $obBonus->getEntity();

        $bonusTable = $hlBonus::getList([
            "order" => ["UF_POINTS" => "ASC"]
        ]);
        while ($bonuses = $bonusTable->Fetch()) {
            $this->arResult["BONUSES"][] = $bonuses;
        }

        $this->IncludeComponentTemplate();
    }
}


?>
