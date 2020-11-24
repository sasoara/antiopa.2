<?php
require_once("lib/db.php");

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

# users ordered by email ascending - isn't using, only for showing how it works with a string
$user_by_email_asc = "";
foreach ($dbh->query("select * from users order by email asc") as $row) {
    $user_by_email_asc .= "$row[0] $row[1] $row[2] <br>";
}

# posts ordered by title ascending - isn't using, only for showing how it works with an array
$post_by_title_asc = array();
foreach ($dbh->query('select * from posts order by title asc') as $row) {
    array_push($post_by_title_asc, $row[1]);
}


# access from an other file to this snippet = $info['query']['user_by_email_as'] OR $info['url']['path'] etc.
return [
    'query' => [
        'user_by_email_as' => $user_by_email_asc,
        'post_by_title_asc' => $post_by_title_asc,
    ]

];
