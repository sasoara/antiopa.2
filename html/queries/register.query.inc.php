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
        //make user input harmless
        $uemail = htmlspecialchars($_POST['email']);
        $upassword = htmlspecialchars($_POST['password']);

        // Check regex match of email and password
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $uemail) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $upassword)) {
            $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
        } else {

            // Select users from the database to check for duplex users.
            try{
                $stmt = $dbh->prepare("SELECT users.email FROM users WHERE users.email = :email LIMIT 1");
                $stmt->bindParam(':email', $uemail);
                $stmt->execute();
                $db_email_result = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
            $error .= $e;
            }

            // Insert registering user into database, if he are not existing yet.
            if (!$db_email_result) {
                try{
                $stmt = $dbh->prepare("INSERT INTO users (email, pwd) values (:email, :pwd_hash)");
                //set email
                $stmt->bindParam(':email', $uemail);

                // hash, salt(by default since php7) and set email
                $pwd_hash = password_hash($upassword, PASSWORD_DEFAULT);
                $stmt->bindParam(':pwd_hash', $pwd_hash);

                // execute the insert statement
                $stmt->execute();
                $db_array_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['email'] = $uemail;
                $_SESSION['user_id'] = $db_array_results['id'];
                $_SESSION['stay_logged_in'] = false;
                header('location: html/search.php');
                } catch (PDOException $e) {
                    $error .= $e;
                }
            } else {
                $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
            }
        }
    }
    if (!empty($error)) {
        # TODO: Logger!!
        debug_to_console($error);
    }
}
?>