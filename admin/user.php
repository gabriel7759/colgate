<?php
include_once ("session.php");

function getUserInfo ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Login WHERE id='$id'";
    $loginInfo = $mysqli->query($instruction);
    $loginInfoResult = $loginInfo->fetch_assoc();
    $loginInfoResult = array_map("decode", $loginInfoResult);

    $instruction = "SELECT * FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $userInfoResult = array_map("decode", $userInfoResult);


    $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userInfoResult[tienda_id]'";
    $storeInfo = $mysqli->query($instruction);
    $storeResult = $storeInfo->fetch_assoc();
    $storeResult = array_map("decode", $storeResult);

    $store=[
        "name"=> $storeResult[nombre],
        "image"=> $storeResult[imagen],
        "id" => $storeResult[id]
    ];
    $user=[
        "name"=> $userInfoResult[nombre],
        "street"=> $userInfoResult[calle],
        "suburb"=> $userInfoResult[colonia],
        "number"=> $userInfoResult[numero],
        "city"=> $userInfoResult[municipio],
        "state"=> $userInfoResult[estado],
        "cp"=> $userInfoResult[cp],
        "phone"=> $userInfoResult[telefono],
        "email"=> $loginInfoResult[email],
        "username"=> $loginInfoResult[username],
        "type"=> $loginInfoResult[tipo],
        "firstTime"=> $loginInfoResult[firstTime],
        "route"=> $userInfoResult[ruta],
        "points"=> $userInfoResult[puntos],
        "rfc"=> $userInfoResult[rfc],
        "curp"=> $userInfoResult[curp],
        "store"=> $store,
        "image" => $userInfoResult[imagen],
        "id" => $userInfoResult[login_id]
    ];
    return json_encode($user);
}
function getUserPoints ($id){
    $points = getPoints($id);
    $firstTime =getFirstTime($id);
    $level = getLevel($id);
    $user=[
        "points"=> $points,
        "level"=> $level,
        "firstTime"=> $firstTime
    ];
    return json_encode($user);
}
function getPoints($id){
    global $mysqli;
    $instruction = "SELECT puntos FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $points = $userInfoResult[puntos];
    return $points;
}
function getLevel($id){
    global $mysqli;
    $instruction = "SELECT nivel FROM Login WHERE id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $level = $userInfoResult[nivel];
    return $level;
}
function getFirstTime($id){
    global $mysqli;
    $instruction = "SELECT firstTime FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $points = $userInfoResult[firstTime];
    return $points;
}
function updateUser($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $password=htmlentities($info->password, ENT_QUOTES);
    $rfc=htmlentities($info->rfc, ENT_QUOTES);
    $curp=htmlentities($info->curp, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=htmlentities($info->number, ENT_QUOTES);
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);
    $id=htmlentities($info->id, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "SELECT password FROM Login WHERE id='$id'";
    $loginInfo = $mysqli->query($instruction);
    $loginInfoResult = $loginInfo->fetch_assoc();

    if($password == null || $password==""){
        $password=$loginInfoResult[password];
    }
    else{
        $password = sha1($password);
    }
    $instruction = "UPDATE Login SET password='$password'WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {
        $instruction = "UPDATE Usuario SET nombre='$name', rfc='$rfc', curp='$curp', telefono='$phone', calle='$street', colonia='$suburb', numero='$number', municipio='$city', estado='$state', cp='$cp', firstTime=0 WHERE login_id='$id'";

        if ($mysqli->query($instruction) === TRUE) {
            $toReturn = [
                "status" => "win",
            ];
        }
    }
    return json_encode($toReturn);
}

function getRecommendations($id, $level){
    global $mysqli;
    $instruction = "SELECT * FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $points = $userInfoResult[puntos];

    $toReturn = [
        "status" => "fail",
        "categories" => null
    ];
    $complement = "";
    if($level == 2){
        $complement = "AND puntos < 118751 ";
    }
    if($level == 3){
        $complement = "AND puntos < 83126 ";
    }
    if($level == 4){
        $complement = "AND puntos < 59376 ";
    }
    if($level == 5){
        $complement = "AND puntos < 47501 ";
    }
    if($level == 5){
        $complement = "AND puntos < 23751 ";
    }
    $categories= [];
    $instruction = "SELECT id, nombre, imagen FROM Categoria";
    $categoryQ = $mysqli->query($instruction);
    while($categoryResult = $categoryQ->fetch_assoc()){
        $categoryResult = array_map("decode", $categoryResult);
        $products= [];

        $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE categoria_id='$categoryResult[id]' AND cantidad>1 AND puntos<=$points  $complement  LIMIT 2";
        $productQ = $mysqli->query($instruction);
        $num_rows = $productQ->num_rows;

        while($productResult = $productQ->fetch_assoc()){
            $productToAdd=[
                "name"=> $productResult[nombre],
                "description"=> $productResult[descripcion],
                "points"=> $productResult[puntos],
                "image"=> $productResult[imagenes],
                "id" => $productResult[id]
            ];
            $productToAdd = array_map("decode", $productToAdd);
            array_push($products, $productToAdd);
        }
        if($num_rows < 2){
            $newLimit = 2 - $num_rows;
            $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE categoria_id='$categoryResult[id]' AND cantidad>1 AND puntos>$points LIMIT $newLimit  ";
            $productQ = $mysqli->query($instruction);

            while($productResult = $productQ->fetch_assoc()){
                $productToAdd=[
                    "name"=> $productResult[nombre],
                    "description"=> $productResult[descripcion],
                    "points"=> $productResult[puntos],
                    "image"=> $productResult[imagenes],
                    "id" => $productResult[id]
                ];
                $productToAdd = array_map("decode", $productToAdd);
                array_push($products, $productToAdd);
            }
        }


        $categoryToAdd=[
            "name"=> $categoryResult[nombre],
            "image"=> $categoryResult[imagen],
            "id" => $categoryResult[id],
            "products" => $products
        ];
        array_push($categories, $categoryToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["categories"] = $categories;
    return json_encode($toReturn);
}
function getCategories(){
    global $mysqli;
    $toReturn = [
        "status" => "fail",
        "categories" => null
    ];
    $categories= [];
    $instruction = "SELECT id, nombre, imagen FROM Categoria";
    $categoryQ = $mysqli->query($instruction);
    while($categoryResult = $categoryQ->fetch_assoc()){
        $categoryResult = array_map("decode", $categoryResult);
        $categoryToAdd=[
            "name"=> $categoryResult[nombre],
            "image"=> $categoryResult[imagen],
            "id" => $categoryResult[id],
        ];
        array_push($categories, $categoryToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["categories"] = $categories;
    return json_encode($toReturn);
}
function getProducts($id, $level){
    global $mysqli;
    $toReturn = [
        "status" => "win",
        "products" => null,
        "category" => null
    ];
    $complement = "";
    if($level == 2){
        $complement = "AND puntos < 118751 ";
    }
    if($level == 3){
        $complement = "AND puntos < 83126 ";
    }
    if($level == 4){
        $complement = "AND puntos < 59376 ";
    }
    if($level == 5){
        $complement = "AND puntos < 47501 ";
    }
    if($level == 5){
        $complement = "AND puntos < 23751 ";
    }
    $id = $id/1;
    if($id != 0)
    {
        $instruction = "SELECT id, nombre, imagen FROM Categoria WHERE id='$id'";
        $categoryQ = $mysqli->query($instruction);
        $categoryResult = $categoryQ->fetch_assoc();
        $categoryResult = array_map("decode", $categoryResult);
        $categoryToAdd=[
            "name"=> $categoryResult[nombre],
            "image" => $categoryResult[imagen],
        ];
        $toReturn["category"] = $categoryToAdd;
        $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE categoria_id='$id' AND cantidad>1 $complement ORDER BY nombre";

    }
    else{
        $categoryToAdd=[
            "name"=> "Todos los productos",
            "image" => "assets/img/allCategories.png",
        ];
        $toReturn["category"] = $categoryToAdd;
        $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE cantidad>1 $complement ORDER BY nombre";

    }



    $productQ = $mysqli->query($instruction);
    $products= [];
    while($productResult = $productQ->fetch_assoc()){
        $productResult = array_map("decode", $productResult);

        $result = mb_substr($productResult[descripcion], 0, 100);
        $productToAdd=[
            "name"=> $productResult[nombre],
            "description"=> $productResult[descripcion],
            "miniDescription"=>$result ,
            "points"=> $productResult[puntos],
            "image"=> $productResult[imagenes],
            "id" => $productResult[id]
        ];
        $productToAdd = array_map("decode", $productToAdd);
        array_push($products, $productToAdd);
    }
    $toReturn["products"] = $products;
    return json_encode($toReturn);
}
function tradeProduct($id, $user){
    global $mysqli;
    $toReturn = [
        "status" => "win",
        "points" => null
    ];
    $inStock = false;
    $enoughPoints = false;
    $userPoints = getPoints($user);
    $instruction = "SELECT cantidad, puntos FROM Producto WHERE id='$id'";
    $productQ = $mysqli->query($instruction);
    $toReturn["points"] = $userPoints;


    $productResult = $productQ->fetch_assoc();
    $stock = $productResult[cantidad];
    $productPoints = $productResult[puntos];

    if($stock < 1){
        $toReturn["status"] = "stock";
    }
    else{
        $inStock = true;
    }

    if($userPoints < $productPoints){
        $toReturn["status"] = "points";
    }
    else{
        $enoughPoints = true;
    }

    if($enoughPoints && $inStock){
        $newBalance = $userPoints - $productPoints;
        $newStock =  $productResult[cantidad]-1;
        $instruction = "UPDATE Usuario SET puntos='$newBalance' WHERE login_id='$user'";
        if ($mysqli->query($instruction) === TRUE) {
            $instruction = "SELECT * FROM Usuario WHERE login_id='$user'";
            $userInfo = $mysqli->query($instruction);
            $userInfoResult = $userInfo->fetch_assoc();

            $date= date('Y-m-d', strtotime( " +1 month"));
            $deliveryDate = date( "Y-m-d", strtotime( $date." +1 month"));

            $instruction = "INSERT INTO CanjeaProducto (producto_id, usuario_id, cantidad, fecha, cp, numero, calle, telefono, colonia, municipio, estado, fechaEntrega) VALUES  ".
                "('$id', '$user', 1, '$date', '$userInfoResult[cp]', '$userInfoResult[numero]', '$userInfoResult[calle]', '$userInfoResult[telefono]', '$userInfoResult[colonia]', '$userInfoResult[municipio]', '$userInfoResult[estado]', '$deliveryDate')";
            if ($mysqli->query($instruction) === TRUE) {
                $lastId = $mysqli->insert_id;
                $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha, canjeaProducto_id) VALUES  ".
                    "(1, '$productPoints', '$user', '$date', '$lastId')";
                if ($mysqli->query($instruction) === TRUE) {
                    $instruction = "UPDATE Producto SET cantidad='$newStock' WHERE id='$id'";
                    if ($mysqli->query($instruction) === TRUE) {
                        $toReturn["status"] = "win";
                        $toReturn["points"] = $newBalance;
                    }
                }
            }


        }

    }
    return json_encode($toReturn);


}
function tradeRecharge($info, $user){
    global $mysqli;
    $toReturn = [
        "status" => "win",
        "points" => null
    ];
    $details = [
        "type" =>1,
        "company" => $info->company,
        "phone" => $info->number,
        "amount" => $info->amount,
        "points" => $info->points
    ];
    $productPoints = $info ->points/1;
    $infoS = json_encode($details);
    $userPoints = getPoints($user);
        $newBalance = $userPoints - $productPoints;
        $instruction = "UPDATE Usuario SET puntos='$newBalance' WHERE login_id='$user'";
        if ($mysqli->query($instruction) === TRUE) {
            $date= date('Y-m-d');
            $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha, detalles) VALUES  ".
                "(1, '$productPoints', '$user', '$date', '$infoS')";
            if ($mysqli->query($instruction) === TRUE) {

                $deliveryDate = date( "Y-m-d", strtotime( $date." +1 month"));

                $instruction = "INSERT INTO CanjeaProducto (usuario_id, fecha, fechaEntrega, detalles) VALUES  ".
                    "('$user', '$date', '$deliveryDate', '$infoS')";
                if ($mysqli->query($instruction) === TRUE) {
                    $toReturn["status"] = "win";
                    $toReturn["points"] = $newBalance;
                }
            }
        }
    return json_encode($toReturn);
}
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
function getTransactionRecord($id){
    global $mysqli;
    $date= date('Y-m-d');
    $aux = explode("-", $date);
    $month = $aux[1]/1;
    $year = $aux[0]/1;
    $monthsToSearch = 6;
    $i = 1;
    $toMonth=$month+1;
    $toYear = $year;
    $fromYear = $year;
    $monthsReport= [];
    $needToGoBack= false;


    while ($i<=$monthsToSearch){
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

        if($fromMonth == 12){
            $toMonth =1;
            $toYear ++;
            $needToGoBack= true;
        }


        $instruction = " SELECT puntos, tipo FROM Transaccion WHERE usuario_id='$id' AND fecha BETWEEN '$fromYear-$fromMonth-01' AND '$toYear-$toMonth-01'";

        echo $instruction;
        $userInfo = $mysqli->query($instruction);
        while($userInfoResult = $userInfo->fetch_assoc()){
            if($userInfoResult[tipo] == 5){
                $possible+=$userInfoResult[puntos];
            }
            if($userInfoResult[tipo] == 4){
                $sellOut+=$userInfoResult[puntos];
            }
            if($userInfoResult[tipo] == 3){
                $distribution+=$userInfoResult[puntos];
            }
            if($userInfoResult[tipo] == 2){
                $pointsAdded+=$userInfoResult[puntos];
            }
            else if ($userInfoResult[tipo] == 1){
                $pointsRemoved+=$userInfoResult[puntos];
            }
        }
        $monthName = getMonthName($fromMonth);
        $monthReport=[
            "name"=> $monthName,
            "added"=> $distribution + $sellOut,
            "removed"=> $pointsRemoved,
            "sellOut"=> $sellOut,
            "distribution"=> $distribution,
            "possible"=> $possible,
        ];
        array_push($monthsReport, $monthReport);
        $toMonth--;
        if($toMonth<=0){
            $toYear--;
            $toMonth=12;
        }
        $i++;
    }

//    $monthsReport=  array_reverse($monthsReport);
    return json_encode($monthsReport);

}

function getTradedProducts($id){
    global $mysqli;
    $products =[];
    $instruction = "SELECT status, producto_id, fecha, fechaEntrega, detalles FROM CanjeaProducto WHERE usuario_id='$id'";
    $tradeProductQ = $mysqli->query($instruction);
    $statusDescription = "Solicitado";
    $statusPercentage = 10;
    $productImage = "";
    $productId = "";
    $productName = "";
    $productDescription = "";
    $productPoints = "";
    $statusColor = "md-primary";
    while($tradeProductResult = $tradeProductQ->fetch_assoc()){
        if($tradeProductResult[producto_id] != 0){
            $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE id='$tradeProductResult[producto_id]'";
            $productQ = $mysqli->query($instruction);
            $productResult = $productQ->fetch_assoc();
            $productName = $productResult[nombre];
            $productDescription = $productResult[descripcion];
            $productPoints= $productResult[puntos];
            $productImage= $productResult[imagenes];
            $productId= $productResult[id];
        }
        else{
            $productQ = json_decode($tradeProductResult[detalles]);
            if($productQ ->type == 1){
                $productImage = "php/Products/recargas.png";
                $productId = 0;
                $productName = "Recarga";
                $productName = $productName . " - " . $productQ ->company . " - " . $productQ ->phone . " - " . $productQ ->amount ;
                $productPoints = $productQ->points;
            }
        }
        $status = $tradeProductResult[status];
        if( $tradeProductResult[fechaEntrega] == null)
            $date = date( "Y-m-d", strtotime( $tradeProductResult[fecha]." +1 month"));
        else{
            $date =$tradeProductResult[fechaEntrega];
        }
        if($status == 1){
            $statusDescription = "Solicitado";
            $statusPercentage = 10;
            $statusColor = "md-primary";
        }
        if($status == 2){
            $statusDescription = "En camino";
            $statusPercentage = 75;
            $statusColor = "md-primary";

        }
        if($status == 3){
            $date= "Entregado";
            $statusDescription = "Entregado";
            $statusPercentage = 100;
            $statusColor = "md-hue-3";
        }
        if($status == 4){
            $date= "Cancelado";
            $statusDescription = "Cancelado";
            $statusPercentage = 100;
            $statusColor = "blackColor";
        }
            $productToAdd=[
                "name"=> $productName,
                "deliveryDate" => $date,
                "description"=> $productDescription,
                "points"=>$productPoints,
                "image"=> $productImage,
                "id" =>$productId,
                "status" => $tradeProductResult[status],
                "statusPercentage" => $statusPercentage,
                "statusDescription" => $statusDescription,
                "statusColor" => $statusColor,
            ];
            $productToAdd = array_map("decode", $productToAdd);
            array_push($products, $productToAdd);
    }


    return json_encode($products);
}
function getInfo (){
    global $mysqli;
    $instruction = "SELECT descripcion FROM General WHERE nombre='mensaje'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $message = $userInfoResult[descripcion];

    $instruction = "SELECT descripcion FROM General WHERE nombre='fechaCompra'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $tradeDate = $userInfoResult[descripcion];

    $instruction = "SELECT descripcion FROM General WHERE nombre='comprarAntes'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $canTrade = $userInfoResult[descripcion];

    $instruction = "SELECT descripcion FROM General WHERE nombre='hastaFecha'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $toDate = $userInfoResult[descripcion];
    $toReturn = [
        "status" => "win",
        "message" => $message,
        "tradeDate" => $tradeDate,
        "toDate" => $toDate,
        "canTrade" => $canTrade,
    ];
    return json_encode($toReturn);

}
function login($email, $password){
    global $mysqli;
    $email=htmlentities($email, ENT_QUOTES);
    $password1 = sha1($password);
    $result = $mysqli->query("SELECT id, email, password FROM Login WHERE (email='$email' OR username='$email') AND  password='$password1'");
    $loginInfo = $result->fetch_assoc();
    if($loginInfo[id] != null && $loginInfo[id]>0){
        $toReturn =[
            "status" => "win"
        ];
    }
    else{
        $toReturn =[
            "status" => "fail"
        ];
    }
    return json_encode($toReturn);
}
function forgotPassword ($info){
    global $mysqli;
    $info=htmlentities($info, ENT_QUOTES);
    $result = $mysqli->query("SELECT email, id FROM Login WHERE email='$info' OR username='$info'");
    $loginInfo = $result->fetch_assoc();
    $ready = false;

    if($loginInfo[id] != null && $loginInfo[id]>0){
        $result = $mysqli->query("SELECT nombre FROM Usuario WHERE login_id=$loginInfo[id] ");
        $userInfo = $result->fetch_assoc();
        $ready = true;
    }
    else{
        $result = $mysqli->query("SELECT login_id, nombre, id FROM Usuario WHERE curp='$info' OR rfc='$info'");
        $userInfo = $result->fetch_assoc();
        if($userInfo[id] != null && $userInfo[id]>0){
            $result = $mysqli->query("SELECT email FROM Login WHERE id='$userInfo[login_id]'");
            $loginInfo = $result->fetch_assoc();
            $ready = true;

        }
    }
    if($ready){
        echo "Debi de haber enviado un correo a ".$loginInfo[email];
        sendPassword($loginInfo[email],$userInfo[nombre] );
    }
    echo "No mande nada";


}
function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}
function changePassword ($info){
    global $mysqli;
    $password = sha1(htmlentities($info->password, 3));
    $hexEmail = htmlentities($info->user, 3);
    $email = hexToStr($hexEmail);

    if($email == null || $email == ""){

    }
    else{
        $instruction = "UPDATE Login SET password='$password'WHERE email='$email'";
        echo $instruction;
        if ($mysqli->query($instruction) === TRUE) {
            echo "Cambie password";
        }
    }
    return 1;

}
function sendMessage ($id, $message){
    global  $mysqli;
    $instruction = "SELECT email FROM Login WHERE id='$id'";
    $loginInfo = $mysqli->query($instruction);
    $loginInfoResult = $loginInfo->fetch_assoc();


    $instruction = "SELECT rfc, nombre FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();

    sendMail('tiempoairecolgate@mkdo.mx', $message, $loginInfoResult[email], $userInfoResult[rfc], $userInfoResult[nombre] );
    return "win";
}
function sendMail($to, $message, $email, $rfc, $name){


    $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
 xmlns:v="urn:schemas-microsoft-com:vml"
 xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <!--[if gte mso 9]><xml>
   <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
   </o:OfficeDocumentSettings>
  </xml><![endif]-->
  <!-- fix outlook zooming on 120 DPI windows devices -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
  <meta name="format-detection" content="date=no"> <!-- disable auto date linking in iOS 7-9 -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS 7-9 -->
  <title>Club Colgate!</title>
  
  <style type="text/css">
body {
  margin: 0;
  padding: 0;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
}

table {
  border-spacing: 0;
}

table td {
  border-collapse: collapse;
}

.ExternalClass {
  width: 100%;
}

.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
  line-height: 100%;
}

.ReadMsgBody {
  width: 100%;
  background-color: #ebebeb;
}

table {
  mso-table-lspace: 0pt;
  mso-table-rspace: 0pt;
}

img {
  -ms-interpolation-mode: bicubic;
}

.yshortcuts a {
  border-bottom: none !important;
}

@media screen and (max-width: 599px) {
  .force-row,
  .container {
    width: 100% !important;
    max-width: 100% !important;
  }
}
@media screen and (max-width: 400px) {
  .container-padding {
    padding-left: 12px !important;
    padding-right: 12px !important;
  }
}
.ios-footer a {
  color: #aaaaaa !important;
  text-decoration: underline;
}
a[href^="x-apple-data-detectors:"],
a[x-apple-data-detectors] {
  color: inherit !important;
  text-decoration: none !important;
  font-size: inherit !important;
  font-family: inherit !important;
  font-weight: inherit !important;
  line-height: inherit !important;
}
</style>
</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- 100% background wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

      <br>

      <!-- 600px container (white background) -->
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
        <tr>
          <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#fe0000;padding-left:24px;padding-right:24px">
            Club Colgate

          </td>
        </tr>
        <tr>
          <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
            <br>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Mensaje</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
    Mensaje de '.$name. ' ('.$email.' '.$rfc.').
            <br>

            <br>
            '.$message.'

    
</div>

          </td>
        </tr>
        <tr>
          <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
          


          </td>
        </tr>
      </table>
<!--/600px container -->


    </td>
  </tr>
</table>
<!--/100% background wrapper-->

</body>
</html>
';
    mail($to,"Club Colgate",
        $html, $headers);
}
function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
function sendPassword($email, $name){

    $hexEmail = strToHex($email);

    $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
 xmlns:v="urn:schemas-microsoft-com:vml"
 xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <!--[if gte mso 9]><xml>
   <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
   </o:OfficeDocumentSettings>
  </xml><![endif]-->
  <!-- fix outlook zooming on 120 DPI windows devices -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
  <meta name="format-detection" content="date=no"> <!-- disable auto date linking in iOS 7-9 -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS 7-9 -->
  <title>Club Colgate!</title>
  
  <style type="text/css">
body {
  margin: 0;
  padding: 0;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
}

table {
  border-spacing: 0;
}

table td {
  border-collapse: collapse;
}

.ExternalClass {
  width: 100%;
}

.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
  line-height: 100%;
}

.ReadMsgBody {
  width: 100%;
  background-color: #ebebeb;
}

table {
  mso-table-lspace: 0pt;
  mso-table-rspace: 0pt;
}

img {
  -ms-interpolation-mode: bicubic;
}

.yshortcuts a {
  border-bottom: none !important;
}

@media screen and (max-width: 599px) {
  .force-row,
  .container {
    width: 100% !important;
    max-width: 100% !important;
  }
}
@media screen and (max-width: 400px) {
  .container-padding {
    padding-left: 12px !important;
    padding-right: 12px !important;
  }
}
.ios-footer a {
  color: #aaaaaa !important;
  text-decoration: underline;
}
a[href^="x-apple-data-detectors:"],
a[x-apple-data-detectors] {
  color: inherit !important;
  text-decoration: none !important;
  font-size: inherit !important;
  font-family: inherit !important;
  font-weight: inherit !important;
  line-height: inherit !important;
}
</style>
</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- 100% background wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

      <br>

      <!-- 600px container (white background) -->
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
        <tr>
          <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#fe0000;padding-left:24px;padding-right:24px">
            Club Colgate

          </td>
        </tr>
        <tr>
          <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
            <br>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">¡Hola '.$name.' !</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
 
  <br><br>
  Para poder cambiar tu contraseña, ingresa a esta  <a href=" http://mkdo.mx/ganamasconcolgate/user/recuperar.html?foo='.$hexEmail.'" target="_blank"> página</a> e ingresa tu nueva contraseña.
  <br><br>

  De no ser así, has caso omiso a este mensaje, tu cuenta está a salvo.

    
</div>

          </td>
        </tr>
        <tr>
          <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
          
<br><br>
            Si tienes problemas al abrir el link, copia la siguiente liga y pegala en el navegador.
            <br>
            http://mkdo.mx/ganamasconcolgate/user/recuperar.html?foo='.$hexEmail.'
            <br>

          </td>
        </tr>
      </table>
<!--/600px container -->


    </td>
  </tr>
</table>
<!--/100% background wrapper-->

</body>
</html>
';
    mail($email,"Club Colgate",
        $html, $headers);
}

?>