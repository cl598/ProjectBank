<link rel="stylesheet" href="static/css/styles.css">
<?php require_once(__DIR__ . "/partials/nav.php"); ?><form method="POST">

    <label><u>CREATE A TRANSACTION</u></label><br><br>

    <button><a style=text-decoration:none href="deposit.php">Deposit here</a></button>
    <button><a style=text-decoration:none href="withdrawal.php">Withdraw here</a></button>
    <button><a style=text-decoration:none href="transfer.php">Transfer here</a></button>
    <button><a style=text-decoration:none href="sendmoney.php">Send money to others</a></button>


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