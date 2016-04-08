function codeLatLng(lat,lng,i) 
{
  //alert("lat="+lat+" ,lng="+lng);
  var trycnt=0;
  var point = new GLatLng(parseFloat(lat),parseFloat(lng));
  document.getElementById('status').value = "0";
  get_address(point,i,0);
//  alert("status:"+status);
  var status1 = document.getElementById('status').value;
 // alert("status2:"+status1+" trycnt:"+trycnt+" test_str:"+document.getElementById('test_str').value);
 /* while((status1=="0")  && (trycnt<5))
  {
    alert("status1:"+status1);
    get_address(point);
    status1 = document.getElementById('status').value;
    trycnt++;
  } */ 
}

function get_address(point,cnt,trystat) 
{
 var accuracy;
 var largest_accuracy;	   
 var delay = 100;

 var geocoder = new GClientGeocoder();
 var address_tmp;
 var address1_tmp;
 var BadAddress=0;
 var place;

 geocoder.getLocations(point, function (result) {
 //alert("RES="+result.Status.code);
 
 if (result.Status.code == 200)
 {
	 var j;
   
   for (var i=0; i<result.Placemark.length; i++)
   {
    accuracy = result.Placemark[i].AddressDetails.Accuracy;
    
    address_tmp = result.Placemark[i];
    address1_tmp = address_tmp.address;

	  //alert("address1_tmp="+address1_tmp+" accuracy="+accuracy);         
		if(i==0)
		{
			largest_accuracy = accuracy; 
			j = i;

			if ((address1_tmp.indexOf("NH") !=-1) || (address1_tmp.indexOf("National Highway") !=-1) || (address1_tmp.indexOf("State Highway") !=-1))
			{
				BadAddress = 1;
			}
		}

		else 
	    {	
		   //alert(" largest accuracy="+largest_accuracy+" accuracy="+accuracy+" i="+i);
			if((largest_accuracy < accuracy) || ((BadAddress == 1) && (accuracy>2)))
			{
				largest_accuracy = accuracy;
				//alert("i="+i);
				j = i;
				///alert("j1========="+j);
				if ((address1_tmp.indexOf("NH") !=-1) || (address1_tmp.indexOf("National Highway") !=-1) || (address1_tmp.indexOf("State Highway") !=-1))
				{
					BadAddress = 1;
				}
				else
				{
					BadAddress = 0;
				}
			}
		}
   }
		// alert("largest_accuracy="+largest_accuracy+ " j="+j+" result="+result);
		var address = result.Placemark[j];
		address1 = address.address;	   
		var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]); 
		var distance = calculate_distance_location(point.y, google_point.y, point.x, google_point.x); 
		//alert("dist="+distance);
		var address2 = distance+" km from "+address1;				
		
	}  // if (result.Status.code == G_GEO_SUCCESS)  CLOSED
	else
	{
	  var address2 ="-";
  }	
  
  if(address2!="-")
  {
    document.getElementById('tmp_location').value = document.getElementById('tmp_location').value +"\n"+ cnt+" "+address2;
  }
  else
  {
   // document.getElementById('tmp_location').value = document.getElementById('tmp_location').value +"\n"+ cnt+" "+address2;
    if(trystat<5)
    {
      var date = new Date();
      var curDate = null;
      do { curDate = new Date(); }
      while(curDate-date < 1000);

      get_address(point,cnt,trystat+1);
     // get_address(point,cnt,trystat+1);
    }
//    document.getElementById('status').value = "0";
  }			
  
  document.getElementById('test_str').value = "hello";
  		
	/*var lt_original = point.y;
	var lng_original = point.x;
	var str = lt_original+","+lng_original;
	
	var strURL="src/php/select_landmark_marker.php?content="+str;

	var req = getXMLHTTP();
	req.open("GET", strURL, false); //third parameter is set to false here
	req.send(null);
	var landmark = req.responseText;
	
	//alert("landmark="+landmark);
	//return req.responseText;
	if(landmark!="")
		place = landmark;
	else
		place = address2; */
		
	//alert("place1="+address2);
  
});  
}


function calculate_distance_location(lat1, lat2, lon1, lon2) 
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

function getXMLHTTP_location()
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