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


// The place the files will be uploaded to (currently a 'temporary' directory)
$upload_temp_dir = "../../data/tmp/";
// The place the file will moves permanent
$upload_dir = "../../data/";

if (isset($_POST['save'])) {
    // Users id
    $user_id = $_SESSION['user_id'];

    // True, when required fields are filled
    $complete_form = true;

    // If the user choose public post
    $visibility_pub = (isset($_POST['public'])) ? 1 : 0;

    // Param validation
    !empty($_POST['date']) ? $date = htmlspecialchars($_POST['date']) : $complete_form = false;
    !empty($_POST['title']) ? $title = htmlspecialchars($_POST['title']) : $complete_form = false;
    !empty($_SESSION['data'][0]) ? $filename = htmlspecialchars($_SESSION['data'][0]) : $complete_form = false;
    !empty($_SESSION['data'][1]) ? $secure_filename = htmlspecialchars($_SESSION['data'][1]) : $complete_form = false;
    !empty($_SESSION['data'][2]) ? $mime_type = htmlspecialchars($_SESSION['data'][2]) : $complete_form = false;

    // Not required field
    $description = htmlspecialchars($_POST['description']);
    $created_on = date('Y-m-d H:i:s');



    // Check if required fields are filled
    if ($complete_form) {

        // Checks if the data/ directory is writable
        if (!is_readable($upload_temp_dir)) {
            # TODO: Logger!!
            debug_to_console('You cannot upload to the specified directory, please CHMOD it to 777.');
        }

        // Checks if the data/ directory is writable
        if (!is_writable($upload_dir)) {
            # TODO: Logger!!
            debug_to_console('You cannot upload to the specified directory, please CHMOD it to 777.');
        }

        debug_to_console($upload_temp_dir . $secure_filename);
        debug_to_console($upload_dir . $secure_filename);

        // Moves the image into the permanent directory
        rename($upload_temp_dir . $secure_filename, $upload_dir . $secure_filename);

        try {
            $stmt = $dbh->prepare("INSERT INTO posts (title, description, content_type, is_public, created_on, file_name, users_id, secure_file_name, date) VALUES (:title, :description, :content_type, :is_public, :created_on, :file_name, :users_id, :secure_file_name, :date)");

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':content_type', $mime_type);
            $stmt->bindParam(':is_public', $visibility_pub);
            $stmt->bindParam(':created_on', $created_on);
            $stmt->bindParam(':file_name', $filename);
            $stmt->bindParam(':users_id', $user_id);
            $stmt->bindParam(':secure_file_name', $secure_filename);
            $stmt->bindParam(':date', $date);

            $stmt->execute();
            # TODO: Prüfen ob da nicht ein Security - Bug steckt!!
            // Get the id of the last insert
            $post_id = $dbh->lastInsertId();
        } catch (PDOException $e) {
            # TODO: Logger!!
            debug_to_console($e);
        }
        cleanSessionData();
        // Redirecting to detailed view
        header("location: ../detailView.php?id=$post_id");
    }
} else if (isset($_POST['cancel'])) {
    if (!empty($_SESSION['data'][1])) {
        $secure_filename = htmlspecialchars($_SESSION['data'][1]);
        unlink($upload_temp_dir . $secure_filename);
        cleanSessionData();
        header("location: ../upload.php");
    } else {
        cleanSessionData();
        # TODO: Logger!!
        debug_to_console("No secure filename presented");
    }
} else {
    cleanSessionData();
    # TODO: Logger!!
    debug_to_console("Invalid function!");
}

// To destroy image data in session
function cleanSessionData()
{
    unset($_SESSION['data']);
}

?>

<html>

</html>