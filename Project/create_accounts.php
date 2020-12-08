<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?><form method="POST">

    <label><u>CREATE AN ACCOUNT</u></label><br>

    <label>Initial amount</label>
    <input type="text" id="amt" name="amt" required><br><br>

    <label>Types</label>
    <select name="types">
        <option value="0">Please select a type</option>
        <option value="1">Checking</option>
        <option value="2">Savings</option>
    </select>
    <input type="submit" name="save" value="Create"><br>

    <a href="loan.php"><input type="button" name="loan" value ="Submit a loan"/></a>
</form>

<?php
if(isset($_POST["save"])){

    //TODO add proper validation/checks
    $name = $_POST["name"];
    $actnum = $_POST["account_number"];
    $acttype = $_POST["account_type"];
    $bal = $_POST["balance"];
    $nst = date('Y-m-d H:i:s');
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (name, actnum, acttype, bal, next_stage_time, user_id) VALUES(:name, :state, :nst,:user)");
    $r = $stmt->execute([
        ":name"=>$name,
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