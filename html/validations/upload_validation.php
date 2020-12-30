<?php
// handle 'submit' of upload.php form
# TODO: Sessionhandling muss eingebunden werden!!

// Enables debugging
$info = require_once("../info.php");

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
        # TODO: Logger!!
        debug_to_console("You cannot upload to the specified directory, please CHMOD it to 777.");
        exit;
    }

    // Be sure we're dealing with an upload
    if (is_uploaded_file($userfile['tmp_name']) === false) {
        # TODO: Audit!!
        debug_to_console("Error on upload: Invalid file definition");
        exit;
    }

    // Check if the filetype is allowed, if not DIE and inform the user.
    if (!strpos($userfile['type'], $allowed_type, 0) == 0) {
        # TODO: Audit!!
        debug_to_console("Opps! Image Format not allowed!");
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

    $data = array([$filename, $secure_filename, $mime_type]);

    # TODO: Param Übergabe an uploadForm.php

    // Redirect with POST request
    header("Location: ../uploadForm.php");
} else {
    # TODO: Audit!!
    debug_to_console("NO VALID IMAGE FILE FOUND!");
}

?>

<html>

</html>