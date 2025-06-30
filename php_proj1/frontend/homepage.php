<?php
session_start();
include("../php/db.php");
include("../php/global/fn.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Home Dashboard</title>
    <link rel="icon" href="./img/devit_icon.png" type="image/png" />
    <link rel="stylesheet" href="../frontend/css/style.css" />
    <script src = "../frontend/js/theme.js"></script>
    
</head>
<body class = "discord-theme">

    <!-- Sidebar -->
    <div class="sidebar">
        
        <a href="../frontend/homepage.php">
            <img src="../frontend/img/home.png" alt="Home" />
        </a>
    
        <a href="../frontend/dashboard.php">
            <img src="../frontend/img/profile.png" alt="Profile" />
        </a>

        <a href="../php/create_com.php">
            <img src="../frontend/img/com.png" alt="Create Community" />
        </a>

        <a href="../php/create_post.php">
            <img src="../frontend/img/post.png" alt="Create Post" />
        </a>

        <a href = "../php/notifications.php">
            <img src = "../frontend/img/bellicon.png" alt = "Notifications">
        </a>

        <a href="../php/index.php">
            <img src="../frontend/img/exit.png" alt="Logout" />
        </a>
    </div>

    <!-- Fixed Search Form -->
    <form method="POST" action="" class="search-form">
        <input type="text" placeholder="Search..." name="search" />
        <input type="submit" name="action" value="Search Users" />
        <input type="submit" name="action" value="Search Communities" />
    </form>

    <!-- Main Content -->
    <div class="main-content">

        <!-- PHP Code Output Here -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] != "POST") {

            $posts_result = mysqli_query(
                $post_conn,
                "SELECT id, title, content, likes, creator, topic FROM posts ORDER BY id DESC LIMIT 40"
            );

            $posts = mysqli_fetch_all($posts_result, MYSQLI_ASSOC);

            echo "<h3>Latest Posts Across All Communities</h3>";

            get_posts($posts);
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST["action"] == "Search Users") {
                $content = htmlspecialchars(trim($_POST["search"]), ENT_QUOTES, 'UTF-8');

                if (empty($content)) {
                    echo "Enter valid content <br>";
                    return;
                }

                $matches = mysqli_fetch_all(
                    mysqli_query($conn, "SELECT user,likes,description FROM users WHERE user LIKE '%$content%' ORDER BY likes DESC"),
                    MYSQLI_ASSOC
                );

                foreach ($matches as $match) {
                    echo "<div class='user-display'>
                            <p class='username'>u/" . htmlspecialchars($match["user"], ENT_QUOTES, 'UTF-8') . "</p>
                            <span class='likes'>❤️ " . htmlspecialchars($match["likes"], ENT_QUOTES, 'UTF-8') . "</span>
                          </div>";
                }                

            } elseif ($_POST["action"] == "Search Communities") {
                $content = htmlspecialchars(trim($_POST["search"]), ENT_QUOTES, 'UTF-8');

                if (empty($content)) {
                    echo "Enter valid content <br>";
                    return;
                }

                $matches = mysqli_fetch_all(
                    mysqli_query($com_conn, "SELECT name FROM topic WHERE name LIKE '%$content%'"),
                    MYSQLI_ASSOC
                );

                foreach ($matches as $match) {
                    echo "<form class='inline-button' method='POST' style='display:inline-block;'>
                            <input type='hidden' name='community_name' value='" . htmlspecialchars($match["name"], ENT_QUOTES, 'UTF-8') . "'>
                            c/" . htmlspecialchars($match["name"], ENT_QUOTES, 'UTF-8') . "
                            <input type='submit' name='action' value='View'>
                          </form><br>";
                }

            } elseif ($_POST["action"] == "View") {
                $community = $_POST["community_name"];

                $posts = mysqli_fetch_all(
                    mysqli_query(
                        $post_conn,
                        "SELECT id, title, content, likes, creator FROM posts WHERE topic = '" . mysqli_real_escape_string($com_conn, $community) . "' ORDER BY id DESC LIMIT 50"
                    ),
                    MYSQLI_ASSOC
                );

                echo "<h3>Posts from c/" . htmlspecialchars($community, ENT_QUOTES, 'UTF-8') . "</h3>";

                get_posts($posts);

            } elseif ($_POST["action"] == "Like") {
                if (!isset($_SESSION['username'])) {
                    echo "You must be logged in to like posts.<br>";
                    echo "<h3>Posts from c/" . htmlspecialchars($community, ENT_QUOTES, 'UTF-8') . "</h3>";
                    exit;
                }

                $current_user_id = $_SESSION['username'];
                $post_id = isset($_POST["id"]) ? $_POST["id"] : '';
                $community = $_POST['community_name'];
                $canbreak=false;

                if(empty($community)){
                    
                    $sql = "SELECT topic FROM posts WHERE id = '$post_id' LIMIT 1";
                    $result = mysqli_query($post_conn, $sql);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $t = $row["topic"];
                        $posts_sql = "SELECT id, title, content, likes, creator FROM posts WHERE topic = '$t' ORDER BY id DESC LIMIT 50";
                        $posts_result = mysqli_query($post_conn, $posts_sql);
                        $posts = mysqli_fetch_all($posts_result, MYSQLI_ASSOC);

                        // if(!empty($community)){
                            echo "<h3>Posts from c/" . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . "</h3>";
                            get_posts($posts);
                        // }    
                    }
                    
                }

                if (empty($post_id)) {
                    echo "Invalid post ID or community name.<br>";
                    exit;
                }

                $community_esc = mysqli_real_escape_string($post_conn, $community);
                $current_user_id_esc = mysqli_real_escape_string($com_conn, $current_user_id);
                $post_id_esc = mysqli_real_escape_string($com_conn, $post_id);

                $check_sql = "SELECT * FROM likes WHERE username = '$current_user_id_esc' AND id = '$post_id_esc'";
                $check_result = mysqli_query($com_conn, $check_sql);

                if (mysqli_num_rows($check_result) > 0) {
                    echo "You have already liked this post.<br>";
                } else {
                    $insert_sql = "INSERT INTO likes (username, id) VALUES ('$current_user_id_esc', '$post_id_esc')";
                    if (!mysqli_query($com_conn, $insert_sql)) {
                        echo "Failed to record like. Please try again.<br>";
                        exit;
                    }

                    $update_post_sql = "UPDATE posts SET likes = likes + 1 WHERE id = '$post_id_esc'";
                    if (!mysqli_query($post_conn, $update_post_sql)) {
                        echo "Failed to update post likes.<br>";
                        exit;
                    }

                    $creator_sql = "SELECT creator FROM posts WHERE id = '$post_id_esc'";
                    $creator_result = mysqli_query($post_conn, $creator_sql);

                    if ($creator_result && mysqli_num_rows($creator_result) > 0) {
                        $row = mysqli_fetch_assoc($creator_result);
                        $author_name = $row["creator"];
                        $author_nameESC = mysqli_real_escape_string($conn, $author_name);
                        $likemsg = "has liked your post";
                        
                        mysqli_query($conn, "UPDATE users SET likes = likes + 1 WHERE user = '$author_nameESC'"); 
                        mysqli_query($com_conn, "INSERT INTO notifications (`user`, `content`, `subject`) VALUES ('$author_nameESC', '$likemsg', '" . $_SESSION["username"] . "')");
                    }

                    
                }

                $posts_sql = "SELECT id, title, content, likes, creator FROM posts WHERE topic = '$community_esc' ORDER BY id DESC LIMIT 50";
                $posts_result = mysqli_query($post_conn, $posts_sql);
                $posts = mysqli_fetch_all($posts_result, MYSQLI_ASSOC);

                if(!empty($community)){
                    echo "<h3>Posts from c/" . htmlspecialchars($community, ENT_QUOTES, 'UTF-8') . "</h3>";
                    get_posts($posts);
                }    
            }elseif($_POST["action"] == "View Creator"){
                $post_creator = $_POST["creator"];
                if(empty($post_creator)){
                    echo "ERR: Creator doesn't exist";
                    return;
                }else{
                    
                    $post_creator_esc= mysqli_real_escape_string($conn,$post_creator);
                    $res = mysqli_query($conn, "SELECT description, likes FROM users WHERE user = '$post_creator_esc'");
                    
                    if ($res && mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        $desc = htmlspecialchars($row["description"], ENT_QUOTES, "UTF-8");
                        $likes_creator = htmlspecialchars($row["likes"], ENT_QUOTES, "UTF-8");
                    
                        echo '<h1 class="usernameframe">u/' . htmlspecialchars($post_creator, ENT_QUOTES, 'UTF-8') . 
                        ' <span class="like_class2">❤️' . $likes_creator . '</span></h1>
                        <h2>About: u/' . htmlspecialchars($post_creator, ENT_QUOTES, 'UTF-8') . '</h2>
                         <p>' . ($desc ?: "No description available.") . '</p>';

                    } else {
                        echo "ERR: Creator description wasn't found";
                    }
                              
                }
            }
        }
        ?>

    </div>
    <script src = "../frontend/js/theme.js"></script>
</body>
</html>
