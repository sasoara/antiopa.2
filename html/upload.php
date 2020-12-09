<?php

# container for Antiopa web-page, includes header and footer
$page_structure = require_once("page_structure.php");
$info = require_once("info.php");

// TODO: Issue #7
// saved image is deleted when user clicks cancel at formUpload.php
if (!empty($_GET['delete'])) {
    $uploads_dir = '../data/';
    $file =  htmlspecialchars($_GET['delete']);
    unlink($uploads_dir . $file);
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
            <?php // header
            include_once("snippets/header.php");
            ?>
            <div class="block setDown">
                <h1 class="flex">Upload</h1>

                <?php // user is forwarded to the page formUpload.php by uploading a file
                ?>
                <form action="formUpload.php" enctype="multipart/form-data" method="POST">
                    <label class="btn fileContainer">browse
                        <?php // TODO: XSS Reflected!! onchange ist angreifbar.
                        ?>
                        <input name="files" onchange="this.form.submit()" type="file" accept="application/*, image/*, video/*">
                    </label>
                </form>
            </div>
        </div>
        <?php
        include_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>