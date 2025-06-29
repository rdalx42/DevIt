<?php
session_start();
include("../php/db.php");
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Register</title>
        <link rel="icon" href="../frontend/img/devit_icon.png" type="image/png">
        <link rel="stylesheet" href="../frontend/css/style.css">
    </head>
    <body class = "discord-theme">
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class = "authform">
            
            <h1>Authentification</h1>

            <input class = "txtbox" type="text" name="username" placeholder="Username...">
            <br>
            <input class = "txtbox" type="password" name="password" placeholder="Password...">
            <br>
            <input class = "form_button_important" type="submit" name="action" value="Login">
            <br>
            <input class = "form_button_important" type="submit" name="action" value="Register">
            <br>
    </form>
    <script src = "../frontend/js/theme.js"></script>
    </body>
</html>

<?php
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // filter both username and password in case they contain a virus script.
    $username = filter_input(INPUT_POST, "username", FILTER_DEFAULT);
    $username = htmlspecialchars(trim($username), ENT_QUOTES, 'UTF-8');
    
    $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);
    $password = htmlspecialchars(trim($password), ENT_QUOTES, 'UTF-8');  
    
    if (empty($username) || empty($password)) {
        echo "Please fill in both fields.";
        return;
    }

    // hash important data.
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($_POST["action"] == "Register") {
        
        $description = "No description yet.";
        $likes = 0;
        $sql = "INSERT INTO users (user, passwords, description, likes)
                VALUES ('$username', '$hash', '$description', '$likes')";

        try {
            mysqli_query($conn, $sql);
            echo "Registered!<br>";
        } catch (mysqli_sql_exception) {
            echo "Username is taken.<br>";
        }  
    } elseif ($_POST["action"] == "Login") {

        // nested if statements for login logic.
        // fetch password AND likes from DB
        $sql = "SELECT passwords, likes FROM users WHERE user = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $found_row = mysqli_fetch_assoc($result);
            if (password_verify($password, $found_row["passwords"])) {
                
                echo "Login successful!<br>";
                $_SESSION["username"] = $username;
                $_SESSION["likes"] = $found_row["likes"];  // set actual likes from db
                
                ob_clean();
                header("Location: ../frontend/homepage.php");
                exit();
            } else {
                echo "Wrong password.<br>";
            }
        } else {
            echo "Username not found.<br>";
        }
    }      
}

mysqli_close($conn);
?>
