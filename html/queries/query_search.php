<?php

// Presents base services
$info = require_once("info.php");

$image_dir = "../../data/";

//Suchbegriff
$url_term = htmlspecialchars($_GET['term']);
//Name / Datum
$url_sort = htmlspecialchars($_GET['sort']);
//ASC/DESC
$url_orderby = htmlspecialchars($_GET['orderby']);


if($url_sort == "title"){
    //sortierung nach titel
    if ($url_orderby = "desc"){
        //ordert DESC
        #Todo: Logic !!
        $sort = "title_desc";
    }else{
        //ordert ASC
        #Todo: Logic !!
        $sort = "title_asc";
    }
}else{
    //sortierung nach datum
    if ($url_orderby = "desc"){
        //ordert DESC
        #Todo: Logic !!
        $sort = "date_desc";
    }else{
        //ordert ASC
        #Todo: Logic !!
        $sort = "date_asc";
    }
}






$is_term = (isset($_GET['term']) && !empty($_GET['term']));
$empty_term = (isset($_GET['term']) && empty($_GET['term']));
$no_term = (!isset($_GET['term']) && empty($_GET['term']));


$is_sort_title = (isset($_GET['sort']) || !empty($_GET['sort']) && htmlspecialchars($_GET['sort']) == "title");
$is_sort_date = (isset($_GET['sort']) || !empty($_GET['sort']) && htmlspecialchars($_GET['sort']) == "date");

$is_ordered_desc = (isset($_GET['orderby']) && !empty($_GET['orderby']) && htmlspecialchars($_GET['orderby']) == "desc");
$is_ordered_asc = (isset($_GET['orderby']) && !empty($_GET['orderby']) && htmlspecialchars($_GET['orderby']) == "asc");
$is_empty_ordered = (!isset($_GET['orderby']) && empty($_GET['orderby']));

// Url like /search.php
if ($no_term) {
}

// Url like /search.php?term=
if ($empty_term) {
}

// Url like /search.php?term=button
if ($is_term) {
}


if ($is_sort_title) {
} elseif ($is_sort_date) {
}


?>

<?php // Post counter
$number_of = $posts->rowCount();
$counter_text = $is_term ? "Found posts: " . $number_of : "Post limit 10";
?>
<div class="resultcounter"><?= $counter_text ?>
</div>

<?php // Sort buttons {date & title}
$base_phpFile = "/search.php";
$orderBY = $is_empty_ordered || $is_ordered_asc ? "asc" :  "desc";
$abcSortClass = $is_sort_title ? 'active_sort_' . $orderBY : 'inactive_sort';
$dateSortClass = $is_sort_date ? 'active_sort_' . $orderBY : 'inactive_sort';

$searchURL = isset($_GET['term']) ? "&term=" . urlencode(htmlspecialchars($_GET['term'])) : "";
// TODO: It would be safer to protect `$filterURL` so we don't forward an "attack" in the links below
$sortURL_date = "?sort=date&orderby=";
$sortURL_abc = "?sort=title&orderby=";
?>
<div class="sticky">
    <a href="<?php echo $base_phpFile . $sortURL_date . $orderBY . $searchURL ?>" id="date_sort" class="block <?= $dateSortClass ?>"></a>
    <a href="<?php echo $base_phpFile . $sortURL_abc . $orderBY . $searchURL ?>" id="abc_sort" class="block <?= $abcSortClass ?>"></a>

</div>

<?php // display every post from the query
?>
<div class="block marginsearchresult">
    <?php $dateCreated = date_create("$date");
    $dateFormatted = date_format($dateCreated, "dS F Y");


    foreach ($posts as $post) {
        # TODO: Prüfen ob Spalten dieser Reihenfolge entspricht!!
        $post_id = $post[0];
        $title = $post[1];
        $date = $post[2];
        $secure_filename = $post[3];
        $content_type = $post[4];
        // Check if the filetype is correct, if not DIE and inform the user.
        if (!strpos($content_type, 'image/', 0) == 0) {
            # TODO: Logger!!
            debug_to_console("It isn't a image format!");
            exit;
        }
    }
    ?>

    <!-- For each Loop für Posts -->

    <a href="detailView.php?id=<?= $post_id; ?>" style="background-image: url('showImg.php?path=<?= $image_dir . $secure_filename; ?>');" class="searchpreview inlineblock">
        <p class="title lightbackground baseline break">
            <?= $title; ?>
            <br>
            <span class="dateTitle">
                <?= $dateFormatted; ?>
            </span>
        </p>
    </a>
    <?php // }
    ?>
</div>