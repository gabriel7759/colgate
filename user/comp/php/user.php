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
    echo $instruction;
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
        "route"=> $userInfoResult[ruta],
        "points"=> $userInfoResult[puntos],
        "rfc"=> $userInfoResult[rfc],
        "curp"=> $userInfoResult[curp],
        "store"=> $store,
        "image" => $userInfoResult[image],
        "id" => $userInfoResult[login_id]
    ];
    return json_encode($user);
}
function getUserPoints ($id){
    global $mysqli;
    $instruction = "SELECT * FROM Usuario WHERE login_id='$id'";
    $userInfo = $mysqli->query($instruction);
    $userInfoResult = $userInfo->fetch_assoc();
    $user=[
        "points"=> $userInfoResult[puntos],
    ];
    $user = array_map("decode", $user);
    return json_encode($user);
}
function updateUser($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $email=htmlentities($info->email, ENT_QUOTES);
    $username=htmlentities($info->username, ENT_QUOTES);
    $password=htmlentities($info->password, ENT_QUOTES);
    $route=htmlentities($info->route, ENT_QUOTES);
    $rfc=htmlentities($info->rfc, ENT_QUOTES);
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
        $instruction = "UPDATE Usuario SET nombre='$name', puntos='$points', tienda_id='$store', rfc='$rfc', ruta='$route', telefono='$phone', calle='$street', colonia='$suburb', numero='$number', municipio='$city', estado='$state', cp='$cp' WHERE login_id='$id'";
        if ($mysqli->query($instruction) === TRUE) {
            $toReturn = [
                "status" => "win",
            ];
        }
    }
    return json_encode($toReturn);
}

function getRecommendations($id){

}

function decode($toDecode) {
    return html_entity_decode($toDecode,ENT_QUOTES, "UTF-8");
}
?>