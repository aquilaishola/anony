<?php
require_once 'core/db.php';
require_once 'helpers/session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch id_key
$stmt = $conn->prepare("SELECT id_key FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($id_key);
$stmt->fetch();
$stmt->close();
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
    <link rel="stylesheet" href="assets/css/style2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Home - ANONY</title>
    
    <style>
     

    </style>
</head>
<body>
    <div class="container">
        <label for="link"><b><u><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "'s Profile" ?></u></b></label>
        <p>
            Share your profile link ❤️ to get responses from your friends. Go to "View Messages" to check out the responses.
        </p>
        <div class="link-container">
            <input
            type="url"
            placeholder="Your link will appear here"
            name="link"
            id="link"
            value="https://anony.devaquila.xyz/<?= htmlspecialchars($id_key) ?>"
            readonly>
            <button type="button" id="copyBtn" onclick="copyToClipboard()">Copy</button>
        </div>
        <button style="width: 100%;" onclick="window.location.href='messages'">
            <i class="fas fa-envelope"></i> View Messages
        </button>
        <button style="width: 100%;" href="https://wa.me/2347070520753?text=Hi+From+Anony">
            <i class="fas fa-phone"></i>  Contact Us
        </button>

        <div class="social-buttons">
            <a href="#" class="facebook" onclick="shareToFacebook()"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="whatsapp" onclick="shareToWhatsApp()"><i class="fab fa-whatsapp"></i></a>
            <a href="#" class="instagram" onclick="shareToInstagram()"><i class="fab fa-instagram"></i></a>
        </div>
    </div>

    <script src="assets/js/script2.js"></script>
</body>
</html>