<?php
require_once '../core/db.php';
require_once '../helpers/session.php';

if (!isset($_SESSION['admin_user_id'])) {
    header("Location: logout.php");
    exit();
}

$admin_user_id = $_SESSION['admin_user_id'];
$admin_username = $_SESSION['admin_username'];

// Handle AJAX request for messages
if (isset($_GET['fetch_messages'])) {
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = 50;

    $query = $conn->prepare("SELECT id, id_key, message, image_path, created_at FROM messages ORDER BY created_at DESC LIMIT ?, ?");
    $query->bind_param('ii', $offset, $limit);
    $query->execute();
    $messages_result = $query->get_result();

    while ($row = $messages_result->fetch_assoc()) {
        $message = nl2br(html_entity_decode($row['message'], ENT_QUOTES, 'UTF-8'));
        $imagePath = htmlspecialchars("../" . ($row['image_path'] ?? ''));
        $formattedDate = date("F j, Y, g:i a", strtotime($row['created_at']));
        $id_key = htmlspecialchars($row['id_key']);
        
        // Fetch the username based on the id_key
        $stmt = $conn->prepare("SELECT username FROM users WHERE id_key = ?");
        $stmt->bind_param("s", $id_key);
        $stmt->execute();
        $username_result = $stmt->get_result();

        if ($username_result->num_rows > 0) {
            $username = $username_result->fetch_assoc()["username"];
        }

        echo '
        <div class="message" id="message-' . $row['id'] . '">
            <div class="message-content">' . $message . '</div>
            <div class="message-date">' . $formattedDate . '</div>
            <div class="message-footer">
                <span data-image="' . $imagePath . '" class="view-image">' .
        (!empty($imagePath) ? 'Image Available' : 'No Image') . '</span>
                <div>
                    ' . htmlspecialchars($username) . '
                </div>
            </div>
            <div class="watermark">-ANONY</div>
        </div>';
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
    <link rel="stylesheet" href="../assets/css/style3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Messages - ANONY Admin</title>
</head>
<body>
    <div class="container">
        <label for="link" style="text-align: center"><b><u>Messages</u></b></label>
        <button style="width: 100%;" onclick="window.location.href='index.php'" class="home">
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
    <script src="../assets/js/script3.js"></script>
</script>
</body>
</html>