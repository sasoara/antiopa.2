<?php

// Presents base services
require_once("info.php");

// Gets the user email from session
$uemail = $_SESSION['email'];

// Image dir path to take pictures from
$image_dir = "../data/";

// To display post counter text
$counter_text = "Found results: ";

$SQL = "SELECT p.id, p.title, p.date, p.secure_file_name, p.content_type FROM posts AS p LEFT JOIN users AS u ON p.users_id = u.id ";

// Search term
$url_terms = isset($_GET['term']) ? htmlspecialchars($_GET['term']) : '';
// Sort date & title
$url_sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : '';
// Order by ASC / DESC
$url_orderby = isset($_GET['orderby']) ? htmlspecialchars($_GET['orderby']) : '';

// Sort order title or date
if ($url_sort == "title") {
    // title
    $sortstate = "title";
    if ($url_orderby = "asc") {
        // asc
        $sort = "ORDER BY p.title ASC";
        $orderstate = "asc";
    } else {
        // desc
        $sort = "ORDER BY p.title DESC";
        $orderstate = "desc";
    }
} else {
    // date
    $sortstate = "date";
    if ($url_orderby = "desc") {
        // desc
        $sort = "ORDER BY p.date DESC";
        $orderstate = "desc";
    } else {
        // asc
        $sort = "ORDER BY p.date ASC";
        $orderstate = "asc";
    }
}

// Terms in the url
if (!empty($url_terms)) {
    $SQL .= "WHERE p.title LIKE :term AND (p.is_public = 1 OR u.email = :email) " . $sort;
} elseif (empty($url_terms) || isset($url_terms)) {
    $SQL .= "WHERE p.is_public = 1 OR u.email = :email " . $sort;
} else {
    $SQL .= "WHERE p.is_public = 1 OR u.email = :email " . $sort . " LIMIT 10";
}

try {
    $stmt = $dbh->prepare($SQL);

    // Provides correct parameters
    if (!empty($url_terms)) {
        $param = "%".$url_terms."%";
        $stmt->bindParam(':term', $param, PDO::PARAM_STR);
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
    error_log("SQL error in search.query.inc: " . $e);
}

?>

<?php // Displays the amount of posts
?>
<div class="resultcounter"><?= $counter_text ?>
</div>

<?php // Sort buttons {date & title}

// Sort by date link
$search_date = "/search.php?term=" . urlencode($url_terms) . "&sort=date&orderby=";
$search_date .= $orderstate == "desc" ? "asc" : "desc";

// Sort by term link
$search_term = "/search.php?term=" . urlencode($url_terms) . "&sort=title&orderby=";
$search_term .= $orderstate == "desc" ? "asc" : "desc";

// Css class name generation
$dateSortClass = $sortstate == "date" ? 'active_sort_' . $orderstate : 'inactive_sort';
$abcSortClass = $sortstate == "title" ? 'active_sort_' . $orderstate : 'inactive_sort';

?>

<?php // Display the sort by buttons
?>
<div class="sticky">
    <a href="<?= $search_date; ?>" id="date_sort" class="block <?= $dateSortClass; ?>"></a>
    <a href="<?= $search_term; ?>" id="abc_sort" class="block <?= $abcSortClass; ?>"></a>
</div>

<?php // Display every post from the query
?>
<div class="block marginsearchresult">
    <?php
    //prevention of loop over empty array
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
                error_log("It isn't a image format! search.querry.inc");
                exit;
            }

    ?>
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