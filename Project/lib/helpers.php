<?php

// Start our session here so we don't need to worry about it on other pages
session_start();
require_once(__DIR__ . "/db.php");

// Office hour assistance
function getURL($path){
    return $_SERVER["CONTEXT_PREFIX"] . "/ProjBank/project/$path";
}

function is_logged_in() {
    return isset($_SESSION["user"]);
}

function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] == $role) {
                return true;
            }
        }
    }return false;
}

function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }return "";
}

function get_name(){
    if (is_logged_in() && isset($_SESSION["user"]["name"])) {
        return $_SESSION["user"]["name"];
    }return "";
}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }return -1;
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

function get_type($n){
    if(is_logged_in()){
        switch ($n) {
            case 0:
                // Checking
                break;

            case 1:
                // Savings
                break;
            case 2:
                // Loan
                break;
            default:
                echo "Oops, please try again!";
                break;
        }
    }
}

function get_transaction($amount, $balance){
    $n = null;
    get_type($n);

    if(is_logged_in()){
        switch ($n) {
            case 0:
                // Check
                while($amount < 0 || $amount > $balance){
                    echo "Oops, please try a different amount!";
                }

                // Deposit
                while($amount += $balance){
                    echo "You have added $amount to your $n account";
                }

                break;

            case 1:
                // Check
                while($amount < 0 || $amount > $balance){
                    echo "Oops, please try a different amount!";
                }

                // Withdrawal
                while($amount -= $balance){
                    echo "You have took out $amount from your $n account";
                }

                break;
            default:
                echo "Oops, please try again!";
                break;
        }
    }
}
// Flash feature
function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }return array();
}
// End flash

?>
