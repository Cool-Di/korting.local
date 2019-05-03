<?php


namespace IT\Intranet\Applications;


use CIBlockElement;
use Intranet;

class CurrentBonus
{
    /**
     * @var int
     */
    private $accepted = 0;
    /**
     * @var int
     */
    private $awaiting = 0;
    /**
     * @var int
     */
    private $reward = 0;
    /**
     * @var int
     */
    private $balance = 0;
    /**
     * @var Bonus
     */
    private $bonusInstance;

    /**
     * @return int
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * @return int
     */
    public function getAwaiting()
    {
        return $this->awaiting;
    }


    public function __construct()
    {
        $this->bonusInstance = Bonus::getInstance();

        $this->getMonthSale();

    }

    /**
     * Получение данных о продажах по ID пользователю и ID периода
     * @param int $userId
     * @param int $periodId
     * @throws \Exception
     */
    public function getMonthSale($userId = 0, $periodId = 0)
    {
        global $USER;


        if (!intval($periodId))
            $periodId = Intranet::getInstance()->getCurrentPeriodId();

        if (!intval($userId))
            $userId = $USER->GetID();

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", 'PROPERTY_PRICE', 'PROPERTY_STATUS');
        $arFilter = Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, 'PROPERTY_USER_ID' => $userId, 'PROPERTY_PERIOD_ID' => $periodId);
        $res = CIBlockElement::GetList(Array('PROPERTIES_WEEK' => 'asc'), $arFilter, false, Array("nTopCount" => 100), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            if ($arFields['PROPERTY_STATUS_ENUM_ID'] == Intranet::getInstance()->getReportStatusIdByXmlId("ACCEPTED")) {
                $this->accepted += $arFields['PROPERTY_PRICE_VALUE'];
            } else {
                $this->awaiting += $arFields['PROPERTY_PRICE_VALUE'];
            }
        }

        $rewardInfo = $this->bonusInstance->getRewardByBonus($this->accepted);
        $this->reward = $rewardInfo['reward'];
        $this->balance = $rewardInfo['balance'];
    }

}