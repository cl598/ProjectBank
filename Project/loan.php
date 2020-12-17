<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?><form method="POST">

    <fieldset>
        <legend>TAKE OUT A LOAN</legend>

        <label>Amount</label>
        <input type="text" id="amt" name="amt" min="1" max="<?php $bal = null;
        safer_echo($bal) ?>" required><br><br>

        <label>Account Number Reference</label>
        <input type="text" id="actnum" name="actnum" required><br><br>
        <input type="submit" value="Submit">
    </fieldset>

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

    // Withdrawal
    if($acttype ="withdraw"){
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
