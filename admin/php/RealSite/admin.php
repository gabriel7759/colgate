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
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "INSERT INTO Tienda (nombre, telefono, calle, colonia, numero, municipio, estado, cp) VALUES  ('$name', '$phone', '$street', '$suburb', '$number', '$city', '$state', '$cp')";
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
    $store=[
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
    $store = array_map("decode", $store);
    return json_encode($store);
}
function getStores(){
    global $mysqli;

    $toReturn = [
        "status" => "fail",
        "stores" => null
    ];
    $store= [
    ];
    $instruction = "SELECT id, nombre FROM Tienda";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){
        $storeToAdd=[
            "name"=> $userResult[nombre],
            "id" => $userResult[id]
        ];
        $storeToAdd = array_map("decode", $storeToAdd);
        array_push($store, $storeToAdd);
        $toReturn["status"] = "win";
    }
    $toReturn["stores"] = $store;

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
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "UPDATE Tienda SET nombre= '$name', telefono='$phone', calle='$street', colonia='$suburb', numero='$number', municipio='$city', estado='$state', cp='$cp' WHERE id='$id'";
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
    $store=[
        "name"=> $storeResult[nombre],
        "image"=> $storeResult[imagen],
        "id" => $storeResult[id]
    ];
    $store = array_map("decode", $store);
    return json_encode($store);
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
    $instruction = "SELECT id, nombre FROM Producto";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){


        $productToAdd=[
            "name"=> $userResult[nombre],
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
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "INSERT INTO Login (tipo, email, password, username) VALUES  ('1', '$email', '$password', '$username')";
    if ($mysqli->query($instruction) === TRUE) {
        $lastId = $mysqli->insert_id;
        $instruction = "INSERT INTO Usuario (nombre, puntos, tienda_id, login_id, rfc, ruta, telefono, calle, colonia, numero, municipio, estado, cp, curp) VALUES  ('$name', '$points', '$store', '$lastId', '$rfc', '$route', '$phone', '$street', '$suburb', '$number', '$city', '$state', '$cp', '$curp')";
        if ($mysqli->query($instruction) === TRUE) {
            $toReturn = [
                "status" => "win",
            ];
        }

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
        "status" => "fail",
        "users" => null
    ];
    $user= [
    ];
    $instruction = "SELECT id, nombre FROM Usuario";
    $surveyTaker = $mysqli->query($instruction);
    while($userResult = $surveyTaker->fetch_assoc()){


        $userToAdd=[
            "name"=> $userResult[nombre],
            "id" => $userResult[id]
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
        $instruction = "UPDATE Usuario SET nombre='$name', puntos='$points', tienda_id='$store', rfc='$rfc', curp='$curp', ruta='$route', telefono='$phone', calle='$street', colonia='$suburb', numero='$number', municipio='$city', estado='$state', cp='$cp' WHERE login_id='$id'";
        if ($mysqli->query($instruction) === TRUE) {
            $toReturn = [
                "status" => "win",
            ];


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
    $x="A";
    $i=2;
    $keepGoing = true;
    $status = "win";
    while($keepGoing){
        $ruta = $objPHPExcel->setActiveSheetIndex(1)->getCell("A".$i)->getValue();
        if($ruta != null  && $ruta != "") {
            $nombre = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
            $curp = $objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();
            $rfc = $objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();
            $email = $objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
            $userName = $objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue();
            $phone = $objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue();
            $points = $objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue();

            $instruction = "INSERT INTO Login (tipo, email, password, username) VALUES  ('1', '$email', 'ganamasconcolgate', '$userName')";
            if ($mysqli->query($instruction) === TRUE) {
                $lastId = $mysqli->insert_id;
                $instruction = "INSERT INTO Usuario (nombre, puntos, tienda_id, login_id, rfc, ruta, telefono, curp) VALUES   ('$nombre', '$points', '$store', '$lastId', '$rfc', '$ruta', '$phone',  '$curp')";
                if ($mysqli->query($instruction) === TRUE) {
                    $i++;
                    $status = "win";
                } else {
                    $status = "fail3";
                    $keepGoing = false;
                }
            }

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
?>