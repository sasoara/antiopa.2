<?php
// Presents base services
require_once("info.php");

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
        <?php echo $page_structure["page"]["formUpload"]; ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">
            <?php
            // Header navbar
            require_once("snippets/header.php");

            // Session data
            $filename = $_SESSION['data'][0];
            $secure_filename = $_SESSION['data'][1];
            $mime_type = $_SESSION['data'][2];

            $upload_tmp_dir = "../data/tmp/";

            ?>
            <div class="block">
                <?php
                // Image
                displayImage($mime_type, $upload_tmp_dir . $secure_filename);
                ?>
                <div class="pageheight">
                    <?php // Left container with title and description field
                    ?>
                    <form action="validations/uploadForm_validation.php" method="post" enctype="application/x-www-form-urlencoded">
                        <div style="display: inline-flex">
                            <div class="inlineblock">
                                <input tabindex="1" placeholder="Title" type="text" name="title" class="lightFont blocknormal" required>
                                <textarea tabindex="2" placeholder="Description" name="description" class="lightFont blocknormal"></textarea>
                            </div>

                            <?php // Right container with date & public field
                            ?>
                            <div class="inlineblock">
                                <div class="date">
                                    <input tabindex="3" type="date" name="date" class="lightFont blocknormal" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div>
                                    <input type="checkbox" name="public">
                                    <label id="public" class="lightFont">public</label>
                                </div>
                            </div>
                        </div>

                        <?php // Cancel and save buttons
                        ?>
                        <div class="block margin2">

                            <?php // Deletes uploaded image
                            ?>
                            <button tabindex="6" type="submit" class="btn" name="cancel">Cancel</button>

                            <?php // Saves image and redirects to detailView.php
                            ?>
                            <button tabindex="5" type="submit" class="btn" name="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php // Footer
        require_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>