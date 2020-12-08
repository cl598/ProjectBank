<?php

// Error reporting
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pull in db.php to access the variables
require_once (__DIR__ . "/../lib/db.php");

$count = 0;
try{
    foreach(glob(__DIR__ . "/*.sql") as $filename){
        $sql[$filename] = file_get_contents($filename);
    }

    if(isset($sql) && $sql && count($sql) > 0){

        /***
         * Sort the array so queries are executed in anticipated order.
         * Be careful with naming, 1-9 is fine, 1-10 has 10 run after #1 due to string sorting.
         ***/

        ksort($sql);
        echo "<br><pre>" . var_export($sql, true) . "</pre><br>";

        // Connect to DB
        $db = getDB();

        /***
         * Let's make this function a bit smarter to save DB calls for small dev plans (i.e., heroku)
         ***/

        $stmt = $db->prepare("show tables");
        $stmt->execute();
        $count++;
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $t = [];

        // Convert it to a flat array
        foreach($tables as $row){
            foreach($row as $key => $value) {
                array_push($t, $value);
            }
        }

        foreach($sql as $key => $value){
            echo "<br>Running: " . $key;
            $lines = explode("(", $value, 2);
            if(count($lines) > 0){

                // Lines attempt to extract the table info from any create commands
                // to determine if they command should be skipped or not
                $line = $lines[0];

                // Clear out duplicate whitespace
                $line = preg_replace('!\s+!', ' ', $line);

                // Remove create table command
                $line = str_ireplace("create table", "", $line);

                // Remove if not exists command
                $line = str_ireplace("if not exists", "", $line);

                // Remove backticks
                $line = str_ireplace("`","",$line);

                // Trim whitespace in front and back
                $line = trim($line);
                if (in_array($line, $t)){
                    echo "<br>Blocked from running, table found in 'show tables' results.<br>";
                    continue;
                }
            }

            $stmt = $db->prepare($value);
            $result = $stmt->execute();
            $count++;
            $error = $stmt->errorInfo();

            if($error && $error[0] !== '00000'){
                echo "<br>Error:<pre>" . var_export($error,true) . "</pre><br>";
            }
            echo "<br>$key result: " . ($result>0?"Success":"Fail") . "<br>";

        }
        echo "<br> Init complete, used approximately $count db calls.<br>";
    }
    else{
        echo "I didn't find any files, please check the directory/directory contents/permissions";
    }
    $db = null;
}
catch(Exception $e){
    echo $e->getMessage();
    exit("Oh no! Something went wrong");
}
?>