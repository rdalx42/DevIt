<?php
    
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "testdb";
$conn ;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

echo "Version: F7D1";

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) {
    echo "ERR; Couldn't connect to database.";
}

$postdb_server = "localhost";
$postdb_user = "root";
$postdb_pass = "";
$postdb_name = "postdb";
$post_conn ;

try {
    $post_conn = mysqli_connect($postdb_server, $postdb_user, $postdb_pass, $postdb_name);
} catch (mysqli_sql_exception) {
    echo "ERR; Couldn't connect to post database.";
}


$com_server = "localhost";
$com_user = "root";
$com_pass = "";
$com_name = "topics";
$com_conn ;

try {
    $com_conn = mysqli_connect($com_server, $com_user, $com_pass, $com_name);
} catch (mysqli_sql_exception) {
    echo "ERR; Couldn't connect to community database.";
}

?>