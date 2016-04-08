	function setting_account_detail(page_name,stage,title)
	{ 
		if(stage=='first_stage')
		{
			poststr="title="+title;
			makePOSTRequest(page_name,poststr);
		}
		else if(stage=='second_stage')
		{
			var obj=document.setting.manage_id;
			var result=radio_selection(obj);
			if(result!=false)
			{
				poststr="setting_account_id="+result;
				makePOSTRequest(page_name, poststr);
			}
		} 	
	}  
 
  function action_setting_account_detail(obj)
  {
  //$login_name1=$_POST['login_name'];		
      var poststr =   "setting_account_id="+document.getElementById("local_account_id").value+
			"&user_name="+document.getElementById("user_name").value+
                "&address1="+document.getElementById("address1").value+
                "&address2="+document.getElementById("address2").value+
                "&city="+document.getElementById("city").value+
                "&state="+document.getElementById("state").value+
                "&country="+document.getElementById("country").value+      
                "&zip="+document.getElementById("zip").value+
                "&phoneno="+document.getElementById("phoneno").value+                        
                "&email="+document.getElementById("email").value;                        
                  //alert(poststr);                                                                
                   
    makePOSTRequest('src/php/action_setting_account_detail.htm', poststr);
  }    
  
  //2.
  function setting_feature_pref()
  {
    makePOSTRequest('src/php/setting_feature_pref.htm', '');
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
                  "&refresh_rate=" + encodeURI( refresh_rate )+
				  "&local_account_id=" +document.getElementById("local_account_id").value;                                 
                 // alert(poststr);                                                                                               
                   
    makePOSTRequest('src/php/action_setting_feature_pref.htm', poststr);
  }    
  
  //3.
  function setting_password()
  {
    makePOSTRequest('src/php/setting_password.htm', '');
  }
  
  function action_setting_password(obj)
  {
    //var old_pass = document.getElementById("old_pass").value;
    var new_pass = document.getElementById("new_pass").value;
    var new_pass1 = document.getElementById("new_pass1").value;
    
    /*if(old_pass=="")
    {
      alert("Please Enter old Password");
      document.getElementById("old_pass").value ="";
      document.getElementById("new_pass").value ="";
      document.getElementById("new_pass1").value="";      
    }*/
    //if( (new_pass == new_pass1) && (old_pass!="") ) 
    if( (new_pass == new_pass1)) 
    {
      var poststr = "setting_account_id="+document.getElementById("local_account_id").value+
					//"&old_pass=" +document.getElementById("old_pass").value+
                  "&new_pass=" +document.getElementById("new_pass").value;                                 
                 // alert(poststr);                                                                                               
                   
      makePOSTRequest('src/php/action_setting_password.htm', poststr);
    }
    else
    {
      alert("New password do not match!");
     // document.getElementById("old_pass").value ="";
      document.getElementById("new_pass").value ="";
      document.getElementById("new_pass1").value ="";      
    }
  }
  
function action_setting_color(obj)
{
	var feature = trim(document.getElementById("feature").value);
	var color_a1 = parseInt(trim(document.getElementById("range_a1").value));	
	var color_a2 = trim(document.getElementById("color_a").value);
	var color_b1 = parseInt(trim(document.getElementById("range_b1").value));	
	var color_b2 = trim(document.getElementById("color_b").value);
	var color_c1 = parseInt(trim(document.getElementById("range_c1").value));	
	var color_c2 = trim(document.getElementById("color_c").value);
	var color_d1 = parseInt(trim(document.getElementById("range_d1").value));	
	var color_d2 = trim(document.getElementById("color_d").value);		
	
	//alert("color_a1="+color_a1+" ,color_a2="+color_a2);
	if(color_a1 <10 || isNaN(color_a1))
	{
		alert("Minimum Minutes must be 10 minutes for- Time1");
		return false;
	}
	if( ((color_b1 <= color_a1) || (color_c1 <= color_b1) || (color_d1 <= color_c1)) || (color_c1>=color_a1 && isNaN(color_b1)) || (color_d1>=color_a1 && isNaN(color_c1)) )
	{
		alert("Minutes should be in Incremental Order");
		return false;
	}
	
	if( ((color_d1 <= color_a1) && (!isNaN(color_d1))) || ((color_c1 <= color_a1)&& !isNaN(color_c1)) || ((color_b1 <= color_a1) && !isNaN(color_b1)) )
	{
		alert("Minutes should be in Incremental Order");
		return false;		
	}	
	
	if( ((color_a1 >= 10) && isNaN(color_b1) && (color_c1>=color_a1)) || ((color_a1 >= 10) &&(color_d1 >= color_a1) && isNaN(color_c1)) )
	{
		alert("Minutes should be in Incremental Order2");
		return false;
	}
	
	if(isNaN(color_a1) && isNaN(color_b1) && isNaN(color_c1) && isNaN(color_d1))
	{
		alert("All fields can not be blank");
		return false;
	}
	
	if(isNaN(color_a1))
	{
		color_a1 = "";
	}
	if(isNaN(color_b1))
	{
		color_b1 = "";
	}
	if(isNaN(color_c1))
	{
		color_c1 = "";
	}
	if(isNaN(color_d1))
	{
		color_d1 = "";
	}	
		
	var color_str = color_a1+ ":"+color_a2+"@"+color_b1+":"+color_b2+"@"+color_c1+":"+color_c2+"@"+color_d1+":"+color_d2;
		
	var user_account_id = document.getElementById("local_account_id").value;

	var poststr = "setting_account_id="+user_account_id+
				"&feature=" +feature+
			  "&color_str=" +color_str;                                 
			 //alert(poststr);                                                                                               
			   
	makePOSTRequest('src/php/action_setting_color.htm', poststr);
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

  ///////////////////// SCHOOL BY RAHUL ///////////////////////////////
  
  function action_setting_alert(action_type)
 {
    if(action_type=="configure")  
    {
		var obj=document.setting1.elements['card_id[]']; 		
		var students=checkbox_selection_setting(obj);
		var alerts=document.setting1.elements['alert[]'];
		var selected_alerts=checkbox_selection_setting(alerts);
		
		var account_id=document.getElementById("account_id_hidden").value;
		
		if(students!=false && selected_alerts!=false)
		{
		  
		  var time_before_min=document.getElementById("time_before_min").value;
         
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(time_before_min=="select")
		  {
			time_before_min="10";
			/*alert("Please Select Shift "); 
			document.getElementById("shift_id").focus();
			return false;*/
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(account_id) +
						"&card_ids="+encodeURI(students) +
						"&selected_alerts="+encodeURI(selected_alerts) +
            "&time_before_min="+encodeURI(time_before_min); 
		}
    }

    makePOSTRequest('src/php/action_setting_alert.htm', poststr);
 }
 
 function show_student_record_parent(obj)
 {
    var student_id=document.getElementById("student_id").value;
    //alert(student_id);
    if(student_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "student_id_parent=" + encodeURI( document.getElementById("student_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.htm', poststr);
    }
 }
 
 function setting_edit_prev_interface(file_name,common_account_id)    
  { 
	//alert(module+action+type);	 //alert("common_accoount_id="+common_account_id);
      var poststr = "common_id=" + encodeURI(common_account_id); 
	  //alert("poststr="+poststr);

      makePOSTRequest(file_name, poststr);   
  }
  
 function setting_edit_prev(file_name)
{	
	var obj=document.setting.manage_id;
	var result=radio_selection(obj);
  //alert("id="+result);		
	if(result!=false)
	{		
		setting_edit_prev_interface(file_name,result);
	}
}
 
function setting_show_file(file_name)
	{
		//alert(file_name);
    makePOSTRequest(file_name, '');
	}


function action_setting_student(action_type)
 {
   if(action_type=="parent_edit")                                               
    {      
      var student_id=document.getElementById("student_id").value;
      if(student_id=="select")
      {
        alert("Please Select Student"); 
        document.getElementById("student_id").focus();
        return false;
      }       
      
      var account_id=document.getElementById("account_id_hidden").value;
      var edit_student_name=document.getElementById("edit_student_name").value;
		  var edit_student_address=document.getElementById("edit_student_address").value;
		  var edit_student_student_mobile_no=document.getElementById("edit_student_student_mobile_no").value;
		  var edit_student_parent_mobile_no=document.getElementById("edit_student_parent_mobile_no").value;
		  
		  if(edit_student_name=="") 
		  {
			alert("Please Enter Student Name "); 
			document.getElementById("edit_student_name").focus();
			return false;
		  }
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
            "&student_id="+encodeURI( student_id ) +
            "&student_name="+encodeURI(edit_student_name)+
            "&student_address="+encodeURI(edit_student_address) +            
            "&student_student_mobile_no="+encodeURI(edit_student_student_mobile_no) +
            "&student_parent_mobile_no="+encodeURI(edit_student_parent_mobile_no);    
    }
    	
	else if(action_type=="change")
	{
		
    var mobile_number=document.getElementById("mobile_number").value;        
		
		  if(mobile_number=="") 
		  {
			alert("Please Enter Mobile Number "); 
			document.getElementById("mobile_number").focus();
			return false;
		  }
				
			var poststr="action_type="+encodeURI(action_type ) + 
					"&mobile_number="+mobile_number;							
			
	}
    makePOSTRequest('src/php/action_manage_student.htm', poststr);
 }

function checkbox_selection_setting(obj)
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
		return id;
	}
}

  function select_all_students(obj)
  {
  	if(obj.all.checked)
  	{
  		var i;
  		var s = obj.elements['card_id[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked="true";		
  	}
  	else if(obj.all.checked==false)
  	{
  		var i;
  		var s = obj.elements['card_id[]'];
  		for(i=0;i<s.length;i++)
  			s[i].checked=false;  		
  	}
  }  

//////////////// SCHOOL CLOSED //////////////////////////////  
 
  /////////////////////  SETTING MODULE CLOSED ////////////////////////////////