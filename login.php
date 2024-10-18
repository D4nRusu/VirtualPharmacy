<?php

session_start();

require "classes/User.php";
require "classes/Database.php";

$connectionData = require("db_settings.php");

$database = new Database($connectionData);

$user = new User($database);

// Handling login on form submit
if (isset($_POST['submit'])) {
    $user->login();
}

// If the user is logged in, redirect to home page
if ($user->isLoggedIn()) {
    $host = $_SERVER['HTTP_HOST'];
    $whereTo = 'index.php';
    header("Location: http://$host/$whereTo");
}

require "html/login.phtml"; // login form

