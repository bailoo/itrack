<table border='0' width="100%" height="100%" cellspacing="0" cellpadding="0">
    <tr class="mb1">
      <td>
          <?php include('module_logo.php');?> 
      </td>
    </tr>
  <tr  class="mb2">
    <td valign="top">
       <div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu">
          <table border='0' width="100%" cellspacing="0" cellpadding="0">		           
              <?php
                 
                echo'<tr>
                	<td> 
                    <table class="menu" width="100%" border="0" bgcolor="grey" cellspacing="1" cellpadding="1">
                    	<tr>
                    		<td><strong>&nbsp;Report</strong></td>
                    	</tr>
                    </table>
                  </td>
                </tr>
                
                <tr>
                	<td> 
                    <table>
                    	<tr>
                    		<td><strong></strong></td>
                    	</tr>
                    </table>
                  </td>
                </tr>';                
                                   
                 include('module_report.php');
                 include('module_alert.php');  
                 include('module_graph.php'); 
                 include('module_datalog.php');                   
              ?>  		          
          </table>
      </div>	
   </td>
  </tr>
  <tr  class="mb3">
      <td>
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>					
  	    

		  