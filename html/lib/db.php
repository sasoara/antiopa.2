<?php
require_once(__DIR__ . "/../../config/db_config.php");

$db_host = $config["database"]["host"];
$db_database = $config["database"]["name"];
$db_user = $config["database"]["user"];
$db_password = $config["database"]["password"];

$dsn = "mysql:host={$db_host};dbname={$db_database};charset=utf8mb4";
$options = [
    PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
];
try {
    $dbh = new PDO($dsn, $db_user, $db_password, $options);
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Something weird happened'); //something a user can understand
}
