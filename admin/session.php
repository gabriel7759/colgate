<?php
//$mysqli = new mysqli("localhost", "adminMomentos", "adminMomentos", "ColgateClub");
//$mysqli = new mysqli("db688560822.db.1and1.com", "dbo688560822", "charlo1357", "db688560822");
$mysqli = new mysqli("localhost", "user", "password", "colgate");
if ($mysqli->connect_errno) {
    echo "Error 504 : (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}
?>