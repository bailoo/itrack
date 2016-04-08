<html>
<head>
  <title>GCM Demo</title>
</head>
<body>
<?
require_once("../objects/Core.php");
require_once("../objects/models/DeviceRegistration.php");
require_once("../objects/controllers/GCMPush.php");
require_once("../objects/Settings.php");

$core = new Core();
global $core;
$core->debugger(Settings::$debug);

$db = new DatabaseConn(Settings::$db_server, Settings::$db_user, Settings::$db_pass, Settings::$db_name);
$db_sets = array("prod"=>$db);
$db_key = "prod";
$core->connect_db($db_sets[$db_key], $db_key, true);
$core->debug("Database Connected");  

?>

  <form method="post" action="insert_action.php">
  <h3>Please enter New Registration ID</h3>
  <input type="text" name="reg_id" id="red_id">
  <input type="submit" name="ADD" value="ADD" >
  </form>
 
 
  
</body>
</html>