<?php

/**
 * This file helps to find a post.
 * It contains the query which finds private and public posts.
 */

// Presents base services
$info = require_once("info.php");


// string variable substitution per complex syntax:
// https://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.double

// TODO: Prüfen ob die Request Anfrage korrekt ist??
/**
 * if ($_SERVER['REQUEST_METHOD'] === 'GET') {
 *      // the request method is fine
 * } else {
 *      exit('Invalid Request');
 * }
 */

$email = $_SESSION['email'];

$limitation = !isset($_GET['term']) ? "LIMIT 10" : "";
$sql = "SELECT DISTINCT p.id, p.title, p.date, p.secure_file_name, p.content_type, p.is_public, p.users_id FROM posts AS p LEFT JOIN users AS u ON p.users_id = u.id";

debug_to_console($sql);

if (!empty($_GET['term'])) {
    // All search terms separated by spaces are saved
    // TODO: my understanding is that `htmlspecialchars` does not escape `;`, so I could technically break the query with `term=foo;DROP TABLE posts;`. Using a prepared statement would be safer
    $query_terms = explode(' ', htmlspecialchars($_GET['term']));

    $conditions = [];
    // Splitting the search terms
    foreach ($query_terms as $term) {
        // Prepare sql statement for search terms
        $conditions[] = "p.title LIKE ('%$term%')";
        $conditions[] = "p.description LIKE ('%$term%')";
    }

    // WHERE (p.title LIKE ('%blue%') OR p.description LIKE ('%blue%'))
    $sql .= " WHERE (" . implode(' OR ', $conditions) .  ")";
}

// Appends the 'AND' if 'WHERE' is present in the sql statement
$sql .= strpos($sql, "WHERE") === false ? " WHERE " : " AND "; // TODO: Braucht es dieses WHERE??
// Adds search for public and private posts to the sql statement
// TODO: Same for `email` here
$sql .= " (p.is_public like 1 or u.email like ('$email') )";

// Checks the filter
if (!empty($_GET['filter'])) {
    // Appends the filter statement
    $sql .= strpos($sql, "WHERE") === false ? " WHERE (" : " AND (";

    // TODO: Same for `filter` here
    foreach ($_GET['filter'] as $filter) {
        $sql .= " p.content_type LIKE '" . $validFilters[$filter] . "' OR";
    }
    $sql = substr_replace($sql, "", -2);
    $sql .= ") ";
    debug_to_console($sql);

    $filterURL = implode('&filter%5B%5D=', $_GET['filter']);
}

//check if there is a sort condition
if (!empty($_GET['sort'])) {
    // TODO: Same for `sort` here
    $sort = htmlspecialchars($_GET['sort']);

    $orderBY = empty($_GET['orderby']) ? "asc" :  htmlspecialchars($_GET['orderby']);

    $abcFilterClass = $sort == 'title' ? 'active_sort_' . $orderBY : 'inactive_sort';
    $dateFilterClass = $sort == 'date' ? 'active_sort_' . $orderBY : 'inactive_sort';

    $sql .= " ORDER BY p." . $sort . " " . $orderBY;
}

// URL term param
$is_term = (!isset($_GET['term']) or empty($_GET['term']));

// Displaying a limit of posts if in get request param 'term' isn't set
$displaying_post_limit = " LIMIT 10";
$sql .= $is_term ? $displaying_post_limit : "";

// The sql query will be completed
$sql .= ";";

// Executing the sql query
$posts = $dbh->query($sql);

// Counter
$display_post_counter = $is_term ? "Found results: " . $posts->rowCount() : "Limit 10";
?>

<?php // Post counter
?>
<div class="resultcounter"><?= $display_post_counter ?></div>

<div class="sticky">
    <?php
    $baseURL = "/search.php";
    $searchURL = isset($_GET['term']) ? "&term=" . urlencode(htmlspecialchars($_GET['term'])) : "";
    // TODO: It would be safer to protect `$filterURL` so we don't forward an "attack" in the links below
    $filterURL = $filterURL != "" ? "&filter%5B%5D=" . $filterURL : "";
    $sortURL_date = "?sort=date&orderby=";
    $sortURL_abc = "?sort=title&orderby=";
    $orderBY = "desc";

    if (isset($_GET['orderby'])) {
        $orderBY = $_GET['orderby'] == "asc" ? "desc" : "asc";
    }
    ?>
    <a href="<?php echo $baseURL . $sortURL_date . $orderBY . $searchURL . $filterURL  ?>" id="date_sort" class="block <?= $dateFilterClass ?>"></a>
    <a href="<?php echo $baseURL . $sortURL_abc . $orderBY . $searchURL . $filterURL  ?>" id="abc_sort" class="block <?= $abcFilterClass ?>"></a>

</div>

<?php // display every post from the query
?>
<div class="block marginsearchresult">
    <?php
    $image_orderby = '../data/';


    foreach ($posts as $post) {
        $title = $post[1];
        $date = $post[2];
        $secure_file_name = $post[3];
        $content_type = $post[4];
        if (strpos($content_type, 'video/', 0) !== false) {
            $secure_file_name = "./imgs/video.png";
        } elseif (strpos($content_type, 'application/', 0) !== false) {
            $secure_file_name = "./imgs/document.png";
        }
    ?>
        <a href="detailView.php?id=<?= $secure_file_name; ?>" style="background-image: url('showImg.php?path=<?= $secure_file_name ?>');" class="searchpreview inlineblock">
            <p class="title lightbackground baseline break">
                <?= $title; ?>
                <br>
                <span class="dateTitle">
                    <?php
                    $dateFormatted = date_create("$date");
                    echo date_format($dateFormatted, "dS F Y");
                    ?>
                </span>
            </p>
        </a>
    <?php } ?>
</div>