<table border='0' width="100%" height="100%" cellspacing="0" cellpadding="0" >
   <!-- <tr class="mb1">
      <td>
          <?php include('module_logo.php');?> 
      </td>
    </tr>-->
  <tr  class="mb2">
    <td valign="top"> 
            <table border='0' width="100%" cellspacing="0" cellpadding="0" class="menu" style="font-size:12px">		           
              <?php
                 
                echo'<tr>
                	<td> 
                    <table class="menu alert alert-warning" width="100%" border="0" bgcolor="" cellspacing="1" cellpadding="1" style="font-size:12px">
                    	<tr>
                    		<td><strong>&nbsp;Report</strong></td>
                    	</tr>
                    </table>
                  </td>
                </tr> ';          
                 include('module_report.php');                  
              ?>  		          
          </table>   	
   </td>
  </tr>
  <tr  class="mb3">
      <td>
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>					
  	    
<?php
//echo "ddd".$_SESSION['drop_down_menu_js_type'];
/*if($_SESSION['drop_down_menu_js_type']=='undefined')
{
 ?>
<script>
    window.onload = function () 
    {        
     manage_show_file( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "'"; ?> );
    }
</script>
 <?php
}*/

if($_SESSION['drop_down_menu_module']=="report.htm")
{
  //if($_SESSION['drop_down_menu_js_type']=='report_common_prev')
  if($_SESSION['drop_down_menu_report_type_upload']=='')
  {
    ?>
   <script>
      var dataLogImei="<?php echo $dataLogImei; ?>";
       var start_date_map="<?php echo $start_date_map; ?>";
       var end_date_map="<?php echo $end_date_map; ?>";
   if(dataLogImei=="")
   {
       window.onload = function () 
       { 
           //alert("un");
        <?php echo  $_SESSION['drop_down_menu_js_type']; ?>( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "', '".$_SESSION['drop_down_menu_title_report'] . "'"; ?> );
       }
   }
   else
   {
        var result= "<?php echo $account_id; ?>";        
        var poststr = "account_id_local="+result+
        "&vehicle_display_option=all"+
        "&title1=data log"+
        "&dataLogImei="+dataLogImei+
        "&options_value=all";
        //alert("poststr="+poststr);
        showManageLoadingMessage();
        makePOSTRequest('src/php/datalog_between_dates.htm',poststr);
    }
   </script>
    <?php
     unset($dataLogImei);
	unset($start_date_map);
	unset($end_date_map);
  }
  else
  {
       ?>
   <script>
       window.onload = function () 
       {  
           //alert("fn");
        <?php echo  $_SESSION['drop_down_menu_js_type']; ?>( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "', '".$_SESSION['drop_down_menu_title_report'] . "', '".$_SESSION['drop_down_menu_report_type_upload'] . "'  "; ?> );
       }
   </script>
    <?php
  }
}
?>
		  