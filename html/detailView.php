<?php
require_once("lib/db.php");
# container for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");
$info = require_once("info.php");

if (!empty($_GET['id'])) {
    $postId = htmlspecialchars($_GET['id']);
    // TODO: my understanding is that `htmlspecialchars` does not escape `;`, so I could technically break the query with `id=1;DROP TABLE posts`. Using a prepared statement would be safer
    $sql = "SELECT DISTINCT posts.title, posts.description, posts.date, posts.file_name, posts.content_type, posts.secure_file_name
        FROM posts
        WHERE posts.id = $postId;";

    $posts = $dbh->query($sql);
    # $post[0] title, $post[1] description, $post[2] date
    foreach ($posts as $post) {
        $title = $post[0];
        $description = $post[1];
        $date = $post[2];
        $file_name = $post[3];
        $content_type = $post[4];
        $secure_file_name = $post[5];
    }
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
                <a href="showImg.php?path=<?= $secure_file_name ?>&filename=<?= $file_name ?>">
                    <div class="flex">
                        <?php //  new GET request for show file with filename
                        require_once("utils.php");
                        $mime_type = $post['content_type'];
                        showDataTag($mime_type, $secure_file_name, $file_name);
                        ?>
                    </div>
                </a>
                <div class="infobox">
                    <div class="flexnormal">
                        <?php //  title and description are read from the foreach loop, on the beginning of this page
                        ?>
                        <?php // TODO: if either value is corrupted in the DB, then we could risk an XSS. It would be safer to wrap the output in `htmlspecialchars` ?>
                        <h1 class="title"><?= $title ?></h1>
                        <p class="date"><?= $date ?></p>
                    </div>
                    <?php
                    //check if there is a description, and if there is show it
                    if ($description != '') {
                    ?>
                        <div>
                            <h3>Description</h3>
                            <?php // TODO: same here ?>
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