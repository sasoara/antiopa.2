<?php
require_once("./lib/db.php");
$page_structure = require_once("page_structure.php");
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
    <?php // header
    include_once("snippets/header_register.php");
    ?>
    <?php // company logo
    ?>
    <div>
        <img class="logo" src="imgs/antiopa.svg" alt="logo">
    </div>
    <form class="flex" method="post">
        <input class="lightFont login" type="email" name="email" placeholder="Email" required="true" minlength="6" />
        <input class="lightFont login" type="password" name="password" placeholder="Password" required="true" minlength="8" maxlength="255" />
        <input class="" type="checkbox" name="stayLoggedIn" value="true">
        <label for="stayLoggedIn" class="lightFont">stay logged in</label>
        <button class="btn" name="login_user" type="submit">login</button>
    </form>
    <?php // db sign in query
    require_once('queries/index.query.inc.php')
    ?>
</body>

</html>