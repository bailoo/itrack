  /////////////////////  SETTING MODULE ///////////////////////////////////////
  //1.
  function setting_account_detail()
  {    
    makePOSTRequest('src/php/setting_account_detail.php', '');
  }  
 
  function action_setting_account_detail(obj)
  {
  //$login_name1=$_POST['login_name'];		
  var poststr =   "user_name=" + encodeURI( document.getElementById("user_name").value )+
                  "&address1=" + encodeURI( document.getElementById("address1").value )+
                  "&address2=" + encodeURI( document.getElementById("address2").value )+
                  "&city=" + encodeURI( document.getElementById("city").value )+
                  "&state=" + encodeURI( document.getElementById("state").value )+
                  "&country=" + encodeURI( document.getElementById("country").value )+      
                  "&zip=" + encodeURI( document.getElementById("zip").value )+
                  "&phoneno=" + encodeURI( document.getElementById("phoneno").value )+                        
                  "&email=" + encodeURI( document.getElementById("email").value );      
                  
                  //alert(poststr);                                                                
                   
    makePOSTRequest('src/php/action_setting_account_detail.php', poststr);
  }    
  
  //2.
  function setting_feature_pref()
  {
    makePOSTRequest('src/php/setting_feature_pref.php', '');
  }
  
  function action_setting_feature_pref(obj)
  {          
     var selection1 = document.forms[0].latlng;  
     var selection2 = document.forms[0].refresh_rate;  

     var latlng;
     var refresh_rate;
     var i=0;
     
    // alert("r1="+selection1.length+" r2="+selection2.length);
     
     for (i=0; i<selection1.length; i++)
     {
        if (selection1[i].checked == true)
          latlng = selection1[i].value;  
     }
     
     for (i=0; i<selection2.length; i++)
     {
        if (selection2[i].checked == true)
          refresh_rate = selection2[i].value;  
     }     
     
     //alert("r1="+latlng+" r2="+refresh_rate);

    var poststr = "latlng=" + encodeURI( latlng ) +
                  "&refresh_rate=" + encodeURI( refresh_rate );                                
                  alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_setting_feature_pref.php', poststr);
  }    
  
  //3.
  function setting_password()
  {
    makePOSTRequest('src/php/setting_password.php', '');
  }
  
  function action_setting_password(obj)
  {
    var old_pass = document.getElementById("old_pass").value;
    var new_pass = document.getElementById("new_pass").value;
    var new_pass1 = document.getElementById("new_pass1").value;
    
    if(old_pass=="")
    {
      alert("Please Enter old Password");
      document.getElementById("old_pass").value ="";
      document.getElementById("new_pass").value ="";
      document.getElementById("new_pass1").value="";      
    }
    if( (new_pass == new_pass1) && (old_pass!="") ) 
    {
      var poststr = "old_pass=" + encodeURI( document.getElementById("old_pass").value ) +
                  "&new_pass=" + encodeURI( document.getElementById("new_pass").value );                                 
                  alert(poststr);                                                                                               
                   
      makePOSTRequest('src/php/action_setting_password.php', poststr);
    }
    else
    {
      alert("New password do not match!");
      document.getElementById("old_pass").value ="";
      document.getElementById("new_pass").value ="";
      document.getElementById("new_pass1").value ="";      
    }
  }      
 
  /////////////////////  SETTING MODULE CLOSED ////////////////////////////////