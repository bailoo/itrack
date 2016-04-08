<html>  
  <head>
<?php
	echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
		include('googleAndroidMapApi.php');
?>
   
	 <link rel="StyleSheet" href="src/css/menu.css">
	 <link rel="StyleSheet" href="src/css/module_hide_show_div.css?<?php echo time();?>">
	 <script type="text/javascript" src="src/js/jquery-1.3.2.js"></script>
<script type="text/javascript">	 
	function portal_android_information(value)
	{
		//alert('test');
		document.getElementById("android_blackout").style.visibility = "visible";
		document.getElementById("android_divpopup").style.visibility = "visible";
		document.getElementById("android_blackout").style.display = "block";
		document.getElementById("android_divpopup").style.display = "block"; 
		var poststr = "process_type=" + encodeURI(value);
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/portal_android_information.php', poststr);
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
		if (http_request.readyState == 4) 
		{
			if (http_request.status == 200) 
			{
				result = http_request.responseText; 
				//alert(result);                     
				var result1=result.split("##");  
				//alert("Rizwan:"+result1[0]);
				if(result1[0]=="portal_android_information")
				{                          
					document.getElementById('portal_android_information').style.display ="";			 			  
					document.getElementById('portal_android_information').innerHTML = result1[1];
					//alert("result2="+result1[2]);
					//setCombo(result1[2])	
				}
			}
			else 
			{
				alert('There was a problem with the request.');
			}
		}
	}
	function close_portal_android_information()
	{
		document.getElementById("android_blackout").style.visibility = "hidden";
		document.getElementById("android_divpopup").style.visibility = "hidden";
		document.getElementById("android_blackout").style.display = "none";
		document.getElementById("android_divpopup").style.display = "none";	
	}
	
	function resize(obj)
	{ 
		if(obj=='home')
		{ 
			var dv = document.getElementById("leftMenu");    
			divHeight =  $(window).height();
			dv.style.height = divHeight - 103;
		}
		else  
		{ 
			var dv1 = document.getElementById("leftMenu");    
			divHeight =  $(window).height();
			dv1.style.height = divHeight - 103;

			var dv2 = document.getElementById("rightMenu");    
			divHeight =  $(window).height();
			dv2.style.height = divHeight - 25; 
		}         
	}
	
function showOnMap(file_name,process_type)
{		
	document.getElementById('prepage').style.visibility='visible';
	document.getElementById("android_blackout").style.visibility = "hidden";
	document.getElementById("android_divpopup").style.visibility = "hidden";
	document.getElementById("android_blackout").style.display = "none";
	document.getElementById("android_divpopup").style.display = "none";	
	var poststr = "fileName="+encodeURI( file_name )+
				  "&processType="+encodeURI( process_type )+
				  "&campareType=1";
	//alert("poststr1="+poststr);
	$.ajax(
	{
		type: "POST",
		url:'src/php/get_android_filtered_xml.php',
		data: poststr,
		success: function(response)
		{
			//console.log(response);
			//alert("response="+response);
			document.getElementById('dummy_div').style.display='none';
			//document.getElementById('map_home').style.display='';
			$("#dummy_div").html(response);					
			document.getElementById('prepage').style.visibility='hidden';
			if(process_type=="CAMPARE DATA")
			{
				showOnMap1(file_name,process_type);
			}
			// document.getElementById('dummy_div').innerHTML=responsedatas;*/
		},
		error: function()
		{
			alert('An unexpected error has occurred! Please try later.');
		}
	});	
}  // function closed
function showOnMap1(file_name,process_type)
{		
		document.getElementById('prepage').style.visibility='visible';	
		var poststr="";
		poststr = "fileName="+encodeURI( file_name )+
					  "&processType="+encodeURI( process_type )+
					  "&campareType=2";
		//alert("poststr2="+poststr);
		$.ajax(
		{
			type: "POST",
			url:'src/php/get_android_filtered_xml.php',
			data: poststr,
			success: function(response)
			{
				//console.log(response);
				//alert("response1="+response);
				document.getElementById('dummy_div').style.display='none';
				//document.getElementById('map_home').style.display='';
				$("#dummy_div").html(response);					
				document.getElementById('prepage').style.visibility='hidden';				
				// document.getElementById('dummy_div').innerHTML=responsedatas;*/
			},
			error: function()
			{
				alert('An unexpected error has occurred! Please try later.');
			}
		});	
}  // function closed

function download_this_file(file_name,process_type)
{
	//alert('filename='+file_name+"process_type="+process_type);
	document.getElementById("download_file_name").value="";
	document.getElementById("download_file_name").value=file_name;
	document.getElementById("download_process_type").value="";
	document.getElementById("download_process_type").value=process_type;
	document.download_files.submit();
}
	
</script>    
  </head>
  
<body class="body_part" topmargin="0"  onload="javascript:resize('home')"  onresize="javascript:resize('home')">
<?php
	include('main_android_frame_part2.php');
	include('module_android_frame_header.php');	
	include('main_android_frame_part3.php');
	include('module_android_home_body.php');	
	include('main_android_frame_part5.php');	 
?>	

	<div id="android_blackout"> </div>
		<div id="android_divpopup">
			<table border="0" class="module_left_menu" width="100%">
				<tr>
					<td class="manage_interfarce" align="right" colspan="7">
						<a href="#" onclick="javascript:close_portal_android_information()" class="hs3">
							<img src="images/close.png" type="image" style="border-style:none;">
						</a>
					</td> 													
				</tr>
			</table>
			<div id="portal_android_information" style="display:none;"></div>        
	</div>
</body>
            
</html>