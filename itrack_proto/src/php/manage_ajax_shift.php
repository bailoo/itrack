<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');   
      
  $shift_id_1=$_POST['shift_id']; 
  $busroute_id_1=$_POST['busroute_id'];   
  $busstop_id_1=$_POST['busstop_id'];     
  $studentcard_id_1=$_POST['studentcard_id'];  
  $bus_id_1=$_POST['bus_id'];
  $student_id_1=$_POST['student_id'];
  $student_id_parent_1=$_POST['student_id_parent'];
  $person_id_1=$_POST['person_id'];
  
  $studentclass_id1=$_POST['studentclass_id'];   //for school
  $drivername_id1=$_POST['drivername_id'];
  $student_classwise_id1=$_POST['student_classwise_id'];
  $student_classwise_id_edit1=$_POST['student_classwise_id_edit'];
  $student_id_byclass1=$_POST['student_id_byclass'];
  $student_id_byclass_edit1=$_POST['student_id_byclass_edit'];
  $selected_account_id_school=$_POST['student_id_byclass_edit_selected_account_id'];
  
  if($shift_id_1!="")
  {
    $query="SELECT * FROM shift WHERE shift_id='$shift_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $shift_name1=$row->shift_name;
    $shift_starttime_1=$row->shift_starttime;
    $shift_stoptime_1=$row->shift_stoptime;
    
    echo "manage_shift##".$shift_name1."##".$shift_starttime_1."##".$shift_stoptime_1;
  }
  
  if($busroute_id_1!="")
  {
    $query="SELECT * FROM busroute WHERE busroute_id='$busroute_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $busroute_name1=$row->busroute_name;
       
    echo "manage_busroute##".$busroute_name1;
  }
  if($busstop_id_1!="")
  {
    $query="SELECT * FROM busstop WHERE busstop_id='$busstop_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $busstop_name1=$row->busstop_name;
    $longitude1=$row->longitude;
    $latitude1=$row->latitude;
       
    echo "manage_busstop##".$busstop_name1."##".$latitude1."##".$longitude1;
  }
  
  if($studentcard_id_1!="")
  {
    $query="SELECT * FROM studentcard WHERE studentcard_id='$studentcard_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $studentcard_number1=$row->studentcard_number;
           
    echo "manage_studentcard##".$studentcard_number1;
  }
  //==============Edited by Taseen for school bus from   oct 11 2012==============//
  //==============copy right iespl 
  
  if($studentclass_id1!="")
  {
    $query="SELECT * FROM studentclass WHERE studentclass_id='$studentclass_id1'";
    //echo $query;
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $studentclass_id1=$row->studentclass_id;
    $studentclass_name1=$row->studentclass_name;  
    $studentclass_section1=$row->studentclass_section;   
    $class_lat1=$row->class_lat;	
    $class_lng1=$row->class_lng;
    echo "manage_studentclass##".$studentclass_id1."##".$studentclass_name1."##".$studentclass_section1."##".$class_lat1."##".$class_lng1;
  }
  
  if($drivername_id1!="")
  {
    $query="SELECT * FROM bus_driver WHERE driverid ='$drivername_id1'";
    //echo $query;
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $driverid1=$row->driverid;
    $drivername1=$row->drivername;  
    $dlnumber1=$row->dlnumber;   
    $dob1=$row->dob;	
    $address1=$row->address;
    echo "manage_busdriver##".$driverid1."##".$drivername1."##".$dlnumber1."##".$dob1."##".$address1;
  }
  
  if($student_classwise_id1!="")
  {
  
    $query="SELECT * FROM student WHERE class='$student_classwise_id1'";
   
    $result=mysql_query($query,$DbConnection);
    
    $v_s=0;
    $student_str="";
	  while($row=mysql_fetch_object($result))
	  {
      $v_s++;
      $student_str=$student_str.$row->student_id."->".$row->student_name.",";
      
    }     
     echo "manage_studentclasswise##".$v_s."##".$student_str;  
   // echo "manage_studentclasswise##".$v_s."##".$student_id."##".$student_name;
  }
  
  if($student_classwise_id_edit1!="")
  {
  
    $query="SELECT * FROM student WHERE class='$student_classwise_id_edit1'";
   
    $result=mysql_query($query,$DbConnection);
    
    $v_s=0;
    $student_str="";
	  while($row=mysql_fetch_object($result))
	  {
      $v_s++;
      $student_str=$student_str.$row->student_id."->".$row->student_name.",";
      
    }     
     echo "manage_studentclasswise_edit##".$v_s."##".$student_str;  
   // echo "manage_studentclasswise##".$v_s."##".$student_id."##".$student_name;
  }
 
if($student_id_byclass_edit1!="")
 {
      $query="SELECT escalation_school_grouping.escalation_school_id ,escalation_school.person_name ,escalation_school.person_mobile ,
      escalation_school.person_email FROM escalation_school_grouping,escalation_school
       WHERE escalation_school_grouping.account_id= '$selected_account_id_school' and
       escalation_school.student_id='$student_id_byclass_edit1' and 
       escalation_school.escalation_school_id= escalation_school_grouping.escalation_school_id and escalation_school.status='1' ";
       //echo "w". $query;
    //$query="SELECT * FROM student WHERE student_id='$student_id_byclass_edit1'";
    
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
     	 	
    $escalation_school_id1=$row->escalation_school_id;
    $person_name1=$row->person_name;
    $person_mobile1=$row->person_mobile;
    $person_email1=$row->person_email;
   
    
           
    echo "manage_student_editescalation##".$escalation_school_id1."##".$person_name1."##".$person_mobile1."##".$person_email1;
  } 
  
if($student_id_byclass1!="")
 {
  
    $query="SELECT * FROM student WHERE student_id='$student_id_byclass1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $student_name1=$row->student_name;
    $address1=$row->address;
    $father_name1=$row->father_name;
    $mother_name1=$row->mother_name;
    $roll_no1=$row->roll_no;
    $class1=$row->class;
    $section1=$row->section;
    $student_mobile_no1=$row->student_mobile_no;
    $parent_mobile_no1=$row->parent_mobile_no;
    $school_id1=$row->school_id;
    $studentcard_id1=$row->studentcard_id;
    
    
    if($studentcard_id1!='0'){
          $query1="SELECT * FROM studentcard WHERE studentcard_id='$studentcard_id1'";
          $result1=mysql_query($query1,$DbConnection);
          $row1=mysql_fetch_object($result1);
          
          $studentcard_id=$row1->studentcard_id;
          $studentcard_number=$row1->studentcard_number;
          
          $studentcard_id1= $studentcard_id.":".$studentcard_number;
    }
    
   if($class1!='0')
    {
      
    				$query2="select * from studentclass where studentclass_id ='$class1' and status='1'";
    				$result2=mysql_query($query2,$DbConnection);
            $row2=mysql_fetch_object($result2);
  				  $studentclass_id=$row2->studentclass_id;
            $studentclass_name=$row2->studentclass_name;
            $studentclass_section=$row2->studentclass_section;
              if($studentclass_section!="")
              {
                $class1=$studentclass_id.":".$studentclass_name.'[Section-'.$studentclass_section.']';
               
              }
              else
              {
                $class1=$studentclass_id.":".$studentclass_name.'[Section-'.$studentclass_section.'[Section N/A]]';
                
              } 
    				
    }
  			
    
           
    echo "manage_student_addescalation##".$student_name1."##".$address1."##".$father_name1."##".$mother_name1."##".$roll_no1."##".$class1."##".$section1.
    "##".$student_mobile_no1."##".$parent_mobile_no1."##".$school_id1."##".$studentcard_id1."##".$student_id_byclass1;
  }
  //=======end of taseen updation
 if($student_id_1!="")
 {
    $query="SELECT * FROM student WHERE student_id='$student_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $student_name1=$row->student_name;
    $address1=$row->address;
    $father_name1=$row->father_name;
    $mother_name1=$row->mother_name;
    $roll_no1=$row->roll_no;
    $class1=$row->class;
    $section1=$row->section;
     $class1=$class1.":".$section1;
    $student_mobile_no1=$row->student_mobile_no;
    $parent_mobile_no1=$row->parent_mobile_no;
    $school_id1=$row->school_id;
    $studentcard_id1=$row->studentcard_id;
    $bus_id_pick1=$row->bus_id_pick;
    $bus_id_drop1=$row->bus_id_drop;
    $route_id_pick1=$row->route_id_pick;
    $route_id_drop1=$row->route_id_drop;
    $shift_id_pick1=$row->shift_id_pick;
    $shift_id_drop1=$row->shift_id_drop;
    $busstop_id_pick1=$row->busstop_id_pick;
    $busstop_id_drop1=$row->busstop_id_drop;
    
    if($studentcard_id1!='0'){
          $query1="SELECT * FROM studentcard WHERE studentcard_id='$studentcard_id1'";
          $result1=mysql_query($query1,$DbConnection);
          $row1=mysql_fetch_object($result1);
          
          $studentcard_id=$row1->studentcard_id;
          $studentcard_number=$row1->studentcard_number;
          
          $studentcard_id1= $studentcard_id.":".$studentcard_number;
    }
    
           
    echo "manage_student##".$student_name1."##".$address1."##".$father_name1."##".$mother_name1."##".$roll_no1."##".$class1."##".$section1.
    "##".$student_mobile_no1."##".$parent_mobile_no1."##".$school_id1."##".$studentcard_id1."##".$bus_id_pick1."##".$bus_id_drop1.
    "##".$route_id_pick1."##".$route_id_drop1."##".$shift_id_pick1."##".$shift_id_drop1."##".$busstop_id_pick1."##".$busstop_id_drop1;
  }
  
  if($student_id_parent_1!="")
  {
    $query="SELECT * FROM student WHERE student_id='$student_id_parent_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $student_name1=$row->student_name;
    $address1=$row->address;
    $father_name1=$row->father_name;
    $mother_name1=$row->mother_name;
    $roll_no1=$row->roll_no;
    $class1=$row->class;
    $section1=$row->section;
    $student_mobile_no1=$row->student_mobile_no;
    $parent_mobile_no1=$row->parent_mobile_no;
    $school_id1=$row->school_id;
    $studentcard_id1=$row->studentcard_id;
    $bus_id_pick1=$row->bus_id_pick;
    $bus_id_drop1=$row->bus_id_drop;
    $route_id_pick1=$row->route_id_pick;
    $route_id_drop1=$row->route_id_drop;
    $shift_id_pick1=$row->shift_id_pick;
    $shift_id_drop1=$row->shift_id_drop;
    $busstop_id_pick1=$row->busstop_id_pick;
    $busstop_id_drop1=$row->busstop_id_drop;
    
    if($studentcard_id1!='0'){
         $query1="SELECT * FROM studentcard WHERE studentcard_id='$studentcard_id1'";
          $result1=mysql_query($query1,$DbConnection);
          $row1=mysql_fetch_object($result1);
          
          $studentcard_id=$row1->studentcard_id;
          $studentcard_number=$row1->studentcard_number;
          
          $studentcard_id1= $studentcard_id.":".$studentcard_number;
    }
    
           
    echo "manage_student_parent##".$student_name1."##".$address1."##".$father_name1."##".$mother_name1."##".$roll_no1."##".$class1."##".$section1.
    "##".$student_mobile_no1."##".$parent_mobile_no1."##".$school_id1."##".$studentcard_id1."##".$bus_id_pick1."##".$bus_id_drop1.
    "##".$route_id_pick1."##".$route_id_drop1."##".$shift_id_pick1."##".$shift_id_drop1."##".$busstop_id_pick1."##".$busstop_id_drop1;
  }
  
  if($bus_id_1!="")
  {
    $i=0;
    $msg="";
    $query="SELECT shift.shift_id,shift.shift_name FROM shift,bus_assignment  WHERE shift.shift_id=bus_assignment.shift_id AND bus_assignment.bus_serial='$bus_id_1' AND bus_assignment.status='1' AND shift.status='1'";
     $result=mysql_query($query,$DbConnection);
  		$row_result=mysql_num_rows($result);		
  		if($row_result!=null)
  		{
  			while($row=mysql_fetch_object($result))
  			{									
  				$shift_id=$row->shift_id;
  				$shift_name=$row->shift_name;							
  				
  				if($i==0){
  				     $msg=$msg.$shift_id.":".$shift_name;
          }
          else{
              $msg=$msg.",".$shift_id.":".$shift_name;
          }
          $i++;
          
  		   }    
      }
               
    echo "manage_bus_deassign##".$msg;
  }
 
 if($person_id_1!="")
  {
    $query="SELECT * FROM person WHERE person_id='$person_id_1'";
    $result=mysql_query($query,$DbConnection);
    $row=mysql_fetch_object($result);
    $person_name1=$row->person_name;
    $person_address_1=$row->address;
    $person_mobile_no_1=$row->mobile_no;
    $person_imei_no_1=$row->imei_no;
    
    echo "manage_person##".$person_name1."##".$person_address_1."##".$person_mobile_no_1."##".$person_imei_no_1;
  } 
  
?>

        