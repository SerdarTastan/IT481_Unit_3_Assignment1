<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $server = trim($_POST['server']);
    $database = trim($_POST['database']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
   
    $connectionInfo = array("Database" => $database, "UID" => $username, "PWD" => $password, "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($server, $connectionInfo);

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        $_SESSION['server'] = $server;
        $_SESSION['database'] = $database;
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        header("Location: dashboard.php");
        exit();
    }
}
?>