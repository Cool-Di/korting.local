<?
//Пустой компонент со структурой

class EmptyComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {

        $this->IncludeComponentTemplate();
    }
}


?>
