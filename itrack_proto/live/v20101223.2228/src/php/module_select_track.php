<?php
  $query1="SELECT time_zone from account_preference WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $time_zone1=$row1->time_zone;
?>
<tr>
  <td>
 <form name="testform">
 <input type="hidden" id="date_switch">
    <table border='0' cellpadding='0' cellspacing='0' class="module_left_menu" width="100%">
      <tr>        
        <td width="47%"><input type='radio' name='mode'> Last Position</td>       
        <td valign="top"><input type='radio' name='mode'>Track</td>
        <!--<td><input type='radio' name='mode'>Track</td>-->
      </tr>
      <tr>        
        <td colspan="2">
          <table class="module_left_menu" width="100%" border="0">
            <tr>
              <td class="module_select_track1">Start Date</td>
              <td class="module_select_track2" align="center">:</td>
              <td>              
                  <input type='text' name='start_date' size='17'>
                  <script language="JavaScript">
                  		new tcal ({
                  		// form name
                  		'formname': 'testform',
                  		// input name
                  		'controlname': 'start_date'
                  	});        	
                  	</script>
                    
              </td>
            </tr>
          </table>
         </td> 
      </tr>
      <tr>        
        <td colspan="2">
          <table class="module_left_menu" width="100%">
            <tr>
              <td class="module_select_track1">End Date</td>
              <td class="module_select_track2" align="center">:</td>
              <td>
                <input type='text' name='end_date' size='17'>
                 <script language="JavaScript">
                  		new tcal1 ({
                  		// form name
                  		'formname': 'testform',
                  		// input name
                  		'controlname': 'end_date'
                  	});        	
                  	</script>
          		   
              </td>
            </tr>
          </table>
         </td> 
      </tr>
      <tr>        
        <td colspan="2">
          <table border="0" class="module_left_menu" width="100%">
              <tr>
                <td class="module_select_track1">Time Zone</td>
                <td class="module_select_track2" align="center">:</td>
                <td>
                  <select name='time_zone' style="width:78%">
                    <? echo '<option value="'.$time_zone1.'">'.$time_zone1.'</option>'; ?>
                    <option value='time_zone1'>time_zone1</option>
                    <option value='time_zone2'>time_zone2</option>
                    <option value='time_zone3'>time_zone3</option>
                    <option value='time_zone4'>time_zone4</option>
                    <option value='time_zone5'>time_zone5</option>
                  </select>
                </td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>        
        <td colspan="2">
          <table class="module_left_menu" width="100%">
              <tr>
                <td class="module_select_track1">Interval</td>
                <td class="module_select_track2" align="center">:</td>
                <td>
                   <select name='interval' style="width:78%">
                    <option value='auto'>Auto</option>             
                  </select>
                </td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>        
        <td colspan="2">
          <table border="0" class="module_left_menu" width="100%"> 
            <tr>              
              <td align="right"><a href='javascript:initialize();' style="text-decoration:none" class="map_text_bt">Map</a></td>
              <td><a href='javascript:show_report();' style="text-decoration:none" class="map_text_bt">Report</a></td> 
            </tr>
          </table>
        </td>
      </tr>
    </table>
	</form>
  </td>
</tr>