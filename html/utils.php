<?php

/**
 * @param string $mime_type
 * The content type in MIME format, like text/plain.
 * @param string $secure_filename
 * The hashed filename.
 * @return string HTML tag that depends on the specific @param string $mime_type.
 */
function showDataTag(String $mime_type, String $secure_filename)
{
    // Data is an image
    if (strpos($mime_type, 'image/', 0) !== false) {
?>
        <img class='imgview' src="showImg.php?path=<?= $secure_filename ?>" alt='Vorschaubild'>
    <?php return;
    } // Data is a video
    elseif (strpos($mime_type, 'video/', 0) !== false) {
    ?>
        <video autoplay class='imgview' controls poster='imgs/video.png'>
            <source src='showImg.php?path=<?= $secure_filename ?>'>
            Sorry, your browser doesn't support embedded videos.
        </video>
    <?php
        return;
    } // Data is a document with ending .pdf
    elseif (strpos($mime_type, 'application/pdf', 0) !== false) {
    ?>
        <embed class='embed-pdf-file' src='showImg.php?path=<?= $secure_filename ?>' frameboarder='0' scrolling='yes'>
        </embed>
    <?php
        return;
    } // Data is something else
    else {
        $path = "./imgs/document.png";
    ?>
        <img class='imgview' src='showImg.php?path=<?= $path ?>' alt='vorschaubild'>
<?php
        return;
    }
}
?>