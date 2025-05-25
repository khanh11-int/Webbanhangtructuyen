<?php
$servwername = "localhost";
$connectionOptions = array(
    "Database" => "dbms_mypham",
    "Uid" => "admin",
    "PWD" => "123123",
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($servwername, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>