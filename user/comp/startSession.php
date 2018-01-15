<?php session_start();

//datos para establecer la conexion con la base de mysql.
include_once("php/session.php");
$email=$_COOKIE["email"];
$password = $_COOKIE["password"];
$aux="no";
if(trim($_COOKIE["email"]) != "" && trim($_COOKIE["password"]) != "")
{

    $email = strtolower(htmlentities($email, ENT_QUOTES));
    $password = sha1($password);
    $result = $mysqli->query("SELECT id, email, password FROM Login WHERE (email='$email' OR username='$email') AND password='$password'");
//    echo "SELECT id, email, password FROM login WHERE email='$email' AND password='$password'";
    $loginInfo = $result->fetch_assoc();

    if($loginInfo[id] != null && $loginInfo[id]>0){
        setcookie("user",$loginInfo[id], time() + (86400 * 30), "/");
//        setcookie("email","", time() + (86400 * 30), "/");
        $_SESSION["user"] = $loginInfo['id'];
        echo '	<SCRIPT LANGUAGE="javascript">
							location.href = "index.php";
						</SCRIPT>';
    }
    else{
        setcookie("password","", time() + (86400 * 30), "/");
        setcookie("email","", time() + (86400 * 30), "/");
        echo '	<SCRIPT LANGUAGE="javascript">
							location.href = "login.php";
						</SCRIPT>';
    }

}
else{
    echo '	<SCRIPT LANGUAGE="javascript">
							location.href = "login.php";
						</SCRIPT>';
}
?>