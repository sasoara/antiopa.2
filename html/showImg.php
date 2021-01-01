<?php
require_once('../config/db_config.php');
# when get request is a path, save it in var $path
if (isset($_GET["path"])) {
    // TODO: Technically, could probably attack the `path` parameter with `../../etc/passwd` or similar traversal queries
    $image_url = realpath(htmlspecialchars($_GET["path"]));

    /* Send headers and file to visitor */
    header('Content-Description: File Transfer');
    // TODO: Not sure if same attack could be applied to filename, but we could enforce using only the basename
    header('Content-Disposition: attachment; filename=' . basename($image_url));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($image_url));
    header('Content-Type: ' . mime_content_type($image_url));
    readfile($image_url);
}
exit;
