<?php
// Presents base services
$info = require_once("info.php");

// Contains page and footer infos
$page_structure = require_once("page_structure.php");

//Helper function to display image data html tag
require_once("snippets/display_image.php");

if (!empty($_GET['id'])) {
    $stmt = $dbh->prepare("SELECT DISTINCT title, description, date, file_name, content_type, secure_file_name FROM posts WHERE id = :id");
    $postId = htmlspecialchars($_GET['id']);
    $stmt->bindParam(':id', $postId);
    $stmt->execute();
    $post_result = $stmt->fetch(PDO::FETCH_ASSOC);
}

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
            <?php // header
            require_once("snippets/header.php");
            ?>
            <div class="block">
                <a href="showImg.php?path=../<?= $post_result['file_name'] ?>">
                    <?php debug_to_console('post_result: ' . $post_result['file_name']); ?>
                    <div class="flex">
                        <?php //  new GET request for show file with filename
                        $mime_type = $post['content_type'];
                        displayImage($mime_type, $file_name);
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