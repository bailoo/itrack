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

$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; //local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; //server path

$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";
include_once($filePathToS3Wrapper);

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
$cmpHeading="";
$sNo=1;
while($statement->fetch()) 
{
    
    $filePath="android/".$apk_type."/".$apk_version_name."/".$apk_heading."/".$download_file_name;    
    $S3Filename="android/".$apk_type."/".$apk_version_name."/".$apk_heading."/".$download_file_name;
    //echo "serverFilePath=".$S3Filename."<br>";
    $sourcefileNameArr=listFile($S3Filename);
    //print_r($sourcefileNameArr);
    $sourceFilePath=$S3Filename."/".$sourcefileNameArr[0]['name'];
    $destinationFileName=$sourcefileNameArr[0]['name'];
    $tmpFilePath="tmpFolder/".$destinationFileName;
    if(!file_exists($tmpFilePath))
    {
        $overwrite=true;
        $copyResult=copyFile($sourceFilePath,$tmpFilePath,$overwrite); 
    }   
    
    //$downloadPathLink="http://www.itracksolution.com/android/downloadApkUrl.php?aT=".$apk_type."&vN=".$apk_version_name."&aH=".$apk_heading."&dFN=".$download_file_name;
    $downloadPathLink="http://www.itracksolution.com/android/downloadApkUrl.php?fileName=".$destinationFileName;
    $arrKey=$apk_version_name."###".$apk_heading;
    $apkDetailArr[$arrKey][]=array(
                                    'downloadPathLink'=>$downloadPathLink,
                                    'download_file_name'=>$download_file_name,
                                    "filePath"=>$filePath  
                                );
}

print_r($apkDetailArr);

$statement->close();




/*while($statement->fetch()) 
{
    if($version_name!=$apk_version_name)
    {         
        echo '<center><div><b>'.$apk_version_name.'</b></div></center><br>';    
    }
    if($apk_heading!=$cmpHeading)
    {
        //echo "in if1<br>";
        $sNo=1;
        echo'<div style="margin-left :19%;"><b>&nbsp;'.$apk_heading.'</b></div><br>';
        echo'<center><table border="1" class="menu" cellspacing="3" cellpadding="3" rules="all" bordercolor="grey">';  
    }
    $filePath="android/".$apk_type."/".$apk_version_name."/".$apk_heading."/".$download_file_name;
    
    $S3Filename="android/".$apk_type."/".$apk_version_name."/".$apk_heading."/".$download_file_name;
    //echo "serverFilePath=".$S3Filename."<br>";
    $sourcefileNameArr=listFile($S3Filename);
    //print_r($sourcefileNameArr);
    $sourceFilePath=$S3Filename."/".$sourcefileNameArr[0]['name'];
    $destinationFileName=$sourcefileNameArr[0]['name'];
    $tmpFilePath="tmpFolder/".$destinationFileName;
    $overwrite=true;
    $copyResult=copyFile($sourceFilePath,$tmpFilePath,$overwrite);
    //$downloadPathLink="http://www.itracksolution.com/android/downloadApkUrl.php?aT=".$apk_type."&vN=".$apk_version_name."&aH=".$apk_heading."&dFN=".$download_file_name;
    $downloadPathLink="http://www.itracksolution.com/android/downloadApkUrl.php?fileName=".$destinationFileName;
   
    echo '<tr>';
    echo '<td>'.$sNo.'</td>';
    echo '<td>'.$downloadPathLink.'</td>';
     echo '<td>'.$download_file_name.'</td>';
    echo "<td> &nbsp;
            <a href='#' onclick='javascript:downloadApkFile1(\"".$filePath."\",\"".$download_file_name."\");'>
                Download
            </a>
        </td>";
    echo '</tr>';
    if($apk_heading!=$cmpHeading && $sNo!=1)
    {
        //echo "in if2<br>";
      echo'</table>';
    }
    $sNo++;
    $version_name=$apk_version_name;
    $cmpHeading=$apk_heading;
    
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
//echo "filePath=".$filePathToS3Wrapper."<br>";   */
?>
