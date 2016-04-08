<?php
include_once("utilSetUnsetSession.php");
include_once("utilDatabaseConnectivity.php");


$passwordStr=md5($_POST['password']);
$query = "SELECT account_id,permission FROM android_apk_account WHERE user_id=? AND password=?";
$statement = $mysqli->prepare($query);

if ( false===$statement ) 
{
    die ('prepare() failed: ' . $mysqli->error);
}

$statement->bind_param('ss', $userId,$passwordStr);
$statement->execute();
$statement->store_result();
//echo $statement->fullQuery;
$numrows = $statement->num_rows;
//echo "numRow=".$numrows."<br>";

if($numrows==0)
{
    print"<br><br><br><br><br><br><br><br><br>
        <center>
            <FONT color=\"red\" 
            font size=\"2\" 
            face=\"verdana\">
                <strong>
                    Not a Registered User!  ...
                </strong>
            </font>
        </center>";
 echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">"; 
 exit();
}

$statement->bind_result($account_id, $permission);     
$statement->fetch();
$_SESSION['accountIdSession'] =$account_id;
$_SESSION['permissionSession']=$permission;    
$statement->free_result();    
$statement->close();
 $mysqli->close();    
 print"<br><br><br><br><br><br><br><br><br>
            <center>
                <FONT color=\"green\" 
                font size=\"2\" 
                face=\"verdana\">
                    <strong>
                        Redirecting ... 
                    </strong>
                </font>
            </center>";
 echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=home.php\">";

?>
