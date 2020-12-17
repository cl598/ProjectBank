<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
// Since this function call is included we can omit it here. Having multiple calls to session_start() will cause errors/warnings
//session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

echo "You have successfully logged out!<br>";
?>

<?php require(__DIR__ . "/partials/flash.php");?>
