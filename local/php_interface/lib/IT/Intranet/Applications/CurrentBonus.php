<?php


namespace IT\Intranet\Applications;


use CIBlockElement;
use Intranet;
use IT\Intranet\Entity\BonusEntity;
use IT\Intranet\Entity\MoneyTransferEntity;
use Bitrix\Main\Type\DateTime;

class CurrentBonus
{
    private $period = null;
    private $periodId = 0;
    private $userId = 0;
    private $accepted = 0;
    private $awaiting = 0;
    private $reward = 0;
    private $balance = 0;
    private $bonusInstance;
    private $hlTransfer;
    private $user;

    /**
     * @var int
     */
    private $usedPoints = 0;

    /**
     * @return \Bitrix\Main\Entity\DataManager
     * @throws \Exception
     */
    public function getHlTransfer()
    {
        if(!$this->hlTransfer) {
            $obTransfer = new MoneyTransferEntity();
            $this->hlTransfer = $obTransfer->getEntity();
        }

        return $this->hlTransfer;
    }


    /**
     * @return int
     */
    public function getPeriodId()
    {
        return $this->periodId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getUsedPoints()
    {
        return $this->usedPoints;
    }

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


    public function __construct($userId = 0, $periodId = 0)
    {
        global $USER;

        $this->bonusInstance = Bonus::getInstance();
        if (!intval($periodId))
            $this->periodId = Intranet::getInstance()->getCurrentPeriodId();
        else
            $this->periodId = $periodId;

        if (!intval($userId))
            $this->userId = $USER->GetID();
        else
            $this->userId = $userId;

        $this->user = Intranet::getInstance()->GetUserArr($this->userId);

        $this->getMonthSale();

    }

    /**
     * Получение данных о продажах по ID пользователю и ID периода
     * @param int $userId
     * @param int $periodId
     * @throws \Exception
     */
    public function getMonthSale()
    {

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", 'PROPERTY_PRICE', 'PROPERTY_STATUS');
        $arFilter = Array("IBLOCK_ID" => Intranet::getInstance()->REPORT_IBLOCK_ID, 'PROPERTY_USER_ID' => $this->userId, 'PROPERTY_PERIOD_ID' => $this->periodId);
        $res = CIBlockElement::GetList(Array('PROPERTIES_WEEK' => 'asc'), $arFilter, false, Array("nTopCount" => 100), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            if ($arFields['PROPERTY_STATUS_ENUM_ID'] == Intranet::getInstance()->getReportStatusIdByXmlId("ACCEPTED")) {
                $this->accepted += $arFields['PROPERTY_PRICE_VALUE'];
            } elseif ($arFields['PROPERTY_STATUS_ENUM_ID'] == Intranet::getInstance()->getReportStatusIdByXmlId("AWAITING")) {
                $this->awaiting += $arFields['PROPERTY_PRICE_VALUE'];
            }
        }

        $rewardInfo = $this->bonusInstance->getRewardByBonus($this->accepted);
        $this->reward = $rewardInfo['reward'];
        $this->balance = $rewardInfo['balance'];
        $this->usedPoints = $rewardInfo['usedPoints'];
    }

    public function toArray()
    {
        return [
            'accepted' => $this->getAccepted(),
            'awaiting' => $this->getAwaiting(),
            'reward' => $this->getReward(),
            'balance' => $this->getBalance(),
            'usedPoints' => $this->getUsedPoints()
        ];
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Exception
     */
    public function transferBonus()
    {

        $hlTransfer = $this->getHlTransfer();

        //Проверка существования такой записи за период
        if($this->existTransfer()) {
            throw new \Exception('За текущий период уже добавлена запись');
        }

        //Добавление перевода денег
        $result = $hlTransfer::add([
            'UF_MONEY' => $this->getReward(),
            'UF_USER_ID' => $this->getUserId(),
            'UF_PERIOD_ID' => $this->getPeriodId(),
            'UF_DATE_ADDED' => new DateTime(),
            'UF_COMMENT' => 'Перевод баллов в деньги за период'
        ]);
        if (!$result->isSuccess()) {
            throw new \Exception('Ошибка добавления денежный трансфер в базу');
        }

        //Добавление записи в продажи о списании баллов
        $this->addSystemReport("Списание баллов " . $this->user['FIO'], (-1) * $this->getAccepted());

        //Добавление записи в продажи на следующий период с остатком баллов
        $this->addSystemReport("Остаток с предыдущего периода " . $this->user['FIO'], $this->getBalance(), Intranet::getInstance()->getNextPeriod($this->getPeriodId()));

    }

    /**
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public function existTransfer()
    {
        $hlTransfer = $this->getHlTransfer();

        //Проверка существования такой записи за период
        $transferTable = $hlTransfer::getList([
            "filter" => [
                "UF_USER_ID" => $this->getUserId(),
                'UF_PERIOD_ID' => $this->getPeriodId(),
            ]
        ]);
        if ($transfer = $transferTable->Fetch()) {
            return true;
        } else {
            return false;
        }
    }

    public function addSystemReport(String $name, Int $points, $periodId = 0)
    {
        global $USER;

        if(!$periodId) {
            $periodId = $this->getPeriodId();
        }

        $city_shop	= Intranet::getInstance()->GetUserCityShop($this->userId);
        $city	= $city_shop['city'];
        $shop	= $city_shop['shop'];

        $el = new CIBlockElement;
        $PROP = [];
        $PROP['USER_ID']		= $this->getUserId();
        $PROP['PERIOD_ID']		= $periodId;
        $PROP['PRICE']          = $points;
        $PROP['SALE_DATE']      = date('d.m.Y');
        $PROP['IS_SYSTEM']      = "Y";
        $PROP['FIO'] 			= $this->user['FIO'];
        $PROP['CITY']			= $city['NAME'];
        $PROP['CITY_ID']		= $city['ID'];
        $PROP['SHOP']			= $shop['NAME'];
        $PROP['SHOP_ID']		= $shop['NAME'];
        $PROP['STATUS']         = Intranet::getInstance()->getReportStatusIdByXmlId("ACCEPTED");
        $arLoadProductArray = Array(
            "MODIFIED_BY"    	=> $USER->GetID(),
            "IBLOCK_SECTION_ID" => 0,
            "IBLOCK_ID"      	=> Intranet::getInstance()->REPORT_IBLOCK_ID,
            "PROPERTY_VALUES"	=> $PROP,
            "NAME"           	=> $name,
            "ACTIVE"         	=> "Y",
            "ACTIVE_FROM"       => ConvertTimeStamp(time(), "FULL", "ru"),
        );
        if($transferId = $el->Add($arLoadProductArray))
        {
        }
    }

}