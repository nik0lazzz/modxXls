<?php
// Подключаем
define('MODX_API_MODE', true);
require $_SERVER['DOCUMENT_ROOT'].'/index.php';

// Включаем обработку ошибок
$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');


function getXLS($xls){

    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/components/modxexcel/lib/phpexcel/PHPExcel/IOFactory.php';
    $filePatch = $_SERVER['DOCUMENT_ROOT'].'/assets/components/modxexcel/files/';


    $objPHPExcel = PHPExcel_IOFactory::load($filePatch.$xls);
    $objPHPExcel->setActiveSheetIndex(0);
    $aSheet = $objPHPExcel->getActiveSheet();

    //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
    $array = array();
    //получим итератор строки и пройдемся по нему циклом
    foreach($aSheet->getRowIterator() as $row){
        //получим итератор ячеек текущей строки
        $cellIterator = $row->getCellIterator();
        //пройдемся циклом по ячейкам строки
        //этот массив будет содержать значения каждой отдельной строки
        $item = array();
        foreach($cellIterator as $cell){
            //заносим значения ячеек одной строки в отдельный массив
            array_push($item, $cell->getCalculatedValue());
        }
        //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
        array_push($array, $item);
    }
    return $array;
}


$xlsData = getXLS('h1title.xlsx'); //извлеаем данные из XLS
//print_r($xlsData);
$fildName = array(
    'title' => 'meta_title',
    'h1' => 'h1',

);


foreach($xlsData as $row){

    $uri = str_replace("http://www.dveriarkada.ru/", "", $row['1']);
    if($res = $modx->getObject('modResource', array( 'uri' => $uri))) {

     //   Запись в TV
     //   $res->setTVValue( $fildName[$row['0']], $row['2']);

    }
}



?>