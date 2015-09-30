<?php
ini_set('max_execution_time', 300);
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once("utilSetUnsetSession.php");
include_once("utilDatabaseConnectivity.php"); 
if(!$accountIdSession)
{
print"<br><br><br><br><br><br><br><br><br>
    <center>
        <FONT color=\"red\" 
            font size=\"2\" 
            face=\"verdana\">
        
            <strong>
               You are not a authorizes user
            </strong>
        </font>
    </center>";
    //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
    exit();
}
/*$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; ////// local path
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; /// server Path
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
    
include_once($filePathToS3Wrapper);*/
    

$statusApk=1;
$query = "SELECT apk_type,apk_version_name,apk_heading,download_file_name FROM android_apk_upload_format WHERE apk_type=? AND status=? order by apk_version_name";
    $statement = $mysqli->prepare($query);
    //echo "statement=".$statement."<br>";
    if (false===$statement) 
    {
    die ('prepare() failed: ' . $mysqli->error);
    }
    $downloadTitle=$_POST['download_file_name_'.$i];
    
    //echo "downloadTitle=".$downloadTitle."<br>";
    $statusApk=1;
    $statement->bind_param('si',$apkType,$statusApk);
    $statement->execute();

//bind result variables
$statement->bind_result($apk_type, $apk_version_name, $apk_heading, $download_file_name);

print '<center><table border="0" width=40% class="menu">';

//fetch records
$version_name="";
while($statement->fetch()) {
    if($version_name!=$apk_version_name)
    {         
    print '<tr>';
    print '<td colspan=3 align="center"><b>'.$apk_version_name.'</b></td>';
    print '</tr>';  
    print '<tr>';    
    print '<td colspan=3><b>'.$apk_heading.'</b></td>';
    print '</tr>';  
    }
    $filePath="android/".$apk_type."/".$apk_version_name."/".$apk_heading."/".$download_file_name;
    print '<tr>';
    print '<td width=10%>&nbsp;</td>';
    print '<td width=50%>'.$download_file_name.'</td>';
    print "<td> &nbsp;
            <a href='#' onclick='javascript:deleteAndroidApkFile(\"".$filePath."\");'>
                Delete
            </a>
        </td>";
    print '</tr>';
    $version_name=$apk_version_name;
    
    }  
echo "</table></center>";
    $statement->close();


/*for($i=0;$i<sizeof($listDir);$i++)
{
    $listDir1=listDir("android/".$apkType."/".$listDir[$i]);
    echo "<br>";
    //print_r($listDir1);
    for($j=0;$j<sizeof($listDir1);$j++)
    {
        $listDir2=listDir("android/".$apkType."/".$listDir[$i]."/".$listDir1[$j]);
        print_r($listDir2);
        echo "<br>";
        for($k=0;$k<sizeof($listDir2);$k++)
        {
            echo "android/".$apkType."/".$listDir[$i]."/".$listDir1[$j]."/".$listDir2[$k];
            //$listFile=listFile("android/".$apkType."/".$listDir[$i]."/".$listDir[$j]."/".$listDir2[$k]);
            echo "<br>";
            //print_r($listFile);

            //$finalArr=array('apdType'=>$listDir[$i],'heading'=>$listDir1[$j],'fileFolder'=>$listDir2[$k],'fileName'=>)
        }
    }
}*/
//echo "filePath=".$filePathToS3Wrapper."<br>";   
?>
