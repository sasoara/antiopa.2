<?php
// Presents base services
$info = require_once("info.php");

// Contains page and footer infos
$page_structure = require_once("page_structure.php");

//Helper function to display image data html tag
require_once("snippets/display_image.php");

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

            $postId = htmlspecialchars($_GET['id']);
            $uemail = $_SESSION['email'];

            try {
                if (!empty($postId)) {

                    $SQL = "SELECT p.title, p.description, p.date, p.file_name, p.content_type, p.secure_file_name 
                                FROM posts AS p LEFT JOIN users AS u ON p.users_id = u.id 
                                WHERE p.id = :id AND (p.is_public = 1 OR u.email = :email);";

                    $stmt = $dbh->prepare($SQL);

                    $stmt->bindParam(':id', $postId);
                    $stmt->bindParam(':email', $uemail);

                    $stmt->execute();
                    $post_result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if(!empty($post_result['title'])){
                    // read out of
                    $title = $post_result['title'];
                    $description = $post_result['description'];
                    $date = $post_result['date'];
                    $filename = $post_result['secure_file_name'];
                    $mime_type = $post_result['content_type'];

                    $image_dir = "../data/";
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

                <?php
                    }else{
                        # TODO: Logger!! no image found
                        header('location: search.php');
                    }
                }else{
                    # TODO: Logger!!
                    debug_to_console("no post id");
                }
            } catch (PDOException $e) {
                # TODO: Logger!!
                debug_to_console($e);
            }
            ?>

        </div>

        <?php // footer
        require_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>