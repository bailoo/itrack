<?php

if (!class_exists('S3')) require_once 'S3.php';

// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIAMXHEMT2IF2PUKQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");

// Pointless without your keys!
if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
	"define('awsAccessKey', 'change-me');\ndefine('awsSecretKey', 'change-me');\n\n");


function dirExistsS3($DirName)
{
    $DirPath = $DirName; // File to upload, we'll use the S3 class since it exists
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    $BucketList = $s3->getBucket($bucketName);
    foreach ($BucketList as $key => $values)
    {
        if(strlen($key)<strlen($DirPath))
        {
            continue;
        }
        else if(strcmp($DirPath,substr($key,0,strlen($DirPath)))==0)
        {
            return true;
        }
    } 
    return false;
}

function fileExistsS3($FileName)
{
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        //echo "file exist false 1\n";
        return false;
    }
    
    $BucketList = $s3->getBucket($bucketName);
    //echo "BucketSize=".sizeof($BucketList)."\n";
    foreach ($BucketList as $key => $values)
    {
        //echo "key=".$key." strlen key=".strlen($key)." strlen FileName=".strlen($FileName)." FileName=".$FileName."\n";
        if(strlen($key)<strlen($FileName))
        {
            continue;
        }
        else if(strcmp($key,$FileName)==0)
        {
            return true;
        }
    } 
    return false;
}

function uploadFile($S3FileName, $LocalFilePath, $overwrite)
{
    $upldFile = $LocalFilePath; // File to upload, we'll use the S3 class since it exists
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        //echo "Upload false 1 \n";
        return false;
    }
    if (!file_exists($upldFile) || !is_file($upldFile))
    {
       // echo "Upload false 2 \n";
        return false;
    }
    
    if(($overwrite==true) || (!fileExistsS3($upldFile)))
    {
        if(!($s3->putObjectFile($upldFile, $bucketName, $S3FileName, S3::ACL_PUBLIC_READ)))
        {
            //echo "Upload false 3 \n";
            return false;
        }
    }
    return true;
}

function copyFile($S3Filename, $LocalPath, $overwrite) 
{
    $uploadFile = $LocalFilePath; // File to upload, we'll use the S3 class since it exists
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    if(fileExistsS3($S3Filename))
    {
        if($s3->getObject($bucketName, $S3Filename, $LocalPath))
        {
            return true;
        }
    }
    return false;
}

function copyDir($S3DirPath, $LocalPath, $overwrite) 
{
    echo "\nInCopyDir";
    $uploadFile = $LocalFilePath; // File to upload, we'll use the S3 class since it exists
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    $BucketList = $s3->getBucket($bucketName);
    //echo "BucketSize=".sizeof($BucketList)."\n";
    foreach ($BucketList as $key => $values)
    {
        //echo "key=".$key." strlen key=".strlen($key)." strlen S3DirPath=".strlen($S3DirPath)." S3DirPath=".$S3DirPath."\n";
        //echo "substr=".substr($key,0,strlen($S3DirPath))."\n";
        if(strlen($key)<strlen($S3DirPath))
        {
            continue;
        }
        else if((strcmp($key,$S3DirPath)==0) || (strcmp(substr($key,0,strlen($S3DirPath)),$S3DirPath)==0))
        {
            $keyArr = explode("/",$key);
           // echo " sizeof keyArr=".sizeof($keyArr)."\n";
            if(sizeof($keyArr)<1)
            {
                continue;
            }
            else 
            {
                //echo " keyArr=".$keyArr[sizeof($keyArr)-1]."\n";
                $LocalFile = $LocalPath.'/'.$keyArr[sizeof($keyArr)-1];
            }
            if(!copyFile($key,$LocalFile,true))
            {
                return false;
            }
        }
    } 
    
    return true;
}

function delFile($S3Filename)
{
    $uploadFile = $LocalFilePath; // File to upload, we'll use the S3 class since it exists
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    if($s3->deleteObject($bucketName, $S3Filename))
    {
        return true;
    }
    return false;
}

function listFile($S3Path)
{
    $bucketName = 'itrackreport'; // this is your bucket
    $filelist = [];
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    $BucketList = $s3->getBucket($bucketName);
    
    foreach ($BucketList as $key => $values)
    {
        //echo "key=".$key." value=".$values['time']."<br>";
        if(strlen($key)<strlen($S3Path))
        {
            continue;
        }
        else if((strcmp($S3Path,$key)==0)||
                (strcmp(($S3Path.'/'),substr($key,0,strlen($S3Path)+1))==0))
        {
            $keyArr = explode("/",$key);
           // echo " sizeof keyArr=".sizeof($keyArr)."\n";
            if(sizeof($keyArr)<1)
            {
                continue;
            }
            else 
            {
                //echo " keyArr=".$keyArr[sizeof($keyArr)-1]."\n";
                //$filelist[] = $keyArr[sizeof($keyArr)-1];
                $FileDate = date("Y-m-d H:i:s",$values['time']);
                $filelist[] = array('name'=>$keyArr[sizeof($keyArr)-1],'dateTime'=>$FileDate);
            }
        }
    }
    return $filelist;
}

function listDir($S3Path)
{
    $bucketName = 'itrackreport'; // this is your bucket
    $filelist = [];
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    $BucketList = $s3->getBucket($bucketName);
    
    foreach ($BucketList as $key => $values)
    {
        if(strlen($key)<strlen($S3Path))
        {
            continue;
        }
        else if((strcmp(($S3Path.'/'),substr($key,0,strlen($S3Path)+1))==0))
        {
            $keyArr = explode("/",substr($key,strlen($S3Path)+1,strlen($key)));
            //echo " sizeof keyArr=".sizeof($keyArr)."\n";
            if(sizeof($keyArr)<1)
            {
                continue;
            }
            else 
            {
                //echo " keyArr=".$keyArr[0]."size filelist".sizeof($filelist)."\n";
                //print_r($filelist);
                $fileMatch=false;
                for($i=0;$i<sizeof($filelist);$i++)
                {
                    if(strcmp($filelist[$i],$keyArr[0])==0)
                    {
                        $fileMatch=true;
                        break;
                    }
                }
                if($fileMatch==false)
                {
                    $filelist[] = $keyArr[0];
                }
            }
        }
    }
    //echo "Hi";
    //print_r($filelist);
    return $filelist;
}

function delDir($S3Dirname) 
{
    $bucketName = 'itrackreport'; // this is your bucket
    $filelist = [];
    $s3 = new S3(awsAccessKey, awsSecretKey);
    
    if(!$s3->getBucketLocation($bucketName))
    {
        return false;
    }
    
    $BucketList = $s3->getBucket($bucketName);
    
    foreach ($BucketList as $key => $values)
    {
        //echo "key=".$key."S3Dirname".$S3Dirname."len-S3Dirname=".strlen($S3Dirname)."<br>";
        if(strlen($key)<strlen($S3Dirname))
        {
            continue;
        }
        else if((strcmp($S3Dirname,$key)==0)||
                (strcmp(($S3Dirname),substr($key,0,strlen($S3Dirname)))==0))
        {
            $S3Filename=$key;
            //echo "key2=".$key."S3Dirname".$S3Dirname."<br>";
            if(!($s3->deleteObject($bucketName, $S3Filename)))
            {
                return false;
            }
        }
    }
    return true;
}
function listBucket()
{
    $bucketName = 'itrackreport'; // this is your bucket
    $s3 = new S3(awsAccessKey, awsSecretKey);
    echo "bucketloc".$s3->getBucketLocation($bucketName)."\n";
    // List your buckets:
    echo "S3::listBuckets(): ".print_r($s3->listBuckets(), 1)."\n";
    
    $contents = $s3->getBucket($bucketName);
    echo "<br><br>";
    print_r($contents);
    /*echo "<br><br>";*/
    //echo "S3::getBucket(): Files in bucket {$bucketName}: ".print_r($contents, 1);
    /*foreach( $contents as $index => $code ) {
	echo "keyValue=".$code."Value".$contents[$index]."<br>";
}*/
   /* foreach ($contents as $key => $values) 
    {
        //foreach ($values as $anotherkey => $val) 
        {
            echo 'keyValue:'.$key.'<br>';//' AnotherKey: '.$anotherkey.' value:'.$val.'<br>';
        }
    }*/
}

if($account_id=="322" || $account_id=="1100" || $account_id=="1115" || $account_id=="1568")
{
    include_once('station_sort_mumbai.php');
}
else if($account_id=="231" || $account_id=="232")
{	
    //include_once('station_sort.php');
    include_once('station_sort_delhi.php');
}
else if($account_id=="568")
{	
    //include_once('station_sort.php');
    //echo "<br>Tanker";
    include_once('station_sort_tanker.php');
}
else if($account_id=="718")
{
    include_once("station_sort_pdu.php");
}
//$S3Dirname="gps_report/568/master";
//$delStatus=delDir($S3Filename);
//echo "delStatus=".$delStatus."<br>";

/*$S3Path="gps_report/231/master";
$overwrite=true;
$localPath="test";
//copyDir($S3Path,)
unlink($localPath);


$contents=copyDir($S3Path,$localPath,$overwrite);*/
//echo "contents=".$contents."<br>";
//echo "List Bucket1:\n";
//listBucket();
/*$FileName = 'C:\Users\Shams\Desktop'.'/HR38L2027.xlsx';
$LocalFileName = 'ABCD.xlsx';
$targetFile =  '0068/mother_delhi/M1.xlsx';  
$targetFile2 =  '0068/mother_delhi/M2.xlsx';
echo "S3::uploading File:".$FileName."\n";

if(uploadFile($targetFile,$FileName,true))
{
    echo "S3::uploadFile():".$FileName.",True\n";
}
else
{
    echo "S3::uploadFile():".$FileName.",False\n";
}

if(uploadFile($targetFile2,$FileName,true))
{
    echo "S3::uploadFile():".$FileName.",True\n";
}
else
{
    echo "S3::uploadFile():".$FileName.",False\n";
}

echo "List Bucket2:\n";
listBucket();

/*if(fileExistsS3($targetFile))
{
    echo "S3::FileExists():".$targetFile."\n";
}
else
{
    echo "S3::File not Exists():".$targetFile."\n";
}*/

/*echo "copy Directory:\n";

if(copyDir('0068/mother_delhi','0068/mother_delhi',true))
{
    echo "S3::Dir copied():".'0068/mother_delhi'."\n";
}
else
{
    echo "S3::Dir not copied():".'0068/mother_delhi'."\n";
}
*/
//echo "List File in Directory:\n";
//$filelist = listFile('gps_report/568/master');
//print_r($filelist);

/*if(copyFile($targetFile,$LocalFileName,true))
{
    echo "S3::File copied():".$targetFile."\n";
}
else
{
    echo "S3::File not copied():".$targetFile."\n";
}*/

/*if(delFile($targetFile))
{
    echo "S3::File Deleted():".$targetFile."\n";
}
else
{
    echo "S3::File not Deleted():".$targetFile."\n";
}*/

//echo "List Bucket3:\n";
//listBucket();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
