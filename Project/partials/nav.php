<link rel="stylesheet" href="../static/css/styles.css">

<?php
// We'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<nav>
    <ul class="nav">

        <li class="nav-item"><a class="nav-link" href="<?php echo getURL("home.php"); ?>">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("login.php"); ?>">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("register.php"); ?>">Register</a></li>
        <?php endif; ?>

        <?php if (has_role("Admin")): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test_accounts/create_accounts.php"); ?>">Create an account</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test_accounts/view_accounts.php"); ?>">View accounts</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test_transactions/create_transactions.php"); ?>">Create a transaction</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test_transactions/view_transactions.php"); ?>">View transactions</a></li>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("accounts.php"); ?>">Accounts</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("profile.php"); ?>">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("logout.php"); ?>">Logout</a></li>
        <?php endif; ?>

    </ul>
</nav>
