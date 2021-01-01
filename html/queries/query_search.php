<?php

// Presents base services
require_once("../info.php");

//Gets user email from session
$uemail = $_SESSION['email'];

//image dir path to take pictures from
$image_dir = "../../data/";

// Suchbegriff
$url_term = htmlspecialchars($_GET['term']);
// Name/Datum
$url_sort = htmlspecialchars($_GET['sort']);
// ASC/DESC
$url_orderby = htmlspecialchars($_GET['orderby']);

//sort order
if($url_sort == "title"){
    //sortierung nach titel
    $sortstate = "title";
    if ($url_orderby = "desc"){
        //title_desc
        $sort = "ORDER BY p.title desc";
        $orderstate = "desc";
    }else{
        //title_asc
        $sort = "ORDER BY p.title asc";
        $orderstate = "asc";
    }
}else{
    //sortierung nach datum
    $sortstate = "date";
    if ($url_orderby = "desc"){
        //date_desc
        $sort = "ORDER BY p.date desc";
        $orderstate = "asc";
    }else{
        //date_asc
        $sort = "ORDER BY p.date asc";
        $orderstate = "desc";
    }
}

// search term
if(!empty($url_term)){
    $search_params = "WHERE p.title= :term AND p.is_public like 1 or u.email like :email " . $sort;
}elseif(empty($url_term)||isset($url_term)){
    $search_params = "WHERE p.is_public like 1 or u.email like :email " . $sort;
}else{
    $search_params = "WHERE p.is_public like 1 or u.email like :email " . $sort . " LIMIT 10";
}

try{
    $stmt = $dbh->prepare("SELECT DISTINCT p.id, p.title, p.date, p.secure_file_name, p.content_type FROM posts AS p LEFT JOIN users AS u ON p.users_id = u.id " . $search_params);

    //provide corect params
    if(!empty($url_term)){
        $stmt->bindParam(':term', $url_term);
        $stmt->bindParam(':email', $uemail);
    }else{
        $stmt->bindParam(':email', $uemail);
    }

    // Executing the sql query
    //$posts = $dbh->query($stmt);
    //$posts = $stmt->execute();

    $stmt->execute();
    $posts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Post counter
    debug_to_console($posts->rowCount() . "zeilen");
    $counter_text = !empty($url_term) ? "Found results: " . $posts->rowCount() : "Limit 10";

} catch (PDOException $e) {
    # TODO: Logger!!
    debug_to_console($e);
}

?>

<?php // displays the amount of posts
?>
<div class="resultcounter"><?= $counter_text ?>
</div>

<?php // Sort buttons {date & title}

//sort by date link
$search_date = "/search.php?term=" . urlencode($url_term) . "&sort=date&orderby=";
$search_date .= $orderstate == "desc" ? "asc" : "dec";

//sort by term link
$search_term = "/search.php?term=" . urlencode($url_term) . "&sort=term&orderby=";
$search_term .= $orderstate == "desc" ? "asc" : "dec";

//css class name generation
$dateSortClass = $sortstate == "date" ? 'active_sort_' . $orderstate : 'inactive_sort';
$abcSortClass = $sortstate == "title" ? 'active_sort_' . $orderstate : 'inactive_sort';

?>

<?php // display the sort by buttons
?>
<div class="sticky">
    <a href="<?= $search_date; ?>" id="date_sort" class="block <?= $dateSortClass; ?>"></a>
    <a href="<?= $search_term; ?>" id="abc_sort" class="block <?= $abcSortClass; ?>"></a>
</div>

<?php // display every post from the query
?>
<div class="block marginsearchresult">
    <?php

if(!empty($posts)){


    foreach ($posts as $post) {
        # TODO: Prüfen ob Spalten dieser Reihenfolge entspricht!!
        $post_id = $post[0];
        $title = $post[1];
        $date_from_db = $post[2];
        $date = date_create("$date_from_db");
        $dateFormatted = date_format($date, "dS F Y");
        $secure_filename = $post[3];
        $content_type = $post[4];
        // Check if the filetype is correct, if not DIE and inform the user.
        if (!strpos($content_type, 'image/', 0) == 0) {
            # TODO: Logger!!
            debug_to_console("It isn't a image format!");
            exit;
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
    <?php
    }
}
    ?>
</div>