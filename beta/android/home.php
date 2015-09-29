<?php
include_once("utilSetUnsetSession.php");

if(!$accountIdSession)
{
print"<br><br><br><br><br><br><br><br><br>
    <center>
        <FONT color=\"red\" 
            font size=\"2\" 
            face=\"verdana\">
        
            <strong>
               Session Time Out 
            </strong>
        </font>
    </center>";
    echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
    exit();
}
//echo "account=".$account;

echo'
<html>
  <head>
  <style type="text/css">
    table, td, th
    {
    border:1px solid green;
    }
    th
    {
    background-color:green;
    color:white;
    }
  </style>
  
  <script language="javascript" type="text/javascript">
  	function changeColor(color, ID) {
  		document.getElementById(ID).bgColor = "#" + color;
  	}
  </script>
  
  </head>

  <body>
  <div align="right"><a href="logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a> 
  <br>   
    <center><font color=green face="WildWest" size="3"><strong>IESPL VTS UTILITY&nbsp;&nbsp;&nbsp;&nbsp;</strong></font></center> 
    <br>    
    <table align="center" style="font-size:14px;">      
      <tr>';  
      
   
    echo'<td id="cell1" 
            align="center" 
            style="cursor:pointer" 
            onmouseover="changeColor(\'F7BE81\', this.id);" 
            onmouseout="changeColor(\'FFFFFF\', this.id);" 
            onclick="javascript:window.open(\'addApkUploadFormat.php\',\'_blank\');">

            <strong>
                <font color="#610B0B">
                    Upload Apk
                </font>
            </strong>
        </td>';  
    echo'<td id="cell2" 
            align="center" 
            style="cursor:pointer" 
            onmouseover="changeColor(\'F7BE81\', this.id);" 
            onmouseout="changeColor(\'FFFFFF\', this.id);" 
            onclick="javascript:window.open(\'downloadApkFile.php\',\'_blank\');">

            <strong>
                <font color="#610B0B">
                    Download Apk
                </font>
            </strong>
        </td>'; 
    echo'<td id="cell3" 
            align="center" 
            style="cursor:pointer" 
            onmouseover="changeColor(\'F7BE81\', this.id);" 
            onmouseout="changeColor(\'FFFFFF\', this.id);" 
            onclick="javascript:window.open(\'deleteApkFile.php\',\'_blank\');">

            <strong>
                <font color="#610B0B">
                    Delete Apk
                </font>
            </strong>
        </td>'; 
    echo'</tr>     
    </table>  
  </body>
</html>';
?>
