<?php
require_once 'core/db.php';
require_once 'helpers/session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch the id_key for the user
$stmt = $conn->prepare("SELECT id_key FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $id_key = $result->fetch_assoc()["id_key"];
} else {
    echo "No user found.";
    exit();
}

// Handle AJAX request for messages
if (isset($_GET['fetch_messages'])) {
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = 50;

    // Query messages for the user based on id_key
    $message_query = $conn->prepare("SELECT id, message, image_path, created_at FROM messages WHERE id_key = ? ORDER BY created_at DESC LIMIT ?, ?");
    $message_query->bind_param('sii', $id_key, $offset, $limit);
    $message_query->execute();
    $messages_result = $message_query->get_result();

    if ($messages_result->num_rows > 0) {
        while ($row = $messages_result->fetch_assoc()) {
            $message = nl2br(html_entity_decode($row['message'], ENT_QUOTES, 'UTF-8'));
            $imagePath = htmlspecialchars($row['image_path'] ?? '');
            $formattedDate = date("F j, Y, g:i a", strtotime($row['created_at']));
            echo '
<a href="view-message.php?id=' . $row['id'] . '" class="message-link">
    <div class="message" id="message-' . $row['id'] . '">
        <div class="message-content">' . $message . '</div>
        <div class="message-date">' . $formattedDate . '</div>
        <div class="message-footer">
            <span data-image="' . $imagePath . '" class="view-image">' .
    (!empty($imagePath) ? 'Image Available' : 'No Image') . '</span>
            <div class="social-buttons">
                <button class="share-btn whatsapp" data-id="message-' . $row['id'] . '">
                    <i class="fab fa-whatsapp"></i>
                </button>
                <button class="share-btn facebook" data-id="message-' . $row['id'] . '">
                    <i class="fab fa-facebook"></i>
                </button>
            </div>
        </div>
        <div class="watermark">-ANONY</div>
    </div>
</a>';
        }
    } else {
        echo "No messages found." . "<br>";
    }
    exit;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/style3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Messages - ANONY</title>
</head>
<body>
    <div class="container">
        <label for="link" style="text-align: center"><b><u><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "'s Messages" ?></u></b></label>
        <button style="width: 100%;" onclick="window.location.href='home'" class="home">
            <i class="fas fa-house"></i> Go Home
        </button>
        <div id="message-container"></div>
        <div class="load-more">
            <button id="load-more-btn">Load More</button>
        </div>
    </div>

    <div class="modal" id="image-modal">
        <span class="modal-close" id="modal-close">X</span>
        <img id="modal-image" src="" alt="Message Image">
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script3.js"></script>
</script>
</body>
</html>