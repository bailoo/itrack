function manage_checked_account(local_account)
{
	var account_string="";
	var account_status=document.getElementById('account_status').value;
	var account_status1=account_status.split(":");
	for(var i=0;i<(account_status1.length-1);i++)
	{
		var account_status2=account_status1[i].split(",");
		if(account_status2[0]==local_account.value)
		{
			if(local_account.checked==true)
			{
				account_string=account_string+account_status2[0]+",1"+":";		
			}
			else
			{
				account_string=account_string+account_status2[0]+",0"+":";
			}
		}	
		else
		{
			account_string=account_string+account_status2[0]+","+account_status2[1]+":";			
		}
	}
	var poststr="account_status_1="+account_string+
				"&device_imei_no="+document.getElementById('device_imei_no').value+
				"&action_no="+"second"+
				"&LocalAccount="+local_account.value;
				"&CheckStatus="+local_account.checked;
				//alert("poststr="+poststr);
	makePOSTRequest('src/php/manage_show_device_account.php', poststr); 

}  
  function show_manage_interfaces(type, option)   // option = add_feature
{
	var poststr="account_id_local="+document.getElementById("account_id_local").value;
	makePOSTRequest('src/php/' + type+ '_'+ option + '.php', poststr);   
}
   
   	function manage_show_entity_option(target_file_prev, target_file) // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
	{	
		var poststr = "target_file="+target_file;
		if(target_file_prev=="account_details")
		{
			makePOSTRequest(target_file, '');
		}
		else
		{
			makePOSTRequest(target_file_prev, poststr);
		}
	}
	
	function manage_io_prev_interface(options)
	{
		//alert("test");
		var poststr = "display_type1=" + encodeURI(options);
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_entity_selection_information.php', poststr);
	}
	
	function manage_select_by_entity(filename,options)
	{
		var obj=document.manage1.manage_id;
		var result = radio_selection(obj);
		if(result!=false)
		{
		var poststr = "display_type1=" +options+
						"&account_id_local=" +result;
		//alert("poststr="+poststr);
		makePOSTRequest(filename, poststr);
		}
	}
	function manage_show_file(file_name)
	{
		//alert(file_name);
    makePOSTRequest(file_name, '');
	}
	function manage_show_device_account(file_name)
	{
		poststr="device_imei_no="+document.getElementById('device_imei_no').value;
		makePOSTRequest(file_name, poststr);
	}
   
  function show_interface(file_name,action_type,common_account_id)    
  { 
	//alert(module+action+type);	 //alert("common_accoount_id="+common_account_id);
      var poststr = action_type + "=" + encodeURI(common_account_id);  
      makePOSTRequest(file_name, poststr);   
  }
  
  function manage_edit_prev_interface(file_name,common_account_id)    
  { 
	//alert(module+action+type);	 //alert("common_accoount_id="+common_account_id);
      var poststr = "common_id=" + encodeURI(common_account_id); 

      makePOSTRequest(file_name, poststr);   
  }

  
  function action_manage_account(action_type)
	{
		//alert("action_type="+action_type);
		var result;
		result = validate_manage_add_account(action_type);
		var obj1=document.manage1;	    
		if(result)
		{
			if(action_type == "add")
			{ 			
				var poststr="action_type=" + encodeURI(action_type)+
				"&login=" + encodeURI( document.getElementById("login").value )+
				"&group_id=" + encodeURI( document.getElementById("group_id").value )+
				"&user_name=" + encodeURI( document.getElementById("user_name").value )+
				"&add_account_id=" + encodeURI( document.getElementById("add_account_id").value )+
				//"user_type=" + encodeURI( document.getElementById("user_type").value )+
				"&user_type=test"+
				"&password=" + encodeURI( document.getElementById("password").value )+
				"&ac_type=" + encodeURI(get_radio_button_value1(obj1.elements["ac_type"]))+
				"&company_type=" + encodeURI( get_radio_button_value1(obj1.elements["company_type"]) )+
				"&perm_type=" + encodeURI( get_radio_button_value1(obj1.elements["perm_type"]) )+
				//"admin_perm=" + encodeURI( document.getElementById("admin_perm").value )+
				"&account_feature1="+result; 			    
			} 
			if(action_type == "edit")
			{
				var poststr="action_type=" + encodeURI(action_type)+
							"&edit_account_id=" + encodeURI( document.getElementById("edit_account_id").value )+
							"&user_name=" + encodeURI( document.getElementById("user_name").value )+
							"&ac_type=" + encodeURI(get_radio_button_value1(obj1.elements["ac_type"]))+							
							"&account_feature1="+result; 
			}
			if(action_type == "delete")
			{
				/*var action_type =  "action_type=" + encodeURI( action_type );
				var edit_account = "edit_account=" + encodeURI( document.getElementById("edit_account").value ); */
			}   
				//alert("poststr="+poststr);	
				makePOSTRequest('src/php/action_manage_account.php', poststr);
		}
	}
   
   function action_manage_device(action_type)
   {
	    var obj=document.manage1.elements['io_name[]'];
      var result=checkbox_selection(obj);
	//alert("result="+result);	  
      var result_validaton = validate_manage_add_device(action_type); 
      
      if(result!= false && result_validaton!=false)
      {
        if(action_type == "add")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&imei_no=" + encodeURI( document.getElementById("imei_no").value ) +
                        "&manufacturing_date=" + encodeURI( document.getElementById("manufacturing_date").value )+
                        "&make=" + encodeURI( document.getElementById("make").value )+
						            "&io_ids=" + encodeURI(result);
        }
        else if(action_type == "edit")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&imei_no_edit=" + encodeURI( document.getElementById("imei_no_edit").value ) +
                        "&manufacturing_date_edit=" + encodeURI( document.getElementById("manufacturing_date_edit").value )+
                        "&make_edit=" + encodeURI( document.getElementById("make_edit").value )      
        }      
        else if(action_type == "delete")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&imei_no_edit=" + encodeURI( document.getElementById("imei_no_edit").value );                             
        }        
       alert("Riz:"+poststr);
        makePOSTRequest('src/php/action_manage_device.php', poststr);
      }
   }     
   
   function action_manage_device_sale(obj, action_type)
   {
      var res = false;        
      if(action_type == "add")
        res = validate_manage_add_device_sale(obj);
      else if(action_type =="edit")
        res = validate_manage_edit_device_sale(obj); 
      else if(action_type == "delete")
        res = true;               
              
      //alert("riz:res="+res);
      if(res == true)
      {
        if(action_type == "add" || action_type=="edit")
        {
          var poststr = "action_type=" + encodeURI( action_type ) +
                        "&imei_no=" + encodeURI( obj.imei_no.value ) +
                        "&super_user=" + encodeURI( obj.super_user.value )+
                        "&user=" + encodeURI( obj.user.value );                       
        }       
        else if(action_type == "delete")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&imei_no=" + encodeURI( document.getElementById("imei_no").value );                             
        }            
        makePOSTRequest('src/php/action_manage_device_sale.php', poststr);
      }
   }
      
	function action_manage_vehicle(obj, action_type)
	{
		if(action_type=="edit_action")
		{
			var poststr = "action_type=" + encodeURI(action_type) +
                        "&vehicle_id=" + encodeURI( document.getElementById("vehicle_id").value ) +
                        "&vehicle_name=" + encodeURI( document.getElementById("vehicle_name").value ) +
                        "&vehicle_number=" + encodeURI( document.getElementById("vehicle_number").value )+
                        "&max_speed=" + encodeURI( document.getElementById("max_speed").value )+
                        "&vehicle_tag=" + encodeURI( document.getElementById("vehicle_tag").value )+
                        "&vehicle_type=" + encodeURI( document.getElementById("vehicle_type").value );  
						var file_name="src/php/action_manage_vehicle.php";
		}
		if(action_type=="add")
		{
			var obj=document.manage1.manage_id;
			var result=radio_selection(obj);
			var vehicle_validation1=vehicle_validation();
			if(result!=false && vehicle_validation1!=false)
			{
			var poststr = "action_type=" +action_type+  
						"&add_account_id=" +result+			
                        "&vehicle_name=" + document.getElementById("vehicle_name").value+
                        "&vehicle_number=" +document.getElementById("vehicle_number").value+
                        "&max_speed=" + document.getElementById("max_speed").value +
                        "&vehicle_tag=" + document.getElementById("vehicle_tag").value +
                        "&vehicle_type=" +document.getElementById("vehicle_type").value;  
			}
			var file_name="src/php/action_manage_vehicle.php";
		}
		else if(action_type=="edit" || action_type=="delete")
		{
			var result = validate_manage_vehicle(obj); 		
			if(result != false)
			{
				if(action_type=="edit")
				{
					var file_name="src/php/manage_edit_vehicle.php";
				}
				else if(action_type=="delete")
				{
					txt="Are You Sure You Want To Delete this One";
					if(!confirm(txt))
					{
						return false;
					}
					else
					{
						var file_name="src/php/action_manage_vehicle.php";
					}
				}
				var poststr = "action_type="+action_type+
								"&manage_vehicle_id="+result;		
				
			}
		}
		else if(action_type=="grouping")
		{
			
			var result = validate_manage_grouping(obj);
			if(result != false)
			{
				var poststr = "action_type="+action_type+
								"&manage_vehicle_id="+result;
				alert("poststr="+poststr);
								return false;
			}
			var file_name="src/php/action_manage_vehicle.php";		
		}
		//alert("poststr="+poststr+"file_name="+file_name);
		makePOSTRequest(file_name, poststr);
	} 
	
	function vehicle_validation()
	{
		var vehicle_name=document.getElementById('vehicle_name').value;
		var vehicle_number=document.getElementById('vehicle_number').value;
		var max_speed=document.getElementById('max_speed').value;
		var vehicle_tag=document.getElementById('vehicle_tag').value;
		var vehicle_type=document.getElementById('vehicle_type').value;
		if(vehicle_name=="")
		{alert("Vehicle Name field can not be Empty!");return false;document.getElementById('vehicle_name').focus();}
		if(vehicle_number=="")
		{alert("Vehicle Number field can not be Empty!");return false;document.getElementById('vehicle_number').focus();}
		if(vehicle_name=="")
		{alert("Max Speed field can not be Empty!");return false;document.getElementById('max_speed').focus();}
		if(vehicle_tag=="")
		{alert("Vehicle Tag field can not be Empty!");return false;document.getElementById('vehicle_tag').focus();}
		if(vehicle_type=="select")
		{alert("Select Vehicle Type option!");return false;document.getElementById('vehicle_type').focus();}		
	}
   
   function action_manage_group(action_type)
   {  
		//alert("test="+obj+"action="+action_type);
      var res = false;
      
      if(action_type == "add")
        res = validate_manage_add_group();
      else if(action_type =="edit")
        res = validate_manage_edit_group(); 
      else if(action_type == "delete")
        res = true;            
             
      if(res)
      {
        if(action_type == "add")
        {
          var poststr = "action_type=" + encodeURI( action_type ) +
						"&manage_account_id=" + encodeURI(res) +
                        "&group_name=" + encodeURI( document.getElementById("group_name").value ) +
                        "&remark=" + encodeURI( document.getElementById("remark").value );                        
        }
        else if(action_type == "edit")
        {
          var poststr = "action_type=" + encodeURI( action_type ) +
                        "&group_id_local=" + encodeURI( document.getElementById("group_id_local").value ) +
						"&manage_account_id=" + encodeURI(document.getElementById("edit_account_id").value ) +
                        "&group_name=" + encodeURI( document.getElementById("group_name").value ) +
                        "&remark=" + encodeURI( document.getElementById("remark").value );
                         //alert("Shams:poststr="+poststr);      
        }
		else if(action_type == "delete")
        {
          var poststr =  "action_type=" + encodeURI( action_type ) +
						"&group_id_local=" + encodeURI( document.getElementById("group_id_local").value );
                         //alert("Shams:poststr="+poststr);      
        }
		//alert("Shams:poststr="+poststr); 
        makePOSTRequest('src/php/action_manage_group.php', poststr);
      }
   }
  
   function show_account_type_panel()
   {
     if(document.getElementById("account_type_user").checked)
     {
         document.getElementById("user_panel").style.display="";
         document.getElementById("group_panel").style.display="none";
     }
     else if(document.getElementById("account_type_group").checked)
     { 
        document.getElementById("group_panel").style.display="";
        document.getElementById("user_panel").style.display="none";
     }        
   }        
   
   /*function show_add_assign() 
   {
     makePOSTRequest('src/php/manage_add_assign.php', '');
   }
   
   function show_add_assign_res(obj)
   {
      var poststr = "superuser=" + encodeURI( document.getElementById("superuser").value ) +
                    "&user=" + encodeURI( document.getElementById("user").value )+
                    "&grp=" + encodeURI( document.getElementById("grp").value )+
                    "&password=" + encodeURI( document.getElementById("password").value )+                    
                    "&permission=" + encodeURI( document.getElementById("permission").value )+
                    "&lat_lng=" + encodeURI( document.getElementById("lat_lng").value )+
                    "&refresh_rate=" + encodeURI( document.getElementById("refresh_rate").value )+
                    "&geofencing=" + encodeURI( document.getElementById("geofencing").value )+
                    "&landmark=" + encodeURI( document.getElementById("landmark").value )+
                    "&route=" + encodeURI( document.getElementById("route").value )+
                    "&fuel=" + encodeURI( document.getElementById("fuel").value )+
                    "&trip=" + encodeURI( document.getElementById("trip").value )+
                    "&perm_type=" + encodeURI( document.getElementById("perm_type").value )+
                    "&admin_perm=" + encodeURI( document.getElementById("admin_perm").value );  
                    alert(poststr);                                                                                               
                     
      makePOSTRequest('src/php/action_manage_add_assign.php', poststr);
   }*/
   
   ////////// xxx
   function manage_availability(field_value, file_type)
   {
	//alert("value="+document.getElementById("account_id_local").value);
      if(field_value!="")
      {
		//var account_id_local=document.getElementById("account_id_local").value;
        var poststr ="field_value=" +encodeURI(field_value)+
                      "&file_type=" + encodeURI(file_type);
					  //"&account_id_local=" + encodeURI(document.getElementById("account_id_local").value);
		//alert("poststr="+poststr);
        makePOSTRequest('src/php/manage_availability.php', poststr);
      }
      else
      {
       document.getElementById("available_message").innerHTML="";
      }
   }
   
   function get_radio_selection(obj, input_name)
   {
    var s = document+"."+obj+"."+input_name;
    if(input_name=="device_option")
      var s = document.forms[0].device_option;
    else if(input_name=="vehicle_option")  
      var s = document.forms[0].vehicle_option;
      
    //alert("Rizwan:slen="+s.length);
    
    for(var i=0;i<s.length;i++)
    {       
      //alert("Rizwan:in action1"+s[i].value);
      if(s[i].checked == true)
      {
        var radio_value = s[i].value; 
      }  
    } 
    return radio_value;     
   }
   
   /*function get_radio_selection_1(obj, input_name)
   {
    var s = document+"."+obj+"."+input_name;
    if(input_name=="geofence_option")
      var s = document.forms[0].geofence_option;
    else if(input_name=="vehicle_option")  
      var s = document.forms[0].vehicle_option;
   
    for(var i=0;i<s.length;i++)
    {
      if(s[i].checked == true)
      {
        var radio_value = s[i].value; 
      }  
    } 
    return radio_value;     
   }
   
   function get_radio_selection_2(obj, input_name)
   {
    var s = document+"."+obj+"."+input_name;
    if(input_name=="route_option")
      var s = document.forms[0].route_option;
    else if(input_name=="vehicle_option")  
      var s = document.forms[0].vehicle_option;
    
    for(var i=0;i<s.length;i++)
    {
      if(s[i].checked == true)
      {
        var radio_value = s[i].value; 
      }   
    } 
    return radio_value;     
   } */
      
  function action_manage_device_vehicle_assignment(obj)
  {
      //alert("Rizwan:obj="+obj+" device_option="+device_option+" vehicle_option="+vehicle_option+" imei_no2="+imei_no2+" vid2="+vid2);          
      var res = false;
      res = validate_manage_device_vehicle_assignment(obj);              
      
      if(res == true)
      {
        var poststr = "ls=" + encodeURI( document.getElementById("ls").value )+
                      "&rs=" + encodeURI( document.getElementById("rs").value )+
                      "&action_type=" + encodeURI( "assign" );
        makePOSTRequest('src/php/action_manage_vehicle.php', poststr);
      }
  } 
   
  function get_selected_values(obj, input_name)
  { 
      if(input_name=="deassign_vehicle")
      {
        var selected_values=""
        with (obj) 
        {
          for (var i=0;i<device.length;i++) 
          {
            if (device.options[i].selected) 
            {
              if (selected_values=="")
                //selected_values = device.options[i].text
                selected_values = device.options[i].value
              else
                //selected_values = selected_values + "," + device.options[i].text
                selected_values = selected_values + "," + device.options[i].value
            }
          }
        //alert("selected values are:\n" + selectedvalue);     
        }
      }
      
      if(input_name=="degroup_vehicle")
      {
        var selected_values=""
        with (obj) 
        {
          for (var i=0;i<v_group_str.length;i++) 
          {
            if (v_group_str.options[i].selected) 
            {
              if (selected_values=="")
                //selected_values = v_group_str.options[i].text
                selected_values = v_group_str.options[i].value
              else
                //selected_values = selected_values + "," + v_group_str.options[i].text
                selected_values = selected_values + "," + v_group_str.options[i].value
            }
          }
        //alert("selected values are:\n" + selectedvalue);     
        }
      }      
       return selected_values;
   }        
   
   function action_manage_device_vehicle_deassignment(obj)
   {
      var selected_values = get_selected_values(obj,"deassign_vehicle");
      //alert("Rizwan:selvalues="+selected_values);    
               
      var poststr = "device=" + encodeURI( selected_values )+
                    "&action_type=" + encodeURI( "deassign" )                  
      makePOSTRequest('src/php/action_manage_vehicle.php', poststr);
   }  
   
   function action_manage_vehicle_grouping(obj)
   {
      //alert("Rizwan:obj="+obj+" device_option="+device_option+" vehicle_option="+vehicle_option+" imei_no2="+imei_no2+" vid2="+vid2);          
      var res = false;
      res = validate_manage_vehicle_grouping(obj);              
      
      if(res == true)
      {
        var poststr = "ls=" + encodeURI( document.getElementById("ls").value )+
                      "&rs=" + encodeURI( document.getElementById("rs").value )+
                      "&action_type=" + encodeURI( "group" );
        makePOSTRequest('src/php/action_manage_vehicle.php', poststr);
      }
   } 
   
   function action_manage_vehicle_degrouping(obj)
   {
      var selected_values = get_selected_values(obj,"degroup_vehicle");
      //alert("Rizwan:selvalues="+selected_values);    
               
      var poststr = "v_group_str=" + encodeURI( selected_values )+
                    "&action_type=" + encodeURI( "degroup" )                  
      makePOSTRequest('src/php/action_manage_vehicle.php', poststr);
   }        
   /////////////// xxx  
   
 function manage_add_geofence() 
 {
   makePOSTRequest1('src/php/manage_add_geofence.php', '');
 }
   
   
 function action_manage_geofence(action_type)
 {  
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
	 
		  var add_geo_name=document.getElementById("add_geo_name").value; 
		  var geo_coord=document.getElementById("geo_coord").value;
		  if(add_geo_name=="") 
		  {
			alert("Please Enter Geofence Name"); 
			document.getElementById("add_geo_name").focus();
			return false;
		  }
		  else if(geo_coord=="") 
		  {
			alert("Please Draw Geofence");
			document.getElementById("geo_coord").focus();
			return false;
		  }
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&geo_name="+encodeURI(add_geo_name) +
						"&geo_coord="+encodeURI(geo_coord); 
		}
    }
    else if(action_type=="edit")
    {		
		var geo_id1=document.getElementById("geo_id").value; 
		if(geo_id1=="select")
		{
			alert("Please Select Geofences"); 
			document.getElementById("geo_id").focus();
			return false;
		}       
		var geo_name=document.getElementById("geo_name").value; 
		var geo_coord=document.getElementById("geo_coord").value;
		if(geo_name=="") 
		{
			alert("Please Enter Geofence Name"); 
			document.getElementById("geo_name").focus();
			return false;
		}
		else if(geo_coord=="") 
		{
			alert("Please Draw Geofence");
			document.getElementById("geo_coord").focus();
			return false;
		}
		var poststr ="action_type="+encodeURI(action_type ) + 
		"&local_account_ids="+encodeURI(result) +
		"&geo_id="+encodeURI(geo_id1) +
		"&geo_name="+encodeURI(document.getElementById("geo_name").value ) +
		"&geo_coord="+encodeURI(document.getElementById("geo_coord").value); 

    }
    else if(action_type=="delete")
    {
      var geo_id1=document.getElementById("geo_id").value;
      if(geo_id1=="select")
      {
        alert("Please Select Geofences"); 
        document.getElementById("geo_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Close Without Saving or Drawing";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&geo_id=" + encodeURI(geo_id1);
    }
	else if(action_type=="assign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{
			var form_obj1=document.manage1.geo_id;
			var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			if(radio_result!=false)
			{
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result + 
                    "&geofence_id=" +radio_result;
			}					
		}			
	}
	else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result;							
		}			
	}
	//alert("poststr="+poststr);
    makePOSTRequest('src/php/action_manage_geofence.php', poststr);
 }
   
 function manage_add_route() 
 { 
   makePOSTRequest('src/php/manage_add_route.php', '');
 }
 
 function show_vehicles(obj) 
 {
   poststr="create_id="+encodeURI(obj);
   makePOSTRequest('src/php/manage_availability.php', poststr);
 }
 
 function action_manage_route(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_route_name=document.getElementById("add_route_name").value; 
		  var route_coord=document.getElementById("route_coord").value;
		  if(add_route_name=="") 
		  {
			alert("Please Enter Route Name"); 
			document.getElementById("add_route_name").focus();
			return false;
		  }
		  else if(route_coord=="") 
		  {
			alert("Please Draw Landmark Route");
			document.getElementById("route_coord").focus();
			return false;
		  }
		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&route_name="+encodeURI(add_route_name) +
						"&route_coord="+encodeURI(route_coord); 
		}
    }
    else if(action_type=="edit")                                               
    {      
      if(route_id=="select")
      {
        alert("Please Select Route Name"); 
        document.getElementById("route_id").focus();
        return false;
      }       
      var edit_route_name=document.getElementById("edit_route_name").value; 
      var edit_route_coord=document.getElementById("edit_route_coord").value;
      if(edit_route_name=="") 
      {
        alert("Please Enter Route Name"); 
        document.getElementById("edit_route_name").focus();
        return false;
      }
      else if(edit_route_coord=="") 
      {
        alert("Please Draw Route");
        document.getElementById("edit_route_coord").focus();
        return false;
      }
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                    "&route_id="+encodeURI( route_id ) +
                    "&edit_route_name="+encodeURI(edit_route_name) +
                    "&edit_route_coord="+encodeURI(edit_route_coord);    
    }
    else if(action_type=="delete")
    {
     if(route_id=="select") 
     {
        alert("Please Select Route Name");        
        document.getElementById("route_id").focus();
        return false;
     }
      var txt="Are You Sure You Want To Close Without Saving or Drawing";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&route_id=" + encodeURI(route_id);  
    }
	else if(action_type=="assign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{
			var form_obj1=document.manage1.route_id;
			var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			if(radio_result!=false)
			{
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result + 
                    "&route_id=" +radio_result;
			}					
		}			
	}
	else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result;							
		}			
	}
    makePOSTRequest1('src/php/action_manage_route.php', poststr);
 }
   
      
   
 function manage_add_landmark() 
 {
   makePOSTRequest('src/php/manage_add_landmark.php', '');
 }
 
 function action_manage_landmark(action_type)
 {
	  var account_id_local=document.getElementById("account_id_local").value;
    
    if(action_type=="add" || action_type=="edit") 
    {        
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
      
  		if(result!=false)
  		{
        var landmark_name=document.getElementById("landmark_name").value;     
        var landmark_point=document.getElementById("landmark_point").value;     
        var zoom_level=document.getElementById("select_zoom_level").value;
  
        if(landmark_name=="") 
        {
          alert("Please Enter Landmark Name"); 
          document.getElementById("landmark_name").focus();
          return false;
        }
        else if(landmark_point=="") 
        {
          alert("Please Draw Landmark Point");
          document.getElementById("landmark_point").focus();
          return false;
        }
        
        else if(zoom_level=="select") 
        {
          alert("Please Select Zoom Level");
          document.getElementById("zoom_level").focus();
          return false;
        }  	 
  		}  
      
      var poststr = "action_type=" + action_type +
      "&account_id_local=" + encodeURI(account_id_local) +		 
      "&landmark_name=" + encodeURI(landmark_name) +
      "&landmark_point=" + encodeURI(landmark_point)+
      "&zoom_level=" + encodeURI(zoom_level);                      
    }  

    if(action_type=="edit")
    {
      var landmark_id=document.getElementById("landmark_id").value; 
      var poststr = "action_type=" + action_type +
                "&landmark_id=" +landmark_id+
    	          "&account_id_local=" + account_id_local+
                "&landmark_name=" +landmark_name+
                "&landmark_point=" +landmark_point+
                "&zoom_level=" +zoom_level				 
    }  
  	else if(action_type=="delete")
  	{
  		var landmark_id=document.getElementById("landmark_id").value
  		if(landmark_id=="select") 
  		{
  			alert("Please Select Landmark Name");        
  			document.getElementById("landmark_id").focus();
  			return false;
  		}
  		var txt="Are You Sure You Want To Delete This One";
  		if(!confirm(txt))
  		{
  			return false; 
  		}
  		else
  		{
  			var poststr = "action_type=" + action_type +
  			"&account_id_local=" + encodeURI(account_id_local) +
  			"&landmark_id=" + encodeURI(landmark_id);    
  		}
	}   
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/action_manage_landmark.php', poststr);
	}   
   
function manage_availability_1(obj, source, type)
{
    // TYPE MAY BE - IMEINO, SUPER USER, USER
    //alert("Rizwan:s="+source+" t="+type)
    if(document.getElementById(source).value!="")
    {
      var poststr = source+"=" +encodeURI( document.getElementById(source).value )+
                    "&type=" + encodeURI( type );
      //alert("Rizwan:source="+source+" type="+type);
      makePOSTRequest('src/php/manage_availability.php', poststr);
    }
    else
    {
     document.getElementById("available_message").innerHTML="";
    }
}

function violation_get_selected_values(obj, input_name)
{
    var param=document.getElementById(input_name); 
    selected_values="";      
    for (var i=0;i<param.length;i++) 
    {
      if(param.options[i].selected) 
      {
        if (selected_values=="")
          //selected_values = device.options[i].text
          selected_values = param.options[i].value
        else
          //selected_values = selected_values + "," + device.options[i].text
          selected_values = selected_values + "," + param.options[i].value
      }
     // alert("shams:"+selected_values);
    }        
   return selected_values;
 }
 
 function show_geo_coord(obj)
 {
    var geo_id=document.getElementById("geo_id").value;
    if(geo_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "geo_id=" + encodeURI( document.getElementById("geo_id").value);
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
    }
 }
   
 function show_route_coord(obj)
 {
    var route_id=document.getElementById("route_id").value;
    if(route_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "route_id=" + encodeURI( document.getElementById("route_id").value);
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
    }
 }
   
 function show_landmark_coord(obj)
 {
    var landmark_id=document.getElementById("landmark_id").value;
    if(landmark_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "landmark_id=" + encodeURI( document.getElementById("landmark_id").value);
      //alert("postr="+poststr);
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
    }
 }
   
 //////////////////////////////////////////////////geofencing js/////////////////
   
var COLORS = [["red", "#ff0000"], ["orange", "#ff8800"], ["green","#008000"],
              ["blue", "#000080"], ["purple", "#800080"]];
			  
var options = {};
var lineCounter_ = 0;
var shapeCounter_ = 0;
var markerCounter_ = 0;
var colorIndex_ = 0;
var featureTable_; 
var common_event; 

function showCoordinateInterface(param_1)
{
	///////////for visibility of map in the hidden div pop /////////////////
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup").style.display = "block"; 
	///////////////////////////////////////////////////////////////////////////

  common_event=param_1; 
  if(common_event=="landmark") ////////only for landmark
  {
    manage_landmark(common_event);   
  }
  else///////for geofencing and route both ////////////
  {   	 
    if(common_event=="geofencing")
    {
       document.getElementById("close_geo_route_coord").value = document.getElementById("geo_coord").value; // kept last geo coord details for closing pop up div 
    }   
    else if(common_event=="route")
    {
      document.getElementById("close_geo_route_coord").value = document.getElementById("route_coord").value; // kept last geo coord details for closing pop up div
    }
  	map = new GMap2(document.getElementById("map_div")); 	
		map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5); 
		map.setUIToDefault();		
    manage_draw_geofencing_route();
  } 
}

function getColor(named){return COLORS[(colorIndex_++) % COLORS.length][named ? 0 : 1];}

var poly_type; /////////it is both for polygon or polyline 
var coord;  

function manage_draw_geofencing_route()                                                    
{    
    var color = getColor(false); 
    if(common_event=="geofencing") 
    {
      coord=document.getElementById("geo_coord").value;
      poly_type=GPolygon;
      var id_param="geo_coord";      
    }
    else if(common_event=="route")
    {
        coord=document.getElementById("route_coord").value;       
        poly_type=GPolyline;        
        var id_param="route_coord";
    }    
    if(coord!="")
    {         
      var coord_test = (((((coord.split('),(')).join(':')).split('(')).join('')).split(')')).join(''); 
      var coord1 = coord_test.split(":");
    
    	var point;
    	var bounds = new GLatLngBounds();
    
    	for(var z=0;z<coord1.length;z++)
    	{
    		var coord2 = coord1[z].split(",");
    		point = new GLatLng(parseFloat(coord2[0]),parseFloat(coord2[1]));		
    		bounds.extend(point); 			
    	}		
  
    	var center = bounds.getCenter(); 	
    	var zoom = map.getBoundsZoomLevel(bounds); 	
    	map.setCenter(center,zoom);
    	
    	var coord1_length= coord1.length ;
      var gLL_array=new Array();
      var lat = new Array();
      var lng = new Array();
      for(var i=0 ;i<coord1_length;i++)
      {
        var coord1_i=coord1[i].split(",");
        lat[i] =coord1_i[0];
        lng[i] =coord1_i[1];
        gLL_array[i] = new GLatLng(lat[i],lng[i]);
      }
      poly_type= new poly_type(gLL_array);           
    }
    else
    {
       var poly_type = new poly_type([], color, 2, 0.7, color, 0.2);
      // var edit_flag=1;
    }        	 
       
    startDrawing(id_param,poly_type, "Shape " + (++shapeCounter_), function()     
    {
      var cell = this; 
      if(common_event=="geofencing") 
      {     
        var area = poly_type.getArea();
      }
      cell.innerHTML = (Math.round(area / 10000) / 100) + "km<sup>2</sup>";     
     // var len = poly_type.getLength();
	    var vert = poly_type.getVertexCount();
      var poly_points = new Array();  
    	for(var i=0; i < vert; i++)
    	{	
    		poly_points[i] = poly_type.getVertex(i);
    	}     	
    	document.getElementById(id_param).value=poly_points;
      //alert("poly_point"+poly_points);       	
    }, color);
}// JavaScript Document

function startDrawing(id_param,poly, name, onUpdate, color)
{
  //alert("test");
  map.addOverlay(poly);
  var vert;  
  poly.enableDrawing(options);  

  poly.enableEditing({onEvent: "mouseover"});  
  poly.disableEditing({onEvent: "mouseout"});    
  var poly_points = new Array();
   
  GEvent.addListener(poly, "endline", function()
  {	
  	vert = poly.getVertexCount(); 	
  	for(var i=0; i < vert; i++)  
    {	
  		poly_points[i] = poly.getVertex(i);  			
  	}
   	var cells = addFeatureEntry(name, color);
 		document.getElementById(id_param).value=poly_points;
    //alert("poly_point"+poly_points);
	  GEvent.bind(poly, "lineupdated", cells.desc, onUpdate);
	
    GEvent.addListener(poly, "click", function(latlng, index)
    {
      if (typeof index == "number")
      { 			
			  poly.deleteVertex(index); 
    		vert = poly.getVertexCount();		
    		poly_points.slice(0,vert);
       // document.getElementById(id_param).value=poly_points;        		 
      } 
      else
      {
        var newColor = getColor(false);
        cells.color.style.backgroundColor = newColor
        poly.setStrokeStyle({color: newColor, weight: 4});
		
      }
    });
  });
}

function addFeatureEntry(name, color) 
{
  currentRow_ = document.createElement("tr");
  var colorCell = document.createElement("td");
  currentRow_.appendChild(colorCell);
  colorCell.style.backgroundColor = color;
  colorCell.style.width = "1em";
  var nameCell = document.createElement("td");
  currentRow_.appendChild(nameCell);
  nameCell.innerHTML = name;
  var descriptionCell = document.createElement("td");
  currentRow_.appendChild(descriptionCell);
  return {desc: descriptionCell, color: colorCell};
}

///////////////geofencing and route handling feature for pop up map /////////////


function clear_initialize()
{
    if(common_event=="geofencing")
     {
       document.getElementById("geo_coord").value = "";
            
     }
     else if(common_event=="route")
     { 
        document.getElementById("route_coord").value = "";        
     }
    if (GBrowserIsCompatible())
    {	
  			map = new GMap2(document.getElementById("map_div"));
  		  map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
  	    map.setUIToDefault();   																	  
  	} 
}  
 
 function save_route_or_geofencing()
 { 
    if(common_event=="geofencing")
    {
      var coord_point=document.getElementById("geo_coord").value;
      var div_id_string="geo_coord";
    }
    else if(common_event=="edit_geofencing")
    {
      var coord_point=document.getElementById("edit_geo_coord").value;
      var div_id_string="edit_geo_coord";
    }
    else if(common_event=="route")
    {
      var coord_point=document.getElementById("route_coord").value;
      var div_id_string="route_coord";
    }
    else if(common_event=="edit_route")
    {
      var coord_point=document.getElementById("edit_route_coord").value;
      var div_id_string="edit_route_coord";
    }
    
    if(coord_point=="")
    {
      alert("Please Draw Geofencing");
      return false;
    }
    else
    {
      div_close_block();
      document.getElementById(div_id_string).value =  coord_point;
    } 
           
 }
 
function close_div()
{
   var txt="Are You Sure You Want To Close Without Saving or Drawing";
   if(!confirm(txt))
   {
     return false; 
   }   
   if(common_event=="geofencing")
   {
        var final_coord_point=document.getElementById("close_geo_route_coord").value;
        document.getElementById("geo_coord").value =  final_coord_point;
   }
    if(common_event=="route")
   {     
      var final_coord_point=document.getElementById("close_geo_route_coord").value;
      document.getElementById("route_coord").value =  final_coord_point;
   } 
   div_close_block();
}


function div_close_block()
{
  document.getElementById("blackout").style.visibility = "hidden";
	document.getElementById("divpopup").style.visibility = "hidden";
	document.getElementById("blackout").style.display = "none";
	document.getElementById("divpopup").style.display = "none";
	return true;
}	
 ////////////////////////////////close handling features ////////////////////

 /////////////////////////landmark//////////

	function manage_landmark(param)
	{
	//alert("param="+param);
		if(GBrowserIsCompatible())
		{	
		var lat_lng=document.getElementById("landmark_point").value; 
		if(lat_lng!="")  
		{     
			landmark_map_part(lat_lng);
			document.getElementById("prev_landmark_point").value=lat_lng;
			lat_lng=lat_lng.split(",");   
			var point=new GLatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));
			var lat=point.lat();  var lng=point.lng();        
			
			var iwform = '<div style="height:10px"></div><table>'
			+'<tr><td style="font-size:11px;">'+point+'</td></tr>'               
			+'</table><div style="height:10px"></div>'			 					
			+'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\'landmark_point\')" /></center>';
												   
			var marker = new GMarker(point, lnmark);
			alert("markers="+marker);
			GEvent.addListener(marker, "click", function()
			{
				lastmarker = marker;
				document.getElementById("landmark_point").value=lat+","+lng;
				marker.openInfoWindowHtml(iwform);
			});
			map.addOverlay(marker);    
		}
		else
		{
			//alert("in else");
			var lat_lng="";        
			landmark_map_part(lat_lng);
			map.clearOverlays();
		}

		var lastmarker;    
		GEvent.addListener(map,"click",function(overlay,point)
		{
			document.getElementById("zoom_level").value=map.getZoom();
			if (!overlay)
			{
				//alert("point_id="+point_id+"zoome_id="+zoom_id)
				map.clearOverlays(); 
				createInputMarker(point);
			}
		});     
	}
	else 
	{
		alert("Sorry, the Google Maps API is not compatible with this browser");
	}
}

	function landmark_map_part(lat_lng)
	{
		if(lat_lng=="")
		{
			var zoom=5;
		}
		else
		{
			var zoom=parseInt(document.getElementById("zoom_level").value);
		}
		map = new GMap2(document.getElementById("landmark_map"));	
		map.setCenter(new GLatLng(22.755920681486405,78.2666015625), zoom);
		map.setUIToDefault();
	}

function createInputMarker(point) 
{
    var lat_1=point.lat();
    var lng_1=point.lng();  
       
    document.getElementById("landmark_point").value=lat_1+","+lng_1;   
   
    var iwform = '<div style="height:10px"></div><table>'
                 +'<tr><td style="font-size:11px">('+lat_1+', '+lng_1+')</td></tr>'               
                 +'</table><div style="height:10px"></div>'			 					
			           +'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\'landmark_point\')" /></center>';
			           
  	var marker = new GMarker(point, lnmark);
  	GEvent.addListener(marker, "click", function()
    {
  	  lastmarker = marker;
  	  document.getElementById("landmark_point").value=lat+","+lng;
  	  marker.openInfoWindowHtml(iwform);
  	});
  	map.addOverlay(marker);
  	marker.openInfoWindowHtml(iwform);
  	lastmarker=marker;
  	return marker;
}

 function save_landmark_details(point_id)
 {
    var coord_point=document.getElementById("landmark_point").value;
    if(coord_point=="")
    {
      alert("Please Enter Points");
      return false;
    }
    else
    {
      div_close_block();
      document.getElementById("landmark_point").value =  coord_point;
    }
    return false;
 }

function close_landmark_div(close_pararm)
{
   var txt="Are You Sure You Want To Close Without Saving Points";
   if(!confirm(txt))
   {
     return false; 
   }
   document.getElementById("landmark_point").value="";    /////// at the time of add landmark
   document.getElementById("landmark_point").value=document.getElementById("prev_landmark_point").value;  ///at the time of edit landmark
   prev_landmark_point
   div_close_block();
}
//////////////////////////////// close landmark /////////////////
  
function new_exist_route_geo_lanmark(param)
{
   if(param=="geofencing")
   {
     var select_param=document.getElementById("geo_id").value;
     document.getElementById("add_geo_name").value = "";
     document.getElementById("geo_coord").value = "";
   }
   else if(param=="route")
   {
      var select_param=document.getElementById("route_id").value;
      document.getElementById("add_route_name").value = ""; 
      document.getElementById("route_coord").value = "";
   }
   else if(param=="landmark")
   {
      var select_param=document.getElementById("landmark_id").value;
      document.getElementById("add_landmark_name").value = ""; 
      document.getElementById("landmark_point").value = "";
   }
	 var radio_object=document.thisform.new_exist;
	for(var i=0;i<radio_object.length;i++)
	{
		if(radio_object[i].checked)
		{
			var object_value=radio_object[i].value;
			if(object_value=="new")
			{
        document.getElementById("coord_area").style.display="none";
				document.getElementById("exist_fieldset").style.display="none";
				document.getElementById("new_fieldset").style.display="";
				document.getElementById('available_message').innerHTML="";
			}
			else if(object_value=="exist")
			{
			  if(select_param=="select")
			  {
			    document.getElementById("coord_area").style.display="none";
			  }
			  else
			  {
			   document.getElementById("coord_area").style.display="";
        }
				document.getElementById("new_fieldset").style.display="none";
				document.getElementById("exist_fieldset").style.display="";
				document.getElementById('available_message').innerHTML ="";
			}
		}
	}	
}

function select_manage_edit_options(options)
{
	//var display_type="group";
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}
function select_manage_assignment_options(options)
{
	//var display_type="group";
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/manage_assignment_selection_information.php', poststr);
}

function select_manage_deassignment_options(options)
{
	//var display_type="group";
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/manage_deassignment_selection_information.php', poststr);
}

function select_manage_register_options(options)
{
	//var display_type="group";
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/manage_register_selection_information.php', poststr);
}

function select_manage_deregister_options(options)
{
	//var display_type="group";
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/manage_deregister_selection_information.php', poststr);
}

/*function select_manage_usertype(root)
{
	var display_type="usertype";
	var poststr = "display_type1=" + encodeURI(display_type);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}
function select_manage_user(root)
{
	var display_type="user";
	var poststr = "display_type1=" + encodeURI(display_type);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}

function select_manage_vehicle_tag(root)
{
	var display_type="vehicletag";
	var poststr = "display_type1=" + encodeURI(display_type);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}

function select_manage_vehicle_type(root)
{
	var display_type="vehicletype";
	var poststr = "display_type1=" + encodeURI(display_type);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}

function select_manage_vehicle(root)
{
	var display_type="vehicle";
	var poststr = "display_type1=" + encodeURI(display_type);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}

function select_manage_all_vehicle(root)
{
	var display_type="all";
	var poststr = "display_type1=" + encodeURI(display_type);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.php', poststr);
}*/

function portal_manage_vehicle_information(value)
{
	var poststr = "vehicle_id=" + encodeURI(value);
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/portal_manage_vehicle_information.php', poststr);
}

////////////////////////////////////  VALIDATION  ///////////////////

function validation_group(module,action,type)
{
	var obj=document.manage1.group_option;
	if(action=="add")
	{
		var dumy_value="dumy";		
		show_interface(module,action,type,dumy_value);		
	}
	else if(action=="edit")
	{
	  var result=check_selection(obj);
	  if(result!=false)
	  {		
		show_interface(module,action,type,result);
	  }		
	}
	else if(action=="delete")
	{
		var result=check_selection(obj);
		if(result!=false)
		{			
			var return_result=delete_confirmation(result);
			if(return_result!=false)
			{
				var delete1="delete";
				var poststr = "action_type=" + encodeURI(delete1)+
							  "&manage_account_group_id=" + encodeURI(result); 
				//alert("poststr="+poststr);                                                                                                               
				makePOSTRequest('src/php/action_manage_group.php', poststr);			
			}
		}		
	}
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
	    //alert("checkbox_id="+id);
		return id;
	}
}

function delete_confirmation(id)
{
	txt="Are You Sure You Want To Delete this Option";
	if(!confirm(txt))
	{
		return false;	
	}
	else
	{
		return true;
	}
}

function validation_user(file_name,action_type)
{
	var obj=document.manage1.manage_id;
	if(action_type=="add")
	{
		//var dumy_variable="dumy";
		var result=radio_selection(obj);
		//alert("result="+result);
		if(result!=false)
		{
			show_interface(file_name,action_type,result);
		}
	}
	else if(action_type=="edit")
	{
		var result=radio_selection(obj);		
		if(result!=false)
		{		
			show_interface(file_name,action_type,result);
		}
	}	
	else if(action_type=="delete")
	{
		var result=radio_selection(obj);
		if(result!=false)
		{			
			var return_result=delete_confirmation(result);
			if(return_result!=false)
			{
				var delete1="delete";
				var poststr = "action_type=" + encodeURI(delete1)+
							  "&manage_account_id=" + encodeURI(result); 
				//alert("poststr="+poststr);                                                                                                               
				makePOSTRequest('src/php/action_manage_account.php', poststr);			
			}
		}				
	}
}

function manage_edit_prev(file_name)
{	
	var obj=document.manage1.manage_id;
	var result=radio_selection(obj);		
	if(result!=false)
	{		
		manage_edit_prev_interface(file_name,result);
	}
}
function manage_edit_vehicle_prev(file_name)
{	
	var obj=document.manage1.manage_id;
	var obj1=document.manage1;
	
	var result=radio_selection(obj);
	var vehicle_result=radio_vehicle_option_selection(obj);	
	var options_value=manage_tree_validation(obj1);
	if(result!=false && vehicle_result!=false && options_value!=false)
	{		
      var poststr = "account_id_local="+result+
					"&vehicle_display_option="+vehicle_result+
					"&options_value="+options_value;
		//alert("poststr="+poststr);
		makePOSTRequest(file_name,poststr);
	}
}
function radio_vehicle_option_selection()
{
	var obj=document.manage1.vehicle_display_option;
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
		alert("Please checked atleast one vehicle option"); 
		return false;		
	}
	else
	{
		return vehicle_option;
	}
	alert("vehicle_option="+vehicle_option);
}

function validation_landmark_user(file_name)
{
	var obj=document.manage1.manage_user;	
	var result=check_selection(obj);
	if(result!=false)
	{	
		var poststr = "&manage_account_id=" + encodeURI(result); 	
		makePOSTRequest(file_name, poststr);
	}		
}


function validate_manage_add_account(action_type)
{ 
	var obj=document.manage1;
	
	if(action_type=="add" || action_type=="edit")
	{
		if(action_type=="add")
		{
			var login = document.getElementById("login").value;
			var password = document.getElementById("password").value;
			var password2 = document.getElementById("re_enter_password").value;
			var perm_type = get_radio_button_value1(obj.elements["perm_type"]);								
		}
		if(action_type=="add" || action_type=="edit")
		{
			var user_name = document.getElementById("user_name").value;	
			var ac_type = get_radio_button_value1(obj.elements["ac_type"]);			
			var account_feature1=obj.elements['account_feature[]'];	
		}		

		if(login=="")
		{alert("Login field can not be Empty!");return false;}
		
		if(password=="")
		{alert("Password field can not be Empty!");return false;}
		
		if(password!=password2)
		{alert("Password field do not match!");return false;} 
		
		if(user_name=="")
		{alert("User Name can do not be Empty!");return false;} 
		
		if(ac_type==-1)
		{alert("Must Select Atleast one Permission option!");return false;}	
		
		if(perm_type==-1)
		{alert("Must Select Atleast one Admin Permission option!");return false;} 
		if(action_type=="add" || action_type=="edit")
		{
			var account_feature2 = "";
			var flag=0;
			if(account_feature1.length!=undefined)
			{
				for(var i=0;i<account_feature1.length;i++)
				{
					if(account_feature1[i].checked==true)
					{						
						account_feature2 = account_feature2+"1,"+ account_feature1[i].value+":";
						flag=1;
					}
					else
					{
						account_feature2 = account_feature2+ "0,"+account_feature1[i].value+":";
					}
				}
			}
			else if(account_feature1.length==undefined)
			{
				if(account_feature1.checked==true)
				{
					account_feature2 = account_feature2+"1,"+account_feature1.value;
					flag=1;
				}				
			}
		}

		if(flag==0)
		{
			alert("Please Select Atleast One Account Feature");
			return false;
		}
		
		return account_feature2;
	}
}  



function validate_manage_vehicle(obj)
{
	var vehicle_obj=obj.elements['vehicle_id'];
	//alert("obj="+vehicle_obj.length);
	var flag=0;
	if(vehicle_obj.length!=undefined)
	{
		for(i=0;i<vehicle_obj.length;i++)
		{ 
			if(vehicle_obj[i].checked==true)
			{
				var id=vehicle_obj[i].value;
				flag=1;
			}
		}
	}
	else
	{
		if(vehicle_obj.checked==true)
		{
			var id=vehicle_obj.value;
			flag=1;
		}	
	}
	
	if(flag==0)
	{
		alert("Please Select Atleast One Vehicle");
		return false;
	}
	else
	{
		return id;
	}
}


function manage_io_validation()
{
	var final_type_and_value = "";
	var final_vehicle_id = "";
	var vehicle_id=new Array();
	var io_type=new Array();
	var io_type_value=new Array();
	var io_type_value1=new Array(); //////useing for io validation
	var vehicle_id_obj=document.manage.elements['vehicle_id[]'];

	var num1=0;   var veh_count=0;	var type_count=0; 	
	if(vehicle_id_obj.length!=undefined)
	{
		for(i=0;i<vehicle_id_obj.length;i++)
		{
			if(vehicle_id_obj[i].checked)
			{				
                var id_string='io_type'+vehicle_id_obj[i].value+'[]';		
				var io_type_obj=document.manage.elements[id_string];			
				var num2=0;	
				
				for(j=0;j<io_type_obj.length;j++)
				{
					var io_name=io_type_obj[j].value;							
					var id_string_1=vehicle_id_obj[i].value+io_name;										
					var io_name_value=document.getElementById(id_string_1).value;
						//alert("io_name_value="+io_name_value);				
					if(io_name_value!="select")
					{
						io_type[type_count]=io_name;
						io_type_value[type_count]=io_name_value;												
						final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":";
						io_type_value1[type_count]=vehicle_id_obj[i].value+","+io_name_value; // io for validateion			
						num2 = 1;
						type_count++;
					}				
				}
				
				vehicle_id[veh_count] =  vehicle_id_obj[i].value;				
				final_type_and_value=final_type_and_value+"#";	
				final_vehicle_id=final_vehicle_id+vehicle_id[veh_count]+",";	
				veh_count++;				
				num1 = 1;
			}
		}
	}
	else
	{
		if(vehicle_id_obj.checked)
		{
			vehicle_id[veh_count] =  vehicle_id_obj[i].value;
			io_type_value[veh_count]=document.getElementById(vehicle_id_obj[i]).value;	
			io_combo_value[veh_count]=document.getElementById(io_type_value[cnt]).value;
			num1 = 1;
		}
	}
	//alert("final_num2="+num2);
	if(num1==0)
	{
		alert("Please Select At Least One Vehicle");							
			return false;  			
	}
	else if(num2==0)
	{
		alert("Please Select At Least One IO Type");							
			return false;  			
	}
	else
	{
		for(var m=0;m<type_count;m++)
		{
			for(n=0;n<m;n++)
			{
				 //alert(io_type_value[n]+","+io_type_value[m]);
				if(io_type_value1[n]==io_type_value1[m])
				{
					alert("IO Should Not Be Same");
					return false;
				}
			}
		}					
		
		var poststr = "vehicle_ids=" +final_vehicle_id+
		"&types=" +final_type_and_value;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_final_io_assignment.php', poststr);
	}			
}

function select_all_assigned_vehicle(obj)
{
	if(obj.all_vehicle.checked)
	{
		var i;
		var s = obj.elements['vehicle_id[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all_vehicle.checked==false)
	{
		var i;
		var s = obj.elements['vehicle_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}




           
function validate_manage_add_device(obj)
{  
  //alert("Riz:in validate device");
  var imei_no = document.getElementById("imei_no").value;
  var manufacturing_date = document.getElementById("manufacturing_date").value;
  var make = document.getElementById("make").value;
  
  if(imei_no == "")
  {
    alert("Device IMEI No field can not be Empty!");
    return false;
  }
  if(manufacturing_date == "")
  {
    alert("Manufacturing Date field can not be Empty!");
    return false;    
  }
  if(make == "")
  {
    alert("Make field can not be Empty!");
    return false;    
  }  
  return true;
}

function validate_manage_edit_device(obj)
{  
  var imei_no_edit = document.getElementById("imei_no_edit").value;
  var manufacturing_date_edit = document.getElementById("manufacturing_date_edit").value;
  var make_edit = document.getElementById("make_edit").value;
  
  if(imei_no_edit == "")
  {
    alert("Device IMEI No field can not be Empty!");
    return false;
  }
  if(manufacturing_date_edit == "")
  {
    alert("Manufacturing Date field can not be Empty!");
    return false;    
  }
  if(make_edit == "")
  {
    alert("Make field can not be Empty!");
    return false;    
  }  
  return true;
}

function validate_manage_add_vehicle(obj)
{  
   var vehicle_name = document.getElementById("vehicle_name").value;
   var vehicle_number = document.getElementById("vehicle_number").value;
   var max_speed =  document.getElementById("max_speed").value;
   var vehicle_tag = document.getElementById("vehicle_tag").value;
   var vehicle_type = document.getElementById("vehicle_type").value;        
        
  if(vehicle_name == "")
  {
    alert("VehicleName field can not be Empty!");
    return false;
  }
  if(vehicle_number == "")
  {
    alert("Vehicle Number field can not be Empty!");    
    return false;        
  }

  if(max_speed == "")
  {
    alert("Max Speed field can not be Empty!");
    return false;        
  }    
  
  if(max_speed!="")
  {
    if (isNaN(max_speed)) 
    {  
      document.getElementById("max_speed").value = "";
      alert("Please Enter a Number Only for Max Speed");
      return false;
    }      
  }
      
  if(vehicle_tag == "")
  {
    alert("Vehicle Tag field can not be Empty!");
    return false;    
  }    
  if(vehicle_type == "")
  {
    alert("Select Atleast One Vehicle Type!");
    return false;    
  }
  return true;             
}
function validate_manage_edit_vehicle(obj)
{  
   var vehicle_name_edit = document.getElementById("vehicle_name_edit").value;
   var vehicle_number_edit = document.getElementById("vehicle_number_edit").value;
   var max_speed_edit =  document.getElementById("max_speed_edit").value;
   var vehicle_tag_edit = document.getElementById("vehicle_tag_edit").value;
   var vehicle_type_edit = document.getElementById("vehicle_type_edit").value;        
        
  if(vehicle_name_edit == "")
  {
    alert("VehicleName field can not be Empty!");
    return false;
  }
  if(vehicle_number_edit == "")
  {
    alert("Vehicle Number field can not be Empty!");
    return false;    
  }
  if(max_speed_edit == "")
  {
    alert("Max Speed field can not be Empty!");
    return false;    
  }    
  if(vehicle_tag_edit == "")
  {
    alert("Vehicle Tag field can not be Empty!");
    return false;    
  }    
  if(vehicle_type_edit == "")
  {
    alert("Select Atleast One Vehicle Type!");
    return false;    
  }   
  return true;          
}
 

function validate_manage_add_group()
{

	var cnt=0;	
	var obj=document.manage1.manage_id;
	for (var i=0;i<obj.length;i++)
	{
	  if(obj[i].checked==true)
	  {
		var obj_1=obj[i].value;
		var user_id=obj[i].value;
		cnt++;
	  }	  
	}
	if(cnt==0)
	{
		alert("Please Select Atleast One User");
		return false;
	}
   var group_name = document.getElementById("group_name").value;
   //var remark = document.getElementById("remark").value;        
    if(group_name == "")
    {
      alert("Group Name field can not be Empty!");
      return false;
    } 
    return user_id;             
}

function validate_manage_edit_group()
{  
  var group_name = document.getElementById("group_name").value;
 //var remark = document.getElementById("remark").value;        
  if(group_name == "")
  {
    alert("Group Name field can not be Empty!");
    return false;
  } 
  return true;          
}


function validate_manage_add_device_sale(obj)
{  
  var imei_no = document.getElementById("imei_no").value;
  var super_user = document.getElementById("super_user").value;
  var user = document.getElementById("user").value;
  //var qos = document.getElementById("qos").value;
  
  if(imei_no == "")
  {
    alert("IMEI No field can not be Empty!");
    return false;    
  }    
  if(super_user == "")
  {
    alert("Super User field can not be Empty!");
    return false;    
  }    
  if(user == "")
  {
    alert("User field can not be Empty!");
    return false;    
  }

  var all_value = imei_no+":"+super_user+":"+user;
  
  manage_availability(all_value, 'all_value', 'existing#device_sale_all');
  
  //alert("riz:="+document.getElementById("enter_button").disabled);
  if(obj.enter_button.disabled)
  {
    //alert("Riz:In IF");   
    return false;
  }
  else
  {   
    //alert("Riz:In Else");      
    return true;
  }              
}

function validate_manage_edit_device_sale(obj)
{  
  var imei_no_edit = document.getElementById("imei_no_edit").value;
  var super_user_edit = document.getElementById("super_user_edit").value;
  var user_edit = document.getElementById("user_edit").value;
  
  if(imei_no_edit == "")
  {
    alert("IMEI No field can not be Empty!");
    return false;    
  }    
  if(super_user_edit == "")
  {
    alert("Super User field can not be Empty!");
    return false;    
  }    
  if(user_edit == "")
  {
    alert("User field can not be Empty!");
    return false;    
  }     
  return true;              
}

function validate_manage_device_vehicle_assignment(obj)
{  
  var device = document.getElementById("ls").value;
  var vehicle = document.getElementById("rs").value;
  
  if(device == "")
  {
    alert("IMEI No field can not be Empty!");
    return false;    
  }    
  if(vehicle == "")
  {
    alert("Vehicle field can not be Empty!");
    return false;    
  }    
  return true;              
}

function validate_manage_vehicle_grouping(obj)
{  
  var vehicle = document.getElementById("ls").value;
  var account = document.getElementById("rs").value;
  
  if(vehicle == "")
  {
    alert("Vehicle field can not be Empty!");
    return false;    
  }     
  
  if(account == "")
  {
    alert("Account field can not be Empty!");
    return false;    
  }    
  return true;              
}


function get_radio_button_value1(rb)
{
   // alert("rb="+rb);
  //rb_value = (rb.value==null) ? -1:rb.value;
  for (var i=0; i<rb.length; i++)
  {
    if (rb[i].checked==true)
    {
      var rb_value = rb[i].value;
    }
  }
  //alert("Riz:rbvalue="+rb_value)
  return rb_value;
}


/*function manage_tree_validation(obj)
{
	var tree_option_id = "";	
	var users_flag=document.getElementById("users").value;
	
	if(users_flag=="1")
	{
		var tree_option_obj=obj.elements['manage_option'];
	}
	else
	{
		var tree_option_obj=obj.elements['manage_option[]'];
	}
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
			num1 = 1;
		}
	}
	
	if(num1==0)
	{
		alert("Please Select At Least One Option");							
			return false;  			
	}
	else
	{
		var file_name=document.getElementById('file_name').value;
		//alert("filename="+file_name);
		var div_option_values=tree_option_id;
		var common_div_option1=document.getElementById('common_div_option').value;
			var poststr = "common_div_option1=" + encodeURI(common_div_option1) +
			"&div_option_values1=" + encodeURI(div_option_values);
			//alert("poststr="+poststr);
		makePOSTRequest('src/php/'+file_name, poststr);
	}	
}*/
function manage_io_tree_validation(obj)
{
	var tree_option_id = "";	
	var users_flag=document.getElementById("users").value;
	
	if(users_flag=="1")
	{
		var tree_option_obj=obj.elements['manage_option'];
	}
	else
	{
		var tree_option_obj=obj.elements['manage_option[]'];
	}
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
			num1 = 1;
		}
	}
	
	if(num1==0)
	{
		alert("Please Select At Least One Option");							
			return false;  			
	}
	else
	{
		var file_name=document.getElementById('file_name').value;
		var div_option_values=tree_option_id;
		var common_div_option1=document.getElementById('common_div_option').value;
			var poststr = "common_div_option1=" + encodeURI(common_div_option1) +
			"&div_option_values1=" + encodeURI(div_option_values);
			//alert("poststr="+poststr);
		makePOSTRequest(file_name, poststr);
	}	
}

function manage_tree_validation(obj)
{
	var tree_option_id = "";	
	//var users_flag=document.getElementById("users").value;
	
	/*if(users_flag=="1")
	{
		var tree_option_obj=obj.elements['manage_option'];
	}*/
	//else
	//{
		var tree_option_obj=obj.elements['manage_option[]'];
	//}
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
			num1 = 1;
		}
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

function select_manage_all_portal_option(obj)
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

function io_all_check(obj)
{
	if(obj.io_all.checked)
	{
		var i;
		var s = obj.elements['io_name[]'];
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.io_all.checked==false)
	{
		var i;
		var s = obj.elements['io_name[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}



function validate_manage_grouping(obj)
{
	var obj=document.manage1;
	var vehicle_obj=obj.elements['vehicle_id[]'];
	var user_obj=document.manage1.manage_user;
	//alert("user_obj="+user_obj);
	var vehicle_flag=0;
	var user_flag=0;
	
	if(vehicle_obj.length!=undefined)
	{
		for(i=0;i<vehicle_obj.length;i++)
		{ 
			if(vehicle_obj[i].checked==true)
			{
				var vehicle_id=vehicle_obj[i].value;
				vehicle_flag=1;
			}
		}
	}
	else
	{
		if(vehicle_obj.checked==true)
		{
			var vehicle_id=vehicle_obj.value;
			vehicle_flag=1;
		}		
	}	
	
	
	if(user_obj.length!=undefined)
	{
		for(i=0;i<user_obj.length;i++)
		{ 
			if(user_obj[i].checked==true)
			{
				var user_id=user_obj[i].value;
				user_flag=1;
			}
		}
	}
	else
	{
		if(user_obj.checked==true)
		{
			var user_id=user_obj.value;
			user_flag=1;
		}				
	}
	
	if(vehicle_flag==0)
	{
		alert("Please Select Atleast One Vehicle");
		return false;
	}
	else if(user_flag==0)
	{
		alert("Please Select Atleast One User");
		return false;
	}
	else
	{
		var final_id=vehicle_id+"##"+user_id;
		return final_id;
	}
}


/////////////// MILESTONE ///////////////////////////

  function get_latlng_fields(number)
  {
  	//alert("get_latlng_fields");
    var poststr= "number="+number;
  	makePOSTRequest('src/php/manage_get_latlng_fields.php', poststr);   
  }
  
  function action_manage_milestone(action_type)
  {
		//result = validate_manage_add_account(action_type);
		//var obj1=document.manage1;	    
		//if(result)
		//{
			if(action_type == "add")
			{			
        var obj=document.manage1.elements['manage_id[]'];
        alert("obj="+obj);
        var result=checkbox_selection(obj);
        //alert("result"+result);
        if(result!=false)
        {			
          var number = document.getElementById("lat_lng").value;
          var points = "";
          for(var i=0;i<number;i++)
          {
            if(i==number-1)
            points = points + document.getElementById("lat"+i).value + " " + document.getElementById("lng"+i).value;
            else
            points = points + document.getElementById("lat"+i).value + " " + document.getElementById("lng"+i).value + ",";
          }
                          		 			
          var poststr="action_type=" + encodeURI(action_type)+
            "&local_account_ids=" + encodeURI(result)+							
          	"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
          	"&ms_type=" + encodeURI(document.getElementById("ms_type").value )+							
          	"&points="+points;
         }       
      }             
			
      else if(action_type == "edit")
			{
				var poststr="action_type=" + encodeURI(action_type)+							
							"&ms_id=" + encodeURI( document.getElementById("ms_id").value )+											
							"&points="+points;       
      }
			
      else if(action_type == "delete")
			{
				var poststr="action_type=" + encodeURI(action_type)+
							"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
							"&ms_type=" + encodeURI(get_radio_button_value1(obj1.elements["ms_type"]))+							
							"&points="+points;       
			} 
      
			else if(action_type == "assign")
			{
				var poststr="action_type=" + encodeURI(action_type)+
							"&group_account_id=" + encodeURI( document.getElementById("group_account_id").value )+
							"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
							"&ms_type=" + encodeURI(get_radio_button_value1(obj1.elements["ms_type"]))+							
							"&points="+points;       
			}        
			
			else if(action_type == "deassign")
			{
				var poststr="action_type=" + encodeURI(action_type)+
							"&group_account_id=" + encodeURI( document.getElementById("group_account_id").value )+
							"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
							"&ms_type=" + encodeURI(get_radio_button_value1(obj1.elements["ms_type"]))+							
							"&points="+points;       
			}        			
				//alert("poststr="+poststr);	
			makePOSTRequest('src/php/action_manage_milestone.php', poststr);     
  }  
  
////

function CheckFloating(FloatValue) 
{
	var point;
	var final_value="";
	var thisfloat = FloatValue.value;
	var thisfloat1=thisfloat.split("");

	var thisfloat = FloatValue.value;	
	for (i=0; i < thisfloat1.length; i++) 
	{
		if(i==2 && thisfloat1[i]!=".") 
		{
			var c=thisfloat.charAt(i);
			if((c < "0")||(c > "9"))
			{
				alert("Invalid input please enter numbers only");
				FloatValue.value="";
				return false;
			}
			else
			{
			alert("Third place must be in decimal point");				
			FloatValue.value=thisfloat1[0]+thisfloat1[1]+"."+thisfloat1[2];
			}
		}	
		
		var d=thisfloat.charAt(i);
		if(i !=2 && i<=20)
		{
			if((d < "0")||(d > "9"))
			{
				alert("Invalid input please enter numbers only");
				FloatValue.value="";
				return false;
			}
		}

		if(i > 20)
		{			
			alert("Lat long must be of 18 digit including decimal point");
			FloatValue.value="";
			return false;			
		}
	}
}  

function form_submit1(obj)
{
		if(obj.ms_name.value == "")
		{
			alert("Please Enter Mile Stone Name");
			obj.ms_name.focus();
			return false;
		}	
		var flag;
		var thisval = obj.lat_size.value;
		for(var i=0;i<thisval;i++)
		{
			if(document.getElementById("lat"+i).value=="")
			{
				alert("Please Enter Lat/Long");
				document.getElementById("lat"+i).focus();
				flag=0;
				return false;
			}
			
		}	
		if(flag!=0)
		{
			document.thisform.action="AddMilestoneAction.php";
			document.thismyform.target="_blank";
			document.thismyform.submit();	
		}	
}

function manage_get_edit_latlng_fields(number,id)
{
    var poststr="number=" + encodeURI(number) +
    "&id=" + encodeURI( id );       
    makePOSTRequest('src/php/manage_get_edit_latlng_fields.php', poststr);       
}