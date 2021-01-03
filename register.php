<?php
// Presents base services
require_once("html/info.php");

// Contains page and footer infos
$page_structure = require_once("html/page_structure.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="html/css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["register"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php
            // Register navbar
            require_once("html/snippets/header_register.php");
            ?>
            <div class="block setDown">
                <h1>Register</h1>
            </div>
            <!-- Input fields 'email' and 'password' & the button 'submit' -->
            <form class="flex" method="post">
                <input class="lightFont login" type="email" placeholder="email" name="email" required minlength="6" />
                <input class="lightFont login" type="password" name="password" placeholder="password" required minlength="8" maxlength="255" />
                <button class="btn" type="submit" name="register_user">submit</button>
            </form>
            <div class="push-notification-error">
                <?php
                // Database query to register
                require_once('html/queries/register.query.inc.php')
                ?>
            </div>
        </div>

        <?php
        // Footer
        require_once("html/snippets/footer.php");
        ?>
    </div>
</body>

</html>