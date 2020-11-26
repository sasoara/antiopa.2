<?php
/**
 * This file helps to login an existing user.
 * It contains the query that verifies the user's valid email address and password.
 */

// Database configuration
require_once("./lib/db.php");
// Possible error message
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
        // Check regex match of email and password
        if (!preg_match($valid_email_regex, $_POST['email']) || !preg_match($valid_pw_regex, $_POST['password'])) {
            $error = "Not valid. Wrong email or password. Use specialchars, uppercase, numbers";
        } else {
            // Selects users from the database to check the fit.
            $stmt = $dbh->prepare("SELECT email, pwd, id FROM users WHERE email = :email");
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $db_array_results = $stmt->fetch(PDO::FETCH_ASSOC);

            // Checks the entered password, which must match the entered user.
            if (password_verify($password, $db_array_results['pwd'])) {
                session_regenerate_id(true);

                // Session variables are set for the registered user.
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $db_array_results['id'];
                $_SESSION['stay_logged_in'] = htmlspecialchars($_POST["stayLoggedIn"]);
                header('location: search.php');
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