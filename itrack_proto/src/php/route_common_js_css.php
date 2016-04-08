<script language="javascript" src="src/js/drag.js"></script>
<script language="javascript" src="src/js/tablefilter.js"></script>
	
		
<link rel="shortcut icon" href="images/iesicon.ico">
<link rel="StyleSheet" href="src/css/menu.css">	
<link rel="StyleSheet" href="src/css/module_hide_show_div.css?<?php echo time();?>">	
<link rel="stylesheet" href="src/css/calendar.css">
<script type="text/javascript" src="src/js/menu.js"></script>
<script language="javascript" src="src/js/calendar_us.js"></script> 
<link rel="stylesheet" href="src/css/search_style.css?<?php echo time();?>">
<script language="javascript" src="src/js/datetimepicker.js"></script>
<script language="javascript" src="src/js/datetimepicker_sd.js"></script>
<script type="text/javascript" src="src/js/jquery.js"></script> 
<script language="javascript" src="src/js/routeAjax.js?<?php echo time();?>"></script>
<script type="text/javascript" src="src/js/jquery-1.3.2.js"></script> 


<style type="text/css">

.divm {
 position:absolute;
 top:50%;
 right:50%;
 width:100px;
}

@media print 
	{
		.noprint
		{
			display: none;
		}
	}
	@media screen
	{ 
		.noscreen
		{ 
			display: none; 
		} 
	}

  .normal1 { background-color: #F8F8FF }
  .highlight1 { background-color: #C6DEFF }

  .normal2 { background-color: #FFFDF9 }
  .highlight2 { background-color: #C6DEFF }
 </style>
 
<script type="text/javascript">
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
	
	function showSectorInfo(vname_g_found,prev_sector_seq_found,prev_sector_name_found,prev_datetime_found,current_sector_seq_found,current_sector_found,current_datetime_found)
	{	               	 	 	 
   //alert("str="+str);
	 //document.getElementById('sector_area').style.display="";
	 //$("#sector_area").slideDown("slow");   
   /*var msg_string = "<table style='font-size: 8pt; margin: 1em; border-collapse: collapse;' align='center'>"+
                "<thead style='padding: .3em; border: 1px #ccc solid; background: #FFFFFF;'>"+
                "<tr align='left'><th style='padding: .3em; border: 1px #ccc solid;'>Name</th><th style='padding: .3em; border: 1px #ccc solid;'></th><th style='padding: .3em; border: 1px #ccc solid;'>Value </th><th align='right'><a href='#' style='text-decoration:none;text-align:right;' onClick=\"document.getElementById('sector_area').style.display= 'none';\">Close</a></th></tr>"+
                "</thead>"+
                "<tbody style='background: #FFFFFF;border-collapse: collapse;border: 1px #ccc solid;'>"+
                "<tr><td>Vehicle</td><td>:</td><td colspan=2><font color=red>"+vname_g_found+"</font></td></tr>"+
                "<tr><td>Previous Sector (Seq:<font color=red>"+prev_sector_seq_found+"</font>)</td><td>:</td><td colspan=2><font color=green>"+prev_sector_name_found+"</font></td></tr>"+
                "<tr><td>Previous DateTime</td><td>:</td><td colspan=2><font color=blue>"+prev_datetime_found+"</font></td></tr>"+
                "<tr><td>Current Sector (Seq:<font color=red>"+current_sector_seq_found+"</font>)</td><td>:</td><td colspan=2><font color=green>"+current_sector_found+"</font></td></tr>"+
                "<tr><td>Current DateTime</td><td>:</td><td colspan=2><font color=blue>"+current_datetime_found+"</font></td></tr>"+
                "</tbody>"+
                "</table>";*/
/*   var msg_string = "<table style='font-size: 8pt; margin: 1em; border-collapse: collapse;' align='center'>"+
                "<thead style='padding: .3em; border: 1px #ccc solid; background: #FFFFFF;'>"+
                "<tr align='left'><th style='padding: .3em; border: 1px #ccc solid;'>Name</th><th style='padding: .3em; border: 1px #ccc solid;'></th><th style='padding: .3em; border: 1px #ccc solid;'>Value </th><th align='right'><a href='#' style='text-decoration:none;text-align:right;' onClick=\"document.getElementById('sector_area').style.display= 'none';\">Close</a></th></tr>"+
                "</thead>"+
                "<tbody style='background: #FFFFFF;border-collapse: collapse;border: 1px #ccc solid;'>"+
                "<tr><td>Vehicle</td><td>:</td><td colspan=2><font color=red>"+vname_g_found+"</font></td></tr>"+
                "<tr><td>Previous Sector (Seq:<font color=red>"+prev_sector_seq_found+"</font>)</td><td>:</td><td colspan=2><font color=green>"+prev_sector_name_found+"</font></td></tr>"+                
                "<tr><td>Current Sector (Seq:<font color=red>"+current_sector_seq_found+"</font>)</td><td>:</td><td colspan=2><font color=green>"+current_sector_found+"</font></td></tr>"+
                "<tr><td>Sector Change Time</td><td>:</td><td colspan=2><font color=blue>"+current_datetime_found+"</font></td></tr>"+
                "</tbody>"+
                "</table>";*/
                
   var msg_string = "<table style='font-size: 8pt; margin: 1em; border-collapse: collapse;' align='center'>"+
                "<thead style='padding: .3em; border: 1px #ccc solid; background: #FFFFFF;'>"+
                "<tr align='left'><th style='padding: .3em; border: 1px #ccc solid;'>Name</th><th style='padding: .3em; border: 1px #ccc solid;'></th><th style='padding: .3em; border: 1px #ccc solid;'>Value </th><th align='right'><a href='#' style='text-decoration:none;text-align:right;' onClick=\"$('#sector_area').slideUp('slow');\">Close</a></th></tr>"+
                "</thead>"+
                "<tbody style='background: #FFFFFF;border-collapse: collapse;border: 1px #ccc solid;'>"+
                "<tr><td>Vehicle</td><td>:</td><td colspan=2><font color=red>"+vname_g_found+"</font></td></tr>"+
                "<tr><td>Previous Sector (Seq:<font color=red>"+prev_sector_seq_found+"</font>)</td><td>:</td><td colspan=2><font color=green>"+prev_sector_name_found+"</font></td></tr>"+                
                "<tr><td>Current Sector (Seq:<font color=red>"+current_sector_seq_found+"</font>)</td><td>:</td><td colspan=2><font color=green>"+current_sector_found+"</font></td></tr>"+
                "<tr><td>Sector Change Time</td><td>:</td><td colspan=2><font color=blue>"+current_datetime_found+"</font></td></tr>"+
                "</tbody>"+
                "</table>";  
                                                      	 
	   document.getElementById('sector_area').innerHTML = msg_string;
	   
     var df = document.getElementById('display_flag').value;
     //alert(df);
     if(df == 0)
     { 
      $("#sector_area").slideDown("slow");
      document.getElementById('display_flag').value =1;
     }
     else
     {
      $("#sector_area").slideUp("slow");
      document.getElementById('display_flag').value =0;
     } 
     	 
  }
  
  function show_report_duration()
  {
    //alert("show report:"+document.getElementById('select_report_duration'));
    document.getElementById('select_report_duration').style.display = "";    
  }	
  function select_report_duration()
  {
    //alert("in select:"+document.getElementById('select_duration').value);
    if(document.getElementById('select_duration').value=="select")
    {
      alert("Please select duration");
      document.getElementById('report_duration').value = "";
      return false;      
    }
    else
    {
      document.getElementById('report_duration').value = document.getElementById('select_duration').value;
      document.getElementById('select_report_duration').style.display = "none"; 
    } 
  }	
</script>
<script type="text/javascript" src="src/js/tree_view/yahoo-dom-event.js"></script>
<script type="text/javascript" src="src/js/tree_view/treeview-min.js"></script>
<link rel="stylesheet" type="text/css" href="src/css/tree_view/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="src/css/tree_view/treeview.css" />

