<?php
// Contains page and footer infos
$page_structure = require_once("page_structure.php");

# TODO: Braucht es diese info.php / queries??
// Queries for filtering and sorting
$info = require_once("info.php");

// Helper function to display image data html tag
require_once("utils.php");

// The value from POST, the form input field from type file
$userfile = $_FILES['image'];
// Mime content type which is allowed
$allowed_type = 'image/';
// The place the files will be uploaded to (currently a 'temporary' directory)
$upload_temp_dir = "../data/tmp/";
// The place the file will moves permanent
$uploaddir = "../data/";
# TODO: Eine maximale Uploadgrösse festlegen und prüfen!!
// Maximum filesize in BYTES (currently 2MB)
$maxsize = 2097152;
// The named image in html entities
$filename = htmlspecialchars($userfile['name']);
// Get the name of the file (including file extension)
$ext = strtolower(substr($filename, strpos($filename, '.'), strlen($filename) - 1));

if ($userfile) {

    if (isset($_POST['submit'])) {
        // The secure renamed image file
        $secure_filename = bin2hex(random_bytes(16)) . $ext;

        // Be sure we're dealing with an upload
        if (is_uploaded_file($userfile['tmp_name']) === false) {
            # TODO: Verbessern der Error Meldung/Ausgabe!!
            echo 'Error on upload: Invalid file definition';
            exit;
        }

        // Check if the filetype is allowed, if not DIE and inform the user.
        if (!strpos($userfile['type'], $allowed_type, 0) == 0) {
            // TODO: Verbessern der Error Meldung/Ausgabe!!
            echo 'Opps! Image Format not allowed!';
            exit;
        }

        // TODO: Hier braucht es eine Content-type verification!!
        // Check if we've uploaded an image file
        if (!empty($userfile) && ($userfile['error'] == UPLOAD_ERR_OK) && (strpos($userfile['type'], $allowed_type, 0) == 0)) {

            if (!is_writable($upload_temp_dir)) {
                // TODO: Verbessern der Error Meldung/Ausgabe!!
                die('You cannot upload to the specified directory, please CHMOD it to 777.');
            }

            // Insert it into our tracking along with the secure name
            move_uploaded_file($userfile['tmp_name'], $upload_temp_dir . $secure_filename);
        } else {
            // TODO: Verbessern der Error Meldung/Ausgabe!!
            echo 'Oops! no image!';
            exit;
        }
    }
}

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
                <?php showDataTag($userfile['type'], $secure_filename) ?>
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
                    // True, when required fields are filled
                    $complete_form = 1;
                    // If the user choose public post
                    $visibility_pub = 0;

                    // Save button to store the image
                    if (isset($_POST['save'])) {
                        $visibility_pub = ($_POST['public']) == 1 ? 1 : 0;
                        !empty($_POST['date']) ? $date = htmlspecialchars($_POST['date']) : $complete_form = false;
                        !empty($_POST['title']) ? $title = htmlspecialchars($_POST['title']) : $complete_form = false;
                        !empty($_POST['secure_filename']) ? $secure_filename = htmlspecialchars($_POST['secure_filename']) : $complete_form = false;
                        !empty($_POST['filename']) ? $filename = htmlspecialchars($_POST['filename']) : $complete_form = false;

                        // Moves the image to the data/ directory and in the database
                        if ($complete_form) {
                            if (!is_writable($uploaddir)) {
                                die('You cannot upload to the specified directory, please CHMOD it to 777.');
                            }

                            rename("../data/tmp/{$secure_filename}", "../data/{$secure_filename}");

                            $mime_type = mime_content_type("../data/$secure_filename");
                            $description = htmlspecialchars($_POST['description']);
                            $created_on = date('Y-m-d H:i:s');
                            $user_id = $_SESSION['user_id'];

                            try {
                                $stmt = $dbh->prepare("INSERT INTO posts (title, description, content_type, is_public, created_on, file_name, users_id, secure_file_name, date) VALUES (:title, :description, :content_type, :is_public, :created_on, :file_name, :users_id, :secure_file_name, :date)");

                                $stmt->bindParam(':title', $title);
                                $stmt->bindParam(':description', $description);
                                $stmt->bindParam(':content_type', $mime_type);
                                $stmt->bindParam(':is_public', $visibility_pub);
                                $stmt->bindParam(':created_on', $created_on);
                                $stmt->bindParam(':file_name', $filename);
                                $stmt->bindParam(':users_id', $user_id);
                                $stmt->bindParam(':secure_file_name', $secure_filename);
                                $stmt->bindParam(':date', $date);

                                $stmt->execute();
                                # TODO: Prüfen ob da nicht ein Security - Bug steckt!!
                                // Get the id of the last insert
                                $post_id = $dbh->lastInsertId();
                                echo $post_id;
                            } catch (PDOException $e) {
                                # TODO: Error Meldung soll in Logger output!!
                                echo $e;
                            }
                            header("location: detailView.php?id=$post_id");
                            exit;
                        } else {
                            // TODO: Verbessern der Error Meldung/Ausgabe!!
                            echo 'Oops! Fill required fields.';
                            header("refresh: 12; location: formUpload.php");
                            exit;
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