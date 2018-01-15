<?php
header('Access-Control-Allow-Origin: *');
include ("session.php");
$filename = $_FILES['file']['name'];
$user = $_POST["user"]/1;
$filename=htmlentities($filename, ENT_QUOTES);
$ext = pathinfo($filename, PATHINFO_EXTENSION);
if(!mkdir("Users/", 0777 , true)){
    $error = error_get_last();


//    trigger_error("Value must be 1 or below",E_USER_WARNING);
}
$random = strtotime("now") * 1000;

$pathName = "Users/".$user.".".$ext;
move_uploaded_file( $_FILES['file']['tmp_name'] , $pathName);
$error = error_get_last();




$instruction = "UPDATE Usuario SET imagen='php/$pathName?$random' WHERE login_id='$user'";

if (mysqli_query($mysqli, $instruction)) {
    echo "php/".$pathName;
} else {
    echo "Fatal error: " . mysqli_error($mysqli);
}

//echo "Products/".$user."/".$filename;
?>