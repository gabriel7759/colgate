<?php
header('Access-Control-Allow-Origin: *');
include ("session.php");
$filename = $_FILES['file']['name'];
$category = $_POST["category"]/1;
$filename=htmlentities($filename, ENT_QUOTES);
$ext = pathinfo($filename, PATHINFO_EXTENSION);
if(!mkdir("Categories/", 0777 , true)){
    $error = error_get_last();


//    trigger_error("Value must be 1 or below",E_USER_WARNING);
}
$random = strtotime("now") * 1000;

$pathName = "Categories/".$category.".".$ext;
move_uploaded_file( $_FILES['file']['tmp_name'] , $pathName);
$error = error_get_last();




$instruction = "UPDATE Categoria SET imagen='php/$pathName?$random' WHERE id='$category'";

if (mysqli_query($mysqli, $instruction)) {
    echo "php/".$pathName;
} else {
    echo "Fatal error: " . mysqli_error($mysqli);
}

//echo "Categories/".$category."/".$filename;
?>