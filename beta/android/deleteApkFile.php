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
               You are not a authorizes user
            </strong>
        </font>
    </center>";
    //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=index.php\">";
    exit();
}
?>
<head>
    <style>
    table.menu
    {
        font-size: 10pt;
        margin: 0px;
        padding: 0px;
        font-weight: normal;
    }        
    </style>
        
    <script>
   var http_request = false;
function makePOSTRequest(url, parameters) 
{
    //alert("url="+url+" ,parameter="+parameters);
    http_request = false;
    if (window.XMLHttpRequest) 
    { 
       http_request = new XMLHttpRequest();
       if (http_request.overrideMimeType)
       {
       	//set type accordingly to anticipated content type
          //http_request.overrideMimeType('text/xml');
          http_request.overrideMimeType('text/html');
       }
    }
    else if (window.ActiveXObject) 
    { // IE
       try 
       {
          http_request = new ActiveXObject("Msxml2.XMLHTTP");
       }
       catch (e) 
       {
          try 
          {
             http_request = new ActiveXObject("Microsoft.XMLHTTP");
          }
          catch (e)
          {}
       }
    }
    if (!http_request) 
    {
       alert('Cannot create XMLHTTP instance');
       return false;
    }
    
    http_request.onreadystatechange = alertContents;
    http_request.open('POST', url, true);
    http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http_request.setRequestHeader("Content-length", parameters.length);
    http_request.setRequestHeader("Connection", "close");
    http_request.send(parameters);
}
 
function alertContents()
{

    if(typeof String.prototype.trim !== 'function') 
    {
	String.prototype.trim = function() 
        {
            return this.replace(/^\s+|\s+$/g, '');
        }
    }

    //alert("alertC");
    if (http_request.readyState == 4) 
    {
       if (http_request.status == 200) 
       {
            var result = http_request.responseText;
            //alert("result="+result);
            document.getElementById("downloadList").style.display='';
            document.getElementById("downloadList").innerHTML=result;
        }
    }
 }
function showDownloadList(apkType)
{
     //alert("test"+action_type);
    var apkTypeObj=document.deleteApkFile.apkType;
    var apkType=radio_selection(apkTypeObj);  //////////validate and get geofence
    if(apkType==false)
    {
        return false;
    }
   
    var poststr ="apkType="+apkType;
   // alert("poststr="+poststr);
    makePOSTRequest('ajaxDeleteApkContent.htm', poststr); 
}

function deleteAndroidApkFile(sourceFilePath,destinationFileName)
{
    //alert("sourceFilePath="+sourceFilePath+"apkFileName="+destinationFileName);
    document.getElementById("sourceFilePath").value="";
    document.getElementById("sourceFilePath").value=sourceFilePath;
    
    document.getElementById("destinationFileName").value="";
    document.getElementById("destinationFileName").value=destinationFileName;
    document.deleteApkFile.submit();
}

function radio_selection(obj)
{
    //alert("obj="+obj);	
    var flag=0;
    if(obj.length!=undefined)
    {
        for (var i=0;i<obj.length;i++)
        {
            if(obj[i].checked==true)
            {
                var id=obj[i].value;
                flag=1;
            }	  
        }
    }
    else
    {
        if(obj.checked==true)
        {
            id=obj.value;
            flag=1;
        }
    }
    if(flag==0)
    {
        alert("Please Select Atleast One Format Type");
        return false;
    }
    else
    {
       // alert("id="+id);
        return id;
    }
}
  </script>
  
  </head>
<?php
echo'<form name="deleteApkFile" method="post" action="deleteAndroidApk.php" enctype="multipart/form-data">
    <input type="hidden" id="sourceFilePath" name="sourceFilePath">
           <input type="hidden" id="destinationFileName" name="destinationFileName">
    <table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">        
        <tr>
            <td colspan=4>
                <table class="menu">
                    <tr>
                        <td>
                            <input type="radio" name="apkType"  value="person" onclick="javascript:showDownloadList(this.value);">
                        </td>
                        <td>Person Apk</td>
                        <td>
                            <input type="radio" name="apkType"  value="vts" onclick="javascript:showDownloadList(this.value);">
                        </td>
                        <td>Vts Apk</td>
                        <td>
                            <input type="radio" name="apkType"  value="cce" onclick="javascript:showDownloadList(this.value);">
                        </td>                       
                        <td>Cce Apk</td>
                    </tr>
                </table>
            </td>
        </tr> 
	</table>
	<div id="downloadList" style="display:none"></div>	
    </form>';  
  ?>
