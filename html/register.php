<?php
require_once("./lib/db.php");
# container for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");
$info = require_once("info.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["register"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php // header
            include_once("snippets/header_register.php");
            ?>
            <div class="block setDown">
                <h1>Register</h1>
            </div>
            <?php // input fields 'email' and 'password' and the button 'submit'
            ?>
            <form class="flex" method="post">
                <input class="lightFont login" type="email" placeholder="email" name="email" required="true" minlength="6" />
                <input class="lightFont login" type="password" name="password" placeholder="password" required="true" minlength="8" maxlength="255" />
                <button class="btn" type="submit" name="register_user">submit</button>
            </form>
            <div class="push-notification-error">
                <?php // db register query
                require_once('queries/register.query.inc.php')
                ?>
            </div>
        </div>

        <?php // footer
        include_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>