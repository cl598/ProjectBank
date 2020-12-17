<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?><form method="POST">

    <fieldset>
        <legend>MAKE A TRANSFER</legend>

        <label>Amount</label>
        <input type="text" id="amt" name="amt" min="1" max="<?php $bal = null;
        safer_echo($bal) ?>" required><br><br>

        <label>From this account...</label>
        <input type="text" id="srcact" name="srcact" min="1" required><br><br>

        <label>... To this account</label>
        <input type="text" id="destact" name="destact" min="1" required><br><br>

        <textarea name="memo">Enter your memo here</textarea><br>
    </fieldset>

    <input type="submit" value="Submit">

</form>

<?php
if(isset($_POST["save"])){

    //TODO add proper validation/checks
    $name = $_POST["id"];
    $actdest = $_POST["act_dest_id"];
    $amt = $_POST["amount"];
    $acttype = $_POST["action_type"];
    $bal = $_POST["balance"];
    $mm = $_POST["memo"];
    $nst = date('Y-m-d H:i:s');
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Transactions (name, actdest, amt, acttype, bal, mm) VALUES(:name, :nst,:user)");
    $r = $stmt->execute([
        ":name"=>$name,
        ":actdest"=>$actdest,
        ":amt"=>$amt,
        ":acttype"=>$acttype,
        ":bal"=>$bal,
        ":nst"=>$nst,
        ":user"=>$user
    ]);

    // Transfer
    if(get_type(0) || get_type(1) && $acttype ="transfer"){
        get_transaction($amt != null,$bal != null);
    }

    if($r){
        flash("Created successfully with id ... " . $db->lastInsertId());
    }
    else{
        $e = $stmt->errorInfo();
        flash("Following error has occurred ... " . var_export($e, true));
    }
}
?>

<?php require(__DIR__ . "/partials/flash.php");