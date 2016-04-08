 
  function show_add(type, option)   // option = add_feature
  {                    
      makePOSTRequest('src/php/' + type+ '_'+ option + '.php', '');   
  }
  
  function show_edit(type, option)    // option = edit_feature
  {      
      var param_edit = document.getElementById(option).value;
      var poststr = option + "=" + encodeURI( param_edit );  
       
      if(param_edit == "select")
        alert("Please Select One Option");
      else        
        makePOSTRequest('src/php/' + type+ '_'+ option + '.php', poststr);   
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