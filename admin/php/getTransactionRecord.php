<?php
global $mysqli;





function getMonthName($month){
    $name = "";
    if($month == 2){
        $name = "Enero";
    }
    if($month == 3){
        $name = "Febrero";
    }
    if($month == 4){
        $name = "Marzo";
    }
    if($month == 5){
        $name = "Abril";
    }
    if($month == 6){
        $name = "Mayo";
    }
    if($month == 7){
        $name = "Junio";
    }
    if($month == 8){
        $name = "Julio";
    }
    if($month == 9){
        $name = "Agosto";
    }
    if($month == 10){
        $name = "Septiembre";
    }
    if($month == 11){
        $name = "Octubre";
    }
    if($month == 12){
        $name = "Noviembre";
    }
    if($month == 1){
        $name = "Diciembre";
    }
    return $name;
}
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
    $condition = "WHERE tienda_id<$store";
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
    ->setCellValue('A2', 'ID')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'CEDIS')
    ->setCellValue('D2', 'Ruta')
    ->setCellValue('E2', 'RFC')
    ->setCellValue('F2', 'Curp')
    ->setCellValue('G2', 'Email')
    ->setCellValue('H2', 'Username')
    ->setCellValue('I2', 'Teléfono')
    ->setCellValue('J2', 'Estado')
    ->setCellValue('K2', 'Ciudad/Municipio')
    ->setCellValue('L2', 'Colonia')
    ->setCellValue('M2', 'Calle')
    ->setCellValue('N2', 'Número')
    ->setCellValue('O2', 'C.P.')
    ->setCellValue('P2', 'Puntos actuales')
    ->setCellValue('Q2', 'Puntos distribución')
    ->setCellValue('R2', 'Puntos sellout')
    ->setCellValue('S2', 'Puntos posibles');

$i=3;
$x = "T";
$needToDraw = true;
while($userInfoResult=$questions -> fetch_assoc()) {
    $userInfo= $mysqli->query("SELECT id, email, username FROM Login WHERE id='$userInfoResult[login_id]'");
    $fila = $userInfo->fetch_assoc();

    $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userInfoResult[tienda_id]'";
    $storeInfo = $mysqli->query($instruction);
    $storeResult = $storeInfo->fetch_assoc();
    $date= date('Y-m-d');
    $aux = explode("-", $date);
    $month = $aux[1]/1;
    $year = $aux[0]/1;
    $monthsToSearch = 4;
    $k = 1;
    $x = "T";

    $toMonth=$month+1;
    $toYear = $year;
    $fromYear = $year;
    $monthsReport= [];

    $userInfoResult= array_map("decode", $userInfoResult);
    $possible = 0;

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


//    $storeResult = array_map("decode", $storeResult);
    while ($k<=$monthsToSearch){
        $pointsAdded = 0;
        $sellOut = 0;
        $distribution = 0;
        $possible = 0;
        $pointsRemoved = 0;
        $fromMonth = $toMonth-1;

        if($fromMonth<=0){
            $fromYear--;
            $fromMonth=12;
        }
        $instruction = " SELECT puntos, tipo FROM Transaccion WHERE usuario_id='$userInfoResult[login_id]' AND puntos>0 AND fecha   BETWEEN '$fromYear-$fromMonth-01' AND '$toYear-$toMonth-01'";
        $userInfo = $mysqli->query($instruction);
        while($pointsInfo = $userInfo->fetch_assoc()){
            if($pointsInfo["tipo"] == 5){
                $possible+=$pointsInfo["puntos"];
            }
            if($pointsInfo["tipo"] == 4){
                $sellOut+=$pointsInfo["puntos"];
            }
            if($pointsInfo["tipo"] == 3){
                $distribution+=$pointsInfo["puntos"];
            }
            if($pointsInfo["tipo"] == 2){
                $pointsAdded+=$pointsInfo["puntos"];
            }
            else if ($pointsInfo["tipo"] == 1){
                $pointsRemoved+=$pointsInfo["puntos"];
            }
        }
        if($needToDraw){
            $monthName = getMonthName($fromMonth);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($x."1", $monthName);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($x."2", "Puntos distribución");
            $objPHPExcel->getActiveSheet()->getColumnDimension($x)->setAutoSize(true);

        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($x.$i, $distribution);

        $x++;


        if($needToDraw){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($x."2", "Puntos sellout");
            $objPHPExcel->getActiveSheet()->getColumnDimension($x)->setAutoSize(true);

        }


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($x.$i, $sellOut);

        $x++;

        if($needToDraw){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($x."2", "Puntos posibles");
            $objPHPExcel->getActiveSheet()->getColumnDimension($x)->setAutoSize(true);

        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($x.$i, $possible);


        $x++;

        if($needToDraw){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($x."2", "Puntos redimidos");
            $objPHPExcel->getActiveSheet()->getColumnDimension($x)->setAutoSize(true);

        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($x.$i, $pointsRemoved);

        $x++;


        $toMonth--;
        if($toMonth<=0){
            $toYear--;
            $toMonth=12;
        }
        $k++;
    }
    $needToDraw =false;

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

?>