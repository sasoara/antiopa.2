<?php
require_once(__DIR__ . "/../../config/db_config.php");

$db_host = $config["database"]["host"];
$db_database = $config["database"]["name"];
$db_user = $config["database"]["user"];
$db_password = $config["database"]["password"];

$dsn = "mysql:host={$db_host};dbname={$db_database}";

$options = array();
$dbh = new PDO($dsn, $db_user, $db_password, $options);
