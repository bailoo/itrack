<?php
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
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3]; ////// local path
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]; /// server Path
$filePathToS3Wrapper=$pathToRoot."/s3/S3Wrapper.php";

//echo "filePath=".$filePathToS3Wrapper."<br>";

include_once($filePathToS3Wrapper);
     	
$fileUploadFlag=0;
for($i=1;$i<=$noOfFiles;$i++)
{
    $query = "SELECT download_file_name FROM android_apk_upload_format WHERE apk_type=? AND apk_version_name=?".
    " AND apk_heading=? AND download_file_name=? AND status=?";
    $statement = $mysqli->prepare($query);
    //echo "statement=".$statement."<br>";
    if (false===$statement) 
    {
    die ('prepare() failed: ' . $mysqli->error);
    }
    $downloadTitle=$_POST['download_file_name_'.$i];
    
    //echo "downloadTitle=".$downloadTitle."<br>";
    $statusApk=1;
    $statement->bind_param('ssssi',$apkType,$apkVersionName,$apkHeading,$downloadTitle,$statusApk);
    $statement->execute();
    $statement->store_result();
    //echo $statement->fullQuery;
    $numrows = $statement->num_rows;
    //echo "numrows=".$numrows."<br>";
    $statement->close();
    if($numrows>0)
    {
    echo"<br>
        <font color=\"Red\" size=4>
            <strong>
                ".$downloadTitle." This Apk Id Already Exist! Please try another Apk Id ...
            </strong>
        </font>";
    }
    else
    {
    
       $targetFile="android/".$apkType."/".$apkVersionName."/".$apkHeading."/".$downloadTitle."/".$_FILES["upload_file_$i"]["name"];
       // echo "targetFile=".$targetFile."<br>";   
        //echo "fileSize=".$_FILES[$uploadFileNameArr[3]]["size"]."<br>";
        if($_FILES["upload_file_$i"]["name"]!="")
        {
            //echo "targetFile=".$targetFile."<br>"; 
            $uploadStatus=uploadFile($targetFile,$_FILES["upload_file_$i"]["tmp_name"],true);        
            if($uploadStatus==1)
            {
                //$fileUploadFlag=1;
                date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
                $createDate=date("Y-m-d H:i:s");

                $query = "INSERT INTO android_apk_upload_format (apk_type, apk_version_name,apk_heading,".
                        "download_file_name,status,create_id,create_date)".
                        " VALUES(?, ?, ?, ?, ?, ?, ?)";
                $statement = $mysqli->prepare($query);

                //bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
                $statement->bind_param('ssssiis', 
                        $apkType, 
                        $apkVersionName,
                        $apkHeading,
                        $downloadTitle,
                        $statusApk,
                        $accountIdSession,
                        $createDate
                    );

                    if($statement->execute())
                    {
                        print '<br>Record inserted and inserted record is : ' .$_FILES["upload_file_$i"]["name"].'<br />';
                    }
                    else
                    {                
                        die('Error : ('. $mysqli->errno .') '. $mysqli->error);
                    }
                    $statement->close(); 
            }
        }
    }
}

/*if($fileUploadFlag==1)
{
    echo "File Uploaded Successfully";
}*/
?>
        