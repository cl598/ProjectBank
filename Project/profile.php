<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
// Note: we have this up here, so our update happens before our get/fetch
// that way we'll fetch the updated data and have it correctly reflect on the form below
// As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    //die(header("Location: login.php"));
}

$db = getDB();

// Save data
if (isset($_POST["saved"])) {

    $isValid = true;
    // Check if our email changed
    $newEmail = get_email();
    if (get_email() != $_POST["email"]) {

        //TODO we'll need to check if the email is available
        $email = $_POST["email"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where email = :email");
        $stmt->execute([":email" => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Default it to a failure scenario
        $inUse = 1;
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            echo "Email is already in use";
            //for now we can just stop the rest of the update
            $isValid = false;
        }else {
            $newEmail = $email;
        }
    }
    $newUsername = get_username();
    if (get_username() != $_POST["username"]) {
        $username = $_POST["username"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where username = :username");
        $stmt->execute([":username" => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            echo "Username is already in use";
            $isValid = false;
        }else {
            $newUsername = $username;
        }
    }

    $newName = get_name();
    if(get_name() != $_POST["name"]){
        $name = $_POST["name"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Roles where name = :name");
        $stmt->execute([":name" => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse =1;
        if($result && isset($result["InUse"])){
            try{
                $inUse = intval($result["InUse"]);
            }catch(Exception $e){

            }
        }
    }

    if ($isValid) {
        $stmt = $db->prepare("UPDATE Users set email = :email, username= :username where id = :id");
        $r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
        if ($r) {
            echo "Updated profile";
        }else {
            echo "Error updating profile";
        }

        if (!empty($_POST["password"]) && !empty($_POST["confirm"])) {
            if ($_POST["password"] == $_POST["confirm"]) {
                $password = $_POST["password"];
                $hash = password_hash($password, PASSWORD_BCRYPT);
                //this one we'll do separate
                $stmt = $db->prepare("UPDATE Users set password = :password where id = :id");
                $r = $stmt->execute([":id" => get_user_id(), ":password" => $hash]);
                if ($r) {
                    echo "Reset password";
                }else {
                    echo "Error resetting password";
                }
            }
        }
// Fetch/select fresh data in case anything changed
        $stmt = $db->prepare("SELECT email, username from Users WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => get_user_id()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $email = $result["email"];
            $username = $result["username"];
            //let's update our session too
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["username"] = $username;
        }
    }else {
        //else for $isValid, though don't need to put anything here since the specific failure will output the message
    }
}
?>

<form method="POST">

    <label for="email">Email</label>
    <input type="email" name="email" value="<?php safer_echo(get_email()); ?>" required/>

    <label for="username">Username</label>
    <input type="text" maxlength="60" name="username" value="<?php safer_echo(get_username()); ?>" required/>

    <label for="name">Name</label>
    <?php safer_echo(get_name());?>

    <label for="pw">Password</label>
    <input type="password" name="password" required/>

    <label for="cpw">Confirm password</label>
    <input type="password" name="confirm" required/>

    <input type="submit" name="saved" value="Save Profile"/>
</form>

<?php require(__DIR__ . "/partials/flash.php");?>
