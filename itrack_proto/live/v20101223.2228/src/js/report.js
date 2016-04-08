  /////////////////////  REPORT MODULE /////////////////////////////////////////
	
  // GET SELECTED VEHICLE
  function get_selected_vehicle(obj)
	{
    var i;
		var s = obj.elements['vid[]'];		
		var vehicle_str="";
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
      }  
    }	
    return vehicle_str;		
  }    
   	
  
  //1.VEHICLE REPORT
  function report_vehicle()
  {
    //alert("Rizwan:in report");
    makePOSTRequest('src/php/report_vehicle.php', '');
  }
  
  function action_report_vehicle(obj)
  {    
    // GET SELECTED VEHICLES 
    var vehicle_str = get_selected_vehicle(obj);
    	
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
        
    var poststr = "vid=" + encodeURI( vehicle_str ) +
                  "&option=" + encodeURI( option_str );                                   
                  alert("Rizwan:"+poststr);                                                                                              
                       
    makePOSTRequest('src/php/action_report_vehicle.php', poststr);
  }  
  
    
  //1.SPEED REPORT  
  function report_speed()
  {
    makePOSTRequest('src/php/report_speed.php', '');
  }
  
  function action_report_speed(obj)
  {
    // GET SELECTED VEHICLES 
    var vehicle_str = get_selected_vehicle(obj);    
    
    var poststr = "vid=" + encodeURI( vehicle_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+  
                  "&user_interval=" + encodeURI( document.getElementById("user_interval").value );                
                  alert("Rizwan:"+poststr);                                                                                              
                   
    makePOSTRequest('src/php/action_report_speed.php', poststr);
  }      
  
  //1.DISTANCE REPORT  
  function report_distance()
  {   
    makePOSTRequest('src/php/report_distance.php', '');
  }
  
  function action_report_distance(obj)
  {
    // GET SELECTED VEHICLES 
    var device_str = get_selected_vehicle(obj);    
        
    var poststr = "device=" + encodeURI( device_str ) +
                  "&start_date=" + encodeURI( document.getElementById("date1").value )+
                  "&end_date=" + encodeURI( document.getElementById("date2").value )+                  
                  alert("Rizwan:"+poststr);                                                                                                   
                   
    makePOSTRequest('src/php/action_report_distance.php', poststr);
  }        
    
  //1.MONTHLY DISTANCE REPORT  
  function report_monthly_distance()
  {
    makePOSTRequest('src/php/report_monthly_distance.php', '');
  }
  
  function action_report_monthly_distance(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_monthly_distance.php', poststr);
  }        
    
  //1.FUEL REPORT  
  function report_fuel()
  {
    makePOSTRequest('src/php/report_fuel.php', '');
  }
  
  function action_report_fuel(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_fuel.php', poststr);
  }        

  //1.SUMMARY REPORT  
  function report_summary()
  {
    makePOSTRequest('src/php/report_summary.php', '');
  }
  
  function action_report_summary(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_summary.php', poststr);
  }        
  
  //1.VIEW LOGGED DATA REPORT  
  //A.
  function report_datalog_today()
  {
    makePOSTRequest('src/php/report_datalog_today.php', '');
  }
  
  function action_report_datalog_today(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_datalog_today.php', poststr);
  } 
         
  //B.
  function report_datalog_date()
  {
    makePOSTRequest('src/php/report_datalog_date.php', '');
  }
  
  function action_report_datalog_date(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_datalog_date.php', poststr);
  }         
  
  //C.
  function report_datalog_vehicle()
  {
    makePOSTRequest('src/php/report_datalog_vehicle.php', '');
  }
  
  function action_report_datalog_vehicle(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_datalog_vehicle.php', poststr);
  }          
  
  //C.
  function report_datalog_search()
  {
    makePOSTRequest('src/php/report_datalog_search.php', '');
  }
  
  function action_report_datalog_search(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_datalog_search.php', poststr);
  }           
  
  
  //1.HALT REPORT  
  function report_halt()
  {
    makePOSTRequest('src/php/report_halt.php', '');
  }
  
  function action_report_halt(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_report_halt.php', poststr);
  }        
  
  
  //1.AREA VIOLATION ALERT REPORT  
  function alert_area_violation()
  {
    //alert("k");
    makePOSTRequest('src/php/alert_area_violation.php', '');
  }
  
  function action_report_area_violation(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_alert_violation.php', poststr);
  }        
  
  //2.SPEED VIOLATION ALERT REPORT  
  function alert_speed_violation()
  {
    makePOSTRequest('src/php/alert_speed_violation.php', '');
  }
  
  function action_alert_speed_violation(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_alert_speed_violation.php', poststr);
  }        
  
  //1.SPEED GRAPH  
  function graph_daily_speed()
  {   
    makePOSTRequest('src/php/graph_daily_speed.php', '');
  }
  
  function action_daily_graph_speed(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_graph_daily_speed.php', poststr);
  }        
  
  //2.DISTANCE GRAPH 
  function graph_daily_distance()
  {
    makePOSTRequest('src/php/graph_daily_distance.php', '');
  }
  
  function action_graph_distance(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_graph_daily_distance.php', poststr);
  }        
  
  //3.FUEL GRAPH  
  function graph_daily_fuel()
  {
    makePOSTRequest('src/php/graph_daily_fuel.php', '');
  }
  
  function action_graph_daily_fuel(obj)
  {
    var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                  "&user=" + encodeURI( document.getElementById("user").value )+
                  "&grp=" + encodeURI( document.getElementById("grp").value )+                  
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_graph_daily_fuel.php', poststr);
  }        
  
  
  //  Report vehicle
  
  function create_pdf()
	{
		 document.forms[0].target = "_blank";
		 document.forms[0].action="getpdf3.php";
	}

	/*function print_pdf()
	{
		 document.forms[0].target = "_blank";
		 document.forms[0].action="getpdf3.php?print_pdf=1";
	}*/

	function mail_report()
	{
		document.forms[0].target = "_self";
		document.forms[0].action="mail_vehicle_report.php";
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


	function SelectAll(obj)
	{
		//alert("Rizwan:2"+obj);
    if(obj.all.checked)
		{
			var i;
			var s = obj.elements['vid[]'];
			for(i=0;i<s.length;i++)
				s[i].checked="true";		
		}
		else if(obj.all.checked==false)
		{
			var i;
			var s = obj.elements['vid[]'];
			for(i=0;i<s.length;i++)
				s[i].checked=false;
		}   
	}
  
	function updateFields(obj)
	{
		//alert("Rizwan:1"+obj);	
    if(obj.selectall.checked)
		{
			obj.option1.checked="true";
			obj.option2.checked="true";
			obj.option3.checked="true";
			obj.option4.checked="true";
			obj.option5.checked="true";
			obj.option6.checked="true";	
      obj.option7.checked="true";			
		}
		else if(obj.selectall.checked==false)
		{
			obj.option1.checked=false;
			obj.option2.checked=false;
			obj.option3.checked=false;
			obj.option4.checked=false;
			obj.option5.checked=false;
			obj.option6.checked=false;	
      obj.option7.checked=false;			
		} 
	}  
  // END TRIP REPORT	
    
  // REPORT DATA LOG SEARCH
  
  function getScriptPage(div_id,content_id)
	{
		subject_id = div_id;
		content = document.getElementById(content_id).value;
		http.open("GET", "module_report_datalog_search.php?content=" + escape(content), true);
		http.onreadystatechange = handleHttpResponse;
		http.send(null);
		if(content.length>0)
			box('1');
		else
			box('0');				
	}	

	function highlight(action,id)
	{
		//alert('hello1');
		if(action)	
		document.getElementById('word'+id).bgColor = "#C2B8F5";
		else
		document.getElementById('word'+id).bgColor = "#F8F8F8";
	}

	function display(word)
	{
		//alert('hello2');
		document.getElementById('text_content').value = word;
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
	
	/// REPORT DATA LOG SEARCH CLOSED
	
	
	//  REPORT DATA LOG DATE 
	
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
  
  /////////////////////////////////////    