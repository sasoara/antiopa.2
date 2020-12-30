<?php
// Presents base services
$info = require_once("info.php");

// Contains page and footer infos
$page_structure = require_once("page_structure.php");

//Helper function to display image data html tag
require_once("snippets/display_image.php");

# TODO: Validierung / Zugriffsrecht auf Bild privat / öffentlich

if (!empty($_GET['id'])) {
    $stmt = $dbh->prepare("SELECT DISTINCT title, description, date, file_name, content_type, secure_file_name FROM posts WHERE id = :id");
    $postId = htmlspecialchars($_GET['id']);
    $stmt->bindParam(':id', $postId);
    $stmt->execute();
    $post_result = $stmt->fetch(PDO::FETCH_ASSOC);
}

$title = $post_result['title'];
$description = $post_result['description'];
$date = $post_result['date'];
$filename = $post_result['secure_file_name'];
$mime_type = $post_result['content_type'];

$image_dir = "../data/";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["detailView"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php // Header
            require_once("snippets/header.php");
            ?>
            <div class="block">
                <a href="showImg.php?path=../<?= $filename ?>">
                    <div class="flex">
                        <?php //  Show image
                        displayImage($mime_type, $image_dir . $filename);
                        ?>
                    </div>
                </a>
                <div class="infobox">
                    <div class="flexnormal">
                        <?php //  title and description are read from the foreach loop, on the beginning of this page
                        ?>
                        <?php // TODO: if either value is corrupted in the DB, then we could risk an XSS. It would be safer to wrap the output in `htmlspecialchars`
                        ?>
                        <h1 class="title"><?= $title ?></h1>
                        <p class="date"><?= $date ?></p>
                    </div>
                    <?php
                    //check if there is a description, and if there is show it
                    if ($description != '') {
                    ?>
                        <div>
                            <h3>Description</h3>
                            <?php // TODO: same here
                            ?>
                            <p><?= $description ?></p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php // footer
        require_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>