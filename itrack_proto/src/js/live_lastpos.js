function LP_prev(vserial)
{
	if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  }	
  if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
		alert("Retrieving data ..... plz wait!");
	}

	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
	document.getElementById('prepage').style.visibility='visible';	
	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
  //alert(" DIST IN movingVehicle_prev ="+dist+ " tmp_dist="+tmp_dist);
	//alert("in mprev");
	document.form1.mapcontrol_startvar.value=1;
	startup_var = 1;
	map.clearOverlays();
	
	document.forms[0].vehicleSerial.value = vserial;
	//alert("vserial in moving vehicle ="+document.forms[0].vehicleSerial.value);
  /// REFRESH VALUES
	dist= 0;
	tmp_dist = 0;
	document.form1.vid2.value ="";
	document.form1.lat.value ="";
	document.form1.lng.value ="";
	lm = 0;
	
	geocoder = null;
	address = null;
 	ad=0;
	address1=0;	
	//alert("before load")

	LP_Vehicle();

	//alert("MOVING1");
	//auto_refresh();
	//alert("after load");
}

function LP_Vehicle()
{	
	//alert("in mnext");
	//alert(" DIST IN movingVehicle ="+dist+ " tmp_dist="+tmp_dist);
	tmp_dist = 0;

	var dmode;
	var startdate;
	var enddate;
	var status;
	var pt_for_zoom;
	var zoom_level;
 
 	///////////// get date ////////////////////////
 	var currentDate2 = new Date;
 	var yr = currentDate2.getFullYear();
	var mnt =  currentDate2.getMonth()+1;
	var dt =  currentDate2.getDate();
	var hr = currentDate2.getHours();
	var min = currentDate2.getMinutes();
	var sec = currentDate2.getSeconds();
 	if(mnt>0&&mnt<10)
		mnt = "0"+mnt ;
 	if(dt>0&&dt<10)
		dt = "0"+dt;
 	if(hr>0&&hr<10)
		hr = "0"+hr;
 	if(min>0&&min<10)
		min = "0"+min;
 	if(sec>0&&sec<10)
		sec = "0"+sec;
 	startdate = yr+"-"+mnt+"-"+dt+" 00:00:00";
	enddate = yr+"-"+mnt+"-"+dt+" "+hr+":"+min+":"+sec;

	// pass zoom parameters
 	if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
	{
		status = "ON";
	}
	else
	{
		status = "OFF";
		pt_for_zoom = "0";
		zoom_level = "0";
	}
	
	var vid;
	vid = document.forms[0].vehicleSerial.value;
	//alert(vid+".xml");
 	////////// CALL MAIN FUNCTION ///////////////
	//alert("before load call");
	//alert("st="+startdate+" ed="+enddate);
	var access = document.forms[0].access.value;
	load_lp(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access);
 	document.form1.current_vehicle=1;

	//alert("MOVING2");
}

 ////////////////function load /////////////////////////////////////////////
function load_lp(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access)
{
	//alert("in load");
	Load_MovingData(vid,startdate,enddate,pt_for_zoom,zoom_level,status,access);		
}