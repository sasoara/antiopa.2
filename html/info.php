<?php
// Database connection
require_once(__DIR__ . "/lib/db.php");

// Debug function
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

# TODO: Implementieren von Audit Funktion!!
# TODO: Implementieren von Logger Funktion!!