<?php
// Presents base services
require_once(__DIR__ . "/html/info.php");

// Contains page and footer infos
$page_structure = require_once(__DIR__ . "/html/page_structure.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="html/css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["index"] ?>
    </title>
</head>

<body class="block">
    <?php
    // Register navbar
    require_once(__DIR__ . "/html/snippets/header_register.php");
    ?>
    <div>
        <!-- Logo -->
        <img class="logo" src="html/imgs/antiopa.svg" alt="logo">
    </div>
    <form class="flex" method="post">
        <input class="lightFont login" type="email" name="email" placeholder="Email" required minlength="6" />
        <input class="lightFont login" type="password" name="password" placeholder="Password" required minlength="8" maxlength="255" />
        <input type="checkbox" name="stayLoggedIn" value="true">
        <label id="stayLoggedIn" class="lightFont">stay logged in</label>
        <button class="btn" name="login_user" type="submit">login</button>
    </form>
    <?php
    // Database query to sign in
    require_once(__DIR__ . "/html/queries/index.query.inc.php")
    ?>
</body>

</html>