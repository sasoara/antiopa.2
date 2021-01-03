<?php
// Presents base services
$ino = require_once("info.php");

// Contains page and footer infos
$page_structure = require_once("page_structure.php");

?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="css/stylesheet.css">
                <title>
                    <?php echo $page_structure["page"]["admin"]; ?>
                </title>
            </head>

            <body>
            <div class="page-container">
                <div class="content">
                    <?php
                    // Header navbar
                    require_once("snippets/header.php");

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
                        ?>

                    <div class="block setDown">
                        <h1 class="flex">Admin</h1>

                        <?php // Button that allows admin to deleat all image
                        ?>
                        <h2>This Button deleats all pictures no Saving after this!</h2>
                        <form enctype="multipart/form-data" method="POST">
                            <button type="submit" class="btn" name="delete">Delete all</button>
                        </form>
                        <?php
                        // Database query to deleate all
                        require_once("queries/admin.query.inc.php")

                        ?>
                    </div>

                    <?php
                            }else{
                                error_log("prevented unoutorised acses try at admin");
                                header('location: search.php');
                            }
                        } catch (PDOException $e) {
                            error_log("SQL error in admin: " . $e);
                            header('location: search.php');
                        }
                    }else{
                        error_log("no session email set at");
                        header('location: search.php');
                    }
                    ?>

                </div>

                <?php
                // Footer
                require_once("snippets/footer.php");
                ?>
            </div>
            </body>
            </html>
