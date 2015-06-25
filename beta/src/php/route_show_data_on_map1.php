<?php include_once("user_type_setting.php"); 	
include_once("markers.php");?>
<script language="javascript" src="src/js/date_time_validation.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
	var startup_var;    
	var newurl;      ///////for change url every time
	newurl=0;
	var delaycnt=0;
	var thisdest;
	var thismode;
	var thisaccess;
	var timer;
	var TryCnt;
	var label_type = "<?php echo $report_type; ?>";
	var customerMarkers=new Array();
	//alert(label_type);
	var mining_user_type1 = "<?php echo $mining_user_type; ?>";
	var account_id_session = "<?php echo $account_id; ?>";
	var imei_iotype_arr=new Array();
	//alert("label_type="+label_type);
	var MAX_TIMELIMIT=1000;

	//alert("len="+xml_data.length);
	var lat_customer=new Array();
	var lng_customer=new Array();
	var station_customer=new Array();
	var customer_station_no=new Array();
	var type_customer=new Array();
	var lineColor=new Array('#000099','#311919','#CC6633','#FF3366','#999900')

	var station_name_customer = new Array();
	var station_lat_customer = new Array();
	var station_lng_customer = new Array();			
	var station_customer_1 = new Array();
	var station_type_customer = new Array();
	//var station_marker_customer = new Array();
	var station_counter_customer = 0;
	//markerCustomerS = new Array();
	var schedule_location_id = new Array();
	var schedule_location_name = new Array();
	var schedule_lat = new Array();
	var schedule_lng = new Array();	
	var schedule_location_counter= 0;
	
	var RouteNMCustomer=new Array(); // Route Number Evening Customer Type
	var RouteMCustomerLat=new Array();
	var RouteMCustomerLng=new Array();
	var RouteMCustomerStationNo=new Array();
	var RouteMCustomerNo=new Array();
	var RouteMCustomerType=new Array();
	
	var RouteNECustomer=new Array(); // Route Number Evening Customer Type
	var RouteECustomerLat=new Array();
	var RouteECustomerLng=new Array();
	var RouteECustomerStationNo=new Array();
	var RouteECustomerNo=new Array();	
	var RouteECustomerType=new Array();
	
	/*var RouteNMPlant=new Array(); // Route Number Evening Plant Type
	var RouteMPlantLat=new Array();
	var RouteMPlantLng=new Array();
	var RouteMPlantStationNo=new Array();
	var RouteMPlantNo=new Array();
	var RouteMPlantType=new Array();
	
	var RouteNEPlant=new Array(); // Route Number Evening Plant Type
	var RouteEPlantLat=new Array();
	var RouteEPlantLng=new Array();
	var RouteEPlantStationNo=new Array();
	var RouteEPlantNo=new Array();
	var RouteEPlantType=new Array();*/

	//alert("len="+xml_data.length);
	var lat_plant=new Array();
	var lng_plant=new Array();
	var station_plant=new Array();
	var customer_plant=new Array();
	var type_plant=new Array();	
	
	var chillingLatPlant=new Array();
	var chillingLngPlant=new Array();
	var chillingStationPlant=new Array();
	var chillingCustomerPlant=new Array();
	var chillingTypePlant=new Array();
	
	var station_name_plant = new Array();
	var station_lat_plant = new Array();
	var station_lng_plant = new Array();			
	var station_customer_plant = new Array();
	var station_type_plant = new Array();			
	//var station_marker_plant = new Array();
	var station_counter_plant = 0;
	//markerTypeS = new Array();
	
	var bname = navigator.appName;	
				var xml_data_this;
				var user_type_customer = "<?php echo $_SESSION['unique_client_customer']; ?>";	
				var xmlObjThis = null; 			
				var dest_station_test="src/php/client_map_feature_data/"+user_type_customer+".xml";
				//alert(dest_station_test);
				xmlObjThis = loadXML(dest_station_test);
				var dataReceived=false;
				if (bname == "Microsoft Internet Explorer")
				{    
					if(xmlObjThis!=null)	
					{                                      
						xml_data_this = xmlObjThis.documentElement.getElementsByTagName("marker");
						var xml_data_this1 = xmlObjThis.documentElement.getElementsByTagName("a1");
						if(xml_data_this1.length>0)
						{
							dataReceived=true;
						}
					}                                     
				}
				else
				{   
					xml_data_this = xmlObjThis.documentElement.getElementsByTagName("marker");
					//alert("length 1:"+xml_data.length);    
					var xml_data_this1 = xmlObjThis.documentElement.getElementsByTagName("a1");
					//alert("length 2:"+xml_data_this1.length);
					if(xml_data_this1.length>0)
					{
						dataReceived=true;
					}
				}
				if(dataReceived==true)
				{
					//alert("len="+xml_data_this.length);
					for (var k = 0; k < xml_data_this.length; k++) 
					{																													
						lat_customer[k] = xml_data_this[k].getAttribute("lat");
						lng_customer[k] = xml_data_this[k].getAttribute("lng");			
						station_customer[k] = xml_data_this[k].getAttribute("station");					
						customer_station_no[k] = xml_data_this[k].getAttribute("customer");
						type_customer[k] = xml_data_this[k].getAttribute("type");	
					
						station_name_customer[station_counter_customer] = station_customer[k];
						station_lat_customer[station_counter_customer] =lat_customer[k];
						station_lng_customer[station_counter_customer] =lng_customer[k];
						station_customer_1[station_counter_customer] = customer_station_no[k];
						station_type_customer[station_counter_customer] = type_customer[k];       		    	
						//station_marker_customer[station_counter_customer] = markerCustomerS[k];
						station_counter_customer++; 				
					} 
				}
				
				xml_data_this="";
				var user_type_plant = "<?php echo $_SESSION['unique_client_plant']; ?>";
				xmlObj = null; 			
				var dest_station_test="src/php/client_map_feature_data/"+user_type_plant+".xml";
				//alert("dest_station_test="+dest_station_test);
				xmlObj = loadXML(dest_station_test);
				dataReceived=false;
				if (bname == "Microsoft Internet Explorer")
				{    
					if(xmlObj!=null)	
					{                                      
						xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
						if(xml_data_this1.length>0)
						{
							dataReceived=true;
						}
					}                                     
				}
				else
				{   
					xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
					//alert("length 1:"+xml_data.length);    
					var xml_data_this1 = xmlObj.documentElement.getElementsByTagName("a1");
					// alert("length 2:"+xml_data1.length);
					if(xml_data_this1.length>0)
					{
						dataReceived=true;
					}
				}
				if(dataReceived==true)
				{
					for (var k = 0; k < xml_data_this.length; k++) 
					{																													
						lat_plant[k] = xml_data_this[k].getAttribute("lat");
						lng_plant[k] = xml_data_this[k].getAttribute("lng");			
						station_plant[k] = xml_data_this[k].getAttribute("station");					
						customer_plant[k] = xml_data_this[k].getAttribute("customer");
						type_plant[k] = xml_data_this[k].getAttribute("type");	

						station_name_plant[station_counter_plant] = station_plant[k];
						station_lat_plant[station_counter_plant] =lat_plant[k];
						station_lng_plant[station_counter_plant] =lng_plant[k];
						station_customer_plant[station_counter_plant] = customer_plant[k];
						station_type_plant[station_counter_plant] = type_plant[k];       		    	
						//station_marker_customer[station_counter_plant] = markerCustomerS[k];
						station_counter_plant++; 
					} 
				}
				
				xml_data_this="";
				var uniqueChillingPlant = "<?php echo $_SESSION['uniqueChillingPlant']; ?>";
				xmlObj = null;
				xmlObj = loadXML(uniqueChillingPlant);
				dataReceived=false;
				if (bname == "Microsoft Internet Explorer")
				{    
					if(xmlObj!=null)	
					{                                      
						xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
						if(xml_data_this1.length>0)
						{
							dataReceived=true;
						}
					}                                     
				}
				else
				{   
					xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
					//alert("length 1:"+xml_data.length);    
					var xml_data_this1 = xmlObj.documentElement.getElementsByTagName("a1");
					// alert("length 2:"+xml_data1.length);
					if(xml_data_this1.length>0)
					{
						dataReceived=true;
					}
				}
				if(dataReceived==true)
				{
					for (var k = 0; k < xml_data_this.length; k++) 
					{																													
						chillingLatPlant[k] = xml_data_this[k].getAttribute("lat");
						chillingLngPlant[k] = xml_data_this[k].getAttribute("lng");			
						chillingStationPlant[k] = xml_data_this[k].getAttribute("station");					
						chillingCustomerPlant[k] = xml_data_this[k].getAttribute("customer");
						chillingTypePlant[k] = xml_data_this[k].getAttribute("type");
					} 
				}				
				xml_data_this="";
				var customerRouteEvening = "<?php echo $_SESSION['uniqueCustomerRouteEvening']; ?>";
				xmlObj = null; 			
				var dest_station_test=customerRouteEvening;
				//alert("dest_station_test="+dest_station_test);
				xmlObj = loadXML(dest_station_test);
				dataReceived=false;
				if (bname == "Microsoft Internet Explorer")
				{    
					if(xmlObj!=null)	
					{                                      
						xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
						if(xml_data_this1.length>0)
						{
							dataReceived=true;
						}
					}                                     
				}
				else
				{   
					xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
					//alert("length 1:"+xml_data_this.length);    
					var xml_data_this1 = xmlObj.documentElement.getElementsByTagName("a1");
					// alert("length 2:"+xml_data1.length);
					if(xml_data_this1.length>0)
					{
						dataReceived=true;
					}
				}
				if(dataReceived==true)
				{
					for (var k = 0; k < xml_data_this.length; k++) 
					{
						RouteNECustomer[k] = xml_data_this[k].getAttribute("routeNo");
						RouteECustomerLat[k] = xml_data_this[k].getAttribute("lat");
						RouteECustomerLng[k] = xml_data_this[k].getAttribute("lng");			
						RouteECustomerStationNo[k] = xml_data_this[k].getAttribute("station");					
						RouteECustomerNo[k] = xml_data_this[k].getAttribute("customer");
						RouteECustomerType[k] = xml_data_this[k].getAttribute("type");	
					} 
				}
				
				xml_data_this="";
				var customerRouteMorning ="<?php echo $_SESSION['uniqueCustomerRouteMorning']; ?>";
				xmlObj = null; 			
				var dest_station_test=customerRouteMorning;
				 var exists = isFile(dest_station_test);
				//alert("exists="+exists);
				//alert("dest_station_test="+dest_station_test);
				xmlObj = loadXML(dest_station_test);
				dataReceived=false;
				if (bname == "Microsoft Internet Explorer")
				{    
					if(xmlObj!=null)	
					{                                      
						xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
						if(xml_data_this1.length>0)
						{
							dataReceived=true;
						}
					}                                     
				}
				else
				{   
					xml_data_this = xmlObj.documentElement.getElementsByTagName("marker");
					//alert("length 1:"+xml_data_this.length);    
					var xml_data_this1 = xmlObj.documentElement.getElementsByTagName("a1");
					//alert("length 2:"+xml_data1.length);
					if(xml_data_this1.length>0)
					{
						dataReceived=true;
					}
				}
				if(dataReceived==true)
				{
					for (var k = 0; k < xml_data_this.length; k++) 
					{																													
						RouteNMCustomer[k] = xml_data_this[k].getAttribute("routeNo");
						//alert("routeNo="+RouteNMCustomer[k]);
						RouteMCustomerLat[k] = xml_data_this[k].getAttribute("lat");
						RouteMCustomerLng[k] = xml_data_this[k].getAttribute("lng");			
						RouteMCustomerStationNo[k] = xml_data_this[k].getAttribute("station");					
						RouteMCustomerNo[k] = xml_data_this[k].getAttribute("customer");
						RouteMCustomerType[k] = xml_data_this[k].getAttribute("type");
					} 
				}
				

function show_data_on_map(report_format)
{
		//alert("in function");
	imei_iotype_arr.length=0;
  //alert(report_format);
  //var time_zone=document.thisform.time_zone.value;
	var access="1";   /////set access temporarily
	var time_interval=document.thisform.interval.value;  
	var startdateDoc=document.thisform.start_date.value;		
	//var enddateDoc=document.thisform.end_date.value;
	
	var startdate = startdateDoc.replace('/', '-');
	startdate = startdate.replace('/', '-');
	/*var enddate = enddateDoc.replace('/', '-');
	enddate = enddate.replace('/', '-');*/ 		
	//var display_mode=document.thisform.mode;   /////// 1=last_postoin  and 2=track 		
	var imeino1;
	var vid;
	var text_report_io_element="";
	vid = "";
	
	var obj_unique= {};
	/*for(i=0;i<display_mode.length;i++)
	{
    if(display_mode[i].checked)
    {
      var mode=display_mode[i].value;
      
      if(mode == 1)
      {  
        imeino1=document.thisform.elements['vehicleserial[]'];
      }
      else if(mode == 2)
      {
        imeino1=document.thisform.vehicleserial_radio; 
      }
    }
  }*/
  var mode=2;
   imeino1=document.thisform.elements['vehicleserial[]'];
  //alert("display_mode="+mode+" ,imei="+imeino1); 
  var uniqueImeiArr=new Array();
	var num1=0; 
	var dt = route_date_time_validation(mode);
	if(dt==true)
	{ 
		//if(mode==1)               // LAST POSITION
		{
			/*if(imeino1.length!=undefined)
			{
				var mlpi=0;  // map last position increment 
				for(i=0;i<imeino1.length;i++)
				{
					if(imeino1[i].checked)
					{
						uniqueImeiArr[mlpi]=imeino1[i].value;
						//alert('value'+j);
						//alert('value'+uniqueImeiArr[j]);
						mlpi++;
						num1 = 1;						
					}
				}
				imeino1=uniqueImeiArr.filter( onlyUnique );		
				if(num1==1)
				{
					for(i=0;i<imeino1.length;i++)
					{						
						var value_tmp=imeino1[i];
						var vid_local=value_tmp.split("*");
						if(vid_local[1]!="tmp_str")
						{
							imei_iotype_arr[vid_local[0]]=vid_local[1];				
						}
						text_report_io_element=text_report_io_element+vid_local[1]+",";	
						//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
						vid=vid+vid_local[0]+",";				
					}
				}
			}
			else
			{
				if(imeino1.checked)
				{
					var value_tmp=imeino1.value;					
					var vid_local=value_tmp.split("*");
					if(vid_local[1]!="tmp_str")
					{
						imei_iotype_arr[vid_local[0]]=vid_local[1];				
					}
					text_report_io_element=text_report_io_element+vid_local[1]+",";				
					vid=vid+vid_local[0]+",";
					num1 = 1;
				}
			}
			if(num1==0)
			{
				alert("Please Select At Least One Vehicle");							
				return false;  			
			}
			var strIOElement = text_report_io_element.length;
				text_report_io_element = text_report_io_element.slice(0,strIOElement-1);
			var strLen = vid.length;
			vid = vid.slice(0,strLen-1);*/
			/*else
			{
				for(id in obj_unique) 
				{				
					if(obj_unique.hasOwnProperty(id)) 
					{ 
						var vid_local=(obj_unique[id]).split("*");
						if(vid_local[1]!="tmp_str")
						{
							imei_iotype_arr[vid_local[0]]=vid_local[1];				
						}
						text_report_io_element=vid_local[1];
						vid=vid+vid_local[0]+",";
					}
				}
				var strLen = vid.length;
				vid = vid.slice(0,strLen-1);
			}	*/
		}
		//else if(mode==2)        // TRACK
		if(mode==2)        // TRACK
		{
			if(imeino1.length!=undefined)
			{
				var mlpi=0;  // map last position increment 
				for(i=0;i<imeino1.length;i++)
				{
					if(imeino1[i].checked)
					{
						uniqueImeiArr[mlpi]=imeino1[i].value;
						//alert('value'+j);
						//alert('value'+uniqueImeiArr[j]);
						mlpi++;
						num1++;						
					}
				}
				imeino1=uniqueImeiArr.filter( onlyUnique );		
				if(num1>=1)
				{
					for(i=0;i<imeino1.length;i++)
					{						
						var value_tmp=imeino1[i];
						var vid_local=value_tmp.split("*");
						if(vid_local[1]!="tmp_str")
						{
							imei_iotype_arr[vid_local[0]]=vid_local[1];				
						}
						text_report_io_element=text_report_io_element+vid_local[1]+",";	
						//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
						vid=vid+vid_local[0]+",";				
					}
				}
			}
			else
			{
				if(imeino1.checked)
				{
					var value_tmp=imeino1.value;					
					var vid_local=value_tmp.split("*");
					if(vid_local[1]!="tmp_str")
					{
						imei_iotype_arr[vid_local[0]]=vid_local[1];				
					}
					text_report_io_element=text_report_io_element+vid_local[1]+",";				
					vid=vid+vid_local[0]+",";
					num1 = 1;
				}
			}
			if(num1==0)
			{
				alert("Please Select At Least One Vehicle");							
				return false;  			
			}
			if(num1==6 || num1>6)
			{
				alert("You may not Select more than five Vehicle For Track.Please select below of it.");
				return false;
			}
			var strIOElement = text_report_io_element.length;
				text_report_io_element = text_report_io_element.slice(0,strIOElement-1);
			var strLen = vid.length;
			vid = vid.slice(0,strLen-1);
			/*else
			{
				for(id in obj_unique) 
				{				
					if(obj_unique.hasOwnProperty(id)) 
					{ 
						var vid_local=(obj_unique[id]).split("*");
						if(vid_local[1]!="tmp_str")
						{
							imei_iotype_arr[vid_local[0]]=vid_local[1];				
						}
						text_report_io_element=vid_local[1];
						vid=vid+vid_local[0]+",";
					}
				}
				var strLen = vid.length;
				vid = vid.slice(0,strLen-1);
			}	*/
			
		}
		
		/*else if(mode==2 && count>1)
		{
			alert("Please Select One Vehicle For Track");
			return false;
		}*/
		 
		//alert("num1="+num1);
    //else if(num1==1)
		if(num1 >= 1)
		{
			var feature_id_map = document.getElementById('station_flag_map').value;
			if(report_format=="map_report")
			{
				document.getElementById('prepage').style.visibility='visible';
				startup_var = 1;
				var status;
				var time_interval;
				var pt_for_zoom;
				var zoom_level;	
				var n=new Array();

				if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
				{status = "ON";}
				else{status = "OFF";  pt_for_zoom = "0";  zoom_level = "0";} 

				//initialize(); 
				var diffdate;
				var difftype;

				/*if(time_zone=="IST")
				{
					diffdate = 0;
					difftype = 0;
				}
				else if(time_zone=="GMT")
				{
					diffdate = 19800000;
					difftype = 1;
				}*/
			  diffdate = 0;
				difftype = 0;
				//load(vid,mode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval);		
				flag_play=0;
				play_interval=0;
				/* comment further improve
				var play_stop=document.getElementById('play_stop').value;
				
				if(play_stop=="Stop Play"){
					document.getElementById('play_stop').value="Track Play";
				}*/
				//alert("startdate="+startdate);
				enddate=startdate+" 23:59:59";
				startdate=startdate+" 00:00:00";
				
				//alert("startdate="+startdate+"enddate="+enddate);
				load(vid,mode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval,flag_play,play_interval);	
			} 
			/*else if(report_format=="text_report")
			{
				var date = new Date();
				var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
				if(document.thisform.location.checked==true)
				{
					var data_with_location="1";					
				}
				else
				{
					var data_with_location="0";
				}
				
				
				if(mode==1)
				{
					//alert("vid="+vid);
				  //document.ld.action="src/php/Last_data_prev.php?vserial="+vid+"&startdate="+startdate+"&enddate="+enddate+"&mode="+mode+"&time_interval="+time_interval+"&time_zone="+time_zone+"&xml_file="+dest;
					//document.ld.action="src/php/Last_data_prev.php?vserial="+vid+"&text_report_io_element="+text_report_io_element+"&startdate="+startdate+"&enddate="+enddate+"&mode="+mode+"&time_interval="+time_interval+"&xml_file="+dest;
					document.ld.xml_file.value=dest;
					document.ld.vserial.value=vid;
					document.ld.startdate.value=startdate;
					document.ld.enddate.value=enddate;
					document.ld.text_report_io_element.value=text_report_io_element;
					document.ld.mode.value=mode;
					document.ld.time_interval.value=time_interval;
					document.ld.dwt.value=data_with_location;
					document.ld.lastcategory.value=document.getElementById("category").value;
					document.ld.submit();
				
				}
				else if(mode==2)
				{				
					//document.fd.action="src/php/Full_data_prev.php?vserial="+vid+"&startdate="+startdate+"&enddate="+enddate+"&mode="+mode+"&time_interval="+time_interval+"&time_zone="+time_zone+"&xml_file="+dest;
					//document.fd.action="src/php/Full_data_prev.php?vserial="+vid+"&text_report_io_element="+text_report_io_element+"&startdate="+startdate+"&enddate="+enddate+"&mode="+mode+"&time_interval="+time_interval+"&xml_file="+dest;
					document.fd.xml_file.value=dest;
					document.fd.vserial.value=vid;
					document.fd.startdate.value=startdate;
					document.fd.enddate.value=enddate;
					document.fd.text_report_io_element.value=text_report_io_element;
					document.fd.mode.value=mode;
					document.fd.time_interval.value=time_interval;
					document.fd.dwt.value=data_with_location;
					document.ld.lastcategory.value=document.getElementById("category").value;
					document.fd.submit();					
				}
			} //else if  */
			/*else if(report_format=="play_report")
			{
				document.getElementById('prepage').style.visibility='visible';
				startup_var = 1;
				var status;
				var time_interval;
				var pt_for_zoom;
				var zoom_level;	
				var n=new Array();

				if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
				{status = "ON";}
				else{status = "OFF";  pt_for_zoom = "0";  zoom_level = "0";} 

				//initialize(); 
				var diffdate;
				var difftype;

				
			    diffdate = 0;
				difftype = 0;
				flag_play=1;
				play_interval=document.getElementById('play_interval').value;
				var play_stop=document.getElementById('play_stop').value;
				if(play_stop=="Stop Play"){
					document.getElementById('play_stop').value="Track Play";
					document.getElementById('prepage').style.visibility='hidden';
				}
				else if(play_stop=="Track Play"){
					document.getElementById('play_stop').value="Stop Play";
					load(vid,mode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval,flag_play,play_interval);
				}					
			} //else if*/
		} // if num =1
	} // if dt = true
}  // function closed
// for storing all point of plant and custormer

function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}


/*function DateCheck_1(b)
{
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1
	var day = currentTime.getDate()
	var year = currentTime.getFullYear() 
  
	  var startdate1;
	  var enddate1;

	  if(b==1 || b==2)
	  {
  		startdate1=document.thisform.start_date.value;		
  		enddate1=document.thisform.end_date.value;
		}
		else
		{
			startdate1 = "";
			enddate1 = "";  
	  }
	
	//alert(" b="+b+" startdate="+startdate1+" enddate="+enddate1);
	
	var startlen = startdate1.length;
	var endlen = enddate1.length;

	if(((startlen > 0)&&(startlen < 10))||((endlen > 0)&&(endlen < 10)))
	{
		alert("Incorrect date  format...enter yyyy-mm-dd");
		return false;
	}

	if(startlen > 0)
	{
		var startday = startdate1.substr(8,2);
		var startmonth = startdate1.substr(5,2);
		var startyr = startdate1.substr(0,4);
		
	//alert("startday="+ startday +" startmonth="+startmonth+" startyr="+startyr);
		if(startyr > year)
		{
			alert("Incorrect Date From Value...Please Enter Again");
			document.thisform.start_date.focus();
			return false;
		}
		if(year == startyr)
		{
			if(startmonth == month)
				if(startday > day)
				{
					alert("Incorrect Date From Value...Please Enter Again");
					document.thisform.end_date.focus();
					return false;
				}
			if(startmonth > month)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();	
				return false;
			}
		}
		var leapyr=0;
		if(startyr%4 == 0)
		{
			if(startyr%100 != 0)
			{
				leapyr = 1;
			}
			else
			{
				if(startyr%400 == 0)
					leapyr = 1;
				else
					leapyr = 0;
			}
		}
		if((leapyr == 1)&&(startmonth == "02"))
		{
			if(startday > 29)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();
				return false;
			}
		}
		if((leapyr == 0)&&(startmonth == "02"))
		{
			if(startday > 28)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();
				return false;
			}
		}
		if((startmonth == "04")||(startmonth == "06")||(startmonth == "09")||(startmonth == "11"))
		{
			if(startday > 30)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();						
				return false;
			}
		}
	}

	if(endlen > 0)
	{
		var endday = enddate1.substr(8,2);
		var endmonth = enddate1.substr(5,2);
		var endyr = enddate1.substr(0,4);
		if(endyr > year)
		{
			alert("Incorrect Date To Value...Please Enter Again");
			document.thisform.end_date.focus();
			return false;
		}
		if(year == endyr)
		{
			if(endmonth == month)
				if(endday > day)
				{
					alert("Incorrect Date To Value...Please Enter Again");
					document.thisform.end_date.focus();
					return false;
				}
			if(endmonth > month)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();	
				return false;
			}
		}
		var leapyr=0;
		if(endyr%4 == 0)
		{
			if(endyr%100 != 0)
			{
				leapyr = 1;
			}
			else
			{
				if(endyr%400 == 0)
					leapyr = 1;
				else
					leapyr = 0;
			}
		}
		if((leapyr == 1)&&(endmonth == "02"))
		{
			if(endday > 29)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();
				return false;
			}
		}
		if((leapyr == 0)&&(endmonth == "02"))
		{
			if(endday > 28)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();
				return false;
			}
		}
		if((endmonth == "04")||(endmonth == "06")||(endmonth == "09")||(endmonth == "11"))
		{
			if(endday > 30)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();
				return false;
			}
		}
	}
	if((startlen > 0)&&(endlen > 0))
	{
		if(startyr > endyr)
		{
			alert("Incorrect Duration Entered...Please Enter Again");
			document.thisform.start_date.focus();
			return false;
		}
		if(startyr == endyr)
		{

			if(endmonth == startmonth)
				if(startday > endday)
				{
					alert("Incorrect Duration Entered...Please Enter Again");
					document.thisform.start_date.focus();
					return false;
				}
			if(startmonth > endmonth)
			{
				alert("Incorrect Duration Entered...Please Enter Again");
				document.thisform.start_date.focus();
				return false;
			}
		}
	}
	return true;
}*/

  function trim(str) 
  {
          return str.replace(/^\s+|\s+$/g,"");
  }
  
	function CalculateActualDate(date,diffdate,difftype)
	{
		datetime = date;
		var date_ist_gmt1=datetime.split(" ");
		var date_ist_gmt2=date_ist_gmt1[1].split(":");
		var date_ist_gmt3=date_ist_gmt1[0].split("/");
		
		var d = new Date();
		var year1= d.getYear();
	
		d.setDate(date_ist_gmt3[2]);
		d.setMonth(date_ist_gmt3[1]);
		d.setYear(date_ist_gmt3[0]);
		d.setHours(date_ist_gmt2[0]);
		d.setMinutes(date_ist_gmt2[1]);
		d.setSeconds(date_ist_gmt2[2]);

		var datetime1=d.getTime();
		if(difftype==1)
		{				
			var datetime2=datetime1-diffdate;
		}
		else if(difftype==0)
		{
			var datetime2=datetime1+diffdate;
		}
	
		var getfulldate=new Date();
		getfulldate.setTime(datetime2);
		var year=getfulldate.getYear();
		
		var Final_year=0;

		if(year>2000)    
		Final_year=year;
		else
		Final_year=year+1900;  

		var month=getfulldate.getMonth();
		if(month<=9)
		{
			month='0'+month;
		}
		var day=getfulldate.getDate();
		if(day<=9)
		{
			day='0'+day;
		}
		var hour=getfulldate.getHours();
		if(hour<=9)
		{
			hour='0'+hour;
		}
		var minute=getfulldate.getMinutes();
		if(minute<=9)
		{
			minute='0'+minute;
		}
	
		var second=getfulldate.getSeconds();
		if(second<9)
		{
			second='0'+second;
		}

		datetime=Final_year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
		//alert("datetime="+datetime);	
		return  datetime;    
	}

	function y2k(number)
	{ 
		return (number < 1000) ? number + 1900 : number; 
	}
	function padout(number)
	{ 
		return (number < 10) ? '0' + number : number;
	}

	function showDates(startYear1,startMonth1,startDay1,endYear1,endMonth1,endDay1) 
	{
		var all_dates = new Array();
		var ad=0;	
		startDate1 = new Date(startYear1,startMonth1 - 1,startDay1);
		endDate1 = new Date(endYear1,endMonth1 - 1,endDay1); 
		var tmp;

		for (;;) 
		{
			if(startDate1<=endDate1)
			{
				tmp = (y2k(startDate1.getYear()) + '-' + padout(startDate1.getMonth() + 1) + '-' + padout(startDate1.getDate()));	
				all_dates[ad] = tmp;
				ad++;
				startDate1 = new Date(startDate1.getTime() + 1*24*60*60*1000);
			}
			else if (startDate1 > endDate1) 
			{
				return all_dates;
			}			

		}	
	}
		
///////**** FUNCTION TO SELECT LANDMARK ON ZOOM 

function getLandMark1(event,newzoomlevel)
{
  //alert("In landmark");
  landmark_name_list = new Array();
  landmark_point_list = new Array();
  landmark_customer_list = new Array();
  landmark_marker_list = new Array();    	
  landmark_counter = 0;
  
  //alert("landmark");
  var newzoomlevel= map.getZoom();				
	GDownloadUrl("src/php/select_landmark.php", function(data)
    {								
		var xml = GXml.parse(data);
		var lnmark_data = xml.documentElement.getElementsByTagName("marker");	
		//alert("landmark length="+lnmark_data.length);
		var i;
		var landmark;	
		var markerL;
		var zoomlevel;
		var point;	
																							
		//alert("Landmark_data.length="+lnmark_data.length);
    for(i=0; i <lnmark_data.length; i++) 
		{									 					
			point = new GLatLng(parseFloat(lnmark_data[i].getAttribute("lat")),
			parseFloat(lnmark_data[i].getAttribute("lng")));				

			zoomlevel = lnmark_data[i].getAttribute("zoomlevel");					
			landmark = lnmark_data[i].getAttribute("landmark");
													
			//alert("zoomlevel="+zoomlevel+" , newzoomlevel="+newzoomlevel);
			if(zoomlevel == newzoomlevel || zoomlevel<newzoomlevel)
			{
				markerL = ShowLandmark(point, landmark);	
				map.addOverlay(markerL);
							
        //STORE VARIABLE IN ARRAYS FOR LANDMARK SEARCH
        landmark_name_list[landmark_counter] = landmark;
        landmark_point_list[landmark_counter] = point;            					
      	landmark_marker_list[landmark_counter] = markerL;
      	landmark_counter++;    				
      	/////////////////////////////////////////////
			}			
		}																																
	});	 // GDownload url closed		
} 

////****** FUNCTION LANDMARK CLOSED


/////****** FUNCTION TO GET STATION 
/*var markerS = new Array();
var dest_station_tmp ="";
var dest_station ="";

function getStation1(select_value)
{
	//alert("StationPrev");  
  var date = new Date();    
  dest_station = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";        
  dest_station_tmp = dest_station;
  
  var poststr = "xml_file_station="+dest_station+"&select_val="+select_value;				
  //alert("exists="+exists);
  makePOSTRequestMap('src/php/select_station.php', poststr);			
     
  TryCnt =0;
  clearTimeout(timer);
  timer = setTimeout('display_station()',1000);	 
 
 /*  				
	GDownloadUrl("src/php/select_station.php", function(data)
    {								
		var xml = GXml.parse(data);
		var lnmark_data = xml.documentElement.getElementsByTagName("marker");	
		//alert("landmark length="+lnmark_data.length);
		var i;
		var station;	
		var markerL;
		var customer;
		var point;	
																							
		for(i=0; i <lnmark_data.length; i++) 
		{									 					
			point = new GLatLng(parseFloat(lnmark_data[i].getAttribute("lat")),
			parseFloat(lnmark_data[i].getAttribute("lng")));				

			station = lnmark_data[i].getAttribute("station");					
			customer = lnmark_data[i].getAttribute("customer");
													
			//alert(point+","+station+","+customer);
      markerS[i] = ShowStation(point, station, customer);	   //RR
			map.addOverlay(markerS[i]);			
		}																																
	});	 // GDownload url closed		
	*/
//} 

/*var station_name_list = new Array();
var station_point_list = new Array();
var station_type_list = new Array();
var station_customer_list = new Array();
var station_marker_list = new Array();
var station_counter = 0;

var landmark_name_list = new Array();
var landmark_point_list = new Array();
var landmark_marker_list = new Array();
var landmark_counter = 0;

function display_station()
{
  station_name_list = new Array();
  station_point_list = new Array();
  station_type_list = new Array();
  station_customer_list = new Array();
  station_marker_list = new Array(); 
  station_counter = 0;

  //alert("displaystationok");
  markerS = new Array();  
	var station;	
	var markerL;
	var customer;
	var point;
  var type;	
  
  var xml_data; 
  var DataReceived = false;  
  try
  {
    var bname = navigator.appName;
          
    /*if (bname == "Microsoft Internet Explorer")
    {
      alert("Wait for data, please use Mozilla for better compatibility");
    }//alert(bname);   */
              
    //var xmlObj = null;        
   // alert("thisdest="+thisdest);
    /*xmlObj = loadXML(dest_station_tmp);
     
    if (bname == "Microsoft Internet Explorer")
    {
      //alert("In IE:"+xmlObj);
      if(xmlObj!=null)	
      {                                      
        xml_data = xmlObj.documentElement.getElementsByTagName("marker");
        DataReceived = true;
      } 
      else
      {
        if(TryCnt<=MAX_TIMELIMIT)
        {
          TryCnt++;
          clearTimeout(timer);
          timer = setTimeout('display_station()',1000);
        }
      }                                
    }
    else
    {
      //alert("In Mozilla");
      xml_data = xmlObj.documentElement.getElementsByTagName("marker");
      //alert("length 1:"+xml_data.length);
      //var xml_data1 = xmlObj.getElementsByTagName("t1");
      var xml_data1 = xmlObj.documentElement.getElementsByTagName("a1");
	    // alert("length 2:"+xml_data1.length);
      if(xml_data1.length>0)
      {
        //alert("A");
        DataReceived = true;
      }
      else
      {
        //alert("B:"+TryCnt);
        if(TryCnt<=MAX_TIMELIMIT)
        {
          TryCnt++;
          clearTimeout(timer);
          timer = setTimeout('display_station()',1000);
        }
      }
    }   
    //alert("xml_data="+xml_data);             
	}
	catch(err)
	{
		alert("sorry!unable to get station information");
	}	
	    
	//alert("data recieved:"+DataReceived+" ,xml_data.length="+xml_data.length);
	
  if(DataReceived)
	{
    clearTimeout(timer);
    
    //alert("len="+xml_data.length);
    for (var k = 0; k < xml_data.length; k++) 
  	{																													
  		point = new GLatLng(parseFloat(xml_data[k].getAttribute("lat")),
  		parseFloat(xml_data[k].getAttribute("lng")));				
  
  		station = xml_data[k].getAttribute("station");					
  		customer = xml_data[k].getAttribute("customer");
  		type = xml_data[k].getAttribute("type");
  												
  		//alert(point+","+station+","+customer);
      markerS[k] = ShowStation(point, station, customer, type);	   //RR
  		map.addOverlay(markerS[k]);
      
      //STORE VARIABLE IN ARRAYS FOR STATION SEARCH
      station_name_list[station_counter] = station;
      station_point_list[station_counter] = point;
      station_customer_list[station_counter] = customer;
      station_type_list[station_counter] = type;        		    	
      station_marker_list[station_counter] = markerS[k];
    	station_counter++; 
      ///////////////// ///////////////////////////                	      	      		
    }  
     
    var poststr = "dest=" + encodeURI( dest_station );
    makePOSTRequestMap('src/php/del_xml.php', poststr);	
  }							   
}
/// **** FUNCTION STATION CLOSED */


var markerS = new Array();
var dest_station_tmp ="";
var dest_station ="";

function getStation1(select_value)
{
	//alert("StationPrev");  
  var date = new Date();    
  /*dest_station = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";        
  dest_station_tmp = dest_station;
  
  var poststr = "xml_file_station="+dest_station+"&select_val="+select_value;				
  //alert("exists="+exists);
  makePOSTRequestMap('src/php/select_station.php', poststr);*/			
     
  TryCnt =0;
  clearTimeout(timer);
  timer = setTimeout('display_station()',1000);	 
 
 /*  				
	GDownloadUrl("src/php/select_station.php", function(data)
    {								
		var xml = GXml.parse(data);
		var lnmark_data = xml.documentElement.getElementsByTagName("marker");	
		//alert("landmark length="+lnmark_data.length);
		var i;
		var station;	
		var markerL;
		var customer;
		var point;	
																							
		for(i=0; i <lnmark_data.length; i++) 
		{									 					
			point = new GLatLng(parseFloat(lnmark_data[i].getAttribute("lat")),
			parseFloat(lnmark_data[i].getAttribute("lng")));				

			station = lnmark_data[i].getAttribute("station");					
			customer = lnmark_data[i].getAttribute("customer");
													
			//alert(point+","+station+","+customer);
      markerS[i] = ShowStation(point, station, customer);	   //RR
			map.addOverlay(markerS[i]);			
		}																																
	});	 // GDownload url closed		
	*/
} 



	/// **** FUNCTION STATION CLOSED 
	
	function runScriptEnter_station(value) 
	{
		//alert("value="+value);
		
		var imgCnt=0;
		var client_type_combo=document.getElementById('station_chk').value;
		if(client_type_combo=="2")
		{
			var obj=document.thisform.elements['morningArr[]'];
			var customerPlotFlag=0;
			var mcnt=0;
			if(obj.length!=undefined)
			{
				for (var i=0;i<obj.length;i++)
				{
					if(obj[i].checked==true)
					{
						mcnt++;
						if((mcnt==6) || (mcnt>6))
						{
							customerPlotFlag=1;
						}
						if(customerPlotFlag==1)
						{
							obj[i].checked=false;
						}
					}
					
				}
			}
			if(customerPlotFlag==1)
			{
				alert("You may not select more than five routes.");
				return false;
			}
			else
			{
				//alert("in else");
				deleteOverlayCustomer();
				//alert("in else 1");
				if(obj.length!=undefined)
				{
					for (var i=0;i<obj.length;i++)
					{
						if(obj[i].checked==true)
						{	
							search_station(obj[i].value,imgCnt);
							if(imgCnt>4)
							{
								imgCnt=0;
								break;
							}
							imgCnt++;
						}	  
					}
				}
				else
				{				//alert("In else");
					if(obj.checked==true)
					{
						search_station(obj.value,i);
					}
				}
			}
		}
		if(client_type_combo=="3")
		{
			var obj=document.thisform.elements['eveningArr[]'];
			var customerPlotFlag=0;
			var ecnt=0;
			if(obj.length!=undefined)
			{
				for (var i=0;i<obj.length;i++)
				{
					if(obj[i].checked==true)
					{	
						ecnt++;
						if((ecnt==6) || (ecnt>6))
						{
							customerPlotFlag=1;
						}
						//alert("customerPlotFlag="+customerPlotFlag+"i="+i);
						if(customerPlotFlag==1)
						{
							obj[i].checked=false;
						}
					}
										
				}
			}
			//alert("customerPlotFlag="+customerPlotFlag);
			if(customerPlotFlag==1)
			{
				alert("You may not select more than five routes.");
				return false;
			}
			else
			{
				deleteOverlayCustomer();
				if(obj.length!=undefined)
				{
					for (var i=0;i<obj.length;i++)
					{
						if(obj[i].checked==true)
						{	
							search_station(obj[i].value,imgCnt);
							if(imgCnt>4)
							{
								imgCnt=0;
								break;
							}
							imgCnt++;
						}	  
					}
				}
				else
				{				//alert("In else");
					if(obj.checked==true)
					{
						search_station(obj.value,i);
					}
				}
			}
		}
		function deleteOverlayCustomer() 
		{
			for (var i = 0; i < customerMarkers.length; i++) 
			{
				customerMarkers[i].setMap(null);
			}
		}
		/*if (e.keyCode == 13) 
		{
			if(document.getElementById('station_chk').value=="select")
			{
				alert("Please Select Customer Or Plant");
				return false;
			}
			else
			{
				var tb = document.getElementById("station_search_text");
				//alert("Enter hits Station");
				search_station();
			}
		}*/		
	}
	
	function runScriptEnter_location(e) 
	{		
		if (e.keyCode == 13) 
		{
			var tb = document.getElementById("location_search_text");
			//alert("Enter hits Station");
			search_schedule_location();	
		}		
	}

	function runScriptEnter_landmark(e) 
	{
		if (e.keyCode == 13) 
		{
		  var tb = document.getElementById("lanmark_search_text");
			//alert("Enter hits Landmark");
			search_landmark();
		}
	}
	function search_schedule_location()
	{  
		var search_text = document.getElementById('location_search_text').value;
		//alert("location2="+search_text);
		var found_location_name;
		var found_location_id;
		var found_lat;
		var found_lng;		
		found_location_name="";
		found_location_id="";
		found_lat="";
		found_lng="";
		var record_found=0;
		//alert("count="+schedule_location_counter);
		if(schedule_location_counter>0)
		{
			for(var i=0;i<schedule_location_counter;i++)
			{
				search_text = trim(search_text);
				
				//station_name_customer[i] = trim(station_name_customer[i]);
				schedule_location_name[i] = trim(schedule_location_name[i]); 
				//schedule_location_name[i] = trim(schedule_location_name[i]); 
				//alert("laction1="+schedule_location_name[i]+"location2="+search_text);
				//if((search_text == station_name_customer[i]) || (search_text == station_customer_1[i]) )
				if(search_text == schedule_location_name[i])
				{
					//alert("found");
					record_found=1;
					found_location_name = schedule_location_name[i];
					found_lat = schedule_lat[i];
					found_lng = schedule_lng[i];									
					break;
				}    
			}  
			if(record_found==1)
			{	
				var lt_1 = found_lat; 
				var ln_1 = found_lng;
				//alert("lt_1="+lt_1+"ln_g="+ln_1);
				Show_Search_Location(lt_1,ln_1, found_location_name);
			}
			else
			{
				alert("Location did not exists or not matched.");
				return false;
			}
		}
		else
		{
			alert("Location did not add.Please add Location");
			return false;
		}
		
	}
	
	function Show_Search_Location(lt_1, ln_1, location_name)  
	{
			//alert("lt_1="+lt_1);
		var icon1 = 'images/station.png';
		var point=new google.maps.LatLng(lt_1, ln_1);
		//alert("point="+point);	
		var lat = Math.round((lt_1)*100000)/100000;
		var lng = Math.round((ln_1)*100000)/100000;
		
		var contentString = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Location Name</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2><b>'+location_name+ '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';
		//alert("contentString="+contentString);
			//alert("map="+map_canvas);
		var marker1 = new google.maps.Marker({
		  position: point,
		  map: map_canvas,
		  icon: icon1,
		  title: 'Uluru (Ayers Rock)'
	  });	
	  //alert("marker1="+marker1);
		var infowindow1 = new google.maps.InfoWindow({
		  content: contentString
	  }); 
	infowindow1.open(map_canvas,marker1);  
	  google.maps.event.addListener(marker1, 'click', function() {
		infowindow1.open(map_canvas,marker1);
	  });
	}
	
	function showRouteCustomerLine()
	{
		//alert("value="+value);
		var customerFileArr=Object();
		var bname = navigator.appName;	
		var xml_data_this;
		var xmlObjThis = null; 			
		var dest_station_test="src/php/client_map_feature_data/commonXmlFile.xml";
		 var exists = isFile(dest_station_test);
		//alert("exists="+exists);
		//alert(dest_station_test);
		xmlObjThis = loadXML(dest_station_test);
		var dataReceived=false;
		if (bname == "Microsoft Internet Explorer")
		{    
			if(xmlObjThis!=null)	
			{                                      
				xml_data_this = xmlObjThis.documentElement.getElementsByTagName("marker");
				var xml_data_this1 = xmlObjThis.documentElement.getElementsByTagName("a1");
				if(xml_data_this1.length>0)
				{
					dataReceived=true;
				}
			}                                     
		}
		else
		{   
			xml_data_this = xmlObjThis.documentElement.getElementsByTagName("marker");
			//alert("length 1:"+xml_data_this.length);    
			var xml_data_this1 = xmlObjThis.documentElement.getElementsByTagName("a1");
			//alert("length 2:"+xml_data_this1.length);
			if(xml_data_this1.length>0)
			{
				dataReceived=true;
			}
		}
		if(dataReceived==true)
		{
			//alert("len="+xml_data_this.length);
			for (var k = 0; k < xml_data_this.length; k++) 
			{
				var crno=xml_data_this[k].getAttribute("cno")+xml_data_this[k].getAttribute("rno");
				customerFileArr[crno]=xml_data_this[k].getAttribute("vname")+","+xml_data_this[k].getAttribute("cno")+","+xml_data_this[k].getAttribute("rno")+","+xml_data_this[k].getAttribute("hd")+","+xml_data_this[k].getAttribute("adt")+","+xml_data_this[k].getAttribute("tname");									
				//alert("data="+customerFileArr[crno]);
			} 
		}
		
		var imgCnt=0;
		var client_type_combo=document.getElementById('station_chk').value;
		if(client_type_combo=="2")
		{
			var obj=document.thisform.elements['morningArr[]'];
			var customerPlotFlag=0;
			var mcnt=0;
			if(obj.length!=undefined)
			{
				for (var i=0;i<obj.length;i++)
				{
					if(obj[i].checked==true)
					{
						mcnt++;
						if((mcnt==6) || (mcnt>6))
						{
							customerPlotFlag=1;
						}
						if(customerPlotFlag==1)
						{
							obj[i].checked=false;
						}
					}
					
				}
			}
			if(customerPlotFlag==1)
			{
				alert("You may not select more than five routes.");
				return false;
			}
			else
			{
				//alert("in else");
				deleteOverlayCustomer();
				//alert("in else 1");
				if(obj.length!=undefined)
				{
					for (var i=0;i<obj.length;i++)
					{
						if(obj[i].checked==true)
						{	
							tmp_search_station(obj[i].value,imgCnt,customerFileArr);
							if(imgCnt>4)
							{
								imgCnt=0;
								break;
							}
							imgCnt++;
						}	  
					}
				}
				else
				{				//alert("In else");
					if(obj.checked==true)
					{
						tmp_search_station(obj.value,i,customerFileArr);
					}
				}
			}
		}
		if(client_type_combo=="3")
		{
			var obj=document.thisform.elements['eveningArr[]'];
			var customerPlotFlag=0;
			var ecnt=0;
			if(obj.length!=undefined)
			{
				for (var i=0;i<obj.length;i++)
				{
					if(obj[i].checked==true)
					{	
						ecnt++;
						if((ecnt==6) || (ecnt>6))
						{
							customerPlotFlag=1;
						}
						//alert("customerPlotFlag="+customerPlotFlag+"i="+i);
						if(customerPlotFlag==1)
						{
							obj[i].checked=false;
						}
					}
										
				}
			}
			//alert("customerPlotFlag="+customerPlotFlag);
			if(customerPlotFlag==1)
			{
				alert("You may not select more than five routes.");
				return false;
			}
			else
			{
				deleteOverlayCustomer();
				if(obj.length!=undefined)
				{
					for (var i=0;i<obj.length;i++)
					{
						if(obj[i].checked==true)
						{	
							tmp_search_station(obj[i].value,imgCnt,customerFileArr);
							if(imgCnt>4)
							{
								imgCnt=0;
								break;
							}
							imgCnt++;
						}	  
					}
				}
				else
				{				//alert("In else");
					if(obj.checked==true)
					{
						tmp_search_station(obj.value,i,customerFileArr);
					}
				}
			}
		}
		function deleteOverlayCustomer() 
		{
			for (var i = 0; i < customerMarkers.length; i++) 
			{
				customerMarkers[i].setMap(null);
			}
		}
		/*if (e.keyCode == 13) 
		{
			if(document.getElementById('station_chk').value=="select")
			{
				alert("Please Select Customer Or Plant");
				return false;
			}
			else
			{
				var tb = document.getElementById("station_search_text");
				//alert("Enter hits Station");
				search_station();
			}
		}*/
	}
	
	function search_station(value,inci) // inc=increatment , i=image
	{  
		//alert("value_1="+value);
		var search_text=value;
		var found_station_name;
		var found_customer_no;
		var found_lat;
		var found_lng;
		var found_type; 

		
		var client_type_combo=document.getElementById('station_chk').value;
		
		var rFoundRNumber=[];
		var rFoundStationName=[];
		var rFoundCustomerNo=[];
		var rFoundLat=[];
		var rFoundLng=[];
		var rFoundType=[];	
		var routeLength=0;
		var tmpCnt=0;
		var routeTmpFlag = false;
	//alert("client_type_combo_up="+client_type_combo);
		if(client_type_combo=="2")
		{	
			//alert("client_type_combo="+client_type_combo);
			routeLength=RouteNMCustomer.length;
			//alert("routeLengthaa="+routeLength+" search_text="+search_text);
			if(parseInt(routeLength)>0)
			{
				//alert("in if");
				for(var i=0;i<routeLength;i++)
				{
					search_text = search_text.trim();
					//alert("route1="+RouteNMCustomer[i]+" route2="+search_text);
					if(search_text == RouteNMCustomer[i].trim())                                        
					{
						routeTmpFlag=true;
						rFoundRNumber[tmpCnt]=RouteNMCustomer[i]
						rFoundLat[tmpCnt] = RouteMCustomerLat[i];
						rFoundLng[tmpCnt] = RouteMCustomerLng[i];
						rFoundStationName[tmpCnt] = RouteMCustomerStationNo[i];
						rFoundCustomerNo[tmpCnt] = RouteMCustomerNo[i];
						rFoundType[tmpCnt] = RouteMCustomerType[i];
						tmpCnt++;
					}    
				}  
				if(routeTmpFlag==true)
				{
					plotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType, rFoundLat, rFoundLng,inci);
				}
			}
			if(routeTmpFlag==false)
			{
				alert("Error! Unable to Find the Morning Route Customers");
				return false;
			}
		}
		
		rFoundRNumber=[];
		rFoundStationName=[];
		rFoundCustomerNo=[];
		rFoundLat=[];
		rFoundLng=[];
		rFoundType=[];	
		var routeLength=0;
		tmpCnt=0;
		var routeTmpFlag = false;
		if(client_type_combo=="3")
		{				
			routeLength=RouteNECustomer.length;
			//alert("routeLength="+routeLength+" search_text="+search_text);
			
			if(routeLength>0)
			{
				for(var i=0;i<routeLength;i++)
				{
					search_text = search_text.trim();
					if(search_text == RouteNECustomer[i].trim())                                        
					{
						//alert("routeNo="+RouteNECustomer[i].trim());
						routeTmpFlag=true;
						rFoundRNumber[tmpCnt]=RouteNECustomer[i]
						rFoundLat[tmpCnt] = RouteECustomerLat[i];
						rFoundLng[tmpCnt] = RouteECustomerLng[i];
						rFoundStationName[tmpCnt] = RouteECustomerStationNo[i];
						rFoundCustomerNo[tmpCnt] = RouteECustomerNo[i];
						rFoundType[tmpCnt] = RouteECustomerType[i];
						tmpCnt++;
					}    
				}  
				if(routeTmpFlag==true)
				{
					plotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType, rFoundLat, rFoundLng,inci);
				}
			}
			if(routeTmpFlag==false)
			{
				alert("Error! Unable to Find the Evening Route Customers");
				return false;
			}
		}
		
		
		/*rFoundRNumber=[];
		rFoundStationName=[];
		rFoundCustomerNo=[];
		rFoundLat=[];
		rFoundLng=[];
		rFoundType=[];	
		var routeLength=0;
		tmpCnt=0;
		var routeTmpFlag = false;
		if(client_type_combo=="4")
		{				
			routeLength=RouteNECustomer.length;
			alert("routeLength="+routeLength+" search_text="+search_text);
			
			if(routeLength>0)
			{	
				
				for(var i=0;i<routeLength;i++)
				{
					search_text = search_text.trim();
					if(search_text == RouteNECustomer[i].trim())                                        
					{
						routeTmpFlag=true;
						rFoundRNumber[tmpCnt]=RouteNMPlant[i]
						rFoundLat[tmpCnt] = RouteMPlantLat[i];
						rFoundLng[tmpCnt] = RouteMPlantLng[i];
						rFoundStationName[tmpCnt] = RouteMPlantStationNo[i];
						rFoundCustomerNo[tmpCnt] = RouteMPlantNo[i];
						rFoundType[tmpCnt] = RouteMPlantType[i];
					}    
				}  
				if(routeTmpFlag==true)
				{
					plotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType, rFoundLat, rFoundLng);
				}
			}
			if(routeTmpFlag==false)
			{
				alert("Error! Unable to Find the Morning Route Plants");
				return false;
			}
		}
		
		rFoundRNumber=[];
		rFoundStationName=[];
		rFoundCustomerNo=[];
		rFoundLat=[];
		rFoundLng=[];
		rFoundType=[];	
		var routeLength=0;
		tmpCnt=0;
		var routeTmpFlag = false;
		if(client_type_combo=="5")
		{				
			routeLength=RouteNECustomer.length;
			//alert("routeLength="+routeLength+" search_text="+search_text);
			
			if(routeLength>0)
			{	
				
				for(var i=0;i<routeLength;i++)
				{
					search_text = trim(search_text);
					if(search_text == trim(RouteNECustomer[i]))                                        
					{
						routeTmpFlag=true;
						rFoundRNumber[tmpCnt]=RouteNEPlant[i]
						rFoundLat[tmpCnt] = RouteEPlantLat[i];
						rFoundLng[tmpCnt] = RouteEPlantLng[i];
						rFoundStationName[tmpCnt] = RouteEPlantStationNo[i];
						rFoundCustomerNo[tmpCnt] = RouteEPlantNo[i];
						rFoundType[tmpCnt] = RouteEPlantType[i];
					}    
				}  
				if(routeTmpFlag==true)
				{
					plotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType, rFoundLat, rFoundLng);
				}
			}
			if(routeTmpFlag==false)
			{
				alert("Error! Unable to Find the Evening Route Plants");
				return false;
			}
		}*/
	}
	function tmp_search_station(value,inci,customerFileArr) // inc=increatment , i=image
	{  
		//alert("value_1="+value);
		var search_text=value;
		var found_station_name;
		var found_customer_no;
		var found_lat;
		var found_lng;
		var found_type; 

		
		var client_type_combo=document.getElementById('station_chk').value;
		
		var rFoundRNumber=[];
		var rFoundStationName=[];
		var rFoundCustomerNo=[];
		var rFoundLat=[];
		var rFoundLng=[];
		var rFoundType=[];	
		var routeLength=0;
		var tmpCnt=0;
		var routeTmpFlag = false;
	//alert("client_type_combo_up="+client_type_combo);
		if(client_type_combo=="2")
		{	
			//alert("client_type_combo="+client_type_combo);
			routeLength=RouteNMCustomer.length;
			//alert("routeLengthaa="+routeLength+" search_text="+search_text);
			if(parseInt(routeLength)>0)
			{
				//alert("in if");
				for(var i=0;i<routeLength;i++)
				{
					search_text = search_text.trim();
					//alert("route1="+RouteNMCustomer[i]+" route2="+search_text);
					if(search_text == RouteNMCustomer[i].trim())                                        
					{
						routeTmpFlag=true;
						rFoundRNumber[tmpCnt]=RouteNMCustomer[i]
						rFoundLat[tmpCnt] = RouteMCustomerLat[i];
						rFoundLng[tmpCnt] = RouteMCustomerLng[i];
						rFoundStationName[tmpCnt] = RouteMCustomerStationNo[i];
						rFoundCustomerNo[tmpCnt] = RouteMCustomerNo[i];
						rFoundType[tmpCnt] = RouteMCustomerType[i];
						tmpCnt++;
					}    
				}  
				if(routeTmpFlag==true)
				{
					tmpPlotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType, rFoundLat, rFoundLng,inci,customerFileArr);
				}
			}
			if(routeTmpFlag==false)
			{
				alert("Error! Unable to Find the Morning Route Customers");
				return false;
			}
		}
		
		rFoundRNumber=[];
		rFoundStationName=[];
		rFoundCustomerNo=[];
		rFoundLat=[];
		rFoundLng=[];
		rFoundType=[];	
		var routeLength=0;
		tmpCnt=0;
		var routeTmpFlag = false;
		if(client_type_combo=="3")
		{				
			routeLength=RouteNECustomer.length;
			//alert("routeLength="+routeLength+" search_text="+search_text);
			
			if(routeLength>0)
			{
				for(var i=0;i<routeLength;i++)
				{
					search_text = search_text.trim();
					if(search_text == RouteNECustomer[i].trim())                                        
					{
						//alert("routeNo="+RouteNECustomer[i].trim());
						routeTmpFlag=true;
						rFoundRNumber[tmpCnt]=RouteNECustomer[i]
						rFoundLat[tmpCnt] = RouteECustomerLat[i];
						rFoundLng[tmpCnt] = RouteECustomerLng[i];
						rFoundStationName[tmpCnt] = RouteECustomerStationNo[i];
						rFoundCustomerNo[tmpCnt] = RouteECustomerNo[i];
						rFoundType[tmpCnt] = RouteECustomerType[i];
						tmpCnt++;
					}    
				}  
				if(routeTmpFlag==true)
				{
					tmpPlotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType, rFoundLat, rFoundLng,inci,customerFileArr);
				}
			}
			if(routeTmpFlag==false)
			{
				alert("Error! Unable to Find the Evening Route Customers");
				return false;
			}
		}		
	}
	
	function tmpPlotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType,rFoundLat,rFoundLng,inci,customerFileArr)
	{ 
		var latlngbounds = new google.maps.LatLngBounds();
		var icon;
		
		var routeNumberTmp;
		var routeStationNoTmp;
		var routeCustomerNoTmp;
		var routeTypeTmp;
		var routeLatTmp;
		var routeLngTmp;	
		var position;
		var title;
		var f_found_lat=new Array();
		var f_found_lng=new Array();
		var f_halt_duration=new Array();
		var f_vehicle_name=new Array();
		var f_route_no=new Array();
		var f_station_name=new Array();
		var f_customer_no=new Array();
		var f_transpoter_name=new Array();
		var f_ad_at=new Array();
		//alert("plotRoute");
		var latlngbounds = new google.maps.LatLngBounds();
		var tmpCnt=0;
		 var imageAnchor = {
				url: 'images/customer'+inci+'.png',
				// This marker is 20 pixels wide by 32 pixels tall.
				size: new google.maps.Size(20, 32),
				// The origin for this image is 0,0.
				origin: new google.maps.Point(0,0),
				// The anchor for this image is the base of the flagpole at 0,32.
				anchor: new google.maps.Point(14, 22)
			  };

		for(var i=0;i<rFoundLat.length;i++)
		{
			//icon='images/customer'+inci+'.png';
			routeLatTmp=rFoundLat[i];
			routeLngTmp=rFoundLng[i];
			
			position=new google.maps.LatLng(routeLatTmp, routeLngTmp);	
			
			//alert("pos="+position);
			//latlngbounds.extend(position);
			
			title='abc';
			routeNumberTmp=rFoundRNumber[i];
			routeStationNoTmp=rFoundStationName[i];
			routeCustomerNoTmp=rFoundCustomerNo[i];		
			routeTypeTmp=rFoundType[i];		
			
			var marker = new google.maps.Marker
			({
				position: position,	 map: map_canvas, icon: imageAnchor, title:title
			});
			
			latlngbounds.extend(position);
			//alert("marker="+marker);
			
							
			google.maps.event.addListener
			(
				marker, 'click', infoCallbackRoute(routeNumberTmp,routeStationNoTmp,routeCustomerNoTmp,routeTypeTmp,routeLatTmp,routeLngTmp,marker)
			);
			var tmpCRNo=routeCustomerNoTmp+routeNumberTmp;			
			if(customerFileArr[tmpCRNo]==undefined)
			{
				customerMarkers.push(marker);			
			}
			else
			{
				var routeDetail=customerFileArr[tmpCRNo].split(",");
				f_vehicle_name[tmpCnt]=routeDetail[0];
				f_customer_no[tmpCnt]=routeDetail[1];
				f_route_no[tmpCnt]=routeDetail[2];
				f_halt_duration[tmpCnt]=routeDetail[3];
				f_ad_at[tmpCnt]=routeDetail[4];
				f_transpoter_name[tmpCnt]=routeDetail[5];
				f_station_name[tmpCnt]=routeStationNoTmp;				
				f_found_lat[tmpCnt]=rFoundLat[i];
				f_found_lng[tmpCnt]=rFoundLng[i];
				tmpCnt++;
			}
		}
		
		for(var si=1;si<f_ad_at.length;si++)
		{
			var tmp_vname = f_vehicle_name[si];
			var tmp_cno = f_customer_no[si];
			var tmp_ad_at = f_ad_at[si];
			var tmp_rno = f_route_no[si];
			var tmp_hd = f_halt_duration[si];
			var tmp_sname= f_station_name[si];
			var tmp_lat= f_found_lat[si];
			var tmp_lng= f_found_lng[si];
			var zi = si - 1;
			var done = false;
			while(done == false)
			{
				var ad_at_tmp1 = f_ad_at[zi];
				//echo "<br>first=".strtotime($ad_at_tmp1)." ,next=".strtotime($tmp_ad_at);
				if(ad_at_tmp1 > tmp_ad_at)
				{
					f_vehicle_name[zi + 1] = f_vehicle_name[zi];
					f_customer_no[zi + 1] = f_customer_no[zi];
					f_ad_at[zi + 1] = f_ad_at[zi];
					f_route_no[zi + 1] = f_route_no[zi];
					f_halt_duration[zi + 1] = f_halt_duration[zi];
					f_station_name[zi + 1] = f_station_name[zi];
					f_found_lat[zi + 1] = f_found_lat[zi];
					f_found_lng[zi + 1] = f_found_lng[zi];
					
					//////////////////
					zi = zi - 1;
					if (zi < 0)
					{
						done = true;
					}
				}
				else
				{
					done = true;
				}
			} //WHILE CLOSED
		
			f_vehicle_name[zi + 1] = tmp_vname;
			f_customer_no[zi + 1] = tmp_cno;
			f_ad_at[zi + 1] = tmp_ad_at;
			f_route_no[zi + 1] = tmp_rno;
			f_halt_duration[zi + 1] = tmp_hd;
			f_station_name[zi + 1] = tmp_sname;
			f_found_lat[zi + 1] = tmp_lat;
			f_found_lng[zi + 1] = tmp_lng;
		}
		
		for(var i=0;i<f_found_lat.length;i++)
		{
			//icon='images/customer'+inci+'.png';
			position=new google.maps.LatLng(f_found_lat[i], f_found_lng[i]);
			var marker = new google.maps.Marker
			({
				position: position,	 map: map_canvas, icon: imageAnchor, title:title
			});
			
			google.maps.event.addListener
			(
				marker, 'click', infoCallbackRouteFile(f_route_no[i],f_station_name[i],f_customer_no[i],f_vehicle_name[i],f_halt_duration[i],f_found_lat[i],f_found_lng[i],f_transpoter_name[i],marker)
			);
			
			latlngbounds.extend(position);
			//alert("name="+customerFileArr[tmpCRNo])
				var line = new google.maps.Polyline
				({
					path: [new google.maps.LatLng(f_found_lat[i], f_found_lng[i]),new google.maps.LatLng(f_found_lat[i-1], f_found_lng[i-1])],
					strokeColor: lineColor[inci],
					strokeOpacity: 0.5,
					strokeWeight: 1.5
				});
				customerMarkers.push(marker);
				customerMarkers.push(line);						
				line.setMap(map_canvas);
		}
		
		for(var i=1;i<f_found_lat.length;i++)
		{
			lat1 = f_found_lat[i-1];
			lng1 = f_found_lng[i-1];
									
									// alert('lat='+lat1+'lng1='+lng1);

			lat2 = f_found_lat[i];
			lng2 = f_found_lng[i];
									
									//alert('lat2='+lat2+'lng2='+lng2);
									   //var adddd=lat1 + lat2;
									   //alert('adddd='+adddd);
			var yaxis = (parseFloat(lat1) + parseFloat(lat2))/2;
			var xaxis = (parseFloat(lng1) + parseFloat(lng2))/2;
			//alert('yaxis='+yaxis+'xaxis='+xaxis);
			var angle_t = Math.atan( (parseFloat(lat2)-parseFloat(lat1))/(parseFloat(lng2)-parseFloat(lng1)) );
			var angle_deg = 360 * angle_t/(2 * Math.PI);

			if((lng2-lng1)<0)
			{
				angle_deg = 180 + angle_deg;
			}
			else if((lat2-lat1)<0)
			{
				angle_deg = 360 + angle_deg;
			}
			angle_deg = Math.round(angle_deg,0);
			//alert('angle_degree='+angle_deg);
			var image = 
			{
				url: 'images/arrow_images/'+angle_deg+'.png',
				size: new google.maps.Size(21, 21),
				scaledSize: new google.maps.Size(21, 21),							
				anchor: new google.maps.Point(10, 10)
			};
								   // alert('image='+image);
			position=new google.maps.LatLng(yaxis, xaxis);
									 //alert('position='+position);
			var marker1 = new google.maps.Marker
			({
				position: position,
				map: map_canvas,
				icon: image
			});	
			customerMarkers.push(marker1);						
		}
		map_canvas.setCenter(latlngbounds.getCenter());
		map_canvas.fitBounds(latlngbounds);
		//map_canvas.setCenter(latlngbounds.getCenter());
		//map_canvas.fitBounds(latlngbounds);	
		
		
	}
	function plotRoutePlantOrCustomer(rFoundRNumber,rFoundStationName, rFoundCustomerNo, rFoundType,rFoundLat,rFoundLng,inci)
	{ 
		var latlngbounds = new google.maps.LatLngBounds();
		var icon;
		
		var routeNumberTmp;
		var routeStationNoTmp;
		var routeCustomerNoTmp;
		var routeTypeTmp;
		var routeLatTmp;
		var routeLngTmp;	
		var position;
		var title;
		//alert("plotRoute");
		var latlngbounds = new google.maps.LatLngBounds();
		 var imageAnchor = {
				url: 'images/customer'+inci+'.png',
				// This marker is 20 pixels wide by 32 pixels tall.
				size: new google.maps.Size(20, 32),
				// The origin for this image is 0,0.
				origin: new google.maps.Point(0,0),
				// The anchor for this image is the base of the flagpole at 0,32.
				anchor: new google.maps.Point(14, 22)
			  };
		for(var i=0;i<rFoundLat.length;i++)
		{
			//icon='images/customer'+inci+'.png';
			routeLatTmp=rFoundLat[i];
			routeLngTmp=rFoundLng[i];
			
			position=new google.maps.LatLng(routeLatTmp, routeLngTmp);	
			
			//alert("pos="+position);
			//latlngbounds.extend(position);
			
			title='abc';
			routeNumberTmp=rFoundRNumber[i];
			routeStationNoTmp=rFoundStationName[i];
			routeCustomerNoTmp=rFoundCustomerNo[i];		
			routeTypeTmp=rFoundType[i];		
			
			var marker = new google.maps.Marker
			({
				position: position,	 map: map_canvas, icon: imageAnchor, title:title
			});
			
			latlngbounds.extend(position);
			//alert("marker="+marker);
			customerMarkers.push(marker);
							
			google.maps.event.addListener
			(
				marker, 'click', infoCallbackRoute(routeNumberTmp,routeStationNoTmp,routeCustomerNoTmp,routeTypeTmp,routeLatTmp,routeLngTmp,marker)
			);						
		}
		map_canvas.setCenter(latlngbounds.getCenter());
		map_canvas.fitBounds(latlngbounds);
		//map_canvas.setCenter(latlngbounds.getCenter());
		//map_canvas.fitBounds(latlngbounds);	
		
		
	}
	
	function infoCallbackRoute(routeNumberTmp,routeStationNoTmp,routeCustomerNoTmp,routeTypeTmp,routeLatTmp,routeLngTmp,marker)
	{					
		return function() 
		{
			var contentString='';
			var contenttmpstr='';
			if (infowindow) infowindow.close();
			infowindow = new google.maps.InfoWindow();
			var latlng = new google.maps.LatLng(routeLatTmp, routeLngTmp);			
			contentString='<table>'+
			'<tr>'+
			'<td class=\"live_td_css1\">Route Number</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+routeNumberTmp+'</td>'+
		   '</tr>'+
		   '<tr>'+
			'<td class=\"live_td_css1\">Customer Number</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+routeCustomerNoTmp+'</td>'+
		   '</tr>'+	
		   '<tr>'+
			'<td class=\"live_td_css1\">Customer Name</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+routeStationNoTmp+'</td>'+
		   '</tr>'+									
			'</table>'+									
			'<b><font color=black size=2>('+routeLatTmp+','+routeLngTmp+')</font></b>';										
				infowindow.setContent(contentString);
				infowindow.open(map_canvas, marker);		 						
		};
	}
	
	function infoCallbackRouteFile(fl_route_no,fl_station_name,fl_customer_no,fl_vehicle_name,fl_halt_duration,fl_found_lat,fl_found_lng,fl_transpoter_name,marker)
	{
		return function() 
		{
			var contentString='';
			var contenttmpstr='';
			if (infowindow) infowindow.close();
			infowindow = new google.maps.InfoWindow();
			var latlng = new google.maps.LatLng(fl_found_lat, fl_found_lng);			
			contentString='<table>'+
			'<tr>'+
			'<td class=\"live_td_css1\">Route Number</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+fl_route_no+'</td>'+
		   '</tr>'+
		   '<tr>'+
			'<td class=\"live_td_css1\">Customer Number</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+fl_customer_no+'</td>'+
		   '</tr>'+	
		   '<tr>'+
			'<td class=\"live_td_css1\">Vehicle Name</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+fl_vehicle_name+'</td>'+
		   '</tr>'+	
		   '<tr>'+
			'<td class=\"live_td_css1\">Customer Name</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+fl_station_name+'</td>'+
		   '</tr>'+	
		   '<tr>'+
			'<td class=\"live_td_css1\">Transporter Name</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+fl_transpoter_name+'</td>'+
		   '</tr>'+	
			'<tr>'+
			'<td class=\"live_td_css1\">Halt Duration</td>'+
			'<td>:</td>'+
			'<td class=\"live_td_css2\">'+fl_halt_duration+'</td>'+
		   '</tr>'+			   
			'</table>'+									
			'<b><font color=black size=2>('+fl_found_lat+','+fl_found_lng+')</font></b>';										
				infowindow.setContent(contentString);
				infowindow.open(map_canvas, marker);		 						
		};
	}

function search_landmark()
{  
  var search_text = document.getElementById('landmark_search_text').value;
  var found_landmark_name = "", found_point ="", found_marker="";  
  //alert("station_counter="+station_counter+" ,search_text="+search_text);
  
  for(var i=0;i<landmark_counter;i++)
  {
    search_text = trim(search_text);
    landmark_name_list[i] = trim(landmark_name_list[i]);
     
    //alert("search_text="+search_text+" ,landmark_name_list="+landmark_name_list[i]);
    
    if(search_text == landmark_name_list[i])
    {
      //alert("found");
      found_landmark_name = landmark_name_list[i];
      found_point = landmark_point_list[i].split(",");
      found_marker = landmark_marker_list[i];
      break;
    }    
  }
  
  if(found_landmark_name!="")
  {
    //alert("In found_station_name");
   	var lt_1 = found_point[0]; 
  	var ln_1 = found_point[1];
    
    Show_Search_Landmark(lt_1,ln_1, found_landmark_name, found_marker);
  }
}  
  
  //function load(vserial,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval)
  //modified by taseen on 160914
  function load(vserial,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval,flag_play,play_interval)
  {
	//alert("in load");
		//document.getElementById('prepage').style.visibility='visible';
		//Load_Data(vserial,startdate,enddate,pt_for_zoom,zoom_level,status,access,dmode,time_interval);
		Load_Data(vserial,startdate,enddate,pt_for_zoom,zoom_level,status,access,dmode,time_interval,flag_play,play_interval);
	
		GEvent.addListener(map,"click", function(overlay,point) 
		{ 											
			var ltlng;   ////////// for display lat long on click while ltlng set="show"
			if(document.forms[0].latlng.checked == true)
			{
				ltlng = document.forms[0].latlng.value="show";		
			}
			else
			{
				ltlng = document.forms[0].latlng.value="";
			}
					
			if(ltlng=="show")
			{
				var myHtml = "<font size='2' color='#000000'>The GPoint value is: " + map.fromLatLngToDivPixel(point) + "<br>"+point + "<br>" + "<center>at zoom level " + map.getZoom()+"</font></center>";
				map.openInfoWindow(point, myHtml);
			}
		}); //GEvent.addListener closed 
	}

	var browser=navigator.appName;
	var b_version=navigator.appVersion;
	var version=parseFloat(b_version);

	function loadXML(xmlFile)
	{
		//alert("Please wait sending request...");
		
    var xmlhttp=false;
		var status = false;
		var xmlDoc=null;		
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') 
		{
			try
			{
				xmlhttp = new XMLHttpRequest();
			} 
			catch (e)
			{
				xmlhttp=false;
			}
		}
		if (!xmlhttp && window.createRequest)
		{
			try 
			{
				xmlhttp = window.createRequest();
			} 
			catch (e)
			{
				xmlhttp=false;
			}
		}
	  
		//newurl++;
		var d = new Date();
		newurl = d.getTime();
		xmlFile=xmlFile+"?newurl="+newurl; 
		//alert("xml_file="+xml_file);
		xmlhttp.open("GET",xmlFile,false);
		xmlhttp.send(null);
		var finalStr = xmlhttp.responseText	
		//alert("final_str="+finalStr);	
		if (window.DOMParser)
		{
			parser=new DOMParser();
			xmlDoc=parser.parseFromString(finalStr,"text/xml");
		}
		else // Internet Explorer
		{
			try
			{
				xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
				xmlDoc.async="false";
				status = xmlDoc.loadXML(finalStr);
			}
			catch(e1)
			{
			 xmlDoc=null;
			}
			if(status==false)
		    {
				xmlDoc=null;
			}
		}
		  return xmlDoc;
	};	

	function verify()    //for Internet Explorer
	{
		if (xmldoc.readyState != 4)
		{
			return false;
		}
	}

function calculate_distance(lat1, lat2, lon1, lon2) 
{
	lat1 = (lat1/180)*Math.PI;
	lon1 = (lon1/180)*Math.PI;
	lat2 = (lat2/180)*Math.PI;
	lon2 = (lon2/180)*Math.PI;
	
	var delta_lat = lat2 - lat1;
	var delta_lon = lon2 - lon1;
	var temp = Math.pow(Math.sin(delta_lat/2.0),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(delta_lon/2.0),2);
	
	var distance = 3956 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));

	distance = distance*1.609344;
	distance=Math.round(distance*100)/100;
	return distance;
}
/*function calculate_distance(lat1, lat2, lon1, lon2) 
{
	lat1 = (lat1/180)*Math.PI;
	lon1 = (lon1/180)*Math.PI;
	lat2 = (lat2/180)*Math.PI;
	lon2 = (lon2/180)*Math.PI;
	
	var delta_lat = lat2 - lat1;
	var delta_lon = lon2 - lon1;
	var temp = Math.pow(Math.sin(delta_lat/2.0),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(delta_lon/2.0),2);
	
	var distance = 3956 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));

	distance = distance*1.609344;
	distance=Math.round(distance*100)/100;
	return distance;
} */

var dist_array = new Array();

var last_point_arr2 = new Array();
var last_vid_arr2 = new Array();
var last_vehiclename_arr2 = new Array();
var last_speed_arr2 = new Array();
var last_datetime_arr2 = new Array();
//var last_place_arr = new Array();
var last_fuel_level_arr2 = new Array();
var last_fuel_litres_arr2 = new Array();
//var last_vehicletype_arr = new Array();
var last_marker_arr2 = new Array();
var vIcon_arr2 = new Array();


function getxmlData_Track(len2,flag1,lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr,fuel_arr, vehicletype_arr, access,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)
{
	//alert("v00="+vehiclename_arr[0]);
  if(vid_arr.length<=0)
	{
		flag1=0;
	}
	
	if(flag1)
	{
		var point;
		
		if(startup_var == 1)
		{
			var bounds = new GLatLngBounds();

			for(var z=0;z<lat_arr.length;z++)
			{
				point = new GLatLng(parseFloat(lat_arr[z]),
							parseFloat(lng_arr[z]));

				bounds.extend(point); 
			}	
			
			var center = bounds.getCenter(); 
			
			if(len2>1 && len2<6)
				var zoom = map.getBoundsZoomLevel(bounds)-3; 
			else if(len2>6 && len2<16)
				var zoom = map.getBoundsZoomLevel(bounds)-2; 
			else if(len2>16 && len2<25)
				var zoom = map.getBoundsZoomLevel(bounds)-1; 
			else
				var zoom = map.getBoundsZoomLevel(bounds); 	
						
			/*if(access=="Zone")
			{
				show_milestones();		
			}
			else
			{*/
				map.setCenter(center,zoom);
			//}
			
			startup_var = 0;
		}
						
    track_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr,fuel_arr,vehicletype_arr,len2,mm,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr);

		var zoom;
		var event = 0;
		var newzoomlevel=0;
		
		/*var feature_id_map = document.getElementById('station_flag_map').value; 
		alert("fid track:"+feature_id_map);
    if(feature_id_map ==1)
		{
		  getStation1();
    }*/
    
    getLandMark1(event,newzoomlevel);
		
		///////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR TRACK //////////////////////////
		GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
		{			
			var event =1;
			getLandMark1(event,newzoomlevel);
		}); //GEvent addListener		
	}//if flag1 closed				
} //FUNCTION getxmlDataTrack


function track_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr,fuel_arr, vehicletype_arr, len2,mm,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)
{
	var gmarkersA = new Array();      
	var gmarkersB = new Array();    
	var gmarkersC = new Array(); 
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,vehicle_number,speed,point,datetime,place,marker,polyline,last,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8;

	var vehicleserial;
	var vehicletype;
	var vid1=0;
	var vid2=0;
	var pt = new Array();
	var value = new Array();
	var poly = new Array();
	var dist = 0;
	var lastmarker = 0;

	var p = 0;
	var fuel=0;
	
	var mouse_action = document.forms[0].mouse_action.value;

	for (i = 0; i < len2; i++) 
	{			
		vehicleserial = vid_arr[i];
		vehiclename = vehiclename_arr[i];
		vehicle_number = vehiclenumber_arr[i];
		
		//alert("v0"+vehiclename);
    vehicletype = vehicletype_arr[i];		
		speed = speed_arr[i];
		if(speed<=3)
			speed = 0;
		point = new GLatLng(parseFloat(lat_arr[i]),
		parseFloat(lng_arr[i]));
		datetime = datetime_arr[i];	
	
		fuel = fuel_arr[i];	
		io_1=io1_arr[i];
		io_2=io2_arr[i];
		io_3=io3_arr[i];
		io_4=io4_arr[i];
		io_5=io5_arr[i];
		io_6=io6_arr[i];
		io_7=io7_arr[i];
		io_8=io8_arr[i];
		
		pt[i] = point;
		place=0;
		//alert("fuel0="+fuel);
    marker = CreateMarkerTrack(point, vehicleserial, vehiclename, vehicle_number, speed, datetime, dist, fuel, vehicletype, len2, gmarkersC, p, mouse_action,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
		p++;
		gmarkersA.push(marker);
		
		if(i==len2-1)
		{
			var dt =datetime.split(' ');
			var date1 = dt[0].split('-');
			
			var year1 = date1[0];
			var month1 = date1[1];
			var day1 = date1[2];

			var time1 = dt[1].split(':');	
			var hr1 = time1[0];
			var year2 = currentDate.getFullYear();
			var month2 = currentDate.getMonth()+1;	
			var day2 = currentDate.getDate();
			var hr2 = currentDate.getHours();

			if(month2<10)
			{
			month2="0"+month2;
			}
			
			if(hr2<10)
			{
			hr2="0"+hr2;	
			}

			if( (year1==year2)&&(month1==month2)&&(day1==day2)&&(hr1==hr2) )
			{
  			last = 1;
  			lastmarker = new PdMarker(pt[i],iconCurrent);
  			//alert("Current marker="+lastmarker);
    	  if(document.forms[0].m4.value == 1)
        {			
          lastmarker.blink(true,150);
        }
  			map.addOverlay(lastmarker);
			}			
			
			//////////***************** PLOT ALL TRACK MARKERS OF ALL VEHICLES *********//////////
			for(var m=0;m<gmarkersA.length;m++)
				map.addOverlay(gmarkersA[m]);

			/*if(last==1)
			{
				map.addOverlay(lastmarker);
			}*/
			
			document.getElementById('prepage').style.visibility='hidden';	
			////////////////////////////////////////////////////////////////
		}

		/*if(j==7)
		{
			j=0;
			dist = 0;
		}	*/
		
		if(i>=0&&i<=len2-1)
		{
			vid1 = vid_arr[i];
		}
		if(i>=0&&i<=len2-2)
		{
			vid2 = vid_arr[i+1];	
		}
		//if(vid1 == vid2)  ////// CHECK FOR SAME VEHICLE
		//{																					
			if( (i>=0)&&(i<=len2-2))
			{
				polyline = new GPolyline([
				new GLatLng(parseFloat(lat_arr[i]),parseFloat(lng_arr[i])),
				new GLatLng(parseFloat(lat_arr[i+1]),
				parseFloat(lng_arr[i+1]))], '#FF0000', 3,1);	
				map.addOverlay(polyline);
			}			
				value[i] = polyline.getLength();
				value[i] = value[i] / 1000;
				//var distance = Math.round(value[i]*100)/100; 
				dist = dist + value[i];
				dist = Math.round(dist*100)/100;																		
				
				var pt1 = new Array();
				var pt2 = new Array();

				var pt1 = new GLatLng(parseFloat(lat_arr[i]),
				parseFloat(lng_arr[i]));

			if(i>=0&&i<=len2-2)
			{
				var pt2 = new GLatLng(parseFloat(lat_arr[i+1]),
				parseFloat(lng_arr[i+1]));
			}

				var lt1 = pt1.y;
				var lng1 = pt1.x;

				var lt2 = pt2.y;
				var lng2 = pt2.x;				
	} //for i loop closed	
} //track markers closed

var pt = new Array();
var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
var vname = new Array();
//var gmarkersC;
var mm;	
var ew;


function CreateMarkerTrack(point, imei, vehiclename, vehicle_number, speed, datetime, dist, fuel, vehicletype, len2, gmarkersC, p, mouse_action,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
{	
	//alert("In createmtrack="+vehiclename);
  /*if(p==0)
	{
		mm = new GMarkerManager(map, {borderPadding:1});
	}*/

	//alert("p outside condition="+p);
	
	pt[p] = point;
	vname[p] = vehiclename;

	if(p>0&&(vname[p]==vname[p-1]))
	{		
		//alert("p Inside condition="+p);
		
		lat1 = pt[p-1].y;
		lng1 = pt[p-1].x;

		lat2 = pt[p].y;
		lng2 = pt[p].x;	

		var yaxis = (lat1 + lat2)/2;
		var xaxis = (lng1 + lng2)/2;
				
		coord = new GLatLng(yaxis,xaxis);
		var angle_t = Math.atan( (lat2-lat1)/(lng2-lng1) );
		var angle_deg = 360 * angle_t/(2 * Math.PI);

		if((lng2-lng1)<0)
		{
				angle_deg = 180 + angle_deg;
		}
		else if((lat2-lat1)<0)
		{
				angle_deg = 360 + angle_deg;
		}

		angle_deg = Math.round(angle_deg,0);
		var IconArrow = new GIcon(); 
		
		//var a=
		//alert("val="+a);
		IconArrow.image = "images/arrow_images/"+angle_deg+'.png';
		IconArrow.iconSize = new GSize(20, 19);
		IconArrow.iconAnchor = new GPoint(10, 10);

		var marker2 = new GMarker(coord, IconArrow);
		gmarkersC.push(marker2);
		//alert("(before p=len2-1), p="+p+" len2="+len2+" coord="+coord+" angle_deg="+angle_deg);
		
		if(p == len2-1)
		{
			//alert("inside p==len2-1");
			//mm.addMarkers(gmarkersC,0,17);
			//mm.refresh();

			for(var m=0;m<gmarkersC.length;m++)
				map.addOverlay(gmarkersC[m]);
		}		
	}	
	
	var lt_1 = Math.round(point.y*100000)/100000; 
	var ln_1 = Math.round(point.x*100000)/100000;

	//alert("p="+p+" len2="+len2);
	if(p==0)
	{
		var Icon= new GIcon(startIcon);
	}
	else if(p == len2-1)
	{
		var Icon= new GIcon(stopIcon);
	}
	else
	{
    var one,two,three;
    var valid_marker = 0;
    
    if(document.forms[0].m1.value == 1)
    {            
      //if(document.forms[0].mtest.value=="marker")
      //var one = "1";        
      if(speed>1&&speed<=20)
      {
		    var Icon= new GIcon(iconYellow);
        valid_marker = 1;
      }	  
		}
    
    if(document.forms[0].m2.value == 1)
    {		      
      //if(document.forms[0].mtest.value=="marker")
      //var two = "1"; 
      
      if(speed>20)
      {
		    var Icon= new GIcon(iconGreen);
		    valid_marker = 1;
      }	    
		}

		if(document.forms[0].m3.value == 1)
    {
      //if(document.forms[0].mtest.value=="marker")
      //var three = "1"; 
      if(speed<1)
      {
		    var Icon= new GIcon(iconRed);
		    valid_marker = 1;
      }             
		}
		
		if((document.forms[0].m1.value == 2) && (document.forms[0].m2.value == 2) && (document.forms[0].m3.value == 2) && (document.forms[0].m4.value == 2))
    {
		    var Icon= new GIcon(iconYellow);
		    valid_marker = 1;           
		}		

    if(valid_marker == 0)
    {
      //alert("invalid marker");      
      var Icon= new GIcon(iconDot);
    }
	}
		
	var marker;	
	marker = new GMarker(point, Icon);
	var action_marker;
		
	var Icon2= new GIcon(iconGreen);
	Icon2.image = 'green_Marker1.png';
	Icon2.iconSize = new GSize(14, 22);
	Icon2.iconAnchor = new GPoint(6, 20);
	Icon2.infoWindowAnchor = new GPoint(5, 1);
	action_marker = new GMarker(point, Icon2);

	startdate = document.thisform.start_date.value;
	enddate = document.thisform.start_date.value;
	
	if(document.thisform.GEarthStatus.value == 1)
	{	
		//alert("in if");	
				//GEvent.addListener(marker, 'click', function()
		GEvent.addListener(marker, mouse_action, function()
		{
			//place = "-";						
			//alert("point="+point+"icon="+Icon+"marker="+marker+" fuel_litres="+fuel_litres+ " fuel_level="+fuel_level+"rad_but="+rad_but+"veiclename="+vehiclename+"speed="+speed+"datetime="+datetime);
			//alert("FUEL="+fuel);
			PlotTrackMarkerWithAddress(point, Icon, marker, imei, vehiclename, vehicle_number, speed,datetime, dist, fuel, vehicletype,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
			//PlotTrackMarkerWithAddress1(point, Icon, marker, vehiclename, speed,datetime, dist,fuel_litres, fuel_level,rad_but);
			map.addOverlay(action_marker);

		//alert("action_marker in mouseover="+action_marker);
		});	
	}
	
	else
	{	
		//alert("in else");	
		//GEvent.addListener(marker, 'mouseover', function()
    GEvent.addListener(marker, mouse_action, function()
		{
			//alert("point="+point+"icon="+Icon+"marker="+marker+" fuel_litres="+fuel_litres+ " fuel_level="+fuel_level+"rad_but="+rad_but+"veiclename="+vehiclename+"speed="+speed+"datetime="+datetime);
			//alert("FUEL="+fuel);
      PlotTrackMarkerWithAddress(point, Icon, marker, imei, vehiclename, vehicle_number, speed, datetime, dist, fuel, vehicletype,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
			//PlotTrackMarkerWithAddress1(point, Icon, marker, vehiclename, speed,datetime, dist,fuel_litres, fuel_level,rad_but);
			map.addOverlay(action_marker);

		//alert("action_marker in mouseover="+action_marker);
		});	
	}	
	
	//alert("action_marker="+action_marker);

	GEvent.addListener(action_marker, 'mouseout', function() {				
		//alert("action_marker in mouseout"+action_marker);		
		map.removeOverlay(action_marker);
	////////////////////////////////////////////////////////////////////////////////
	});	
	///////////////////////////MOUSE OUT CLOSED/////////////////////////////////
	
	//alert("marker="+marker);
	return marker;		
}				
////////info landmark window table//////////////

function pretty(a) 
{
	return '<table border="0" cellpadding="0" cellspacing="0"><tr><td width="100%" class="EWTitle" nowrap>' + a + '</td></tr>' +
		   '<tr><td nowrap></td></tr></table>';
}


////////******** SHOW LANDMARK MARKER
function ShowLandmark(point, landmark) 
{		
	//alert("pt="+point);
	//alert("lnmrk="+landmark);

	var Icon= new GIcon(lnmark);
	//alert('icon='+Icon);

	var marker = new GMarker(point,Icon);
	var marker2 = new GMarker(point,Icon);

	var lat = Math.round((point.y)*100000)/100000;
	var lng = Math.round((point.x)*100000)/100000;

	//var iwform = pretty('<center>LANDMARK <br>'
  var iwform = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>LANDMARK</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=2><b>'+landmark + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';

	//alert("iwform="+iwform);
	
	/*GEvent.addListener(marker, 'click', function() {
	marker.openInfoWindowHtml(iwform);				
	});*/
        
	// ========== Open the EWindow instead of a Google Info Window ==========

	if(document.thisform.GEarthStatus.value == 1)
	{		
		GEvent.addListener(marker, "click", function() {
		//ew.openOnMarker(marker,iwform);

		///////////////////////MINI MAP CODE////////////////////
		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><font color=#000000'+iwform+'</font></div>');
				
		//var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');
		var infoTabs = [tab1];
		marker.openInfoWindowTabsHtml(infoTabs);

		/*var dMapDiv = document.getElementById("detailmap");
		var detailMap = new GMap2(dMapDiv);
		detailMap.setCenter(point , 12);

		detailMap.removeMapType(G_SATELLITE_MAP);																

		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
		detailMap.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		detailMap.addControl(mapControl, topRight);

		var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,30));
		var mapControl2 = new GSmallMapControl();
		//detailMap.addControl(new GSmallMapControl());
		GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
		GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
		detailMap.addControl(mapControl2, topLeft);

		var CopyrightDiv = dMapDiv.firstChild.nextSibling;
		var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
		CopyrightDiv.style.display = "none"; 
		CopyrightImg.style.display = "none";
		detailMap.addOverlay(marker2);

		showMinimapRect(detailMap,point);  */
	///////////////////////////////////////////////////////////////
		});     
	}	
	else
	{
		GEvent.addListener(marker, "mouseover", function() {
		//ew.openOnMarker(marker,iwform);

		///////////////////////MINI MAP CODE////////////////////
		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><font color=#000000'+iwform+'</font></div>');
		
		//var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');
		//var infoTabs = [tab1,tab2];
		var infoTabs = [tab1];
		marker.openInfoWindowTabsHtml(infoTabs);

		/*var dMapDiv = document.getElementById("detailmap");
		var detailMap = new GMap2(dMapDiv);
		detailMap.setCenter(point , 12);

		detailMap.removeMapType(G_SATELLITE_MAP);																

		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
		detailMap.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		detailMap.addControl(mapControl, topRight);

		var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,30));
		var mapControl2 = new GSmallMapControl();
		//detailMap.addControl(new GSmallMapControl());
		GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
		GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
		detailMap.addControl(mapControl2, topLeft);

		var CopyrightDiv = dMapDiv.firstChild.nextSibling;
		var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
		CopyrightDiv.style.display = "none"; 
		CopyrightImg.style.display = "none";
		detailMap.addOverlay(marker2);

		showMinimapRect(detailMap,point);   */
	 ///////////////////////////////////////////////////////////////
		});     
	}

	/*
   // ========== Close the EWindow if theres a map click ==========
	GEvent.addListener(map, "click", function(overlay,point) {
		if (!overlay) {
		  ew.hide();
		}
	});  */

	return marker;
}
//////SHOW LANMARK CLOSED


////*** SHOW STATION 

///////////SHOW STATION WINDOW MARKER////////////////

function ShowStation(point, station, customer, type) 
{		
	var Icon= new GIcon(station_icon);
	var marker_local = new GMarker(point,Icon);

	var lat = Math.round((point.y)*100000)/100000;
	var lng = Math.round((point.x)*100000)/100000;

	var type_name;
	
	if(type==0)
	{
		type_name = "CUSTOMER";
	}
	else
	{
		type_name = "PLANT";
	}

  var iwform = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>STATION</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2><b>'+station + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>CUSTOMER</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=2><b>'+customer + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Type</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=2><b>'+type_name+ '</b></font></td><td></td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';
 
	// ========== Open the EWindow instead of a Google Info Window ==========
	GEvent.addListener(marker, "mouseover", function() {
	//ew.openOnMarker(marker,iwform);

	///////////////////////MINI MAP CODE////////////////////
	var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><font color=#000000'+iwform+'</font></div>');

	var infoTabs = [tab1];
	marker.openInfoWindowTabsHtml(infoTabs);

	/*var dMapDiv = document.getElementById("detailmap");
	var detailMap = new GMap2(dMapDiv);
	detailMap.setCenter(point , 12);

	detailMap.removeMapType(G_SATELLITE_MAP);																

	var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
	detailMap.addMapType(G_SATELLITE_MAP);
	var mapControl = new GMapTypeControl();
	detailMap.addControl(mapControl, topRight);

	var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,30));
	var mapControl2 = new GSmallMapControl();
	//detailMap.addControl(new GSmallMapControl());
	GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
	GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
	detailMap.addControl(mapControl2, topLeft);

	var CopyrightDiv = dMapDiv.firstChild.nextSibling;
	var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
	CopyrightDiv.style.display = "none"; 
	CopyrightImg.style.display = "none";
	detailMap.addOverlay(marker2);

	//showMinimapRect(detailMap,point);     */
	});     

	
  /* // ========== Close the EWindow if theres a map click ==========
	GEvent.addListener(map, "click", function(overlay,point) {
		if (!overlay) {
		  ew.hide();
		}
	});*/ 

	return marker;
}
/// *** SHOW STATION CLOSED


function Show_Search_Station(lt_1, ln_1, station, customer, type)  
{
	//map.clearOverlays();
	var icon1 = 'images/station.png';
	point=new google.maps.LatLng(lt_1, ln_1);
	//alert("point="+point);	
	var lat = Math.round((lt_1)*100000)/100000;
	var lng = Math.round((ln_1)*100000)/100000;
	var type_name;	
	if(type==0)
	{
		type_name = "CUSTOMER";
	}
	else
	{
		type_name = "PLANT";
	}
	var contentString = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>STATION</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2><b>'+station + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>CUSTOMER</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=2><b>'+customer + '</b></font></td><td></td></tr><tr><td><font size=2 color=#000000><b>Type</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=2><b>'+type_name+ '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';
	//alert("contentString="+contentString);
		//alert("map="+map_canvas);
	var marker1 = new google.maps.Marker({
      position: point,
      map: map_canvas,
	  icon: icon1,
      title: 'Uluru (Ayers Rock)'
  });	
  //alert("marker1="+marker1);
	var infowindow1 = new google.maps.InfoWindow({
      content: contentString
  }); 
infowindow1.open(map_canvas,marker1);  
  google.maps.event.addListener(marker1, 'click', function() {
    infowindow1.open(map_canvas,marker1);
  });	
}	
/// *** SHOW STATION CLOSED


function Show_Search_Landmark(lt_1, ln_1, landmark, marker)  
{
	var point=new google.maps.LatLng(lt_1, ln_1);
	//alert("point="+point);	
	var lat = Math.round((lt_1)*100000)/100000;
	var lng = Math.round((ln_1)*100000)/100000;
	var contentString = '<table bgcolor="#EEEFF0" border="0"><tr><td>Landmark Name</td><td>:</td><td>'+landmark+'</td></tr><tr><td>Coordinates</td><td>:</td><td>'+lat+','+lng+'</td></tr></table>';
	//alert("contentString="+contentString);
		//alert("map="+map_canvas);
	/*var marker1 = new google.maps.Marker({
      position: point,
      map: map_canvas,
      title: 'Uluru (Ayers Rock)'
  });*/	
  //alert("marker1="+marker1);
	var infowindow1 = new google.maps.InfoWindow({
      content: contentString
  }); 
infowindow1.setPosition(point)
    infowindow1.open(map_canvas)  
    // infowindow1.open(map_canvas,marker1);
  /*google.maps.event.addListener(marker1, 'click', function() {
    infowindow1.open(map_canvas,marker1);
  });*/	
	
	
}

var last_point_arr = new Array();
var last_vid_arr = new Array();
var last_vehiclename_arr = new Array();
var last_speed_arr = new Array();
var last_datetime_arr = new Array();
//var last_place_arr = new Array();
var last_fuel_level_arr = new Array();
var last_fuel_litres_arr = new Array();
//var last_vehicletype_arr = new Array();
var last_marker_arr = new Array();
var vIcon_arr = new Array();
var counter;
counter=0;

///////////////////////////////// LAST POSITION DATA ////////////////////////////////////////////////

//function Load_Data(vserial,startdate,enddate,pt_for_zoom,zoom_level,status,access,dmode,time_interval)
function Load_Data(vserial,startdate,enddate,pt_for_zoom,zoom_level,status,access,dmode,time_interval,flag_play,play_interval)
{    
  //alert("vid="+vid+" startdate="+startdate+" endate="+enddate+" pt_for_zoom="+pt_for_zoom+" access="+access+ " dmode="+dmode+" time_interval="+time_interval);
  //////// GET CURRENT DATE  /////////////////
  deleteOverlays(); 
  var d1 = new Date();			// CURRENT DATE IN MILLISECONDS
  var curr_date = d1.getDate();
  var curr_month = d1.getMonth();
  var curr_year = d1.getFullYear();
  var current_date;
  
  var month_tmp;
  var day_tmp;

	if(curr_month<9)
	{
		curr_month = curr_month+1;
		month_tmp = "0"+curr_month;
	}
	else
	{
		curr_month = curr_month+1;
		month_tmp = curr_month;
	}

	if(curr_date<10)
		day_tmp = "0"+curr_date;
	else
		day_tmp = curr_date;

	current_date = curr_year+"-"+month_tmp+"-"+day_tmp;
	var current_dtstr =	curr_year+"/"+month_tmp+"/"+day_tmp+" 23:59:59"; // USE THIS VARIABLE FOR 10 DAYS CONDITION IF ANY
	///////////////////////////////////////////////////	

	var dateopt = document.thisform.last_dateopt.value;

	if(dateopt==2)
	{		
		//alert("in date1 condition=");
		////////// GET DAY 10 DAYS BEFORE  /////////////////////////////////////
		var curr_timestamp = d1.getTime();			// = Current timestamp in milliseconds	

		var secs_oneday = 24*60*60;				// = 86400 secs
		var secs_tendays_before = 86400 * 10	// = 864000 secs
		var prev_timestamp = secs_tendays_before * 1000		// = 10 Days before in MilliSeconds 

		//alert("d2_tmp="+d2_tmp);

		var diff = curr_timestamp - prev_timestamp;
		
		var d2 = new Date(diff);

		var last_date = d2.getDate();
		var last_month = d2.getMonth();
		var last_year = d2.getFullYear();
		var last_datestr;

		var month_tmp2;
		var day_tmp2;

		if(last_month<10)
		{
			last_month = last_month+1
			month_tmp2 = "0"+last_month;
		}
		else
		{
			last_month = last_month+1
			month_tmp2 = last_month;
		}

		if(last_date<10)
			day_tmp2 = "0"+last_date;
		else
			day_tmp2 = last_date;

		last_datestr = last_year+"/"+month_tmp2+"/"+day_tmp2+ " 00:00:00";
		// DECLARE NEW START AND END DATE
		date1 = last_datestr;
		startdate = last_datestr;

		date2 = current_dtstr;
		enddate = current_dtstr;
	}


	   			
  		//alert(document.getElementById('station_chk').checked);
      var feature_id_map = document.getElementById('station_flag_map').value;            
      if(feature_id_map == 1)
      {      
        //document.getElementById('station_chk').checked = false;
      }
      //alert("in GBrowserIsCompatible")
  		//map.clearOverlays();	
  		//alert('user_date='+user_dates.length+'vehicleserial='+vehicleSerial);
  		if(vserial!=null)
  		{
  		 //alert('check');
        var date = new Date();
        // COPY ORIGINAL XML FILE        
		    var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
		    //alert("dest="+dest);
        //var dest = "src/php/xml_tmp/filtered_xml/tmp_1296469048456.xml";
        thisdest = dest;
        thismode = dmode;
        thisaccess = access;
        //var dest = "xml_tmp/filtered_xml/tmp_1295185453465.xml" ;
        // alert("d="+dest);        
        
        // MAKE FILTERED COPY        
        var poststr = "xml_file=" + encodeURI( dest )+
                "&mode=" + encodeURI( dmode )+
				"&home_report_type=map_report"+
                "&vserial=" + encodeURI( vserial )+
                "&startdate=" + encodeURI( startdate )+
                "&enddate=" + encodeURI( enddate )+ 
				"&flag_play=" + encodeURI(flag_play) +
				"&play_interval=" + encodeURI(play_interval) +
                "&time_interval=" + encodeURI(time_interval);
		//alert("poststr="+poststr);
				$.ajax({
		type: "POST",
		url:'src/php/get_route_filtered_xml.php',
		data: poststr,
		success: function(response){
		//console.log(response);
		//alert("response="+response);
		document.getElementById('dummy_div').style.display='none';
		//document.getElementById('dummy_div').style.display='';
		//document.getElementById('map_home').innerHTML=response;
		$("#dummy_div").html(response);
		
		/*var account_id_lo = "<?php echo $_SESSION['account_id']; ?>";
		
		if(account_id_lo=='2')
		{
			alert("response="+response);
		}*/
		document.getElementById('prepage').style.visibility='hidden';
		// document.getElementById('dummy_div').innerHTML=responsedatas;
		},
		error: function()
		{
		alert('An unexpected error has occurred! Please try later.');
		}
		});
				
        //alert("poststr="+poststr);                                          
        //var str = "src/php/get_filtered_xml.php";
        //var exists = isFile(str);
        //alert("exists="+exists);
		  /*  makePOSTRequestMap('src/php/get_filtered_xml.php', poststr);			
			  thisdest = "./../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
        TryCnt =0;
        clearTimeout(timer);
        timer = setTimeout('displayInfo()',1000);*/	 
      } // if vid closed
    function deleteOverlays() 
	{
		for (var i = 0; i < markers.length; i++) 
		{
			markers[i].setMap(null);
		}
	}

} //function load1 closed


function isFile(str){
var O= AJ();
if(!O) return false;
try
{
O.open("HEAD", str, false);
O.send(null);
return (O.status==200) ? true : false;
}
catch(er)
{
return false;
}
}
function AJ()
{
var obj;
if (window.XMLHttpRequest)
{
obj= new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
try
{
obj= new ActiveXObject('MSXML2.XMLHTTP.3.0');
}
catch(er)
{
obj=false;
}
}
return obj;
}


function displayInfo()
{
	var lat_arr = new Array();
	var lng_arr = new Array();
	var vid_arr = new Array();
	var vehiclename_arr = new Array();
	var vehiclenumber_arr = new Array();
	var speed_arr = new Array();
	var datetime_arr = new Array();
	var place_arr = new Array();
	var fuel_arr = new Array();
	var day_max_speed_arr = new Array();
	var day_max_speed_time_arr = new Array();
	var last_halt_time_arr = new Array();
	var vehicletype_arr = new Array();
	var Final_DateTime=new Array();  
	var xml_data; 
	var DataReceived = false;  
	var io1_arr=new Array(); 
	var io2_arr=new Array(); 
	var io3_arr=new Array(); 
	var io4_arr=new Array(); 
	var io5_arr=new Array(); 
	var io6_arr=new Array(); 
	var io7_arr=new Array(); 
	var io8_arr=new Array(); 
	try
	{
		var bname = navigator.appName;
		  
		/*if (bname == "Microsoft Internet Explorer")
		{
		alert("Wait for data, please use Mozilla for better compatibility");
		}//alert(bname);   */
			  
		var xmlObj = null;        
		//alert("thisdest="+thisdest);
		xmlObj = loadXML(thisdest);

		//alert("xmObj="+xmlObj);    
		if (bname == "Microsoft Internet Explorer")
		{
			//alert("In IE:"+xmlObj);
			if(xmlObj!=null)	
			{                                      
				xml_data = xmlObj.documentElement.getElementsByTagName("x");
				DataReceived = true;
			} 
			else
			{
				if(TryCnt<=MAX_TIMELIMIT)
				{
				  TryCnt++;
				  clearTimeout(timer);
				  timer = setTimeout('displayInfo()',1000);
				}
			}                                
		}
		else
		{
			//alert("In Mozilla");
			xml_data = xmlObj.documentElement.getElementsByTagName("x");
			//alert("length 1:"+xml_data.length+" xml_obj="+xml_data);
			//var xml_data1 = xmlObj.getElementsByTagName("t1");
			var xml_data1 = xmlObj.documentElement.getElementsByTagName("a1");
			//alert("length 2:"+xml_data1.length);
			if(xml_data1.length>0)
			{
				//alert("A");
				DataReceived = true;
			}
			else
			{
				//alert("B:"+TryCnt);
				if(TryCnt<=MAX_TIMELIMIT)
				{
				  TryCnt++;
				  clearTimeout(timer);
				  timer = setTimeout('displayInfo()',1000);
				}
			}
		}   
		//alert("xml_data="+xml_data);             
	}
	catch(err)
	{
		alert("sorry! unable to get marker information");
	}	
    
	/*if (bname == "Microsoft Internet Explorer")
	{
	alert("Data Received");
	}	*/								
				
	if((((xml_data.length==0) || (xml_data.length==undefined)) && (DataReceived==true)) || (TryCnt>=MAX_TIMELIMIT))
	{	
		alert("No Data Found");
		document.getElementById('prepage').style.visibility='hidden';	
		clearTimeout(timer);	
		var poststr = "dest=" + encodeURI( thisdest );
		makePOSTRequestMap('src/php/del_xml.php', poststr);								
	}
	else  if(DataReceived==true)
	{
		//alert("in else if");
		clearTimeout(timer);
		var len2=0;
			
		/////////////// GET IO ///////////////
		var imei = xml_data[0].getAttribute("v")		
		//var str = imei+",temperature"; 
         
		/* //// GET VNAME //////////////
		var strURL="src/php/map_get_vname.php?imei="+imei;
		//alert("strurl:"+strURL);
		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);  
		var vname = req.responseText; 
				 
		////GET IO /////////////////
		strURL="src/php/map_get_io.php?content="+str;
		//alert("strurl:"+strURL);
		req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);  
		var io = req.responseText; 
		//var io="io8";
		//alert("io1="+io); 

		if(io=="")
		  io="io8"; */      
		//alert("io2="+io);   
				   
		//alert("io2="+io); 
		/////////////////////////////////////////////		
			
		var firstdata_flag = 0, last_time1 = 0, current_time =0.0, date1tmp = "", distance=0, distance1=0, tmp_speed=0.0;
		var lat="", lng ="", lat1="", lat2="", lng1="", lng2="", latlast="", lnglast="", last_time=0.0, tmp_time_diff=0, tmp_time_diff1=0;
	
		for (var k = 0; k < xml_data.length; k++) 
		{																													
			//alert("t11111111==="+xml_data[k].getAttribute("datetime"));						
			lat = xml_data[k].getAttribute("d");
			lng = xml_data[k].getAttribute("e");				
			datetime =  xml_data[k].getAttribute("h");
			//alert("lat="+lat+" lng="+lng+" datetime="+datetime);
			
			lat = lat.substring(0, lat.length - 1);
			lng = lng.substring(0, lng.length - 1);
			
			if(thismode==2)
			{
				if(firstdata_flag==0)
				{					
					//alert("FirstFlag Zero");
					//######################### STORE FIRST DATA ############################/				
					lat_arr[len2] = lat;
					lng_arr[len2] = lng;
					datetime_arr[len2] =  datetime;
					vid_arr[len2] = xml_data[k].getAttribute("v");
					vehiclename_arr[len2] = xml_data[k].getAttribute("w");
					vehiclenumber_arr[len2] = xml_data[k].getAttribute("x");
					
					//alert("v000="+vehiclename_arr[len2] );
					speed_arr[len2] = Math.round(xml_data[k].getAttribute("f")*100)/100;
					if( (speed_arr[len2]<=3) || (speed_arr[len2]>200))
					{
						speed_arr[len2] = 0;
					}
					
					// fuel_arr[len2] = xml_data[k].getAttribute(io);	
					io1_arr[len2]=xml_data[k].getAttribute("i");
					io2_arr[len2]=xml_data[k].getAttribute("j");
					io3_arr[len2]=xml_data[k].getAttribute("k");
					io4_arr[len2]=xml_data[k].getAttribute("l");
					io5_arr[len2]=xml_data[k].getAttribute("m");
					io6_arr[len2]=xml_data[k].getAttribute("n");
					io7_arr[len2]=xml_data[k].getAttribute("o");
					io8_arr[len2]=xml_data[k].getAttribute("p");
					/*if(fuel_arr[len2] <30)
					{
						fuel_arr[len2] =0;
					}*/											
					vehicletype_arr[len2] = xml_data[k].getAttribute("y");
					//alert("vid_arr="+vid_arr+" vehiclename_arr="+vehiclename_arr+" vehiclenumber_arr="+vehiclenumber_arr);
								
					if(label_type1!="Person")
					{
						day_max_speed_arr[len2] =  xml_data[k].getAttribute("s");
						day_max_speed_time_arr[len2] =  xml_data[k].getAttribute("t");                         
						last_halt_time_arr[len2] =  xml_data[k].getAttribute("u");
					}
					//alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
					len2++;						
					//#################### FIRST DATA CLOSED ##########################/
			
					firstdata_flag = 1;

					lat1 = lat;
					lng1 = lng;
						
					var date1tmp = datetime.replace(/-/g,"/");
					var previous_time = new Date(date1tmp); //yyyy-mm-dd format    
					last_time = (previous_time.getTime())/(1000);

					latlast = lat;
					lnglast = lng;
				}           	          	
				else
				{                           					
					var date2tmp = datetime.replace(/-/g,"/");
					var next_time = new Date(date2tmp); //yyyy-mm-dd format    
					current_time = (next_time.getTime())/(1000);
					
					tmp_time_diff = (parseFloat(current_time) - parseFloat(last_time)) / 3600;             
					//alert("current_time="+current_time+" ,last_time1="+last_time1+" ,tmp_time_diff="+tmp_time_diff1);
							
					var distance1 = calculate_distance(latlast, lat, lnglast, lng);
					//alert("latlast="+latlast+" ,lnglast="+lnglast+" ,lat2="+lat+" ,lng2="+lng);
								
					if(tmp_time_diff>0)
					{
						tmp_speed = (parseFloat(distance1)) / tmp_time_diff;        
					}		
																 
					//alert("distance1="+distance1+" ,tmp_time_diff="+tmp_time_diff+" ,tmp_speed="+tmp_speed+" ,distance="+distance+" ,current_time="+$current_time+" ,last_time="+$last_time);
				
					if(((tmp_speed<250.0) || (tmp_time_diff>0.1))&& (tmp_time_diff>0.0))
					{
						latlast = lat;
						lnglast = lng;
						last_time = current_time;			         														

						//alert("IN NEXT");
						//alert("tmp_speed3="+tmp_speed+" ,distance3="+distance+" ,tmp_time_diff3="+tmp_time_diff);
						//######################### STORE NEXT DATA ############################/				
						lat_arr[len2] = lat;
						lng_arr[len2] = lng;
						datetime_arr[len2] =  datetime;
						vid_arr[len2] = xml_data[k].getAttribute("v");
						vehiclename_arr[len2] = xml_data[k].getAttribute("w");
						vehiclenumber_arr[len2] = xml_data[k].getAttribute("x");
					
						//alert("v000="+vehiclename_arr[len2] );
						speed_arr[len2] = Math.round(xml_data[k].getAttribute("f")*100)/100;
						if( (speed_arr[len2]<=3) || (speed_arr[len2]>200))
						{
							speed_arr[len2] = 0;
						}
					
						// fuel_arr[len2] = xml_data[k].getAttribute(io);	
						io1_arr[len2]=xml_data[k].getAttribute("i");
						io2_arr[len2]=xml_data[k].getAttribute("j");
						io3_arr[len2]=xml_data[k].getAttribute("k");
						io4_arr[len2]=xml_data[k].getAttribute("l");
						io5_arr[len2]=xml_data[k].getAttribute("m");
						io6_arr[len2]=xml_data[k].getAttribute("n");
						io7_arr[len2]=xml_data[k].getAttribute("o");
						io8_arr[len2]=xml_data[k].getAttribute("p");
						/*if(fuel_arr[len2] <30)
						{
							fuel_arr[len2] =0;
						}*/											
						vehicletype_arr[len2] = xml_data[k].getAttribute("y");
								
						if(label_type1!="Person")
						{
							day_max_speed_arr[len2] =  xml_data[k].getAttribute("s");
							day_max_speed_time_arr[len2] =  xml_data[k].getAttribute("t");                         
							last_halt_time_arr[len2] =  xml_data[k].getAttribute("u");
						}
						//alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
						len2++;						
						//#################### NEXT DATA CLOSED ##########################/
			  
						//alert("Valid points");
					}
				}                  
			} //IF MODE=2 CLOSED
			else
			{
				lat_arr[len2] = lat;
				lng_arr[len2] = lng;
				datetime_arr[len2] =  datetime;
				vid_arr[len2] = xml_data[k].getAttribute("v");
				vehiclename_arr[len2] = xml_data[k].getAttribute("w");
				vehiclenumber_arr[len2] = xml_data[k].getAttribute("x");
				
				//alert("v000="+vehiclename_arr[len2] );
				speed_arr[len2] = Math.round(xml_data[k].getAttribute("f")*100)/100;
				if( (speed_arr[len2]<=3) || (speed_arr[len2]>200))
				{
					speed_arr[len2] = 0;
				}
				
				// fuel_arr[len2] = xml_data[k].getAttribute(io);	
				io1_arr[len2]=xml_data[k].getAttribute("i");
				io2_arr[len2]=xml_data[k].getAttribute("j");
				io3_arr[len2]=xml_data[k].getAttribute("k");
				io4_arr[len2]=xml_data[k].getAttribute("l");
				io5_arr[len2]=xml_data[k].getAttribute("m");
				io6_arr[len2]=xml_data[k].getAttribute("n");
				io7_arr[len2]=xml_data[k].getAttribute("o");
				io8_arr[len2]=xml_data[k].getAttribute("p");
				/*if(fuel_arr[len2] <30)
				{
					fuel_arr[len2] =0;
				}*/											
				vehicletype_arr[len2] = xml_data[k].getAttribute("y");
							
				if(label_type1!="Person")
				{
					day_max_speed_arr[len2] =  xml_data[k].getAttribute("s");
					day_max_speed_time_arr[len2] =  xml_data[k].getAttribute("t");                         
					last_halt_time_arr[len2] =  xml_data[k].getAttribute("u");
				}
				//alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
				len2++;									
			} // ELSE CLOSED
		////////////////////////////////////////
		}	//XML LEN LOOP CLOSEDhaa
		
		var poststr = "dest=" + encodeURI( thisdest );
		makePOSTRequest('src/php/del_xml.php', poststr);
		if((document.thisform.geofence_feature.checked==true) && (document.getElementById("category").value))
		{	  
			show_geofence();		
		}
	    if(vid_arr.length>0 && lat_arr.length>0 && lng_arr.length>0)
		{	
			  //alert("LPCOUNT="+lp_count);
			  //alert("before track markers "+len2+" "+lat_arr+" "+lng_arr+" "+vid_arr+" "+vehiclename_arr+" "+speed_arr+" "+datetime_arr+"  VTYPE="+vehicletype_arr);				
				if(thismode==1)
			{  			  			
				getxmlData_LP(len2,1,lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr,fuel_arr,vehicletype_arr,day_max_speed_arr,day_max_speed_time_arr,last_halt_time_arr,thisaccess,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)			
			   //alert("len2 outside loop="+len2);
			}
			else if(thismode==2)
			{
				getxmlData_Track(len2,1,lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr,fuel_arr,vehicletype_arr,thisaccess,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)

				//alert("len2 outside loop="+len2);
			}
			if(document.getElementById("category").value=="5")
			{
				document.getElementById("vehicle_milstone").value='vehicle_zoom';			
				show_milestones();		
			}
			document.getElementById('prepage').style.visibility='hidden';	
		}					
	} // ELSE DATA RECIEVED       		
}


/*
function pausecomp(millis)
{
  var date = new Date();
  var curDate = null;  
  do 
  {
    curDate = null;
    curDate = new Date();
    var obj=document.getElementById("waittxt")
    obj.value+="Hello! wait";
  }
  while(curDate-date < millis);
} */

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getxmlData_LP(len2,flag1,lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr, fuel_arr, vehicletype_arr,day_max_speed_arr, day_max_speed_time_arr,last_halt_time_arr,access,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)
{
	//alert("In getxmlData_LP"+access);
	
	if(vid_arr.length<=0)
	{
		flag1=0;
	}	
	
	if(flag1)
	{
		var point;	
		//alert("startup="+startup_var);
		if(startup_var == 1)
		{
			var bounds = new GLatLngBounds();
			for(var z=0;z<lat_arr.length;z++)
			{
				point = new GLatLng(parseFloat(lat_arr[z]),
							parseFloat(lng_arr[z]));

				bounds.extend(point); 
			}			
			var center = bounds.getCenter(); 			
			
			if(len2>0 && len2<2)
				var zoom = map.getBoundsZoomLevel(bounds)-7; 
			else if(len2>2 && len2<6)
				var zoom = map.getBoundsZoomLevel(bounds)-1; 
			else
				var zoom = map.getBoundsZoomLevel(bounds)-1;							
			/*if(access=="Zone")
			{
				//alert("access="+access+"ms="+ms);
				show_milestones();
				//alert("access="+access);
			//map.setCenter(center,zoom); 
			}
			else
			{*/
				map.setCenter(center,zoom);
			//}
			startup_var = 0;
		}		
		
		//alert("L0");
    LP_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr, fuel_arr, vehicletype_arr, day_max_speed_arr, day_max_speed_time_arr, last_halt_time_arr,len2,mm,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr);

		var zoom;
		var event = 0;
		var newzoomlevel=0;		
		
		//alert("L1");
    
		/*var feature_id_map = document.getElementById('station_flag_map').value; 
		//alert("LP fid:"+feature_id_map);
    if(feature_id_map ==1)
		{
		  getStation1();
    } */   

    getLandMark1(event,newzoomlevel);

		////////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR LAST POSITION //////////////////////////
		GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
		{
			var event =1;
			getLandMark1(event,newzoomlevel);
		}); //GEvent addListener												
	}//if flag1 closed			
} //FUNCTION getxmlDataTrack

/////////////////////// get_LastPosition ////////////////////////////////////

function LP_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,vehiclenumber_arr,speed_arr,datetime_arr, fuel_arr, vehicletype_arr, day_max_speed_arr, day_max_speed_time_arr, last_halt_time_arr, len2,mm,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)
{
	//alert("In LP markers");
	
	var gmarkersA = new Array();      
	var gmarkersB = new Array();  
	var gmarkersC = new Array();  
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,vehicle_number,speed,point,datetime, day_max_speed, day_max_speed_time, last_halt_time, place,marker,polyline,last,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8;

	var vid1=0;
	var vid2=0;
	var pt = new Array();
	var value = new Array();
	var poly = new Array();
	var dist = 0;
	var lastmarker = 0;
	var p = 0;
	var fuel=0;

	var mouse_action = document.forms[0].mouse_action.value;
	
  for (i = 0; i < len2; i++) 
	{
		vid = vid_arr[i];
		vehiclename = vehiclename_arr[i];
		vehicle_number = vehiclenumber_arr[i];
		
		speed = speed_arr[i];
		if(speed<=3)
			speed = 0;

		point = new GLatLng(parseFloat(lat_arr[i]),
		parseFloat(lng_arr[i]));
		datetime = datetime_arr[i];		
		fuel = fuel_arr[i];	
		vehicletype = vehicletype_arr[i];
		day_max_speed = day_max_speed_arr[i];
		day_max_speed_time = day_max_speed_time_arr[i];
		last_halt_time = last_halt_time_arr[i];
		io_1=io1_arr[i];
		io_2=io2_arr[i];
		io_3=io3_arr[i];
		io_4=io4_arr[i];
		io_5=io5_arr[i];
		io_6=io6_arr[i];
		io_7=io7_arr[i];
		io_8=io8_arr[i];
		pt[i] = point;
		place=0;		
		marker = CreateMarkerLP(point, vid, vehiclename, vehicle_number, speed, datetime, fuel, vehicletype, day_max_speed, day_max_speed_time, last_halt_time, len2,gmarkersC,p, mouse_action,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
		p++;
		gmarkersA.push(marker);

		if(i==len2-1)
		{
			for(var m=0;m<gmarkersA.length;m++)
			map.addOverlay(gmarkersA[m]);				
			document.getElementById('prepage').style.visibility='hidden';					
		}

		///////////////////////////// PLOT CURRENT MARKER ////////////////////////////////////

		var dt = datetime.split(' ');	
		var date1 = dt[0].split('-');
		
		var year1 = date1[0];
		var month1 = date1[1];
		var day1 = date1[2];

		var time1 = dt[1].split(':');	
		var hr1 = time1[0];	

		var year2 = currentDate.getFullYear();
		var month2 = currentDate.getMonth()+1;	
		var day2 = currentDate.getDate();
		var hr2 = currentDate.getHours();
		
		if(month2<10)
		{
			month2="0"+month2;
		}
		
		if(hr2<10)
		{
			hr2="0"+hr2;
		}

	} //for i loop closed	
	
	//alert("i="+i+" len2="+len2);
		
} //track markers closed

//////////////////////////////////////////////////////////////////////////////////////////////

var pt = new Array();
var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
var vname = new Array();
var mm;
var rect;	

function CreateMarkerLP(point, imei, vehiclename, vehicle_number, speed, datetime, fuel, vehicletype, day_max_speed, day_max_speed_time, last_halt_time, len2, gmarkersC, p, mouse_action,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
{
	var vIcon;
	/*var req = getXMLHTTP();
	req.open("GET", strURL, false); //third parameter is set to false here
	req.send(null);
	var db_vtype = req.responseText;*/
	var db_vtype = vehicletype;  
	if(p==0)
	{
		mm = new GMarkerManager(map, {borderPadding:1});
		//if(vehicletype=="Light")
		if(db_vtype=="car")
			vIcon= new GIcon(lvIcon1);

		//else if(vehicletype=="Heavy")
		else if(db_vtype=="truck")
			vIcon= new GIcon(hvIcon2);
			
		else if(db_vtype=="bus")
			vIcon= new GIcon(hvIcon4);
      
		else if(db_vtype=="motorbike")
			vIcon= new GIcon(hvIcon5);      			
			
		else
			vIcon = new GIcon(lvIcon1);
			
			//alert("vIcon_0="+vIcon);
	}
	
	else if(p%2==0)
	{
		//if(vehicletype=="Light")
		if(db_vtype=="car")
			vIcon = new GIcon(lvIcon1);

		//else if(vehicletype=="Heavy")
		else if(db_vtype=="truck")
			vIcon = new GIcon(hvIcon2);
		
		else if(db_vtype=="bus")
			vIcon = new GIcon(hvIcon4);
			
		else if(db_vtype=="motorbike")
			vIcon = new GIcon(hvIcon5);			
		
		else
			vIcon = new GIcon(lvIcon1);
			
				//alert("vIcon_1="+vIcon);
	}
	else 
	{
		//if(vehicletype=="Light")
		if(db_vtype=="car")
			vIcon = new GIcon(lvIcon3);

		//else if(vehicletype=="Heavy")
		else if(db_vtype=="truck")
			vIcon = new GIcon(hvIcon3);
			
		else if(db_vtype=="bus")
			vIcon = new GIcon(hvIcon4);
			
		else if(db_vtype=="motorbike")
			vIcon = new GIcon(hvIcon5);			
			
		else
			vIcon = new GIcon(lvIcon1);
	}

	pt[p] = point;
	vname[p] = vehiclename;
	
	var lt_1 = Math.round(point.y*100000)/100000; 
	var ln_1 = Math.round(point.x*100000)/100000;

	var marker = new GMarker(point, vIcon);
	
	startdate = document.thisform.start_date.value;
	enddate = document.thisform.end_date.value;

	//last_vehicletype_arr[p] = vehicletype;
	last_marker_arr[p] = marker;
	vIcon_arr[p] = vIcon;
	
	//alert("GEarthStatus="+document.myform.GEarthStatus.value);
	
	if(document.thisform.GEarthStatus.value == 1)
	{		
	    //alert("in if");		
		//GEvent.addListener(marker, 'click', function()
		GEvent.addListener(marker, mouse_action, function()
		{	
			PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, vehicle_number, speed, datetime, day_max_speed, day_max_speed_time, last_halt_time, fuel,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
		});	
	}
	
	else
	{ 
	    //alert("in else");	 
		//GEvent.addListener(marker, 'mouseover', function()
		GEvent.addListener(marker, mouse_action, function()
		{			
			PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, vehicle_number, speed,datetime, day_max_speed, day_max_speed_time, last_halt_time, fuel,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
		});
	}

	return marker;		
}				

function miniMapZoomEnd(oldZ,newZ) 
{
	showMinimapRect(this);
}

function miniMapMoveEnd() 
{
	showMinimapRect(this);
}

function showMinimapRect(detailMap,point) 
{
	if (rect)
	{
		map.removeOverlay(rect);
	}
	var bounds = detailMap.getBounds();
	var polyPoints = [	bounds.getSouthWest(),
						new GLatLng(bounds.getSouthWest().lat(),bounds.getNorthEast().lng()),
						bounds.getNorthEast(),
						new GLatLng(bounds.getNorthEast().lat(),bounds.getSouthWest().lng()),
						bounds.getSouthWest()
					]

	rect = new GPolygon(polyPoints, '#ff0000', 2, 1, '', 0.5);	
	map.addOverlay(rect);

}

function mapIWClose() 
{
	if (rect)
	{
		map.removeOverlay(rect);
	}
}

function getXMLHTTP()
{
	http_request=false;
	if (window.XMLHttpRequest)
	{
		http_request = new XMLHttpRequest();
	} 
	else if (window.ActiveXObject) 
	{
		http_request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return http_request;
}

/*function getScriptPage2(str)
{
  var strURL="src/php/get_fuel_pro.php?content="+str;
  var req = getXMLHTTP();
  req.open("GET", strURL, false); //third parameter is set to false here
  req.send(null);
  return req.responseText;
} */


///////////////// SORT ARRAY DATE WISE //////////////////
	
var dateRE = /^(\d{2})[\/\- ](\d{2})[\/\- ](\d{4})/;
function dmyOrdA(a, b){
a = a.replace(dateRE,"$3$2$1");
b = b.replace(dateRE,"$3$2$1");
if (a>b) return 1;
if (a <b) return -1;
return 0; }
function dmyOrdD(a, b){
a = a.replace(dateRE,"$3$2$1");
b = b.replace(dateRE,"$3$2$1");
if (a>b) return -1;
if (a <b) return 1;
return 0; }
function mdyOrdA(a, b){
a = a.replace(dateRE,"$3$1$2");
b = b.replace(dateRE,"$3$1$2");
if (a>b) return 1;
if (a <b) return -1;
return 0; }
function mdyOrdD(a, b){
a = a.replace(dateRE,"$3$1$2");
b = b.replace(dateRE,"$3$1$2");
if (a>b) return -1;
if (a <b) return 1;
return 0; } 

///////////////////////////////////////////////////////////////////////////////////////////////////////



function isFile(str){
    var O= AJ();
    if(!O) return false;
    try
    {
        O.open("HEAD", str, false);
        O.send(null);
        return (O.status==200) ? true : false;
    }
    catch(er)
    {
        return false;
    }
}
function AJ()
{
    var obj;
    if (window.XMLHttpRequest)
    {
        obj= new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        try
        {
            obj= new ActiveXObject('MSXML2.XMLHTTP.3.0');
        }
        catch(er)
        {
            obj=false;
        }
    }
    return obj;
}


//////////////////  MAKE POST REQUEST  ///////////////////////

   var http_request = false;
   function makePOSTRequestMap(url, parameters) 
   {
      //alert("IN POST REQ");
      http_request = false;
      if (window.XMLHttpRequest) 
      { 
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType)
         {
         	// set type accordingly to anticipated content type
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
      
      http_request.onreadystatechange = alertContentsMap;
      http_request.open('POST', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
   }
   
  function alertContentsMap()
  {
    //alert("IN alert CNT");
    if (http_request.readyState == 4) 
    {
       if (http_request.status == 200) 
       {
          result = http_request.responseText;
          //alert(result);
       }
    }
  } 
   
///////////////////////////////////////////////////////////////////////// ////////////////////////  

function PlotLastMarkerWithAddress(point, Icon, marker, imei, vehiclename, vehicle_number, speed,datetime, day_max_speed, day_max_speed_time, last_halt_time, fuel,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
{
	var window_style1="style='color:#000000;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;font-weight:bold;'";
	var window_style2="style='color:blue;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;'";
	var accuracy;
	var largest_accuracy;	   
	var delay = 100;
	var io_str="";	
	var window_height=150;
	//alert("imei_iotype_arr="+imei_iotype_arr[imei]);
	if(imei_iotype_arr[imei]!=undefined)
	{
		var iotype_iovalue_str=imei_iotype_arr[imei].split(":");
		if(iotype_iovalue_str.length==2)
		{
			window_height=160;
		}
		else if(iotype_iovalue_str.length==3)
		{
			window_height=175;
		}
		else if(iotype_iovalue_str.length==4)
		{
			window_height=190;
		}
		else if(iotype_iovalue_str.length==5)
		{
			window_height=205;
		}
		else if(iotype_iovalue_str.length==6)
		{
			window_height=220;
		}
		else if(iotype_iovalue_str.length==7)
		{
			window_height=235;
		}
		else if(iotype_iovalue_str.length==8)
		{
			window_height=250;
		}
		for(var i=0;i<iotype_iovalue_str.length;i++)
		{
			var iotype_iovalue_str1=iotype_iovalue_str[i].split("^");
			//alert("iotype_iovalue_str1="+iotype_iovalue_str1[0]);				
			var io_values="io_"+iotype_iovalue_str1[0];	
		
			if(iotype_iovalue_str1[1]=="temperature")
			{					
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
					if(eval(io_values)>=-30 && eval(io_values)<=70)
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+eval(io_values)+"</td></tr>";
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}
				else
				{
					io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
				}
			}
			else 
			{
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{					
					io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+eval(io_values)+"</td></tr>";
				}
				else
				{
					io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
				}			
			}			
		}
	}

 var geocoder = new GClientGeocoder();
 var address_tmp;
 var address1_tmp;
 var BadAddress=0;
 var place;
 var bname = navigator.appName;
 geocoder.getLocations(point, function (result) { 
 
	var customer_plant_str="";
	var customer_plant_str1="";
	var feature_id_map = document.getElementById('station_flag_map').value;
	if(feature_id_map==1)
	{
		window_height=window_height+20;
		var client_type_combo=document.getElementById('station_chk').value;
		//if(document.getElementById('station_search_text').value=="")
		{	
			 var customer_distance_arr=new Array();
                         var customer_print_str=new Array();
		
			/*if(client_type_combo=="0")
			{*/
				var customerDataLength=lat_customer.length;
				var customer_min_distance;				
				if(customerDataLength>0)
				{
					//var customer_distance_arr=new Array();
					//var customer_print_str=new Array();
					for(var i=0;i<customerDataLength;i++)
					{					
						var customer_distance = calculate_distance(point.y, lat_customer[i], point.x, lng_customer[i]);
						customer_distance_arr[i]=customer_distance;
						customer_print_str[customer_distance]=station_customer[i]+":"+customer_station_no[i];
					}
					customer_distance_arr.sort();
					customer_min_distance=customer_distance_arr[0];
					var customer_print_str1=customer_print_str[customer_min_distance];
					///customer_plant_str="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_distance_arr[0]+"From "+customer_print_str[customer_distance_arr[0]]+"</td></tr>";
				}				
			/*}
			else if(client_type_combo=="1")
			{*/
				var planDataLength=lat_plant.length;
				var plant_min_distance;		
				if(planDataLength>0)
				{
					var plant_distance_arr=new Array();
					var plant_print_str=new Array();
					for(var i=0;i<planDataLength;i++)
					{					
						var customer_distance = calculate_distance(point.y, lng_plant[i], point.x, lat_plant[i]);
						plant_distance_arr[i]=customer_distance;
						plant_print_str[customer_distance]=station_plant[i]+":"+customer_plant[i];
					}
					plant_distance_arr.sort();
					plant_min_distance=plant_distance_arr[0];
					var plant_print_str1=customer_print_str[plant_min_distance];
					//customer_plant_str="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+plant_distance_arr[0]+"From "+plant_print_str[plant_distance_arr[0]]+"</td></tr>";
				}
				//alert("plant_min_distance="+plant_min_distance+"customer_min_distance="+customer_min_distance);
				if(plant_min_distance==undefined && customer_min_distance!=undefined)
				{
					//alert("in if");
					customer_plant_str="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_min_distance+ " From "+customer_print_str1+"</td></tr>";
				}
				else if(customer_min_distance==undefined && plant_min_distance!=undefined)
				{
					//alert("in else if 1");
					customer_plant_str="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+plant_min_distance+" From "+plant_print_str1+"</td></tr>";
				}
				else if(plant_min_distance==undefined && customer_min_distance==undefined)
				{
					//alert("in else if 2");
					customer_plant_str="";
				}
				else
				{
					//alert("else");
					if(plant_min_distance<customer_min_distance)
					{				
						customer_plant_str="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+plant_min_distance+" From "+plant_print_str1+"</td></tr>";
					}
					else if(customer_min_distance<plant_min_distance)
					{					
						customer_plant_str="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_min_distance+" From "+customer_print_str1+"</td></tr>";
					}
				}
			//}
		}
		if(document.getElementById('station_search_text').value!="")
		{	
			var search_text = document.getElementById('station_search_text').value;
			if(client_type_combo=="select")
			{
				alert("Please select customer");
				return false;
			}
			else
			{
				if(client_type_combo=="0")
				{			
					if(station_counter_customer>0)
					{
						for(var i=0;i<station_counter_customer;i++)
						{
							search_text = trim(search_text);
							station_name_customer[i] = trim(station_name_customer[i]);
							station_customer_1[i] = trim(station_customer_1[i]); 
							if((search_text == station_name_customer[i]) || (search_text == station_customer_1[i]) )
							{
								//alert("found");
								var customer_distance = calculate_distance(point.y, station_lat_customer[i], point.x, station_lng_customer[i]);							
								customer_plant_str1="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_distance+" From "+station_name_customer[i]+":"+station_customer_1[i]+"</td></tr>";
								break;
							}    
						}  				
					}			
				}
				
				if(client_type_combo=="1")
				{	
					if(station_counter_plant>0)
					{				
						for(var i=0;i<station_counter_plant;i++)
						{
							search_text = trim(search_text);
							station_name_plant[i] = trim(station_name_plant[i]);
							station_customer_plant[i] = trim(station_customer_plant[i]); 
							if( (search_text == station_name_plant[i]) || (search_text == station_customer_plant[i]) )
							{
								//alert("found");
								var customer_distance = calculate_distance(point.y, station_lat_plant[i], point.x, station_lng_plant[i]);							
								customer_plant_str1="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+customer_distance+" From "+station_name_plant[i]+":"+station_customer_plant[i]+"</td></tr>";
								break;					
							}    
						} 				
					}
				}
			}
		}
	}
	var address2=""; // for getting the location from google map or xml 
if (result.Status.code == G_GEO_SUCCESS) // OR !=200
	{
		var j;
		for (var i=0; i<result.Placemark.length; i++)
		{
			accuracy = result.Placemark[i].AddressDetails.Accuracy;
			address_tmp = result.Placemark[i];
			address1_tmp = address_tmp.address;
			//alert("address1_tmp="+address1_tmp+"accuracy="+accuracy);
			if(accuracy!=0 && accuracy!=1 && accuracy!=2 && accuracy!=5 && accuracy!=7 && accuracy!=8)
			{
				if(accuracy==6)  /// this is street leve aprox accurate
				{					
					if((address1_tmp.indexOf("NH") ==-1) && (address1_tmp.indexOf("National Highway") ==-1) && (address1_tmp.indexOf("State Highway")==-1))
					{					
						address2=get_js_location(result.Placemark[i],point);
						break;
					}		 
				}		
				else if(accuracy==3) /////// this is country munciple level address 
				{			
					address2=get_js_location(result.Placemark[i],point);
					break;
				}
				else
				{
					if(accuracy==4) /////////// city,village level address
					{					
						address2=get_js_location(result.Placemark[i],point);
						break;			
					}				
				}
			}
		}		
	}  // if (result.Status.code == G_GEO_SUCCESS)  CLOSED
	else
	{
		var address2 ="-";
	}	
    ///////////////////////////// SELECT LANDMARK OR GOOGLE PLACE CODE /////////////////////////////////////////////////////
		/// IF DISTANCE CALCULATED THROUGH FILE IS LESS THAN 1 KM THEN DISPLAY LANDMARK OTHERWISE DISPLAY GOOGLE PLACE /////////
		
		if(address2=="" || address2=="-") // if address not come form google map then this block get address from xml
		{					
			address2=get_xml_location(point);
			//alert("xml_loacation_2="+address2);	
		}
		
		var lt_original = point.y;
		var lng_original = point.x;
		var str = lt_original+","+lng_original;
		
		//var access2=document.thisform.access.value;
			//alert('access='+str);

		//if(access2=="Zone")
		//{
		//	var strURL="src/php/select_mining_landmark.php?content="+str;
		//}
		//else
		//{
			var strURL="src/php/select_landmark_marker.php?content="+str;
		//}

		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);
		var landmark = req.responseText;
		
		//alert("landmark="+landmark);
		//return req.responseText;
		if(landmark!="")
			place = landmark;
		else
			place = address2;	
		
    // GET FUEL LEVE LAST POSITION
		str = imei+","+fuel;		
		strURL="src/php/map_fuel_calibration.php?content="+str;	        
		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);
		var fuel_level = req.responseText;		
    //////////////////////  
		var imeino_to_vehicle_no=imei;
		var strURL="src/php/home_driver_detail.php?imei_no="+imeino_to_vehicle_no;
		//}
		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);
		//alert("result="+req.responseText);
		var vehicle_mob_no = req.responseText;
		if(vehicle_mob_no=="")
		{
			vehicle_mob_no="-";
		}
  	if(label_type!="Person")
  	{
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Fuel</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel_level+' litres</td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:100px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr></table></div>');
  		if(day_max_speed =="0 km/hr" || day_max_speed=="")
  		{
        var day_max_speed_string = '';
      }
      else
      {
        var day_max_speed_string = day_max_speed+' &nbsp;('+day_max_speed_time+')';
      }
      //var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:195px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Day Max Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+day_max_speed_string+'</font></td></tr><tr><td><font size=2 color=#000000>Last Halt Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+last_halt_time+'</font></td></tr><tr><td><font size=2 color=#000000>Temp</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel+'</font></td><td></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');       
	   var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:'+window_height+'"px;" align=left>'+
												'<table cellpadding=0 cellspacing=0>'+													
													'<tr>'+
														'<td '+window_style1+'> Vehicle </td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+vehiclename+'</td>'+														
													'</tr>'+
													'<tr>'+
														'<td '+window_style1+'>IMEI</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+imei+'</td>'+													
													'</tr>'+
													'<tr>'+
														'<td '+window_style1+'>Speed</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+speed+' kmph'+'</td>'+
													'</tr>'+
													'<tr>'+
														'<td '+window_style1+'>Date & Time</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+datetime+'&nbsp;&nbsp;</td>'+
													'</tr>'+
													'<tr>'+
														'<td '+window_style1+'>Place</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+place+'</td>'+
													'</tr>'+
													'<tr>'+
														'<td '+window_style1+'>Driver Name/Mob</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+vehicle_mob_no+'</td>'+														
													'</tr>'+
													customer_plant_str+
													customer_plant_str1+
													'<tr>'+
														'<td '+window_style1+'>Day Max Speed</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+day_max_speed_string+'</td>'+
													'</tr>'+
													'<tr>'+
														'<td '+window_style1+'>Last Halt Time</td>'+
														'<td>&nbsp;:&nbsp;</td>'+
														'<td '+window_style2+'>'+last_halt_time+'</td>'+
													'</tr>'
													+io_str+
													'<tr>'+
														'<td colspan=3 height="3px"></td>'+
													'</tr>'+
													'<tr>'+
														'<td colspan=3 '+window_style1+'>'+														
																'( '+point.y+', '+point.x+' )'+													
														'</td>'+
													'</tr>'+
												'</table>'+
											'</div>');
  	}
  	else
  	{
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:100px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Mobile No</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehicle_number + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr></table></div>');
  	}
		//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
		var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');

		//alert(" tab1="+tab1+" tab2="+tab2);
		//var infoTabs = [tab1,tab2];
		var infoTabs = [tab1];

		//alert(" marker="+marker+" infoTabs="+infoTabs);
		marker.openInfoWindowTabsHtml(infoTabs);

		var dMapDiv = document.getElementById("detailmap");
		var detailMap = new GMap2(dMapDiv);
		detailMap.setCenter(point , 12);

		detailMap.removeMapType(G_SATELLITE_MAP);																

		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
		detailMap.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		detailMap.addControl(mapControl, topRight);

		var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,35));
		var mapControl2 = new GSmallMapControl();
		//detailMap.addControl(new GSmallMapControl());
		GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
		GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
		detailMap.addControl(mapControl2, topLeft);

		var CopyrightDiv = dMapDiv.firstChild.nextSibling;
		var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
		CopyrightDiv.style.display = "none"; 
		CopyrightImg.style.display = "none";
		var marker3 = new GMarker(point,Icon);
		//alert("point ="+point+" mrk3="+marker3);
		detailMap.addOverlay(marker3);

		showMinimapRect(detailMap,marker3);
    // }
  });
}
	

 function get_js_location(placemark,point)
{
	var address = placemark;
	address1 = address.address;		
	var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]); 
	//alert("google_point.y======="+google_point.y+" google_point.x="+google_point.x);
	var distance = calculate_distance(point.y, google_point.y, point.x, google_point.x); 
	//alert("dist="+distance);
	var address_local = distance+" km from "+address1;
	return address_local;
} 

function get_xml_location(point)
{
	var strURL="src/php/get_location_tmp_file.php?point_test="+point;
	//alert("strurl:"+strURL);
	var req = getXMLHTTP();
	req.open("GET", strURL, false); //third parameter is set to false here
	req.send(null);  
	var place_name_temp_param = req.responseText; 
	//alert("place_name_temp_param1="+place_name_temp_param);
	place_name_temp_param =place_name_temp_param.split(":");
	//alert("lat1="+point.x+"lng="+point.y+"lat2="+place_name_temp_param[1]+"log2="+place_name_temp_param[2]);
	var distance = calculate_distance(point.lat(), place_name_temp_param[1], point.lng(), place_name_temp_param[2]);
	//alert("distance="+distance);
	var address_local = distance+" km from "+place_name_temp_param[0];
	return address_local;
}   


var fuel_level=0;

function PlotTrackMarkerWithAddress(point, Icon, marker, imei, vehiclename, vehicle_number, speed, datetime, dist, fuel, vehicletype,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
{
 var accuracy;
 var largest_accuracy;	   
 var delay = 100;
var window_style1="style='color:#000000;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;font-weight:bold;'";
var window_style2="style='color:blue;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;'";
 var io_str="";
 
 
	var window_height=125;
	if(imei_iotype_arr[imei]!=undefined)
	{
		var iotype_iovalue_str=imei_iotype_arr[imei].split(":");		
		if(iotype_iovalue_str.length==2)
		{
			window_height=160;
		}
		else if(iotype_iovalue_str.length==3)
		{
			window_height=200;
		}
		else if(iotype_iovalue_str.length==4)
		{
			window_height=190;
		}
		else if(iotype_iovalue_str.length==5)
		{
			window_height=205;
		}
		else if(iotype_iovalue_str.length==6)
		{
			window_height=220;
		}
		else if(iotype_iovalue_str.length==7)
		{
			window_height=235;
		}
		else if(iotype_iovalue_str.length==8)
		{
			window_height=250;
		}
		for(var i=0;i<iotype_iovalue_str.length;i++)
		{
			var iotype_iovalue_str1=iotype_iovalue_str[i].split("^");
			//alert("iotype_iovalue_str1="+iotype_iovalue_str1[0]);				
			var io_values="io_"+iotype_iovalue_str1[0];	
		
			if(iotype_iovalue_str1[1]=="temperature")
			{					
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
					if(eval(io_values)>=-30 && eval(io_values)<=70)
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+eval(io_values)+"</td></tr>";
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}
				else
				{
					io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
				}
			}
			else 
			{
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{					
					io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+eval(io_values)+"</td></tr>";
				}
				else
				{
					io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
				}			
			}			
		}
	}
 var geocoder = new GClientGeocoder();
 var address_tmp;
 var address1_tmp;
 var BadAddress=0;
 var place;
 
 geocoder.getLocations(point, function (result) {
 var address2=""; // for getting the location from google map or xml
 if (result.Status.code == G_GEO_SUCCESS) // OR !=200
	{
		var j;
		for (var i=0; i<result.Placemark.length; i++)
		{
			accuracy = result.Placemark[i].AddressDetails.Accuracy;
			address_tmp = result.Placemark[i];
			address1_tmp = address_tmp.address;
			//alert("address1_tmp="+address1_tmp+"accuracy="+accuracy);
			if(accuracy!=0 && accuracy!=1 && accuracy!=2 && accuracy!=5 && accuracy!=7 && accuracy!=8)
			{
				if(accuracy==6)  /// this is street leve aprox accurate
				{					
					if((address1_tmp.indexOf("NH") ==-1) && (address1_tmp.indexOf("National Highway") ==-1) && (address1_tmp.indexOf("State Highway")==-1))
					{					
						address2=get_js_location(result.Placemark[i],point);
						break;
					}		 
				}		
				else if(accuracy==3) /////// this is country munciple level address 
				{			
					address2=get_js_location(result.Placemark[i],point);
					break;
				}
				else
				{
					if(accuracy==4) /////////// city,village level address
					{					
						address2=get_js_location(result.Placemark[i],point);
						break;			
					}					
				}
			}
		}		
	}  // if (result.Status.code == G_GEO_SUCCESS)  CLOSED
	else
	{
		address2 ="-";
	}	
    ///////////////////////////// SELECT LANDMARK OR GOOGLE PLACE CODE /////////////////////////////////////////////////////
		/// IF DISTANCE CALCULATED THROUGH FILE IS LESS THAN 1 KM THEN DISPLAY LANDMARK OTHERWISE DISPLAY GOOGLE PLACE /////////
		
		
	var customer_plant_str="";
	var customer_plant_str1="";
	var feature_id_map = document.getElementById('station_flag_map').value;
	if(feature_id_map==1)
	{		
		window_height=window_height+20;
		var client_type_combo=document.getElementById('station_chk').value;
		//if(document.getElementById('station_search_text').value=="")
		{			
			/*if(client_type_combo=="0")
			{*/
				var customerDataLength=lat_customer.length;
				var customer_min_distance;	

				var customer_distance_arr=new Array();
				var customer_print_str=new Array();
					
				if(customerDataLength>0)
				{
					//var customer_distance_arr=new Array();
					//var customer_print_str=new Array();
					for(var i=0;i<customerDataLength;i++)
					{					
						var customer_distance = calculate_distance(point.y, lat_customer[i], point.x, lng_customer[i]);
						customer_distance_arr[i]=customer_distance;
						customer_print_str[customer_distance]=station_customer[i]+":"+customer_station_no[i];
					}
					customer_distance_arr.sort();
					customer_min_distance=customer_distance_arr[0];
					var customer_print_str1=customer_print_str[customer_min_distance];
					///customer_plant_str="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_distance_arr[0]+"From "+customer_print_str[customer_distance_arr[0]]+"</td></tr>";
				}				
			/*}
			else if(client_type_combo=="1")
			{*/
				var planDataLength=lat_plant.length;
				var plant_min_distance;		
				if(planDataLength>0)
				{
					var plant_distance_arr=new Array();
					var plant_print_str=new Array();
					for(var i=0;i<planDataLength;i++)
					{					
						var customer_distance = calculate_distance(point.y, lng_plant[i], point.x, lat_plant[i]);
						plant_distance_arr[i]=customer_distance;
						plant_print_str[customer_distance]=station_plant[i]+":"+customer_plant[i];
					}
					plant_distance_arr.sort();
					plant_min_distance=plant_distance_arr[0];
					var plant_print_str1=customer_print_str[plant_min_distance];
					//customer_plant_str="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+plant_distance_arr[0]+"From "+plant_print_str[plant_distance_arr[0]]+"</td></tr>";
				}
				//alert("plant_min_distance="+plant_min_distance+"customer_min_distance="+customer_min_distance);
				if(plant_min_distance==undefined && customer_min_distance!=undefined)
				{
					//alert("in if");
					customer_plant_str="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_min_distance+ " From "+customer_print_str1+"</td></tr>";
				}
				else if(customer_min_distance==undefined && plant_min_distance!=undefined)
				{
					//alert("in else if 1");
					customer_plant_str="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+plant_min_distance+" From "+plant_print_str1+"</td></tr>";
				}
				else if(plant_min_distance==undefined && customer_min_distance==undefined)
				{
					//alert("in else if 2");
					customer_plant_str="";
				}
				else
				{
					//alert("else");
					if(plant_min_distance<customer_min_distance)
					{				
						customer_plant_str="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+plant_min_distance+" From "+plant_print_str1+"</td></tr>";
					}
					else if(customer_min_distance<plant_min_distance)
					{					
						customer_plant_str="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_min_distance+" From "+customer_print_str1+"</td></tr>";
					}
				}
			//}
		}
		if(document.getElementById('station_search_text').value!="")
		{	
			var search_text = document.getElementById('station_search_text').value;
			if(client_type_combo=="select")
			{
				alert("Please select customer");
				return false;
			}
			else
			{
				if(client_type_combo=="0")
				{			
					if(station_counter_customer>0)
					{
						for(var i=0;i<station_counter_customer;i++)
						{
							search_text = trim(search_text);
							station_name_customer[i] = trim(station_name_customer[i]);
							station_customer_1[i] = trim(station_customer_1[i]); 
							if((search_text == station_name_customer[i]) || (search_text == station_customer_1[i]) )
							{
								//alert("found");
								var customer_distance = calculate_distance(point.y, station_lat_customer[i], point.x, station_lng_customer[i]);							
								customer_plant_str1="<tr><td "+window_style1+">Place From Customer</td><td>:</td><td "+window_style2+">"+customer_distance+" From "+station_name_customer[i]+":"+station_customer_1[i]+"</td></tr>";
								break;
							}    
						}  				
					}			
				}
				
				if(client_type_combo=="1")
				{	
					if(station_counter_plant>0)
					{				
						for(var i=0;i<station_counter_plant;i++)
						{
							search_text = trim(search_text);
							station_name_plant[i] = trim(station_name_plant[i]);
							station_customer_plant[i] = trim(station_customer_plant[i]); 
							if( (search_text == station_name_plant[i]) || (search_text == station_customer_plant[i]) )
							{
								//alert("found");
								var customer_distance = calculate_distance(point.y, station_lat_plant[i], point.x, station_lng_plant[i]);							
								customer_plant_str1="<tr><td "+window_style1+">Place From Plant</td><td>:</td><td "+window_style2+">"+customer_distance+" From "+station_name_plant[i]+":"+station_customer_plant[i]+"</td></tr>";
								break;					
							}    
						} 				
					}
				}
			}
		}
	}
		if(address2=="" || address2=="-") // if address not come form google map then this block get address from xml
		{					
			address2=get_xml_location(point);
			//alert("xml_loacation_2="+address2);	
		}
		
		var lt_original = point.y;
		var lng_original = point.x;
		var str = lt_original+","+lng_original;
		
		//var access2=document.myform.access.value;
		//alert('access='+str);

		/*if(access2=="Zone")
		{
			var strURL="select_mining_landmark.php?content="+str;
		}
		else
		{*/
			var strURL="src/php/select_landmark_marker.php?content="+str;
		//}		
        
		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);
		var landmark = req.responseText;
		//alert("landmark="+landmark);
		//return req.responseText;
		if(landmark!="")
			place = landmark;
		else
			place = address2;

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		//alert(place);
		/*alert("[Original latlng="+latlng+"]   <br>  [Google PT="+point+"]   largest_accuracy"+largest_accuracy+"			address1="+address1);*/
		//alert("add before="+address1);
		//alert("Icon="+Icon+" map="+map+" marker="+marker+ " actionmrkr="+action_marker+"  vname="+vehiclename+" spd="+speed+" dt="+datetime+" dist="+dist+" fuelltr="+fuel_litres+" fuel_level="+fuel_level);

		
    ///GET FUEL LEVEL IN TRACK///////////
		
		if(fuel>30 && fuel<4096)
		{
      str = imei+","+fuel;		
      strURL="src/php/map_fuel_calibration.php?content="+str;	
      //alert(strURL);
          
  		var req2 = getXMLHTTP();
  		req2.open("GET", strURL, false); //third parameter is set to false here
  		req2.send(null);
  		fuel_level = req2.responseText;	
    }

	var imeino_to_vehicle_no=imei;
	var strURL="src/php/home_driver_detail.php?imei_no="+imeino_to_vehicle_no;
	//}
	var req = getXMLHTTP();
	req.open("GET", strURL, false); //third parameter is set to false here
	req.send(null);
	var vehicle_mob_no = req.responseText;
	if(vehicle_mob_no=="")
	{
		vehicle_mob_no="-";
	}
    /////////////////////////////////////
	//alert("label_type="+label_type);
    if(label_type!="Person")
  	{    	  		      
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Distance travelled</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+dist+' km</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  		
      var feature_id_map = document.getElementById('station_flag_map').value;      
      
      var tab_content;
      var caption;
      
      if(feature_id_map == 1)
      {
        caption = '<em>Add </em><select id="landmark_type" onchange="display_landmark_type(this.value)"><option value="landmark">Landmark</option><option value="station">Station</option></select></em>';
      }
      else
      {
        caption = '<em>Add Landmark</em>';
      }
      
     // var content1 = '<table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr><tr><td style="height:30px;"><font size=2 color=#000000>Distance travelled</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+dist+' km</font></td></tr><tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table>';
      var content1 = '<table cellpadding=0 cellspacing=0><tr><td '+window_style1+'>Vehicle</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+vehiclename + '</td><td></td></tr><tr><td '+window_style1+'>IMEI</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+imei + '</td><td></td></tr><tr><td '+window_style1+'>Speed</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+speed+' kmph</td></tr><tr><td '+window_style1+'>Date & Time</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+datetime+' '+'&nbsp;&nbsp;</td></tr><tr><td '+window_style1+'>Distance travelled</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+dist+' km</td></tr><tr><td '+window_style1+'>Place</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+place+'</td></tr><tr><td '+window_style1+'>Driver Name/Mob</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+vehicle_mob_no+'</td></tr>'+customer_plant_str+customer_plant_str1+io_str+'<tr><td colspan=3 height="5px"></td></tr><tr><td colspan=3 '+window_style1+'>( '+point.y+','+point.x+' )</td></tr></table>';
      var content2 =         
       '<br><form action="#">'
			 + '<table><tr><td '+window_style2+'>'+caption+'</td>' 
       + '<td>'
       + '<span id ="landmark_area">'
			 + '<table><tr><td>LandmarkName&nbsp;:&nbsp;<input type="text" name="landmark_name" id="landmark_name"  size="10"><input type="hidden" name="landmark_point" id="landmark_point" value="'+point+'"></td>'			 					
			 + '<td><input type="button" value="SAVE" id="save_lnmrk" onclick="map_add_landmark(this.form);"/></td><td><span id="wait_lnmrk" style="display:none;"><img src="images/map_add_landmark_loading.gif" align="absmiddle">&nbsp;wait ...</span></td>'
			 + '</tr></table>'
       + '</span>'
			 + '</td>'
			 + '<td>'
       + '<span id ="station_area"  style="display:none;">'			 
       + '<table><tr><td>StationName &nbsp;:&nbsp;<input type="text" name="station_name" id="station_name"  size="10"></td><td>CustomerNo&nbsp;:&nbsp;<input type="text" name="customer_no" id="customer_no" size="10"><input type="hidden" name="landmark_point" id="landmark_point" value="'+point+'"></td>'			 					
			 + '<td><input type="button" value="SAVE" id="save_lnmrk" onclick="map_add_station(this.form);"/></td><td><span id="wait_lnmrk" style="display:none;"><img src="images/map_add_landmark_loading.gif" align="absmiddle">&nbsp;wait ...</span></td>'
			 + '</tr></table>'
       + '</span>'
			 + '</td>'
       + '</tr></table></form>';

      var tab_content = content1 + content2;
             
      var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="'+window_height+'px;width:435px;" align=left>'+tab_content+'</div>');
  	}
  	else
  	{
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:150px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr></table></div>');
  	}
		//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
		var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:160px;"></div>');

		//alert(" tab1="+tab1+" tab2="+tab2);
		//var infoTabs = [tab1,tab2];
		var infoTabs = [tab1];

		//alert(" marker="+marker+" infoTabs="+infoTabs);
		marker.openInfoWindowTabsHtml(infoTabs);


		var dMapDiv = document.getElementById("detailmap");
		var detailMap = new GMap2(dMapDiv);
		detailMap.setCenter(point , 12);

		detailMap.removeMapType(G_SATELLITE_MAP);																

		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
		detailMap.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		detailMap.addControl(mapControl, topRight);

		var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,35));
		var mapControl2 = new GSmallMapControl();
		//detailMap.addControl(new GSmallMapControl());
		GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
		GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
		detailMap.addControl(mapControl2, topLeft);

		var CopyrightDiv = dMapDiv.firstChild.nextSibling;
		var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
		CopyrightDiv.style.display = "none"; 
		CopyrightImg.style.display = "none";
		var marker3 = new GMarker(point,Icon);
		//alert("point ="+point+" mrk3="+marker3);
		detailMap.addOverlay(marker3);

		showMinimapRect(detailMap,marker3);
   
  });
}


//ADD LANDMARK THROUGH MAP INTERFACE
function map_add_landmark(form) 
{	
	if(document.getElementById("landmark_name").value=="")
	{
     alert("Please Enter Landmark Name");      
	   document.getElementById("landmark_name").focus();
	   return false;
  }
	
	document.getElementById('save_lnmrk').style.display ='none';
  document.getElementById('wait_lnmrk').style.display ='';
  
  var landmark_name = document.getElementById('landmark_name').value;
	var landmark_point =document.getElementById('landmark_point').value;
	
  landmark_point = landmark_point.replace('(','');
  landmark_point = landmark_point.replace(')','');
  //var landmark_point = lat+" ,"+lng;

	var url = "src/php/map_add_landmark.php?landmark_name="+landmark_name+ "&landmark_point="+landmark_point;
  //alert(url);
      	
  var req = getXMLHTTP();
	req.open("GET", url, false); //third parameter is set to false here
	req.send(null);
	var res = req.responseText;
  document.getElementById('wait_lnmrk').style.display ='none';
  document.getElementById('save_lnmrk').style.display ='';	  
  alert(res);
}


//ADD STATION THROUGH MAP INTERFACE
function map_add_station(form) 
{	
	if(document.getElementById("station_name").value=="")
	{
		alert("Please Enter Station Name");
		document.getElementById("station_name").focus();
		return false;
	}

	if(document.getElementById("customer_no").value=="")
	{
		alert("Please Enter Customer Number");
		document.getElementById("customer_no").focus();
		return false;
	}
	if(isNaN(document.getElementById('radius').value) || document.getElementById('radius').value=="")
	{
		alert("Please enter valid radius in KM");
		document.getElementById("radius").focus();
		return false;
	}	
    
	document.getElementById('save_lnmrk').style.display ='none';
	document.getElementById('wait_lnmrk').style.display ='';

	var station_name = document.getElementById('station_name').value;
	var customer_no = document.getElementById('customer_no').value;		
	var landmark_point = document.getElementById('landmark_point').value;
	var radius = document.getElementById('radius').value;

	landmark_point = landmark_point.replace('(','');
	landmark_point = landmark_point.replace(')','');
  //var landmark_point = lat+" ,"+lng;

	var url = "src/php/map_add_station.php?station_name="+station_name+"&customer_no="+customer_no+"&landmark_point="+landmark_point+"&radius="+radius;
  //alert(url);
      	
  var req = getXMLHTTP();
	req.open("GET", url, false); //third parameter is set to false here
	req.send(null);
	var res = req.responseText;
  document.getElementById('wait_lnmrk').style.display ='none';
  document.getElementById('save_lnmrk').style.display ='';	  
  alert(res);
}
//CLOSE STATION

/*var lnmark= new GIcon();
lnmark.image = 'images/landmark.png';
lnmark.iconSize= new GSize(10, 10);
lnmark.iconAnchor= new GPoint(9, 34);
lnmark.infoWindowAnchor= new GPoint(5, 1);*/
  
//createMarkerLandmark
function create_marker_landmark(point,text) 
{
	var marker = new GMarker(point, lnmark);
	GEvent.addListener(marker,"click", function() {
	 // marker.openInfoWindow(document.createTextNode("<html><body><table><tr><td><font color='blue' size=3><strong>"+text+"</strong></font></td></tr></table></body></html>));

	   marker.openInfoWindow(document.createTextNode(text));
	});
	map.addOverlay(marker);
	return marker;
}



function display_landmark_type(sel_value)
{
  //alert(sel_value);
  if(sel_value == 'landmark')
  {
    document.getElementById('landmark_area').style.display ='';
    document.getElementById('station_area').style.display ='none';
  }
  else
  {
    document.getElementById('landmark_area').style.display ='none';
    document.getElementById('station_area').style.display ='';    
  }
} 


function visible_station()
{
	var client_feature = document.getElementById('station_chk').value;    
	// alert("client_feature="+client_feature);
	if(client_feature=="select")
	{
		alert("Please Select Atleast One Option")
		document.getElementById('station_chk').focus();
		return false;
	}
	else
	{
		if(client_feature=="0" || client_feature=="1")
		{				
			getStation1(client_feature);		
		} 
		else if(client_feature=="none")
		{
			//alert("marker_len="+markerS.length);
			for(var i=0;i<markerS.length;i++)
			{
				map.removeOverlay(markerS[i]);
			}
		}  
	}
}

/*function visible_station()
{
		var station_chk = document.getElementById('station_chk').checked;    
		//alert("station_chk="+station_chk);
		
    var select_value = document.getElementById('station_sel').value;
    //alert("sel_value="+select_value);
		
    if(station_chk)
		{
      var feature_id_map = document.getElementById('station_flag_map').value; 
  		//alert("LP fid:"+feature_id_map);
      if(feature_id_map ==1)
  		{
  		  getStation1(select_value);
      }
    } 
    else
    {
       //alert("marker_len="+markerS.length);
       
       for(var i=0;i<markerS.length;i++)
       {
          map.removeOverlay(markerS[i]);
       }
    }   
}*/

</script>

