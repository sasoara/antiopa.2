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
    } // Data is something else TODO: Korrekte Error Meldung!!
    else {
        $path = "./imgs/warning.svg";
    ?>
        <img class='imgview' src='showImg.php?path=<?= $path ?>' alt='vorschaubild'>
<?php
        return;
    }
}
?>