<?php


namespace IT\Intranet\Exchange\CSV;


class ProductBonusCsvFile extends CsvFile
{
    protected $fileName = 'points.csv';
    protected $filePath = '/exchange/';
    protected $fullName = '';

    public function __construct()
    {
        $this->getFullName();
    }

}