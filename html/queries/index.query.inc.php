<?php

/**
 * This file helps to login an existing user.
 * It contains the query that verifies the user's valid email address and password.
 */

// Database configuration
require_once(__DIR__ . "/../../html/lib/db.php");
// Possible user output error message
$error = '';
// Regex for email and password
$valid_email_regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
$valid_pw_regex = "/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/";
// POST request method - check(login_user)
if (isset($_POST['login_user'])) {
    // Check the existence of email and password and if they are empty
    if ((!isset($_POST['email']) || !isset($_POST['password'])) || (empty(trim($_POST['email'])) || empty(trim($_POST['password'])))) {
        $error = "Missing email or password.";
    } else {
        //prepere variables fore usage
        $uemail = htmlspecialchars($_POST['email']);
        $upassword = htmlspecialchars($_POST['password']);
        $ustayLoggedIn = is_bool($_POST["stayLoggedIn"])? $_POST["stayLoggedIn"] : false; error_log("Invalid value in stayLoggedIn at index.query.inc");

        // Check regex match of email and password
        if (!preg_match($valid_email_regex, $uemail) || !preg_match($valid_pw_regex, $upassword)) {
            $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
        } else {
            // Selects users from the database to check the fit.
            $stmt = $dbh->prepare("SELECT email, pwd, id FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $uemail);
            $stmt->execute();
            $db_array_results = $stmt->fetch(PDO::FETCH_ASSOC);

            // Checks the entered password, which must match the entered user.
            if (password_verify($upassword, $db_array_results['pwd'])) { //TODO: Da versteckt sich ein Bug!!
                session_regenerate_id(true);

                // Session variables are set for the registered user.
                $_SESSION['email'] = $uemail;
                $_SESSION['user_id'] = $db_array_results['id'];
                $_SESSION['stay_logged_in'] = $ustayLoggedIn;
                header('location: html/search.php');
            } else {
                $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
            }
        }
    }
    if (!empty($error)) {
?>
        <div class="block">
            <?php
            echo ($error);
            $error = '';
            ?>
        </div>
<?php
    }
}
?>