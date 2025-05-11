<?php
require_once '../core/db.php';
require_once '../helpers/session.php';
require_once '../helpers/csrf.php';

if (isset($_SESSION["admin_user_id"])) {
    header("Location: index");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["admin_login"])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token";
    } else {
        $admin_username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($admin_id, $admin_hashed_password);

        if ($stmt->fetch() && password_verify($password, $admin_hashed_password)) {
            $_SESSION['admin_user_id'] = $admin_id;
            $_SESSION['admin_username'] = $admin_username;
            $success = "Login Successful.";
            $url = "index.php";
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Anonymous Message Sender">
    <meta name="author" content="Ishola Aquila">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style1.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Admin Login - ANONY</title>
</head>
<body>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="container">
            <h2 style="color: #321ACF"><u><b>Admin Login - ANONY</b></u></h2>
            <input type="text" name="username" placeholder="Username" required> <br>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" name="admin_login">Login</button>
        </div>
    </form>
    <?php require_once('../helpers/sweetalert.php'); ?>
</body>
</html>