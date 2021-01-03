<?php

/**
 * This file helps to login an existing user.
 * It contains the query that verifies the user's valid email address and password.
 */

// Presents base services
$info = require_once("info.php");



// Possible user output
$uout = '';

// POST request method - check(delete)
if (isset($_POST['delete'])) {

    $is_Admin = false;
    if(isset($_SESSION['email'])) {
        $uemail = $_SESSION['email'];
        try {
// Selects users from the database to check the fit.
            $stmt = $dbh->prepare("SELECT ur FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $uemail);
            $stmt->execute();
            $db_array_results = $stmt->fetch(PDO::FETCH_ASSOC);
            if($db_array_results['ur']=="admin"){

                // Image dir path to take pictures from
                $image_dir = "../data/";

                //seaches all posts from db
                $SQL = "SELECT secure_file_name FROM posts WHERE 1=1;";
                $stmt = $dbh->prepare($SQL);
                // Executing the sql query
                $stmt->execute();
                $posts = $stmt->fetchAll();

                //deleates all pictures from data
                if (!empty($posts)) {
                    foreach ($posts as $post) {
                        unlink($image_dir . $post['secure_file_name']);
                    }
                }

                //deleats all posts from db
                $SQL = "DELETE FROM posts WHERE 1=1;";
                $stmt = $dbh->prepare($SQL);
                // Executing the sql query
                $stmt->execute();

                $uout .= "all deleted sucsessful";

            }else{
                error_log("Unoutorised acses prevented in admin.query.inc");
                header('location: search.php');
            }
        } catch (PDOException $e) {
            error_log("SQL error in admin.query.inc: " . $e);
            header('location: search.php');
        }
    }

    if (!empty($uout)) {
?>
        <div class="block">
            <?php
            echo ($uout);
            ?>
        </div>
<?php
    }
}
?>