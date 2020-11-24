<?php
function showDataTag(String $mime_type, String $secure_filename, String $filename)
{
    if (strpos($mime_type, 'image/', 0) !== false) {
        echo "<img class='imgview' src='showImg.php?path=$secure_filename' alt='vorschaubild'>";
    } elseif (strpos($mime_type, 'video/', 0) !== false) {
        echo "<video autoplay class='imgview' controls poster='imgs/video.png'> 
                <source src='showImg.php?path=$secure_filename'>
                Sorry, your browser doesn't support embedded videos.
            </video>
            ";
    } elseif (strpos($mime_type, 'application/pdf', 0) !== false) {
        echo "<embed class='embed-pdf-file' src='showImg.php?path=$secure_filename' frameboarder='0' scrolling='yes'>
        </embed>";
    }elseif (strpos($mime_type, 'application/', 0) !== false) {
        $path = "./imgs/document.png";
        echo "<img class='imgview' src='showImg.php?path=$path' alt='vorschaubild'>";
    }
    else {
        $path = "./imgs/document.png";
        echo "<img class='imgview' src='showImg.php?path=$path' alt='vorschaubild'>";
    }
}
