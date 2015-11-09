<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('coreDb.php');
  $material_id1=$_POST['material_id'];
  
  if($material_id1!="")
  {
    $data=getParticularRawMilkInvoiceMaterial($material_id1,$DbConnection);				
    foreach($data as $dt)
    {					
            $code=$dt['code']; 
            $name=$dt['name'];
            $sno=$dt['sno']; 
           
    }
    echo "invoiceMaterialParticular##".$sno."##".$name."##".$code;
  }  
  
 
?>

        