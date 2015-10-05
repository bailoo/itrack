<?php
    echo"reportPrevPage##";
    echo'<center><fieldset class="report_fieldset">';
    echo'<legend>Inactive Data Duration</legend><br>';     
														
    date_default_timezone_set('Asia/Calcutta');
    if($start_date=="" && $end_date=="")
    {
        $StartDate=date("Y/m/d 00:00:00");	
        $EndDate=date("Y/m/d H:i:s");
    }
    else
    {
        $StartDate=$start_date;	
        $EndDate=$end_date;
    }	
			
echo'<table border=0 cellspacing=0 cellpadding=3 align="center">	
        <tr>
            <td  class="text"><b>Select Duration : </b></td>
            <td>
                <table>
                    <tr>
                        <td  class="text">Duration</td>
                        <td class="text">
                        :
                        </td>
                        <td>
                            <select id="duration">
                                <option value="30">30 Min</opiton>
                                <option value="60">1 hr</opiton>
                                <option value="120">2 hr</opiton>
                                <option value="240">4 hr</opiton>
                            </select>																	
                        </td>
                    </tr>
                </table>
            <td>
        </tr>										
    </table>
			
    <br>  
    <table border=0 cellspacing=0 cellpadding=3 align="center">	
        <tr>
            <td align="center" colspan=2>
                <input type="button" onclick="javascript:action_report_invalid_data(this.form);" value="Enter">
                    &nbsp;
                <input type="reset" value="Clear">
            </td>
        </tr>
    </table>		
</form>  </center>     
<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>  ';
?>						
					 
