<?php


namespace IT\Intranet\Exchange\CSV;


abstract class CsvFile
{
    protected $fileName = 'default.csv';
    protected $filePath = '/test/';
    protected $fullName = '';
    protected $fileData = [];
    protected $firstRowTitle = true; //первая строка в файле - заголовки
    private $title = []; //Заголовки

    protected function getFullName() {
        $this->fullName = $_SERVER["DOCUMENT_ROOT"] . $this->filePath . $this->fileName;
    }

    public function loadData() {
        $i = 1;
        $file = @fopen($this->fullName , "r");
        while (($row = fgetcsv($file, 0, ";")) !== false) {
            if($this->firstRowTitle && $i == 1) {
                foreach($row as $rowTitle) {
                    $this->title[] = $rowTitle;
                }
            } else {
                if($this->firstRowTitle) {
                    $rowFormatData = [];
                    foreach($row as $key => $value) {
                        $rowFormatData[$this->title[$key]] = $value;
                    }
                    $this->fileData[] = $rowFormatData;
                } else {
                    $this->fileData[] = $row;
                }

            }
            $i++;
        }

        return $this->fileData;
    }

}