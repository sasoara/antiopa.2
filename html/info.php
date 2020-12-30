<?php
// require_once("lib/db.php");
require_once(__DIR__ . "/lib/db.php");

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
