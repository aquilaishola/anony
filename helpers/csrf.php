<?php

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CSRF token for the form
$csrf_token = $_SESSION['csrf_token'];

?>