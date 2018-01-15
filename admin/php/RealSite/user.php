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
    $user=[
        "points"=> $points,
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

function getRecommendations($id){
    global $mysqli;
    $instruction = "SELECT * FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $points = $userInfoResult[puntos];

    $toReturn = [
        "status" => "fail",
        "categories" => null
    ];
    $categories= [];
    $instruction = "SELECT id, nombre, imagen FROM Categoria";
    $categoryQ = $mysqli->query($instruction);
    while($categoryResult = $categoryQ->fetch_assoc()){
        $categoryResult = array_map("decode", $categoryResult);
        $products= [];

        $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE categoria_id='$categoryResult[id]' AND cantidad>1 AND puntos<=$points   LIMIT 2";
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
function getProducts($id){
    global $mysqli;
    $toReturn = [
        "status" => "win",
        "products" => null,
        "category" => null
    ];
    $instruction = "SELECT id, nombre, imagen FROM Categoria WHERE id='$id'";
    $categoryQ = $mysqli->query($instruction);
    $categoryResult = $categoryQ->fetch_assoc();
        $categoryResult = array_map("decode", $categoryResult);
        $categoryToAdd=[
            "name"=> $categoryResult[nombre],
            "image" => $categoryResult[imagen],
        ];
        $toReturn["category"] = $categoryToAdd;


    $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE categoria_id='$id' AND cantidad>1";
    $productQ = $mysqli->query($instruction);
    $products= [];
    while($productResult = $productQ->fetch_assoc()){
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
            $date= date('Y-m-d');
            $instruction = "INSERT INTO CanjeaProducto (producto_id, usuario_id, cantidad, fecha, cp, numero, calle, telefono, colonia, municipio, estado) VALUES  ".
                "('$id', '$user', 1, '$date', '$userInfoResult[cp]', '$userInfoResult[numero]', '$userInfoResult[calle]', '$userInfoResult[telefono]', '$userInfoResult[colonia]', '$userInfoResult[municipio]', '$userInfoResult[estado]')";
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
function getMonthName($month){
    $name = "";
    if($month == 1){
        $name = "Enero";
    }
    if($month == 2){
        $name = "Febrero";
    }
    if($month == 3){
        $name = "Marzo";
    }
    if($month == 4){
        $name = "Abril";
    }
    if($month == 5){
        $name = "Mayo";
    }
    if($month == 6){
        $name = "Junio";
    }
    if($month == 7){
        $name = "Julio";
    }
    if($month == 8){
        $name = "Agosto";
    }
    if($month == 9){
        $name = "Septiembre";
    }
    if($month == 10){
        $name = "Octubre";
    }
    if($month == 11){
        $name = "Noviembre";
    }
    if($month == 12){
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


    while ($i<=$monthsToSearch){
        $pointsAdded = 0;
        $pointsRemoved = 0;
        $fromMonth = $toMonth-1;

        if($fromMonth<=0){
            $fromYear--;
            $fromMonth=12;
        }
        $instruction = " SELECT puntos, tipo FROM Transaccion WHERE usuario_id='$id' AND fecha BETWEEN '$fromYear-$fromMonth-01' AND '$toYear-$toMonth-01'";
        $userInfo = $mysqli->query($instruction);
        while($userInfoResult = $userInfo->fetch_assoc()){
            if($userInfoResult[tipo] == 0){
                $pointsAdded+=$userInfoResult[puntos];
            }
            else if ($userInfoResult[tipo] == 1){
                $pointsRemoved+=$userInfoResult[puntos];
            }
        }
        $monthName = getMonthName($fromMonth);
        $monthReport=[
            "name"=> $monthName,
            "added"=> $pointsAdded,
            "removed"=> $pointsRemoved,
        ];
        array_push($monthsReport, $monthReport);
        $toMonth--;
        if($toMonth<=0){
            $toYear--;
            $toMonth=12;
        }
        $i++;
    }

    $monthsReport=  array_reverse($monthsReport);
    return json_encode($monthsReport);

}

function getTradedProducts($id){
    global $mysqli;
    $products =[];
    $instruction = "SELECT status, producto_id FROM CanjeaProducto WHERE usuario_id='$id'";
    $tradeProductQ = $mysqli->query($instruction);
    while($tradeProductResult = $tradeProductQ->fetch_assoc()){
        $instruction = "SELECT id, nombre, imagenes, descripcion, puntos FROM Producto WHERE id='$tradeProductResult[producto_id]'";
        $productQ = $mysqli->query($instruction);
        $status = $tradeProductResult[status];
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
            $statusDescription = "Entregado";
            $statusPercentage = 100;
            $statusColor = "md-hue-3";

        }
        $productResult = $productQ->fetch_assoc();
            $productToAdd=[
                "name"=> $productResult[nombre],
                "description"=> $productResult[descripcion],
                "points"=> $productResult[puntos],
                "image"=> $productResult[imagenes],
                "id" => $productResult[id],
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

function login($email, $password){
    global $mysqli;
    $email=htmlentities($email, ENT_QUOTES);
    $password = sha1($password);
    $result = $mysqli->query("SELECT id, email, password FROM Login WHERE email='$email' AND password='$password'");
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
function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}
?>