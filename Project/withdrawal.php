<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?><form method="POST">

    <fieldset>
        <legend>MAKE A WITHDRAWAL</legend>
        <label>Amount</label>
        <input type="text" id="amt" name="amt" min="0" max="<?php $bal ?>" required><br><br>
        <label>Account Number</label>
        <input type="text" id="actnum" name="actnum" required><br><br>
        <input type="submit" value="Submit">
    </fieldset>

</form>

<?php
if(isset($_POST["save"])){

    //TODO add proper validation/checks
    $name = $_POST["name"];
    $state = $_POST["state"];
    $actnum = $_POST["account_number"];
    $acttype = $_POST["account_type"];
    $bal = $_POST["balance"];
    $nst = date('Y-m-d H:i:s');
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (name, state, actnum, acttype, bal, next_stage_time, user_id) VALUES(:name, :state, :nst,:user)");
    $r = $stmt->execute([
        ":name"=>$name,
        ":state"=>$state,
        ":actnum"=>$actnum,
        ":acttype"=>$acttype,
        ":bal"=>$bal,
        ":nst"=>$nst,
        ":user"=>$user
    ]);
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