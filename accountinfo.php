<?php

session_start();

require "classes/User.php";
require "classes/Database.php";

$connectionData = require("db_settings.php");

$database = new Database($connectionData);

$user = new User($database);
// Setting up the head, title and navbar
// If the user is not logged in, they will be redirected to the login page, if they attempt to access this one
if (!$user->isLoggedIn()) {
    $host = $_SERVER['HTTP_HOST'];
    $whereTo = 'login.php';
    header("Location: http://$host/$whereTo");
}


// This displays some account information
require "html/account_information.phtml";