<?php
session_start();

require 'classes/Products.php';
require 'classes/User.php';
require "classes/Database.php";


// This displays details about a product in their respective page
// => products.php?prodId=[product id]

$connectionData = require("db_settings.php");
$database = new Database($connectionData);
$prod = new Products($database);
$user = new User($database);
$result = $prod->getProductDetails($_GET['prodId']);

require "html/products.phtml";