<?php
include_once ("session.php");
//Stores

function createStore ($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=htmlentities($info->number, ENT_QUOTES);
    $route=htmlentities($info->route, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "INSERT INTO Tienda (nombre, telefono, calle, colonia, numero, municipio, estado, cp) VALUES  ('$name', '$phone', '$street', '$suburb', '$number', '$route', '$state', '$cp')";
    if ($mysqli->query($instruction) === TRUE) {
        $lastId = $mysqli->insert_id;

        $toReturn = [
            "status" => "win",
            "id" =>    $lastId
        ];
    }
    return json_encode($toReturn);
}
function deleteStore($id){
    global $mysqli;

    $result = "DELETE FROM Tienda
 WHERE id='$id' ";
    if (mysqli_query($mysqli, $result)) {
        return "win";
    } else {
        echo "Fatal error: ". mysqli_error($mysqli);
        return;
    }
}
function getStore ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Tienda WHERE id='$id'";
    $storeInfo = $mysqli->query($instruction);
    $storeResult = $storeInfo->fetch_assoc();
    $route=[
        "name"=> $storeResult[nombre],
        "phone"=> $storeResult[telefono],
        "street"=> $storeResult[calle],
        "suburb"=> $storeResult[colonia],
        "number"=> $storeResult[numero],
        "city"=> $storeResult[municipio],
        "state"=> $storeResult[estado],
        "image"=> $storeResult[imagen],
        "cp"=> $storeResult[cp],
        "id" => $storeResult[id]
    ];
    $route = array_map("decode", $route);
    return json_encode($route);
}
function getStores(){
    global $mysqli;

    $toReturn = [
        "status" => "fail",
        "stores" => null
    ];
    $route= [
    ];
    $instruction = "SELECT id, nombre, imagen FROM Tienda";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){
        $storeToAdd=[
            "name"=> $userResult[nombre],
            "image"=> $userResult[imagen],
            "id" => $userResult[id]
        ];
        $storeToAdd = array_map("decode", $storeToAdd);
        array_push($route, $storeToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["stores"] = $route;

    return json_encode($toReturn);

}
function updateStore($info){
    global $mysqli;
    $id=htmlentities($info->id, ENT_QUOTES);
    $name=htmlentities($info->name, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=htmlentities($info->number, ENT_QUOTES);
    $route=htmlentities($info->route, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "UPDATE Tienda SET nombre= '$name', telefono='$phone', calle='$street', colonia='$suburb', numero='$number', municipio='$route', estado='$state', cp='$cp' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {

                $toReturn = [
                    "status" => "win",
                ];

    }
    return json_encode($toReturn);


}


function createCategory ($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);


    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "INSERT INTO Categoria (nombre) VALUES  ('$name')";
    if ($mysqli->query($instruction) === TRUE) {
        $lastId = $mysqli->insert_id;

        $toReturn = [
            "status" => "win",
            "id" =>    $lastId
        ];
    }
    return json_encode($toReturn);
}
function deleteCategory($id){
    global $mysqli;

    $result = "DELETE FROM Categoria
 WHERE id='$id' ";
    if (mysqli_query($mysqli, $result)) {
        return "win";
    } else {
        echo "Fatal error: ". mysqli_error($mysqli);
        return;
    }
}
function getCategory ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Categoria WHERE id='$id'";
    $storeInfo = $mysqli->query($instruction);
    $storeResult = $storeInfo->fetch_assoc();
    $route=[
        "name"=> $storeResult[nombre],
        "image"=> $storeResult[imagen],
        "id" => $storeResult[id]
    ];
    $route = array_map("decode", $route);
    return json_encode($route);
}
function getCategories(){
    global $mysqli;

    $toReturn = [
        "status" => "fail",
        "categories" => null
    ];
    $category= [
    ];
    $instruction = "SELECT id, nombre FROM Categoria";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){


        $categoryToAdd=[
            "name"=> $userResult[nombre],
            "id" => $userResult[id]
        ];
        $categoryToAdd = array_map("decode", $categoryToAdd);
        array_push($category, $categoryToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["categories"] = $category;

    return json_encode($toReturn);

}
function updateCategory($info){
    global $mysqli;
    $id=htmlentities($info->id, ENT_QUOTES);
    $name=htmlentities($info->name, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "UPDATE Categoria SET nombre= '$name' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {

        $toReturn = [
            "status" => "win",
        ];

    }
    return json_encode($toReturn);


}


function createProduct ($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $stock=htmlentities($info->stock, ENT_QUOTES);
    $description=htmlentities($info->description, ENT_QUOTES);
    $points=htmlentities($info->points, ENT_QUOTES);
    $category=htmlentities($info->category, ENT_QUOTES);


    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "INSERT INTO Producto (nombre, cantidad, descripcion, puntos, categoria_id) VALUES  ('$name', '$stock', '$description', '$points', '$category')";
    if ($mysqli->query($instruction) === TRUE) {
        $lastId = $mysqli->insert_id;

        $toReturn = [
            "status" => "win",
            "id" =>    $lastId
        ];
    }
    return json_encode($toReturn);
}
function deleteProduct($id){
    global $mysqli;

    $result = "DELETE FROM Producto WHERE id='$id' ";
    if (mysqli_query($mysqli, $result)) {
        return "win";
    } else {
        echo "Fatal error: ". mysqli_error($mysqli);
        return;
    }
}
function getProduct ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Producto WHERE id='$id'";
    $productInfo = $mysqli->query($instruction);
    $productResult = $productInfo->fetch_assoc();
    $product=[
        "name"=> $productResult[nombre],
        "stock"=> $productResult[cantidad],
        "description"=> $productResult[descripcion],
        "points"=> $productResult[puntos],
        "category"=> $productResult[categoria_id],
        "image"=> $productResult[imagenes],
        "id" => $productResult[id]
    ];
    $product = array_map("decode", $product);
    return json_encode($product);
}
function getProducts(){
    global $mysqli;

    $toReturn = [
        "status" => "fail",
        "products" => null
    ];
    $product= [
    ];
    $instruction = "SELECT id, nombre, categoria_id FROM Producto";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){
        $instruction = "SELECT id, nombre FROM Categoria WHERE id='$userResult[categoria_id]'";
        $categoryQ = $mysqli->query($instruction);
        $categoryResult = $categoryQ->fetch_assoc();

        $productToAdd=[
            "name"=> $userResult[nombre],
            "category"=> $categoryResult[nombre],
            "id" => $userResult[id]
        ];
        $productToAdd = array_map("decode", $productToAdd);
        array_push($product, $productToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["products"] = $product;

    return json_encode($toReturn);

}
function updateProduct($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $stock=htmlentities($info->stock, ENT_QUOTES);
    $description=htmlentities($info->description, ENT_QUOTES);
    $points=htmlentities($info->points, ENT_QUOTES);
    $category=htmlentities($info->category, ENT_QUOTES);
    $id=htmlentities($info->id, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "UPDATE Producto SET nombre= '$name', cantidad='$stock', descripcion='$description', puntos='$points', categoria_id='$category' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {

        $toReturn = [
            "status" => "win",
        ];

    }
    return json_encode($toReturn);


}


function createUser ($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $email=htmlentities($info->email, ENT_QUOTES);
    $username=htmlentities($info->username, ENT_QUOTES);
    $password=sha1(htmlentities($info->password, ENT_QUOTES));
    $route=htmlentities($info->route, ENT_QUOTES);
    $rfc=htmlentities($info->rfc, ENT_QUOTES);
    $curp=htmlentities($info->curp, ENT_QUOTES);
    $store=htmlentities($info->store, ENT_QUOTES);
    $points=htmlentities($info->points, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=htmlentities($info->number, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);
    $level=htmlentities($info->level, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];

    if(verifyUser($email, $username)){
        $email=strtolower($email);
        $username=strtolower($username);
        $instruction = "INSERT INTO Login (tipo, email, password, username, nivel) VALUES  ('1', '$email', '$password', '$username', '$level')";
        if ($mysqli->query($instruction) === TRUE) {
            $lastId = $mysqli->insert_id;
            $instruction = "INSERT INTO Usuario (nombre, puntos, tienda_id, login_id, rfc, ruta, telefono, calle, colonia, numero, municipio, estado, cp, curp) VALUES  ('$name', '$points', '$store', '$lastId', '$rfc', '$route', '$phone', '$street', '$suburb', '$number', '$route', '$state', '$cp', '$curp')";
            if ($mysqli->query($instruction) === TRUE) {
                $toReturn = [
                    "status" => "win",
                ];
                if($email != "" || $email!= null){
                    sendMail($email, $info->name);

                }

            }

        }
    }
    else{
            $toReturn = [
                "status" => "fail",
                "code" => 1
            ];
    }



    return json_encode($toReturn);
}
function deleteUser($id){
    global $mysqli;
    $result = "DELETE FROM Usuario WHERE login_id='$id' ";
    if (mysqli_query($mysqli, $result)) {
        $result = "DELETE FROM Login WHERE id='$id' ";
        if (mysqli_query($mysqli, $result)) {
            return "win";
        } else {
            echo "Fatal error: ". mysqli_error($mysqli);
            return;
        }
    } else {
        echo "Fatal error: ". mysqli_error($mysqli);
        return;
    }
}
function getUser ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Login WHERE id='$id'";
    $loginInfo = $mysqli->query($instruction);
    $loginInfoResult = $loginInfo->fetch_assoc();

    $instruction = "SELECT * FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
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
        "level"=> $loginInfoResult[nivel],
        "username"=> $loginInfoResult[username],
        "type"=> $loginInfoResult[tipo],
        "route"=> $userInfoResult[ruta],
        "points"=> $userInfoResult[puntos],
        "rfc"=> $userInfoResult[rfc],
        "curp"=> $userInfoResult[curp],
        "store"=> $userInfoResult[tienda_id],
        "id" => $userInfoResult[login_id]
    ];
    $user = array_map("decode", $user);
    return json_encode($user);
}
function getUsers(){
    global $mysqli;
    $toReturn = [
        "status" => "win",
        "users" => null
    ];
    $user= [
    ];
    $instruction = "SELECT id, nombre, puntos, tienda_id, login_id, rfc FROM Usuario";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){

        $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userResult[tienda_id]'";
        $storeInfo = $mysqli->query($instruction);
        $storeResult = $storeInfo->fetch_assoc();
        $storeResult = array_map("decode", $storeResult);
        $littleName = explode(" ", $userResult[nombre]) ;
        $userToAdd=[
            "name"=> $userResult[nombre],
            "littleName"=> $littleName[0],
            "rfc"=> $userResult[rfc],
            "points"=> $userResult[puntos],
            "store"=> $storeResult[nombre],
            "id" => $userResult[login_id]
        ];

        $userToAdd = array_map("decode", $userToAdd);
        array_push($user, $userToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["users"] = $user;

    return json_encode($toReturn);

}
function updateUser($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $email=htmlentities($info->email, ENT_QUOTES);
    $username=htmlentities($info->username, ENT_QUOTES);
    $password=htmlentities($info->password, ENT_QUOTES);
    $route=htmlentities($info->route, ENT_QUOTES);
    $rfc=htmlentities($info->rfc, ENT_QUOTES);
    $curp=htmlentities($info->curp, ENT_QUOTES);
    $store=htmlentities($info->store, ENT_QUOTES);
    $points=htmlentities($info->points, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=htmlentities($info->number, ENT_QUOTES);
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);
    $level=htmlentities($info->level, ENT_QUOTES);
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
    $instruction = "UPDATE Login SET email= '$email', password='$password', username='$username', nivel='$level' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {
        $instruction = "UPDATE Usuario SET nombre='$name', puntos='$points', tienda_id='$store', rfc='$rfc', curp='$curp', ruta='$route', telefono='$phone', calle='$street', colonia='$suburb', numero='$number', municipio='$city', estado='$state', cp='$cp' WHERE login_id='$id'";
        if ($mysqli->query($instruction) === TRUE) {
            $toReturn = [
                "status" => "win",
            ];


        }

    }
    return json_encode($toReturn);


}
function addPoints1 ($points, $type, $id)
{
    global $mysqli;
    $date = date('Y-m-d');
    $points = $points/1;

    $toReturn = [
        "status" => "fail",
    ];

    $instruction = "SELECT puntos FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $previousPoints = $userInfoResult[puntos];
    $newBalance = $points + $previousPoints;

    $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('$type', '$points', '$id',  '$date')";
    if ($mysqli->query($instruction) === TRUE) {
        if($type != 5){
            $instruction = "UPDATE Usuario SET puntos='$newBalance' WHERE login_id='$id'";
            if ($mysqli->query($instruction) === TRUE) {
                $toReturn = [
                    "status" => "win"
                ];
            }
        }
        else{
            $toReturn = [
                "status" => "win"
            ];
        }

    }
    return json_encode($toReturn);

}
function addPoints ($info)
{
    global $mysqli;
    $date = date('Y-m-d');
    $points = htmlentities($info->points, ENT_QUOTES);
    $type = htmlentities($info->type, ENT_QUOTES);
    $points = $points/1;
    $id = htmlentities($info->id, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
    ];

    $instruction = "SELECT puntos FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $previousPoints = $userInfoResult[puntos];
    $newBalance = $points + $previousPoints;

    $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('$type', '$points', '$id',  '$date')";
    if ($mysqli->query($instruction) === TRUE) {
        if($type != 5){
            $instruction = "UPDATE Usuario SET puntos='$newBalance' WHERE login_id='$id'";
            if ($mysqli->query($instruction) === TRUE) {
                $toReturn = [
                    "status" => "win"
                ];
            }
        }
        else{
            $toReturn = [
                "status" => "win"
            ];
        }

    }
    return json_encode($toReturn);

}
function verifyUser ($email, $user){
    global $mysqli;
    $email = htmlentities($email, ENT_QUOTES);
    $user = htmlentities($user, ENT_QUOTES);

    if($email != "" && $email != null && $user != "" && $user != null ){
        $instruction = "SELECT id FROM Login WHERE email='$email' OR username='$user'";

    }
    else if($email != "" || $email != null){
        $instruction = "SELECT id FROM Login WHERE email='$email' ";

    }
    else if($user != "" || $user != null){
        $instruction = "SELECT id FROM Login WHERE username='$user'";
    }

    $loginInfo = $mysqli->query($instruction);

    $num_row = $loginInfo ->num_rows;
    if($num_row == 0){
        $canAdd = true;
    }
    else{
        $canAdd = false;
    }
    return $canAdd;

}



function createAdmin ($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $email=htmlentities($info->email, ENT_QUOTES);
    $username=htmlentities($info->username, ENT_QUOTES);
    $password=sha1(htmlentities($info->password, ENT_QUOTES));
    $phone=htmlentities($info->phone, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    if(verifyUser($email, $username)){
        $instruction = "INSERT INTO Login (tipo, email, password, username) VALUES  ('2', '$email', '$password', '$username')";
        if ($mysqli->query($instruction) === TRUE) {
            $lastId = $mysqli->insert_id;
            $instruction = "INSERT INTO Administrador (nombre, login_id, telefono) VALUES  ('$name', '$lastId',  '$phone' )";
            if ($mysqli->query($instruction) === TRUE) {
                $toReturn = [
                    "status" => "win",
                ];
                if($email != "" || $email!= null){
                    sendMail($email, $info->name);

                }

            }

        }
    }
    else{
        $toReturn = [
            "status" => "fail",
            "code" => 1
        ];
    }


    return json_encode($toReturn);
}
function deleteAdmin($id){
    global $mysqli;
    $result = "DELETE FROM Administrador WHERE login_id='$id' ";
    if (mysqli_query($mysqli, $result)) {
        $result = "DELETE FROM Login WHERE id='$id' ";
        if (mysqli_query($mysqli, $result)) {
            return "win";
        } else {
            echo "Fatal error: ". mysqli_error($mysqli);
            return;
        }
    } else {
        echo "Fatal error: ". mysqli_error($mysqli);
        return;
    }
}
function getAdmin ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Login WHERE id='$id'";
    $loginInfo = $mysqli->query($instruction);
    $loginInfoResult = $loginInfo->fetch_assoc();

    $instruction = "SELECT * FROM Administrador WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $user=[
        "name"=> $userInfoResult[nombre],
        "phone"=> $userInfoResult[telefono],
        "email"=> $loginInfoResult[email],
        "username"=> $loginInfoResult[username],
        "id" => $loginInfoResult[id]
    ];
    $user = array_map("decode", $user);
    return json_encode($user);
}
function getAdmins(){
    global $mysqli;
    $toReturn = [
        "status" => "win",
        "users" => null
    ];
    $user= [
    ];
    $instruction = "SELECT id, nombre, login_id FROM Administrador";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){

        $littleName = explode(" ", $userResult[nombre]) ;
        $userToAdd=[
            "name"=> $userResult[nombre],
            "littleName"=> $littleName[0],
            "id" => $userResult[login_id]
        ];

        $userToAdd = array_map("decode", $userToAdd);
        array_push($user, $userToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["users"] = $user;

    return json_encode($toReturn);

}
function updateAdmin($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $email=htmlentities($info->email, ENT_QUOTES);
    $username=htmlentities($info->username, ENT_QUOTES);
    $password=htmlentities($info->password, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
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
    $instruction = "UPDATE Login SET email= '$email', password='$password', username='$username' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {
        $instruction = "UPDATE Administrador SET nombre='$name', telefono='$phone' WHERE login_id='$id'";
        if ($mysqli->query($instruction) === TRUE) {
            $toReturn = [
                "status" => "win",
            ];


        }

    }
    return json_encode($toReturn);


}




function getTradedProducts(){
    global $mysqli;
    $products =[];
    $toReturn = [
        "status" => "fail",
        "trades" => null
    ];
    $productName = "";

    $instruction = "SELECT id, status, producto_id, usuario_id, detalles FROM CanjeaProducto ";
    $tradeProductQ = $mysqli->query($instruction);
    while($tradeProductResult = $tradeProductQ->fetch_assoc()){
        $instruction = "SELECT nombre, tienda_id, rfc FROM Usuario WHERE login_id='$tradeProductResult[usuario_id]'";
        $userInfo = $mysqli->query($instruction);
        $userInfoResult = $userInfo->fetch_assoc();

        $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userInfoResult[tienda_id]'";
        $storeInfo = $mysqli->query($instruction);
        $storeResult = $storeInfo->fetch_assoc();

        if($tradeProductResult[producto_id] != 0){
            $instruction = "SELECT id, nombre FROM Producto WHERE id='$tradeProductResult[producto_id]'";
            $productQ = $mysqli->query($instruction);
            $productResult = $productQ->fetch_assoc();
            $littleName = explode(" ", $userInfoResult[nombre]) ;
            $productName = $productResult[nombre];
        }
        else{
            $productQ = json_decode($tradeProductResult[detalles]);
            if($productQ ->type == 1){
                $productName = "Recarga";
                $productName = $productName . " - " . $productQ ->company . " - " . $productQ ->phone . " - " . $productQ ->amount ;
            }
        }

        if($tradeProductResult[status] == 1){
            $statusDescription = "Solicitado";
        }
        if($tradeProductResult[status] == 2){
            $statusDescription = "En camino";
        }
        if($tradeProductResult[status] == 3){
            $statusDescription = "Entregado";
        }
        if($tradeProductResult[status] == 4){
            $statusDescription = "Cancelado";
        }

        $productToAdd=[
            "productName"=> $productName,
            "userName"=> $userInfoResult[nombre],
            "rfc"=> $userInfoResult[rfc],
            "littleName" => $littleName[0],
            "storeName"=> $storeResult[nombre],
            "id" => $tradeProductResult[id],
            "status" => $tradeProductResult[status],
            "statusDescription" => $statusDescription,
            "initialStatus" => $tradeProductResult[status]

        ];
        $productToAdd = array_map("decode", $productToAdd);
        array_push($products, $productToAdd);
    }
    $toReturn = [
        "status" => "win",
        "trades" => $products
    ];
    return json_encode($toReturn);
}
function cancelStatus ($id){
    global $mysqli;
    $instruction = " SELECT puntos, usuario_id, fecha FROM Transaccion WHERE canjeaProducto_id='$id'";
//    echo $instruction;
    $transaction = $mysqli->query($instruction);
    $transactionResult = $transaction->fetch_assoc();
    $points = $transactionResult[puntos];
    $user = $transactionResult[usuario_id];

    addPoints1 ($points, 4, $user);



}
function changeStatus ($info){
    global $mysqli;
    $toReturn = [
        "status" => "fail"
    ];
    $id = htmlentities($info->id, ENT_QUOTES);
    $status = htmlentities($info->status, ENT_QUOTES)/1;
    $date = htmlentities($info->date, ENT_QUOTES);

    $instruction = "UPDATE CanjeaProducto SET status='$status', fechaEntrega='$date' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {
        if($status == 4){
            cancelStatus($id);
        }
        $toReturn = [
            "status" => "win"
        ];
    }
    return json_encode($toReturn);

}

function getUsersByStore($route){
    global $mysqli;
    $toReturn = [
        "status" => "fail",
        "users" => null
    ];
    $user= [
    ];
    $instruction = "SELECT id, nombre, puntos, tienda_id, municipio, ruta FROM Usuario WHERE tienda_id='$route'";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){

        $userToAdd=[
            "route" => $userResult[ruta],
            "city" => $userResult[municipio],
            "name"=> $userResult[nombre],
            "points"=> $userResult[puntos],
            "id" => $userResult[id]
        ];

        $userToAdd = array_map("decode", $userToAdd);
        array_push($user, $userToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["users"] = $user;

    return json_encode($toReturn);
}
function getUsersByCity($route){
    global $mysqli;
    $toReturn = [
        "status" => "fail",
        "users" => null
    ];
    $user= [
    ];
    $instruction = "SELECT id, nombre, puntos, tienda_id, municipio, ruta FROM Usuario WHERE municipio LIKE '%$route%';";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){

        $userToAdd=[
            "route" => $userResult[ruta],
            "city" => $userResult[municipio],
            "name"=> $userResult[nombre],
            "points"=> $userResult[puntos],
            "id" => $userResult[id]
        ];

        $userToAdd = array_map("decode", $userToAdd);
        array_push($user, $userToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["users"] = $user;

    return json_encode($toReturn);
}
function getUsersByRoute($route, $store){
    global $mysqli;
    $toReturn = [
        "status" => "fail",
        "users" => null
    ];
    $user= [
    ];
    $instruction = "SELECT id, nombre, puntos, tienda_id, municipio, ruta FROM Usuario WHERE ruta='$route' AND tienda_id='$store';";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){

        $userToAdd=[
            "route" => $userResult[ruta],
            "city" => $userResult[municipio],
            "name"=> $userResult[nombre],
            "points"=> $userResult[puntos],
            "id" => $userResult[id]
        ];

        $userToAdd = array_map("decode", $userToAdd);
        array_push($user, $userToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["users"] = $user;

    return json_encode($toReturn);
}
function getTradedProductsReport($status){
    global $mysqli;
    $products =[];
    $toReturn = [
        "status" => "fail",
        "trades" => null
    ];
    if($status == null || $status == 0 ){
        $instruction = "SELECT id, status, producto_id, usuario_id FROM CanjeaProducto ";

    }else{
        $instruction = "SELECT id, status, producto_id, usuario_id, detalles FROM CanjeaProducto WHERE status='$status'";

    }
    $tradeProductQ = $mysqli->query($instruction);
    while($tradeProductResult = $tradeProductQ->fetch_assoc()){
        $instruction = "SELECT nombre, tienda_id FROM Usuario WHERE login_id='$tradeProductResult[usuario_id]'";
        $userInfo = $mysqli->query($instruction);
        $userInfoResult = $userInfo->fetch_assoc();

        $instruction = "SELECT nombre, imagen, id FROM Tienda WHERE id='$userInfoResult[tienda_id]'";
        $storeInfo = $mysqli->query($instruction);
        $storeResult = $storeInfo->fetch_assoc();

        if($tradeProductResult[producto_id] != 0){
            $instruction = "SELECT id, nombre FROM Producto WHERE id='$tradeProductResult[producto_id]'";
            $productQ = $mysqli->query($instruction);
            $productResult = $productQ->fetch_assoc();
            $littleName = explode(" ", $userInfoResult[nombre]) ;
            $productName = $productResult[nombre];
        }
        else{
            $productQ = json_decode($tradeProductResult[detalles]);
            if($productQ ->type == 1){
                $productName = "Recarga";
                $productName = $productName . " - " . $productQ ->company . " - " . $productQ ->phone . " - " . $productQ ->amount ;
            }
        }
        $littleName = explode(" ", $userInfoResult[nombre]) ;
        $statusDescription = "";
        if($tradeProductResult[status]==1)
            $statusDescription = "Solicitado";
        if($tradeProductResult[status]==2)
            $statusDescription = "En camino";
        if($tradeProductResult[status]==3)
            $statusDescription = "Entregado";
        if($tradeProductResult[status]==4)
            $statusDescription = "Cancelado";

        $productToAdd=[
            "product"=> $productName,
            "name"=> $userInfoResult[nombre],
            "littleName" => $littleName[0],
            "storeName"=> $storeResult[nombre],
            "id" => $tradeProductResult[id],
            "status" => $statusDescription,
            "initialStatus" => $statusDescription,
        ];
        $productToAdd = array_map("decode", $productToAdd);
        array_push($products, $productToAdd);
    }
    $toReturn = [
        "status" => "win",
        "trades" => $products
    ];
    return json_encode($toReturn);
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

    $instruction = "SELECT descripcion FROM General WHERE nombre='hastaFecha'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $toDate = $userInfoResult[descripcion];

    $instruction = "SELECT descripcion FROM General WHERE nombre='comprarAntes'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $canTrade = $userInfoResult[descripcion];
    $toReturn = [
        "status" => "win",
        "message" => $message,
        "tradeDate" => $tradeDate,
        "canTrade" => $canTrade,
        "toDate" => $toDate
    ];
    return json_encode($toReturn);

}


function setMessage ($message){
    $message = htmlentities($message, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
    ];
    global $mysqli;
    $instruction = "UPDATE General SET descripcion= '$message' WHERE nombre='mensaje'";
    if ($mysqli->query($instruction) === TRUE) {
        $toReturn = [
            "status" => "win",
        ];

    }
    return json_encode($toReturn);

}
function setTradeDate ($info){
    $date = htmlentities($info->tradeDate, ENT_QUOTES);
    $canTrade = htmlentities($info->canTrade, ENT_QUOTES);
    $toDate = htmlentities($info->toDate, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
    ];
    global $mysqli;
    $instruction = "UPDATE General SET descripcion= '$date' WHERE nombre='fechaCompra'";
    if ($mysqli->query($instruction) === TRUE) {
        $instruction = "UPDATE General SET descripcion= '$canTrade' WHERE nombre='comprarAntes'";
        if ($mysqli->query($instruction) === TRUE) {
            $instruction = "UPDATE General SET descripcion= '$toDate' WHERE nombre='hastaFecha'";
            if ($mysqli->query($instruction) === TRUE) {
                $toReturn = [
                    "status" => "win",
                ];

            }



        }

    }
    return json_encode($toReturn);

}


function createUsersFromFile ($info){
    global $mysqli;
    $fileName=htmlentities($info->fileName, ENT_QUOTES);
    $store=htmlentities($info->store, ENT_QUOTES);


    require_once ( 'Classes/PHPExcel/IOFactory.php');
    $objPHPExcel = PHPExcel_IOFactory::load($fileName);
    $i=2;
    $keepGoing = true;
    $status = "fail";
    $date = date('Y-m-d');
    $errorUsers = [];

    $password = sha1("ganamasconcolgate");
    $error = 0;
    while($keepGoing){
        $nombre = $objPHPExcel->setActiveSheetIndex()->getCell("B" . $i)->getValue();

        if($nombre != null  && $nombre != "") {
            $ruta = $objPHPExcel->setActiveSheetIndex(0)->getCell("A".$i)->getValue();
            $realName = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
            $nivel = $objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();
            $email = $objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();
            $curp = $objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
            $rfc = $objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue();
            $userName = $objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue();
            $distributionPoints = $objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue();
            $selloutPoints = $objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue();
            $possiblePoints = $objPHPExcel->getActiveSheet()->getCell("J" . $i)->getValue();

            $nombre=htmlentities($nombre, ENT_QUOTES);
            $curp=htmlentities($curp, ENT_QUOTES);
            $email=htmlentities($email, ENT_QUOTES);
            $userName=htmlentities($userName, ENT_QUOTES);
            $points=($distributionPoints/1) + ($selloutPoints/1);
            $rfc=htmlentities($rfc, ENT_QUOTES);
            $email = strtolower ( $email );

            if($nivel == null || $nivel == "" ){
                $nivel = 1;
            }
            else{
                $nivel = $nivel/1 +1;
            }
            if(verifyUser($email, $userName)){
                $email=strtolower($email);
                $userName=strtolower($userName);
                $instruction = "INSERT INTO Login (tipo, email, password, username, nivel) VALUES  ('1', '$email', '$password', '$userName', '$nivel')";
//            echo $instruction;
                if ($mysqli->query($instruction) === TRUE) {
                    $lastId = $mysqli->insert_id;
                    $instruction = "INSERT INTO Usuario (nombre, tienda_id, login_id, rfc, ruta, curp, puntos) VALUES   ('$nombre',  '$store', '$lastId', '$rfc', '$ruta', '$curp', '$points')";
                    if ($mysqli->query($instruction) === TRUE) {
                        $i++;
                        if($email != "" || $email!= null){
                            sendMail($email, $realName);
                        }
                        $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('3', '$distributionPoints', '$lastId',  '$date')";
                        if ($mysqli->query($instruction) === TRUE) {
                            $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('4', '$selloutPoints', '$lastId',  '$date')";
                            if ($mysqli->query($instruction) === TRUE) {
                                $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('5', '$possiblePoints', '$lastId',  '$date')";
                                if ($mysqli->query($instruction) === TRUE) {
                                    $status = "win";
                                }
                            }
                        }
                        $status = "win";

                    } else {
                        $status = "fail3";
                        $keepGoing = false;
                    }
                }
                else{
                    $errorUser = [
                        "name" => $nombre,
                        "email" => $email,
                        "reason" => "Error al insertar"
                    ];
                    array_push($errorUsers, $errorUser);
                    $status = "fail4";
                    $keepGoing = false;
                    $error = 1;

                }
            }
            else{
                $error = 1;
                $errorUser = [
                    "name" => $nombre,
                    "email" => $email,
                    "reason" => "Duplicado"
                ];
                array_push($errorUsers, $errorUser);
                $i++;
            }


        }
        else{
            $keepGoing = false;
        }

    }
    if($status == "win"){
        unlink($fileName);
    }
    if($error == 1){
        $status = "someError";
    }
    $toReturn = [
        "status" => $status,
        "error" => $error,
        "userErrors" => $errorUsers
    ];
    return json_encode($toReturn);
}
function setPointsFromFile ($info){
    global $mysqli;
    $fileName=htmlentities($info->fileName, ENT_QUOTES);


    require_once ( 'Classes/PHPExcel/IOFactory.php');
    $objPHPExcel = PHPExcel_IOFactory::load($fileName);
    $i=2;
    $keepGoing = true;
    $status = "fail";
    $date = date('Y-m-d');

    while($keepGoing){
        $id = $objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
        if($id != null  && $id != "") {
            $distributionPoints = $objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue();
            $selloutPoints = $objPHPExcel->getActiveSheet()->getCell("J" . $i)->getValue();
            $possiblePoints = $objPHPExcel->getActiveSheet()->getCell("K" . $i)->getValue();
            $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('3', '$distributionPoints', '$id',  '$date')";
            if ($mysqli->query($instruction) === TRUE) {
                $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('4', '$selloutPoints', '$id',  '$date')";
                if ($mysqli->query($instruction) === TRUE) {
                    $instruction = "INSERT INTO Transaccion (tipo, puntos, usuario_id, fecha) VALUES  " . "('5', '$possiblePoints', '$id',  '$date')";
                    if ($mysqli->query($instruction) === TRUE) {
                        $status = "win1";
                    }
                }
            }
            $i++;
        }
        else{
            $keepGoing = false;
        }

    }
    if($status == "win"){
        unlink($fileName);
    }
    $toReturn = [
        "status" => $status
    ];
    return json_encode($toReturn);
}


function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}

function sendMail($to, $name){


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
  <title>Bienvenido a Colgate Club!</title>
  
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
            Colgate Club

          </td>
        </tr>
        <tr>
          <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
            <br>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">¡Bienvenido '.$name.' </div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
  Ahora eres parte del Club Colgate
  <br><br>
  Puedes ingresar a través de esta  <a href="http://mkdo.mx/ganamasconcolgate/user/" target="_blank"> página</a>.
  <br><br>

  Tu códgio de acceso es tu usuario, '.$to.' y la contraseña "ganamasconcolgate", la cual podrás cambiar cuando inicies sesión.
  <br><br>
</div>

          </td>
        </tr>
        <tr>
          <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
            <br><br>
            Si tienes problemas al abrir el link, copia la siguiente liga y pegala en el navegador.
            <br>
            http://mkdo.mx/ganamasconcolgate/user/
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
    mail($to,"Bienvenido al Colgate Club",
        $html, $headers);
}

?>