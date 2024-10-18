<?php
session_start();

require "classes/User.php";
require "classes/Database.php";

$connectionData = require("db_settings.php");

$database = new Database($connectionData);

$user = new User($database);

// Setting up the head, navbar, title
if ($user->isLoggedIn()) {
    $host = $_SERVER['HTTP_HOST'];
    $whereTo = 'index.php';
    header("Location: http://$host/$whereTo");
}

// Handling register on submit

if(isset($_POST['submit'])){
    $user->register();
    if(isset($_SESSION['Register'])){
        if($_SESSION['Register'] === true){
            $host = $_SERVER['HTTP_HOST'];
            $whereTo = "login.php";
            header("Location: http://$host/$whereTo");
        }
    }
}

// Register form
require "html/register.phtml";