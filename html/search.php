<?php
// Database connectivity
require_once("lib/db.php");

// Contains page and footer infos
$page_structure = require_once("page_structure.php");

// Queries for filtering and sorting
// TODO: Braucht es diese info.php / queries??
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

            <?php
            // Header navbar
            require_once("snippets/header.php");
            ?>
            <div class="block setDown">
                <h1>Search</h1>

                <?php // Search input field
                ?>
                <form method="get">
                    <?php // TODO: XSS Reflected! > The input should be validated & encoded!
                    ?>
                    <div class="flex">
                        <input class="search" type="text" name="term">
                        <button type="submit" class="searchBtn btn"></button>
                    </div>

                    <?php // Tabs under the search input field
                    ?>
                    <div class="flex">
                        <?php // Filter documents
                        ?>
                        <div class="roundshadow tab">
                            <label class="lightFont hand">
                                <input type="checkbox" name="filter[]" value="docs"> docs
                            </label>
                        </div>
                        <?php // Filter images
                        ?>
                        <div class="roundshadow tab">
                            <label class="lightFont hand">
                                <input type="checkbox" name="filter[]" value="images"> images
                            </label>
                        </div>
                        <?php // Filter videos
                        ?>
                        <div class="roundshadow tab">
                            <label class="lightFont hand">
                                <input type="checkbox" name="filter[]" value="videos"> videos
                            </label>
                        </div>
                    </div>
                </form>
                <?php
                // Database query to search
                require_once('queries/search.query.inc.php') ?>
            </div>
        </div>

        <?php
        // Footer
        require_once("snippets/footer.php");
        ?>
    </div>
</body>

</html>