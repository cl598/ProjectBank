<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?><form method="POST">

    <fieldset>
        <legend>TRANSFER FROM</legend>

        <label>Amount</label>
        <input type="text" id="amt" name="amt" min="0" max="<?php $bal ?>" required><br><br>

        <label>From this account...</label>
        <input type="text" id="srcact" name="srcact" min="1" required><br><br>
    </fieldset>

    <fieldset>
        <legend>TRANSFER HERE</legend>

        <label>Last name</label>
        <input type="text" id="lname" name="lname" required><br><br>

        <label>Last 4-digits SSN</label>
        <input type="text" id="ssn" name="ssn" min="0" maxlength="4" required><br><br>

        <label>... To this account</label>
        <input type="text" id="destact" name="destact" min="1" required><br><br>

        <textarea name="memo">Enter your memo here</textarea><br>

    </fieldset>

    <input type="submit" value="Submit">

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