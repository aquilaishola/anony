<?php
require_once 'core/db.php';
require_once 'helpers/session.php';
require_once 'helpers/csrf.php';

date_default_timezone_set('Africa/Lagos');
$datetime = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["signup"])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token";
    } else {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $id_key = bin2hex(random_bytes(2)) . substr($username, -3);

        $email_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $email_stmt->bind_param("s", $email);
        $email_stmt->execute();
        $email_result = $email_stmt->get_result();

        $username_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $username_stmt->bind_param("s", $username);
        $username_stmt->execute();
        $username_result = $username_stmt->get_result();

        if (empty($username) || empty($email) || empty($password)) {
            $error = "All fields are required";
        } elseif (strlen($username) < 4) {
            $error = "Username is too short";
        } elseif (strlen($email) > 30) {
            $error = "Email is too long";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid Email Format";
        } elseif (!preg_match('/^(?=.*[0-9])(?=.*[\W]).{6,}$/', $password)) {
            $error = "Password must be at least 6 characters, include a special symbol, and a number";
        } elseif ($email_result->num_rows > 0 || $username_result->num_rows > 0) {
            $error = "Email or Username already exists";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, id_key, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $password_hashed, $id_key, $datetime);

            if ($stmt->execute()) {
                $success = "Registration Successful. Proceed To login.";
                $url = "login";
            } else {
                $error = "Unable to Complete Registration";
            }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/style1.css">

</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="container">
            <h2 style="color: #321ACF"><u><b>Signup - ANONY</b></u></h2>
            <input type="text" name="username" placeholder="Username" minlength="4" maxlength="30" required>
            <input type="email" name="email" placeholder="Email" maxlength="30" required>
            <input type="password" id="password" name="password" placeholder="Password" minlength="6" required oninput="validatePassword()">
            
            <small>Password Requirements:</small>
            <ul style="list-style-type: none">
                <li><input type="checkbox" id="lengthCheck" disabled> At least 6 characters</li>
                <li><input type="checkbox" id="specialCharCheck" disabled> At least one special symbol</li>
                <li><input type="checkbox" id="numberCheck" disabled> At least one number</li>
            </ul>
            
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" id="signupButton" name="signup" disabled>Sign Up</button>
            <p>
                Already have an account? <a style="color: #321ACF" href="login.php">Login Now!</a>
            </p>
        </div>
    </form>
    <script src="assets/js/script1.js"></script>
    <?php require_once('helpers/sweetalert.php'); ?>
</body>
</html>