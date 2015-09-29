<script>
function downloadApkFile1(sourceFilePath,destinationFileName)
{
    //alert("sourceFilePath="+sourceFilePath+"apkFileName="+destinationFileName);
    document.getElementById("sourceFilePath").value="";
    document.getElementById("sourceFilePath").value=sourceFilePath;
    
    document.getElementById("destinationFileName").value="";
    document.getElementById("destinationFileName").value=destinationFileName;
    document.downloadApkFile.submit();
}
</script>
<?php
ini_set('max_execution_time', 300);
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once("utilSetUnsetSession.php");
include_once("utilDatabaseConnectivity.php"); 

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



//fetch records
$version_name="";
$sNo=1;
echo'<form name="downloadApkFile" method="post" action="downloadAndroidApk.php" target="_blank">
    <input type="hidden" id="sourceFilePath" name="sourceFilePath">
           <input type="hidden" id="destinationFileName" name="destinationFileName">';
while($statement->fetch()) {
    if($version_name!=$apk_version_name)
    {         
    print '<center><div><b>'.$apk_version_name.'</b></div></center><br>
            <div style="margin-left :19%;"><b>&nbsp;'.$apk_heading.'</b></div><br>';
    print'<center><table border="1" class="menu" cellspacing="3" cellpadding="3" rules="all" bordercolor="grey">';  
    }
    $filePath="android/".$apk_type."/".$apk_version_name."/".$apk_heading."/".$download_file_name;
    
    $downloadPathLink="http://itrackdevelop/android/downloadApkUrl.php?aT=".$apk_type."&vN=".$apk_version_name."&aH=".$apk_heading."&dFN=".$download_file_name;
    print '<tr>';
    print '<td>'.$sNo.'</td>';
    print '<td>'.$downloadPathLink.'</td>';
     print '<td>'.$download_file_name.'</td>';
    print "<td> &nbsp;
            <a href='#' onclick='javascript:downloadApkFile1(\"".$filePath."\",\"".$download_file_name."\");'>
                Download
            </a>
        </td>";
    print '</tr>';
    $sNo++;
    $version_name=$apk_version_name;
    
    }  
echo "</table></center>
   </form>";
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
