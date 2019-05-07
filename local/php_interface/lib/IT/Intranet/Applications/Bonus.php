<?php


namespace IT\Intranet\Applications;

use CIBlockElement;
use Intranet;
use IT\Intranet\Models\BonusEntity;

class Bonus
{
    //Здесь хранится экземпляр класса
    private static $_Instance;

    private $bonuses = [];

    /**
     * @return array
     */
    public function getBonuses()
    {
        return $this->bonuses;
    }

    public function __construct()
    {
        $obBonus =  new BonusEntity();
        $hlBonus = $obBonus->getEntity();

        $bonusTable = $hlBonus::getList([
            "order" => ["UF_POINTS" => "ASC"]
        ]);
        while ($bonuses = $bonusTable->Fetch()) {
            $this->bonuses[] = $bonuses;
        }
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        //Проверяем был ли создан объект ранее
        if (!self::$_Instance)
        {
            //Если нет, то создаем его
            self::$_Instance = new self();
        }
        //Возвращаем объект
        return self::$_Instance;
    }

    public function getRewardByBonus($points) {
        $reward = 0;

        $bonusList = $this->getBonuses();
        foreach($bonusList as $bonus) {
            if($points >= $bonus['UF_POINTS']) {
                $reward = $bonus['UF_REWARD'];
                $balance = $points - $bonus['UF_POINTS'];
                $usedPoints = $bonus['UF_POINTS'];
            }
        }

        return ['reward' => $reward, 'balance' => $balance, 'usedPoints' => $usedPoints];
    }
}