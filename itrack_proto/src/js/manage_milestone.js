
  function get_latlng_fields(number)
  {
  	alert("get_latlng_fields");
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
							"&group_account_id=" + encodeURI( document.getElementById("group_account_id").value )+
							"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
							"&ms_type=" + encodeURI(get_radio_button_value1(obj1.elements["ms_type"]))+							
							"&points="+points;       
      }             
			
      else if(action_type == "edit")
			{
				var poststr="action_type=" + encodeURI(action_type)+							
							"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
							"&ms_type=" + encodeURI(get_radio_button_value1(obj1.elements["ms_type"]))+							
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


