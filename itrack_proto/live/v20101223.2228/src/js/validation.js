// JavaScript Document
function get_radio_button_value1(rb)
{
  rb_value = (rb.value==null) ? -1:rb.value;
  for (i=0; i<rb.length; i++)
  {
    if (rb[i].checked==true)
    {
      rb_value = rb[i].value;
    }
  }
  //alert("Riz:rbvalue="+rb_value)
  return rb_value;
}

function validate_manage_add_account(obj)
{ 
  var login = document.getElementById("login").value;
  var password = document.getElementById("password").value;
  var password2 = document.getElementById("re_enter_password").value;  
  var ac_type = get_radio_button_value1(obj.elements["ac_type"]);
  var company_type = get_radio_button_value1(obj.elements["company_type"]);
  var perm_type = get_radio_button_value1(obj.elements["perm_type"]);
  var admin_perm = document.getElementById("admin_perm").value;
  var fcount = document.getElementById("fcount").value;
  
  var fname=new Array();
  var fvalue=new Array();
  var name_fname_i=new Array();
  var name_fname = "";
  var ac_feature = 0;
  for (i=1; i<=fcount; i++)
  {
    fname[i] = document.getElementById("fname"+i).value;
    // fvalue[i] = document.getElementById(fname[i]).value;
    fvalue[i] = (document.getElementById(fname[i]).checked) ? 1:0;
    
    if(fvalue[i]==1) 
    {
      ac_feature = 1; 
    }
  }    
  //alert("Riz:login="+login+" ,pass="+password+" ,pass2="+password2+" ,ac_type="+ac_type+" ,comp_type="+company_type+" ,perm_type="+perm_type+" ,admin_perm="+admin_perm+" ,name_fname="+name_fname)
  
  if(login=="")
  {
    alert("Login field can not be Empty!");
    return false;
  }
  if(password=="")
  {
    alert("Password field can not be Empty!");
    return false;
  }
  if(password!=password2)
  {
    alert("Password field do not match!");
    return false;
  }  
  if(ac_type==-1)
  {
    alert("Must Select Atleast one Permission option!");
    return false;
  }
  if(company_type==-1)
  {
    alert("Must Select Atleast one Company option!");
    return false;
  }
  if(perm_type==-1)
  {
    alert("Must Select Atleast one Admin Permission option!");
    return false;
  }    
  /*if(admin_perm=="")  // select box
  {
    alert("Login field can not be Empty!");
    return false;
  } */
  if(ac_feature==0)
  {
    alert("Must Select Atleast one Account Feature!");
    return false;
  }          
 
  return true;
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
 
function validate_manage_add_device_sale(obj)
{  
  var imei_no = document.getElementById("imei_no").value;
  var super_user = document.getElementById("super_user").value;
  var user = document.getElementById("user").value;
  var qos = document.getElementById("qos").value;
  
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
  if(qos == "")
  {
    alert("QOS field can not be Empty!");
    return false;    
  }   
  return true;              
}

function validate_manage_edit_device_sale(obj)
{  
  var imei_no_edit = document.getElementById("imei_no_edit").value;
  var super_user_edit = document.getElementById("super_user_edit").value;
  var user_edit = document.getElementById("user_edit").value;
  var qos_edit = document.getElementById("qos_edit").value;
  
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
  if(qos_edit == "")
  {
    alert("QOS field can not be Empty!");
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

function set_option(h,s,o)
{
  var v1=document.getElementById(o).value;
  v1=v1.split('#')
  document.getElementById(s).value=v1[1];
  document.getElementById(h).value="correct";
  enable_assign()
}
function enable_assign()
{
 // alert("shams1="+document.getElementById("lh").value+"shams2="+document.getElementById("rh").value);
  if(document.getElementById("lh").value=="correct" &&  document.getElementById("rh").value=="correct")
  {
     //document.getElementById("enter_button").enable=true;
  }
  else
  {
    //alert("shams_test");
    //document.getElementById("enter_button").enable=false;
  }
}

// DATA LOG VALIDATION CLOSED