<?php
// Set the session cookie parameters
$cookie_Params = array(
    'name' => session_name(),
    'value' => '',
    'lifetime' => 0,
    'expire' => 0,
    'path' => '/',
    'domain' => null,
    'secure' => null,
    'httpOnly' => true,
);
session_set_cookie_params(
    $cookie_Params['lifetime'],
    $cookie_Params['path'],
    $cookie_Params['domain'],
    $cookie_Params['secure'],
    $cookie_Params['httpOnly']
);

// Starting the session
session_start();

// TODO: Scheitert!! Pfad ist nicht korrekt (für index.php)!
// Checks where the request come from
if (basename($_SERVER['HTTP_REFERER'], '?' . $_SERVER['QUERY_STRING']) == 'upload_validation.php' || basename($_SERVER['HTTP_REFERER'], '?' . $_SERVER['QUERY_STRING']) == 'uploadForm_validation.php') {
    $path_to_index = '../../index.php';
} else {
    $path_to_index = '../index.php';
}


// If the user does not select the field 'stay logged in', it is automatically logged out
if (!$_SESSION['stay_logged_in']) {

    // Sets the login time as request time, if it is empty
    if (!isset($_SESSION['LAST_REQUEST_TIME'])) {
        $_SESSION['LAST_REQUEST_TIME'] = time();
    }

    // Destroys the session if the request time was longer than 30 minutes
    if ($_SESSION['LAST_REQUEST_TIME'] + 30 * 60 < time()) {
        session_destroy();
        session_write_close();
        setcookie($cookie_Params['name'], $cookie_Params['value'], $cookie_Params['expire'], $cookie_Params['path']);
        header('location: ' . $path_to_index);
        exit;
    } // Otherwise, the request time will be updated by time
    else {
        $_SESSION['LAST_REQUEST_TIME'] = time();
    }
}

// If there is no active or email session then you get back to the login page
if (session_status() !== PHP_SESSION_ACTIVE or !$_SESSION['email'] or session_status() == PHP_SESSION_NONE) {
    header('location: ' . $path_to_index);
}

// Destroy the session and get back to the login
if (!empty($_GET['logout'])) {
    // Unset all session variables
    session_unset();
    // Deletes all saved data in the session
    session_destroy();
    session_write_close();
    // Delete all user cookies -> copied
    setcookie($cookie_Params['name'], $cookie_Params['value'], $cookie_Params['expire'], $cookie_Params['path']);
    header('location: ' . $path_to_index);
}
$res = session_get_cookie_params();
print_r($res);
?>