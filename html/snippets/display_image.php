<?php
// Helper function to display image data html tag
/**
 * @param string $mime_type
 * The content type in MIME format, like text/plain.
 * @param string $filename
 * The image filename.
 * @return string HTML image tag.
 */
function displayImage(String $mime_type, String $filename)
{
    // Data is an image
    if (strpos($mime_type, 'image/', 0) !== false) { ?>
        <img class='imgview' src='showImg.php?path=<?= $filename ?>' alt='Thumbnail'>
    <?php return;
    } // Data is something else
    else {
        error_log("Intercepted attempt to smuggle in a wrong Mime Type at display_image.php");
        # TODO: Logik die abbricht und File aus tmp löscht oder gar nicht erst moved!!
    ?>
        <img class='imgview' src='imgs/warning.svg' alt='Warning'>
<?php return;
    }
}
?>