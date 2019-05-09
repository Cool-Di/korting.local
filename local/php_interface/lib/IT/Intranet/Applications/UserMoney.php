<?php


namespace IT\Intranet\Applications;


use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Type\DateTime;
use IT\Intranet\Models\MoneyTransferEntity;

\CModule::IncludeModule('iblock');


class UserMoney
{
    private $userId = 0;
    private $hlTransfer;
    private $transferList = [];
    private $balance = 0;

    /**
     * @return array
     */
    public function getTransferList()
    {
        return $this->transferList;
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
    public function getUserId()
    {
        return $this->userId;
    }

    public function getHlTransfer()
    {
        if(!$this->hlTransfer) {
            $obTransfer = new MoneyTransferEntity();
            $this->hlTransfer = $obTransfer->getEntity();
        }

        return $this->hlTransfer;
    }

    public function __construct($userId = 0)
    {
        global $USER;

        if (!intval($userId))
            $this->userId = $USER->GetID();
        else
            $this->userId = $userId;

        $this->getAllTransfers();
    }

    public function getAllTransfers() {

        $hlTransfer = $this->getHlTransfer();

        $transferTable = $hlTransfer::getList([
                'select' => [
                    '*',
                    'PERIOD_NAME' => 'PERIODS.NAME',
                    'USER_NAME' => 'USER.NAME',
                    'USER_LAST_NAME' => 'USER.LAST_NAME',
                    'ADDED_NAME' => 'ADDED.NAME',
                    'ADDED_LAST_NAME' => 'ADDED.LAST_NAME',
                ],
                "filter" => [
                    "UF_USER_ID" => $this->getUserId(),
                ],
                'runtime' => [
                    new ReferenceField( //ИБ периодов
                        'PERIODS',
                        '\Bitrix\Iblock\ElementTable',
                        ['=this.UF_PERIOD_ID' => 'ref.ID']
                    ),
                    new ReferenceField( //Таблица пользователей
                        'USER',
                        '\Bitrix\Main\UserTable',
                        ['=this.UF_USER_ID' => 'ref.ID']
                    ),
                    new ReferenceField( //Таблица пользователей
                        'ADDED',
                        '\Bitrix\Main\UserTable',
                        ['=this.UF_USER_ADDED' => 'ref.ID']
                    )
                ]
            ]
        );
        while ($transfer = $transferTable->Fetch()) {
            //debugmessage($transfer);
            $this->balance += $transfer['UF_MONEY'];
            $this->transferList[] = $transfer;
        }
    }

    public static function addTransfer(int $userId, int $money, $comment='', $periodId = 0) {

        $obTransfer = new MoneyTransferEntity();
        $hlTransfer = $obTransfer->getEntity();

        $result = $hlTransfer::add([
            'UF_MONEY' => $money,
            'UF_USER_ID' => $userId,
            'UF_DATE_ADDED' => new DateTime(),
            'UF_COMMENT' => $comment,
            'UF_USER_ADDED' => $GLOBALS["USER"]->GetID()
        ]);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
}