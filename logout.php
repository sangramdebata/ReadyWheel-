<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear localStorage through JavaScript
echo "<script>
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userData');
    window.location.href = 'index.php';
</script>";
?> 