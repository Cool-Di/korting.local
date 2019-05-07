<?php


namespace IT\Intranet\Applications;


use IT\Intranet\Models\MoneyTransferEntity;

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
            "filter" => [
                "UF_USER_ID" => $this->getUserId(),
            ]
        ]);
        while ($transfer = $transferTable->Fetch()) {
            $this->balance += $transfer['UF_MONEY'];
            $this->transferList[] = $transfer;
        }
    }
}