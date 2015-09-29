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
  	function show_selected_filename_field(no_of_files)
	 {
		var poststr ="action_type=add"+
                            "&noOfFiles="+no_of_files; 
		//alert("poststr="+poststr);
		makePOSTRequest('manageAndroidApkField.htm', poststr); 
	 }
         
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
            
            document.getElementById("filename_fields").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById("filename_fields").innerHTML=result;   
             //alert("Rizwan:"+result1[0]);
        }
    }
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

function actionUploadAndroidApkFormat(action_type)
{
    //alert("test"+action_type);
    var apkTypeObj=document.addApkFormat.apkType;
    var apkTypeValue=radio_selection(apkTypeObj);  //////////validate and get geofence
    if(apkTypeValue==false)
    {
        return false;
    }    
  
    if(document.getElementById('apkVersionName').value=="")
    {
        alert("Please Enter File Apk Format Name");
        document.getElementById('apkVersionName').focus();
        return false;	
    }
    if(document.getElementById('apkHeading').value=="select")
    {
        alert("Please Select Apk Heading");
        document.getElementById('apkHeading').focus();
        return false;	
    }
    
    if(document.getElementById('noOfFiles').value=="select")
    {
        alert("Please Select No Of File");
        document.getElementById('noOfFiles').focus();
        return false;
    }

    var total_no_of_files=document.getElementById('no_of_files').value;
    //alert("total_no_of_files="+total_no_of_files);
    var upload_files="";
    var download_file_names="";
    for(var i=1;i<=total_no_of_files;i++)
    {
        if(document.getElementById('download_file_name_'+i).value=="")
        {
            alert("Please Enter Download File");
            document.getElementById('download_file_name_'+i).focus();
            return false;
            break;
        }        
    }
    
    for(var i=1;i<=total_no_of_files;i++)
    {  
        var outerLoopValue=document.getElementById('download_file_name_'+i).value;
        //alert("outerLoopValue="+outerLoopValue+"i="+i); 
        for(var j=total_no_of_files;j>0;j--)
        {
            var innerLoopValue=document.getElementById('download_file_name_'+j).value;
            //alert("innerLoopValue="+innerLoopValue+",j="+j);
            
            //alert("outerLoopValue="+outerLoopValue+"innerLoopValue="+innerLoopValue);
            
            if((outerLoopValue==innerLoopValue) && (i!=j))
            {
                alert("Download Name Field Can Not Be Same");
                document.getElementById('download_file_name_'+i).focus();
                return false;
                break;
            }
        }       
        
    }
    
    for(var i=1;i<=total_no_of_files;i++)
    {        
        upload_files=upload_files+document.getElementById('upload_file'+i).value+",";
        download_file_names=download_file_names+document.getElementById('download_file_name_'+i).value+",";
    }   
}
  </script>
  
  </head>
<?php
echo'<form name="addApkFormat" method="post" action="actionUploadApkFile.php" target="_blank" enctype="multipart/form-data">
    <table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">        
        <tr>
            <td colspan=4>
                <table>
                    <tr>
                        <td>
                            <input type="radio" name="apkType"  value="person">
                        </td>
                        <td>Person Apk</td>
                        <td>
                            <input type="radio" name="apkType"  value="vts">
                        </td>
                        <td>Vts Apk</td>
                        <td>
                            <input type="radio" name="apkType"  value="cce">
                        </td>                       
                        <td>Cce Apk</td>
                    </tr>
                </table>
            </td>
        </tr>      
        <tr>
            <td>Version Name</td>
            <td>&nbsp;:&nbsp;</td>
            <td>
                    <input type="text" name="apkVersionName" id="apkVersionName">				
            </td> 
        </tr>
         <tr>
            <td>Heading</td>
            <td>&nbsp;:&nbsp;</td>
            <td> 
                <select id="apkHeading" name="apkHeading">
                    <option value="select">Select</option>
                    <option value="1 Minutes">1 Minutes</option>
                    <option value="2 Minutes">3 Minutes</option>
                    <option value="3 Minutes">10 Minutes</option>
                    <option value="4 Minutes">30 Minutes</option>
                    <option value="System Check">System Check</option>										
                </select>
            </td> 
        </tr>
        <tr>
            <td>No Of File Upload</td>
            <td>&nbsp;:&nbsp;</td>
                    <td>
                        <select id="noOfFiles" name="noOfFiles" onchange=javascript:show_selected_filename_field(this.value);>
                            <option value="select">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>										
                        </select>
                    </td>						
		</tr>		
	</table>
	<div id="filename_fields" style="display:none"></div>
	<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2" width="27%"> 
		<tr>                    									
                    <td align="center" colspan="3">
                        <input type="submit" id="enter_button" Onclick="javascript:return actionUploadAndroidApkFormat(\'add\');" value="Enter">
                        &nbsp;<input type="reset" value="Clear">
                    </td>
		</tr>
	</table>
    </form>';  
  ?>
