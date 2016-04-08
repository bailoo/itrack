<?php    
  include_once('src/php/util_session_variable.php');
  include_once("src/php/util_php_mysql_connectivity.php");    
?>
<html>
  <head>
    <?php
      include('src/php/main_frame_part1.php')
    ?> 

    <script type="text/javascript">     
    if (document.addEventListener) 
    {
      document.addEventListener("DOMContentLoaded", init, false);
    }
    </script>
  </head>

<body>
  <?php
    if($account_id)
    {
      echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=home.php\">";
    }
    else
    {
  ?>
    <form name="myform" method = "post" action ="login.php" onSubmit="javascript:return index_validate_form(myform)">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">								  
        <tr> 
    		  <td colspan="2" valign="top"> 
      			<table border="0" cellpadding="0" cellspacing="0" align="center">    
      				<tr>
      					 <td>
                   <img src="images/iespl_logo.png" height="76">
                   <img src="images/title.jpg" height="53">
                 </td>   			  
      			  </tr>
      		  </table>
    	    </td>
       </tr> 
              
       <tr>
    		<td>
    		  <div style="height:110px"></div>    				
						<table border="0" cellspacing="0" class="module_index" align="center">
              <input name="width" class="tb1" value="" type="hidden">
              <input name="height" class="tb1" value="" type="hidden">
              <input name="resolution" class="tb1" value="" type="hidden">
							<tr>
								<td colspan="4"><img src="images/userlogin.png" height="15">&nbsp;Sign In</td>
							</tr>
						  <tr>
                <td class="module_index1"></td>								
								<td class="module_index2">Superuser</td>
								<td class="module_index3">:</td>
								<td><input name="superuser" type="text"><br></td>
							</tr>
						  <tr>
						    <td class="module_index1"></td>
								<td class="module_index2">User</td>
								<td class="module_index3">:</td>
								<td><input name="user" type="text"><br></td>
							</tr>
						  <tr>
						    <td class="module_index1"></td>
								<td class="module_index2">group</td>
								<td class="module_index3">:</td>
								<td><input name="group" type="text"><br></td>
							</tr>
							<tr>
							  <td class="module_index1"></td>
							  <td class="module_index2">Password</td>
								<td class="module_index3">:</td>
								<td><input name="password" value="" type="password"></td>
							</tr>								
							<tr>
								<td colspan="4">
                  <table border="0" cellspacing="0" cellpadding="0" width="100%"> 
                    <tr>
                      <td width="45%"></td>
                      <td><input value="Sign In" type="submit"></td>
                    </tr>
                  </table>   
								</td>
							</tr>
    				</table>		
    	   </td>
      </tr>
      
      <tr>
        <td>
          <table align="center" border="0">      			
      			<tr>
      			  <div style="height:40px;"></div>
      				<td colspan="3" valign="top" align="center" style="font-size:14px;font-color:#606060;">This site is best viewd by Mozilla firefox browser (1024 x 768)<br>                                 
      					<a href="http://www.mozilla.com/en-US/" target="_blank">Download Mozilla Firefox</a>
                <div style="height:5px;"></div>      					     			
      			  </td>      				
      			</tr>
            <tr valign="top">
            	<td width="742" align="center" class="module_index_copyright">
      							©copyright Innovative Embedded Systems Pvt. Ltd. All Rights Reserved     					
      				</td>      			
      			</tr> 
           </table>
          </td>
        </tr>
      </table>
    </form>
  <?php
  }
  ?>

<?php
mysql_close($DbConnection);
?>

</body></html>
