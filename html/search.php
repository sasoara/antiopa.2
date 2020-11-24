<?php
// Database connectivity
require_once("lib/db.php");

// Sites in the Antiopa webpage, which includes header and footer
$page_structure = require_once("page_structure.php");

// Queries for filtering and sorting
$info = require_once("info.php");

$uri = $_SERVER['REQUEST_URI'];

// List of valid Filters
$validFilters = [
    'docs' => 'application/%',
    'images' => 'image/%',
    'videos' => 'video/%'
];

// Filter pattern
$filterURL = "";
$orderBY = "desc";
$abcFilterClass = 'inactive_sort';
$dateFilterClass = 'inactive_sort';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>
        <?php echo $page_structure["page"]["search"] ?>
    </title>
</head>

<body>
    <div class="page-container">
        <div class="content">

            <?php // header
            require_once("snippets/header.php");
            ?>
            <div class="block setDown">
                <h1>Search</h1>

                <?php // search input field
                ?>
                <form method="get"> <!-- XSS! TODO: Reflected one; input should be validate / encode! -->
                    <div class="flex">
                        <input class="search" type="text" name="term">
                        <button type="submit" class="searchBtn btn"></button>
                    </div>

                    <?php // tabs under search
                    ?>
                    <div class="flex">
                        <div class="roundshadow tab">
                            <label class="lightFont hand">
                                <input type="checkbox" class="" name="filter[]" value="docs"> docs
                            </label>
                        </div>
                        <div class="roundshadow tab">
                            <label class="lightFont hand">
                                <input type="checkbox" class="" name="filter[]" value="images"> images
                            </label>
                        </div>
                        <div class="roundshadow tab">
                            <label class="lightFont hand">
                                <input type="checkbox" class="" name="filter[]" value="videos"> videos
                            </label>
                        </div>
                    </div>
                </form>

                <?php // db search query
                require_once('queries/search.query.inc.php') ?>
            </div>
        </div>

        <?php // footer
        require_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>