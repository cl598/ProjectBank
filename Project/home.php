<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
// Email displays
$email = "";

if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>

<p>Welcome, <?php echo $email; ?>!</p>
<?php require(__DIR__ . "/partials/flash.php");
