<?php
session_start();
include("../php/db.php");

$bio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $bio = filter_input(INPUT_POST, "description", FILTER_DEFAULT);
        $bio = htmlspecialchars(trim($bio), ENT_QUOTES, 'UTF-8');

        if (isset($_POST["action"]) && $_POST["action"] === "Save Description") {
            if (!empty($bio)) {
                $username_safe = mysqli_real_escape_string($conn, $username);
                $bio_safe = mysqli_real_escape_string($conn, $bio);
                $sql = "UPDATE users SET description = '$bio_safe' WHERE user = '$username_safe'";
                if (mysqli_query($conn, $sql)) {
                    echo "Description saved!<br>";
                    $bio = $bio_safe;
                } else {
                    echo "Error saving description: " . mysqli_error($conn) . "<br>";
                }
            } else {
                echo "Enter a valid description.";
            }
        }
    }
} elseif (isset($_SESSION["username"])) {
    $name = $_SESSION["username"];
    $sql = "SELECT description FROM users WHERE user = '$name' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $bio = $row["description"];
    }
}
?>

<!doctype html>
<html>
<head>
    <title>User Dashboard</title>
    <meta charset="utf-8">
    <link rel="icon" href="../frontend/img/devit_icon.png" type="image/png">
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src = "../frontend/js/theme.js"></script>
</head>
<body class = "discord-theme">

<div class="sidebar">
    <a href="../frontend/homepage.php">
        <img src="../frontend/img/home.png" alt="Home" />
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

<div class = "main-content">
    <form method="POST" action="">
        
        <h1 class = "usernameframe">
            <?php echo "u/" . $_SESSION["username"]?>
            <span class="like_class"><?php echo "❤️" . $_SESSION["likes"]; ?></span>
        </h1>

        <h1>About me: </h1>

        <textarea class = "txtbox" id = "desc" placeholder="Enter a Description..." name="description"><?php echo htmlspecialchars($bio ?? ''); ?></textarea>

        <input type="submit" id = "savedesc" class = "submitbutton" name="action" value="Save Description">
        <br>
    </form>
</div>

<script src = "../frontend/js/theme.js"></script>
</body>
</html>
