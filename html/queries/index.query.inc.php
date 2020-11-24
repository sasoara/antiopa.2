<?php
/**
 * This file helps to login an existing user.
 * It contains the query which check the users valid email and his valid password.
 */
$error = '';
// POST Request-Method check(login_user)
if (isset($_POST['login_user'])) {
    // Check the existence of email and password and if they are empty
    if ((!isset($_POST['email']) || !isset($_POST['password'])) || (empty(trim($_POST['email'])) || empty(trim($_POST['password'])))) {
        $error = "missing email or password";
    } else {
        // Check regex match of email and password
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST['email']) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $_POST['password'])) {
            $error = "not valid / wrong email or password specialchars, uppercase, numbers";
        } else {
            // Select users from the database to check the accuracy.
            $stmt = $dbh->prepare("SELECT users.email, users.pwd, users.id FROM users WHERE users.email = :email");
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
                $error = "Wrong password!";
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