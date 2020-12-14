<?php
# container for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");
$info = require_once("info.php");
require_once("utils.php");

$secure_filename = '';
$filename = '';

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
            <?php
            // Header navbar
            require_once("snippets/header.php");
            ?>
            <div class="block">
                <?php
                // TODO: Hier braucht es eine Content-type verification!!
                // Check if there is a file
                if (!empty($_FILES)) {
                    $uploads_dir = '../data/';

                    // Check if the upload succeed
                    if ($_FILES["files"]["error"] == UPLOAD_ERR_OK) {
                        // Saves the temporary filename
                        $tmp_name = $_FILES["files"]["tmp_name"];
                        // Truncates the file type
                        $filename = basename($_FILES["files"]["name"]);
                        // TODO: Ist hier möglicherweise ein Security bug??
                        // think here could be a security problem because data has all chmod
                        $secure_filename = bin2hex(random_bytes(32));
                        // Saves and moves the file to the tmp directory to delete it later
                        $secure_filename = "tmp/$secure_filename";

                        // Deletes every file left in the tmp directoru
                        array_map('unlink', array_filter((array) glob("../data/tmp/*")));
                        // How to name the uploaded file and to which destination it should be moved
                        move_uploaded_file($tmp_name, "$uploads_dir/$secure_filename");
                        // The content type in text/plain
                        $mime_type = mime_content_type("$uploads_dir/$secure_filename");

                        // Returns the HTML tag of the dependent data type
                        $html_data_tag = showDataTag($mime_type, $secure_filename);
                        echo $html_data_tag;
                    } else {
                        // TODO: Verbessern der Error Meldung/Ausgabe!!
                        debug_to_console(" ---error: " . $_FILES['files']['error']);
                    }
                }
                ?>
                <div class="pageheight">
                    <?php // left container with title and description field
                    ?>
                    <form method="post" id="postForm">
                        <div class="inlineblock">
                            <input type="text" name="secure_filename" value="<?= $secure_filename ?>" hidden>
                            <input type="text" name="filename" value="<?= $filename ?>" hidden>
                            <input tabindex="1" placeholder="Title" type="text" name="title" class="lightFont blocknormal" required>
                            <textarea tabindex="2" placeholder="Description" name="description" class="lightFont blocknormal"></textarea>
                        </div>
                        <?php // right container with date field
                        ?>
                        <div class="inlineblock">
                            <div class="date">
                                <input tabindex="3" type="date" name="date" class="lightFont blocknormal" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <?php // cancel and save buttons
                        ?>
                        <div class="inlineblock">
                            <input type="checkbox" name="public" value="1">
                            <label id="public" class="lightFont">public</label>
                        </div>
                        <div class="block margin2">

                            <?php // deletes specified file by clicking on cancel and redirects you to the upload.php page
                            ?>
                            <button tabindex="6" onclick="location.href='upload.php?delete=<?php echo $secure_filename ?>'" class="btn" name="cancel">Cancel</button>

                            <?php // TODO: saves button saves datas in db
                            ?>
                            <button tabindex="5" type="submit" class="btn" name="save">Save</button>
                        </div>
                    </form>
                    <?php
                    $complete_form = 1;
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
                            $created_on = date('Y-m-d H:i:s');
                            $user_id = $_SESSION['user_id'];
                            $sql_post = "INSERT INTO posts (title, description, date, content_type, created_on, file_name, users_id, secure_file_name, is_public) values ('$title', '$description', '$date', '$mime_type', '$created_on', '$filename', '$user_id', '$secure_filename', '$public');";
                            $dbh->query($sql_post);
                            //get the id of the last insert
                            // not sure if thats secure!
                            $post_id = $dbh->lastInsertId();
                            header("location: detailView.php?id=$post_id");
                            exit;
                        } else {
                            header("location: formUpload.php");
                        }
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