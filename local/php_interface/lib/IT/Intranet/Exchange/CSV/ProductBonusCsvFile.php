<?php


namespace IT\Intranet\Exchange\CSV;


class ProductBonusCsvFile extends CsvFile
{
    protected $fileName = 'test.csv';
    protected $filePath = '/test/';
    protected $fullName = '';

    public function __construct()
    {
        $this->getFullName();
    }

}