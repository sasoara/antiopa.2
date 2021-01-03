<?php

date_default_timezone_set("Europe/Zurich");

$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$search_class_search = $url_path == "/html/search.php" ? "activeSite" : "";
$search_class_upload = $url_path == "/html/upload.php" ? "activeSite" : "";
$search_class_upload_form = $url_path == "/html/formUpload.php" ? "activeSite" : "";
$search_class_index = $url_path == "/index.php" ? "activeSite" : "";
$search_class_admin = $url_path == "/html/admin.php" ? "activeSite" : "";

// Sessionhandling
require_once("session_handling.php");

// Presents base services
require_once("info.php");

$is_Admin = false;
if (isset($_SESSION['email'])) {
    $uemail = $_SESSION['email'];
    try {
        // Selects users from the database to check the fit.
        $stmt = $dbh->prepare("SELECT ur FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $uemail);
        $stmt->execute();
        $db_array_results = $stmt->fetch(PDO::FETCH_ASSOC);
        $is_Admin = $db_array_results['ur'] == "admin" ? true : false;
    } catch (PDOException $e) {
        # TODO: Logger!!
        debug_to_console($e);
    }
}
?>

<header>
    <nav class="flex">
        <ul class="navbar roundshadow">
            <li><a class="<?= $search_class_search ?>" href="search.php"><?= $page_structure["page"]["search"] ?></a></li>
            <li><a class="<?= $search_class_upload . $search_class_upload_form ?>" href="upload.php"><?= $page_structure["page"]["upload"] ?></a></li>
            <li><a class="<?= $search_class_index ?>" href="<?= $url_path ?>?logout=true"><?= $page_structure["page"]["logout"] ?></a></li>
            <?php
            if ($is_Admin) {
            ?>
                <li><a class="<?= $search_class_admin ?>" href="admin.php"><?= $page_structure["page"]["admin"] ?></a></li>
            <?php
            }
            ?>
        </ul>
    </nav>
</header>