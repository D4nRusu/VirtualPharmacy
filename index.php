<?php

session_start();

require "classes/Products.php";
require "classes/User.php";
require "classes/Database.php";
$connectionData = require("db_settings.php");

$database = new Database($connectionData);
$prod = new Products($database);
$user = new User($database);


// Handling the data received through js
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['isLoggedIn'])) {
        if ($_SESSION['isLoggedIn'] === true) {
            $input = file_get_contents('php://input');
            $input = json_decode($input, true);
            $input['userId'] = $_SESSION['userId'];
            if ($input['checked'] === true) {
                if ($prod->isFavorite($input['userId'], $input['id']) === 0) {
                    $prod->saveFavoriteProduct($input);
                }
            } else {
                if ($prod->isFavorite($input['userId'], $input['id']) > 0) {
                    $prod->deleteFavoriteProduct($input);
                }
            }
        }
    }
}
require "html/index.phtml"; // this renders the page