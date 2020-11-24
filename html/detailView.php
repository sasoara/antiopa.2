<?php
require_once("lib/db.php");
# container for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");
$info = require_once("info.php");

if (!empty($_GET['id'])) {
    $postId = htmlspecialchars($_GET['id']);
    $sql = "SELECT DISTINCT posts.title, posts.description, posts.date, tags.name, posts.file_name, posts.content_type, posts.secure_file_name
        FROM posts 
        LEFT JOIN posts_has_tags ON posts.id = posts_has_tags.posts_id
        LEFT JOIN tags ON posts_has_tags.tags_id = tags.id
        WHERE posts.id = $postId;";

    $posts = $dbh->query($sql);
    $tag_array = array();
# $post[0] title, $post[1] description, $post[2] date, $post[3] tag
foreach ($posts as $post) {
    $title = $post[0];
    $description = $post[1];
    $date = $post[2];
    array_push($tag_array, $post[3]);
    $file_name = $post[4];
    $content_type = $post[5];
    $secure_file_name = $post[6];
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
            include_once("snippets/header.php");
            ?>
            <div class="block">
                    <a href="showImg.php?path=<?= $secure_file_name ?>&filename=<?= $file_name?>">
                        <div class="flex">
                            <?php //  new GET request for show file with filename
                            include("utils.php");
                            $mime_type = $post['content_type'];
                            showDataTag($mime_type, $secure_file_name, $file_name);
                            ?>
                        </div>
                    </a>
                <div class="infobox">
                    <div class="flexnormal">
                        <?php //  title and description are read from the foreach loop, on the beginning of this page
                        ?>
                        <h1 class="title"><?= $title ?></h1>
                        <p class="date"><?= $date ?></p>
                    </div>
                    <?php
                    //check if there is a description, and if there is show it
                    if ($description!='') {
                        ?>
                    <div>
                        <h3>Description</h3>
                        <p><?= $description ?></p>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                    //check if there are tags, and if there are show them
                    if ($tag_array[0]!='') {
                        ?>
                    <div class="tagbox">
                        <h3>Tags</h3>
                        <?php foreach ($tag_array as $tag) {
                            ?>
                        <a href="search.php?term=%23<?= $tag ?>">#<?= $tag ?>
                        </a>
                        <?php
                        }
                        ?>
                        </a>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php // footer
        include_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>