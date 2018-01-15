<?php
header('Access-Control-Allow-Origin: *');
include ("session.php");
$filename = $_FILES['file']['name'];
$filename=htmlentities($filename, ENT_QUOTES);
$ext = pathinfo($filename, PATHINFO_EXTENSION);
if(!mkdir("UsersX/", 0777 , true)){
    $error = error_get_last();
}
$random = strtotime("now") * 1000;

$pathName = "UsersX/".$random.".".$ext;
move_uploaded_file( $_FILES['file']['tmp_name'] , $pathName);

echo $pathName;

?>