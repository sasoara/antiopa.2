<?php
$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$search_class_index = $url_path == "/index.php" ? "activeSite" : "";
$search_class_register = $url_path == "/register.php" ? "activeSite" : "";

//check if the session is not active or set and then start the session
if (session_status() !== PHP_SESSION_ACTIVE or session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<?php // Menu
?>
<header>
    <nav class="flex">
        <ul class="navbar navbar_register roundshadow">
            <?php // TODO: OWASP #5 Fehlerhafte Zugriffskontrolle
            ?>
            <li><a class="<?= $search_class_index ?>" href="../index.php"><?= $page_structure["page"]["index"] ?></a></li>
            <li><a class="<?= $search_class_register ?>" href="html/register.php"><?= $page_structure["page"]["register"] ?></a></li>
        </ul>
    </nav>
</header>