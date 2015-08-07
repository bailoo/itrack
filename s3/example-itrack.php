#!/usr/local/bin/php
<?php
/**
* $Id$
*
* S3 class usage
*/

if (!class_exists('S3')) require_once 'S3.php';

// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIAMXHEMT2IF2PUKQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

$uploadFile = dirname(__FILE__).'/foo'; // File to upload, we'll use the S3 class since it exists
$bucketName = 'itrackreport'; // this is your bucket 

// If you want to use PECL Fileinfo for MIME types:
//if (!extension_loaded('fileinfo') && @dl('fileinfo.so')) $_ENV['MAGIC'] = '/usr/share/file/magic';


// Check if our upload file exists
if (!file_exists($uploadFile) || !is_file($uploadFile))
	exit("\nERROR: No such file: $uploadFile\n\n");

// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");

// Pointless without your keys!
if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
	"define('awsAccessKey', 'change-me');\ndefine('awsSecretKey', 'change-me');\n\n");

// Instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);


	// Put our file (also with public read access)
	if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PRIVATE)) {
		echo "S3::putObjectFile(): File copied to {$bucketName}/".baseName($uploadFile).PHP_EOL;


		// Get the contents of our bucket
		$contents = $s3->getBucket($bucketName);
		echo "S3::getBucket(): Files in bucket {$bucketName}: ".print_r($contents, 1);


		// Get object info
		$info = $s3->getObjectInfo($bucketName, baseName($uploadFile));
		echo "S3::getObjectInfo(): Info for {$bucketName}/".baseName($uploadFile).': '.print_r($info, 1);


		// You can also fetch the object into memory
		 var_dump("S3::getObject() to memory", $s3->getObject($bucketName, baseName($uploadFile)));

		// Or save it into a file (write stream)
		// var_dump("S3::getObject() to savefile.txt", $s3->getObject($bucketName, baseName($uploadFile), 'savefile.txt'));

		// Or write it to a resource (write stream)
		// var_dump("S3::getObject() to resource", $s3->getObject($bucketName, baseName($uploadFile), fopen('savefile.txt', 'wb')));



		// Delete our file
		/* if ($s3->deleteObject($bucketName, baseName($uploadFile))) {
			echo "S3::deleteObject(): Deleted file\n";

			// Delete the bucket we created (a bucket has to be empty to be deleted)
			if ($s3->deleteBucket($bucketName)) {
				echo "S3::deleteBucket(): Deleted bucket {$bucketName}\n";
			} else {
				echo "S3::deleteBucket(): Failed to delete bucket (it probably isn't empty)\n";
			}

		} else {
			echo "S3::deleteObject(): Failed to delete file\n";
		}*/
	} else {
		echo "S3::putObjectFile(): Failed to copy file\n";
	}

?>
