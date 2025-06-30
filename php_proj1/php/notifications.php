<?php
    session_start();
    include("../php/db.php");
?>

<!doctype html>
<html>

<head>
    <title>Notifications</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../frontend/css/style.css" />
    <link rel="icon" href="./img/devit_icon.png" type="image/png" />
    <script src="../frontend/js/theme.js"></script>
</head>

<body class="discord-theme">
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

        <a href="../php/index.php">
            <img src="../frontend/img/exit.png" alt="Logout" />
        </a>
    </div>

    <div class="main-content">
        <?php
            $username = $_SESSION['username'];

            $sql = 'SELECT user, content, subject FROM notifications WHERE user = "' . $username . '"';
            $notification_result = mysqli_query($com_conn, $sql);

            if ($notification_result && mysqli_num_rows($notification_result) > 0) {
                $notifications = mysqli_fetch_all($notification_result, MYSQLI_ASSOC);

                echo "<h1>Notifications:</h1>";
                
                // arr reverse because we want the most recent notifications

                foreach (array_reverse($notifications) as $notification) {
                    echo "<div class='user-display'>
                        <p class='username'>u/" . htmlspecialchars($notification["subject"], ENT_QUOTES, 'UTF-8') .
                        ": " . htmlspecialchars($notification["content"], ENT_QUOTES, 'UTF-8') . "</p>
                    </div>";
                }                
            } else {
                echo "<p>No notifications found.</p>";
            }
        ?>
    </div>
</body>

</html>
