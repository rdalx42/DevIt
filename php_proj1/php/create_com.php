
<?php
    session_start();
    include("../php/db.php");
?>

<!doctype html>
<html>
    <head>
        <title>Create Community</title>
        <meta charset="utf-8">
        <link rel="icon" href="../frontend/img/devit_icon.png" type="image/png">
        <link rel="stylesheet" href="../frontend/css/style.css">
        <script src = "../frontend/js/theme.js"></script>
        </head>

    <body class = "discord-theme">
        
        <div class="main-content">
            <form method = "POST", action = "">
                <h1 >Create Community</h1> <br>
                <input type = "text" class = "txtbox"id = "gapbox" placeholder="Community name..." name  ="comcreate">
                <br>
                <input type = "submit" class = "submitbutton" name = "action" value = "Create Community!"></input>
            </form>
        </div>   

        <div class="sidebar">
            
            <a href="../frontend/homepage.php">
                <img src="../frontend/img/home.png" alt="Home" />
            </a>

            <a href="../frontend/dashboard.php">
                <img src="../frontend/img/profile.png" alt="Profile" />
            </a>

            <a href="../php/create_post.php">
                <img src="../frontend/img/post.png" alt="Create Post" />
            </a>

            <a href="../php/index.php">
                <img src="../frontend/img/exit.png" alt="Logout" />
            </a>
        </div>

         
    </body>
    <script src = "../frontend/js/theme.js"></script>
</html>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"&&$_POST["action"] == "Create Community!"){
        $comcontent = filter_input(INPUT_POST,"comcreate",FILTER_DEFAULT);
        $comcontent = htmlspecialchars(trim($comcontent), ENT_QUOTES, 'UTF-8');

        if(empty($comcontent)){
            echo "Enter a valid name.";
            return;
        }

        $sql = "INSERT INTO topic (name)VALUES ('$comcontent')";

        try{
            mysqli_query($com_conn, $sql);
            echo "Created Community!<br>";
        }catch(mysqli_sql_exception){
            echo "Community name is taken.<br>";
        }  
    }
?>