<?php
  function insert_into_track_table($id,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection,&$ret_result)
  {
    $count=0;
    for($i=0;($i<sizeof($old_value));$i++)
    {      
      if(strlen(trim($old_value[$i]))!=strlen(trim($new_value[$i])))
      { 
          $old_value_1[$count]=$old_value[$i];         
          $new_value_1[$count]=$new_value[$i];           
          $field_name_1[$count]=$field_name[$i];
          $count++;           
      }         
    }
   if($count>0)
   {
     $query="INSERT INTO track_log(id,table_name,field_name,old_value,new_value,edit_id,edit_date) VALUES ";
     for ($i=0;$i<($count-1);$i++)
     {
      $query.="('$id','$table_name','$field_name_1[$i]','$old_value_1[$i]','$new_value_1[$i]','$account_id','$date') ,";
     }
      $query.="('$id','$table_name','$field_name_1[$i]','$old_value_1[$i]','$new_value_1[$i]','$account_id','$date')";
    
      $result=mysql_query($query,$DbConnection);
      if($result)
      {
        $ret_result="success";
        return $ret_result;
      } 
    } 
  } 
?>
