<?php
require_once('../config/db_config.php');
# when get request is a path, save it in var $path
if (isset($_GET["path"])) {
    $image_url = realpath("../data/" . ($_GET["path"]));
    $root = $config['web']['root'];

    #checks if the realpath contains var/Antiopa/data/
    if (strpos($image_url, $root . 'data/', 0) !== false) {

        # opens the file
        $handle = fopen($image_url, "rb"); # r for 'read'

        # type of the resource (file / directory etc)
        $type = gettype($handle); #php type

        # the size of given file, displayed in bytes
        $size = filesize($image_url);

        # the mime type that's needed to know of which resource the file is
        $mime_type = mime_content_type($image_url);

        # send the headers
        header('Content-Type: ' . $mime_type);
        if (isset($_GET["filename"])) {
            $filename = htmlspecialchars($_GET["filename"]);
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        }
        header("Content-Length: " . $size);
        fpassthru($handle);
    }
}
exit;
