<?php

session_start();

$_SESSION['isLoggedIn'] = false;

session_destroy();

$host = $_SERVER['HTTP_HOST'];
$whereTo = "index.php";

header("Location: http://$host/$whereTo");