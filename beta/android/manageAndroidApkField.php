<?php	
    include_once("utilSetUnsetSession.php");
    include_once("utilDatabaseConnectivity.php");  
	if(!$accountIdSession)
{
print"<br><br><br><br><br><br><br><br><br>
    <center>
        <FONT color=\"red\" 
            font size=\"2\" 
            face=\"verdana\">
        
            <strong>
               You are not a authorizes user
            </strong>
        </font>
    </center>";
    //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
    exit();
}
	
    if($action_type=="add")
    {
    echo"<table border='0' align=center class='manage_interface' cellspacing='4' cellpadding='4'>";
            for($i=1;$i<=$noOfFiles;$i++)
            {
            echo"<tr>
                    <td>			
                            Download File Name ".$i."
                    </td>
                    <td>
                    :
                    </td>
                    <td>
                            <input type='text' id='download_file_name_$i' name='download_file_name_$i'>
                    </td>                    
                    <td>
                        <input type='file' name='upload_file_$i'>
                    </td>
                </tr>";
            }
        echo"</table>";
    }

?>
