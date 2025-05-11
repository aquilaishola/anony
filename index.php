<?php
require_once 'core/db.php';
require_once 'helpers/session.php';
require_once 'helpers/csrf.php';

date_default_timezone_set('Africa/Lagos');
$datetime = date('Y-m-d H:i:s');

$success = $error = $id_key = null;

// User ID validation
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_key = $_GET['id'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE id_key = ?");
    $stmt->bind_param("s", $id_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc()["username"];

        // Handling POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["send"])) {
            // CSRF validation
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $error = "Invalid CSRF token";
            } else {
                $message = filter_var($_POST['message'], FILTER_SANITIZE_SPECIAL_CHARS);
                $image = $_FILES['image'] ?? NULL;

                // Validate the message length
                if (strlen($message) > 300) {
                    $message = substr($message, 0, 300);
                }

                // Check if image is provided
                if ($image && $image['error'] === 0) {
                    // Validate and handle the image upload
                    $allowed_extensions = ['jpg',
                        'jpeg',
                        'png'];

                    $file_extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

                    $safe_filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($image['name'], PATHINFO_FILENAME));

                    // Combine sanitized filename with the extension
                    $safe_filename = $safe_filename . '.' . $file_extension;
                    // Check MIME type to ensure it's a valid image
                    $file_info = getimagesize($image['tmp_name']);
                    if ($file_info === false) {
                        $error = "Uploaded file is not a valid image.";
                    } else {
                        // Verify allowed file types based on extension and MIME type
                        if (in_array($file_extension, $allowed_extensions) && $image['size'] <= 1024 * 1024) {
                            // Ensure the upload directory is outside the web root for better security
                            $image_path = 'uploads/' . uniqid() . '_' . $safe_filename;

                            // Move the file to the uploads directory
                            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                                // Sanitize the image path and store in the database
                                $stmt = $conn->prepare("INSERT INTO messages (id_key, message, image_path, created_at) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("ssss", $id_key, $message, $image_path, $datetime);
                                $stmt->execute();

                                $success = "Message Sent Successfully 🎉";
                                $url = "";
                            } else {
                                $error = "Error uploading image. Please try again.";
                            }
                        } else {
                            $error = "Invalid image format or file size. Please upload a valid image (max 1MB).";
                        }
                    }
                } else {
                    // If no image is provided, insert the message only
                    $stmt = $conn->prepare("INSERT INTO messages (id_key, message, created_at) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $id_key, $message, $datetime);
                    $stmt->execute();

                    $success = "Message Sent Successfully 🎉";
                    $url = "";

                }
            }
        }
    } else {
        header("Location: login");
    }
} else {
    header("Location: login");
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
    <title>Send Anonymous Message to <?= htmlspecialchars($user) ?></title>
    
    <!-- Open Graph (OG) Tags -->
    <meta property="og:title" content="Send Anonymous Message to <?= htmlspecialchars($user) ?>">
    <meta property="og:description" content="Checkout ANONY, an anonymous message sender for sending messages without revealing your identity.">
    <meta property="og:image" content="https://anony.devaquila.xyz/assets/img/logo.png">
    <meta property="og:url" content="https://anony.devaquila.xyz">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ANONY">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Send Anonymous Message to <?= htmlspecialchars($user) ?>">
    <meta name="twitter:description" content="Checkout ANONY, an anonymous message sender for sending messages without revealing your identity.">
    <meta name="twitter:image" content="https://anony.devaquila.xyz/assets/img/logo.png">
    <meta name="twitter:site" content="@DevAquila">
  
    <!-- Favicon -->
    <link rel="icon" href="https://www.devaquila.xyz/assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style4.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
    <form id="message-form" method="POST" enctype="multipart/form-data" action="/<?= htmlspecialchars($id_key) ?>">
        <div class="container">
            <h2>Send a Message to <?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?></h2>
            <textarea id="message" name="message" rows="10" placeholder="Type your message here..." required></textarea>
            <input type="file" name="image" accept="image/*">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <p class="counter" id="counter">
                0 / 300
            </p>
            <button id="send-btn" name="send" type="submit">Send Message</button>

            <p>
                <a href="login">
                    Get your own link - Click here </a>
            </p>
        </div>
    </form>
    <?php require_once('helpers/sweetalert.php'); ?>
    <script src="assets/js/script4.js"></script>
</body>
</html>