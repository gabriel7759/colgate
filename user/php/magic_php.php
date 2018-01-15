<?php
header('Content-Type: text/html; charset=ISO-8859-1');
header("Access-Control-Allow-Origin: *");
$result ='fail10';
$what ='';
$toDo = '' ;
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$what = $request->what;
$toDo = $request->toDo;

$result=html_entity_decode($result,ENT_NOQUOTES, "UTF-8");
$result=preg_replace( "/\r|\n/", "\\n", $result );
$result = utf8_decode($result);

if($what == "user"){
    include_once ("user.php");
    if($toDo == "getUserInfo"){
       $result = getUserInfo ($request->user);
    }
    if($toDo == "getUserPoints"){
        getUserInfo ($request->user);
    }
}

$result=html_entity_decode($result,ENT_NOQUOTES, "UTF-8");
$result=preg_replace( "/\r|\n/", "\\n", $result );
$result = utf8_decode($result);
echo $result;
?>