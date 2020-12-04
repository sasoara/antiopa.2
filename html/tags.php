<?php
require_once("lib/db.php");
# containers for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["tags"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php // header
            include_once("snippets/header.php");
            ?>

            <?php // tags
            $email = $_SESSION['email'];

            $sql = "SELECT DISTINCT *

                    from tags as t

                    LEFT JOIN posts_has_tags AS pt ON t.id = pt.tags_id

                    LEFT JOIN posts AS p ON pt.posts_id = p.id

                    LEFT JOIN users AS u ON p.users_id = u.id

                    WHERE p.is_public like 1 or u.email like ('$email');";

            # tags ordered by name ascending
            $tags = $dbh->query($sql);

            # counter that counts all saved tags in db for different tag-col preview
            $counting_tags = $tags->rowCount();
            $size_on_tags = 'tag-col-';
            $size_on_tags = $counting_tags <= 50 ? 'tag-col-small' : 'tag-col-large';
            ?>
            <h1 class="flex setDown">Tags</h1>
            <div class="amount-of-tags">Amount of tags: <?= $counting_tags ?></div>
            <div>
                <section class="tag-col <?= $size_on_tags ?>">
                    <?php
                    foreach ($tags as $tag) {
                    ?>
                        <a href="search.php?term=%23<?= $tag[1] ?>">#<?= $tag[1] ?>
                        </a>
                    <?php
                    } // %23 = # in UTF-8 (https://www.urlencoder.org/)
                    ?>
                </section>
            </div>
        </div>

        <?php // footer
        include_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>