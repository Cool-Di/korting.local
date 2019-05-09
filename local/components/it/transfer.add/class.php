<?
/*
 * Компонент для списание средств
 */

use IT\Intranet\Models\BonusEntity;
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
            if(UserMoney::addTransfer($this->arParams["USER_ID"], (-1)*$_POST["MONEY"], $_POST["COMMENT"])){
                LocalRedirect($GLOBALS['APPLICATION']->GetCurPageParam());
            } else {
                throw new \Exception("Ошибка добавления транзакции");
            }
        }

        $this->IncludeComponentTemplate();
    }
}


?>
