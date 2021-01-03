<?php

date_default_timezone_set("Europe/Zurich");

$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$search_class_search = $url_path == "/html/search.php" ? "activeSite" : "";
$search_class_upload = $url_path == "/html/upload.php" ? "activeSite" : "";
$search_class_upload_form = $url_path == "/html/formUpload.php" ? "activeSite" : "";
$search_class_index = $url_path == "/index.php" ? "activeSite" : "";
$search_class_admin = $url_path == "/html/admin.php" ? "activeSite" : "";

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
        header('location: ../index.php');
        exit;
    } // otherwise, the request time will be updated by time
    else {
        $_SESSION['LAST_REQUEST_TIME'] = time();
    }
}

//if there is no active or email session then you get back to the login page
if (session_status() !== PHP_SESSION_ACTIVE or !$_SESSION['email'] or session_status() == PHP_SESSION_NONE) {
    header('location: ../index.php');
}

// destroy the session and get back to the login
if (!empty($_GET['logout'])) {
    //if (!empty($_GET['logout']) or isset($_SESSION['logged_in']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60)) {
    // unset all session variables
    session_unset();
    // deletes all saved data in the session
    session_destroy();
    session_write_close();
    //delete all ser cookies -> copied
    setcookie(session_name(), '', 0, '/');
    header('location: ../index.php');
}


// Presents base services
require_once("info.php");

$is_Admin = false;
if(isset($_SESSION['email'])) {
    $uemail = $_SESSION['email'];
    try {
// Selects users from the database to check the fit.
        $stmt = $dbh->prepare("SELECT ur FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $uemail);
        $stmt->execute();
        $db_array_results = $stmt->fetch(PDO::FETCH_ASSOC);
        $is_Admin = $db_array_results['ur']=="admin"? true : false;
    } catch (PDOException $e) {
        error_log("SQL error in header: " . $e);
    }
}
?>

<header>
    <nav class="flex">
        <ul class="navbar roundshadow">
            <li><a class="<?= $search_class_search ?>" href="search.php"><?= $page_structure["page"]["search"] ?></a></li>
            <li><a class="<?= $search_class_upload . $search_class_upload_form ?>" href="upload.php"><?= $page_structure["page"]["upload"] ?></a></li>
            <li><a class="<?= $search_class_index ?>" href="<?= $url_path ?>?logout=true"><?= $page_structure["page"]["logout"] ?></a></li>
            <?php
            if($is_Admin){
                ?>
                <li><a class="<?= $search_class_admin ?>" href="admin.php"><?= $page_structure["page"]["admin"] ?></a></li>
            <?php
            }
            ?>
        </ul>
    </nav>
</header>
