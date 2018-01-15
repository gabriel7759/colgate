<?php session_start();
include_once("php/session.php");
session_start();

/*if (isset($_SESSION['admin']) || isset($_SESSION['regularUser']) ) {
    echo '	<SCRIPT LANGUAGE="javascript">
                                location.href = "index.php";
                </SCRIPT>';
}*/
if (trim($_POST["email"]) != "" && trim($_POST["password"]) != "") {
    $email = htmlentities($_POST["email"], ENT_QUOTES);
    $password= sha1($_POST["password"]);
    $found = false;
    $type = 0;
    $name = "";
    $id = 0;
    $result = $mysqli->query("SELECT * FROM Login WHERE email='$email' AND password='$password' ");
    $rows = $result->num_rows;
    if ($rows == 0) {
        echo '	<SCRIPT LANGUAGE="javascript">
                                location.href = "login.php";
                </SCRIPT>';
    } else {
        $user = $result->fetch_assoc();
        session_start();
        $_SESSION["user"] = $user[id];
        setcookie("user", $user[id], 0);

        echo '	<SCRIPT LANGUAGE="javascript">
                                location.href = "index.php";
                </SCRIPT>';



        }


} else {
    echo '	<SCRIPT LANGUAGE="javascript">
                                location.href = "login.php";
                </SCRIPT>';
}
?>