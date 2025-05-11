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
if (isset($_GET['fetch_users'])) {
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = 50;

    $query = $conn->prepare("SELECT username, email, id_key, created_at FROM users ORDER BY created_at DESC LIMIT ?, ?");
    $query->bind_param('ii', $offset, $limit);
    $query->execute();
    $user_result = $query->get_result();

    while ($row = $user_result->fetch_assoc()) {
        $username = htmlspecialchars($row['username']);
        $email = htmlspecialchars($row['email']);
        $formattedDate = date("F j, Y, g:i a", strtotime($row['created_at']));
        $id_key = htmlspecialchars($row['id_key']);
        
        echo '
            <div class="message">
                <div class="message-content">' . $username . '</div>
                <div class="message-date">' . $formattedDate . '</div>
                <div class="message-content">' . $email . '</div>
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
    <title>Users - ANONY Admin</title>
</head>
<body>
    <div class="container">
        <label for="link" style="text-align: center"><b><u>Users</u></b></label>
        <button style="width: 100%;" onclick="window.location.href='index.php'" class="home">
            <i class="fas fa-house"></i> Go Home
        </button>
        <div id="message-container"></div>
        <div class="load-more">
            <button id="load-more-btn">Load More</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    
    let offset = 0;
    
// Function to fetch messages
function fetchUsers() {
    $.ajax({
        url: 'users.php',
        type: 'GET',
        data: {
            fetch_users: 1, offset: offset
        },
        success: function (response) {
            $('#message-container').append(response);
            offset += 50;
        }
    });
}

// Initial fetch
fetchUsers();

// Load more messages
$('#load-more-btn').click(function () {
    fetchUsers();
});

    
</script>
</body>
</html>