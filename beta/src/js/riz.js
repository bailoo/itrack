 
  function show_add(type, option)   // option = add_feature
  { 
      makePOSTRequest('src/php/' + type+ '_'+ option + '.php', '');   
  }
  
  function show_edit(type, option)    // option = edit_feature
  {    
	//alert(type+option);
      var param_edit = document.getElementById(option).value;
      var poststr = option + "=" + encodeURI( param_edit );  
       
      if(param_edit == "select")
        alert("Please Select One Option");
      else        
        makePOSTRequest('src/php/' + type+ '_'+ option + '.php', poststr);   
  }
  
  
   
   function action_manage_manage_vehicle_grouping(obj)
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
      makePOSTRequest('src/php/action_manage_vehicle_grouping.php', poststr);
   } 
   
   function manage_vehicle_degrouping() 
   {
     makePOSTRequest('src/php/manage_vehicle_degrouping.php', '');
   }
   
   function action_manage_manage_vehicle_grouping(obj)
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
      makePOSTRequest('src/php/action_manage_vehicle_degrouping.php', poststr);
   }    
     
  /*function get_poststr(obj, type, option, feature, param_str)
  {
    var poststr = "";
    poststr = poststr + option + '=' + encodeURI( option );
    var param_name = param_str.split(":");    
    for(var i=0;i<param_name.length;i++)
    {
      poststr = poststr + '&'+param_name[i]+ '=' + encodeURI( param_name[i] );
      //alert(poststr);
    }       
    return poststr;
  }
   
  function action_global(obj, type, option, feature, param_str)
  {
      alert("riz:obj="+obj+" type="+type+" option="+option+" feature="+feature+" param_str="+param_str);
      var res = false;    
      //var func = 'validate_'+type+'_'+option+'_'+feature+'('+obj+')';
      //alert("func="+func);
        
      //res = 'validate_'+type+'_'+option+'_'+feature+'('+obj+')';
      res = validate_manage_add_device(obj);
      var poststr = get_poststr(obj, type, option, feature, param_str);
           
      alert("Riz:res="+res);
      
      if(res == true)
        makePOSTRequest('src/php/action_'+type+'_'+feature+'.php', poststr);   
      
      /*if(res == true)
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
          var poststr = "action_type_edit=" + encodeURI( action_type )+
                        "&imei_no_edit=" + encodeURI( document.getElementById("imei_no_edit").value ) +
                        "&manufacturing_date_edit=" + encodeURI( document.getElementById("manufacturing_date_edit").value )+
                        "&make_edit=" + encodeURI( document.getElementById("make_edit").value )      
        }
        makePOSTRequest('src/php/action_manage_device.php', poststr);
      } 
  }  */   