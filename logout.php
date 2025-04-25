<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the index page with a logout success message
header("Location: index.html");
exit();
?>
