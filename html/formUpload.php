<?php
# container for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");
$info = require_once("info.php");
include("utils.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["formUpload"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php // header
            include_once("snippets/header.php");
            ?>
            <div class="block">
                <?php
                //check if there is a file
                if (!empty($_FILES)) {
                    $uploads_dir = '../data/';
                    // check if the upload succeed
                    if ($_FILES["files"]["error"] == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES["files"]["tmp_name"];
                        $filename = basename($_FILES["files"]["name"]);
                        //think here could be a security problem because data has all chmod
                        $secure_filename = bin2hex(random_bytes(32));
                        // save/move the file in the tmp file to delete it later 
                        $secure_filename = "tmp/$secure_filename";
                        //deletes every file left in the tmp dir
                        array_map('unlink', array_filter((array) glob("../data/tmp/*")));
                        move_uploaded_file($tmp_name, "$uploads_dir/$secure_filename");
                        $mime_type = mime_content_type("$uploads_dir/$secure_filename");
                        showDataTag($mime_type, $secure_filename, $filename);
                    } else {
                        debug_to_console(" ---error: " . $_FILES['files']['error']);
                    }

                    //IF WE WANT TO HAVE A MULTIUPLOAD

                    // foreach ($_FILES["files"]["error"] as $key => $error) {
                    //     if ($error == UPLOAD_ERR_OK) {
                    //         $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
                    //         // basename() kann Directory-Traversal-Angriffe verhindern;
                    //         // weitere Validierung/Bereinigung des Dateinamens kann angebracht sein
                    //         $name = basename($_FILES["pictures"]["name"][$key]);
                    //         move_uploaded_file($tmp_name, "$uploads_dir/$name");
                    //     }
                    // }
                }
                ?>
                <div class="pageheight">
                    <?php // left container with title and description field
                    ?>
                    <form method="post" id="postForm">
                        <div class="inlineblock">
                            <input type="hidden" name="secure_filename" value="<?= $secure_filename ?>" required>
                            <input type="hidden" name="filename" value="<?= $filename ?>" required>
                            <input tabindex="1" placeholder="Title" type="text" name="title" class="lightFont blocknormal" required>
                            <textarea tabindex="2" placeholder="Description" type="text" name="description" class="lightFont blocknormal"></textarea>
                        </div>
                        <?php // right container with date and tag field
                        ?>
                        <div class="inlineblock">
                            <div class="date">
                                <input tabindex="3" type="date" name="date" class="lightFont blocknormal" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <textarea tabindex="4" type="text" name="tags" placeholder="tags (separated by space; without '#')" class="blocknormal lightFont"></textarea>
                        </div>
                        <?php // cancel and save buttons 
                        ?>
                        <div class="inlineblock">
                            <input type="checkbox" name="public" value="1">
                            <label for="public" class="lightFont">public</label>
                        </div>
                        <div class="block margin2">

                            <?php // deletes specified file by clicking on cancel and redirects you to the upload.php page
                            ?>
                            <button tabindex="6" onclick="location.href='upload.php?delete=<?php echo $secure_filename ?>'" class="btn" name="cancel">Cancel</button>

                            <?php // TODO: scave button saves datas in db 
                            ?>
                            <button tabindex="5" type="submit" class="btn" name="save">Save</button>
                        </div>
                    </form>
                    <?php
                    $complete_form =1;
                    $public = 0;
                    if (isset($_POST['save'])) {
                        $public = ($_POST['public']) == 1 ? 1 : 0;
                        !empty($_POST['filename']) ? $filename = basename(htmlspecialchars($_POST['filename'])) : $complete_form = false;
                        !empty($_POST['secure_filename']) ? $secure_filename = basename(htmlspecialchars($_POST['secure_filename'])) : $complete_form = false;
                        !empty($_POST['date']) ? $date = htmlspecialchars($_POST['date']) : $complete_form = false;
                        !empty($_POST['title']) ? $title = htmlspecialchars($_POST['title']) : $complete_form = false;
                        if ($complete_form) {
                            rename("../data/tmp/$secure_filename", "../data/$secure_filename");
                            $mime_type = mime_content_type("../data/$secure_filename");
                            $description = htmlspecialchars($_POST['description']);
                            $tags = htmlspecialchars($_POST['tags']);
                            $tag_array = explode(' ', $tags);
                            //check if there are multiple spaces and replace them with a single space
                            $tag_array = preg_replace('!\s+!', '', $tag_array);
                            //remove empty elements from array
                            $tag_array = array_filter($tag_array);
                            $created_on = date('Y-m-d H:i:s');
                            $user_id = $_SESSION['user_id'];
                            $sql_post = "INSERT INTO posts (title, description, date, content_type, created_on, file_name, users_id, secure_file_name, is_public) values ('$title', '$description', '$date', '$mime_type', '$created_on', '$filename', '$user_id', '$secure_filename', '$public');";
                            $dbh->query($sql_post);
                            //get the id of the last insert 
                            // not sure if thats secure!
                            $post_id = $dbh->lastInsertId();
                            //go through all the tags and check if they already exist and insert them if not
                            foreach ($tag_array as $tag) {
                                $tag_query = "SELECT * FROM tags WHERE name = '$tag' LIMIT 1;";
                                $db_tags = $dbh->query($tag_query);
                                $tags_result = $db_tags->fetch(PDO::FETCH_ASSOC);
                                if ($tags_result['name'] !== $tag) {
                                    $sql_tag = "INSERT INTO tags (name) VALUES ('$tag');";
                                    $dbh->query($sql_tag);
                                    $tag_id = $dbh->lastInsertId();
                                } else {
                                    $tag_id = $tags_result['id'];
                                }
                                $sql_post_tag = "INSERT INTO posts_has_tags (posts_id, tags_id) VALUES ($post_id, $tag_id); ";
                                $dbh->query($sql_post_tag);
                            }
                            header("location: detailView.php?id=$post_id");
                            exit;
                        }
                        else {
                            header("location: formUpload.php");
                        }
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