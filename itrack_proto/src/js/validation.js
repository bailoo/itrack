// JavaScript Document
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
 

function validate_manage_add_group(obj)
{
	var cnt=0;	
	var obj=document.manage1.manage_user;
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

function validate_manage_edit_group(obj)
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
  
function index_validate_form(obj)
{
  obj.width.value = client_data('width');
  obj.height.value = client_data('height');
  obj.resolution.value = client_data('width') + "x" + client_data('height');
	if(obj.superuser.value == "")
	{
		alert("Please Enter Super User");
		obj.superuser.focus();
		return false;
	}
	if(obj.user.value == "")
	{
		alert("Please Enter User");
		obj.user.focus();
		return false;
	}
	if(obj.group.value == "")
	{
		alert("Please Enter Group");
		obj.group.focus();
		return false;
	}
	if(obj.password.value == "")
	{
		alert("Please Enter Password");
		obj.password.focus();
		return false;
	}
	return true;
}

//////// DATE CHECK //////////////////////////////////////////////  
function DateCheck()
{	
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1
	var day = currentTime.getDate()
	var year = currentTime.getFullYear()
	var startdate=document.forms[0].StartDate.value;
	var enddate=document.forms[0].EndDate.value;
	var startlen = startdate.length;
	var endlen = enddate.length;
	if(((startlen > 0)&&(startlen < 10))||((endlen > 0)&&(endlen < 10)))
	{
		alert("Incorrect date  format...enter yyyy-mm-dd");
		return false;
	}

	if(startlen > 0)
	{
		var startday = startdate.substr(8,2);
		var startmonth = startdate.substr(5,2);
		var startyr = startdate.substr(0,4);
		if(startyr > year)
		{
			alert("Incorrect Date From Value...Please Enter Again");
			document.forms[0].StartDate.focus();
			return false;
		}
		if(year == startyr)
		{
			if(startmonth == month)
				if(startday > day)
				{
					alert("Incorrect Date From Value...Please Enter Again");
					document.forms[0].StartDate.focus();
					return false;
				}
			if(startmonth > month)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.forms[0].StartDate.focus();	
				return false;
			}
		}
		var leapyr=0;
		if(startyr%4 == 0)
		{
			if(startyr%100 != 0)
			{
				leapyr = 1;
			}
			else
			{
				if(startyr%400 == 0)
					leapyr = 1;
				else
					leapyr = 0;
			}
		}
		if((leapyr == 1)&&(startmonth == "02"))
		{
			if(startday > 29)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.forms[0].StartDate.focus();
				return false;
			}
		}
		if((leapyr == 0)&&(startmonth == "02"))
		{
			if(startday > 28)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.forms[0].StartDate.focus();
				return false;
			}
		}
		if((startmonth == "04")||(startmonth == "06")||(startmonth == "09")||(startmonth == "11"))
		{
			if(startday > 30)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.forms[0].StartDate.focus();						
				return false;
			}
		}
	}

	if(endlen > 0)
	{
		var endday = enddate.substr(8,2);
		var endmonth = enddate.substr(5,2);
		var endyr = enddate.substr(0,4);
		if(endyr > year)
		{
			alert("Incorrect Date To Value...Please Enter Again");
			document.forms[0].EndDate.focus();
			return false;
		}
		if(year == endyr)
		{
			if(endmonth == month)
				if(endday > day)
				{
					alert("Incorrect Date To Value...Please Enter Again");
					document.forms[0].EndDate.focus();
					return false;
				}
			if(endmonth > month)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.forms[0].EndDate.focus();	
				return false;
			}
		}
		var leapyr=0;
		if(endyr%4 == 0)
		{
			if(endyr%100 != 0)
			{
				leapyr = 1;
			}
			else
			{
				if(endyr%400 == 0)
					leapyr = 1;
				else
					leapyr = 0;
			}
		}
		if((leapyr == 1)&&(endmonth == "02"))
		{
			if(endday > 29)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.forms[0].EndDate.focus();
				return false;
			}
		}
		if((leapyr == 0)&&(endmonth == "02"))
		{
			if(endday > 28)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.forms[0].EndDate.focus();
				return false;
			}
		}
		if((endmonth == "04")||(endmonth == "06")||(endmonth == "09")||(endmonth == "11"))
		{
			if(endday > 30)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.forms[0].EndDate.focus();
				return false;
			}
		}
	}
	if((startlen > 0)&&(endlen > 0))
	{
		if(startyr > endyr)
		{
			alert("Incorrect Duration Entered...Please Enter Again");
			document.forms[0].StartDate.focus();
			return false;
		}
		if(startyr == endyr)
		{

			if(endmonth == startmonth)
				if(startday > endday)
				{
					alert("Incorrect Duration Entered...Please Enter Again");
					document.forms[0].StartDate.focus();
					return false;
				}
			if(startmonth > endmonth)
			{
				alert("Incorrect Duration Entered...Please Enter Again");
				document.forms[0].StartDate.focus();
				return false;
			}
		}
	}
	return true;
}  
////////////////////////////////////
 
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
	var option_choices=0;	
	if((obj.option1.checked)||(obj.option2.checked)||(obj.option3.checked)||(obj.option4.checked)||(obj.option5.checked)||(obj.option6.checked)||(obj.option7.checked)||(obj.option8.checked)||(obj.option9.checked)||(obj.option10.checked)||(obj.option11.checked)||(obj.option12.checked)||(obj.option13.checked))
	{ 
			option_choices = 1; 
	}
	
	if (option_choices < 1 )
	{
		alert("Please Select At Least One Display Option");
		return false;
	}
}

/// unknown
function validate_form(obj) 
{
	var option_choices=0;
	var numtype = 0;
	var i = 0;
	var s = obj.elements['vehicleid[]'];
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
}
function All(obj)
{
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['vehicleid[]'];
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['vehicleid[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}
///////////////////////

/// REPORT VEHICLE
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
	var option_choices=0;	
	if((obj.option1.checked)||(obj.option2.checked)||(obj.option3.checked)||(obj.option4.checked)||(obj.option5.checked)||(obj.option6.checked)||(obj.option7.checked)||(obj.option8.checked)||(obj.option9.checked)||(obj.option10.checked)||(obj.option11.checked)||(obj.option12.checked)||(obj.option13.checked))
	{ 
			option_choices = 1; 
	}
	
	if (option_choices < 1 )
	{
		alert("Please Select At Least One Display Option");
		return false;
	}
}

///////REPORT VEHICLE CLOSED/////////////////	

// DATA LOG VALIDATION

function date_validation()
{	
	var startdate=document.forms[0].StartDate.value;	
	var start_date1=startdate.split(" ");
	var sd=start_date1[0];
	
	var enddate=document.forms[0].EndDate.value;
	var enddate1=enddate.split(" ");
	var ed=enddate1[0];

	var today_date1=document.forms[0].today_date.value;
	var today_date2=today_date1.split(" ");
	var td=today_date2[0];
	
	if(td == sd && td == ed)
	{
		return true;
	}
	else
	{
		alert("Please Enter Current Date")
		return false;
	}
}

function set_option(search_obj,select_obj)
{
  //alert("val1="+search_obj+"val2="+select_obj);
  var select_obj_value =document.getElementById(select_obj).value;
  //alert("select_obj_value="+select_obj_value)
  //v1=v1.split('#')
  document.getElementById(search_obj).value=select_obj_value;
  //document.getElementById(h).value="correct";
  //enable_assign()
}
function enable_assign()
{
  //alert("shams1="+document.getElementById("lh").value+"shams2="+document.getElementById("rh").value);
  if(document.getElementById("lh").value=="correct" &&  document.getElementById("rh").value=="correct")
  {
     document.getElementById("enter_button").disabled=false;
  }
  else
  {
    //alert("shams_test");
    document.getElementById("enter_button").disabled=true;
  }
}

function assign_validation(obj)
{ 
  var left_search=obj.left_search;
  var right_search=obj.right_search;
  var flag=0;
  var left_search1;
  var right_search1;
  //var j=0;
  for(var i=0;i<left_search.length;i++)
  {
      if(left_search[i].checked)
      {
        left_search1=parseInt(left_search[i].value);              
        flag=1;
      } 
  }
  
  
  if(flag==1)
  {
     if(left_search1=="1")
     {
        if(obj.ls.value=="")              
        {
          alert("Please Fill Left Search Field");
          obj.ls.focus();
          return false;              
        }
     }
     else if(left_search1=="2")
     {
        if(obj.lo.value=="")              
        {
          alert("Please Select Vehicle");              
          obj.lo.focus();
          return false;              
        }           
     }      
  }
  else 
  {
    alert("Please Enter Left Search OR Left Select Option");     
    return false;
  }
flag=0;
  
    for(var i=0;i<right_search.length;i++)
    {
        if(right_search[i].checked)
        {
            right_search1=right_search[i].value;
            flag=1;      
        }
    }
  if(flag==1)
  {
    if(right_search1=="1")
     {
        if(obj.rs.value=="")              
        {
          alert("Please Fill Right Search Field");
          obj.rs.focus();
          return false;              
        }
     }
     else if(right_search1=="2")
     {
        if(obj.ro.value=="")              
        {
          alert("Please Select Vehicle");
          obj.ro.focus();
          return false;              
        }           
     }      
  }
  else
  {
    alert("Please Select Right Search Option OR Right Select Option");
    return false;
  }
  
  return true;
}

function select_all_portal_option(obj)
{	
	//var option_in_portal=document.getElementById("option_in_portal").value;
	//alert("value="+option_in_portal);
	//var obj1=document.thisform;
	if(obj.all_1.checked)
	{
		var i;
		var s = obj.elements['home_array[]'];		
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all_1.checked==false)
	{
		var i;
		var s = obj.elements['home_array[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}








function manage_tree_validation(obj)
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
		makePOSTRequest('src/php/'+file_name, poststr);
	}	
}

function report_tree_validation(obj)
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
}

function manage_grouping_validation(obj)
{
	var tree_option_id = "";
	//alert("obj="+obj);
	var users_flag=document.getElementById("users").value;
	//alert("users_flag="+users_flag);	
	var tree_option_obj=obj.elements['manage_option[]'];	
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
		makePOSTRequest('src/php/'+file_name, poststr);
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



function IO_SelectAll(obj)
{
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['vehicle_id[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['vehicle_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}




function setting_validate_user()
{
	var obj=document.setting.manage_user;	
	var result=setting_check_selection(obj);
	return result;
}

function setting_check_selection(obj)
{
	var cnt=0;	
	for (var i=0;i<obj.length;i++)
	{
		if(obj[i].checked==true)
		{
		var account_id=obj[i].value;
		cnt++;
		}	  
	}
	if(cnt==0)
	{
		alert("Please Select Atleast Option");
		return false;
	}
	else
	{
		return account_id;
	}
}

// DATA LOG VALIDATION CLOSED