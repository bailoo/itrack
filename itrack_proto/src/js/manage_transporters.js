 function action_manage_transporter(action_type)
 {  
    if(action_type=="add")  
    {
  		var obj=document.manage1.manage_id;
  		var result=radio_selection(obj);
  		//alert("result"+result);
  		if(result!=false)
  		{ 	 
  		  var transporter_name=document.getElementById("transporter_name").value; 
        var state=document.getElementById("state").value;
        var city=document.getElementById("city").value;
        var address1=document.getElementById("address1").value;
        var address2=document.getElementById("address2").value;
  		  if(transporter_name=="") 
  		  {
  			alert("Please Enter Transporter Name"); 
  			document.getElementById("transporter_name").focus();
  			return false;
  		  }
  		  else if(state=="") 
  		  {
  			alert("Please Enter state"); 
  			document.getElementById("state").focus();
  			return false;
  		  }
  		  else if(city=="") 
  		  {
  			alert("Please Enter city"); 
  			document.getElementById("city").focus();
  			return false;
  		  }
  		  else if(address1=="") 
  		  {
  			alert("Please Enter address1"); 
  			document.getElementById("address1").focus();
  			return false;
  		  }   
  		  else if(address2=="") 
  		  {
          address2 ="-";
  		  }                  		  
  		  
  		  var poststr = "action_type="+encodeURI(action_type ) + 
  						"&local_account_ids="+encodeURI(result) +
  						"&transporter_name="+encodeURI(transporter_name) +
  						"&state="+encodeURI(state) +
  						"&city="+encodeURI(city) +
  						"&address1="+encodeURI(address1) +
  						"&address2="+encodeURI(address2);
  		}
    }
    else if(action_type=="edit")
    {		
  		var geo_id1=document.getElementById("transporter_id").value; 
  		if(geo_id1=="select")
  		{
  			alert("Please Select Transporter"); 
  			document.getElementById("transporter_id").focus();
  			return false;
  		}       

      var state=document.getElementById("state").value;
      var city=document.getElementById("city").value;
      var address1=document.getElementById("address1").value;
      var address2=document.getElementById("address2").value;

		  if(state=="") 
		  {
			alert("Please Enter state"); 
			document.getElementById("state").focus();
			return false;
		  }
		  else if(city=="") 
		  {
			alert("Please Enter city"); 
			document.getElementById("city").focus();
			return false;
		  }
		  else if(address1=="") 
		  {
			alert("Please Enter address1"); 
			document.getElementById("address1").focus();
			return false;
		  }   
		  else if(address2=="") 
		  {
        address2 ="-";
		  }                  		  
  	
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&transporter_name="+encodeURI(transporter_name) +
						"&state="+encodeURI(state) +
						"&city="+encodeURI(city) +
						"&address1="+encodeURI(address1) +
						"&address2="+encodeURI(address2);
    }
    else if(action_type=="delete")
    {
      var transporter_id=document.getElementById("transporter_id").value;
      if(transporter_id=="select")
      {
        alert("Please Select Transporter"); 
        document.getElementById("transporter_id").focus();
        return false;
      }
    
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&transporter_id=" + encodeURI(transporter_id);
    }
    
    makePOSTRequest('src/php/action_manage_transporter.php', poststr);
}

function show_city(state)
{
  //alert("state="+state);
  var poststr =  "state=" + encodeURI(state);
  
  makePOSTRequest('src/php/manage_transporters_show_city.php', poststr); 
}

function show_transporter_info()
{
  //alert("city="+city);
  var poststr =  "city=" + encodeURI(document.getElementById('city').value);
  
  makePOSTRequest('src/php/manage_transporters_show_info.php', poststr); 
}

function book_vehicle(transporter_id)
{
  //alert("city="+city);
  var poststr =  "transporter_id=" + encodeURI(transporter_id);
  
  makePOSTRequest('src/php/manage_transporters_vehicle_booking.php', poststr); 
}

function action_manage_transporters_vehicle_booking(transporter_id)
{
  //alert("city="+city);
  var poststr = "action_type="+encodeURI("vehicle_booking") + 
                "&user_name="+encodeURI(document.getElementById('user_name').value) + 
                "&email_id=" + encodeURI(document.getElementById('email_id').value) + 
                "&address=" + encodeURI(document.getElementById('address').value) + 
                "&phone=" + encodeURI(document.getElementById('phone').value) + 
                "&dobooking=" + encodeURI(document.getElementById('dobooking').value) + 
                "&placefrom=" + encodeURI(document.getElementById('placefrom').value) + 
                "&placeto=" + encodeURI(document.getElementById('placeto').value) + 
                "&remark=" + encodeURI(document.getElementById('remark').value) + 
                "&transporter_id=" + encodeURI(transporter_id);
  //alert(poststr);                
                  
  makePOSTRequest('src/php/action_manage_transporter.php', poststr); 
}