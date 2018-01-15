<?php session_start();
session_destroy();
setcookie("user", "", 1);
echo '	<SCRIPT LANGUAGE="javascript">
						location.href = "login.php";
					</SCRIPT>';
?>