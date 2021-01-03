<?php

/**
 * This file helps to register an user.
 * It contains the query which saves an user (email) with a valid password.
 */

// Presents base services
require_once("html/info.php");

// Possible error message
$error = '';
// POST request method - check(login_user)
if (isset($_POST['register_user'])) {
    // Check the existence of email and password and if they are empty
    if ((!isset($_POST['email']) || !isset($_POST['password'])) || (empty(trim($_POST['email'])) || empty(trim($_POST['password'])))) {
        $error = "Missing email or password";
    } else {
        // Make user input harmless
        $uemail = htmlspecialchars($_POST['email']);
        $upassword = htmlspecialchars($_POST['password']);

        // Check regex match of email and password
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $uemail) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $upassword)) {
            $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
        } else {
            // Select users from the database to check for duplex users.
            try {
                $SQL = "SELECT users.email FROM users WHERE users.email = :email LIMIT 1";
                $stmt = $dbh->prepare($SQL);
                $stmt->bindParam(':email', $uemail);
                $stmt->execute();
                $db_email_result = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                # TODO: Logger!!
                debug_to_console($e);
            }

            // Insert registering user into database, if he are not existing yet.
            if (!$db_email_result) {
                try {
                    $SQL = "INSERT INTO users (email, pwd) VALUES (:email, :pwd_hash)";
                    $stmt = $dbh->prepare($SQL);
                    // Bind the email
                    $stmt->bindParam(':email', $uemail);

                    // Hash, salt(by default since php7) and set email
                    $pwd_hash = password_hash($upassword, PASSWORD_DEFAULT);
                    $stmt->bindParam(':pwd_hash', $pwd_hash);

                    // Execute the prepared statement
                    $stmt->execute();
                    // Get the last registered user id
                    $post_id = $dbh->lastInsertId();
                    $_SESSION['email'] = $uemail;
                    $_SESSION['user_id'] = $post_id;
                    $_SESSION['stay_logged_in'] = false;
                    header('location: html/search.php');
                } catch (PDOException $e) {
                    # TODO: Logger!!
                    debug_to_console($e);
                }
            } else {
                $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
            }
        }
    }
    if (!empty($error)) {
        # TODO: Audit!!
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