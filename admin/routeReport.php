<?php
function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once ( 'Classes/PHPExcel.php');
include_once ("session.php");
$route=$_GET['info'];
$store=$_GET['store'];



// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$instruction = "SELECT id, nombre, puntos, tienda_id, municipio, ruta FROM Usuario WHERE ruta='$route' AND tienda_id='$store';";
$surveyTaker = $mysqli->query($instruction);



// Set document properties
$objPHPExcel->getProperties()->setCreator("Carlos Melgoza")
    ->setLastModifiedBy("Carlos Melgoza")
    ->setTitle("Reporte por tienda")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");
//$objPHPExcel->setPreCalculateFormulas(false);


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Ciudad')
    ->setCellValue('B1', 'Ruta')
    ->setCellValue('C1', 'Nombre')
    ->setCellValue('D1', 'Puntos');

$i=2;
while($surveyInfo=$surveyTaker -> fetch_assoc()) {
    $surveyInfo= array_map("decode", $surveyInfo);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $surveyInfo['municipio'])
        ->setCellValue('B'.$i, $surveyInfo['ruta'])
        ->setCellValue('C'.$i, $surveyInfo['nombre'])
        ->setCellValue('D'.$i, $surveyInfo['puntos']);
    $i++;

}
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);




// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$random = strtotime("now") ;
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ReporteRuta_'.$random.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
