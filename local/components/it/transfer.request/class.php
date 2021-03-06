<?
/*
 * Компонент для запроса снятия денежных средств
 */

use IT\Intranet\Entity\BonusEntity;
use Bitrix\Main\Type\DateTime;

class TransferRequestComponent extends CBitrixComponent
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
        $userMoney = new \IT\Intranet\Applications\UserMoney();
        $this->arResult['TRANSFER_LIST'] = $userMoney->getTransferList();
        $this->arResult['BALANCE'] = $userMoney->getBalance();

        $this->arResult['MONEY_REQUESTED'] = $_COOKIE['money_requested'] == "Y" ? true : false;

        $dayOfMonth = date('d');
        //Только с 10 по 20 числа каждого месяца доступна кнопка запроса снятия денег.
        if($dayOfMonth < 10 || $dayOfMonth > 20) {
            $this->arResult['FORBIDDEN_DATE'] = true;
        }

        if($_POST && $_POST["MONEY_REQUEST"] == "Y" && !$this->arResult['MONEY_REQUESTED']) {

            $arFields = Array(
                "USERNAME" => $GLOBALS["USER"]->GetFullName(),
                "COMMENT" => htmlspecialcharsEx($_POST["COMMENT"]),
                "REPORT_LINK" => "/intranet/money/add_transfer.php?USER_ID=" . $GLOBALS["USER"]->GetID(),
                "BALANCE" => $this->arResult['BALANCE']
            );
            CEvent::Send("MONEY_REQUEST", 's1', $arFields);
            setcookie('money_requested', "Y", time() + 60*24, '/');
            //debugmessage($_POST);
            LocalRedirect($GLOBALS['APPLICATION']->GetCurPageParam());
        }

        $this->IncludeComponentTemplate();
    }
}


?>
