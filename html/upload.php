<?php
// Presents base services
$info = require_once("info.php");

// Contains page and footer infos
$page_structure = require_once("page_structure.php");


// TODO: Issue #7
// saved image is deleted when user clicks cancel at formUpload.php
if (!empty($_GET['delete'])) {
    $uploads_temp_dir = '../data/tmp/';
    # TODO: Technically, could probably attack the `delete` parameter with `../../etc/passwd` or similar traversal queries
    $filename =  htmlspecialchars($_GET['delete']);
    unlink($uploads_temp_dir . $filename);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["upload"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php
            // Header navbar
            require_once("snippets/header.php");
            ?>
            <div class="block setDown">
                <h1 class="flex">Upload</h1>

                <?php // Button that allows user to choose an image
                ?>
                <form action="validations/upload_validation.php" enctype="multipart/form-data" method="POST">
                    <?php
                    // TODO: 'accept' Attribut ist auch nicht sicher vor XSS (Dom-based)!!
                    ?>
                    <label class="btn fileContainer">browse
                        <input name="image" type="file" accept="image/*">
                    </label>
                    <label class="btn fileContainer">submit
                        <input name="submit" type="submit">
                    </label>
                </form>
            </div>
        </div>

        <?php
        // Footer
        require_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>