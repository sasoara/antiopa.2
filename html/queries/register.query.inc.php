<?php

/**
 * This file helps to register an user.
 * It contains the query which saves an user (email) with a valid password.
 */

// Database configuration
require_once(__DIR__ . "/../../html/lib/db.php");
// Possible error message
$error = '';
// POST request method - check(login_user)
if (isset($_POST['register_user'])) {
    // Check the existence of email and password and if they are empty
    if ((!isset($_POST['email']) || !isset($_POST['password'])) || (empty(trim($_POST['email'])) || empty(trim($_POST['password'])))) {
        $error = "Missing email or password";
    } else {
        // Check regex match of email and password
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST['email']) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $_POST['password'])) {
            $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
        } else {
            // Select users from the database to check for duplex users.
            $stmt = $dbh->prepare("SELECT users.email FROM users WHERE users.email = :email LIMIT 1");
            // TODO: while we protect the query using a prepared statement, I would still escape to not leak an attacked email in the session on line 39
            $email = $_POST['email'];
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $db_email_result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Insert registering user into database, if he are not existing yet.
            if (!$db_email_result['email'] == $email) {
                $stmt = $dbh->prepare("INSERT INTO users (email, pwd) values (:email, :pwd_hash)");
                $email = $_POST['email'];
                $pwd_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':pwd_hash', $pwd_hash);
                $stmt->execute();
                $db_array_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $db_array_results['id'];
                $_SESSION['stay_logged_in'] = false;
                header('location: search.php');
            } else {
                $error = "Email already exists, try again!"; // TODO: Ist das eine gute / schlechte Fehlermeldung??
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