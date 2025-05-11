<?php
require_once 'core/db.php';
require_once 'helpers/session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid message ID.";
    exit();
}

$message_id = $_GET['id'];

// Fetch the message from the database
$stmt = $conn->prepare("SELECT message, image_path, created_at FROM messages WHERE id = ?");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Message not found.";
    exit();
}

$row = $result->fetch_assoc();
$message = nl2br(html_entity_decode($row['message'], ENT_QUOTES, 'UTF-8'));
$imagePath = htmlspecialchars($row['image_path'] ?? '');
$formattedDate = date("F j, Y, g:i a", strtotime($row['created_at']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
    <link rel="stylesheet" href="assets/css/view-message.css">
</head>
<body>
    <div class="message-container">
        <div class="message-box">
            <div class="message-content"><?= $message ?></div>
            <?php if (!empty($imagePath)): ?>
                <img src="<?= $imagePath ?>" class="message-image" alt="Message Image">
            <?php endif; ?>
            <div class="message-date"><?= $formattedDate ?></div>
        </div>
        <button onclick="window.history.back()" class="back-button">Go Back</button>
    </div>
</body>
</html>