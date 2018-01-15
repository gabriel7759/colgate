<?php
function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once ( 'Classes/PHPExcel.php');
include_once ("session.php");

$store=$_GET['info'];

$condition = "";
if($store!=null && $store >0){
    $condition = "WHERE tienda_id=$store";
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$questions= $mysqli->query("SELECT * FROM Usuario $condition");



// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");
//$objPHPExcel->setPreCalculateFormulas(false);


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'Nombre')
    ->setCellValue('C1', 'CEDIS')
    ->setCellValue('D1', 'Ruta')
    ->setCellValue('E1', 'RFC')
    ->setCellValue('F1', 'Curp')
    ->setCellValue('G1', 'Email')
    ->setCellValue('H1', 'Username')
    ->setCellValue('I1', 'Teléfono')
    ->setCellValue('J1', 'Estado')
    ->setCellValue('K1', 'Ciudad/Municipio')
    ->setCellValue('L1', 'Colonia')
    ->setCellValue('M1', 'Calle')
    ->setCellValue('N1', 'Número')
    ->setCellValue('O1', 'C.P.')
    ->setCellValue('P1', 'Puntos actuales')
    ->setCellValue('Q1', 'Puntos distribución')
    ->setCellValue('R1', 'Puntos sellout')
    ->setCellValue('S1', 'Puntos posibles');

$i=2;
while($userInfoResult=$questions -> fetch_assoc()) {
    $userInfo= $mysqli->query("SELECT id, email, username FROM Login WHERE id='$userInfoResult[login_id]'");
    $fila = $userInfo->fetch_assoc();

    $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userInfoResult[tienda_id]'";
    $storeInfo = $mysqli->query($instruction);
    $storeResult = $storeInfo->fetch_assoc();
//    $storeResult = array_map("decode", $storeResult);

    $userInfoResult= array_map("decode", $userInfoResult);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $fila['id'])
        ->setCellValue('B'.$i, $userInfoResult['nombre'])
        ->setCellValue('C'.$i, $storeResult['nombre'])
        ->setCellValue('D'.$i, $userInfoResult['ruta'])
        ->setCellValue('E'.$i, $userInfoResult['rfc'])
        ->setCellValue('F'.$i, $userInfoResult['curp'])
        ->setCellValue('G'.$i, $fila['email'])
        ->setCellValue('H'.$i, $fila['username'])
        ->setCellValue('I'.$i, $userInfoResult['telefono'])
        ->setCellValue('J'.$i, $userInfoResult['estado'])
        ->setCellValue('K'.$i, $userInfoResult['municipio'])
        ->setCellValue('L'.$i, $userInfoResult['colonia'])
        ->setCellValue('M'.$i, $userInfoResult['calle'])
        ->setCellValue('N'.$i, $userInfoResult['numero'])
        ->setCellValue('O'.$i, $userInfoResult['cp'])
        ->setCellValue('P'.$i, $userInfoResult['puntos']);
    $i++;

}


$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Usuarios');
$objPHPExcel->setActiveSheetIndex(0);

$random = strtotime("now") ;
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Usuarios_'.$random.'.xls"');
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
