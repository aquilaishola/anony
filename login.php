 
 <?php
require_once 'core/db.php';
require_once 'helpers/session.php';
require_once 'helpers/csrf.php';

if (isset($_SESSION["user_id"])) {
    header("Location: home");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login"])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token";
    } else {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, password, id_key FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $hashed_password, $id_key);

        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['id_key'] = $id_key;
            $_SESSION['username'] = $username;

            $success = "Login Successful.";
            $url = "home";
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Checkout ANONY, an anonymous message sender for sending messages without revealing your identity.">
    <meta name="keywords" content="anony, anonymous,message,sender,link,Aquila,chippapay,devaquila">
    <meta name="author" content="Ishola Aquila">
    <meta name="robots" content="index, follow">
    <title>ANONY - Anonymous Message Sender</title>
    <meta name="monetag" content="60712117bc418e7ea3f917cce4076f11">
    <!-- Open Graph (OG) Tags -->
    <meta property="og:title" content"ANONY - Anonymous Message Sender" >
    <meta property="og:description" contnt="Checkout ANONY, an anonymous message sender for sending messages without revealing your identity.">
    <meta property="og:image" content="https://anony.devaquila.xyz/assets/img/logo.png">
    <meta property="og:url" content="https://anony.devaquila.xyz">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ANONY">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="ANONY - Anonymous Message Sender">
    <meta name="twitter:description" content="Checkout ANONY, an anonymous message sender for sending messages without revealing your identity.">
    <meta name="twitter:image" content="https://anony.devaquila.xyz/assets/img/logo.png">
    <meta name="twitter:site" content="@DevAquila">
    
        <link rel="icon" href="https://anony.devaquila.xyz/assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style1.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login - ANONY</title>
    
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="container">
            <h2 style="color: #321ACF"><u><b>Login - ANONY</b></u></h2>
            <input type="text" name="username" placeholder="Username" required> <br>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" name="login">Login</button>
            <p>
                New here? <a style="color: #321ACF" href="signup.php">Sign Up Now!</a>
            </p>
        </div>
    </form>
    <?php require_once('helpers/sweetalert.php'); ?>
</body>