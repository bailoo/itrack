  
   /////////////////////  MANAGE MODULE ////////////////////////////////////////////////////////
   //1. ADD ACCOUNT
     
   /*function show_option(type, option)
   {
      makePOSTRequest('src/php/'+ type + '_' + option+ '.php', '');                           
   } //Now Defined in ajax.js */
   
   function action_manage_device(obj, action_type)
   {
      var res = false;
      
      //alert("riz:action_type="+action_type);
      if(action_type == "add")
        res = validate_manage_add_device(obj);
      else if(action_type =="edit")
        res = validate_manage_edit_device(obj); 
      else if(action_type == "delete")
        res = true;        
      
      if(res == true)
      {
        if(action_type == "add")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&imei_no=" + encodeURI( document.getElementById("imei_no").value ) +
                        "&manufacturing_date=" + encodeURI( document.getElementById("manufacturing_date").value )+
                        "&make=" + encodeURI( document.getElementById("make").value )
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
        //alert("Riz:"+poststr);
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
              
      if(res == true)
      {
        if(action_type == "add" || action_type=="edit")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&imei_no=" + encodeURI( document.getElementById("imei_no").value ) +
                        "&super_user=" + encodeURI( document.getElementById("super_user").value )+
                        "&user=" + encodeURI( document.getElementById("user").value )+
                        "&qos=" + encodeURI( document.getElementById("qos").value );
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
      var res = false;
      
      if(action_type == "add")
        res = validate_manage_add_vehicle(obj);
      else if(action_type =="edit")
        res = validate_manage_edit_vehicle(obj); 
      else if(action_type == "delete")
        res = true;            
             
      if(res == true)
      {
        if(action_type == "add")
        {
          var poststr = "action_type=" + encodeURI( action_type ) +
                        "&vehicle_name=" + encodeURI( document.getElementById("vehicle_name").value ) +
                        "&vehicle_number=" + encodeURI( document.getElementById("vehicle_number").value )+
                        "&max_speed=" + encodeURI( document.getElementById("max_speed").value )+
                        "&vehicle_tag=" + encodeURI( document.getElementById("vehicle_tag").value )+
                        "&vehicle_type=" + encodeURI( document.getElementById("vehicle_type").value );
        }
        else if(action_type == "edit")
        {
          var poststr = "action_type=" + encodeURI( action_type ) +
                        "&vehicle_id_edit=" + encodeURI( document.getElementById("vehicle_id_edit").value ) +
                        "&vehicle_name_edit=" + encodeURI( document.getElementById("vehicle_name_edit").value ) +
                        "&vehicle_number_edit=" + encodeURI( document.getElementById("vehicle_number_edit").value )+
                        "&max_speed_edit=" + encodeURI( document.getElementById("max_speed_edit").value )+
                        "&vehicle_tag_edit=" + encodeURI( document.getElementById("vehicle_tag_edit").value )+
                        "&vehicle_type_edit=" + encodeURI( document.getElementById("vehicle_type_edit").value );      
        }
        else if(action_type == "delete")
        {
          var poststr = "action_type=" + encodeURI( action_type )+
                        "&vehicle_id_edit=" + encodeURI( document.getElementById("vehicle_id_edit").value );   
                        //alert("Riz:poststr="+poststr);                         
        }            
                                                                                                                           
        makePOSTRequest('src/php/action_manage_vehicle.php', poststr);
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
   function manage_availability(obj, source, type)
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
   
   function get_radio_selection_1(obj, input_name)
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
   }
   
   
   function manage_device_vehicle_assignment() 
   {
     makePOSTRequest('src/php/manage_device_vehicle_assignment.php', '');
   }
   
   function action_manage_device_vehicle_assignment(obj)
   {
      var device_option = get_radio_selection(obj, "device_option");
      var vehicle_option = get_radio_selection(obj, "vehicle_option");    
      
      var imei_no2 = obj.imei_no2.value;
      var vid2 = obj.vid2.value;
      
      //alert("Rizwan:obj="+obj+" device_option="+device_option+" vehicle_option="+vehicle_option+" imei_no2="+imei_no2+" vid2="+vid2);
           
      var poststr = "device_option=" + encodeURI( device_option ) +
                    "&vehicle_option=" + encodeURI( vehicle_option )+
                    "&imei_no=" + encodeURI( document.getElementById("imei_no").value )+
                    "&imei_no2=" + encodeURI( imei_no2 )+
                    "&vname=" + encodeURI( document.getElementById("vname").value )+
                    "&vid2=" + encodeURI( vid2 );
      makePOSTRequest('src/php/action_manage_device_vehicle_assignment.php', poststr);
   } 
   
  function get_selected_values(obj, input_name)
  { 
      if(input_name=="device")
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
       return selected_values;
   }
        
   function manage_device_vehicle_deassignment() 
   {
     makePOSTRequest('src/php/manage_device_vehicle_deassignment.php', '');
   }
   
   function action_manage_device_vehicle_deassignment(obj)
   {
      var selected_values = get_selected_values(obj,"device");
      //alert("Rizwan:selvalues="+selected_values);    
               
      var poststr = "device=" + encodeURI( selected_values );                  
      makePOSTRequest('src/php/action_manage_device_vehicle_deassignment.php', poststr);
   }  
   /////////////// xxx  
   
 function manage_add_geofence() 
 {
   makePOSTRequest1('src/php/manage_add_geofence.php', '');
 }
   
   
 function action_manage_geofence(action_type)
 {
    //alert("action_type="+action_type);
    var geo_id1=document.getElementById("geo_id").value;  
  
    if(action_type=="add")  
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
                    "&geo_name="+encodeURI(add_geo_name) +
                    "&geo_coord="+encodeURI(geo_coord); 
    }
    else if(action_type=="edit")
    {
      if(geo_id1=="select")
      {
        alert("Please Select Geofences"); 
        document.getElementById("geo_id").focus();
        return false;
      }       
      var edit_geo_name=document.getElementById("edit_geo_name").value; 
      var edit_geo_coord=document.getElementById("edit_geo_coord").value;
      if(edit_geo_name=="") 
      {
        alert("Please Enter Geofence Name"); 
        document.getElementById("edit_geo_name").focus();
        return false;
      }
      else if(edit_geo_coord=="") 
      {
        alert("Please Draw Geofence");
        document.getElementById("edit_geo_coord").focus();
        return false;
      }
       var poststr ="action_type="+encodeURI(action_type ) +  
                    "&geo_id="+encodeURI(geo_id1) +
                     "&edit_geo_name="+encodeURI(document.getElementById("edit_geo_name").value ) +
                     "&edit_geo_coord="+encodeURI(document.getElementById("edit_geo_coord").value); 
    }
    else if(action_type=="delete")
    {
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
    makePOSTRequest1('src/php/action_manage_geofence.php', poststr);
 }
   
 function manage_add_route() 
 {
   makePOSTRequest('src/php/manage_add_route.php', '');
 }
 
 function action_manage_route(action_type)
 {
    var route_id=document.getElementById("route_id").value;
    if(action_type=="add")  
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
                    "&route_name="+encodeURI(add_route_name) +
                    "&route_coord="+encodeURI(route_coord);                     
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
    makePOSTRequest1('src/php/action_manage_route.php', poststr);
 }
   
      
   
 function manage_add_landmark() 
 {
   makePOSTRequest('src/php/manage_add_landmark.php', '');
 }
 
 function action_manage_landmark(action_type)
 {
    if(action_type=="add") 
    {      
      var add_landmark_name=document.getElementById("add_landmark_name").value;     
      var landmark_point=document.getElementById("landmark_point").value;     
      var zoom_level=document.getElementById("zoom_level").value;
      if(add_landmark_name=="") 
      {
        alert("Please Enter Landmark Name"); 
        document.getElementById("add_landmark_name").focus();
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
      var poststr = "action_type=" + action_type +
                    "&landmark_name=" + encodeURI(add_landmark_name) +
                    "&landmark_coord=" + encodeURI(landmark_point)+
                    "&zoom_level=" + encodeURI(zoom_level);
    }
    else if(action_type=="edit")
    {
      var landmark_id=document.getElementById("landmark_id").value;
      var edit_landmark_name=document.getElementById("edit_landmark_name").value;
      var edit_landmark_point=document.getElementById("edit_landmark_point").value
      var edit_zoom_level=document.getElementById("edit_zoom_level").value;
      if(landmark_id=="select") 
      {
        alert("Please Select Landmark Name");        
        document.getElementById("landmark_id").focus();
        return false;
      }
      if(edit_landmark_name=="") 
      {
        alert("Please Landmark Name");
        document.getElementById("edit_landmark_name").focus();
        return false;
      }
      else if(edit_landmark_point=="") 
      {
        alert("Please Draw Landmark Point");
        document.getElementById("edit_landmark_point").focus();
        return false;
      }          
      else if(edit_zoom_level=="select") 
      {
        alert("Please Select Zoom Level");
        document.getElementById("edit_zoom_level").focus();
        return false;
      }
      var poststr = "action_type=" + action_type +
                  "&landmark_id=" + encodeURI(landmark_id) +
                  "&edit_landmark_name=" + encodeURI(edit_landmark_name) +
                  "&edit_landmark_point=" + encodeURI(edit_landmark_point)+
                  "&edit_zoom_level=" + encodeURI(edit_zoom_level);
    }
    else if(action_type=="delete")
    {
     var landmark_id=document.getElementById("landmark_id").value;
     if(landmark_id=="select") 
     {
        alert("Please Select Landmark Name");        
        document.getElementById("landmark_id").focus();
        return false;
     }
     var txt="Are You Sure You Want To Close Without Saving or Drawing";
     if(!confirm(txt))
     {
       return false; 
     }
     else
     {
      var poststr = "action_type=" + action_type +
                    "&landmark_id=" + encodeURI(landmark_id);
     }
    }   
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
   
function manage_geofence_assignment() 
{
 makePOSTRequest('src/php/manage_geofence_assignment.php', '');
}

function action_manage_geofence_assignment(obj)
{ 
 
 /* var geo_name=obj.geo_name.value;
  var vname=obj.vname.value;
  if(geo_name=="")
  {
    alert("Please Enter Geofence Name");
    return false;
  }
  if(vname=="")
  {
    alert("Please Enter Vehicle Name");
    return false;
  }  
  if(geo_name=="" && vname=="")
  {
    if(geo_id2=="")
    {
      alert("Please Select Atleast One Geofence");
      return false;
    }
    if(vid2=="")
    {
      alert("Please Select Atleast One Vehicle");
      return false;
    }
  }*/ 

  var geofence_option = get_radio_selection_1(obj, "geofence_option");
  var vehicle_option = get_radio_selection_1(obj, "vehicle_option");
  
   var geo_id2 = obj.geo_id2.value;
  var vid2 = obj.vid2.value;  
  
  //alert("Rizwan:obj="+obj+" device_option="+device_option+" vehicle_option="+vehicle_option+" imei_no2="+imei_no2+" vid2="+vid2);
       
  var poststr = "geofence_option=" + encodeURI(geofence_option) +
                "&vehicle_option=" + encodeURI(vehicle_option)+
                "&geo_name=" + encodeURI( document.getElementById("geo_name").value )+
                "&geo_id2=" + encodeURI(geo_id2)+
                "&vname=" + encodeURI(document.getElementById("vname").value)+
                "&vid2=" + encodeURI(vid2);  
  makePOSTRequest('src/php/action_manage_geofence_assignment.php', poststr);
} 
   
function manage_route_assignment() 
{
 makePOSTRequest('src/php/manage_route_assignment.php', '');
}

function action_manage_route_assignment(obj)
{
  var route_option = get_radio_selection_2(obj, "route_option");
  var vehicle_option = get_radio_selection_2(obj, "vehicle_option");    
  
  var route_id2 = obj.route_id2.value;
  var vid2 = obj.vid2.value;
       
  var poststr = "route_option=" + encodeURI( route_option ) +
                "&vehicle_option=" + encodeURI( vehicle_option )+
                "&route_name=" + encodeURI( document.getElementById("route_name").value )+
                "&route_id2=" + encodeURI( route_id2 )+
                "&vname=" + encodeURI( document.getElementById("vname").value )+
                "&vid2=" + encodeURI( vid2 );
  makePOSTRequest('src/php/action_manage_route_assignment.php', poststr);
} 

function manage_geofence_vehicle_deassignment() 
{
 makePOSTRequest('src/php/manage_geofence_vehicle_deassignment.php', '');
}
   
function action_manage_geofence_vehicle_deassignment(obj)
{
  var selected_values = violation_get_selected_values(obj,"veh_geo_id"); 
  var poststr = "veh_geo_id=" + encodeURI( selected_values );                  
  makePOSTRequest('src/php/action_manage_geofence_vehicle_deassignment.php', poststr);
}

function manage_route_vehicle_deassignment() 
{
 makePOSTRequest('src/php/manage_route_vehicle_deassignment.php', '');
}
   
function action_manage_route_vehicle_deassignment(obj)
{
  var selected_values = violation_get_selected_values(obj,"veh_route_id");               
  var poststr = "veh_route_id=" + encodeURI( selected_values );                  
  makePOSTRequest('src/php/action_manage_route_vehicle_deassignment.php', poststr);
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
  if(common_event=="landmark" || common_event=="edit_landmark") ////////only for landmark
  {
    manage_landmark(common_event);   
  }
  else///////for geofencing and route both ////////////
  {
    var div;   	 
    if(common_event=="geofencing")
    {
      document.getElementById("geo_coord").value="";  ///////at the time of geofence creation keeping goe_coord fell null
      div="geo_map";
    }
    else if(common_event=="edit_geofencing")
    {
      document.getElementById("close_geo_coord").value = document.getElementById("edit_geo_coord").value; // kept last geo coord details for closing pop up div 
      div="geo_map"; 
    }
    else if(common_event=="route")
    {
      document.getElementById("route_coord").value="";  ///////at the time of route creation keeping route_coord field null
      div="route_map"; 
    }
    else if(common_event=="edit_route")
    {
      document.getElementById("close_route_coord").value = document.getElementById("edit_route_coord").value; // kept last geo coord details for closing pop up div
      div="route_map";   
    }
    ////it's work for geofence and route
  	map = new GMap2(document.getElementById(div)); 	
		map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5); 
		map.setUIToDefault();		
    manage_draw_geofencing_route();
  } 
}

function getColor(named){return COLORS[(colorIndex_++) % COLORS.length][named ? 0 : 1];}

var poly_type; /////////it is both for polygon or polyline  

function manage_draw_geofencing_route()                                                    
{     
    ////////for draw the map     
    var color = getColor(false); 
    if(common_event=="geofencing") 
    {
      document.getElementById("geo_coord").value="";
      poly_type = new GPolygon([], color, 2, 0.7, color, 0.2); 
      var id_param="geo_coord";      
    }    
    else if(common_event=="edit_geofencing" || common_event=="edit_route")
    { 
       if(common_event=="edit_geofencing")
       {
         var divid_editcoord= "edit_geo_coord";
         var poly_type=GPolygon;
         var id_param="edit_geo_coord";
       }
       else if(common_event=="edit_route")
       {
         var divid_editcoord= "edit_route_coord";
         var poly_type=GPolyline;
         var id_param="edit_route_coord";
       }        
       
        var coord = document.getElementById(divid_editcoord).value;
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
           var poly_type = new poly_type([], color);
           var edit_flag=1;
        }        	 
    }
    else if(common_event=="route")
    {
        document.getElementById("route_coord").value="";
        var poly_type = new GPolyline([], color);
        var id_param="route_coord";
    }
    
    startDrawing(id_param,poly_type, "Shape " + (++shapeCounter_), function()     
    {
      var cell = this; 
      if(common_event=="geofencing" || common_event=="edit_geofencing") 
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
function manage_initialize()
{     
  if(common_event=="geofencing" || common_event=="edit_geofencing")
   {
     var div="geo_map";
   }
   else if(common_event=="route" || common_event=="edit_route")
   {
      var div="route_map";      
   }       
    if (GBrowserIsCompatible())
    {	
  		map = new GMap2(document.getElementById(div)); 	
  		map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5); 
  		map.setUIToDefault();
  	}
} 

function clear_initialize()
{
    if(common_event=="geofencing")
     {
       var div="geo_map";
       document.getElementById("geo_coord").value = "";
     }
     else if(common_event=="edit_geofencing")
     {
        var div="geo_map";
        document.getElementById("edit_geo_coord").value = "";              
     }
     else if(common_event=="route")
     {
        var div="route_map"; 
        document.getElementById("route_coord").value = "";
     }
     else if(common_event=="edit_route")
     {
        var div="route_map";
        document.getElementById("edit_route_coord").value = "";      
     }
    if (GBrowserIsCompatible())
    {	
  			map = new GMap2(document.getElementById(div));
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
      document.getElementById("geo_coord").value="";  
   }	    
   else if(common_event=="edit_geofencing")
   {
        var final_coord_point=document.getElementById("close_geo_coord").value;
        document.getElementById("edit_geo_coord").value =  final_coord_point;
   }
    if(common_event=="route")
   {
      document.getElementById("route_coord").value="";  
   }
   else if(common_event=="edit_route")
   {       
      var final_coord_point=document.getElementById("close_route_coord").value;
      document.getElementById("edit_route_coord").value =  final_coord_point;
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

/////////////////////////landmark////////// 
function manage_landmark(param)
{
  if (GBrowserIsCompatible())
  {
    if(param=="edit_landmark")
  	{
  	  var point_id= "edit_landmark_point";
      var zoom_id= "edit_zoom_level";     
      var zoom_level_1=parseInt(document.getElementById(zoom_id).value);
      
      landmark_map_part(zoom_level_1); 
      
      var lat_lng=document.getElementById(point_id).value;
      document.getElementById("prev_landmark_point").value=lat_lng;
  	  lat_lng=lat_lng.split(",");  	
  	  
    	 var point=new GLatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));
     	 var lat=point.lat();  var lng=point.lng();
        
    	
    	 var iwform = '<div style="height:10px"></div><table>'
                   +'<tr><td style="font-size:11px;">'+point+'</td></tr>'               
                   +'</table><div style="height:10px"></div>'			 					
  			           +'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\''+point_id+'\')" /></center>' ;
  			                                                                                                                   
    	 var marker = new GMarker(point, lnmark);
    	 GEvent.addListener(marker, "click", function()
       {
    	  lastmarker = marker;
    	  document.getElementById(point_id).value=lat+","+lng;
    	  marker.openInfoWindowHtml(iwform);
    	 });
    	map.addOverlay(marker);    
    }
    else
    {
       var zoom_level_1=5;
       zoom_level_1=parseInt(zoom_level_1);
        
       var point_id= "landmark_point";
       var zoom_id= "zoom_level";
       landmark_map_part(zoom_level_1);
       map.clearOverlays();
    }
   
    var lastmarker;    
  	GEvent.addListener(map,"click",function(overlay,point)
    {
          if (!overlay)
          {
            //alert("point_id="+point_id+"zoome_id="+zoom_id)
            map.clearOverlays(); 
  		      createInputMarker(point,point_id,zoom_id);
          }
    });     
   }
   else 
   {
      alert("Sorry, the Google Maps API is not compatible with this browser");
   }
}

function landmark_map_part(zoom_level_1)
{
    map = new GMap2(document.getElementById("landmark_map"));
  	map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), zoom_level_1);
  	map.setUIToDefault();	
}

function createInputMarker(point,point_id,zoom_id) 
{
    var lat_1=point.lat();
    var lng_1=point.lng();  
       
    document.getElementById(point_id).value=lat_1+","+lng_1;   
   
    var iwform = '<div style="height:10px"></div><table>'
                 +'<tr><td style="font-size:11px">('+lat_1+', '+lng_1+')</td></tr>'               
                 +'</table><div style="height:10px"></div>'			 					
			           +'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\''+point_id+'\')" /></center>';
			           
  	var marker = new GMarker(point, lnmark);
  	GEvent.addListener(marker, "click", function()
    {
  	  lastmarker = marker;
  	  document.getElementById(point_id).value=lat+","+lng;
  	  marker.openInfoWindowHtml(iwform);
  	});
  	map.addOverlay(marker);
  	marker.openInfoWindowHtml(iwform);
  	lastmarker=marker;
  	return marker;
}

 function save_landmark_details(point_id)
 {
    var coord_point=document.getElementById(point_id).value;
    if(coord_point=="")
    {
      alert("Please Enter Points");
      return false;
    }
    else
    {
      div_close_block();
      document.getElementById(point_id).value =  coord_point;
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
   document.getElementById("edit_landmark_point").value=document.getElementById("prev_landmark_point").value;  ///at the time of edit landmark
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

