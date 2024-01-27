<?php
$host = "localhost";
$name = "root";
$pass = "";
$db_name = "webfinal";

$conn = mysqli_connect($host, $name, $pass, $db_name);
if (!$conn) {
    echo ("db error");
}

