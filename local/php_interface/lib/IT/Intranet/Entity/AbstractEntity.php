<?php


namespace IT\Intranet\Entity;


use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

class AbstractEntity
{
    // имя таблицы
    protected $tableName;

    public function __construct()
    {
        if(!$this->tableName) {
            throw new \Exception('Имя таблицы не задано');
        }
    }

    public function getEntity()
    {
        Loader::includeModule('highloadblock');

        $hldata = HighloadBlockTable::getList(['filter' => ['TABLE_NAME' => $this->tableName]])->fetch();
        $hlentity = HighloadBlockTable::compileEntity($hldata);
        $entity_data_class = $hlentity->getDataClass();

        return $entity_data_class;
    }
}