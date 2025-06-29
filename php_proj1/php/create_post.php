<?php
session_start();
include("../php/db.php");
?>

<!doctype html>
<html>
<head>
    <title>Create post</title>
    <meta charset="utf-8">
    <link rel="icon" href="../frontend/img/devit_icon.png" type="image/png">
    <link rel="stylesheet" href="../frontend/css/style.css">
    <!-- <script src = "../frontend/js/theme.js"></script> -->
    
</head>
<body>
   
<!DOCTYPE html>
<html>
<head>
    <title>Create post</title>
    <meta charset="utf-8">
    <link rel="icon" href="../frontend/img/devit_icon.png" type="image/png">
    <link rel="stylesheet" href="../frontend/css/style.css">

    
</head>
<body class = "discord-theme">

    <div class="sidebar">
        <a href="../frontend/homepage.php">
            <img src="../frontend/img/home.png" alt="Home">
        </a>
        <a href="../frontend/dashboard.php">
            <img src="../frontend/img/profile.png" alt="Profile">
        </a>
        <a href = "../php/create_com.php">
            <img src = "../frontend/img/com.png" alt = "Create Community">
        </a>
        <a href="../php/index.php">
            <img src="../frontend/img/exit.png" alt="Logout">
        </a>
    </div>

    <div class="main-content">
        <form method="POST" action="">
            <h1 style = "padding:10px 10px 10px 10px margin-left 20px">Create Post</h1>
            <input class="txtbox" type="text" placeholder="Post title..." name="post_title"><br><br>
            <textarea  placeholder="Post text..." name="post_text" class =  "txtbox" id = "p"></textarea><br><br>
            <input type="text" class="txtbox" placeholder="Post community name..." name="post_community"><br><br><br>
            <input type="submit" class="form_button_important" name="action" value="Post!" >
            <br>
        </form>
    </div>
    <script src = "../frontend/js/theme.js"></script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "Post!") {
    $title = filter_input(INPUT_POST, "post_title", FILTER_DEFAULT);
    $content = filter_input(INPUT_POST, "post_text", FILTER_DEFAULT);
    $community = filter_input(INPUT_POST, "post_community", FILTER_DEFAULT);

    if (empty($title) || empty($content) || empty($community)) {
        echo "Invalid post data.<br>";
        return;
    }

    $likes = 0;
    $username = $_SESSION["username"];

    $sql = "SELECT 1 FROM topic WHERE name = '$community' LIMIT 1";
    $result = mysqli_query($com_conn, $sql);

    if (!mysqli_num_rows($result) > 0) {
        echo "Topic is invalid.<br>";
        return;
    }

    $sql = "INSERT INTO posts (content, title, topic, likes, creator)
            VALUES ('$content', '$title', '$community', $likes, '$username')";

    try {
        mysqli_query($post_conn, $sql);
        echo "Post created!<br>";
    } catch (mysqli_sql_exception) {
        echo "Couldn't insert post in database!<br>";
        return;
    }
}
?>
