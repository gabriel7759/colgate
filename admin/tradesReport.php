<?php
function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}

error_reporting(E_ALL);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once ( 'Classes/PHPExcel.php');
include_once ("session.php");
$status=$_GET['info'];



// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
if($status == null || $status == 0 ){
$instruction = "SELECT id, status, producto_id, usuario_id, fecha, fechaEntrega, detalles FROM CanjeaProducto ";

}else{
    $instruction = "SELECT id, status, producto_id, usuario_id, detalles, fecha, fechaEntrega FROM CanjeaProducto WHERE status='$status'";

}
$tradeProductQ = $mysqli->query($instruction);



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
    ->setCellValue('A1', 'Nombre')
    ->setCellValue('B1', 'CEDIS')
    ->setCellValue('C1', 'Ruta')
    ->setCellValue('D1', 'RFC')
    ->setCellValue('E1', 'Curp')
    ->setCellValue('F1', 'Email')
    ->setCellValue('G1', 'Username')
    ->setCellValue('H1', 'Teléfono')
    ->setCellValue('I1', 'Estado')
    ->setCellValue('J1', 'Ciudad/Municipio')
    ->setCellValue('K1', 'Colonia')
    ->setCellValue('L1', 'Calle')
    ->setCellValue('M1', 'Número')
    ->setCellValue('N1', 'C.P.')
    ->setCellValue('I1', 'Producto')
    ->setCellValue('P1', 'Status')
    ->setCellValue('Q1', 'Fecha de pedido')
    ->setCellValue('R1', 'Fecha estimada');

$i=2;
while($tradeProductResult=$tradeProductQ -> fetch_assoc()) {
    $instruction = "SELECT * FROM Usuario WHERE login_id='$tradeProductResult[usuario_id]'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();

    $instruction = "SELECT email, username FROM Login WHERE id='$tradeProductResult[usuario_id]'";
    $loginInfo = $mysqli->query($instruction);
    $loginInfoResult = $loginInfo->fetch_assoc();

    $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userInfoResult[tienda_id]'";
    $storeInfo = $mysqli->query($instruction);
    $storeResult = $storeInfo->fetch_assoc();
    $productName = "";
    if($tradeProductResult['producto_id'] != 0){
        $instruction = "SELECT id, nombre FROM Producto WHERE id='$tradeProductResult[producto_id]'";
        $productQ = $mysqli->query($instruction);
        $productResult = $productQ->fetch_assoc();
        $productResult= array_map("decode", $productResult);
        $littleName = explode(" ", $userInfoResult['nombre']) ;
        $productName = $productResult['nombre'];
    }
    else{
        $productQ = json_decode($tradeProductResult['detalles']);
        if($productQ ->type == 1){
            $productName = "Recarga";
            $productName = $productName . " - " . $productQ ->company . " - " . $productQ ->phone . " - " . $productQ ->amount ;
        }
    }
    $littleName = explode(" ", $userInfoResult['nombre']) ;
    $statusDescription = "";
    if($tradeProductResult['status']==1)
        $statusDescription = "Solicitado";
    else if($tradeProductResult['status']==2)
        $statusDescription = "En camino";
    else if($tradeProductResult['status']==3)
        $statusDescription = "Entregado";
    else
        $statusDescription = "Cancelado";

    if(count($userInfoResult)>0){
        $userInfoResult= array_map("decode", $userInfoResult);

    }
//    $userInfoResult= array_map("decode", $userInfoResult);

    if($tradeProductResult['fechaEntrega'] == "0000-00-00" || $tradeProductResult['fechaEntrega'] == "" || $tradeProductResult['fechaEntrega'] == null){
        $date= date('Y-m-d');
//        $deliveryDate = date( "Y-m-d", strtotime( ."+1 month"));
        $deliveryDate = date('Y-m-d', strtotime("+1 months", strtotime($tradeProductResult['fecha'])));
//        $deliveryDate = $tradeProductResult['fechaEntrega'];
    }
    else{
        $deliveryDate = date( "Y-m-d", strtotime( $tradeProductResult['fechaEntrega']));
    }
    $tradeDate = date( "Y-m-d", strtotime($tradeProductResult['fecha']));

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $userInfoResult['nombre'])
        ->setCellValue('B'.$i, $storeResult['nombre'])
        ->setCellValue('C'.$i, $userInfoResult['ruta'])
        ->setCellValue('D'.$i, $userInfoResult['rfc'])
        ->setCellValue('E'.$i, $userInfoResult['curp'])
        ->setCellValue('F'.$i, $loginInfoResult['email'])
        ->setCellValue('G'.$i, $loginInfoResult['username'])
        ->setCellValue('H'.$i, $userInfoResult['telefono'])
        ->setCellValue('I'.$i, $userInfoResult['estado'])
        ->setCellValue('J'.$i, $userInfoResult['municipio'])
        ->setCellValue('K'.$i, $userInfoResult['colonia'])
        ->setCellValue('L'.$i, $userInfoResult['calle'])
        ->setCellValue('M'.$i, $userInfoResult['numero'])
        ->setCellValue('N'.$i, $userInfoResult['cp'])
        ->setCellValue('O'.$i, $productName)
        ->setCellValue('P'.$i, $statusDescription)
        ->setCellValue('Q'.$i, $tradeDate)
        ->setCellValue('R'.$i, $deliveryDate);
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
$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);




// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$random = strtotime("now") ;
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ReporteCanje_'.$random.'.xls"');
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
