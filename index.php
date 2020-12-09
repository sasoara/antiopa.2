<?php
// Contains page and footer infos
$page_structure = require_once("page_structure.php");
// TODO: Braucht es diese info.php / queries??
$info = require_once("info.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["index"] ?>
    </title>
</head>

<body class="block">
    <?php
    // Register navbar
    include_once("snippets/header_register.php");
    ?>
    <div>
        <!-- Logo -->
        <img class="logo" src="imgs/antiopa.svg" alt="logo">
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
    require_once('queries/index.query.inc.php')
    ?>
</body>

</html>