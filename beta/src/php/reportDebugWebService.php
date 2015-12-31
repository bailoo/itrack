<?php
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
    set_time_limit(300);	
    date_default_timezone_set("Asia/Kolkata");
    

//====cassamdra //////////////
    include_once('xmlParameters.php');
    include_once('parameterizeData.php'); /////// for seeing parameters
    include_once('data.php');   
    include_once("getXmlData.php");
    ////////////////////////
    $dateTime=$_POST['dateTime'];
    $vSerial=$_POST['vserial'];
    
    $sortBy='h';
    
    $requiredData="All";
    $parameterizeData=null;
    $parameterizeData=new parameterizeData();
    

    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    $SortedDataObject=null;
    $SortedDataObject=new data();
    readFileXmlNew($vSerial,$dateTime,$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
    echo $SortedDataObject;   
    
    	 
?>
