<?
/*
 * Компонент для списание средств
 */

use IT\Intranet\Entity\BonusEntity;
use IT\Intranet\Applications\UserMoney;
use Bitrix\Main\Type\DateTime;

class TransferAddComponent extends CBitrixComponent
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
     * @throws Exception
     */
    public function executeComponent()
    {
        if($_POST && $_POST["MONEY"]) {
            $this->arResult["MONEY"] = $_POST["MONEY"];
            $this->arResult["COMMENT"] = $_POST["COMMENT"];
            $userMoney = new \IT\Intranet\Applications\UserMoney($this->arParams["USER_ID"]);
            if($userMoney->getBalance() < $_POST["MONEY"]) {
                $this->arResult["ERRORS"][] = "Недостаточно средств для списания " . $_POST["MONEY"] . " руб.";
            }
            if(empty($this->arResult["ERRORS"])) {
                if (UserMoney::addTransfer($this->arParams["USER_ID"], (-1) * $_POST["MONEY"], $_POST["COMMENT"])) {
                    LocalRedirect($GLOBALS['APPLICATION']->GetCurPageParam());
                } else {
                    throw new \Exception("Ошибка добавления транзакции");
                }
            }
        }

        $this->IncludeComponentTemplate();
    }
}


?>
