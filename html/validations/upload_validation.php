<?php
// Presents base services
$info = require_once("../info.php");

// Sessionhandling
session_start();
// if the user does not select the field 'stay logged in', it is automatically logged out
if (!$_SESSION['stay_logged_in']) {
    // sets the login time as request time, if it is empty
    if (!isset($_SESSION['LAST_REQUEST_TIME'])) {
        $_SESSION['LAST_REQUEST_TIME'] = time();
    }
    // destroys the session if the request time was longer than 30 minutes
    if ($_SESSION['LAST_REQUEST_TIME'] + 30 * 60 < time()) {
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        header('location: ../../index.php');
        exit;
    } // otherwise, the request time will be updated by time
    else {
        $_SESSION['LAST_REQUEST_TIME'] = time();
    }
}
// if there is no active or email session then you get back to the login page
if (session_status() !== PHP_SESSION_ACTIVE or !$_SESSION['email'] or session_status() == PHP_SESSION_NONE) {
    header('location: ../../index.php');
}

// Mime content type which is allowed
$allowed_type = 'image/';
// The place the files will be uploaded to (currently a 'temporary' directory)
$upload_temp_dir = "../../data/tmp/";
# TODO: Eine maximale Uploadgrösse festlegen und prüfen!!
// Maximum filesize in BYTES (currently 2MB)
$maxsize = 2097152;

// The value from POST, the form input field from type file
$userfile = $_FILES['image'];

if (!empty($userfile) && ($userfile['error'] == UPLOAD_ERR_OK) && (strpos($userfile['type'], $allowed_type, 0) == 0)) {

    // Checks if the data/tmp directory is writable
    if (!is_writable($upload_temp_dir)) {
        error_log("You cannot upload to the specified directory, please CHMOD it to 777.");
        exit;
    }

    // Be sure we're dealing with an upload
    if (is_uploaded_file($userfile['tmp_name']) === false) {
        error_log("Error on upload_validation: Invalid file definition");
        exit;
    }

    // Check if the filetype is allowed, if not DIE and inform the user.
    if (!strpos($userfile['type'], $allowed_type, 0) == 0) {
        error_log("Opps! Image Format not allowed! at upload_validation");
        exit;
    }

    // Mime type from image
    $mime_type = $userfile['type'];
    // The named image in html entities
    $filename = htmlspecialchars($userfile['name']);
    // Get the name of the file (including file extension)
    $ext = strtolower(substr($filename, strpos($filename, '.'), strlen($filename) - 1));
    // The secure renamed image name
    $secure_filename = bin2hex(random_bytes(16)) . $ext;

    // Insert it into our tracking along with the secure name
    move_uploaded_file($userfile['tmp_name'], $upload_temp_dir . $secure_filename);

    $data = array($filename, $secure_filename, $mime_type);

    # TODO: Param Übergabe an uploadForm.php

    // Write data in session
    if (empty($_SESSION['data'])) {
        $_SESSION['data'] = $data;
    } else {
        $_SESSION['data'] = $data;
        error_log("Session 'data' isn't empty! at upload_validation");
    }

    // Redirect with POST request
    header("Location: ../uploadForm.php");
} else {
    error_log("NO VALID IMAGE FILE FOUND! at upload_validation");
}

?>