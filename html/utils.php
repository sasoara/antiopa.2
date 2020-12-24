<?php

/**
 * @param string $mime_type
 * The content type in MIME format, like text/plain.
 * @param string $filename
 * The image filename.
 * @return string HTML image tag.
 */
function showDataTag(String $mime_type, String $filename)
{
    // Data is an image
    if (strpos($mime_type, 'image/', 0) !== false) {
?>
        <img class='imgview' src='showImg.php?path=<?= $filename ?>' alt='Thumbnail'>
    <?php return;
    } // Data is something else TODO: Korrekte Error Meldung!!
    else {
        # TODO: Audit Eintrag - Versuch ein anderer Mime Type hochzuladen!!
        # TODO: Logik die abbricht und File aus tmp löscht oder gar nicht erst moved!!
    ?>
        <img class='imgview' src='imgs/warning.svg' alt='Warning'>
<?php
        return;
    }
}
?>