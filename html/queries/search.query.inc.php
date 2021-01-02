<?php

// Presents base services
require_once("info.php");

// Gets the user email from session
$uemail = $_SESSION['email'];

// Image dir path to take pictures from
$image_dir = "../data/";

// To display post counter text
$counter_text = "Found results: ";

// Search term
$url_terms = isset($_GET['term']) ? htmlspecialchars($_GET['term']) : '';
// Name/Datum
$url_sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : '';
// ASC/DESC
$url_orderby = isset($_GET['orderby']) ? htmlspecialchars($_GET['orderby']) : '';

//sort order
if ($url_sort == "title") {
    //sortierung nach titel
    $sortstate = "title";
    if ($url_orderby = "desc") {
        //title_desc
        $sort = "ORDER BY p.title desc";
        $orderstate = "desc";
    } else {
        //title_asc
        $sort = "ORDER BY p.title asc";
        $orderstate = "asc";
    }
} else {
    //sortierung nach datum
    $sortstate = "date";
    if ($url_orderby = "desc") {
        //date_desc
        $sort = "ORDER BY p.date desc";
        $orderstate = "desc";
    } else {
        //date_asc
        $sort = "ORDER BY p.date asc";
        $orderstate = "asc";
    }
}

// search term
if (!empty($url_terms)) {
    $search_params = "WHERE p.title = :term AND p.is_public LIKE 1 OR u.email = :email " . $sort;
} elseif (empty($url_terms) || isset($url_terms)) {
    $search_params = "WHERE p.is_public LIKE 1 OR u.email = :email " . $sort;
} else {
    $search_params = "WHERE p.is_public LIKE 1 OR u.email = :email " . $sort . " LIMIT 10";
}

try {
    $stmt = $dbh->prepare("SELECT p.id, p.title, p.date, p.secure_file_name, p.content_type FROM posts AS p LEFT JOIN users AS u ON p.users_id = u.id " . $search_params);

    //provide corect params
    if (!empty($url_terms)) {
        $stmt->bindParam(':term', $url_terms);
        $stmt->bindParam(':email', $uemail);
    } else {
        $stmt->bindParam(':email', $uemail);
    }

    // Executing the sql query
    $stmt->execute();
    $posts = $stmt->fetchAll();

    // Post counter
    $counter_text = !empty($url_terms) ? "Found results: " . count($posts) : "Limit 10";
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
$search_date = "/search.php?term=" . urlencode($url_terms) . "&sort=date&orderby=";
$search_date .= $orderstate == "desc" ? "asc" : "desc";

//sort by term link
$search_term = "/search.php?term=" . urlencode($url_terms) . "&sort=title&orderby=";
$search_term .= $orderstate == "desc" ? "asc" : "desc";

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

    if (!empty($posts)) {


        foreach ($posts as $post) {
            # TODO: Prüfen ob Spalten dieser Reihenfolge entspricht!!
            $post_id = $post['id'];
            $title = $post['title'];
            $date_from_db = $post['date'];
            $date = date_create("$date_from_db");
            $dateFormatted = date_format($date, "dS F Y");
            $secure_filename = $post['secure_file_name'];
            $content_type = $post['content_type'];
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