<?php
  $query_copyright1="SELECT DISTINCT company_info.copyright_name, company_info.tech_supp_no FROM company_info,account_detail WHERE account_detail.company_id=company_info.company_id AND account_detail.account_id='$account_id'";
  $result_copyright1=mysql_query($query_copyright1,$DbConnection); 
  $row_copyright1=mysql_fetch_object($result_copyright1);
  $copyright_name=$row_copyright1->copyright_name;  
  $tech_supp_no=$row_copyright1->tech_supp_no;
   include('gethostnameurl.php');
?>
<?php echo '<table border="0" class="module_copyright" width="100%" bgcolor="'.$bgcolor.'" >';?>
  <tr>
    <td>
         <center>                  
         <?php 
          if($user_id!='demo')
          {
            echo "<font color='#333333'><strong>Support Ph:".$tech_supp_no."</strong></font><br>";
          }
           
          echo "<font color='#333333'><strong>".$copyright_name."</strong></font>"; ?>  
          
          </center>
    </td>        
  </tr>
</table>

