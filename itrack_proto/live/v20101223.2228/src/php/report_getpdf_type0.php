<?php
include_once('util_session_variable.php');
include ('CreateFile.php');

//print_r($text);
//print_r($temp1[9]);
//print_r($temp4);
//$heading=$temp;
$file_name='reports.pdf';
//$num_heading=3;
//$num_data=$temp4;
///$data=array($heading,$temp0,$temp1,$temp2);
//echo $temp[1][0].'<br>';
//Create_Pdf_Table(&$file_name,&$heading,$num_heading,$temp,$num_data);
///Create_Text_PDF(&$text);
//$text="This class is designed to provide a <b>non-module</b>, non-commercial alternative to dynamically creating pdf documents from within PHP.Obviously this will not be quite as quick as the module alternatives, but it is surprisingly fast, this demonstration page is almost a worst case due to the large number of fonts which are displayed. \n\nThere are a number of features which can be within a Pdf document that it is not at the moment possible to use with this class, but I feel that it is useful enough to be released.This document describes the possible useful calls to the class, the readme.php file (which will create this pdf) should be sufficient as an introduction.Note that this document was generated using the demo script 'readme.php' which came with this package.";
//echo $temp[0][0];
$option=array('showLines'=>1,
				'showHeadings'=>1,
				'shaded'=> 0,
//				'shadeCol' =>(0.8,0.8,0.8),
//				'shadeCol2' =>(0.7,0.7,0.7),
				'fontSize' => 10,
//				'textCol' => (0,0,0),
				'titleFontSize' => 12,
				'rowGap' => 2 ,
				'colGap' => 5 ,
//				'lineCol' => (0,0,0),
				'xPos' => 'center',
				'xOrientation' => 'center',
				'width'=>500,
//				'maxWidth' => 400
				'innerLineThickness' =>1,
				'outerLineThickness' =>1,
//				'protectRows' => <number>
				);
Create_Pdf(&$file_name,&$temp,&$title,&$option);
//echo $file_name;
//print_r($temp2);
//print_r($temp3);
//echo $heading;
//echo $heading[1];
//echo $heading[2];

//$heading= $_SESSION['heading'];
//$heading=$_POST['heading'];
//printf("$heading[0]=%s",$heading[0]);
//printf("$heading[1]=%s",$heading[1]);

?>

