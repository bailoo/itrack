  /////////////////////  REPORT MODULE /////////////////////////////////////////  
/*action_graph_daily_speed  //function test()
  {
    alert("hello");
  } */
function show_upload_format(param_value)
{
        if(param_value=="upload_format1")
        {
                document.getElementById("format_type").value="";
                document.getElementById("format_type").value="upload_format1";
                document.getElementById("upload_format2").style.display="none";
                document.getElementById("upload_format3").style.display="none";
                document.getElementById("upload_format1").style.display="";			
        }
        if(param_value=="upload_format2")
        {
                document.getElementById("format_type").value="";
                document.getElementById("format_type").value="upload_format2";
                document.getElementById("upload_format1").style.display="none";
                document.getElementById("upload_format3").style.display="none";	
                document.getElementById("upload_format2").style.display="";			
        }
        if(param_value=="upload_format3")
        {
                document.getElementById("format_type").value="";
                document.getElementById("format_type").value="upload_format3";
                document.getElementById("upload_format1").style.display="none";
                document.getElementById("upload_format2").style.display="none";
                document.getElementById("upload_format3").style.display="";			
        }
}

function report_upload_file(filename,title)            // manage.js
{
        var poststr ="";
        makePOSTRequest(filename, poststr); 
}	
  
  function action_report_fuel_halt(obj)
  {
    //alert("fuel halt");
    var area_defined = document.getElementById("area_defined").value;
    var geo_id ="";
    
    if(area_defined == 1)
    {
      var geo_obj = obj.elements['halt_geo_area[]'];
      
      //alert("geo_obj:"+geo_obj.length);
      
      if(geo_obj.length!=undefined)
      {
        //alert("len:"+geo_obj.length)
        var counter = 0;
        for(var i=0;i<geo_obj.length;i++)
        {
          if(geo_obj[i].checked==true)
          {
            //alert("counter="+counter);
            if(counter==0)
            {              
              geo_id = geo_id+""+geo_obj[i].value;
            }
            else
            {
              geo_id = geo_id+":"+geo_obj[i].value;
            }
            counter++;
          }
        }
        //alert("final_str:"+geo_id)
      }
      else
      {
        if(geo_obj.checked==true)
        {        
          geo_id = geo_obj.value;
          //alert("geo_id1:"+geo_id);
        } 
        else
        {
          //alert("geo_id2:"+geo_id);
        }
      }
    }
    
    //alert("area_defined:"+area_defined+" ,geo_id="+geo_id); 
    
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    
    if(device_str!=false)
    {    
        if(area_defined == 1)
        {
          var poststr = "vehicleserial=" + encodeURI( device_str ) +					
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+
                  "&geo_id=" + encodeURI( geo_id )+                    
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );
        }
        else
        {        
          var poststr = "vehicleserial=" + encodeURI( device_str ) +					
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );
        }                                    
                //alert("riz:"+poststr); 
    }    
    //alert("postrstr:"+poststr);               
    makePOSTRequest('src/php/action_report_fuel_halt.htm', poststr);
  }
  ////////schedule//////////////////
  
  function report_display_schedule_location(file_name,title)
  {   
    var obj=document.report1.manage_id;    
    var result=radio_selection(obj);   
    if(result!=false)
    {
    var poststr = "account_id_local="+result+
				  "&start_date="+document.getElementById('date1').value+
				  "&end_date="+document.getElementById('date2').value+
				  "&title1="+title;
		//alert("poststr="+poststr+"filename="+file_name);				  
		makePOSTRequest(file_name,poststr);
    }
  }
  
	function schedule_location_prev(filename,title)            // manage.js
	{
		showManageLoadingMessage();
		var poststr = "filename=" + encodeURI(filename)+
		"&title=" + encodeURI(title);	
		makePOSTRequest('src/php/report_schedule_location_prev.htm', poststr);
	}
	
	
	 function action_report_schedule_assignment(obj)
  {
    // GET SELECTED VEHICLES 
    //alert("one="+document.getElementById("loading_msg").style.display);
    document.getElementById("loading_msg").style.display = '';  
    //alert("two="+document.getElementById("loading_msg").style.display);
     
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                
                  //alert("Rizwan:"+poststr);  
	}
                   
    makePOSTRequest('src/php/action_report_schedule_assignment.htm', poststr);
  }
  
  
  /////////////////////////
  
function report_show_download_file(file_path,file_name)
{
	showManageLoadingMessage();
	//alert("file_path="+file_path+"file_name="+file_name);
	if(file_path!="" && file_name!="")
	{
		var poststr = "file_name="+file_name;
		//alert("poststr="+poststr+"file_path="+file_path);
		makePOSTRequest(file_path, poststr);
	}
}

/*function download_this_file(file_path)
{
	//alert("file_path="+file_path);
	window.location.href = file_path;
}*/

function download_this_file(file_name)
{
	document.getElementById("download_file_id").value="";
	document.getElementById("download_file_id").value=file_name;
	document.download_files.submit();
}

function delete_this_file(file_path,tr_id)
{
	var poststr="file_path="+file_path+
	"&tr_id="+tr_id;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/action_report_delete_file.htm', poststr);
}
  
  function temp()
  {
  	document.getElementById("portal_vehicle_information").style.display="none";
  }  

  function show_report_vehicles(value)
  {	
  	var poststr="common_id=" +value+
  				"&report_type1="+document.getElementById("report_type").value;
  				//"&report_type1="+document.getElementById("report_type").value;
  				//alert("poststr="+poststr);
  	 makePOSTRequest('src/php/trip_hierarchy_header.htm', poststr);	
  }
  function report_show_vehicle_details(value)
  {	
  	var poststr="common_id=" +value;
  				//"&report_type1="+document.getElementById("report_type").value;
  				//"&report_type1="+document.getElementById("report_type").value;
  			//alert("poststr="+poststr);
  	 makePOSTRequest('src/php/report_vehicle_details.htm', poststr);	
  }
  
  
    
  function report_csv(target_file)
  {
    document.forms[0].action = target_file;    
    document.forms[0].submit();
  }
  

  function report_csv_2(target_file)
  {
    document.forms[1].action = target_file;    
    document.forms[1].submit();
  }
  function report_csv_3(target_file)
  {
    document.forms[2].action = target_file;    
    document.forms[2].submit();
  }

  function add_options_nodata(nogps_value)
  {
    //alert("in addopt");
    var len = document.forms[0].no_data_interval.options.length;
    //alert("len="+len)
    for(var j=0;j<len;j++)
    {
      //alert("len="+len)
      document.forms[0].no_data_interval.options.remove(j);
    }  
      
    var option_nodata_text = new Array();
    var option_nodata_value = new Array();
    
    var option_nodata = new Array();
    
if(document.getElementById('acc').value=='1')
{
    option_nodata_text[0] = "1 min";
    option_nodata_value[0] = 1;

    option_nodata_text[1] = "5 min";
    option_nodata_value[1] = 5;
}
    option_nodata_text[2] = "10 min";
    option_nodata_value[2] = 10;
    
    option_nodata_text[3] = "15 min";
    option_nodata_value[3] = 15;
    
    option_nodata_text[4] = "30 min";
    option_nodata_value[4] = 30;   

    option_nodata_text[5] = "1 hr";
    option_nodata_value[5] = 60;
    
    option_nodata_text[6] = "2 hr";
    option_nodata_value[6] = 120;
    
    option_nodata_text[7] = "3 hr";
    option_nodata_value[7] = 180;
    
    option_nodata_text[8] = "4 hr";
    option_nodata_value[8] = 240;
    
    option_nodata_text[9] = "5 hr";
    option_nodata_value[9] = 300;
    
    option_nodata_text[10] = "6 hr";
    option_nodata_value[10] = 360;
    
    option_nodata_text[11] = "7 hr";
    option_nodata_value[11] = 420;
    
    option_nodata_text[12] = "8 hr";  
    option_nodata_value[12] = 480;
    
    option_nodata_text[13] = "9 hr";
    option_nodata_value[13] = 540;
    
    option_nodata_text[14] = "10 hr";
    option_nodata_value[14] = 600;
    
    option_nodata_text[15] = "11 hr";
    option_nodata_value[15] = 660;     
    
    option_nodata_text[16] = "12 hr";
    option_nodata_value[16] = 720;                                 
         
    var flag =0;
//alert(option_nodata_text.length);
    for(var i=0;i<option_nodata_text.length;i++)
    {
      if(option_nodata_value[i] <= nogps_value)
      {
        var Option = document.createElement("OPTION");
        Option.text = option_nodata_text[i];
        Option.value = option_nodata_value[i];     
        document.forms[0].no_data_interval.options.add(Option);
        flag =1;
      } 
    } 
    
    if(flag == 0)
    {
      var Option = document.createElement("OPTION");
      Option.text = "Select";
      Option.value = "select";     
      document.forms[0].no_data_interval.options.add(Option);       
    } 
  }
  
  function get_pdf_datagap() 
  { 
    document.pdf_form.submit();
  }
  
  function print_datagap() 
  { 
    document.print_form.submit();
  }  
        
  function load_map(lat1,lng1,lat2,lng2) 
  {       
      //alert("in load_map");
  	  if (GBrowserIsCompatible()) 
  	  {
        //alert("in compatible"+lat1+","+lng1+" "+lat2+","+lng2);
        //alert("id="+document.getElementById("map_canvas"));
        var map = new GMap2(document.getElementById("map_canvas"));
        //alert("map="+map);
        map.removeMapType(G_SATELLITE_MAP);

        
        map.addMapType(G_SATELLITE_MAP);	
        var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));
        
        var mapControl = new GMapTypeControl();
        map.addControl(mapControl, topRight);
              
        var bounds = new GLatLngBounds();             
        var point1 = new GLatLng(parseFloat(lat1),parseFloat(lng1));
        bounds.extend(point1);
        //alert(point1);
        
        var point2 = new GLatLng(parseFloat(lat2),parseFloat(lng2));
        bounds.extend(point2);          
        //alert(point1+","+point2);
        
        var center = bounds.getCenter();
        var zoom = map.getBoundsZoomLevel(bounds)-3;
        //alert("center:"+center+" ,zoom:"+zoom)  
        map.setCenter(center,zoom);   
        //map.setCenter(new GLatLng(37.339085, -121.8914807), 18);     
        
        //ADD LABEL        
        var label1 = new GIcon(G_DEFAULT_ICON);
        label1.image = "images/start_marker.png";
    		markerOptions = { icon:label1 };
        //var point1 = new GLatLng(parseFloat(37.4419),parseFloat(-122.1419));       
        map.addOverlay(new GMarker(point1, markerOptions));
        
        var label2 = new GIcon(G_DEFAULT_ICON);
        label2.image = "images/stop_marker.png";
    		markerOptions = { icon:label2 };
        //var point1 = new GLatLng(parseFloat(37.4419),parseFloat(-122.1419));       
        map.addOverlay(new GMarker(point2, markerOptions));        
                               
        //map.addOverlay(new GMarker(point1));
        //map.addOverlay(new GMarker(point2)); 
      } 
  }         

	function changeColor1(color, counter) 
  {
		var ID = "cellA"+counter;
    document.getElementById(ID).bgColor = "#" + color;
    
		var ID = "cellB"+counter;
    document.getElementById(ID).bgColor = "#" + color;
    
		var ID = "cellC"+counter;
    document.getElementById(ID).bgColor = "#" + color;        
	}
	
	function changeColor2(color, counter) 
  {
		var ID = "cellD"+counter;
    document.getElementById(ID).bgColor = "#" + color;
    
		var ID = "cellE"+counter;
    document.getElementById(ID).bgColor = "#" + color;
    
		var ID = "cellF"+counter;
    document.getElementById(ID).bgColor = "#" + color; 
	}	
	  
  function nogps_get_divinfo(t1_nogps,t2_nogps,diff_nogps,lat_t1_nogps,lng_t1_nogps,lat_t2_nogps,lng_t2_nogps,speed1_nogps,speed2_nogps)
  {    
    var poststr = "type=NO GPS" +
    "&time1=" + t1_nogps+
    "&time2=" + t2_nogps+
    "&diff=" + diff_nogps+		
    "&lat1=" + lat_t1_nogps+
    "&lng1=" + lng_t1_nogps+
    "&lat2=" + lat_t2_nogps+
    "&lng2=" + lng_t2_nogps+
    "&speed1=" + speed1_nogps+
    "&speed2=" + speed2_nogps;
    //alert(poststr);    
    makePOSTRequest('src/php/datagap_getdivinfo.htm', poststr);
  }
  
  function nodata_get_divinfo(t1_nodata,t2_nodata,diff_nodata,lat_t1_nodata,lng_t1_nodata,lat_t2_nodata,lng_t2_nodata, speed1_nodata,speed2_nodata)
  {        
    var poststr = "type=NO DATA" +
    "&time1=" + t1_nodata +
    "&time2=" + t2_nodata +
    "&diff=" + diff_nodata +		
    "&lat1=" + lat_t1_nodata +
    "&lng1=" + lng_t1_nodata +
    "&lat2=" + lat_t2_nodata +
    "&lng2=" + lng_t2_nodata +
    "&speed1=" + speed1_nodata+
    "&speed2=" + speed2_nodata;
    //alert(poststr);    
    makePOSTRequest('src/php/datagap_getdivinfo.htm', poststr);
  } 

  /////////////////////  REPORT MODULE /////////////////////////////////////////  	  	
   function report_person_invalid_data(filename,title)            // manage.js
    {
        showManageLoadingMessage();
        var poststr = "title=" + encodeURI(title);	
        //alert("poststr="+poststr);
        makePOSTRequest(filename, poststr);
    }
  
  function report_common_prev(filename,title)            // manage.js
	{
		showManageLoadingMessage();
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
            //alert("poststr="+poststr);
    makePOSTRequest('src/php/report_common_prev.htm', poststr);
	}
	
	
	function report_common_prev_jquery(filename,title)            // manage.js
	{
		var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
		//alert(poststr);
		$.ajax({
		type: "POST",
		url:'src/php/report_common_prev.php',
		data: poststr,
		success: function(response){
			//console.log(response);
			//alert("response="+response);		
			
			$("#bodyspan").html(response);
			//document.getElementById('loading_pending_tanker').innerHTML="";	
		},
		error: function()
		{
			alert('An unexpected error has occurred! Please try later.');
		}
		});
	}
	
	function report_show_auto_manager(filename,title)            // manage.js
	{	
	//alert("file_name="+filename+"title="+title);
    var poststr = "title=" + encodeURI(title);	
    makePOSTRequest(filename, poststr);
	}
	
	function showManageLoadingMessage()
	{
		document.getElementById("loadingBlackout").style.visibility = "visible";
		document.getElementById("loadingDivPopUp").style.visibility = "visible";
		document.getElementById("loadingBlackout").style.display = "block";
		document.getElementById("loadingDivPopUp").style.display = "block"; 
	}
	
	
	
	function report_moto_master_prev(filename,title) 
	{	
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
    makePOSTRequest('src/php/report_moto_master_prev.htm', poststr);
	}
	function report_moto_prev(filename,title)        
	{	
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
    makePOSTRequest('src/php/report_moto_prev.htm', poststr);
	}
	
	function report_moto_monthly_prev(filename,title)            // manage.js
	{	
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
    makePOSTRequest('src/php/report_moto_monthly_prev.htm', poststr);
	}
	function report_moto_trip_performance_prev(filename,title)            // manage.js
	{	
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
    makePOSTRequest('src/php/report_moto_trip_performance_prev.htm', poststr);
	}
 function report_common_prev_station(filename,title)            // manage.js
	{    	
  	var accounst_id_local = radio_selection(document.report1.manage_id);
    if(account_id_local!=false)
    {
      var poststr = "accounst_id_local=" + encodeURI(accounst_id_local)+
                      "&title="+title;	
      makePOSTRequest('src/php/report_common_prev.htm', poststr);
    }
	}	
  function report_common_prev_mining(filename,title)            // manage.js
	{	
    //alert("2");
	showManageLoadingMessage();
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
    makePOSTRequest('src/php/report_common_prev_mining.htm', poststr);
	}		
	function report_common_prev_person(filename)            // manage.js
	{	
    var poststr = "filename=" + encodeURI(filename);	
    makePOSTRequest('src/php/report_common_prev_person.htm', poststr);
	}
	
	function report_search_vehicle_prev(filename,title)            // manage.js
	{	
     var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);		
    makePOSTRequest('src/php/report_search_vehicle_prev.htm', poststr);
	}
	
  function report_select_by_entity(filename,options)
	{
		var obj=document.report1.manage_id;
		var result = radio_selection(obj);
		//alert("filename="+filename+"option="+options);
		if(result!=false)
		{
		var poststr = "display_type1=" +options+
						"&account_id_local=" +result;
		//alert("poststr="+poststr);
		makePOSTRequest(filename, poststr);
		}
	}
	
	function display_option_vehicle(filename,options)
	{
		var poststr = "account_id_local1=" +document.getElementById("account_id_local1").value+
					  "&vehicle_display_option1=" +document.getElementById("vehicle_display_option").value+
					  "&options_value=" +document.getElementById("options_value").value+
					  "&options_type=" +options;
		//alert("poststr="+poststr);
		makePOSTRequest(filename, poststr);		
	}
	
  function radio_selection(obj)
  {
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
  		alert("Please Select Atleast One Option");
  		return false;
  	}
  	else
  	{
  	  // alert("id="+id);
  		return id;
  	}
  }	
  function action_report_moto_load_planning(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var obj1=obj.vehicleserial; 
    var device_str = radio_selection(obj1); 
	//alert("device_str="+device_str);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
		document.thisform.action="src/php/action_report_moto_load_planning.htm";
		document.thisform.target="_blank";
		document.thisform.submit();
	} 
  }
  
function action_report_inactive_data()
{
    var obj=document.thisform;
    var device_str = get_selected_vehicle(obj); 
    if(device_str!=false)
    {
        if(document.getElementById("duration").value=="select")
        {
            alert("Please select duration");
            return false;
        }
        else
        {

            document.getElementById("loading_msg").style.display = ''; 
            var poststr ="vehicleserial="+ encodeURI( device_str )+
                    "&timeDuration="+encodeURI( document.getElementById("duration").value);                
            //alert("poststr:"+poststr);
            makePOSTRequest('src/php/action_report_inactive_data.htm', poststr); 
        }
    }
    
}

function action_report_nogps_data()
{
    var obj=document.thisform;
    var device_str = get_selected_vehicle(obj); 
    if(device_str!=false)
    {
        if(document.getElementById("duration").value=="select")
        {
            alert("Please select duration");
            return false;
        }
        else
        {

            document.getElementById("loading_msg").style.display = ''; 
            var poststr ="vehicleserial="+ encodeURI( device_str )+
                    "&timeDuration="+encodeURI( document.getElementById("duration").value);                
           //alert("poststr:"+poststr);
           makePOSTRequest('src/php/action_report_nogps_data.htm', poststr); 
        }
    }
}

function report_moto_trip_display(file_name,title)
  {
   // var obj=document.report1.manage_id;   
    //var result=radio_selection(obj);    
    //if(result!=false)
    {
		if(document.getElementById("date1").value=="")
		{
			alert("Please Enter Start Date");
			document.getElementById("date1").focus();
			return false;
		}
		if(document.getElementById("date2").value=="")
		{
			alert("Please Enter End Date");
			document.getElementById("date2").focus();
			return false;
		}
		if(document.getElementById("date1").value>document.getElementById("date2").value)
		{
			alert("Start date should be less than end date");
			return false;
		}
		var poststr = "title="+title+
		"&start_date="+document.getElementById("date1").value+
		"&end_date="+document.getElementById("date2").value;
		/*var poststr = "account_id_local="+result+
		"&title="+title+
		"&start_date="+document.getElementById("date1").value+
		"&end_date="+document.getElementById("date2").value;*/
		//alert("account_id_local="+result+" ,vehicle_display_option="+vehicle_result+" ,options_value="+options_value);
		makePOSTRequest(file_name,poststr);
    }
  } 
  
	function action_report_moto_trip_performance(consignment_code,title)
	{
		var consignment_code1=consignment_code;
		var title1=title;
		if(consignment_code=="")
		{
			if(document.getElementById("consignment_code").value=="")
			{
				alert("Please Enter Consignment Code");
				document.getElementById("consignment_code").focus();
				return false;				
			}
			var consignment_code1=document.getElementById("consignment_code").value;
			var title1=document.getElementById("title").value;
		}
		//alert("consignment_code="+consignment_code+"title="+title);
		//document.getElementById("loading_msg").style.display = '';  		
		document.thisform.action="src/php/action_report_moto_trip_performance.htm?consignment_code="+consignment_code1+"&title="+title1;
		document.thisform.target="_blank";
		document.thisform.submit();	
	}
	
  function report_show_hide_moto(element_id)
	{
		if(document.getElementById("common_display_id").value=="")
		{
			document.getElementById("common_display_id").value=element_id+":1,";
			document.getElementById(element_id).style.display="";
		}
		else
		{	
			var ids_str_actual=document.getElementById("common_display_id").value;
			var ids_str = ids_str_actual.substring(0, ids_str_actual.length - 1)
			ids_str=ids_str.split(",");
			//alert("ids_str="+ids_str+"element_id="+element_id);
			var tmp=ids_str_actual.indexOf(element_id);
			//alert("tmp="+tmp);
			if(tmp!=-1)
			{
				for(var i=0;i<ids_str.length;i++)
				{
					var ids_str_1=ids_str[i].split(":");
					var find_str=ids_str_1[0]+":"+ids_str_1[1]+",";
					//alert("ids_str_1[0]="+ids_str_1[0]+"ids_str_1[1]="+ids_str_1[1]+"element_id="+element_id);
					if(element_id==ids_str_1[0])
					{				
						if(ids_str_1[1]=="1")
						{
							//alert("in if");
							var replace_str=element_id+":0,";
							document.getElementById(element_id).style.display="none";						
						}
						else if(ids_str_1[1]=="0")
						{					
							var replace_str=element_id+":1,";
							document.getElementById(element_id).style.display="";						
						}	
						document.getElementById("common_display_id").value="";			
						var final_str=ids_str_actual.replace(find_str,replace_str); 
						document.getElementById("common_display_id").value=final_str;
					}			
				}
			}
			else
			{
				//alert("in else");
				document.getElementById(element_id).style.display="";
				document.getElementById("common_display_id").value=document.getElementById("common_display_id").value+element_id+":1,";
				//alert("final_str_1="+document.getElementById("common_display_id").value);
			}
		}
	}
  function action_report_moto_dispatch_master(file_name,title)
	{
		var obj=document.report1.manage_id;      
		var result=radio_selection(obj);   
		if(result!=false)
		{
			if(document.getElementById("date1").value=="")
			{
				alert("Plese Enter Start Date");
				document.getElementById("date2").focus();
				return false;
			}			
			var poststr = "account_id_local="+result+  
			"&start_date="+document.getElementById("date1").value;		
			makePOSTRequest(file_name,poststr);
		}
	}
	function action_report_moto_stop_on_trip(file_name,title)
	{
		var obj=document.report1.manage_id;      
		var result=radio_selection(obj);   
		if(result!=false)
		{
			document.report1.action=file_name;
			document.report1.target="_blank";
			document.report1.submit();
		}
	}
	function action_report_moto_route_manager(obj)
	{
		//var obj=document.thisform.vehicleserial;      
		//var result=radio_selection(obj);   
		//if(result!=false)
		{
			document.thisform.action="src/php/action_report_moto_route_manager.htm";
			document.thisform.target="_blank";
			document.thisform.submit();
		}
	}
	
	function action_report_moto_geocode_management()
	{
		var obj=document.thisform.vehicleserial;      
		var result=radio_selection(obj);   
		if(result!=false)
		{
			document.thisform.action="src/php/action_report_moto_geocode_management.htm";
			document.thisform.target="_blank";
			document.thisform.submit();
		}
	}
	
	function action_report_moto_monthly_cmp(file_name,title)
	{
		var obj=document.report1.manage_id;      
		var result=radio_selection(obj);   
		if(result!=false)
		{						
			var poststr = "account_id_local="+result+ 
						  "&title="+title+			
						  "&prev_month="+document.getElementById("prev_month").value+
						  "&prev_year="+document.getElementById("prev_year").value+	
						  "&current_month="+document.getElementById("current_month").value+	
						  "&current_year="+document.getElementById("current_year").value;			
			makePOSTRequest(file_name,poststr);
		}
	}

  function report_tree_validation(obj)
  {
	//alert("obj="+obj);
  	var tree_option_id = "";	    
    var radio_menu = obj.vehicle_display_option; 
    //alert("radio_menu="+radio_menu);
    for(i=0;i<radio_menu.length;i++)
    {
      if(radio_menu[i].checked)
        var radio_all = radio_menu[i].value;
    }
    
    if(radio_all!="all")
  	{  	 
      var tree_option_obj = obj.elements['manage_option[]'];  
	  //alert("tree_option_obj="+tree_option_obj);
    	var num1=0;   var count=0;    var cnt=0		
    	if(tree_option_obj.length!=undefined)
    	{
    		for(i=0;i<tree_option_obj.length;i++)
    		{
    			if(tree_option_obj[i].checked)
    			{
    				if(cnt==0)
    				{
    					tree_option_id =  tree_option_id + tree_option_obj[i].value;
    					cnt=1
    				}
    				else
    				{
    					tree_option_id = tree_option_id+ "," + tree_option_obj[i].value;
    				}
    				num1 = 1;
    			}
    		}
    	}
		else
		{
			//alert("in else");
			if(tree_option_obj.checked)
			{
				tree_option_id=tree_option_id + tree_option_obj.value;
				//tree_option_id=tree_option_id + radio_all;
				num1 = 1;
			}
		}
    }
  	else
  	{
  		//alert("in else");
  		//if(tree_option_obj.checked)
  		//{
  			//tree_option_id=tree_option_id + tree_option_obj.value;
  			tree_option_id=tree_option_id + radio_all;
  			num1 = 1;
  		//}
  	}
  	
  	if(num1==0)
  	{
  		alert("Please Select At Least One Option");							
  		return false;  			
  	}
  	else
  	{
  		return tree_option_id;
  	}	
  }
	

  function report_display_vehicle(file_name,title)
  {
    //alert("file_name="+file_name);
    var obj=document.report1.manage_id;
    var obj1=document.report1;
    
    var result=radio_selection(obj);
    var vehicle_result=radio_vehicle_option_selection(obj);
    var options_value=report_tree_validation(obj1);
    if(result!=false && vehicle_result!=false && options_value!=false)
    {
    var poststr = "account_id_local="+result+
    "&vehicle_display_option="+vehicle_result+
	"&filename="+file_name+
    "&title1="+title+
    "&options_value="+options_value;
    //alert("poststr="+poststr);
    makePOSTRequest(file_name,poststr);
    }
  }

   function report_search_vehicle(file_name)
  {	
  	var obj=document.report1.manage_id; 	
  	var result=radio_selection(obj);
  	if(result!=false)
  	{		
        var poststr = "account_id_local="+result;
  		//alert("account_id_local="+result+" ,vehicle_display_option="+vehicle_result+" ,options_value="+options_value);
  		makePOSTRequest(file_name,poststr);
  	}
  }	
  
  
  function radio_vehicle_option_selection()
  {
  	var obj=document.report1.vehicle_display_option;
  	var flag=0;
  	
  	for(var i=0;i<obj.length;i++)
  	{
  		if(obj[i].checked==true)
  		{
  		    var vehicle_option=obj[i].value;
  			flag=1;
  		}
  	}
  	if(flag==0)
  	{
  		alert("Please check atleast one vehicle option"); 
  		return false;		
  	}
  	else
  	{
  		return vehicle_option;
  	}
  	//alert("vehicle_option="+vehicle_option);
  }  
  	
  function change_icon(id, name)
	{
		//alert(name+","+id);
		if(name =="plus")
		{
			document.getElementById(id).name ="minus";
			document.getElementById(id).src ="./images/report_icons/minus.gif";
		}
		else if(name == "minus")
		{
			document.getElementById(id).name ="plus";
			document.getElementById(id).src ="./images/report_icons/plus.gif";			
		}
	}  
  	
  function select_report_options(options)
  {
  	//var display_type="group";
  	var poststr = "display_type1=" + encodeURI(options);
  	//alert("patstr="+poststr);
  	makePOSTRequest('src/php/module_report_selection_information.htm', poststr);
  }
  
  /*function report_select_by_entity(options)
	{
		var poststr = "display_type1=" + encodeURI(options);
		makePOSTRequest('src/php/report_entity_selection_information.htm', poststr);
	}*/
	
  function select_report_all_portal_option(obj)
  {
  	if(obj.all_1.checked)
  	{
  		var i;
  		var s = obj.elements['manage_option[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked="true";			
  	}
  	else if(obj.all_1.checked==false)
  	{
  		var i;
  		var s = obj.elements['manage_option[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked=false;			
  	}
  }
  
  function report_show_entity_option(target_file_prev, target_file) // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
	{	
	  //alert(target_file_prev)
		var poststr = "target_file="+target_file;
		if(target_file=="account_details")
		{
			makePOSTRequest("src/php/report_add_choose_account.htm", '');
		}
		else
		{
			makePOSTRequest(target_file_prev, poststr);
		}
	}  
  
  function report_show_vehicle(account_id)
  {    
    var poststr = "acc_id=" + encodeURI( account_id );                      
    makePOSTRequest('src/php/module_show_vehicle_chk.htm', poststr);
  }
  
  function map_show_vehicle(account_id)
  {    
      //var res = false;
      //res = validate_report_select_vehicle(obj);              
      
      //if(res == true)
      //{
      //alert("Riz:"+account_id);
        var poststr = "acc_id=" + encodeURI( account_id );                      
        makePOSTRequest('src/php/module_map_show_vehicle_chk.htm', poststr);
      //}   
  }
  // GET SELECTED VEHICLE
	function get_iotypeimei_selected(obj)
	{
		var flag=0;
		var i;
		var s = obj.elements['vehicleserial[]'];		
		var vehicle_str="";
		var text_report_io_element="";
		if(s.length!=undefined)
		{
			for(i=0;i<s.length;i++)
			{             
				if(s[i].checked)
				{
					//alert("value="+s[i].value);
					var value_tmp=s[i].value;
					var imei_local=value_tmp.split("*");				
					text_report_io_element=text_report_io_element+imei_local[1]+",";	
					//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
					vehicle_str=vehicle_str+imei_local[0]+":";				
					flag=1;       
				}  
			}	
		}
		else
		{
			if(s.checked)
			{
				//vehicle_str = s.value;
				var value_tmp=s.value;
				var imei_local=value_tmp.split("*");				
				text_report_io_element=text_report_io_element+imei_local[1]+",";	
				//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
				vehicle_str=vehicle_str+imei_local[0]+":";
				flag=1;
			}
		}
		if(flag==0)
		{
			alert("Please Select Atleast One Option");
			return false;
		}
		else
		{
			var strIOElement = text_report_io_element.length;
				text_report_io_element = text_report_io_element.slice(0,strIOElement-1);
			var strLen = vehicle_str.length;
				vehicle_str = vehicle_str.slice(0,strLen-1);
			return vehicle_str+"#"+text_report_io_element;
		}	
	} 
     
	 //by taseen
	 function get_iotypeimei_selected_radio(obj)
	{
		var flag=0;
		var i;
		var s = obj.elements['vehicleserial'];		
		var vehicle_str="";
		var text_report_io_element="";
		if(s.length!=undefined)
		{
			for(i=0;i<s.length;i++)
			{             
				if(s[i].checked)
				{
					//alert("value="+s[i].value);
					var value_tmp=s[i].value;
					var imei_local=value_tmp.split("*");				
					text_report_io_element=text_report_io_element+imei_local[1]+",";	
					//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
					vehicle_str=vehicle_str+imei_local[0]+":";				
					flag=1;       
				}  
			}	
		}
		else
		{
			if(s.checked)
			{
				//vehicle_str = s.value;
				var value_tmp=s.value;
				var imei_local=value_tmp.split("*");				
				text_report_io_element=text_report_io_element+imei_local[1]+",";	
				//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
				vehicle_str=vehicle_str+imei_local[0]+":";
				flag=1;
			}
		}
		if(flag==0)
		{
			alert("Please Select Atleast One Option");
			return false;
		}
		else
		{
			var strIOElement = text_report_io_element.length;
				text_report_io_element = text_report_io_element.slice(0,strIOElement-1);
			var strLen = vehicle_str.length;
				vehicle_str = vehicle_str.slice(0,strLen-1);
			return vehicle_str+"#"+text_report_io_element;
		}	
	} 
	
	function get_selected_vehicle(obj)
	{
		var flag=0;
		var i;
		var s = obj.elements['vehicleserial[]'];		
		var vehicle_str="";
		if(s.length!=undefined)
		{
			for(i=0;i<s.length;i++)
			{             
				if(s[i].checked)
				{
					if(vehicle_str=="")
					{
						vehicle_str = s[i].value; 
					}
					else
					{
						vehicle_str = vehicle_str+":"+s[i].value;        
					} 
					flag=1;       
				}  
			}	
		}
		else
		{
			if(s.checked)
			{
				vehicle_str = s.value;
				flag=1;
			}
		}
		if(flag==0)
		{
			alert("Please Select Atleast One Option");
			return false;
		}
		else
		{	  
			return vehicle_str;
		}	
	}

function get_selected_vehicle_radio(obj)
	{	
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
			alert("Please Select Atleast One Option");
			return false;
		}
		else
		{
		   // alert("id="+id);
			return id;
		}		
	}	
  
  /////// GET SELECTED VEHICLE DATALOG
  function get_selected_vehicle_datalog(obj)
	{
	  var flag=0;
    var i;
		var s = obj.elements['vehicleserial'];		
		var vehicle_str="";
		if(s.length!=undefined)
		{
      for(i=0;i<s.length;i++)
  		{             
        if(s[i].checked)
        {
          if(vehicle_str=="")
          {
            vehicle_str = s[i].value; 
          }
          else
          {
            vehicle_str = vehicle_str+":"+s[i].value;        
          } 
          	flag=1;       
        }  
      }	
    }
    else
    {
      if(s.checked)
      {
        vehicle_str = s.value;
        flag=1;
      }
    }
    if(flag==0)
  	{
  		alert("Please Select Atleast One Option");
  		return false;
  	}
  	else
  	{	  
  	  return vehicle_str;
  	}	
  }  
  ////////////////////////////////    
  
  function get_selected_day(obj)
	{
		var i;
		var s = obj.elements['days[]'];
		var flag=0;
		var day_str="";
		if(s.length!=undefined)
		{
			for(i=0;i<s.length;i++)
			{             
				if(s[i].checked)
				{
					if(day_str=="")
					{
						day_str = s[i].value; 
					}
					else
					{
						day_str = day_str+":"+s[i].value;        
					} 
					flag=1;
				}  
			}
		}
		else
		{
			if(s.checked)
			{
				day_str = s.value;
				flag=1;
			}
			
		}
		if(flag==0)
		{
			alert("Please select atleast one option");
			return false;
		}
		else
		{
			return day_str;	
		}		
	}      
   	
  
  //1.VEHICLE REPORT  
  function action_report_vehicle(obj)
  {    
    // GET SELECTED VEHICLES 

    var device_str = get_selected_vehicle(obj);

	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
		    document.getElementById("loading_msg").style.display = '';
		// MAKE OPTION STRING		
		var i;
		var s = obj.elements['voption[]'];
		//alert("Rizwan:in action"+s.length);
		var option_str="";
		for(i=0;i<s.length;i++)
			{       
		  //alert("Rizwan:in action1"+s[i].value);
		  if(s[i].checked)
		  {
			if(option_str=="")
			{
			  option_str = s[i].value; 
			}
			else
			{
			  option_str = option_str+":"+s[i].value;        
			}        
		  }  
		}					    
		//alert("Rizwan:in action-dstr="+device_str+" options="+option_str);        
		var poststr = "vehicleserial=" + encodeURI( device_str ) +
		
			"&option=" + encodeURI( option_str );                                   
					  //alert("riz:"+poststr);
	}                       
    makePOSTRequest('src/php/action_report_vehicle.htm', poststr);
  }  
   function action_report_consignment_info(obj)
  {
   
    
    var device_str = get_selected_vehicle(obj);
    if(device_str==false)
    {
            document.getElementById("loading_msg").style.display='none';
    }	
    if(device_str!=false)
    {
        var consignment_type_str = checkbox_selection(obj.elements['consignment_type[]']);
        if(consignment_type_str!=false)
        {
             document.getElementById("loading_msg").style.display = ''; 
        var poststr = "vehicleserial=" + encodeURI( device_str ) +
		            "&selected_account_id=" + document.getElementById("selected_account_id").value +
					"&selected_options_value=" + document.getElementById("selected_options_value").value +
					"&s_vehicle_display_option=" + document.getElementById("s_vehicle_display_option").value +
                    "&consignment_type_str=" + encodeURI(consignment_type_str)+
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value ); 
        //alert("poststr="+poststr);
         makePOSTRequest('src/php/action_report_consignment_info.htm', poststr);
        } 
    }   
  }  
  //1.SPEED REPORT  
  
  function action_report_speed(obj)
  {
    // GET SELECTED VEHICLES 
    //alert("one="+document.getElementById("loading_msg").style.display);
    document.getElementById("loading_msg").style.display = '';  
    //alert("two="+document.getElementById("loading_msg").style.display);
     
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +				
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                
                  //alert("Rizwan:"+poststr);  
	}
                   
    makePOSTRequest('src/php/action_report_speed.htm', poststr);
  }

function action_report_version(obj)
{
	document.getElementById("loading_msg").style.display = '';
	var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	if(device_str!=false)
	{
		var poststr = "vehicleserial=" + encodeURI( device_str );
	}
	makePOSTRequest('src/php/action_report_version.htm', poststr);
}

function action_report_nearby_location(obj)
{
    //alert("obj="+obj);
    document.getElementById("reportPrevPage").innerHTML='';
    //alert("two="+document.getElementById("loading_msg").style.display);   
    var rec = obj.vehicleserial;
    var device_str = radio_selection(rec); 
    //alert("update");
    if(device_str!=false)
    {       
        document.thisform.submit();
    }
}  
  
  function action_report_klp_input(filename,title)
	{
		//alert("file_name="+file_name);
		var obj=document.report1.manage_id;
		var obj1=document.report1;

		var result=radio_selection(obj);
		if(result!=false)
		{
			if(document.getElementById('date1').value=="")
			{
				alert("Please Enter the date");
				document.getElementById('date1').focus();
				return false;
			}
			else
			{
				var poststr = "account_id_local="+result+
				"&title="+title+
				"&report_date="+document.getElementById('date1').value;
				//alert("poststr="+poststr);
				makePOSTRequest(filename,poststr);
			}
		}
	}
	
	function klp_report_prev(filename,title)            // manage.js
	{	
    var poststr = "filename=" + encodeURI(filename)+
				  "&title=" + encodeURI(title);	
    makePOSTRequest(filename, poststr);
	}
	
	 function report_pdf_csv(target_file)
    {
      //alert("reportcsv");
      document.forms[0].action = target_file;    
      document.forms[0].submit();
    }
  //ACTION REPORT LOAD CELL
  
  function action_report_load_cell(obj)
  {
    // GET SELECTED VEHICLES 
    //alert("one="+document.getElementById("loading_msg").style.display);
    document.getElementById("loading_msg").style.display = '';  
    //alert("two="+document.getElementById("loading_msg").style.display);
     
    var device_str = get_selected_vehicle(obj); 
    if(device_str==false)
    {
    	document.getElementById("loading_msg").style.display='none';
    }
	
    if(device_str!=false)
	  {
        var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                  
                  //alert("Rizwan:"+poststr);  
	  }
    
    //alert(poststr);               
    makePOSTRequest('src/php/action_report_load_cell.htm', poststr);
  }             
  
  //1.DISTANCE REPORT  
  function action_report_distance(obj)
  {
    // GET SELECTED VEHICLES    
    var startDate1 = document.getElementById("date1").value;
    var endDate1 = document.getElementById("date2").value;
    
    var sd = startDate1.split(" ");
    var ed = endDate1.split(" "); 
    
    var d1 = new Date(sd[0]);
    var m1 = d1.getTime(); 
    
    var d2 = new Date(ed[0]);
    var m2 = d2.getTime(); 	
    
    var mdiff = m2 - m1;               //five Days difference    =432000000 , 30 days diff= 2592000000  (eg.30*60*60*24*1000)
    
    //alert("mdiff="+mdiff); 
	
    //if(mdiff > 432000000)
    if(mdiff > 2592000000)
    {
      alert("Maximum 30 days report is allowed at once");
      return false;
    }
    
    document.getElementById("loading_msg").style.display = '';  
    
    //var device_str = get_selected_vehicle(obj); 
    var rec = obj.vehicleserial;
    var device_str = radio_selection(rec);    
    
  	if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
  	{    
      var poststr = "vehicleserial=" + encodeURI( device_str ) +					
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                    "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                  
                    //alert("riz:"+poststr);  
  	}                     
    makePOSTRequest('src/php/action_report_distance.htm', poststr);
  }
  
   /*function showReportPrevPage(displayPageName,selected_account_id,selected_options_value,s_vehicle_display_option)
  {
	var poststr="account_id_local="+selected_account_id+
				"&vehicle_display_option="+s_vehicle_display_option+
				"&options_value="+selected_options_value;
	//alert("poststr="+poststr+"displayPageName="+displayPageName);
	 makePOSTRequest('src/php/'+displayPageName, poststr);
  }*/
  
  function showReportPrevPage(displayPageName,selected_account_id,selected_options_value,s_vehicle_display_option,start_date,end_date,strArrEnc)
  {
	var poststr="account_id_local="+selected_account_id+
				"&vehicle_display_option="+s_vehicle_display_option+
				"&start_date="+start_date+
				"&end_date="+end_date+
				"&strArrEnc="+strArrEnc+				
				"&options_value="+selected_options_value;
	//alert("poststr="+poststr+"displayPageName="+displayPageName);
	 makePOSTRequest('src/php/'+displayPageName, poststr);
  }
  
   function showReportPrevPageWithInterval(displayPageName,selected_account_id,selected_options_value,s_vehicle_display_option,start_date,end_date,strArrEnc,userInterval)
  {
	var poststr="account_id_local="+selected_account_id+
				"&vehicle_display_option="+s_vehicle_display_option+
				"&start_date="+start_date+
				"&end_date="+end_date+			
				"&strArrEnc="+strArrEnc+				
				"&options_value="+selected_options_value;
	//alert("poststr="+poststr+"displayPageName="+displayPageName);
	 makePOSTRequest('src/php/'+displayPageName, poststr);
	 document.getElementById("user_interval").value=userInterval;
  }
  
  function showReportPrevPageNew()
  {
	document.getElementById('loading_msg').style.display="none";
	document.getElementById('rightMenu').style.display="none";
	document.getElementById('reportPrevPage').style.display="";
	document.getElementById('bodyspan').innerHTML="";
	document.getElementById('bodyspan').style.display="none";	
  }
  
  function showCommonPrevPage(displayPageName,filename,title)
  {
	var poststr="filename="+filename+
				"&title="+title;
	//alert("poststr="+poststr+"displayPageName="+displayPageName);
	 makePOSTRequest('src/php/'+displayPageName, poststr);
  }
  
  function showNearByPrevPage(displayPageName,selected_account_id)
  {
	var poststr="account_id_local="+selected_account_id;
	//alert("poststr="+poststr+"displayPageName="+displayPageName);
	 makePOSTRequest('src/php/'+displayPageName, poststr);
  }
  
  
  
  //TEMPERATURE
  function action_report_temperature(obj)
  {
      // GET SELECTED VEHICLES 
      document.getElementById("loading_msg").style.display = '';  
      var device_str = get_selected_vehicle(obj); 
    	if(device_str==false)
    	{
    		document.getElementById("loading_msg").style.display='none';
    	}
  	if(document.getElementById("serverTimeCheck").checked==true)
	{
            var getDataBy=1;
	}
        else
        {
            var getDataBy=0;  
        }
      if(device_str!=false)
  	  {    
        var poststr = "vehicleserial=" + encodeURI( device_str ) +				
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value )+ 
                    "&getDataBy=" + getDataBy+
                    "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                  
                    //alert("riz:"+poststr);
         makePOSTRequest('src/php/action_report_temperature.htm', poststr);
  	 } 	
  }  
  
  
  //1.PERFORMANCE REPORT  
  
  function action_report_performance(obj)
  {
    //alert("in action");
    // GET SELECTED VEHICLES 
    document.getElementById("loading_msg").style.display = '';  
    var device_str = get_selected_vehicle(obj); 
  	if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
  	
    if(device_str!=false)
  	{          
      var poststr = "vehicleserial=" + encodeURI( device_str ) +	               
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value )+
                    "&filter_flag=" + encodeURI( document.getElementById("filter_flag").checked )+   
                    "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                  
                      
  	}
      //alert("riz:"+poststr);               
      makePOSTRequest('src/php/action_report_performance.htm', poststr);
  }               
    
  //1.MONTHLY DISTANCE REPORT    
  function action_report_daily_distance(obj)
  { 
    var option_choices=0;
    var numtype = 0;
    var i = 0;   
    numtype=0;
    
    var rec = obj.days;
    var device_str = get_selected_vehicle(obj);
	
	if(obj.reportType.checked==true)
	{
	var reportType="speed";
	}
	else
	{
		var reportType="noSpeed";
	}
   
    if(device_str!=false)
    {	
    	var day_str = radio_selection(rec); 
    	if(day_str!=false)
    	{
    		document.getElementById("loading_msg").style.display = '';  
    		var poststr = "vehicleserial=" + encodeURI( device_str ) +						 
    					  "&month=" + encodeURI( document.getElementById("month").value )+
    					  "&year=" + encodeURI( document.getElementById("year").value )+
    					  "&days=" + encodeURI( day_str )+
						  "&reportType=" + encodeURI(reportType);
        //alert(poststr);							  
    	}
    }     					
    //alert("riz:"+poststr);                
    makePOSTRequest('src/php/action_report_daily_distance.htm', poststr);
  }
  
  function action_report_monthly_distance(obj)
  { 
		var option_choices=0;
		var numtype = 0;
		var i = 0;
		
		numtype=0;
    if(obj.day_opt.value == "2")
		{
      //alert("2");
      var s = obj.elements['days[]'];
  		for(i=0;i<s.length;i++)
  		{
  			if(s[i].checked)
  				numtype = 1;
  		}
  		if(numtype==0)
  		{
  			alert("Please Select At Least One Day");
  			return false;
  		}	
  	}      
    
    var rec = obj.vehicleserial;
    var device_str = radio_selection(rec); 	 
    //var device_str = get_selected_vehicle(obj); 
   
    if(device_str!=false)
    {
    	var day_opt1=document.getElementById("day_opt").value;		
    	if(day_opt1=="1")
    	{
    		document.getElementById("loading_msg").style.display = '';  
    		var poststr = "vehicleserial=" + encodeURI( device_str ) +					
    					  "&month=" + encodeURI( document.getElementById("month").value )+
    					  "&year=" + encodeURI( document.getElementById("year").value )+
    					  "&days=" + encodeURI( day_str )+
    					  "&day_opt=" + encodeURI( document.getElementById("day_opt").value ); 			
    	}
    	else
    	{
    		var day_str = get_selected_day(obj);
    		if(day_str!=false)
    		{
    			document.getElementById("loading_msg").style.display = '';  
    			var poststr = "vehicleserial=" + encodeURI( device_str ) +							
    						  "&month=" + encodeURI( document.getElementById("month").value )+
    						  "&year=" + encodeURI( document.getElementById("year").value )+
    						  "&days=" + encodeURI( day_str )+
    						  "&day_opt=" + encodeURI( document.getElementById("day_opt").value );  
    		}
    	}
    }     					
    //alert("riz:"+poststr);                
    makePOSTRequest('src/php/action_report_monthly_distance.htm', poststr);
  } 
   
  
	function day_option(obj)
	{
		//alert("dayopt="+obj+",val="+obj.day_opt.value);
    var i;
				    			
    if(obj.day_opt.value == "1")
		{  		
      var s = obj.elements['days[]'];
  		for(i=0;i<s.length;i++)
  		{
  			s[i].checked=false;
        s[i].disabled=true;
      }		
      document.getElementById("alldays").display='none';	
		}
		
		else if(obj.day_opt.value == "2")
		{		
      //alert(document.getElementById("alldays").display);
      document.getElementById("alldays").display=''; 
      
      var s = obj.elements['days[]'];
      for(i=0;i<s.length;i++)
      {
        s[i].disabled=false;
      	s[i].checked=false;	
      }            		
		}
	}         
    
  //1.FUEL REPORT  
  
 function action_report_fuel(obj)
  {
    //alert("In action");
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj); 
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
	   {
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
					"&selected_account_id=" + document.getElementById("selected_account_id").value +
					"&selected_options_value=" + document.getElementById("selected_options_value").value +
					"&s_vehicle_display_option=" + document.getElementById("s_vehicle_display_option").value +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                  
                  //alert("riz:"+poststr);
	 } 
   //alert(poststr);          
    makePOSTRequest('src/php/action_report_fuel.htm', poststr);
  }        

  //1.SUMMARY REPORT  
  
  function action_report_summary(obj)
  {    
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +				
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                  
                  //alert("riz:"+poststr); 
	}                   
    makePOSTRequest('src/php/action_report_summary.htm', poststr);
  }  
  

  //1.FUEL REPORT   rrrrrrrrrrrrrrrrrrrrrr
  
  function action_report_engine_runhr(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                
                  //alert("riz:"+poststr);
	}                   
    makePOSTRequest('src/php/action_report_engine_runhr.htm', poststr);
  }

  function action_report_ac_runhr(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                
                  //alert("riz:"+poststr);
	}                   
    makePOSTRequest('src/php/action_report_ac_runhr.htm', poststr);
  }      
  
  function action_report_sos(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{	
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                
                  //alert("riz:"+poststr);
	}                   
    makePOSTRequest('src/php/action_report_sos.htm', poststr);
  }      
  
  function action_report_door_open(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{	
    var poststr = "vehicleserial=" + encodeURI( device_str ) +				  
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                
                  //alert("riz:"+poststr);
	}                   
    makePOSTRequest('src/php/action_report_door_open.htm', poststr);
  }

  function action_report_fuel_lead(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{	
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                
                  //alert("riz:"+poststr);
	}                   
    makePOSTRequest('src/php/action_report_fuel_lead.htm', poststr);
  }          
  
  //1.FUEL REPORT  
  
  function action_report_get_vehicles_data(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    var date = new Date();      
    var xml_file = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml"
    
    var mode=2;
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vserial=" + encodeURI( device_str ) +
                  "&xml_file=" + encodeURI( xml_file )+
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&mode=" + encodeURI( mode )+  
                  "&case=" + encodeURI( document.getElementById("case").value );                  
                  //alert("riz:"+poststr); 
	}                   
    makePOSTRequest('src/php/report_get_vehicles_data.htm', poststr);
  }   
  
  function action_report_trip(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    var date = new Date();      
    var xml_file = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
    
    var mode=2;
    var device_str = get_selected_vehicle(obj);
  	if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
  	{	
      var st = document.getElementById("date1").value;
      var et = document.getElementById("date2").value;      
      //var et = st+" 23:59:59";
      //st = st+" 00:00:00";
              
      var poststr = "vserial=" + encodeURI( device_str ) +
					"&trip_old=0"+
                    "&xml_file=" + encodeURI( xml_file )+
                    "&start_date=" + encodeURI( st )+
                    "&end_date=" + encodeURI( et )+  
                    "&mode=" + encodeURI( mode )+
                    "&group_id_local=" + encodeURI( document.getElementById("group_id_local").value )+                      
                    "&case=" + encodeURI( document.getElementById("case").value );                  
                    //alert("riz:"+poststr);  
  	}                   
    makePOSTRequest('src/php/action_report_trip.htm', poststr);
  } 
  
  
	function action_report_trip_old(vserial)
	{
		//alert("vserial="+vserial);
		document.getElementById("loading_msg").style.display = '';    
		var date = new Date();      
		var xml_file = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
		var mode=2;	
		var st = document.getElementById("date1").value;
		var et = document.getElementById("date2").value;      
		//var et = st+" 23:59:59";
		//st = st+" 00:00:00";		  
		var poststr = "vserial=" +encodeURI(vserial) +
		"&trip_old=1"+
		"&xml_file=" + encodeURI( xml_file )+
		"&start_date=" + encodeURI( st )+
		"&end_date=" + encodeURI( et )+  
		"&mode=" + encodeURI( mode )+
		"&group_id_local=" + encodeURI( document.getElementById("group_id_local").value )+                      
		"&case=" + encodeURI( document.getElementById("case").value );                  
		//alert("riz:"+poststr);  			   
		makePOSTRequest('src/php/action_report_trip.htm', poststr);
	} 
  

 function action_report_trip_new(obj)
  {
    
    var date = new Date();      
    var xml_file = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml"
    
    var mode=2;
	//var obj=obj.vehicleserial;
    var device_str = get_selected_vehicle(obj);
  	if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
  	{	
      var st = document.getElementById("date1").value;
      var et = document.getElementById("date2").value; 
	  	var t1 = new Date(st)
		var t2 = new Date(et)
		var dif = t1.getTime() - t2.getTime();
		//alert("diff="+dif);

		var Seconds_from_T1_to_T2 = dif / 1000;
		var Seconds_Between_Dates = Math.abs(Seconds_from_T1_to_T2);
		var tmp=60*60;
		var hour_Between_Dates=Seconds_Between_Dates/tmp;
		//alert("hour_Between_Dates="+hour_Between_Dates);
	  if(hour_Between_Dates>12)
	  {
		alert("Please select duration with in 12 hour.")
		return false;
	  }
	  
	  document.getElementById("loading_msg").style.display = '';
      //var et = st+" 23:59:59";
      //st = st+" 00:00:00";
              
      var poststr = "vserial=" + encodeURI( device_str ) +
                    "&xml_file=" + encodeURI( xml_file )+
                    "&start_date=" + encodeURI( st )+
                    "&end_date=" + encodeURI( et )+  
                    "&mode=" + encodeURI( mode )+
                    "&group_id_local=" + encodeURI( document.getElementById("group_id_local").value )+                      
                    "&case=" + encodeURI( document.getElementById("case").value );                  
               
  	}                   
    makePOSTRequest('src/php/action_report_trip_new.htm', poststr);
  }  
  
  ////////////////////////// TRIP////////////////////////////////
  //1.FUEL REPORT  
  
  function action_report_trip_summary(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj); 
  	if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
  	{
      var st = document.getElementById("date1").value;
      var et = st+" 23:59:59";
      st = st+" 00:00:00";
            	
      var poststr = "vserial=" + encodeURI( device_str ) +
                    "&start_date=" + encodeURI( st )+
                    "&end_date=" + encodeURI( et )+
                    "&group_id_local=" + encodeURI( document.getElementById("group_id_local").value );                                    
                    //alert("poststr:"+poststr);
  	}                   
    makePOSTRequest('src/php/action_report_trip_summary.htm', poststr);
  }                          
  
  //1.VIEW LOGGED DATA REPORT  
  //A.
  
  function action_report_datalog(obj, option)
  {  
	  //alert("action report datalog"+obj+" ,opt="+option);    
    //var result=radio_selection(obj);
	//var result=checkbox_selection_datalog(obj);
    var account_id_local1 = document.getElementById("account_id_local1").value;
    
    //alert("option="+option);
    if(option == "today")
    {
  		//alert("today");
  		var rec = obj.rec;
  		//alert("rec="+rec+" obj="+obj)
  		//var device_str = get_selected_vehicle_datalog(obj);
		var device_str = checkbox_selection_datalog(obj);
  		//alert("device_str="+device_str);
  		
      if(device_str==false)
  		{
  			document.getElementById("loading_msg").style.display='none';
  		}
  
  		if(device_str!=false)
  		{
  			var radio_value=radio_selection(rec); 
  			if(radio_selection!=false)
  			{
  				document.getElementById("loading_msg").style.display = '';
  
  				var date = document.getElementById("date1").value      
  				var hrfrom = document.getElementById("hrfrom").value;
  				var mifrom = document.getElementById("mifrom").value;
  				var ssfrom = document.getElementById("ssfrom").value;
  				var hrto  =  document.getElementById("hrto").value;
  				var mito  =  document.getElementById("mito").value;
  				var ssto	=  document.getElementById("ssto").value;
  				var date1 = date+" "+hrfrom+":"+mifrom+":"+ssfrom;
  				var date2 = date+" "+hrto+":"+mito+":"+ssto;
  
  				var poststr = "account_id_local="+account_id_local1+
  				  "&id=1" +
  				  "&vehicleserial=" + encodeURI( device_str ) +
				  "&option=" + encodeURI( option ) +				
  				  "&start_date=" + encodeURI( date1 )+
  				  "&end_date=" + encodeURI( date2 )+
  				  "&radio_value=" +radio_value;
  			}
  		}
    } 
    
    else if(option == "date")
    {
      var rec = obj.rec;
      //var device_str = get_selected_vehicle_datalog(obj);
	  var device_str = checkbox_selection_datalog(obj);
  		if(device_str==false)
  		{
  			document.getElementById("loading_msg").style.display='none';
  		}
  
  		if(device_str!=false)
  		{	  
  		  var radio_value=radio_selection(rec); 
  			if(radio_selection!=false)
  			{
  			  document.getElementById("loading_msg").style.display = '';
  			  var poststr = "account_id_local="+account_id_local1+
  					  "&id=2" +
  					  "&vehicleserial=" + encodeURI( device_str ) +
					  "&option=" + encodeURI( option ) +					 
  					  "&start_date=" + encodeURI( document.getElementById("date1").value )+
  					  "&end_date=" + encodeURI( document.getElementById("date2").value )+
  					  "&radio_value=" +radio_value;
  			}
  		}
    }   
    
    /*else if(option == "vehicle")
    {
      var rec = obj.rec;
      var device_str = get_selected_vehicle(obj);            
      var radio_value=radio_selection(rec); 
		  document.getElementById("loading_msg").style.display = '';
      var poststr = "account_id_local="+account_id_local1+ 
                  "&id=3" +
                  "&vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );
    }*/
    
    else if(option == "search")
    {
      if(document.getElementById("text_content").value=="")
      {
          alert("Please Enter Vehicle Name");
          document.getElementById("text_content").focus();
          return false;
      } 
      var rec = obj.rec;
                
      var radio_value=radio_selection(rec); 
      if(radio_value!=false) 
      {
        document.getElementById("loading_msg").style.display = '';	  
        var poststr = "account_id_local="+account_id_local1+
                  "&id=3" +
                  "&vehicleserial=" +document.getElementById('device_imei_no').value +
                  "&start_date=" +document.getElementById("date1").value +
                  "&end_date=" +document.getElementById("date2").value+
                  "&radio_value=" +radio_value;
      }
    }  
	  else if(option == "specify_vehicle")
    {
      //var serial_obj = obj.elements['vehicleserial'];      
      //var vehicle_serials=checkbox_selection(serial_obj);
      //var vehicle_serials = get_selected_vehicle_datalog(obj);
	  var vehicle_serials = checkbox_selection_datalog(obj);
      //alert("vehicle_serials="+vehicle_serials);
      if(vehicle_serials!=false)
      {
        var rec = obj.rec;		
        var radio_value=radio_selection(rec); 
        if(radio_value!=false)
        {
        	document.getElementById("loading_msg").style.display = '';
          var poststr = "account_id_local="+account_id_local1+
                        "&id=4" + 
                        "&vehicleserials"+vehicle_serials+
                        "&start_date=" + encodeURI( document.getElementById("date1").value )+
                        "&end_date=" + encodeURI( document.getElementById("date2").value )+
                        "&radio_value=" +radio_value;
        }
        
        var rec = obj.rec;
        //var device_str = get_selected_vehicle_datalog(obj);  
		var device_str = checkbox_selection_datalog(obj);		
        var radio_value=radio_selection(rec);
    		if(radio_value!=false)
        {	  
    		  document.getElementById("loading_msg").style.display = '';
    			var poststr = "account_id_local="+account_id_local1+
                      "&id=2" +
                      "&vehicleserial=" + encodeURI( device_str ) +
                      "&start_date=" + encodeURI( document.getElementById("date1").value )+
                      "&end_date=" + encodeURI( document.getElementById("date2").value )+
                      "&radio_value=" +radio_value;
    		}
      }  
    } 	
    //alert("poststr="+poststr);              
    makePOSTRequest('src/php/action_report_datalog.htm', poststr);
  }  
   
 function action_report_halt(obj)
  {
    document.getElementById("reportPrevPage").innerHTML='';
    var area_defined = document.getElementById("area_defined").value;
    var geo_id ="";
    
    if(area_defined == 1)
    {
      var geo_obj = obj.elements['halt_geo_area[]'];
      
      //alert("geo_obj:"+geo_obj.length);
      
      if(geo_obj.length!=undefined)
      {
        //alert("len:"+geo_obj.length)
        var counter = 0;
        for(var i=0;i<geo_obj.length;i++)
        {
          if(geo_obj[i].checked==true)
          {
            //alert("counter="+counter);
            if(counter==0)
            {              
              geo_id = geo_id+""+geo_obj[i].value;
            }
            else
            {
              geo_id = geo_id+":"+geo_obj[i].value;
            }
            counter++;
          }
        }
        //alert("final_str:"+geo_id)
      }
      else
      {
        if(geo_obj.checked==true)
        {        
          geo_id = geo_obj.value;
          //alert("geo_id1:"+geo_id);
        } 
        else
        {
          //alert("geo_id2:"+geo_id);
        }
      }
    }    
    //alert("area_defined:"+area_defined+" ,geo_id="+geo_id);     
    //document.getElementById("loading_msg").style.display = '';    
    var device_str_iotype = get_iotypeimei_selected(obj);
	//alert("device_str_iotype="+device_str_iotype);
  	
    if(device_str_iotype==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    
    if(device_str_iotype!=false)
    {    
        if(area_defined == 1)
        {
            //document.thisform.vehicleserial_prev.value=device_str_iotype;
            //document.thisform.geo_id.value=geo_id;
            document.getElementById("vehicleserial_prev").value=device_str_iotype;
            document.getElementById("geo_id").value=geo_id;
        }
        else
        {
            document.getElementById("vehicleserial_prev").value=device_str_iotype;
            //document.thisform.vehicleserial_prev.value=device_str_iotype;
        }   
		document.thisform.submit();
    } 
  } 

//1.DISTANCE REPORT  
  function action_report_track_interval(obj)
  {
	
	var time_interval=document.getElementById("user_interval").value;
	var startdateDoc=document.getElementById("date1").value;		
	var enddateDoc=document.getElementById("date2").value;
	var startdate = startdateDoc.replace('/', '-');
	startdate = startdate.replace('/', '-');
	var enddate = enddateDoc.replace('/', '-');
	enddate = enddate.replace('/', '-');
	var display_mode="2";   /////// 1=last_postoin  and 2=track 		
	var imeino1;
	var vid;
	var text_report_io_element="";
	vid = "";
	document.getElementById("loading_msg").style.display = '';  
    var device_str_iotype = get_iotypeimei_selected_radio(obj);
	//alert(device_str_iotype);
	
	var vid_local=(device_str_iotype).split("#");
	
    vid=vid_local[0];
	if(vid!="")
  	{
		var date = new Date();
		var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
		var data_with_location="1";
		var text_report_io_element=vid_local[1];
		var mode="2";
		
		document.getElementById("xml_file").value=dest;
		document.getElementById("vserial").value=vid;
		document.getElementById("startdate").value=startdate;
		document.getElementById("enddate").value=enddate;
		document.getElementById("text_report_io_element").value=text_report_io_element;
		document.getElementById("mode").value=mode;
		document.getElementById("time_interval").value=time_interval;
		document.getElementById("dwt").value=data_with_location;
		//document.getElementById("").value=document.getElementById("category").value;
  		document.trackIntForm.submit();
  	}
	
  }  
  /*function action_report_halt(obj)
  {
    var area_defined = document.getElementById("area_defined").value;
    var geo_id ="";
    
    if(area_defined == 1)
    {
      var geo_obj = obj.elements['halt_geo_area[]'];
      
      //alert("geo_obj:"+geo_obj.length);
      
      if(geo_obj.length!=undefined)
      {
        //alert("len:"+geo_obj.length)
        var counter = 0;
        for(var i=0;i<geo_obj.length;i++)
        {
          if(geo_obj[i].checked==true)
          {
            //alert("counter="+counter);
            if(counter==0)
            {              
              geo_id = geo_id+""+geo_obj[i].value;
            }
            else
            {
              geo_id = geo_id+":"+geo_obj[i].value;
            }
            counter++;
          }
        }
        //alert("final_str:"+geo_id)
      }
      else
      {
        if(geo_obj.checked==true)
        {        
          geo_id = geo_obj.value;
          //alert("geo_id1:"+geo_id);
        } 
        else
        {
          //alert("geo_id2:"+geo_id);
        }
      }
    }
    
    //alert("area_defined:"+area_defined+" ,geo_id="+geo_id); 
    
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    
    if(device_str!=false)
    {    
        if(area_defined == 1)
        {
          var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+
                  "&geo_id=" + encodeURI( geo_id )+                    
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );
        }
        else
        {        
          var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );
        }                                    
                //alert("riz:"+poststr); 
    }    
    //alert("postrstr:"+poststr);               
    makePOSTRequest('src/php/action_report_halt.htm', poststr);
  }*/
  
  function action_report_sector_halt(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    
    if(device_str!=false)
    {          
      var poststr = "vehicleserial=" + encodeURI( device_str ) +
      "&start_date=" + encodeURI( document.getElementById("date1").value )+
      "&end_date=" + encodeURI( document.getElementById("date2").value )+  
      "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                                   
    }    
    //alert("postrstr:"+poststr);               
    makePOSTRequest('src/php/action_report_sector_halt.htm', poststr);
  }  
  
  function action_report_sector_change(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    //var device_str = get_selected_vehicle_datalog(obj);
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    
    if(device_str!=false)
    {          
      var poststr = "vehicleserial=" + encodeURI( device_str ) +
      "&start_date=" + encodeURI( document.getElementById("date1").value )+
      "&end_date=" + encodeURI( document.getElementById("date2").value );  
      //"&user_interval=" + encodeURI( document.getElementById("user_interval").value );                                   
    }    
    //alert("postrstr:"+poststr);               
    makePOSTRequest('src/php/action_report_sector_change.htm', poststr);
  }      
    
  function action_graph_sector(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    //var device_str = get_selected_vehicle_datalog(obj);
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    
    if(device_str!=false)
    {          
      var poststr = "vehicleserial=" + encodeURI( device_str ) +
      "&start_date=" + encodeURI( document.getElementById("date1").value )+
      "&end_date=" + encodeURI( document.getElementById("date2").value );  
      //"&user_interval=" + encodeURI( document.getElementById("user_interval").value );                                   
    }    
    //alert("postrstr:"+poststr);               
    makePOSTRequest('src/php/action_graph_sector.htm', poststr);
  }      
   
function action_report_travel(obj)
{
    document.getElementById("reportPrevPage").innerHTML='';
	//document.getElementById("loading_msg").style.display = '';    
	var device_str = get_selected_vehicle(obj);
	//alert("deviceStr="+device_str);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	else
	{
		document.thisform.vehicleserial_prev.value=device_str;
		document.thisform.submit();
	}	
} 
 /*function action_report_travel(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    if(device_str!=false)
    {    
        var poststr = "vehicleserial=" + encodeURI( device_str ) +
                "&start_date=" + encodeURI( document.getElementById("date1").value )+
                "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                "&threshold=" + encodeURI( document.getElementById("threshold").value );                    
                //alert("riz:"+poststr); 
    }                   
    makePOSTRequest('src/php/action_report_travel.htm', poststr);
  }*/
  
  
  function action_report_travel_summary(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj);
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}	
    if(device_str!=false)
    {    
        var poststr = "vehicleserial=" + encodeURI( device_str ) +
                "&start_date=" + encodeURI( document.getElementById("date1").value )+
                "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                "&threshold=" + encodeURI( document.getElementById("threshold").value );                    
                //alert("riz:"+poststr); 
    }                   
    makePOSTRequest('src/php/action_report_travel_summary.htm', poststr);
  }              
  
  
  // DATA GAP
  function action_report_datagap(obj)
  {
	//alert("in gps gap");
    if( (document.thisform.no_gps_interval.value == "select") || (document.thisform.no_data_interval.value == "select") )
    {
      alert("Please select both interval");
      return false;
    }
   //alert("in gps gap 1");
    document.getElementById("loading_msg").style.display = '';   
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{	
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+
                  "&no_gps_interval=" + encodeURI( document.getElementById("no_gps_interval").value )+
                  "&no_data_interval=" + encodeURI( document.getElementById("no_data_interval").value );	
	}                   
    makePOSTRequest('src/php/action_report_datagap.htm', poststr);
  } 
  //  Report vehicle  
  function create_pdf()
	{
		 document.forms[0].target = "_blank";
		 document.forms[0].action="getpdf3.htm";
	}  
  

function getScriptPage(div_id,content_id)
{
	subject_id = div_id;
	content = document.getElementById(content_id).value;
	var poststr = "content=" +content+
				"&local_account_id="+document.getElementById("local_account_id").value;
				//alert("poststr="+poststr);
    makePOSTRequest('src/php/datalog_script_search.htm', poststr);
	if(content.length>0)
		box('1');
	else
		box('0');				
} 

function highlight(action,id)
{
	//alert('action='+action+"id="+id);
	if(action)	
	document.getElementById('word'+id).bgColor = "#C2B8F5";
	else
	document.getElementById('word'+id).bgColor = "#F8F8F8";
}

function display(word)
{
	var word=word.split(",");
	//alert("word="+word);
	var vehicle_name= word[0];
	var device_imei_no= word[1];
//	alert("vehicle_name="+vehicle_name+"device_imei_no="+device_imei_no);
	document.getElementById('text_content').value = vehicle_name;
	document.getElementById('device_imei_no').value = device_imei_no;
	document.getElementById('box').style.display = 'none';
	document.getElementById('text_content').focus();
}

function box(act)
{
	//alert('hello3');
  if(act=='0')	
  {
	document.getElementById('box').style.display = 'none';
  }
  else 
	document.getElementById('box').style.display = 'block';	 
}	

function display1()
{
	document.getElementById('box').style.display = 'none';
} 
  
  
    //1.AREA VIOLATION ALERT REPORT 
    
     function action_alert_monthly_distance_geofence(obj)
  { 
		var option_choices=0;
		var numtype = 0;
		var i = 0;
		
		numtype=0;
    if(obj.day_opt.value == "2")
		{
      //alert("2");
      var s = obj.elements['days[]'];
  		for(i=0;i<s.length;i++)
  		{
  			if(s[i].checked)
  				numtype = 1;
  		}
  		if(numtype==0)
  		{
  			alert("Please Select At Least One Day");
  			return false;
  		}	
  	}      
    
    var rec = obj.vehicleserial;
    var device_str = radio_selection(rec); 	 
    //var device_str = get_selected_vehicle(obj); 
   
    if(device_str!=false)
    {
    	var day_opt1=document.getElementById("day_opt").value;		
    	if(day_opt1=="1")
    	{
    		document.getElementById("loading_msg").style.display = '';  
    		var poststr = "vehicleserial=" + encodeURI( device_str ) +					
    					  "&month=" + encodeURI( document.getElementById("month").value )+
    					  "&year=" + encodeURI( document.getElementById("year").value )+
    					  "&days=" + encodeURI( day_str )+
    					  "&day_opt=" + encodeURI( document.getElementById("day_opt").value ); 			
    	}
    	else
    	{
    		var day_str = get_selected_day(obj);
    		if(day_str!=false)
    		{
    			document.getElementById("loading_msg").style.display = '';  
    			var poststr = "vehicleserial=" + encodeURI( device_str ) +							
    						  "&month=" + encodeURI( document.getElementById("month").value )+
    						  "&year=" + encodeURI( document.getElementById("year").value )+
    						  "&days=" + encodeURI( day_str )+
    						  "&day_opt=" + encodeURI( document.getElementById("day_opt").value );  
    		}
    	}
    }     					
    //alert("riz:"+poststr);                
    makePOSTRequest('src/php/action_alert_monthly_distance_geofence.htm', poststr);
  } 
  
  function action_alert_area_violation(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value ); 
	}                   
    makePOSTRequest('src/php/action_alert_area_violation.htm', poststr);
  }        
function action_alert_polyline_route_violation(obj)
  {
	
    
    // GET SELECTED VEHICLES    
    var startDate1 = document.getElementById("date1").value;
    var endDate1 = document.getElementById("date2").value;
    
    var sd = startDate1.split(" ");
    var ed = endDate1.split(" "); 
    
    var d1 = new Date(sd[0]);
    var m1 = d1.getTime(); 
    
    var d2 = new Date(ed[0]);
    var m2 = d2.getTime(); 	
    
    var mdiff = m2 - m1;               //five Days difference    =432000000 , 30 days diff= 2592000000  (eg.30*60*60*24*1000)
    
    //alert("mdiff="+mdiff); 
	
    //if(mdiff > 432000000)
    if(mdiff > 2592000000)
    {
      alert("Maximum 30 days report is allowed at once");
      return false;
    }
    
    document.getElementById("loading_msg").style.display = '';  
    
    //var device_str = get_selected_vehicle(obj); 
    var rec = obj.vehicleserial;
    var device_str = radio_selection(rec);    
    
  	if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
  	{    
      var poststr = "vehicleserial=" + encodeURI( device_str ) +					
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                    "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                  
                   // alert("riz:"+poststr);  
  	}                     
    //makePOSTRequest('src/php/action_alert_polyline_route_violation.htm', poststr);
	
	
			
	 //alert(poststr);
	$.ajax({
	type: "POST",
	url:'src/php/action_alert_polyline_route_violation.php',
	data: poststr,
	success: function(response){
		//console.log(response);
		//alert("response="+response);		
		 document.getElementById('reportPrevPage').style.display="none";
                document.getElementById('rightMenu').style.display="";
                document.getElementById('bodyspan').style.display="";
		$("#bodyspan").html(response);
		//document.getElementById('loading_pending_tanker').innerHTML="";	
	},
	error: function()
	{
		alert('An unexpected error has occurred! Please try later.');
	}
	});
	
	
  }
  function action_alert_vehicle_reverse(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var device_str = get_selected_vehicle(obj); 
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value ); 
	}
	makePOSTRequest('src/php/action_alert_vehicle_reverse.htm', poststr);
  }          
  function report_show_station_mode(value)
  {  
    if(value==1)
    {
      document.getElementById("automatic").style.display="";
      document.getElementById("manual").style.display="none";
    }
    else if(value ==2)
    {
      document.getElementById("manual").style.display="";
      document.getElementById("automatic").style.display="none";
    }    
  }
  
   function action_report_station_upload(action_type)
   {
      if(action_type=="add")  
      {
        //var poststr = "action_type="+encodeURI(action_type ) + 
        //"&local_account_ids="+encodeURI(result);
         //alert("before upload"+document.getElementById('file_upload_form'));          
         
      	 document.getElementById('file_upload_form').action_type.value = action_type;
      	 //document.getElementById('file_upload_form').local_account_ids.value = result;
      	                        
         document.getElementById('file_upload_form').onsubmit=function() {
      	 document.getElementById('file_upload_form').target = '_blank'; //'upload_target' is the name of the iframe
      	 }
         
         document.getElementById('file_upload_form').submit();              
      }
   }  
  
function action_report_station_halt(obj)
	{		
		//alert("Station Halt Report..wait");
    document.getElementById("loading_msg").style.display = '';
		var vehicle_str = halt_checkbox_selection(obj.elements['station_vehicle_id[]']);
		var station_str = halt_checkbox_selection(obj.elements['stationids[]']);	
    //alert("station_str"+station_str);		
		if(station_str==false && vehicle_str==false)
		{
			document.getElementById("loading_msg").style.display='none';
		}	
		if(vehicle_str!=false && station_str!=false)
		{
			var poststr = "vehicle_str=" +vehicle_str+
						"&station_str="+station_str+
						"&start_date=" + encodeURI( document.getElementById("date1").value )+
						"&end_date=" + encodeURI( document.getElementById("date2").value ); 
		}                  
		//alert(poststr);
    makePOSTRequest('src/php/action_report_station_halt.htm', poststr);
	}
 function action_report_hourly_distance(obj)
  {
	//alert("test");
	var todayDate=getTodayDate();
	var enterDate=document.getElementById("date1").value;
	//alert('date1='+enterDate+'date2='+todayDate);
	if(enterDate==todayDate)
	{
		//alert("in if");	
		//alert("todayDate="+todayDate+"enterDate="+enterDate);
		document.getElementById("loading_msg").style.display = '';
		var device_str = get_selected_vehicle(obj); 
		if(device_str==false)
		{
			document.getElementById("loading_msg").style.display='none';
			return false;
		}
		document.getElementById("loading_msg").style.display='none';
		document.getElementById("deviceStr").value=device_str;
		document.hourlyDistance.action="src/php/action_report_hourly_distance.php";
		document.hourlyDistance.target="_blank";
		document.hourlyDistance.submit();
	}
	else
	{		    
		
		//alert("todayDate="+todayDate);
		if(enterDate>=todayDate)
		{
			alert("Please Enter Previous Day.");
			document.getElementById("loading_msg").style.display='none';
			return false;
		}
		
		//alert("todayDate="+todayDate+"enterDate="+enterDate);
		document.getElementById("loading_msg").style.display = '';
		var device_str = get_selected_vehicle(obj); 
		if(device_str==false)
		{
			document.getElementById("loading_msg").style.display='none';
			return false;
		}
		document.getElementById("loading_msg").style.display='none';
		document.getElementById("deviceStr").value=device_str;
		document.hourlyDistance.action="src/php/action_report_hourly_distance_prev_date.php";
		document.hourlyDistance.target="_blank";
		document.hourlyDistance.submit();
	}
	
   /* if(device_str!=false)
    {
		//alert(device_str);
		document.getElementById("deviceStr").value=device_str;
	   //document.sumForm.action = target_file;    
      document.hourlyDistance.submit();
        /*var poststr = "vehicleserial=" + encodeURI( device_str ) +
                "&selected_account_id=" + document.getElementById("selected_account_id").value +
                "&selected_options_value=" + document.getElementById("selected_options_value").value +
                "&s_vehicle_display_option=" + document.getElementById("s_vehicle_display_option").value +
                "&start_date=" + encodeURI(enterDate);                
             //alert("Shams:"+poststr);  
         makePOSTRequest('src/php/action_report_hourly_distance.htm', poststr);*/
   // }               
  }
  
  function getTodayDate()
  {
        var currentTime = new Date();
        var month = currentTime.getMonth() + 1;
        var day = currentTime.getDate();
        var year = currentTime.getFullYear();
        if(month<10)
        {
			month="0" + month;
		}		
		if(day<10)
		{
			day="0" +day ;
		}		
		var dateOnly=year + "/" + month + "/" +day ;        
        return dateOnly;
  }
  function setHourlyReportType(thisValue)
  {
	if(thisValue=="current_date")
	{
		document.getElementById("date1").value="";
		document.getElementById("dateOption").style.display="none";
	}
	else if(thisValue=="previous_date")
	{
		document.getElementById("date1").value="";
		document.getElementById("dateOption").style.display="";
	}
  }
function action_report_station_halt_1(obj)
	{		
		//alert("Station Halt Report..wait");
    document.getElementById("loading_msg").style.display = '';
		var vehicle_str = halt_checkbox_selection(obj.elements['station_vehicle_id[]']);
		var station_str = halt_checkbox_selection(obj.elements['stationids[]']);	
    //alert("station_str"+station_str);		
		if(station_str==false && vehicle_str==false)
		{
			document.getElementById("loading_msg").style.display='none';
		}	
		if(vehicle_str!=false && station_str!=false)
		{
			var poststr = "vehicle_str=" +vehicle_str+
			"&station_str="+station_str+
			"&start_date=" + encodeURI( document.getElementById("date1").value )+
			"&end_date=" + encodeURI( document.getElementById("date2").value ); 
		}                  
		//alert(poststr);
    makePOSTRequest('src/php/action_report_station_halt_1.htm', poststr);
	} 	
  
  //2.SPEED VIOLATION ALERT REPORT  
  
  function action_alert_speed_violation(obj)
  {
    document.getElementById("loading_msg").style.display = '';   
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value ); 
	}                   
    makePOSTRequest('src/php/action_alert_speed_violation.htm', poststr);
  }
  
  //1.SPEED GRAPH  
  
  function action_graph_daily_speed(obj)
  {
    if(document.getElementById("vehicle_id_local").value=="select")
    {
        alert("Please select vehicle");
        return false;
    }
    else
    {
      document.getElementById("loading_msg").style.display = '';    
      var poststr = "vehicle_id_local=" + encodeURI( document.getElementById("vehicle_id_local").value ) +
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value ); 
	//alert("poststr="+poststr);
      makePOSTRequest('src/php/action_graph_daily_speed.htm', poststr);
    }
  }  

  function action_graph_temperature(obj)
  {
    if(document.getElementById("vehicle_id_local").value=="select")
    {
        alert("Please select vehicle");
        return false;
    }
    else
    {
      document.getElementById("loading_msg").style.display = '';    
      var poststr = "vehicle_id_local=" + encodeURI( document.getElementById("vehicle_id_local").value ) +
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value ); 
      //alert("poststr="+poststr);
      makePOSTRequest('src/php/action_graph_temperature.htm', poststr);
    }
  }
  
  //2.DISTANCE GRAPH 
  
  function action_graph_daily_distance(obj)
  {
    document.getElementById("loading_msg").style.display = '';    
    var poststr = "vehicleserial=" + encodeURI( document.getElementById("vehicleserial").value ) +
                  "&day=" + encodeURI( document.getElementById("day").value )+
                  "&month=" + encodeURI( document.getElementById("month").value )+
                  "&year=" + encodeURI( document.getElementById("year").value );                                                                                         
                   
    makePOSTRequest('src/php/action_graph_daily_distance.htm', poststr);
  }        
  
  //3.FUEL GRAPH  
  
  function action_graph_daily_fuel(obj)
  {
    if(document.getElementById("vehicle_id_local").value=="select")
    {
        alert("Please select vehicle");
        return false;
    }
    else
    {
      document.getElementById("loading_msg").style.display = '';     
      var poststr = "vehicle_id_local=" + encodeURI( document.getElementById("vehicle_id_local").value ) +
                    "&start_date=" + encodeURI( document.getElementById("date1").value )+
                    "&end_date=" + encodeURI( document.getElementById("date2").value );
      //alert("poststr="+poststr);
      makePOSTRequest('src/php/action_graph_daily_fuel.htm', poststr);
    }
  }        
    
  //  Report vehicle
  
  function action_report_suppv(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj); 
  	
    if(device_str==false)
  	{
  		document.getElementById("loading_msg").style.display='none';
  	}
	
    if(device_str!=false)
	   {
        var poststr = "vehicleserial=" + encodeURI( device_str ) +					
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                  
                  //alert("riz:"+poststr);
	  }
     makePOSTRequest('src/php/action_report_battery_voltage.htm', poststr); 
  }          
  
  function create_pdf()
	{
		 document.forms[0].target = "_blank";
		 document.forms[0].action="getpdf3.htm";
	}

	/*function print_pdf()
	{
		 document.forms[0].target = "_blank";
		 document.forms[0].action="getpdf3.htm?print_pdf=1";
	}*/

	function mail_report()
	{
		document.forms[0].target = "_self";
		document.forms[0].action="mail_vehicle_report.htm";
	}
		
	// TRIP REPORT	

	function validate_form(obj) 
	{
		var option_choices=0;
		var numtype = 0;
		var i = 0;
		var s = obj.elements['vehicleid[]'];
		//alert(s.length);

		for(i=0;i<s.length;i++)
		{
			if(s[i].checked)
				numtype = 1;
		}
		if(numtype==0)
		{
			alert("Please Select At Least One Vehicle");
			return false;
		}
		if(obj.StartDate.value=="")
		{
			alert("Please Enter Start Date");
			obj.StartDate.focus();
			return false;
		}
		if(obj.EndDate.value=="")
		{
			alert("Please Enter End Date");
			obj.EndDate.focus();
			return false;
		}
	}
  
	function updateFields(obj,actionType)
	{
		//alert("Rizwan:1"+obj);	
		if(obj.selectall.checked)
		{
			obj.option1.checked="true";
			obj.option2.checked="true";
			if(actionType=="Vehicle")
			{
				obj.option3.checked="true";
			}
			obj.option4.checked="true";
			obj.option5.checked="true";
			obj.option6.checked="true";	
			obj.option7.checked="true";
			//obj.option8.checked="true";				
		}
		else if(obj.selectall.checked==false)
		{
			obj.option1.checked=false;
			obj.option2.checked=false;
			if(actionType=="Vehicle")
			{
				obj.option3.checked="true";
			}
			obj.option4.checked=false;
			obj.option5.checked=false;
			obj.option6.checked=false;	
			obj.option7.checked=false;
			//obj.option8.checked=false;			
		} 
	} 
	
 	
  function Alln(obj)
  {
  	//alert("K");
  	if(obj.all_1.checked)
  	{
  		var i;
  		var s = obj.elements['vehiclename[]'];
  		//alert(s.length);
  
  		for(i=0;i<s.length;i++)
  			s[i].checked=true;
  		
  	}
  	if(!obj.all_1.checked)
  	{
  		var i;
  		var s = obj.elements['vehiclename[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked=false;
  	}
  }
  
  function AllGeo(obj)
  {
  	//alert("K:"+obj);
  	//alert("c1"+obj.all_geo.checked);  	
  	if(obj.all_geo.checked)
  	{
  		var i;
  		var s = obj.elements['halt_geo_area[]'];
  		//alert("s1="+s);
  
  		if(s.length!=undefined)
  		{
        for(i=0;i<s.length;i++)
    			s[i].checked=true;
  		}
  		else
  		{
  		  s.checked=true;
      }  		
  	}
  	
  	if(!obj.all_geo.checked)
  	{  		
      var i;
  		var s = obj.elements['halt_geo_area[]'];
  		//alert("s2="+s);
  		
  		if(s.length!=undefined)
  		{      
        for(i=0;i<s.length;i++)
    			s[i].checked=false;
    	}
    	else
    	{
    	  s.checked=false;
      }
  	}
  }  
  
  function validate(obj)
  {  	
  	//alert("K");  
  	var s = obj.elements['vehiclename[]'];
  	//alert(s.length);
  
  	var flag = false;
  
  	for(var i=0;i<s.length;i++)
  	{
  		if(s[i].checked)
  		{
  			flag = true;
  		}
  	}
  
  	if(!flag)
  	{
  		alert("Please select vehicle first");
  		return false;
  	}
  	else
  	{
  		//alert("K");
  		document.forms[0].submit();
  	}
  }
  /// REPORT DATA LOG DATE
  
  
  /// REPORT DATALOG VEHICLE
  
  function Alln(obj)
  {
  		if(obj.all_1.checked)
  		{
  			var i;
  			var s = obj.elements['vehiclename[]'];
  			for(i=0;i<s.length;i++)
  				s[i].checked=true;
  			
  		}
  		if(!obj.all_1.checked)
  		{
  			var i;
  			var s = obj.elements['vehiclename[]'];
  			for(i=0;i<s.length;i++)
  				s[i].checked=false;
  		}  
  }
  function Alls(obj)
  {
  		if(obj.all_2.checked)
  		{
  			var i;
  			var s = obj.elements['vehicleserial[]'];
  			for(i=0;i<s.length;i++)
  				s[i].checked=true;
  			
  		}
  		if(!obj.all_2.checked)
  		{
  			var i;
  			var s = obj.elements['vehicleserial[]'];
  			for(i=0;i<s.length;i++)
  				s[i].checked=false;
  		} 
  }
  function Allp(obj)
  {
  		if(obj.all_3.checked)
  		{
  			var i;
  			var s = obj.elements['phone[]'];
  			for(i=0;i<s.length;i++)
  				s[i].checked=true;
  			
  		}
  		if(!obj.all_3.checked)
  		{
  			var i;
  			var s = obj.elements['phone[]'];
  			for(i=0;i<s.length;i++)
  				s[i].checked=false;
  		}  
  }
       
  function opt1()
  {	
    	//document.forms[0].vehiclename.disabled=false;
    	//document.forms[0].vehicleserial.disabled=true;
    	document.forms[0].all_1.disabled=false;
    	var s1 =document.forms[0].elements['vehiclename[]'];
      for(i=0;i<s1.length;i++)
      {
        s1[i].disabled=false;
        //s[i].checked=false;
      }
    	document.forms[0].all_2.disabled=true;
    	document.forms[0].all_2.checked=false;
    	var s2 =document.forms[0].elements['vehicleserial[]'];
      for(i=0;i<s2.length;i++)
      {
        s2[i].disabled=true;
        s2[i].checked=false;
      }
    	document.forms[0].all_3.disabled=true;
    	document.forms[0].all_3.checked=false;
    	var s3 =document.forms[0].elements['phone[]'];
      for(i=0;i<s3.length;i++)
      {
        s3[i].disabled=true;
        s3[i].checked=false;
      }
    	//document.forms[0].userid.disabled=true;
    	//document.forms[0].phone.disabled=true;
   }
    
   function opt2()
   {
    	document.forms[0].all_1.disabled=true;
    	document.forms[0].all_1.checked=false;
    	var s1 =document.forms[0].elements['vehiclename[]'];
      for(i=0;i<s1.length;i++)
      {
        s1[i].disabled=true;
        s1[i].checked=false;
      }
    	document.forms[0].all_2.disabled=false;
    	var s2 =document.forms[0].elements['vehicleserial[]'];
			for(i=0;i<s2.length;i++)
				s2[i].disabled=false;
    	document.forms[0].all_3.disabled=true;
    	document.forms[0].all_3.checked=false;
    	var s3 =document.forms[0].elements['phone[]'];
      for(i=0;i<s3.length;i++)
      {
        s3[i].disabled=true;
        s3[i].checked=false;
      }    	
    }    
    /*function opt3()
    {	
    	document.forms[0].vehiclename.disabled=true;
    	document.forms[0].vehicleserial.disabled=true;
    	document.forms[0].userid.disabled=false;
    	document.forms[0].phone.disabled=true;
    }*/
    
    function opt4()
    {	
    	document.forms[0].all_1.disabled=true;
    	document.forms[0].all_1.checked=false;
    	var s1 =document.forms[0].elements['vehiclename[]'];				
      for(i=0;i<s1.length;i++)
      {
        s1[i].disabled=true;
        s1[i].checked=false;
      }
    	document.forms[0].all_2.disabled=true;
    	document.forms[0].all_2.checked=false;
    	var s2 =document.forms[0].elements['vehicleserial[]'];
      for(i=0;i<s2.length;i++)
      {
        s2[i].disabled=true;
        s2[i].checked=false;
      }
    	document.forms[0].all_3.disabled=false;
    	var s3 =document.forms[0].elements['phone[]'];
			for(i=0;i<s3.length;i++)
				s3[i].disabled=false;
    	//document.forms[0].vehiclename.disabled=true;
    	//document.forms[0].vehicleserial.disabled=true;
    	//document.forms[0].userid.disabled=true;
    	//document.forms[0].phone.disabled=false;
    }    
    // REPORT DATALOG VEHICLE CLOSED  
    
    // ACTION DATALOG TODAY
    
	function waitPreloadPage() 
	{ 
		if(document.getElementById)
		{
		document.getElementById('prepage').style.visibility='hidden';
		document.getElementById('prepage1').style.visibility='visible';
		}
		else
		{
			if (document.layers)
			{ 
			document.prepage.visibility = 'hidden';
			document.getElementById('prepage1').style.visibility='visible';
			}
			else
			{ 
			document.all.prepage.style.visibility = 'hidden';
			document.getElementById('prepage1').style.visibility='visible';
			}
		}
	}
	
	function MapWindow_Report(vname,arr_datetime,dept_datetime,lat,lng)
	{
		//alert("k");
		test();
    //alert(vname+" "+datetime+" "+lat+" "+lng);	
		//test2(vname,datetime,lat,lng);			
		document.getElementById("window").style.display = '';
		//alert("vname="+vname);
    //alert("arr_datetime="+arr_datetime);
		//alert("dept_datetime="+dept_datetime);
		//alert("lat="+lat);
		//alert("lng="+lng);
    load_vehicle_on_map(vname,arr_datetime,dept_datetime,lat,lng);							
	}	
 
  //////////////////////////////////
  function select_all_vehicle(obj)
  {
  	if(obj.all.checked)
  	{
  		var i;
  		var s = obj.elements['vehicleserial[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked="true";		
  	}
  	else if(obj.all.checked==false)
  	{
  		var i;
  		var s = obj.elements['vehicleserial[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked=false;  		
  	}
  } 

/*function common_select_all(obj,all,name)
  {
	alert("all="+all+"name="+name);
  	if(obj.all.checked)
  	{
  		var i;
  		var s = obj.elements[name];
  		for(i=0;i<s.length;i++)
  			s[i].checked="true";		
  	}
  	else if(obj.all.checked==false)
  	{
  		var i;
  		var s = obj.elements[name];
  		for(i=0;i<s.length;i++)
  			s[i].checked=false;  		
  	}
  }*/
function common_select_all(doc_obj,matching_element,checked_element1)
{
	//alert("test");
	if(document.getElementById(matching_element).checked==true)
	{
		var checked_element=doc_obj.elements[checked_element1];
		if(checked_element.length!=undefined)
		{
			for(var i=0;i<checked_element.length;i++)
			{
				checked_element[i].checked=true;
			}
		}
		else
		{
			checked_element.checked=true;
		}
	}
	else if(document.getElementById(matching_element).checked==false)
	{
		var checked_element=doc_obj.elements[checked_element1];
		if(checked_element.length!=undefined)
		{
			for(var i=0;i<checked_element.length;i++)
			{
				checked_element[i].checked=false;
			}
		}
		else
		{
			checked_element.checked=false;
		}		
	}
}  

  function select_all_option(obj)
  {
  	if(obj.all.checked)
  	{
  		var i;
  		var s = obj.elements['manage_option[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked="true";		
  	}
  	else if(obj.all.checked==false)
  	{
  		var i;
  		var s = obj.elements['manage_option[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked=false;  		
  	}
  }  
  /////////////////////////////////  


/////////////////////////////////   
  
    //1.BUS REPORT  
  
  function action_report_bus(obj)
  {
    // GET SELECTED VEHICLES 
    document.getElementById("msg").style.display = '';
    var device_str = get_selected_vehicle(obj);    
    //alert(device_str);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&school_id=" + encodeURI( document.getElementById("account_id_hidden").value )+
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );//+  
                  //"&user_interval=" + encodeURI( document.getElementById("user_interval").value );                
                  //alert("Rizwan:"+poststr); 
	}                   
    makePOSTRequest('src/php/action_report_bus.htm', poststr);
  } 
  //////////////////////////////////////////////////////
  
  //2.STUDENT REPORT 
  function action_report_student(obj)
  {
    // GET SELECTED STUDENT 
    document.getElementById("msg").style.display = '';
    var student_str = get_selected_student(obj);    
    //alert(student_str);
    var poststr = "studentserials=" + encodeURI( student_str ) +
                  "&school_id=" + encodeURI( document.getElementById("account_id_hidden").value )+
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );//+  
                  //"&user_interval=" + encodeURI( document.getElementById("user_interval").value );                
                  //alert("Rizwan:"+poststr);                                                                                              
                   
    makePOSTRequest('src/php/action_report_student.htm', poststr);
  }
  
  // GET SELECTED STUDENT
  function get_selected_student(obj)
	{
    var i;
		var s = obj.elements['studentserial[]'];	
    //alert(s);
		var student_str="";
		if(s.length!=undefined)
		{
      for(i=0;i<s.length-1;i++)
  		{             
        if(s[i].checked)
        {
          if(student_str=="")
          {
            student_str = s[i].value; 
          }
          else
          {
            student_str = student_str+":"+s[i].value;        
          }        
        }  
      }
    }	
    else
    {   //alert(s);	
          student_str = s.value;
    }
    return student_str;		
  }   
  	//---------------------------------------- get section ---------------------
	function  get_section(action_type)
	{
        
      //alert(action_type);
      var classname=document.getElementById("classname").value;
            
      if(classname=="select")
        {
          remOption(document.getElementById("section"));
  		    addOption(document.getElementById("section"),'Select','select'); 
          alert("Please Select Class"); 
          document.getElementById("classname").focus();
          return false;
        }
          
    
    var account_id=document.getElementById("account_id_hidden").value;
    //alert(account_id);
    var poststr="action_type="+encodeURI(action_type ) + 
					"&classname="+classname +
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_student.htm', poststr);
  } 
  function  show_student_classwise(action_type)
	{
        
      //alert(action_type);
      var classname=document.getElementById("student_classwise_id").value;
       if(classname=="select")
        {
          alert("Please Select Class"); 
          document.getElementById("student_classwise_id").focus();
          return false;
        }
       var account_id=document.getElementById("account_id_hidden").value;
       //alert(account_id);
      //alert(classname);
    
    var poststr="action_type="+encodeURI(action_type ) + 
					"&classname="+classname +
          "&account_id="+account_id;							
		   
    makePOSTRequest('src/php/report_student.htm', poststr); 
  } 
  function  show_student(action_type)
	{
        
      //alert(action_type);
      var classname=document.getElementById("classname").value;
      var section=document.getElementById("section").value;
            
      if(classname=="select")
        {
          remOption(document.getElementById("section"));
  		    addOption(document.getElementById("section"),'Select','select'); 
          alert("Please Select Class"); 
          document.getElementById("classname").focus();
          return false;
        }
        if(section=="select")
        {           
          alert("Please Select Section"); 
          document.getElementById("section").focus();
          return false;
        }
          
    
    var account_id=document.getElementById("account_id_hidden").value;
    //alert(account_id);
    var poststr="action_type="+encodeURI(action_type ) + 
					"&classname="+classname +
					"&section="+section +
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/report_student.htm', poststr);
  } 
  
  function select_all_students(obj)
  {
  	
    if(obj.all.checked)
  	{
  		var i;
  		var s = obj.elements['studentserial[]'];
  		
  		if(s.length!=undefined)
  		{
  		  for(i=0;i<s.length;i++)
  			 s[i].checked="true";	
       }
       else{
       	 s.checked="true";	
       }	
  	}
  	else if(obj.all.checked==false)
  	{
  		var i;
  		var s = obj.elements['studentserial[]'];
  		if(s.length!=undefined)
  		{
      for(i=0;i<s.length;i++)
  			s[i].checked=false;
       }
      else{
          s.checked=false;
      }  		
  	}
  }    

function checkbox_selection(obj)
{
	var flag=0;
		var cnt=0;
	var id="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{
					id= id + obj[i].value;
					cnt=1;
				}
				else
				{
					id=id +","+ obj[i].value;
				}
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
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}
}

function checkbox_selection_datalog(obj1)
{
	var flag=0;
		var cnt=0;
	var id="";
	var cnt_1=0;
	var datalog_recordsnumradio=obj1.rec;
	for (var i=0;i<datalog_recordsnumradio.length;i++)
	{
		if(datalog_recordsnumradio[i].checked==true)
		{
			var datalog_recordsnumradio_value=datalog_recordsnumradio[i].value;
		}
	}
	var obj=obj1.elements['vehicleserial[]'];
	if(obj.length!=undefined)
	{		
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{
					id= id + obj[i].value;
					cnt=1;
					if(datalog_recordsnumradio_value!="10")
					{
						cnt_1++;
					}
				}
				else
				{
					id=id +","+ obj[i].value;
					if(datalog_recordsnumradio_value!="10")
					{
						cnt_1++;
					}				
				}
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
	if(cnt_1 > 1)
	{
	alert("Multiple selection is allowed only for last 10 records !!!");
	return false;
	}
	if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}
}

 function report_display_person(file_name)
  {
  	//alert("file_name="+file_name);	
  	var obj=document.report1.manage_id;
  	var obj1=document.report1;
  	
  	var result=radio_selection(obj);
  	//var vehicle_result="all";//radio_vehicle_option_selection(obj);	
  	//var options_value=report_tree_validation(obj1);
  //	if(result!=false && vehicle_result!=false && options_value!=false)
  	if(result!=false)
  	{		
        var poststr = "account_id_local="+result;
        /*+
  					"&vehicle_display_option="+vehicle_result+
  					"&options_value="+options_value; */
  		//alert("account_id_local="+result+" ,vehicle_display_option="+vehicle_result+" ,options_value="+options_value);
  		makePOSTRequest(file_name,poststr);
  	}
  }	
  
  function halt_checkbox_selection(obj)
{
	var flag=0;
		var cnt=0;
	var id="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{
					id= id + obj[i].value;
					cnt=1;
				}
				else
				{
					id=id +"@"+ obj[i].value;
				}
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
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}
}
  //1.Visit Detail REPORT  
  
 function action_report_visitDetail(obj)
  {
    // GET SELECTED VEHICLES 
    //alert("one="+document.getElementById("loading_msg").style.display);     
    //alert("two="+document.getElementById("loading_msg").style.display);
     
    var device_str = get_selected_vehicle(obj);  
    //alert("device_str="+device_str);
    if(device_str!=false)
    {  
        document.getElementById("loading_msg").style.display = ''; 
        var poststr = "vehicleserial=" + encodeURI( device_str ) +			
		  "&start_date=" + encodeURI( document.getElementById("date1").value )+
		  "&end_date=" + encodeURI( document.getElementById("date2").value );                
              //  alert("Rizwan:"+poststr);                                                                                              
                   
        makePOSTRequest('src/php/action_report_visitDetail.htm', poststr);
    }
  }     
  
  	function report_upload_file_1(filename,title,upload_type)
	{
		showManageLoadingMessage();
		var poststr ="title="+title+
					"&upload_type="+upload_type;
		//alert("poststr="+poststr);
		makePOSTRequest(filename, poststr);
	}

	function show_master_browse(format_id_value)
	{
		//alert("format_id_value"+format_id_value);
		var obj=document.file_upload_form.format_ids;
		if(obj.length!=undefined)
		{
			//alert("in if");
			for (var i=0;i<obj.length;i++)
			{
				var formatid_tmp=obj[i].value;
				var formatid_tmp_1=formatid_tmp.split(":");	

				if(obj[i].checked==true)
				{					
					document.getElementById("master_child_table_"+formatid_tmp_1[0]).style.display="";				
				}
				else
				{
					document.getElementById("master_child_table_"+formatid_tmp_1[0]).style.display="none";
				}
			}
		}
		else
		{
			if(obj.checked==true)
			{
				var formatid_tmp=obj.value;
				var formatid_tmp_1=formatid_tmp.split(":");	
				document.getElementById("master_child_table_"+formatid_tmp_1[0]).style.display="";
			}
		}		
	}

  function action_report_io_trip(obj)
  {
    document.getElementById("loading_msg").style.display = '';
    
    var device_str = get_selected_vehicle(obj);
	if(device_str==false)
	{
		document.getElementById("loading_msg").style.display='none';
	}
	
    if(device_str!=false)
	{
    var poststr = "vehicleserial=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value );                                
                  //alert("riz:"+poststr);
	}                   
    makePOSTRequest('src/php/action_report_io_trip.htm', poststr);
  }	
  
