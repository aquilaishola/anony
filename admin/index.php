<?php
require_once '../core/db.php';
require_once '../helpers/session.php';

if (!isset($_SESSION['admin_user_id'])) {
    header("Location: logout.php");
    exit();
}

$user_id = $_SESSION['admin_user_id'];
$admin_username = $_SESSION['admin_username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Anonymous Message Sender">
    <meta name="author" content="Ishola Aquila">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Admin - ANONY</title>
    <style>
     

    </style>
</head>
<body>
    <div class="container">
        <label for="link"><b><u><?= htmlspecialchars($admin_username, ENT_QUOTES, 'UTF-8') . "'s Admin Profile" ?></u></b></label>
        <p>
            This is ANONY Admin dashboard. Welcome 🤗 
        </p>
        <div class="link-container">
        <button style="width: 100%;" onclick="window.location.href='users.php'">
            <i class="fas fa-user-alt"></i> View Users
        </button>
        <button style="width: 100%;" onclick="window.location.href='messages.php'">
            <i class="fas fa-envelope"></i>  View Messages 
        </button>
    </div>

    <script src="../assets/js/script2.js"></script>
</body>
</html>