<?php
include_once ("session.php");


function createUser ($info){
    global $mysqli;
    $name=htmlentities($info->name, ENT_QUOTES);
    $email=htmlentities($info->email, ENT_QUOTES);
    $password=htmlentities($info->password, ENT_QUOTES);
    $route=htmlentities($info->route, ENT_QUOTES);
    $rfc=htmlentities($info->rfc, ENT_QUOTES);
    $store=htmlentities($info->store, ENT_QUOTES);
    $points=htmlentities($info->points, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=sha1(htmlentities($info->number, ENT_QUOTES));
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);

    $toReturn = [
        "status" => "fail",
        "code" => null
    ];
    $instruction = "INSERT INTO Login (tipo, email, password) VALUES  ('1', '$email', '$password')";
    if ($mysqli->query($instruction) === TRUE) {
        $lastId = $mysqli->insert_id;
        $instruction = "INSERT INTO Usuario (nombre, puntos, tienda_id, login_id, rfc, ruta, numero, calle, colonia, numero, ciudad, estado, cp) VALUES  ('$name', '$points', '$user', '$lastId', '$rfc', '$route', '$phone', '$street', '$suburb', '$number', '$city', '$state', '$cp')";
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
        "city"=> $userInfoResult[city],
        "state"=> $userInfoResult[estado],
        "cp"=> $userInfoResult[cp],
        "phone"=> $userInfoResult[telefono],
        "email"=> $loginInfoResult[email],
        "type"=> $loginInfoResult[tipo],
        "route"=> $userInfoResult[ruta],
        "points"=> $userInfoResult[points],
        "rfc"=> $userInfoResult[rfc],
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
    $password=htmlentities($info->password, ENT_QUOTES);
    $route=htmlentities($info->route, ENT_QUOTES);
    $rfc=htmlentities($info->rfc, ENT_QUOTES);
    $store=htmlentities($info->store, ENT_QUOTES);
    $points=htmlentities($info->points, ENT_QUOTES);
    $phone=htmlentities($info->phone, ENT_QUOTES);
    $street=htmlentities($info->street, ENT_QUOTES);
    $suburb=htmlentities($info->suburb, ENT_QUOTES);
    $number=sha1(htmlentities($info->number, ENT_QUOTES));
    $city=htmlentities($info->city, ENT_QUOTES);
    $state=htmlentities($info->state, ENT_QUOTES);
    $cp=htmlentities($info->cp, ENT_QUOTES);
    $id=htmlentities($info->is, ENT_QUOTES);

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
    $instruction = "UPDATE Login SET email= '$email', password='$password' WHERE id='$id'";
    if ($mysqli->query($instruction) === TRUE) {
        $instruction = "UPDATE Informacion SET nombre='$name', puntos='$points', tienda_id='$store', rfc='$rfc', ruta='$route', numero='$phone', calle='$street', colonia='$suburb', numero='$number', ciudad='$city', estado='$state', cp='$cp' WHERE login_id='$id'";
        if ($mysqli->query($instruction) === TRUE) {
                $toReturn = [
                    "status" => "win",
                ];


        }

    }
    return json_encode($toReturn);


}

?>