<?php


namespace IT\Intranet\Models;


use CIBlockElement;
use DateTime;
use Intranet;

class PeriodModel
{
    private $period = null;
    private $periodStartDate;
    private $periodEndDate;
    private $periodLastDate;

    public function __construct($periodId = 0)
    {
        $arSelect = Array("ID", "NAME", "ACTIVE_FROM", "ACTIVE_TO", "PROPERTY_LAST_DAY");
        $arFilter = Array(
            "IBLOCK_ID" => Intranet::getInstance()->PERIOD_IBLOCK_ID
        );
        if((int)$periodId > 0) {
            $arFilter["ID"] = $periodId;
        } else {
            $arFilter["ACTIVE_DATE"] = "Y";
        }
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($period = $res->Fetch()) {
            $this->period = $period;
            $this->periodStartDate = DateTime::createFromFormat("d.m.Y", $period["ACTIVE_FROM"]);
            $this->periodEndDate = DateTime::createFromFormat("d.m.Y", $period["ACTIVE_TO"]);
            $this->periodLastDate = DateTime::createFromFormat("d.m.Y", $period["PROPERTY_LAST_DAY_VALUE"]);
        } else {
            throw new \Exception('В БД не заведён отчетный период, соответстующий текущей дате или ID');
        }
    }

    /**
     * @return array|null
     */
    public function getPeriod(): array
    {
        return $this->period;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isFinished() {
        $currentDay = new DateTime();
        return $currentDay > $this->periodLastDate;
    }

    /**
     * @return bool|DateTime
     */
    public function getPeriodLastDate()
    {
        return $this->periodLastDate;
    }


}