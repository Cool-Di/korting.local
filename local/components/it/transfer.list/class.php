<?
/*
 * Компонент с движением денежных средства
 */

use IT\Intranet\Entity\BonusEntity;
use Bitrix\Main\Type\DateTime;

class TransferListComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        //Проверка доступа на просмотр другого пользователя
        if((int)$params["USER_ID"] > 0) {
            $access_level			= Intranet::getInstance()->GetUserAccessLevel();
            if($access_level < 100) {
                throw new \Exception("Недостаточно прав для просмотра страницы");
            }
        } else {
            $params["USER_ID"] = $GLOBALS["USER"]->GetID();
        }
        return $params;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     */
    public function executeComponent()
    {
        $userMoney = new \IT\Intranet\Applications\UserMoney($this->arParams["USER_ID"]);
        $this->arResult['TRANSFER_LIST'] = $userMoney->getTransferList();
        $this->arResult['BALANCE'] = $userMoney->getBalance();

        $this->IncludeComponentTemplate();
    }
}


?>
