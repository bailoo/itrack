<?php
  $query_copyright1="SELECT DISTINCT company_info.copyright_name FROM company_info,account_detail WHERE account_detail.company_id=company_info.company_id AND account_detail.account_id='$account_id'";
  $result_copyright1=mysql_query($query_copyright1,$DbConnection); 
  $row_copyright1=mysql_fetch_object($result_copyright1);
  $copyright_name=$row_copyright1->copyright_name;  
?>
<table border="0" class="module_copyright" width="100%">
  <tr>
    <td>
         <center><?php echo $copyright_name; ?>  </center>
    </td>        
  </tr>
</table>

