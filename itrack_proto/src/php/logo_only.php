 <?php
 $query_logo1="select company_id from account_detail WHERE account_id='$account_id'";
  $result_logo1=mysql_query($query_logo1,$DbConnection);
  $row_logo1=mysql_fetch_object($result_logo1);
  $company_id1=$row_logo1->company_id;
  
  $query_logo2="SELECT DISTINCT company_info.company_name,company_logo.logo_file FROM company_info,company_logo WHERE company_info.logo_id=company_logo.logo_id AND company_info.company_id='$company_id1'";
  //echo "query=".$query_logo2;
  $result_logo2=mysql_query($query_logo2,$DbConnection);
  $row_logo2=mysql_fetch_object($result_logo2);
  $logo=$row_logo2->logo_file;                          
  //$company_name1=$row_logo2->company_name;
 ?>