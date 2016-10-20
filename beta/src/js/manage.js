var map_div;
var new_request_time = new Date();
var refreshIntervalId;
  function action_manage_mor_shift(evening)
  {    
	document.getElementById("excel_morning_shift").value=1;
	document.getElementById("excel_evening_shift").value=0;
    document.forms[0].submit();
  }
  
  function action_manage_ev_shift(morning)
  {  
	document.getElementById("excel_evening_shift").value=1;
	document.getElementById("excel_morning_shift").value=0;
	
    document.forms[0].submit();
  } 
  
  function keep_alive() {
    http_request = new XMLHttpRequest();
    http_request.open('GET', "http://www.itracksolution.co.in/src/php/dummyServerPage.php");
    http_request.send(null);
};
  
  function actionManageSecondaryVehicle(actionType)
	{
		var form_obj=document.manage1.elements['secondaryVehicles[]'];		
		var vehicleImei=checkbox_selection(form_obj);
		if(vehicleImei!=false)
		{
			var obj=document.manage1.shifTime;
			var shift = radio_selection(obj);
			if(shift!=false)
			{
				var poststr = "action_type="+actionType+
				"&vehicleImei=" +vehicleImei+
				"&shift=" +shift;
			}
		}
		//alert("poststr="+poststr);
		//showManageLoadingMessage();		
		makePOSTRequest("src/php/actionManageSecondaryVehicle.htm", poststr);		
	}
	function showSiftVehicleDeAssign(shitTime)
	{
		var poststr = "shitTime="+shitTime;
		makePOSTRequest("src/php/showSiftVehicleDeAssign.htm", poststr);
	}
	function showSiftVehicleAssign(shitTime)
	{
		var poststr = "shitTime="+shitTime;
		makePOSTRequest("src/php/showSiftVehicleAssign.htm", poststr);
	}
	function selectAllCheckboxElement(obj,checkUncheckElement)
	{
		if(obj.all.checked)
		{
			var i;
			var s = obj.elements[checkUncheckElement];
			for(i=0;i<s.length;i++)
				s[i].checked="true";		
		}
		else if(obj.all.checked==false)
		{
			var i;
			var s = obj.elements[checkUncheckElement];
			for(i=0;i<s.length;i++)
				s[i].checked=false;  		
		}
	}
	var counter;  
	function addRowToTable()
	{
		var tbl = document.getElementById('tblSample');
		var lastRow = tbl.rows.length;
		var iteration = lastRow;
		counter = iteration;
		var row = tbl.insertRow(lastRow);
		var cellLeft = row.insertCell(0);
		var textNode = document.createTextNode(iteration);
		cellLeft.appendChild(textNode);
		var cellRight1 = row.insertCell(1);
		var el = document.createElement('input');
		el.setAttribute('type', 'text');
		el.setAttribute('id', 'order_item' + iteration);      
		el.setAttribute('placeholder', 'Enter Order Item');
		el.setAttribute('size', '15');
		cellRight1.appendChild(el);

		var cellRight2 = row.insertCell(2);
		var e2 = document.createElement('input');
		e2.setAttribute('type', 'text');
		e2.setAttribute('id', 'order_qty' + iteration);
		e2.setAttribute('placeholder', 'Enter Order Quantity');
		e2.setAttribute('size', '15');
		cellRight2.appendChild(e2);

		var cellRight3 = row.insertCell(3);
		var e3 = document.createElement('input');
		e3.setAttribute('type', 'text');
		e3.setAttribute('id', 'unit_price' + iteration);
		e3.setAttribute('placeholder', 'Enter Unit Price');
		e3.setAttribute('size', '15');
		cellRight3.appendChild(e3);

		var cellRight4 = row.insertCell(4);
		var e4 = document.createElement('input');
		e4.setAttribute('type', 'text');
		e4.setAttribute('id', 'any_other_cost' + iteration);
		e4.setAttribute('placeholder', 'Enter Any Other Cost');
		e4.setAttribute('size', '15');
		cellRight4.appendChild(e4);

		var cellRight5 = row.insertCell(5);
		var e5 = document.createElement('input');
		e5.setAttribute('type', 'text');
		e5.setAttribute('id', 'discount' + iteration);
		e5.setAttribute('placeholder', 'Enter Discount');
		e5.setAttribute('size', '15');
		cellRight5.appendChild(e5);

		var cellRight6 = row.insertCell(6);
		var e6 = document.createElement('input');
		e6.setAttribute('type', 'text');
		e6.setAttribute('id', 'total_price' + iteration);
		e6.setAttribute('placeholder', 'Enter Total Price');
		e6.setAttribute('size', '15');
		cellRight6.appendChild(e6);
	}
  
  function removeRowFromTable()
  {
    var tbl = document.getElementById('tblSample');
    var lastRow = tbl.rows.length;
    if (lastRow > 2) tbl.deleteRow(lastRow - 1);
    counter = lastRow-2;
  }
  /////////// route assignment//////////////////
  
function highlight(action,id)
{
	//alert('action='+action+"id="+id);
	if(action)	
	document.getElementById('word'+id).bgColor = "#C2B8F5";
	else
	document.getElementById('word'+id).bgColor = "#F8F8F8";
}

function action_manage_route2(action_type)
 {  
    //alert("action_type="+action_type);       
	var poststr= "";
	if(action_type=="assign")
	{		
		var combo_obj=document.manage1.elements['unassigned_vehicles[]'];
		var result = "";
		var result2 = "";
		var all_vehicles = "";
		//alert("combo_obj_len="+combo_obj.length);
		if(combo_obj.length!=undefined)
		{
			for (var i=0;i<combo_obj.length;i++)
			{									
				var selvalue11=combo_obj[i].value+"text_content";
				var selvalue=document.getElementById(selvalue11).value;
				//var style_data1 = document.getElementById(selvalue11).style.bgcolor;
				//alert("selvalu11="+selvalue11+" ,color="+document.getElementById(selvalue11).style.backgroundColor);
				//hex = #EDEBEC
				if( (selvalue!="") || (document.getElementById(selvalue11).style.backgroundColor == "rgb(237, 235, 236)") )
				{
					/*if(selvalue!="")
					{
						for(var j=i+1;j<combo_obj.length;j++)
						{
							var selvalue1_tmp=combo_obj[j].value+"text_content";
							var selvalue1_next=document.getElementById(selvalue1_tmp).value;
							if(selvalue == selvalue1_next)
							{
								alert("Duplicate Route has been entered in Evening Shift : Route:"+selvalue);
								return false;
							}
						}
					}*/
					//alert("RGB Found");
					var rem_obj_val=combo_obj[i].value+"rem";
					var remvalue = document.getElementById(rem_obj_val).value;
					//alert("remvalue="+remvalue+" ,selvalue="+selvalue);
					result = result+combo_obj[i].value+":"+selvalue+":"+remvalue+",";
				}

				var selvalue_mor=combo_obj[i].value+"text_content2";
				var selvalue2=document.getElementById(selvalue_mor).value;
				//var style_data2 = document.getElementById(selvalue_mor).style;
				
				if( (selvalue2!="") || (document.getElementById(selvalue_mor).style.backgroundColor == "rgb(237, 235, 236)") ) 
				{
					/*if(selvalue2!="")
					{
						for(var j=i+1;j<combo_obj.length;j++)
						{
							var selvalue2_tmp=combo_obj[j].value+"text_content2";
							var selvalue2_next=document.getElementById(selvalue2_tmp).value;
							if(selvalue2 == selvalue2_next)
							{
								alert("Duplicate Route has been entered in Morning Shift : Route:"+selvalue2);
								return false;
							}
						}
					}*/					
					
					var rem_obj_val2=combo_obj[i].value+"rem2";
					var remvalue2 = document.getElementById(rem_obj_val2).value;
					//alert("remvalue="+remvalue+" ,selvalue="+selvalue);
					result2 = result2+combo_obj[i].value+":"+selvalue2+":"+remvalue2+",";
				}
				
				all_vehicles = all_vehicles + combo_obj[i].value+",";
				
			}
		}
		//alert(result);	
		if(result!="" || result2!="")
		{	
			poststr="action_type="+encodeURI(action_type ) + 
				"&local_account_id="+encodeURI(document.getElementById("account_id_hidden").value) +
				"&route_vehicle_ev=" +result +
				"&route_vehicle_mor=" +result2 +
				"&all_vehicles=" +all_vehicles +				
				"&cdate="+new_request_time;
		}			
	}
	/*else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['vehicle_route[]'];
		var checkbox_result=checkbox_selection(form_obj); ///////// validate and get vehicleids		
    			
		if(checkbox_result!=false)
		{		
				poststr="action_type="+encodeURI(action_type ) + 
				"&local_account_id="+encodeURI(document.getElementById("account_id_hidden").value) +	
				"&vehicle_route="+checkbox_result;					// vehicle id also includes station ids with : separator								
		}			
	}*/
	  //alert("poststr11="+poststr);
    makePOSTRequest('src/php/action_manage_route2.htm', poststr);
 }

 function show_route_block(value)
 {
	if(value==1)
	{
		document.getElementById("evening_block").style.display='';
		document.getElementById("morning_block").style.display='none';
	}
	else if(value==2)
	{
		document.getElementById("morning_block").style.display='';	
		document.getElementById("evening_block").style.display='none';		
	}
 }
 
 function action_manage_route2_substation(action_type)
 {  
    //alert("action_type="+action_type);       
	var poststr= "";
	if(action_type=="assign")
	{		
		var route_obj_t1 =document.manage1.elements['unassigned_route_t1[]'];
		var route_obj_t2 =document.manage1.elements['unassigned_route_t2[]'];
		var all_vehicles = document.getElementById("vehicle_list_hidden").value;
		var arr1 = document.getElementById("arr1").value;
		var arr2 = document.getElementById("arr2").value;
		
		var block_sel_value;
		var radio_sel = document.manage1.elements['block_sel'];
		for(var i=0;i<radio_sel.length;i++)
		{
			if(radio_sel[i].checked)
			{
				if(radio_sel[i].value==1)
				{
					block_sel_value = 1;
					var arr_pre=arr1;
				}
				else
				{
					block_sel_value = 2;
					var arr_pre=arr2;
				}
			}			
		}
		//alert(block_sel_value);
		var result_ev = "";
		var result_mor = "";
		//alert("combo_obj_len="+combo_obj.length);
		//######## EVENING
		if(block_sel_value==1)
		{
		if(route_obj_t1.length!=undefined)
		//if(route_obj_t1.length!=undefined && block_sel_value==1)
		{
			for (var i=0;i<route_obj_t1.length;i++)
			{	
		
				var selvalue1 =route_obj_t1[i].value+"text_content1";	
				var selvalue_vehicle_ev =document.getElementById(selvalue1).value;		//vehicle
				//var style_data1 = document.getElementById(selvalue11).style.bgcolor;
				//alert("selvalu11="+selvalue11+" ,color="+document.getElementById(selvalue11).style.backgroundColor);
				//hex = #EDEBEC
				//if( (selvalue_vehicle_ev!="") || (document.getElementById(selvalue1).style.backgroundColor == "rgb(237, 235, 236)") )
				{
					//alert("RGB Found");
					var time_obj_ev=route_obj_t1[i].value+"text_content1_time_ev";
					var time_value_ev = "";
					if(document.getElementById(time_obj_ev).checked)
					{
						//alert("checked");
						time_value_ev = "1";
					}
					else
					{
						time_value_ev = "0";
					}
					
					/*if(i==0)
					{
						alert("time_value_ev="+time_value_ev);
					}*/
					result_ev = result_ev + route_obj_t1[i].value+":"+selvalue_vehicle_ev+":"+time_value_ev+",";
				}				
			}
			
			if(result_ev!="")
			{	
				poststr="action_type="+encodeURI(action_type ) + 
					"&local_account_id="+encodeURI(document.getElementById("account_id_hidden").value) +
					"&parent_admin_id="+encodeURI(document.getElementById("parent_admin_id_hidden").value) +				
					"&result_ev=" +result_ev +
					"&result_mor=" +result_mor +
					"&all_vehicles=" +all_vehicles +	
					"&arr1=" +arr_pre +				
					"&cdate="+new_request_time+
					"&block_sel_value="+block_sel_value;
			}			
		}
	     }
		//############ MORNING
	
		if(block_sel_value==2)
		{
		if(route_obj_t2.length!=undefined)
		{
			for (var i=0;i<route_obj_t2.length;i++)
			{													
				var selvalue2 =route_obj_t2[i].value+"text_content2";	
				var selvalue_vehicle_mor =document.getElementById(selvalue2).value;		//vehicle
				//var style_data1 = document.getElementById(selvalue11).style.bgcolor;
				//alert("selvalu11="+selvalue11+" ,color="+document.getElementById(selvalue11).style.backgroundColor);
				//hex = #EDEBEC
				//if( (selvalue_vehicle_mor!="") || (document.getElementById(selvalue2).style.backgroundColor == "rgb(237, 235, 236)") )
				{
					var time_obj_mor =route_obj_t2[i].value+"text_content2_time_mor";
					
					var time_value_mor = "";
					if(document.getElementById(time_obj_mor).checked)
					{
						//alert("checked");
						time_value_mor = "1";
					}
					else
					{
						time_value_mor = "0";
					}					
					/*if(i==0)
					{
						alert("time_value_mor="+time_value_mor);
					}*/
					result_mor = result_mor + route_obj_t2[i].value+":"+selvalue_vehicle_mor+":"+time_value_mor+",";
				}				
								
			}
			
			if(result_mor!="")
			{	
				poststr="action_type="+encodeURI(action_type ) + 
					"&local_account_id="+encodeURI(document.getElementById("account_id_hidden").value) +
					"&parent_admin_id="+encodeURI(document.getElementById("parent_admin_id_hidden").value) +				
					"&result_ev=" +result_ev +
					"&result_mor=" +result_mor +
					"&all_vehicles=" +all_vehicles +	
					"&arr1=" +arr_pre +				
					"&cdate="+new_request_time+
					"&block_sel_value="+block_sel_value;
			}					
		}
	}		
		//alert(result);				
	}
	
	//alert("poststr11="+poststr);
    makePOSTRequest('src/php/action_manage_route2_substation.htm', poststr);
 }
  
  function getScriptPage1(div_id,content_id,vehicle_id,fieldValue)
{	
	var content = fieldValue;
	var form_obj=document.manage1.elements['excel_assigned_ev_shift[]'];
	var cmp_vehicle_id=vehicle_id+"text_content";
	var morFieldsValue=hidden_arr_selection(form_obj,cmp_vehicle_id); // only not null value
	var poststr = "content=" +content+
	"&local_vehicle_id="+vehicle_id+
	"&morFieldsValue="+morFieldsValue+
	"&local_account_id="+document.getElementById("account_id_hidden").value;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/datalog_script_search1.htm', poststr);
	/*if(content.length>0)
	box('1');
	else
	box('0');*/
}

 function getScriptPage2(div_id,content_id,vehicle_id,fieldValue)
{
	var content = fieldValue;	
	var form_obj=document.manage1.elements['excel_assigned_mor_shift[]'];
	var cmp_vehicle_id=vehicle_id+"text_content2";
	var morFieldsValue=hidden_arr_selection(form_obj,cmp_vehicle_id); // only not null value
	var poststr = "content=" +content+
	"&local_vehicle_id="+vehicle_id+
	"&morFieldsValue="+morFieldsValue+
	"&local_account_id="+document.getElementById("account_id_hidden").value;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/datalog_script_search2.htm', poststr);
	//alert("content="+content.length);
	/*if(content.length>0)
	box2('1',vehicle_id);
	else
	box2('0',vehicle_id);*/
}

function getScriptPage1_substation(div_id,content_id,vehicle_id,fieldValue)
{
//alert("function");	
	var content = fieldValue;
	var form_obj=document.manage1.elements['excel_assigned_ev_shift[]'];
	var cmp_vehicle_id=vehicle_id+"text_content";
	var morFieldsValue=hidden_arr_selection_substation(form_obj,cmp_vehicle_id); // only not null value
	var poststr = "content=" +content+
	"&local_vehicle_id="+vehicle_id+
	"&morFieldsValue="+morFieldsValue+
	"&all_vehicles="+encodeURI(document.getElementById("vehicle_list_hidden").value)+
	"&parent_admin_id="+document.getElementById("parent_admin_id_hidden").value+
	"&local_account_id="+document.getElementById("account_id_hidden").value;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/datalog_script_search1_substation.htm', poststr);
}

 function getScriptPage2_substation(div_id,content_id,vehicle_id,fieldValue)
{
	//alert (content_id);//text_content2
	var content = fieldValue;
	var form_obj=document.manage1.elements['excel_assigned_mor_shift[]'];
	var cmp_vehicle_id=vehicle_id+"text_content";
	//var cmp_vehicle_id=vehicle_id+content_id;
	var morFieldsValue=hidden_arr_selection_substation(form_obj,cmp_vehicle_id); // only not null value
	var poststr1 = "content=" +content+
	"&local_vehicle_id="+vehicle_id+
	"&morFieldsValue="+morFieldsValue+
	"&all_vehicles="+encodeURI(document.getElementById("vehicle_list_hidden").value)+
	"&parent_admin_id="+document.getElementById("parent_admin_id_hidden").value+
	"&local_account_id="+document.getElementById("account_id_hidden").value;
	//alert("poststr="+poststr1);
	makePOSTRequest('src/php/datalog_script_search2_substation.htm', poststr1);
}

function hidden_arr_selection(obj,cmp_vehicle_id)
{
	var tmpAtTheRateValue="";
	var sATValue=""; //// s= slash , AT=at the rate value
	var tmpAtTheRateValue1="";
	var slashValue="";
	var cnt=0;
	var tmpArr=new Array();
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].value!="")
			{
				//if(documnet.getElementById(vid+"text_content").value			
				//alert("Obj.id="+obj[i].id+ ",cmpvid="+cmp_vehicle_id);
				if(obj[i].id!=cmp_vehicle_id)
				{
					if(((obj[i].value.indexOf("/"))==-1) && ((obj[i].value.indexOf("@"))==-1))
					{
						tmpArr[cnt]=obj[i].value;							
						cnt++;					
					}
					else
					{
						sATValue=obj[i].value.split("/");							
						for(var ari=0;ari<sATValue.length;ari++)
						{
							if(sATValue[ari].indexOf("@")==0)
							{
								tmpArr[cnt]=sATValue[ari].slice(1);
								cnt++;
							}
							else
							{
								tmpArr[cnt]=sATValue[ari];
								cnt++;
							}					
						}				
					}
				}
			}	  
		}
	}
	//alert(tmpArr);
	return tmpArr;
	/*if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}*/
}

function hidden_arr_selection_substation(obj,cmp_vehicle_id)
{
	var tmpAtTheRateValue="";
	var sATValue=""; //// s= slash , AT=at the rate value
	var tmpAtTheRateValue1="";
	var slashValue="";
	var cnt=0;
	var tmpArr=new Array();
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].value!="")
			{
				//if(documnet.getElementById(vid+"text_content").value			
				//alert("Obj.id="+obj[i].id+ ",cmpvid="+cmp_vehicle_id);
				if(obj[i].id!=cmp_vehicle_id)
				{
					if(((obj[i].value.indexOf("/"))==-1) && ((obj[i].value.indexOf("@"))==-1))
					{
						tmpArr[cnt]=obj[i].value;							
						cnt++;					
					}
					else
					{
						sATValue=obj[i].value.split("/");							
						for(var ari=0;ari<sATValue.length;ari++)
						{
							if(sATValue[ari].indexOf("@")==0)
							{
								tmpArr[cnt]=sATValue[ari].slice(1);
								cnt++;
							}
							else
							{
								tmpArr[cnt]=sATValue[ari];
								cnt++;
							}					
						}				
					}
				}
			}	  
		}
	}
	//alert(tmpArr);
	return tmpArr;
	/*if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}*/
}
  ////////////////////////////////////////////////
 function add_geo_manually()
 {
   var coord = prompt("Enter Geo coord in format:(latA, lngA),(latB, lngB), (latC, lngC)..(latA lngA) [ends with first set]","");
   document.getElementById('geo_coord').value = coord;
 }
 
function manage_show_plant_customer(type,type_id,display_type_id)
{
	if(type=="select")
	{
		alert("Please Select Type");
		document.getElementById(type_id).focus();
		return false;
	}
	else
	{
		var poststr="moto_pickup_delivery_type="+type+
					"&moto_displaytype_id="+display_type_id;
		//alert("Poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr);  
	}
}

function moto_show_vehicle(action_no)
 {
	var form_obj=document.manage1.manage_id;
	var result=radio_selection(form_obj);
	if(action_no==0)
	{
		//alert("result="+result);
		if(result!=false)
		{
			//alert("type="+document.getElementById("pickup_type").value);
			if(document.getElementById("consignment_name").value=="")
			{	
				alert("Please Enter Consignment Name");
				document.getElementById("consignment_name").focus();
				return false;			
			}
			if(document.getElementById("consignment_code").value=="")
			{	
				alert("Please Enter Consignment Code");
				document.getElementById("consignment_code").focus();
				return false;			
			}
			if(document.getElementById("pickup_type").value=="select")
			{	
				alert("Please Select Pickup Type");
				document.getElementById("pickup_type").focus();
				return false;			
			}
			if(document.getElementById("pickup_point_id").value=="select")
			{	
				alert("Please Select Pickup Point");
				document.getElementById("pickup_point_id").focus();
				return false;			
			}
			if(document.getElementById("date1").value=="")
			{	
				alert("Please Enter Pickup Date Time");
				document.getElementById("date1").focus();
				return false;			
			}
			if(document.getElementById("delivery_type").value=="select")
			{	
				alert("Please Select Delivery Type");
				document.getElementById("delivery_type").focus();
				return false;			
			}
			if(document.getElementById("delivery_point_id").value=="select")
			{	
				alert("Please Select Delivery Point");
				document.getElementById("delivery_point_id").focus();
				return false;			
			}
			if(document.getElementById("date2").value=="")
			{	
				alert("Please Enter Delivery Date Time");
				document.getElementById("date2").focus();
				return false;			
			}
			if(document.getElementById("distance").value=="")
			{	
				alert("Please Enter Distance");
				document.getElementById("distance").focus();
				return false;			
			}
			if(document.getElementById("delivery_point_id").value==document.getElementById("pickup_point_id").value)
			{	
				alert("Pickup Point And Delivery Point Should Not Be Same");
				//document.getElementById("date2").focus();
				return false;			
			}
			
			if(document.getElementById("date1").value>document.getElementById("date2").value)
			{
				alert("Pickup Date Time Should Be Less Then And Delivery Delivery Date Time");
				//document.getElementById("date2").focus();
				return false;	
			}
			poststr="account_id_local1="+result;
			//alert("Poststr="+poststr);
			makePOSTRequest('src/php/manage_consignment_vehicle.htm', poststr);	
		}
	}
	else if((action_no==1))
	{
		var form_obj1=document.manage1.vehicleserial;
		var vehicle_result=radio_selection(form_obj1);
		if(vehicle_result!=false)
		{
			poststr="ActionType=add"+						
					"&MotoVehicleId="+vehicle_result+
					"&ConsignmentName="+document.getElementById("consignment_name").value+
					"&ConsignmentCode="+document.getElementById("consignment_name").value+
					"&PickupType="+document.getElementById("pickup_type").value+
					"&PickupPointId="+document.getElementById("pickup_point_id").value+
					"&PickupDateTime="+document.getElementById("date1").value+
					"&DeliveryType="+document.getElementById("pickup_type").value+
					"&DeliveryPointId="+document.getElementById("pickup_point_id").value+
					"&DeliveryDateTime="+document.getElementById("date2").value+
					"&Distance="+document.getElementById("distance").value;
			//alert("poststr="+poststr);
			makePOSTRequest('src/php/action_manage_moto_consignment.htm', poststr);				
		}
	}
}
 
 function add_sector_manually()
 {
   var coord = prompt("Enter Sector coord in format:(latA, lngA),(latB, lngB), (latC, lngC)..(latA lngA) [ends with first set]","");
   //alert("coord: "+coord)   
   document.getElementById('sector_coord').value = coord;
 }
 
 function show_and_clear_assigned_location(vehicle_id)
 {
  var poststr ="vehicleid_to_location="+vehicle_id; 	
 makePOSTRequest('src/php/manage_clear_assigned_location.htm', poststr);
 }
 
 function manage_delete_schedule_assignment(serial,vehicle_id,vehicle_name)
{
	var txt="Are You Sure You Want To Delete This One";
	if(!confirm(txt))
	{
		return false; 
	}
	else
	{
		var poststr = "cla_serial=" + serial+  //cla=clear loaction assignment
					  "&cla_vehicle_id="+vehicle_id+
					  "&cla_vehicle_name="+vehicle_name;						  
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr);   
	}		
}
function manage_edit_schedule_assignment(serial,vehicle_id,vehicle_name,to_date)
{
	document.getElementById("blackout_edit_sa").style.visibility = "visible";
	document.getElementById("divpopup_edit_sa").style.visibility = "visible";
	document.getElementById("blackout_edit_sa").style.display = "block";
	document.getElementById("divpopup_edit_sa").style.display = "block"; 
	document.getElementById("date1").value=to_date;
	document.getElementById("cmp_date1").value=to_date;
	document.getElementById("edit_sa_serial").value=serial;
	document.getElementById("edit_sa_vehicle_id").value=vehicle_id;
	document.getElementById("edit_sa_vehicle_name").value=vehicle_name;
}

function manage_edit_schedule_assignment_1(serial,vehicle_id,vehicle_name,to_date)
{
	//alert("date1="+document.getElementById("date1").value+"date2="+document.getElementById("cmp_date1").value);
	if(document.getElementById("date1").value<document.getElementById("cmp_date1").value)
	{
		alert("Date to should not be less than previous date to");
		return false;
	}
	else
	{
		document.getElementById("blackout_edit_sa").style.visibility = "hidden";
		document.getElementById("divpopup_edit_sa").style.visibility = "hidden";
		document.getElementById("blackout_edit_sa").style.display = "none";
		var poststr = "edit_sa_serial=" +document.getElementById("edit_sa_serial").value+  //cla=clear loaction assignment
		"&date_to="+document.getElementById("date1").value+
						  "&edit_sa_vehicle_id="+document.getElementById("edit_sa_vehicle_id").value+
						  "&edit_sa_vehicle_name="+document.getElementById("edit_sa_vehicle_name").value;						  
			//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr); 
	}
}
function close_edit_sa_popup()
{
	document.getElementById("blackout_edit_sa").style.visibility = "hidden";
	document.getElementById("divpopup_edit_sa").style.visibility = "hidden";
	document.getElementById("blackout_edit_sa").style.display = "none";
}
 function show_device_imei_no()
 {	
	var form_obj=document.manage1.elements['manage_id[]'];
	var result=checkbox_selection(form_obj);
	//alert("result="+result);
	if(result!=false)
	{
		var veh_validation=vehicle_validation("1");
		if(veh_validation!=false)
		{
			var poststr="accounts_local="+result;
			//alert("Poststr="+poststr);
			makePOSTRequest('src/php/manage_register_device.htm', poststr);
		}
	}
}
function close_vehicle_display_option(value)
{
	var param1=value+"_blackout";
	var param2=value+"_divpopup";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}
function temp()
{
	document.getElementById("portal_vehicle_information").style.display="none";
}

function get_acc_features(usertype, account_str)
{
	var poststr="usertype="+usertype+
	             "&account_str="+account_str;
	             //alert(usertype+","+account_str);
	makePOSTRequest('src/php/manage_get_features.htm', poststr);   
}

function show_vehicle_register(type, option)   // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
{
	//alert("type="+type+"option="+option);
	if(option=="add_vehicle")
	{
		var poststr="show_form=1";
		makePOSTRequest('src/php/' +type+ '_' +option+ '.htm', poststr);
	}
	else
	{
		var account_id_local=document.getElementById('local_account_id').value;
		var poststr = "account_id_local="+account_id_local+
				"&vehicle_display_option="+document.getElementById('vehicle_display_option').value+
				"&page_type="+"no_register"+
				"&options_value="+document.getElementById('options_value').value;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/' +type+ '_' +option+ '.htm', poststr);
	}
}
   
function check_uncheck_features_button(fobj)
{
  if(fobj.all.value == "Select All")
  {
    set_all_features(fobj, true);	    
    fobj.all.value = "Select None";
  }
  else if(fobj.all.value == "Select None")
  {
    set_all_features(fobj, false);	    
    fobj.all.value = "Select All";
  }
  return true;
}

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
				"&manage_id="+document.getElementById('common_id').value+
				"&action_name1="+document.getElementById('action_name').value+
				"&action_no="+"second"+
				"&LocalAccount="+local_account.value+
				"&CheckStatus="+local_account.checked;
				//alert("poststr="+poststr);
	makePOSTRequest('src/php/manage_show_account.htm', poststr); 
} 

function manage_checked_account_1(local_account)
{	
	var accounts_local=new Array();
	var checked_account=document.manage1.elements['manage_id[]'];

	if(checked_account.length!=undefined)
	{
		for(var i=0;i<(checked_account.length);i++)
		{
			if(checked_account[i].checked)
			{		
				accounts_local[i]=checked_account[i].value;
				//alert("checked_acc"+checked_account[i].value);
			}		
		}
	}
	else
	{
		//alert("len="+checked_account.checked);
		if(checked_account.checked==true)
		{
			//alert(checked_account.value);
			accounts_local=checked_account.value;
		}
	}
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
	//alert("checked_acc"+accounts_local);
	var poststr="account_status_1="+account_string+				
				"&action_no="+"second"+
				"&LocalAccount="+local_account.value+
				"&CheckStatus="+local_account.checked+
				"&accounts_local="+accounts_local;
				//alert("poststr="+poststr);
	makePOSTRequest('src/php/manage_checkbox_account_new.htm', poststr); 
} 

 function show_div_block(value)
{	 
 var param1=value+"_blackout";
 var param2=value+"_divpopup";
	//alert("param1="+param1+"param2="+param2);
document.getElementById(param1).style.visibility = "visible";
document.getElementById(param2).style.visibility = "visible";
document.getElementById(param1).style.display = "block";
document.getElementById(param2).style.display = "block"; 
}

function accounts_for_device()
{
	var flag=0;
	var account_string="";
	var manage_ids=document.manage1.elements['manage_id[]'];
	//alert("len="+manage_ids.length);
	if(manage_ids.length!=undefined)
	{
		for(var i=0;i<(manage_ids.length);i++)
		{		
			if(manage_ids[i].checked==true)
			{
				var flag=1;
				account_string=account_string+manage_ids[i].value+",1"+":";		
			}
			else
			{
				var flag=1;
				account_string=account_string+manage_ids[i].value+",0"+":";
			}
		}
	}
	else
	{
		//alert("in else");
		if(manage_ids.checked==true)
		{
			//alert("in if");
			flag=1;
			account_string=account_string+manage_ids.value+",1"+":";
		}
		else
		{
			var flag=1;
			//alert("in else1");
			account_string=account_string+manage_ids.value+",0"+":";
		}
		
	}
	
	if(flag==0)
	{
		alert("Please select atleast one account");
		return false;
	}
	else
	{
		//alert("account_string"+account_string);
		return account_string;
	}
}   
  function show_manage_interfaces(type, option)   // option = add_feature
  {
  	var poststr="account_id_local="+document.getElementById("account_id_local").value;
  	makePOSTRequest('src/php/' + type+ '_'+ option + '.htm', poststr);   
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
	
	function action_manage_listing_files()
	{
		var obj=document.manage1.manage_id;
		var account_id=radio_selection(obj);
		//alert("account_id="+account_id);
		if(account_id!=false)
		{
			document.getElementById("listing_account_id").value="";
			document.getElementById("listing_account_id").value=account_id;		
			document.manage1.action = "src/php/action_manage_listing_files.htm";
			document.manage1.target="_blank";
			document.manage1.submit();		
		}
	}
	
	function manage_io_prev_interface(options)
	{
		//alert("test");
		var poststr = "display_type1=" + encodeURI(options);
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_entity_selection_information.htm', poststr);
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
		showManageLoadingMessage();	
		makePOSTRequest(file_name, '');
	}	
	function manage_show_file_jquery(file_name)
	{
		//alert(file_name);
		//showManageLoadingMessage();	
		//makePOSTRequest(file_name, '');
		var poststr = "x=1";		
		 //alert(poststr);
		$.ajax({
		type: "POST",
		url:file_name,
		data: poststr,
		success: function(response){
			//console.log(response);
			//alert("response="+response);		
			document.getElementById('bodyspan').innerHTML="";	
			$("#bodyspan").html(response);
			//$('body, html').animate({scrollTop:$('html').offset().top-50}, 'slow');
                         $("html,body").animate({ scrollTop: 0 }, "slow");
		},
		error: function()
		{
			alert('An unexpected error has occurred! Please try later.');
		}
		});
	}	
	
	function manage_show_file_1(file_name,action_type)
	{
		showManageLoadingMessage();
		var poststr="action_type="+action_type; 
		//alert("poststr="+poststr);
		makePOSTRequest(file_name, poststr);
               
	}
	
	function manage_show_account(value,file_name)
	{
		document.getElementById("common_id").value=value;
		poststr="manage_id="+value+
				"&action_name1="+document.getElementById("action_name").value;
		//alert("poststr="+poststr);
		makePOSTRequest(file_name, poststr);
	}	
   
  function show_interface(file_name,action_type,common_account_id)    
  {
      var poststr = action_type + "=" + encodeURI(common_account_id);  
      makePOSTRequest(file_name, poststr);   
  }
  
  function manage_edit_prev_interface(file_name,common_account_id)    
  { 
      var poststr = "common_id=" + encodeURI(common_account_id); 
	    //alert("file_name="+file_name+ ", poststr="+poststr);
      makePOSTRequest(file_name, poststr);   
  }
  
    function manage_edit_prev_interface_1(file_name,common_account_id,action_type)    
  {
      var poststr = "common_id=" + encodeURI(common_account_id)+
                    "&action_type="+action_type; 
	  //alert("poststr="+poststr);
      makePOSTRequest(file_name, poststr);   
  }

  
  function validate_usertype_checkbox(obj1)
  {
      var usertype = obj1.elements['user_type[]'];
      var usertype_len = usertype.length;
        
      var utype="";
      if(usertype_len!=undefined)
      {
        for(var i=0;i<(usertype_len);i++)
    		{       
          if(usertype[i].checked==true)
        	{
        	  utype = utype+usertype[i].value+",";   
        	}
        }
        if(utype=="")
        {
          alert("Please select at least one User Type");
          return false;
        }
      }
      else
      {
          if(usertype.checked==true)
        	{
        	  utype = utype+usertype.value+",";   
        	}
        	else
        	{
              return false;
          }
      }
        
      return utype;
  }
  
	function action_manage_account(action_type)
	{
	
		var result1;		
		var result2;         
		var obj1=document.manage1;
		result1 = validate_manage_add_account(action_type);      ////////it is for form validation;
		result2 = validate_usertype_checkbox(obj1);              ////////it check and set value of usertype it may be fleet,mining,courior etc
		//alert("result1="+result1+" ,result2="+result2+"action_type="+action_type);       		 
		if(result1!=false && result2!=false)
		{			
			if(action_type == "add")
			{ 
				var distance_variable=document.getElementById("distance_variable").value;
				if(document.getElementById("distance_variable").value=="")
				{
					var distance_variable='10';
				}
								
					 /* if(obj1.route_substation.checked==true)
						{
							utype = obj1.route_substation.value;
						}
						else{
							utype="";
						}*/
				utype = document.getElementById("sub_utype").value;
					
				//alert("a="+document.getElementById("password").value);		
				var poststr="action_type=" + encodeURI(action_type)+
				"&login=" + encodeURI( document.getElementById("login").value )+
				"&group_id=" + encodeURI( document.getElementById("group_id").value )+
				"&user_name=" + encodeURI( document.getElementById("user_name").value )+
				"&route_substation=" + utype+
				"&distance_variable=" +distance_variable+
				"&add_account_id=" + encodeURI( document.getElementById("add_account_id").value )+
				//"user_type=" + encodeURI( document.getElementById("user_type").value )+
				"&user_type=test"+
				"&password="+document.getElementById("password").value+
				//"&user_type=" + encodeURI(get_radio_button_value1(obj1.elements["user_type"]))+
				"&ac_type=" + encodeURI(get_radio_button_value1(obj1.elements["ac_type"]))+
				"&company_type=" + encodeURI( get_radio_button_value1(obj1.elements["company_type"]) )+
				//"&perm_type=" + encodeURI( get_radio_button_value1(obj1.elements["perm_type"]) )+
				"&perm_type=Child"+
				"&user_type=" + result2+
				//"admin_perm=" + encodeURI( document.getElementById("admin_perm").value )+
				"&account_feature1="+result1; 			    
			} 
			if(action_type == "edit")
			{
			  if(result2!=false)
			  {
				var poststr="action_type=" + encodeURI(action_type)+
							"&edit_account_id=" + encodeURI( document.getElementById("edit_account_id").value )+
							"&user_name=" + encodeURI( document.getElementById("user_name").value )+
							"&distance_variable=" + encodeURI( document.getElementById("distance_variable").value )+
							"&perm_type=" + encodeURI( get_radio_button_value1(obj1.elements["perm_type"]))+
							"&account_feature1="+result1+
							"&user_type="+result2;	
				}					 
			}
			if(action_type == "delete")
			{
				/*var action_type =  "action_type=" + encodeURI( action_type );
				var edit_account = "edit_account=" + encodeURI( document.getElementById("edit_account").value ); */
			}   
				//alert("poststr="+poststr);
			showManageLoadingMessage();
			makePOSTRequest('src/php/action_manage_account.htm', poststr);
		}
	}
	
function action_manage_klp_input(action_type)
 {       
	var poststr= "";
	if(action_type=="assign")
	{
		var combo_obj=document.manage1.elements['unassigned_vehicles[]'];
		var result = "";
		//alert("combo_obj_len="+combo_obj.length);
		var flag=0;
		if(combo_obj.length!=undefined)
		{
			for (var i=0;i<combo_obj.length;i++)
			{
				var icd_vehicle_name=combo_obj[i].value+"_icd_vehicle_name";
				var icd_vehicle_name_1=document.getElementById(icd_vehicle_name).value;
				var icd_code=combo_obj[i].value+"_icd_code";
				var icd_code_1=document.getElementById(icd_code).value;
				var icd_out_datetime=combo_obj[i].value+"_icd_out_datetime";
				var icd_out_datetime_1=document.getElementById(icd_out_datetime).value;				
				var factory_code=combo_obj[i].value+"_factory_code";
				var factory_code_1=document.getElementById(factory_code).value;
				var factory_e_a_t=combo_obj[i].value+"_factory_e_a_t";
				var factory_e_a_t_1=document.getElementById(factory_e_a_t).value;
				var icd_in_datetime=combo_obj[i].value+"_icd_in_datetime";
				var icd_in_datetime_1=document.getElementById(icd_in_datetime).value;
				
				
				var rem=combo_obj[i].value+"_rem";
				var rem_1=document.getElementById(rem).value;
				if(icd_code_1!="")
				{
					//alert("icd_in_datetime_1a="+icd_in_datetime_1+"icd_out_datetimeb="+icd_out_datetime_1);
					if(icd_out_datetime_1=="")
					{
						alert("Please Enter icd out date time");
						document.getElementById(icd_out_datetime).focus();
						return false;
						break;						
					}
					if(trim(factory_e_a_t_1)!="")
					{
						if(factory_e_a_t_1<icd_out_datetime_1)
						{
							alert("factory estimated arrival date time  should not be less than icd out date time");
							document.getElementById(factory_e_a_t).focus();
							return false;
							break;
						}
					}					
					if(trim(icd_in_datetime_1)!="")
					{
						if(icd_in_datetime_1<icd_out_datetime_1)
						{
							alert("In date time should not be less than icd out date time");
							document.getElementById(icd_in_datetime).focus();
							return false;
							break;
						}
					}
					//alert("icd_in_datetime_1="+icd_in_datetime_1);
					if(icd_code_1!="" || icd_out_datetime_1!="" || factory_code_1!="" || factory_e_a_t_1!="" || icd_in_datetime_1!="" || rem_1!="")
					{
						result = result+icd_vehicle_name_1+"@"+icd_code_1+"@"+icd_out_datetime_1+"@"+factory_code_1+"@"+factory_e_a_t_1+"@"+icd_in_datetime_1+"@"+rem_1+",";
						flag=1;
					}
				}
			}
		}
		poststr="action_type="+encodeURI(action_type ) + 
				"&local_account_id="+encodeURI(document.getElementById("account_id_hidden").value) +
				"&klp_input_values=" +result;
	}
	else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['vehicle_route[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
    			
		if(checkbox_result!=false)
		{		
				poststr="action_type="+encodeURI(action_type ) + 
				"&local_account_id="+encodeURI(document.getElementById("account_id_hidden").value) +	
				"&vehicle_route="+checkbox_result;					// vehicle id also includes station ids with : separator	
		}			
	}
	  //alert("poststr="+poststr);
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_klp_input.htm', poststr);
 } 

function action_manage_consignment_info(action_type,obj)
{       
       if(action_type=="add")
       {
            var obj1=obj.manage_id;
            //alert("obj="+obj1);
            var result=radio_selection(obj1);
            // alert("result="+result);
            if(result!=false)
            {
                if(document.getElementById("vehicle_imei_name").value=="select") 
                {
                  alert("Please Select Vehicle Name"); 
                  document.getElementById("vehicle_imei_name").focus();
                  return false;
                }               
                if(document.getElementById("from_place").value=="") 
                {
                  alert("Please Enter From Place Name");
                  document.getElementById("from_place").focus();
                  return false;
                }
                if(document.getElementById("to_place").value=="") 
                {
                  alert("Please Enter To Place Name");
                  document.getElementById("to_place").focus();
                  return false;
                }
                if(document.getElementById("consignee_name").value=="") 
                {
                  alert("Please Enter Consinee Name");
                  document.getElementById("consignee_name").focus();
                  return false;
                }
                if(document.getElementById("date1").value=="") 
                {
                  alert("Please Enter Start Date");
                  document.getElementById("date1").focus();
                  return false;
                }
                if(document.getElementById("date2").value=="") 
                {
                  alert("Please Enter End Date");
                  document.getElementById("date2").focus();
                  return false;
                }
				if(document.getElementById("date1").value>document.getElementById("date2").value)
				{
					alert("Start Date should not be greater than End Date");
					document.getElementById("date2").focus();
					return false;
				}
				if(document.getElementById("email").value=="")
				{
					alert("Email Should not be blank");
					document.getElementById("email").focus();
					return false;
				}
				if(document.getElementById("email").value!="")
				{
					if(document.getElementById("email").value.indexOf('@') === -1)
					{
						alert("Not a valid Emaild");
						document.getElementById("email").value="";
						document.getElementById("email").focus();
						return false;
					}
				}				
            }
			showManageLoadingMessage();
            var poststr="action_type="+action_type+
                  "&account_id_local="+result+
                  "&vehicle_imei_name="+document.getElementById("vehicle_imei_name").value+
                  "&from_place="+document.getElementById("from_place").value+
                  "&to_place="+document.getElementById("to_place").value+ 
                   "&consignee_name="+document.getElementById("consignee_name").value+
                  "&start_date="+document.getElementById("date1").value+
                  "&end_date="+document.getElementById("date2").value+
				  "&email="+document.getElementById("email").value+
                  "&remark="+document.getElementById("remark").value;
                   //alert("poststr="+poststr);
              makePOSTRequest('src/php/action_manage_consignment_info.htm', poststr);
        }
    //alert("poststr="+poststr);      
}

function show_consignment_vehicle(account_id_this)
{		
		var poststr = "accountid_to_vehicle="+account_id_this;
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/consignment_vehicle_combo.htm', poststr);	

}
	
	
   
	function action_manage_device(obj,action_type)
	{
		//alert("action_type="+action_type);
		if(action_type == "add")
		{
			var obj=document.manage1.elements['io_name[]'];
			var result=device_checkbox_selection(obj);
			//alert("result="+result);	  
			var result_validaton = validate_manage_add_device(action_type); 			
			if(result_validaton!=false)
			{
				//alert("in if");
				showManageLoadingMessage();
				var poststr = "action_type=" + encodeURI( action_type )+
				"&imei_no=" + encodeURI( document.getElementById("imei_no").value ) +
				"&manufacturing_date=" + encodeURI( document.getElementById("manufacturing_date").value )+
				"&make=" + encodeURI( document.getElementById("make").value )+
				"&io_ids=" + encodeURI(result);
			}
		}
		else if(action_type == "assign")
		{			
			var result=accounts_for_device();
			if(result!=true)
			{
					showManageLoadingMessage();
					poststr="action_type=" + encodeURI( action_type )+
							"&account_string1="+result+
							"&device_imei_no1="+document.getElementById("common_id").value;
			}   
		} 
		if(action_type == "edit")
		{
			var obj=document.manage1.device_imei_no;			
			var device_imei_no=radio_selection(obj); 		
			if(device_imei_no!= false)
			{
				var obj1=document.manage1.elements['io_name[]'];
				var io_ids=device_checkbox_selection(obj1);	
				showManageLoadingMessage();
				var poststr = "action_type=" + encodeURI( action_type )+
							"&device_imei_no=" +device_imei_no+				
							"&io_ids=" +io_ids;
		
			}
		}		
		//alert("poststr="+poststr);
		showManageLoadingMessage();
		makePOSTRequest('src/php/action_manage_device.htm', poststr); 
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
		showManageLoadingMessage();
        makePOSTRequest('src/php/action_manage_device_sale.htm', poststr);
      }
   }
      
function show_substation_vehicles(obj, action_type)
{
	poststr = "local_account_id=" + encodeURI( document.getElementById("substation_user").value )+
	"&action_type=" + action_type;
	var file_name="src/php/manage_substation_vehicle_deassignment.htm";
	makePOSTRequest(file_name, poststr); 	
}

function show_transporter_vehicles(obj, action_type)
{
	//alert(action_type);
	var rawmilk_user=radio_selection(obj).split(",");
	
	document.getElementById('rawmilkplant_id').value=rawmilk_user[0];
	poststr = "local_account_id=" + encodeURI( rawmilk_user[0] )+
	"&action_type=" + action_type;
	var file_name="src/php/manage_substation_vehicle_deassignment.htm";
	//alert(poststr);
	makePOSTRequest(file_name, poststr); 	
}

function show_substation_plant(obj, action_type)
{
	poststr = "local_account_id=" + encodeURI( document.getElementById("plant_user").value )+
	"&action_type=" + action_type;
	var file_name="src/php/manage_substation_plant_deassignment.htm";
	makePOSTRequest(file_name, poststr); 	
}

function action_manage_vehicle_substation(obj, action_type)
{
	//alert("K");
	var poststr;
	if(action_type=="assign")
	{
		var device_str = get_selected_vehicle_substation(obj); 
		//alert(device_str);
		
		if(device_str!=false)
		{
			poststr = "vehicle_id=" + encodeURI( device_str ) +
					  "&substation_user=" + encodeURI( document.getElementById("substation_user").value )+  
						"&action_type=" + action_type;
					  //alert("Assign:"+poststr);  
		}		
		var file_name="src/php/action_manage_vehicle_substation.htm";						
	}
	else if(action_type=="deassign")
	{
		if(document.getElementById("substation_user").value=="0"){
		alert("Please select one Plant Account");
		return false;
		}
		var device_str = get_selected_vehicle_substation(obj); 
		//alert(device_str);
		
		if(device_str!=false)
		{
			poststr = "vehicle_id=" + encodeURI( device_str ) +
					  "&substation_user=" + encodeURI( document.getElementById("substation_user").value )+  
						"&action_type=" + action_type;
					  //alert("Deassign:"+poststr);  
		}		
		var file_name="src/php/action_manage_vehicle_substation.htm";						
	}
	//alert("poststr="+poststr);
	showManageLoadingMessage();
	makePOSTRequest(file_name, poststr); 		
}

function action_manage_vehicle_transporter(obj, action_type)
{
	//alert("K");
	var poststr;
	if(action_type=="assign")
	{
		var device_str = get_selected_vehicle_substation(obj); 
		var raw_milk_user=radio_selection(obj).split(",");

		//alert(device_str);
		
		if(device_str!=false)
		{
			poststr = "vehicle_id=" + encodeURI( device_str ) +
					  "&substation_user=" + encodeURI( raw_milk_user[0] )+  
						"&action_type=" + action_type;
					  //alert("Assign:"+poststr);  
		}		
		var file_name="src/php/action_manage_vehicle_substation.htm";						
	}
	else if(action_type=="deassign")
	{
		/*if(document.getElementById("substation_user").value=="0"){
		alert("Please select one Plant Account");
		return false;
		}*/
		var device_str = get_selected_vehicle_substation(obj); 
		//alert(device_str);
		var raw_milk_user=document.getElementById('rawmilkplant_id').value;
		if(raw_milk_user[0]==""){
		alert("Please select one Plant Account");
		return false;
		}
		if(device_str!=false)
		{
			poststr = "vehicle_id=" + encodeURI( device_str ) +
					  "&substation_user=" + encodeURI( raw_milk_user )+  
						"&action_type=" + action_type;
					  //alert("Deassign:"+poststr);  
		}		
		var file_name="src/php/action_manage_vehicle_substation.htm";						
	}
	//alert("poststr="+poststr);
	showManageLoadingMessage();
	makePOSTRequest(file_name, poststr); 		
}

function action_manage_default_chilling_plant(obj, action_type)
{
	//alert("K");
	var poststr;
	if(action_type=="assign")
	{
		var chillplant = document.getElementById("chillplant").value; 
		var raw_milk_user=radio_selection(obj).split(",");

		//alert(raw_milk_user.length);
		if(raw_milk_user.length==1){
			alert("Please Select Account");
			return false;
		}
		if(chillplant!="")
		{
			
			poststr = "chillplant=" + encodeURI( chillplant ) +
					  "&transporter=" + encodeURI( raw_milk_user[0] )+  
						"&action_type=" + action_type;
					  //alert("Assign:"+poststr);  
					  var file_name="src/php/action_manage_default_chilling_plant.htm";	
					  //alert("poststr="+poststr);
					showManageLoadingMessage();
					makePOSTRequest(file_name, poststr); 	
		}	
		else{
			alert("Please Select Chilling Plant");
			return false;
		}
							
	}
	else if(action_type=="deassign")
	{
		
		var raw_milk_user=radio_selection(obj).split(",");
		if(raw_milk_user[0]==""){
		alert("Please select one Account");
		return false;
		}
		else
		{
			poststr = "transporter=" + encodeURI( raw_milk_user[0] )+  
						"&action_type=" + action_type;
					  //alert("Deassign:"+poststr);  
		}		
		var file_name="src/php/action_manage_default_chilling_plant.htm";	
		//alert(poststr);
		showManageLoadingMessage();
		makePOSTRequest(file_name, poststr); 	
	}
		
}

function action_manage_plant_substation(obj, action_type)
{
	//alert("K");
	var poststr;
	if(action_type=="assign")
	{
		var plant_str = get_selected_plant_substation(obj); 
		//alert(device_str);
		
		if(plant_str!=false)
		{
			poststr = "plant_id=" + encodeURI( plant_str ) +
					  "&plant_user=" + encodeURI( document.getElementById("plant_user").value )+  
						"&action_type=" + action_type;
					  //alert("Assign:"+poststr);  
		}		
		var file_name="src/php/action_manage_plant_substation.htm";						
	}
	else if(action_type=="deassign")
	{
		if(document.getElementById("plant_user").value=="0"){
		alert("Please select one Plant Account");
		return false;
		}
		var plant_str = get_selected_plant_substation(obj); 
		//alert(device_str);
		
		if(plant_str!=false)
		{
			poststr = "plant_id=" + encodeURI( plant_str ) +
					  "&plant_user=" + encodeURI( document.getElementById("plant_user").value )+  
						"&action_type=" + action_type;
					  //alert("Deassign:"+poststr);  
		}		
		var file_name="src/php/action_manage_plant_substation.htm";						
	}
	//alert("poststr="+poststr);
	showManageLoadingMessage();
	makePOSTRequest(file_name, poststr); 		
}


	function get_selected_vehicle_substation(obj)
	{
		var flag=0;
		var i;
		var s = obj.elements['vehicle_id[]'];		
		var vehicle_str="";
		if(s.length!=undefined)
		{
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
					flag=1;       
				}  
			}	
		}
		else
		{
			if(s.checked)
			{
				vehicle_str = s.value;
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
			return vehicle_str;
		}	
	}
	
	function get_selected_plant_substation(obj)
	{
		var flag=0;
		var i;
		var s = obj.elements['plant_id[]'];		
		var plant_str="";
		if(s.length!=undefined)
		{
			for(i=0;i<s.length;i++)
			{             
				if(s[i].checked)
				{
					if(plant_str=="")
					{
						plant_str = s[i].value; 
					}
					else
					{
						plant_str = plant_str+":"+s[i].value;        
					} 
					flag=1;       
				}  
			}	
		}
		else
		{
			if(s.checked)
			{
				plant_str = s.value;
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
			return plant_str;
		}	
	}
	
	
function validate_substation_type_user(usertype)
{
	if(usertype=='substation'){
		var user_id=document.getElementById("login").value
		//alert(user_id);
		var poststr = "content=" +user_id+
		"&local_account_id="+document.getElementById("add_account_id").value;
		//alert(poststr);
		makePOSTRequest('src/php/validate_substation_type_user.htm', poststr);
	}
}

function unchecked_substation()
{
	//alert("hello");
	//document.getElementById("route_substation").checked=false;
	document.getElementById("sub_utype").value="0";
}
	
  function action_manage_vehicle(obj, action_type)
	{
	//	return false;		
		var action_type1=action_type.split(","); //////it is for add,edit vehicle only
		//alert("action_type1="+action_type1[0]+"action_type2="+action_type1[1]);
		if(action_type=="add,Person" || action_type=="add,Vehicle" || action_type=="edit_action,Person" || action_type=="edit_action,Vehicle" || action_type=="add_register,Person" || action_type=="add_register,Vehicle")
		{
			if(action_type1[1]!="Person")
			{var conditional_string="&max_speed=" + document.getElementById("max_speed").value +"&vehicle_tag=" + document.getElementById("vehicle_tag").value;}
			else
			{var conditional_string="";}
		}
		if(action_type=="add,Person" || action_type=="add,Vehicle")
		{	
					
			var form_obj=document.manage1.elements['manage_id[]'];		
			var result=checkbox_selection(form_obj);
			if(result!=false)
			{
				var veh_validation=vehicle_validation(action_type);
				if(veh_validation==false)
				{
					return false;
				}
				else
				{					
					var poststr = "action_type=" +action_type1[0]+ 
					"&account_ids=" +result+			
					"&vehicle_name=" + document.getElementById("vehicle_name").value+
					"&vehicle_number=" +document.getElementById("vehicle_number").value+conditional_string+	
					"&sim_number=" + document.getElementById("sim_number").value+
					"&mobile_number=" +document.getElementById("mobile_number").value+
                                        "&manufacturer_name=" +document.getElementById("manufacturer_name").value+
					"&vehicle_type=" +document.getElementById("vehicle_type").value+
					"&category=" +document.getElementById("category").value; 
				}
			}
			var file_name="src/php/action_manage_vehicle.htm";
		}    
		else if(action_type=="add_register,Vehicle" || action_type=="add_register,Person")
		{
			var form_obj=document.manage1.elements['manage_id[]'];
			var account_result=checkbox_selection(form_obj);
			//alert("account_result="+account_result);
			var obj=document.manage1.device_imei_no;			
			var dev_result=radio_selection(obj);
			//alert("dev_result="+dev_result);
			if(dev_result!=false)
			{
			close_vehicle_display_option('device_vehicle_assigment');			
			var poststr = "action_type=" +action_type1[0]+ 
					"&account_ids=" +account_result+
					"&device_imei_no="+dev_result+					
					"&vehicle_name=" + document.getElementById("vehicle_name").value+
					"&vehicle_number=" +document.getElementById("vehicle_number").value+conditional_string+						
					"&manufacturer_name=" +document.getElementById("manufacturer_name").value+
                                        "&vehicle_type=" +document.getElementById("vehicle_type").value+
                                        "&category=" +document.getElementById("category").value;	
          //alert("poststr="+poststr);   
			}
				var file_name="src/php/action_manage_vehicle.htm";		
			
		}
		else if(action_type=="edit_action,Person" || action_type=="edit_action,Vehicle")
		{
			var veh_validation=vehicle_validation(action_type);
			if(veh_validation==false)
			{
				return false;
			}
			else
			{
				var poststr = "action_type=" + encodeURI(action_type1[0]) +
                        "&vehicle_id=" + encodeURI( document.getElementById("vehicle_id").value ) +
                        "&vehicle_name=" + encodeURI( document.getElementById("vehicle_name").value ) +
                        "&vehicle_number=" + encodeURI( document.getElementById("vehicle_number").value )+conditional_string+
                        "&sim_number=" + document.getElementById("sim_number").value+
                        "&mobile_number=" +document.getElementById("mobile_number").value+
                        "&manufacturer_name=" +document.getElementById("manufacturer_name").value+
                        "&vehicle_type=" + encodeURI( document.getElementById("vehicle_type").value )+
                        "&category1=" + encodeURI( document.getElementById("category").value); 
            //alert("poststr="+poststr);
                        var file_name="src/php/action_manage_vehicle.htm";
			}
		}		
		else if(action_type=="edit" || action_type=="delete")
		{
			var result = validate_manage_vehicle(obj); 		
			if(result != false)
			{
				if(action_type=="edit")
				{
					var file_name="src/php/manage_edit_vehicle.htm";
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
						var file_name="src/php/action_manage_vehicle.htm";
					}
				}
				var poststr = "action_type="+action_type+
				              "&account_id_local1="+document.getElementById("account_id_local").value+
								      "&manage_vehicle_id="+result;	
        //alert("poststr="+poststr);	
				
			}
		}
		else if(action_type=="register")
		{
			var device_imei_no=document.getElementById("ls").value;
			var vehicle_name=document.getElementById("rs").value;
			var vehicle_display_option1=document.getElementById("vehicle_display_option").value;
			var options_value1=document.getElementById("options_value").value;
		
			
			if(device_imei_no=="")
			{
				alert("Please Enter device IMEI No");
				return false;
			}
			else if(vehicle_name=="")
			{
				alert("Please Enter Vehicle Name");
				return false;
			}			
				var poststr = "action_type="+action_type+
							"&local_account_id1="+document.getElementById("local_account_id").value+
							"&device_imei_no="+device_imei_no+
							"&vehicle_name="+vehicle_name+
							"&vehicle_display_option1="+vehicle_display_option1+
							"&options_value1="+options_value1;			
			var file_name="src/php/action_manage_vehicle.htm";		
		}	
		else if(action_type=="deregister")
		{
		
			var selected_values = get_selected_values(obj,"deregister");	
			var poststr = "device=" + encodeURI( selected_values )+
						  "&action_type=" + encodeURI( "deregister" );
			var file_name="src/php/action_manage_vehicle.htm";	
		}
		else if(action_type=="assign")
		{
			var result=accounts_for_device();		
			poststr="action_type=" + encodeURI( action_type )+
					"&account_string1="+result+
					"&vehicle_ids1="+document.getElementById("common_id").value;
			var file_name="src/php/action_manage_vehicle.htm";						
		}
		else if(action_type=="deassign")
		{
				var selected_values = get_selected_values(obj,"deassign");    			
				var poststr = "vehicle_ids1=" +selected_values+
								"&action_type=" +"deassign"                  
				var file_name="src/php/action_manage_vehicle.htm";		
		}	
		//alert("poststr="+poststr);
		showManageLoadingMessage();
		makePOSTRequest(file_name, poststr);  
	} 	
	function vehicle_validation(action_type)
	{
		var action_type1=action_type.split(",");
		//alert("action_type="+action_type1[1]);
		var vehicle_name=document.getElementById('vehicle_name').value;
		if(vehicle_name=="")
		{
			alert("Vehicle Name field can not be Empty!");
			document.getElementById('vehicle_name').focus();
			return false;
		}
		
		if(action_type1[1]=="Vehicle")
		{
			var vehicle_number=document.getElementById('vehicle_number').value;
			if(vehicle_number=="")
			{
				alert("Vehicle Number field can not be Empty!");
				document.getElementById('vehicle_number').focus();
				return false;
			}
		}
		
		if(document.getElementById('sim_account_id').value=='4')
		{		
			var sim_number=document.getElementById('sim_number').value;
			if((isNaN(sim_number)) || (sim_number==""))
			{
				alert("Enter valid Sim Number!");
				document.getElementById('sim_number').focus();
				return false;
			}
		
			var mobile_number=document.getElementById('mobile_number').value;
			if((isNaN(mobile_number)) || (mobile_number==""))
			{
				alert("Enter valid Mobile Number!");
				document.getElementById('mobile_number').focus();
				return false;
			}
		}
		var vehicle_type=document.getElementById('vehicle_type').value;

		if(vehicle_type=="select")
		{
			alert("Select Vehicle Type option!");
			document.getElementById('vehicle_type').focus();
			return false;
		}
		var category1=document.getElementById('category').value;
		if(category1=="select")
		{
			alert("Select Category option!");
			document.getElementById('category').focus();
			return false;
		}		
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
          var remark = document.getElementById("remark").value;
          if(remark =="")
            remark ="-";
          var poststr = "action_type=" + encodeURI( action_type ) +
						"&manage_account_id=" + encodeURI(res) +
                        "&group_name=" + encodeURI( document.getElementById("group_name").value ) +
                        "&remark=" + encodeURI( remark );                        
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
		showManageLoadingMessage();
        makePOSTRequest('src/php/action_manage_group.htm', poststr);
      }
   }
   
	function showManageLoadingMessage()
	{
		document.getElementById("loadingBlackout").style.visibility = "visible";
		document.getElementById("loadingDivPopUp").style.visibility = "visible";
		document.getElementById("loadingBlackout").style.display = "block";
		document.getElementById("loadingDivPopUp").style.display = "block"; 
	}
   
   function action_manage_load_cell(action_type)
   {  
  		var obj=document.manage1.elements['manage_id[]'];  		
      var result=checkbox_selection(obj);         
             
      if(result)
      { 
        if(action_type == "add")
        {
          var remark = document.getElementById("remark").value;
          if(remark =="")
            remark ="-";
            var poststr = "action_type=" + encodeURI( action_type ) +
						"&local_account_ids=" + encodeURI(result) +                        
                        "&date=" + encodeURI( document.getElementById("date1").value ) +
                        "&load_status1=" + encodeURI( document.getElementById("load_status1").value ) +
                        "&location=" + encodeURI( document.getElementById("location").value ) +
                        "&load=" + encodeURI( document.getElementById("load").value ) +
                        "&load_status2=" + encodeURI( document.getElementById("load_status2").value ) +                                                
                        "&imei=" + encodeURI( document.getElementById("imei").value ) +                        
                        "&remark=" + encodeURI( remark );                                                
        }
        else if(action_type == "edit")
        {
          var poststr = "action_type=" + encodeURI( action_type ) +
                        "&load_cell_id_local=" + encodeURI( document.getElementById("load_cell_id_local").value ) +
						"&local_account_ids=" + encodeURI(document.getElementById("edit_account_id").value ) +
                        "&date=" + encodeURI( document.getElementById("date1").value ) +
                        "&imei=" + encodeURI( document.getElementById("imei").value ) +
                        "&load=" + encodeURI( document.getElementById("load").value ) +
                        "&remark=" + encodeURI( document.getElementById("remark").value );
                         //alert("Shams:poststr="+poststr);      
        }
		  else if(action_type == "delete")
        {
          var poststr =  "action_type=" + encodeURI( action_type ) +
						"&load_cell_id_local=" + encodeURI( document.getElementById("load_cell_id_local").value );
                         //alert("Shams:poststr="+poststr);      
        }
		//alert("Shams:poststr="+poststr); 
		showManageLoadingMessage();
        makePOSTRequest('src/php/action_manage_load_cell.htm', poststr);
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
	function manage_availability(field_value, file_type)
	{
		//alert("in function");
		if(field_value!="")
		{	
			if(file_type=='device_assignment' || file_type=='vehicle_assignment')
			{
				var poststr ="field_value=" +encodeURI(field_value)+
				"&local_account_id1="+document.getElementById('local_account_id').value+
				"&file_type=" + encodeURI(file_type);   
			}
			else if(file_type=='vendor_type')
			{
				var poststr ="field_value=" +encodeURI(field_value)+
				"&vendor_group_id1="+document.getElementById('vendor_group_id').value+
				"&file_type=" + encodeURI(file_type);
			}
			else if(file_type=="expense_type")
			{
				var poststr ="field_value=" +encodeURI(field_value)+
				"&expense_group_id1="+document.getElementById('expense_group_id').value+
				"&file_type=" + encodeURI(file_type);
			}
			else if(file_type=="call_type")
			{
				var poststr ="field_value=" +encodeURI(field_value)+
				"&call_group_id1="+document.getElementById('call_group_id').value+
				"&file_type=" + encodeURI(file_type);
			}
			else
			{			
				var poststr ="field_value=" +encodeURI(field_value)+
                                 //"&local_account_id1="+document.getElementById('local_account_ids').value+
				 "&file_type=" + encodeURI(file_type);
			}
			//alert("poststr="+poststr);
			makePOSTRequest('src/php/manage_availability.htm', poststr);
		}
		else
		{
			document.getElementById("available_message").innerHTML="";
		}
	}
        
        function manage_availability_exclude(field_value, file_type)
	{
            if(field_value!="")
		{
                    var polyline_id=document.getElementById("polyline_id").value;
                    //alert(polyline_id);
                    var poststr ="field_value=" +encodeURI(field_value)+
                                  "&actual_id=" +encodeURI(polyline_id)+
				 "&file_type=" + encodeURI(file_type);
                        makePOSTRequest('src/php/manage_availability.htm', poststr);
                }
                else
		{
			document.getElementById("available_message").innerHTML="";
		}
            
        }
	
	function show_date_option(date_format_option)
  {
	//alert("hello"+date_format_option);
	if(date_format_option==true)
	{
		document.getElementById("day_tr").style.display="";
	}
	else 
	{
		document.getElementById("day_tr").style.display="none";
	}
  
  }
  
  function addRow_schedule(tableID) {

			var table = document.getElementById(tableID);

			var rowCount = table.rows.length;
			var row = table.insertRow(rowCount);

			var colCount = table.rows[0].cells.length;

			for(var i=0; i<colCount; i++) {

				var newcell	= row.insertCell(i);
				newcell.innerHTML = table.rows[0].cells[i].innerHTML;
				//alert(newcell.childNodes);
				switch(newcell.childNodes[0].type) {				
					case "select-one":
							newcell.childNodes[0].selectedIndex = 0;
							break;
				}
			}
		}

		function deleteRow(tableID) 
		{
			try 
			{
				var tbl = document.getElementById('dataTable');
				var lastRow = tbl.rows.length;
				//alert("lastRow="+lastRow);
				
				if(lastRow<=1)
				{
					alert("Can Not Delete All rows");
				}
				else
				{
					tbl.deleteRow(lastRow - 1);
				}				
			}
			catch(e) 
			{
				alert(e);
			}
		}
	function show_value(tableID)
	{
		var table = document.getElementById(tableID);
			var rowCount = table.rows.length;
			//var row = table.insertRow(rowCount);
			var colCount = table.rows[0].cells.length;
			
	
			for(var i=0; i<rowCount; i++)
			{		
				alert("a="+table.rows[i].cells[0].getElementsByTagName('select')[0].value);			
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
  function get_selected_values(obj, input_name)
  { 
	//alert("input_name="+input_name);
      if(input_name=="deregister")
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
      
      if(input_name=="deassign")
      {
        var selected_values="";
        with (obj) 
        {
          for (var i=0;i<vehicle_ids.length;i++) 
          {
            if (vehicle_ids.options[i].selected) 
            {
              if (selected_values=="")
                //selected_values = v_group_str.options[i].text
                selected_values = vehicle_ids.options[i].value
              else
                //selected_values = selected_values + "," + v_group_str.options[i].text
                selected_values = selected_values + "," + vehicle_ids.options[i].value
            }
          }
        //alert("selected values are:\n" + selectedvalue);     
        }
      }      
       return selected_values;
   }        
 
   
  
          
   /////////////// xxx  
   
 function manage_add_geofence() 
 {
   makePOSTRequest1('src/php/manage_add_geofence.htm', '');
 }
   
   
 function action_manage_sector(action_type)
 {  
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
	 
		  var add_sector_name=document.getElementById("add_sector_name").value; 
		  var sector_coord=document.getElementById("sector_coord").value;
		  if(add_sector_name=="") 
		  {
			alert("Please Enter Sector Name"); 
			document.getElementById("add_sector_name").focus();
			return false;
		  }
		  else if(sector_coord=="") 
		  {
			alert("Please Draw Sector");
			document.getElementById("sector_coord").focus();
			return false;
		  }
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&sector_name="+encodeURI(add_sector_name) +
						"&sector_coord="+encodeURI(sector_coord); 
		}
    }
    else if(action_type=="edit")
    {		
		var sector_id1=document.getElementById("sector_id").value; 
		if(sector_id1=="select")
		{
			alert("Please Select Sector"); 
			document.getElementById("sector_id").focus();
			return false;
		}       
		var sector_name=document.getElementById("sector_name").value; 
		var sector_coord=document.getElementById("sector_coord").value;
		if(sector_name=="") 
		{
			alert("Please Enter Sector Name"); 
			document.getElementById("sector_name").focus();
			return false;
		}
		else if(sector_coord=="") 
		{
			alert("Please Draw Sector");
			document.getElementById("sector_coord").focus();
			return false;
		}
		var poststr ="action_type="+encodeURI(action_type ) + 
		"&local_account_ids="+encodeURI(result) +
		"&sector_id="+encodeURI(sector_id1) +
		"&sector_name="+encodeURI(document.getElementById("sector_name").value ) +
		"&sector_coord="+encodeURI(document.getElementById("sector_coord").value); 

    }
    else if(action_type=="delete")
    {
      var sector_id1=document.getElementById("sector_id").value;
      if(sector_id1=="select")
      {
        alert("Please Select Sector"); 
        document.getElementById("sector_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete This Sector";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&sector_id=" + encodeURI(sector_id1);
    }
	else if(action_type=="assign")
	{
		var form_obj1 = document.manage1.elements['escalation_id[]'];
    //var radio_result1 = radio_selection(form_obj1);	
    
    var form_obj2 = document.manage1.elements['alert_id[]'];
    var form_obj3 = document.manage1.elements['duration[]'];
    //var checkbox_result=checkbox_selection(form_obj2); /////////validate and get vehicleids
    
		var form_obj4 = document.manage1.elements['sector_id'];
    var radio_result4 = radio_selection(form_obj4);
    var escalation_result="";
    var cnt1=0;
    var flag1=0;
    
    if(form_obj1.length!=undefined)       //  SELECT ALERT
  	{
  	  for (var i=0;i<form_obj1.length;i++)
  		{
  			if(form_obj1[i].checked==true)
  			{
          if(cnt1==0)
  				{
  					escalation_result = escalation_result + form_obj1[i].value;  					
  					cnt1=1;
  				}
  				else
  				{
  					escalation_result = escalation_result +","+ form_obj1[i].value;
  				}
          flag1 = 1;		  	  			 
        }  		
  		}
    }    
    else
  	{
  	  if(form_obj1.checked==true)
  		{
  		  escalation_result = form_obj1.value;
        flag1 = 1;
  		}
  	}
  		
  	if(flag1==0)
  	{
  		alert("Please Select Atleast One Person");
  		return false;
  	}
    
    //********** SELECT ALERT
    var cnt2=0;
  	var duration;
  	var alertid_result="";
  	var duration_result="";
  	var flag2=0;
  	
    if(form_obj2.length!=undefined)       
  	{
  		for (var i=0;i<form_obj2.length;i++)
  		{
  			if(form_obj2[i].checked==true)
  			{				
          if(form_obj3[i].value == "")
          {
            alert("Please fill the duration field");
            return false; 
          }
          //alert("dur1:"+form_obj3[i].value);
          if(form_obj3[i].value >=0 && form_obj3[i].value <=1440)
          {
            duration = form_obj3[i].value; 
          }        			
          else
          {
            alert("Please enter valid duration in minutes(upto 24 hrs)");
            return false;
          }
            				
          if(cnt2==0)
  				{
  					alertid_result = alertid_result + form_obj2[i].value;
  					duration_result = duration_result + duration;
  					cnt2=1;
  				}
  				else
  				{
  					alertid_result = alertid_result +","+ form_obj2[i].value;
  					duration_result = duration_result + ","+duration;
  				}		  				
  				flag2=1;
  			}	  
  		}
  	}
  	else
  	{
  		if(form_obj2.checked==true)
  		{
        //alert("dur1:"+form_obj3[0].value);
        if(form_obj3[0].value == "")
        {
          alert("Please fill the duration field");
          return false; 
        }
        if(form_obj3[0].value >=0 && form_obj3[0].value <=1440)
        {
          var duration = form_obj3[0].value; 
        }        			
        else
        {
          alert("Please enter valid duration in minutes(upto 24 hrs)");
          return false;
        }
        
        alertid_result = form_obj2.value;
        duration_result = duration;
  			flag2=1;  			
  		}
  	}
  	if(flag2==0)
  	{
  		alert("Please Select Atleast One Option");
  		return false;
  	}
  	/*else
  	{	  
  		return id;
  	}*/
      		
    if(alertid_result!="" && escalation_result!="" && radio_result4!=false)
		{
			var poststr="action_type="+encodeURI(action_type) +
                  "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&escalation_id="+escalation_result +
                  "&alert_ids="+alertid_result + 
                  "&sector_id=" +radio_result4 +
                  "&duration=" +duration_result;					
		}			
	}
	
	else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['escalation_serial_number[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) +
          "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					"&escalation_serial_number="+checkbox_result;							
		}
   }			
	 //alert("poststr="+poststr);
	 showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_sector.htm', poststr);
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
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_geofence.htm', poststr);
 }

function action_manage_polyline(action_type)
 {  
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
	 
		  var add_polyline_name=document.getElementById("add_polyline_name").value; 
		  var polyline_coord=document.getElementById("polyline_coord").value;
		  if(add_polyline_name=="") 
		  {
			alert("Please Enter Route/Polyline Name"); 
			document.getElementById("add_polyline_name").focus();
			return false;
		  }
		  else if(polyline_coord=="") 
		  {
			alert("Please Draw Polyline/Route");
			document.getElementById("polyline_coord").focus();
			return false;
		  }
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&polyline_name="+encodeURI(add_polyline_name) +
						"&polyline_coord="+encodeURI(polyline_coord); 
		}
    }
    else if(action_type=="edit")
    {		
		var polyline_id1=document.getElementById("polyline_id").value; 
		if(polyline_id1=="select")
		{
			alert("Please Select Polyline/Route Name"); 
			document.getElementById("polyline_id").focus();
			return false;
		}       
		var polyline_name=document.getElementById("polyline_name").value; 
		var polyline_coord=document.getElementById("polyline_coord").value;
		if(polyline_name=="") 
		{
			alert("Please Enter Polyline/Route Name"); 
			document.getElementById("polyline_name").focus();
			return false;
		}
		else if(polyline_coord=="") 
		{
			alert("Please Draw Polyline/Route");
			document.getElementById("polyline_coord").focus();
			return false;
		}
		var poststr ="action_type="+encodeURI(action_type ) + 
		"&local_account_ids="+encodeURI(result) +
		"&polyline_id="+encodeURI(polyline_id1) +
		"&polyline_name="+encodeURI(document.getElementById("polyline_name").value ) +
		"&polyline_coord="+encodeURI(document.getElementById("polyline_coord").value); 

    }
    else if(action_type=="delete")
    {
      var polyline_id1=document.getElementById("polyline_id").value;
      if(polyline_id1=="select")
      {
        alert("Please Select Polyline"); 
        document.getElementById("polyline_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Close Without Saving or Drawing";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&polyline_id=" + encodeURI(polyline_id1);
    }
	else if(action_type=="assign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{
                    /*
			var form_obj1=document.manage1.polyline_id;
			var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			if(radio_result!=false)
			{
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result + 
                    "&polyline_id=" +radio_result;
			}*/
                       var  polyline_id = document.getElementById('polyline_id').value;
                       if(polyline_id!="0")
                       {
                           var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result + 
                        "&polyline_id=" +polyline_id;
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
        else if(action_type=="register")
	{
            //alert('test');
            var obj=document.manage1.elements['manage_id'];
            //alert('test'+obj);
            var result=radio_selection(obj);
            //alert("result"+result);
            
            if(result!=false)
            {
               
                var  polyline_id = document.getElementById('polyline_id').value;
                if(polyline_id!="0")
                {
                    var poststr="action_type="+encodeURI(action_type ) + 
                                 "&account_id_to="+encodeURI(result) + 
                                 "&polyline_id=" +polyline_id;
                }
            }
            			
	}
	//alert("poststr="+poststr);
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_polyline.htm', poststr);
 }
/// VISIT AREA  ///
 function action_manage_visit_area(action_type)
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
			alert("Please Enter Visit Area Name"); 
			document.getElementById("add_geo_name").focus();
			return false;
		  }
		  else if(geo_coord=="") 
		  {
			alert("Please Draw Visit Area");
			document.getElementById("geo_coord").focus();
			return false;
		  }
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&visit_area_name="+encodeURI(add_geo_name) +
						"&visit_area_coord="+encodeURI(geo_coord); 
		}
    }
    else if(action_type=="edit")
    {		
		var geo_id1=document.getElementById("geo_id").value; 
		if(geo_id1=="select")
		{
			alert("Please Select Visit Area"); 
			document.getElementById("geo_id").focus();
			return false;
		}       
		var geo_name=document.getElementById("geo_name").value; 
		var geo_coord=document.getElementById("geo_coord").value;
		if(geo_name=="") 
		{
			alert("Please Enter Visit Area Name"); 
			document.getElementById("geo_name").focus();
			return false;
		}
		else if(geo_coord=="") 
		{
			alert("Please Draw Visit Area");
			document.getElementById("geo_coord").focus();
			return false;
		}
		var poststr ="action_type="+encodeURI(action_type ) + 
		"&local_account_ids="+encodeURI(result) +
		"&visit_area_id="+encodeURI(geo_id1) +
		"&visit_area_name="+encodeURI(document.getElementById("geo_name").value ) +
		"&visit_area_coord="+encodeURI(document.getElementById("geo_coord").value); 

    }
    else if(action_type=="delete")
    {
      var geo_id1=document.getElementById("geo_id").value;
      if(geo_id1=="select")
      {
        alert("Please Select Visit Area"); 
        document.getElementById("geo_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Close Without Saving or Drawing";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&visit_area_id=" + encodeURI(geo_id1);
    }
  	else if(action_type=="assign")
  	{
  		//alert("In assign");
      var form_obj=document.manage1.elements['visit_area_id[]'];
  		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
  		
  		if(checkbox_result!=false)
  		{
  			var form_obj1=document.manage1.vehicle_id;
  			var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			var schedule_date = document.getElementById("schedule_date").value;
        
  			if(radio_result!=false)
  			{
  			var poststr="action_type="+encodeURI(action_type ) + 
  					"&vehicle_id="+radio_result + 
                      "&visit_area_ids=" +checkbox_result +
                      "&schedule_date=" +schedule_date;
  			//alert(poststr);
			}					
  		}			
  	}
  	else if(action_type=="deassign")
  	{
  		var form_obj=document.manage1.elements['vid_string[]'];
  		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
      			
  		if(checkbox_result!=false)
  		{		
  			var poststr="action_type="+encodeURI(action_type ) + 
  					"&vid_string="+checkbox_result;					// vehicle id also includes station ids with : separator	
  		}			
  	}
	  //alert("poststr="+poststr);
  showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_visit_area.htm', poststr);
 }
 
////////////////// 

 
 function action_manage_station_upload(action_type)
 {
    if(action_type=="add")  
    {
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
  		//alert("result"+result);
  		if(result!=false)
  		{ 
      	 document.getElementById('file_upload_form').action_type.value = action_type;
      	 document.getElementById('file_upload_form').local_account_ids.value = result;
      	                        
         document.getElementById('file_upload_form').onsubmit=function() {
      	 document.getElementById('file_upload_form').target = '_blank'; //'upload_target' is the name of the iframe
      	 }
         
         document.getElementById('file_upload_form').submit();              
      }
    }
 }
 
 function action_manage_invoice_upload(action_type)
 {
    //alert("Type="+action_type);
	if(action_type=="add")  
    {
  		//var obj=document.manage1.elements['manage_id[]'];		
		//var form_obj1=document.manage1.vehicle_id;
		//var radio_result=radio_selection(form_obj1);  //////////validate and get geofence		
		var obj1=document.manage1.manage_id;
		//alert("obj="+obj1);
		var result=radio_selection(obj1);		
  		
  		//alert("result"+result);
  		if(result!=false)
  		{ 
			 document.getElementById('file_upload_form').action_type.value = action_type;
			 document.getElementById('file_upload_form').local_account_id.value = result;
									
			 document.getElementById('file_upload_form').onsubmit=function() {
			 document.getElementById('file_upload_form').target = '_blank'; //'upload_target' is the name of the iframe
      	 }
         
         document.getElementById('file_upload_form').submit();              
      }
    }
 } 

 function action_manage_station(action_type)
 {  
    //alert("action_type="+action_type);    
    if(action_type=="add")  
    {
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
  		//alert("result"+result);
  		if(result!=false)
  		{
  		var add_station_no=document.getElementById("add_station_no").value;   	    
          var add_station_name=document.getElementById("add_station_name").value; 
    		  var station_coord=document.getElementById("landmark_point").value;
          var file_type=document.getElementById("file_type").value;    		  
    		  

                  if(add_station_no=="")
                  {
                        alert("Please Enter Station Number");
                        document.getElementById("add_station_no").focus();
                        return false;
                  }
 

    		  if(add_station_name=="") 
    		  {
      			alert("Please Enter Station Name"); 
      			document.getElementById("add_station_name").focus();
      			return false;
    		  }
    		  if(station_coord=="") 
    		  {
      			alert("Please Add Station");
      			document.getElementById("landmark_point").focus();
      			return false;
    		  }    		  
	        var poststr = "action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                                        "&station_no="+encodeURI(add_station_no) +
					"&station_name="+encodeURI(add_station_name) +
					"&station_coord="+encodeURI(station_coord) +
					"&file_type="+file_type;
		//	alert(poststr);
  		}
    }
    else if(action_type=="edit")
    {    
  		var station_id1=document.getElementById("station_id").value; 
  		if(station_id1=="select")
  		{
  			alert("Please Select Station"); 
  			document.getElementById("station_id").focus();
  			return false;
  		}       
  		var station_name=document.getElementById("station_name").value;
      var customer_no=document.getElementById("customer_no").value; 
  		var station_coord=document.getElementById("landmark_point").value;
  		var distance_variable=document.getElementById("distance_variable").value; 
  		
      if(station_name=="") 
  		{
  			alert("Please Enter Station Name"); 
  			document.getElementById("station_name").focus();
  			return false;
  		}
  		if(customer_no=="") 
  		{
  			alert("Please Add Customer No");
  			document.getElementById("customer_no").focus();
  			return false;
  		}  		
  		if(station_coord=="") 
  		{
  			alert("Please Add Station");
  			document.getElementById("landmark_point").focus();
  			return false;
  		}
		  if(distance_variable=="") 
		  {
  			alert("Please Enter Distance variable"); 
  			document.getElementById("distance_variable").focus();
  			return false;
		  }  		
  		var poststr ="action_type="+encodeURI(action_type ) + 
  		"&station_id="+station_id1 +
  		"&station_name="+station_name +
  		"&customer_no="+customer_no +
  		"&station_coord="+station_coord +
  		"&distance_variable="+distance_variable;
    }
    else if(action_type=="edit_dist_var")
    {  		
      var form_obj=document.manage1.elements['station_id2[]'];      
	   	var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		      
      var dist_var = document.getElementById('distance_variable').value;
          
      if(checkbox_result==false)
      {
        alert("Please Select One Station");
        return false;
      }      
      if( (dist_var == "") || (isNaN(dist_var)) )
      {
        alert("Please Enter valid Distance variable");
        return false;
      }
      
      var poststr = "action_type="+encodeURI(action_type ) +                     
                    "&station_ids=" + encodeURI(checkbox_result)+"&distance_variable="+dist_var;
    }    
    else if(action_type=="delete")
    {
  		var form_obj=document.manage1.elements['station_id[]'];
	   	var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
      
      if(station_id1=="select")
      {
        alert("Please Select Station"); 
        document.getElementById("station_id1").focus();
        return false;
      }    
      var poststr = "action_type="+encodeURI(action_type ) +                     
                    "&station_ids=" + encodeURI(checkbox_result);
    }
	else if(action_type=="assign")
	{
		var form_obj=document.manage1.elements['station_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{
			var form_obj1=document.manage1.vehicle_id;
			var radio_result=radio_selection(form_obj1);  //////////validate and get geofence

			if(radio_result!=false)
			{
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_id="+radio_result + 
                    "&station_ids=" +checkbox_result;
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
					"&vehicle_ids="+checkbox_result;					// vehicle id also includes station ids with : separator	
		}			
	}
	  //alert("poststr="+poststr);
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_station.htm', poststr);
 }


 function action_manage_invoice(action_type)
 {  
    //alert("action_type="+action_type);
    if(action_type=="edit")
    { 
		//alert(action_type);
		//var obj1=document.manage1.manage_id;
		var form_obj=document.invoice_form.elements['invoice_serial[]'];
		//alert("formobj="+form_obj);
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
    	//alert(checkbox_result);		
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) + 
					"&invoice_serials="+checkbox_result;					// vehicle id also includes station ids with : separator	
		
			showManageLoadingMessage();
		        makePOSTRequest('src/php/action_manage_invoice_upload.htm', poststr);
		}			
    }    
	//alert("poststr="+poststr);
	//showManageLoadingMessage();
	//makePOSTRequest('src/php/action_manage_invoice_upload.htm', poststr);
 }
 
 function show_route_vehicle(radio_value)
 {  
	document.getElementById('route_vehicle_div').innerHTML = '';
	document.getElementById('route_vehicle_div').innerHTML = '<center>Loading..</center>';
	var shift;
	var shift_obj = document.form1.shift;
	for(var i=0;i<shift_obj.length;i++)
	{       			
		if(shift_obj[i].checked == true)
		{
			//alert("Shift:"+shift_obj[i].value);
			shift = shift_obj[i].value; 
		}  
	} 

	poststr= "common_id="+encodeURI(document.getElementById("account_id_hidden").value) +
			"&shift="+shift;
	//alert("poststr="+poststr);
    makePOSTRequest('src/php/manage_route_vehicle_assignment1.htm', poststr);
 }
 
function display_1(word)
{
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
	document.getElementById(veh_id+'text_content').value = vehicle_name;
	document.getElementById(veh_id+'box').style.display = 'none';
	document.getElementById(veh_id+'text_content').focus();
}
 


function display_2(word)
{
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
	document.getElementById(veh_id+'text_content2').value = vehicle_name;
	//document.getElementById('device_imei_no').value = device_imei_no;
	document.getElementById(veh_id+'box2').style.display = 'none';
	document.getElementById(veh_id+'text_content2').focus();
}

function display_substation_1(word)
{
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
//	alert(veh_id+ ","+vehicle_name);
	document.getElementById(veh_id+'text_content1').value = vehicle_name;
	document.getElementById(veh_id+'box').style.display = 'none';
	document.getElementById(veh_id+'text_content1').focus();
}
 


function display_substation_2(word)
{
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
	document.getElementById(veh_id+'text_content2').value = vehicle_name;
	//document.getElementById('device_imei_no').value = device_imei_no;
	document.getElementById(veh_id+'box2').style.display = 'none';
	document.getElementById(veh_id+'text_content2').focus();
}


 
  function show_station_mode(value) 
  {    
    if(value==1)
    {
      document.getElementById("automatic").style.display="";
      document.getElementById("manual").style.display="none";
    }
    else if(value ==2)
    {
      document.getElementById("manual").style.display="";
      document.getElementById("automatic").style.display="none";
    }
  } 
  
  function show_invoice_upload_div() 
  {    
      document.getElementById("automatic").style.display="";
  }   
 
  function show_station(value)
  {
		var account_id_local = document.getElementById('account_id_local').value;
		
    if(value == "select")
		{
		  alert("Please select one Station type");
    }
    else
    {
      var poststr="station_type="+value+"&account_id_local="+account_id_local;
      //alert("POST="+poststr);    
      makePOSTRequest('src/php/manage_edit_distance_variable.htm', poststr);
    }
  }
   
  function action_manage_calibration(action_type)
  {  
    if(action_type=="add")  
    {
      var obj=document.manage1.elements['manage_id[]'];
      var result=checkbox_selection(obj);
      //alert("result"+result);
      if(result!=false)
      {   	           
        var calibration_name=document.getElementById("calibration_name").value; 
        var calibration_data=document.getElementById("calibration_data").value;
        if(calibration_name=="") 
        {
          alert("Please Enter Calibration Name"); 
          document.getElementById("calibration_name").focus();
          return false;
        }
        else if(calibration_data=="") 
        {
          alert("Please Draw Calibration");
          document.getElementById("calibration_data").focus();
          return false;
        }
	
        var poststr = "action_type="+encodeURI(action_type ) + 
        "&local_account_ids="+encodeURI(result) +
        "&calibration_name="+encodeURI(calibration_name) +
        "&calibration_data="+encodeURI(calibration_data); 
      }
    }
    else if(action_type=="edit")
    {		
		var calibration_id1=document.getElementById("calibration_id").value; 
		if(calibration_id1=="select")
		{
			alert("Please Select Calibration"); 
			document.getElementById("calibration_id").focus();
			return false;
		}       
		var calibration_name=document.getElementById("calibration_name").value; 
		var calibration_data=document.getElementById("calibration_data").value;
		if(calibration_name=="") 
		{
			alert("Please Enter Calibration Name"); 
			document.getElementById("calibration_name").focus();
			return false;
		}
		else if(calibration_data=="") 
		{
			alert("Please Draw Calibration");
			document.getElementById("calibration_data").focus();
			return false;
		}
		
		var poststr ="action_type="+encodeURI(action_type) + 
		"&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+
		"&calibration_id="+encodeURI(calibration_id1) +
		"&calibration_name="+encodeURI(document.getElementById("calibration_name").value ) +
		"&calibration_data="+encodeURI(document.getElementById("calibration_data").value); 

    }
    else if(action_type=="delete")
    {
      var calibration_id1=document.getElementById("calibration_id").value;
      if(calibration_id1=="select")
      {
        alert("Please Select Geofences"); 
        document.getElementById("calibration_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Close Without Saving or Drawing";
      if(!confirm(txt))
      {
       return false; 
      }
	
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
                    "&calibration_id=" + encodeURI(calibration_id1);
    }
  else if(action_type=="assign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{
			var form_obj1=document.manage1.calibration_id;
			var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			if(radio_result!=false)
			{
			
			var poststr="action_type="+encodeURI(action_type ) +
                  "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&vehicle_ids="+checkbox_result + 
                  "&calibration_id=" +radio_result;
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
          "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					"&vehicle_ids="+checkbox_result;							
		}			
	}     
	  //alert("poststr="+poststr);
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_calibration.htm', poststr);
 }
 
 ///////////// VENDOR ////////////////
    function show_vendor_type_name(obj)
 {
    var group_id=document.getElementById("vendor_group_id").value;
    var vendor_type_id=document.getElementById("vendor_type_id").value;
	//alert("vendor_type="+vendor_type_id);
    if(group_id=="select")
    {
		remOption(document.getElementById("vendor_type_id"));
		addOption(document.getElementById("vendor_type_id"),'Select','select');	
		alert("Please Select Group"); 
		document.getElementById("vendor_group_id").focus();          
		return false; 
    }
    if(vendor_type_id=="select")
    {     
      alert("Please Select Vendor Type"); 
      document.getElementById("vendor_type_id").focus();          
      return false; 
    }	
    else
    {
		var poststr = "get_name_by_type_id="+vendor_type_id;
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr);
    }
 }

function show_vendor_tree_or_salesperson(obj,action_type)
	{
		var vendor_type_id=document.getElementById("vendor_type_id").value;	
		if(vendor_type_id=="select")
		{     
			alert("Please Select Vendor Type"); 
			document.getElementById("vendor_type_id").focus();          
			return false; 
		}
		else
		{	
			var poststr = "vendor_type_id="+vendor_type_id+
						   "&action_type="+action_type;
			//alert("poststr1="+poststr);
			makePOSTRequest('src/php/manage_ajax.htm', poststr);	
		}
	}
	
	function show_invoice_ref_id(obj)
	{
		var vendor_type_id=document.getElementById("vendor_type_id").value;	
		if(vendor_type_id=="select")
		{     
			alert("Please Select Vendor Type"); 
			document.getElementById("vendor_type_id").focus();          
			return false; 
		}
		else
		{	
			var poststr = "vendortypeid_to_invoicerefid="+vendor_type_id;
			//alert("poststr1="+poststr);
			makePOSTRequest('src/php/manage_ajax.htm', poststr);	
		}
	}
	
	function show_finalise_ref_id(obj)
	{
		var vendor_type_id=document.getElementById("vendor_type_id").value;	
		if(vendor_type_id=="select")
		{     
			alert("Please Select Vendor Type"); 
			document.getElementById("vendor_type_id").focus();          
			return false; 
		}
		else
		{	
			var poststr = "vendortypeid_to_finaliserefid="+vendor_type_id;
			//alert("poststr1="+poststr);
			makePOSTRequest('src/php/manage_ajax.htm', poststr);	
		}
	}
	
	
	function show_invoicerefid_vendorids(value)
	{
		//var vendor_type_id=document.getElementById("manage_id").value;	
		if(value=="select")
		{     
			alert("Please Select Vendor ID"); 
			document.getElementById("manage_id").focus();          
			return false; 
		}
		else
		{	
			var poststr = "invoice_oredered_vendorids="+value;
			//alert("poststr1="+poststr);
			makePOSTRequest('src/php/manage_ajax.htm', poststr);	
		}
	}
	function show_finaliserefid_vendorids(value)
	{
		//var vendor_type_id=document.getElementById("manage_id").value;	
		if(value=="select")
		{     
			alert("Please Select Vendor ID"); 
			document.getElementById("manage_id").focus();          
			return false; 
		}
		else
		{	
			var poststr = "finalise_oredered_vendorids="+value;
			//alert("poststr1="+poststr);
			makePOSTRequest('src/php/manage_ajax.htm', poststr);	
		}
	}
	
	
	
	
	
	function show_distributor_retailer_detials(value)
	{		
		var poststr = "dis_retailer_id="+value;
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/manage_sales_ajax.htm', poststr);
	}


function show_vendor_type(obj)
 {
    var vendor_group_id=document.getElementById("vendor_group_id").value;
    if(vendor_group_id=="select")
    {
      remOption(document.getElementById("vendor_type_id"));
	  addOption(document.getElementById("vendor_type_id"),'Select','select');
	  addOption(document.getElementById("vendor_type_id"),'None','0');
      alert("Please Select Vendor Group"); 
      document.getElementById("vendor_group_id").focus();          
      return false; 
    }
    else
    {
      var poststr = "vendor_group_id="+vendor_group_id;
      //alert("poststr="+poststr);
      makePOSTRequest('src/php/manage_ajax.htm', poststr);
    }
 }

function action_manage_vendor_type(action_type)
{  
    if(action_type=="add" || action_type=="edit" || action_type=="delete") /* working and variable of both action is same */  
    {
        if(action_type=="delete") /* for confirmation alert of delete action for delete it will be true or false and other action it always true */
        {       
          var result=delete_confirmation();
        }
        else
        {
           var result=true;
        }
        
        if(result==true)
        {
          var group_id=document.getElementById("vendor_group_id").value;
          if(action_type=="add")
          {             
            var admin_id=(document.getElementById("vendor_type_id").value).split(",");
            var vendor_type_admin_id=admin_id[0]; 
          }
          else if(action_type=="edit" || action_type=="delete")
          {
              var vendor_type_admin_id=document.getElementById("vendor_type_id").value;
          }
            
          var vendor_type_name=document.getElementById("vendor_type_name").value;
          if(group_id=="select") 
          {
            alert("Please Select Group Name"); 
            document.getElementById("group_id").focus();
            return false;
          }         
          else if(vendor_type_admin_id=="select") 
          {
            alert("Please Select Vendor Admin Name"); 
            document.getElementById("vendor_type_admin_id").focus();
            return false;
          }        
          else if(vendor_type_name=="") 
          {
            alert("Please Enter Vendor Type Name");
            document.getElementById("vendor_type_name").focus();
            return false;
          }
          var poststr = "action_type="+encodeURI(action_type) + 
          "&group_id="+encodeURI(group_id) +
          "&vendor_type_id="+encodeURI(vendor_type_admin_id) +
          "&vendor_type_name="+encodeURI(vendor_type_name); 
        } 
    } 
    //alert("poststr="+poststr);
    makePOSTRequest('src/php/action_manage_vendor_type.htm', poststr);    
}

	function action_manage_vendor_account(action_type)
	{
		if(action_type == "add")
		{
			var result=vendor_account_validation();		
			 if(result!=false)
			 {
				//alert("result="+result);
				var poststr="action_type=" + encodeURI(action_type)+
				"&group_id=" +document.getElementById("vendor_group_id").value+			
				"&vendor_type_id=" +document.getElementById("vendor_type_id").value+
				"&owner_id=" +result+
				"&vendor_name=" + encodeURI( document.getElementById("vendor_name").value )+
				"&vendor_login_id=" + encodeURI( document.getElementById("vendor_login_id").value )+
				"&password="+document.getElementById("password").value;				 
			 }			
		}
		else if(action_type == "edit")
		{
			var obj=document.manage1.manage_id;
			var manage_id=radio_selection(obj);
			if(manage_id!=false)
			{
				if(document.getElementById('vendor_name').value=='')
				{
					alert("Please Enter Vendor Name");
					return false;
				}
				var poststr="action_type=" + encodeURI(action_type)+
							"&vendor_name=" + encodeURI( document.getElementById("vendor_name").value )+
							"&manage_id1=" +manage_id;				
			}		
		}
		else if(action_type == "delete")
		{
			var obj=document.manage1.manage_id;
			var manage_id=radio_selection(obj);
			if(manage_id!=false)
			{
				if(document.getElementById('vendor_name').value=='')
				{
					alert("Please Enter Vendor Name");
					return false;
				}
				var txt="Are You Sure You Want To Delete This One";
				if(!confirm(txt))
				{
					return false; 
				}
				else
				{
				var poststr="action_type=" + encodeURI(action_type)+							
							"&manage_id1=" +manage_id;
				}
			}		
		}		
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_manage_vendor_account.htm', poststr);
	}
	
	function vendor_account_validation()
	{
		var vendor_group_id=document.getElementById('vendor_group_id').value;
		var vendor_type_id=document.getElementById('vendor_type_id').value;	
		if(vendor_group_id=='select')
		{
			alert("Please select group");
			return false;
		}
		else if(vendor_type_id=="select")
		{
			alert("Please select vendor type");
			return false;
		}
		else
		{
			var manage_id1=document.manage1.manage_id.value;
			//alert("manage_id="+manage_id1);
			if(manage_id1=="select")
			{
				alert("Please select sales person");
				return false;
			}
			if(manage_id1==undefined)
			{
				var obj=document.manage1.manage_id;
				var manage_id1=radio_selection(obj);						
			}			
		}
		if(manage_id1!=false)  /* Manage ID has been false if distributor and retailer has not selected */
		{		
			if(document.getElementById('vendor_name').value=='')
			{
				alert("Please Enter Vendor Name");
				return false;
			}
			else if(document.getElementById('vendor_login_id').value=='')
			{
				alert("Please Enter Vendor Login Id");
				return false;
			}
			else if(document.getElementById('password').value=='')
			{
				alert("Please Enter Password");
				return false;
			}
			else if(document.getElementById('re_password').value=='')
			{
				alert("Please Enter Re-Password");
				return false;
			}
		}
		return manage_id1;		
	}
 
 ////////////////////////////////////////
 
  //ACTION ESCLALATION 
  function action_manage_escalation(action_type)
  {  
    //alert("action_type="+action_type);    
    if(action_type=="add")  
    {
      var obj=document.manage1.elements['manage_id[]'];
      var result=checkbox_selection(obj);
      //alert("result"+result);
      if(result!=false)
      {   	           
        var person_name=document.getElementById("person_name").value;
        var person_mob=document.getElementById("person_mob").value;
        var person_email=document.getElementById("person_email").value; 
        var other_detail=document.getElementById("other_detail").value;
        if(person_name=="") 
        {
          alert("Please Enter Person Name"); 
          document.getElementById("person_name").focus();
          return false;
        }
        if(person_mob=="") 
        {
          alert("Please Enter Person Mobile"); 
          document.getElementById("person_mob").focus();
          return false;
        }
        var poststr = "action_type="+encodeURI(action_type ) + 
        "&local_account_ids="+encodeURI(result) +
        "&person_name="+encodeURI(person_name) +
        "&person_mob="+encodeURI(person_mob) +
        "&person_email="+encodeURI(person_email) +
        "&other_detail="+encodeURI(other_detail); 
      }
    }
    else if(action_type=="edit")               
    {		
		var escalation_id1=document.getElementById("escalation_id").value; 
		if(escalation_id1=="select")
		{
			alert("Please Select Escalation"); 
			document.getElementById("escalation_id").focus();
			return false;
		}       
		var person_name=document.getElementById("person_name").value;
    var person_mob=document.getElementById("person_mob").value;
    var person_email=document.getElementById("person_email").value;    
		var other_detail=document.getElementById("other_detail").value;
		if(person_name=="") 
		{
			alert("Please Enter Calibration Name"); 
			document.getElementById("calibration_name").focus();
			return false;
		}
		if(person_mob=="") 
		{
			alert("Please Enter Person Mobile"); 
			document.getElementById("person_mob").focus();
			return false;
		}
		var poststr ="action_type="+encodeURI(action_type) + 
		"&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+
		"&escalation_id="+encodeURI(escalation_id1) +
		"&person_name="+encodeURI(document.getElementById("person_name").value ) +
		"&person_mob="+encodeURI(document.getElementById("person_mob").value ) +
		"&person_email="+encodeURI(document.getElementById("person_email").value ) +
		"&other_detail="+encodeURI(document.getElementById("other_detail").value); 
    }
    else if(action_type=="delete")
    {
      var escalation_id1=document.getElementById("escalation_id").value;
      if(escalation_id1=="select")
      {
        alert("Please Select Escalation"); 
        document.getElementById("escalation_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete this Escalation!";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
                    "&escalation_id=" + encodeURI(escalation_id1);
  }
  else if(action_type=="assign_prev")
	{    
    var form_obj1 = document.manage1.elements['alert_id'];  
    var radio_result1 = radio_selection(form_obj1);
    //var checkbox_result=checkbox_selection(form_obj2); /////////validate and get vehicleids   
		var form_obj4 = document.manage1.elements['vehicle_id[]'];
    var checkbox_result = checkbox_selection(form_obj4);
    //alert("result="+checkbox_result);    
    
    var form_obj5 = document.manage1.elements['sms_status[]'];
    var form_obj6 = document.manage1.elements['mail_status[]'];
    
    //var duration_tmp = document.manage1.duration.value;      
  	var cnt=0;
  	var duration;
  	//var checkbox_result="";
  	var duration_result="";
  	var sms_result="";
  	var mail_result="";
  	var sms;
  	var mail;
  	var flag=0;
  	
    if(form_obj1.length!=undefined)
  	{
  		for (var i=0;i<form_obj1.length;i++)
  		{
  			if(form_obj1[i].checked==true)
  			{				
          //alert("f1="+form_obj5[i].checked+" ,f2="+form_obj6[i].checked);          
          if(form_obj5[i].checked == true)
          {
            sms = "1"; 
          }
          else
          {
            sms = "0";
          }
          
          if(form_obj6[i].checked == true)
          {
            mail = "1"; 
          }
          else
          {
            mail = "0";
          }
          var duration_id_tmp = 'duration' + form_obj1[i].value;          
          if( (document.getElementById(duration_id_tmp).value == "") && (form_obj1[i].value==1 || form_obj1[i].value==2 || form_obj1[i].value==10 || form_obj1[i].value==12 || form_obj1[i].value==13) )
          {
            alert("Please fill the duration field");
            return false; 
          }
          if( document.getElementById(duration_id_tmp).value >=0 && document.getElementById(duration_id_tmp).value <=1440 )
          {
            duration = document.getElementById(duration_id_tmp).value; 
          }        			
          else
          {
            alert("Please enter valid duration in minutes(upto 24 hrs)");
            return false;
          }
            				         
  				flag=1;
  			}	  
  		}
    }
    
    if(checkbox_result!=false && radio_result1!=false)
		{	
			var poststr="action_type="+encodeURI(action_type) +
                  "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&alert_id="+radio_result1 +
                  "&vehicle_ids="+checkbox_result + 
                  "&duration=" +duration +
                  "&sms_status=" +sms +
                  "&mail_status=" +mail;
      //alert(poststr);				
		}	    
  }  
  else if(action_type=="assign")
	{
    document.getElementById('loading_status').style.display="";
    document.getElementById('loading_status').innerHTML = '<font color=blue>Please wait..</font>';            	  
		
    //alert("IN ASSIGN ALERT");
    var landmark = document.form_next.landmark.value;
    
    if( (landmark == "select") && (landmark != "-") )
    {
      alert("Please select valid landmark");
      document.getElementById('loading_status').style.display="none";
      return false;
    }   
    
    var distance_variable = document.form_next.distance_variable.value;
    
    if( (distance_variable == "") && (landmark != "-") )
    {
      alert("Please enter distance");
      document.getElementById('loading_status').style.display="none";
      return false;
    }
    
    if(landmark!="-")
    {
      var result_dec = validateDecimal(distance_variable);
      if(result_dec == false)
      {
        alert("Please enter float/integer value in distance field");
      }
    }        
    
    var alert_id = document.form_next.alert_id.value;
    var duration = document.form_next.duration.value;
    
    var sms_status = document.form_next.sms_status.value;
    var mail_status = document.form_next.mail_status.value;
       
		var vehicle_obj = document.form_next.elements['vehicle_id[]'];
		//alert("vlen="+vehicle_obj.length);
    var vehicle_ids = "";       
  	if(vehicle_obj.length!=undefined)
  	{
  		for (var i=0;i<vehicle_obj.length;i++)
  		{			
				if(i==0)
				{
					vehicle_ids = vehicle_ids + vehicle_obj[i].value;
				}
				else
				{
					vehicle_ids = vehicle_ids +","+ vehicle_obj[i].value;
				}	  
  		}
  	}
  	else
  	{
  		vehicle_ids = vehicle_obj.value;
  	}    
    
    var form_obj1 = document.form_next.elements['escalation_id[]'];    
    var checkbox_result = checkbox_selection(form_obj1); /////////validate and get vehicleids
     
    //alert("vehicle_ids="+vehicle_ids+" ,checkbox_result="+checkbox_result);
    //alert("LANDMAR="+landmark+" #alert_id="+alert_id+" #duration="+duration+" #sms_status="+sms_status+" #mail_status="+mail_status+" #vehicle_ids="+vehicle_ids+" #checkbox_result="+checkbox_result);
      		
    if(!checkbox_result)
    {
      alert("Please select atleast one Person");
      document.getElementById('loading_status').style.display="none";
      return false;
    }
    
    if(checkbox_result!=false && alert_id!="" && vehicle_ids!="")
		{
      var poststr="action_type="+encodeURI(action_type) +
                  "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&landmark_id="+landmark +
					        "&distance_variable="+distance_variable +					        
                  "&alert_id="+alert_id +	
                  "&duration=" +duration +	
                  "&sms_status=" +sms_status +
                  "&mail_status=" +mail_status +      
                  "&vehicle_ids=" +vehicle_ids +            			        
                  "&escalation_ids="+checkbox_result;;						  
		}			
	}
	else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['escalation_serial_number[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{
			var poststr="action_type="+encodeURI(action_type ) +
          "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					"&escalation_serial_number="+checkbox_result;							
		}			
	}     
	  //alert("poststr="+poststr);
	 showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_escalation.htm', poststr);
 } 
 

  function validateDecimal(value)    
  {
      var RE = /^\d*\.?\d*$/;
      if(RE.test(value)){
         return true;
      }else{
         return false;
      }
  }
     
  function show_report_duration()
  {
    //alert("show report:"+document.getElementById('select_report_duration'));
    document.getElementById('select_report_duration').style.display = "";    
  }	
  function select_report_duration()
  {
    //alert("in select:"+document.getElementById('select_duration').value);
    if(document.getElementById('select_duration').value=="select")
    {
      alert("Please select duration");
      document.getElementById('duration14').value = "";
      return false;      
    }
    else
    {
      document.getElementById('duration14').value = document.getElementById('select_duration').value;
      document.getElementById('select_report_duration').style.display = "none"; 
    } 
  }	

  function select_mail()
  {    
    var form_obj2 = document.manage1.elements['alert_id'];
    var form_obj3 = document.manage1.elements['mail_status[]'];
    var form_obj4 = document.manage1.elements['sms_status[]'];

    //alert("f2="+form_obj2.length+" f3="+ form_obj3.length);

    if(form_obj2.length!=undefined)
  	{
  		for (var i=0;i<form_obj2.length;i++)
  		{  
        var duration_id_tmp = 'duration'+form_obj2[i].value;
        document.getElementById(duration_id_tmp).value = '';
  			//alert("durationtmp in selectmail="+duration_id_tmp);
        //alert("duration_value1="+document.getElementById(duration_id_tmp).value);       
			}
		}
    else
    {
        var duration_tmp = 'duration'+form_obj2.value;
  			document.manage1.duration_tmp.value = '';         
    }        
  
    if(form_obj2.length!=undefined)
  	{
  		for (var i=0;i<form_obj2.length;i++)
  		{
  			if(form_obj2[i].checked==true)
  			{
  			  form_obj3[i].checked = true;
				}
        else
        {
          form_obj3[i].checked = false;
          form_obj4[i].checked = false;
        }				
			}
		}
    else
    {
        if(form_obj2.checked==true)
  			{
  			  form_obj3.checked = true;
				}
        else
        {
          form_obj3.checked = false;
          form_obj4.checked = false;
        }				      
    }       
  }  

//Returns true if given input is valid integer  else return false
  function checkforInteger(value) 
  {
      if (parseInt(value) != value)
       return false;
      else return true;
  }
  //Returns true if given input is valid float  else return false
  function checkforPrice(value) 
  {
      if (isNaN(parseFloat(value)))
       return false;
      else return true;
  }


	function manage_show_file_title(file_name,title)
	{
		poststr="title="+title;
		//alert("test="+title+"filename="+file_name);
		makePOSTRequest(file_name, poststr);
	} 
	
	function show_manage_page()
	{		
		document.getElementById("bodyspan").innerHTML="";
	}
	
	function show_print_block()
	{		
		document.getElementById("confirm").style.display='none';
		document.getElementById("print").style.display='';
	}
	
	
  
	function show_previous_page(type, option, title)      // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
	{
		var poststr = "title="+title;
		//alert("poststr="+poststr); 
		makePOSTRequest('src/php/' + type + '_' + option + '.htm', poststr);
	}
	function show_group_accounts(group_id_to_account)
  {		
    var poststr = "group_id_to_account="+group_id_to_account;
    //alert("poststr1="+poststr);
    makePOSTRequest('src/php/manage_ajax.htm', poststr);
  }
  function show_accounts_salesid(account_id_to_salespersonid)
  {	
	if(account_id_to_salespersonid=="select")
	{
		remOption(document.getElementById("sales_id"));
		addOption(document.getElementById("sales_id"),'Select','select');		
		return false;
	}
	else
	{
		var poststr = "account_id_to_salespersonid="+account_id_to_salespersonid;
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr);
	}
  }
  
	function display_data(commonid_to_display,param1,param2)
	{	
		if(commonid_to_display=="select")
		{
			remOption(document.getElementById(param));
			addOption(document.getElementById(param),'Select','select');		
			return false;
		}
		else
		{
			var poststr = "commonid_to_display="+commonid_to_display+
						  "&param1="+param1+
						  "&param2="+param2;
			//alert("poststr1="+poststr);
			makePOSTRequest('src/php/manage_ajax.htm', poststr);
		}
	} 
	
	function show_edit_delete_cancel_table(common_id,param1,param2)
	{
		if(common_id=="")
		{	
			if(document.getElementById(param1).value=="select")
			{
				alert("Please Select Combo Option");
				document.getElementById(param1).focus();
				return false;			
			}
			else
			{
				var poststr = "commonid_to_display="+document.getElementById(param1).value+
							  "&param1="+param1+							  
							  "&param2="+param2+
							  "&start_date="+document.getElementById("date1").value;
			}		
		}
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr);
	}
	
	function delete_cancel(common_id,common_date)
	{
	    var tr_common_id="A_"+common_id;
		if(document.getElementById('common_ids').value=="" || document.getElementById('common_date').value=="")
		{
			document.getElementById('common_ids').value=common_id;
			document.getElementById('common_date').value=common_date;
		}
		else
		{
			var ids=document.getElementById('common_ids').value;
			document.getElementById('common_ids').value=ids+","+common_id;
			var dates=document.getElementById('common_date').value;
			document.getElementById('common_date').value=dates+","+common_date;
		}		
		document.getElementById(tr_common_id).style.display="none";		
		//alert("common_id="+document.getElementById('common_ids').value);
	}
	
	function action_common_function(action_page_name,action_type,match_id)
	{	
		 var title=document.getElementById("title").value;
		if(document.getElementById('common_ids').value=="")
		{
			alert("Please click atleast one object");
			return false;
		}
		else
		{
			var result=delete_confirmation();
			if(result!=false)
			{				
			var poststr = "action_type="+action_type+
						  "&common_match_id="+match_id+
						  "&title="+title+			
						  "&common_ids="+document.getElementById('common_ids').value+
						  "&common_date="+document.getElementById('common_date').value;
			}
		}
		//alert("poststr1="+poststr+"action_page"+action_page_name);
		makePOSTRequest(action_page_name, poststr);		
	}
  
function select_all(obj)
{
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['manage_id[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['manage_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

function select_all_escalation(obj)
{
	//alert("obj="+document.forms[0]);	
  if(obj.all.checked)
	{
		var i;
		var s = obj.elements['escalation_id[]'];
		//alert("obj="+obj);
		//alert("len="+s.length);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['escalation_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	} 
}

function select_all_sectors(obj)
{
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['sector[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['sector[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

 function manage_add_route() 
 { 
   makePOSTRequest('src/php/manage_add_route.htm', '');
 }
 
 function show_vehicles(obj) 
 {
   poststr="create_id="+encodeURI(obj);
   makePOSTRequest('src/php/manage_availability.htm', poststr);
 }
 

 function validate_sector_sequence(mid)
 {
	  //alert("mid="+mid);
    var sector = document.forms[0].elements['sector[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		var sector_ids = "";
		var j=0;
    var id1,id2;
    var seq1,seq2;
    
    //alert("validate_sector_seq");
     
    for(var i=0;i<sector.length;i++)
		{
			id1 = "seq"+i;			
			seq1 = document.getElementById(id1).value;
      //alert("checked="+sector[i].checked);
			
      for(var j=i+1;j<sector.length;j++)
  		{			
        id2 = "seq"+j;
        //alert("id1="+id1+" ,id2="+id2);
        
        seq2 = document.getElementById(id2).value;
        
        //alert("seq1="+seq1+" ,seq2="+seq2);        
        if( (seq1 == seq2) && (seq1>0 && seq2>0) )
        {
          alert("Sequence number should be unique!");
          
          document.getElementById(mid).options[0].selected = "Select";

          return false;
        }
      }
    }   
 }
 
 function action_manage_route(action_type)
 {
	  //alert("action_type="+action_type);
    if(action_type=="add")  
    {
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
  		//alert("result"+result);
  		if(result!=false)
  		{
  		  var add_route_name=document.getElementById("add_route_name").value; 
  		  
        //Get Sector Coord
  		  try
        {
          var sector = document.forms[0].elements['sector[]'];
      		//alert("obj="+obj);
      		//alert("len="+len);
      		var sector_ids = "";
      		var j=0;
      		var seq1,seq2;
          var id1,id2;
           
          //alert("sector1="+sector+" ,sector_len1="+sector.length);
          
          if(sector.length!=undefined)
          {
            for(var i=0;i<sector.length;i++)
        		{
              id1 = "seq"+i;
              seq1 = document.getElementById(id1).value;
              
              for(var j=i+1;j<sector.length;j++)
          		{          
                id2 = "seq"+j;
                seq2 = document.getElementById(id2).value;
                //alert("seq1="+seq1+" ,seq2="+seq2);
                if( (seq1 == seq2) && (seq1>0 && seq2>0) )
                {
                  alert("Sequence number should be unique!");
                  return false;            
                } 
              }
            }        
                    
            j=0;        
            for(var i=0;i<sector.length;i++)
        		{
        			//alert("checked="+sector[i].checked);
              
              id1 = "seq"+i;
              seq1 = document.getElementById(id1).value; 
              
              if(sector[i].checked==true)
        			{
        			  if(seq1=="0")
        			  {
        			   alert("Please select sequence");
                }
                else
                {      			  
                  if(j==0)
                  {
                    sector_ids = sector[i].value+","+seq1;
                  }
                  else
                  {
                    sector_ids = sector_ids+":"+sector[i].value+","+seq1
                  } 
                  j++;
                }
              }
            }          
          }
          
          else if((sector!=undefined) && (sector.length==undefined))
          {
              id1 = "seq0";
              seq1 = document.getElementById(id1).value;                
         
              id1 = "seq0";
              seq1 = document.getElementById(id1).value; 
              
              //alert("sector.checked="+sector.checked);
              if(sector.checked==true)
        			{
        			  if(seq1=="0")
        			  {
        			   alert("Please select sequence");
        			   return false;
                }
                else
                {      			  
                  sector_ids = sector.value+","+seq1;
                }
              }
              //alert("sids="+sector_ids);                                  
          }	
          
          
    		  if(sector_ids=="") 
    		  {
      			alert("Please Select atleast one Sector");
      			return false;
      		}	                 		        
        }
        catch(err)
        {
        //Handle errors here
        }        			
              
        
        if(add_route_name=="") 
  		  {
    			alert("Please Enter Route Name"); 
    			document.getElementById("add_route_name").focus();
    			return false;
  		  }	  		  
    		  
        var poststr = "action_type="+action_type+ 
    					"&local_account_ids="+result+
    					"&route_name="+add_route_name+
    					"&sector_ids="+sector_ids; 
  		}
    }
    else if(action_type=="edit")                                               
    {  
  		//var obj=document.manage1.elements['manage_id[]'];
  		//var result=checkbox_selection(obj);     
    	var account_id_local=document.getElementById("account_id_hidden").value;
  		var route_id = document.getElementById("route_id").value;
  		var route_name = document.getElementById("route_name").value;
  		
      if(route_id=="select") 
      {
        alert("Please Select Route");        
        document.getElementById("route_id").focus();
        return false;
      }  		
  		
  		//alert("result"+result);
  		if(account_id_local!="")
  		{
  		  //var add_route_name=document.getElementById("add_route_name").value;  		  
        //Get Sector Coord
  		  var sector = document.forms[0].elements['sector[]'];
    		//alert("obj="+obj);
    		//alert("len="+len);
    		var sector_ids = "";
    		var j=0;
    		var seq1,seq2;
        var id1,id2;
         
        //alert("sector2="+sector+" ,sector_len2="+sector.length);
        
        if(sector.length!=undefined)
        {
          //alert("one");
          for(var i=0;i<sector.length;i++)
      		{
            id1 = "seq"+i+"";
            seq1 = document.getElementById(id1).value;
            
            for(var j=i+1;j<sector.length;j++)
        		{          
              id2 = "seq"+j+"";
              seq2 = document.getElementById(id2).value;
              //alert("seq1="+seq1+" ,seq2="+seq2);
              if( (seq1 == seq2) && (seq1>0 && seq2>0) )
              {
                alert("Sequence number should be unique!");
                return false;            
              } 
            }
          }        
                  
          j=0;
          
          for(var i=0;i<sector.length;i++)
      		{
      			//alert("checked="+sector[i].checked);
            
            id1 = "seq"+i;
            seq1 = document.getElementById(id1).value; 
            
            if(sector[i].checked==true)
      			{
      			  if(seq1=="0")
      			  {
      			   alert("Please select sequence");
      			   return false;
              }
              else
              {
                if(j==0)
                {
                  sector_ids = sector[i].value+","+seq1;
                }
                else
                {
                  sector_ids = sector_ids+":"+sector[i].value+","+seq1
                } 
                j++;
              }
            }
          }
        }	
        
        else if((sector!=undefined) && (sector.length==undefined))
        {
            //alert("two");
            id1 = "seq0";
            seq1 = document.getElementById(id1).value;                
       
            id1 = "seq0";
            seq1 = document.getElementById(id1).value; 
            
            //alert("sector.checked="+sector.checked);
            if(sector.checked==true)
      			{
      			  if(seq1=="0")
      			  {
      			   alert("Please select sequence");
              }
              else
              {      			  
                sector_ids = sector.value+","+seq1;
              }
            }
            //alert("sids="+sector_ids);                    
        }
          
  		  if(sector_ids=="") 
  		  {
    			alert("Please Select atleast one Sector");
    			return false;
    		}	          		        			        		
              
        if(route_name=="") 
  		  {
    			alert("Please Enter Route Name"); 
    			document.getElementById("route_name").focus();
    			return false;
  		  }
  		  if(sector_ids=="") 
  		  {
    			alert("You have to select one Sector to proceed");
    			return false;
    		}		  
    		  
        var poststr = "action_type="+action_type+ 
    						"&local_account_ids="+account_id_local+
    						"&route_id="+route_id+
    						"&route_name="+route_name+
    						"&sector_ids="+sector_ids; 
  		}
    }
    else if(action_type=="delete")
    {
	   var route_id=document.getElementById("route_id").value;
     if(route_id=="select") 
     {
        alert("Please Select Route Name");        
        document.getElementById("route_id").focus();
        return false;
     }
     var txt="Are You Sure You Want To Delete this route";
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
	  //alert("poststr="+poststr);
    makePOSTRequest('src/php/action_manage_route.htm', poststr);
 }
   
      
   
 function manage_add_landmark() 
 {
   makePOSTRequest('src/php/manage_add_landmark.htm', '');
 }
 
 function action_manage_landmark(action_type)
 {
	//alert("action_type1="+action_type);	
	if(action_type=="add" || action_type=="edit") 
	{
		var landmark_name=document.getElementById("landmark_name").value;     
		var landmark_point=document.getElementById("landmark_point").value;     
		var zoom_level=document.getElementById("select_zoom_level").value;		
	}
    if(action_type=="add") 
    {        
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
		//alert("result="+result);      
  		if(result!=false)
  		{
			var form_validation=landmark_form_validation(landmark_name,landmark_point,zoom_level);
			//alert("form_validation="+form_validation);
			if(form_validation!=false)
			{
				var poststr = "action_type=" + action_type +
							  "&account_id_local=" + encodeURI(result) +		 
							  "&landmark_name=" + encodeURI(landmark_name) +
							  "&landmark_point=" + encodeURI(landmark_point)+
							  "&zoom_level=" + encodeURI(zoom_level);
			}
		}                          
    }  

    if(action_type=="edit")
    {
	  var account_id_local=document.getElementById("account_id_local").value;
      var landmark_id=document.getElementById("landmark_id").value; 
	  var form_validation=landmark_form_validation(landmark_name,landmark_point,zoom_level);
		//alert("form_validation="+form_validation);
		if(form_validation!=false)
		{
		 var poststr = "action_type=" + action_type +
						"&landmark_id=" +landmark_id+
						"&account_id_local=" +account_id_local+
						"&landmark_name="+landmark_name+
						"&landmark_point="+landmark_point+
						"&zoom_level="+zoom_level;						
		}     			 
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
	showManageLoadingMessage();
	makePOSTRequest('src/php/action_manage_landmark.htm', poststr);
	}  
	function landmark_form_validation(landmark_name,landmark_point,zoom_level,distance_variable)
	{
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
function action_manage_vts_trip(action_type)
 {
    //alert("action_type1="+action_type);	
    if(action_type=="add") 
    {
        var landmark_name1=document.getElementById("landmark1_name").value;     
        var landmark_point1=document.getElementById("landmark1_point").value; 
        var landmark_name2=document.getElementById("landmark2_name").value;     
        var landmark_point2=document.getElementById("landmark2_point").value;   
        var trip_startdate=document.getElementById("date1").value;		

		var obj_account =document.manage1.elements['manage_id'];
		//alert("obj_account="+obj_account);  
		var result_account = radio_selection(obj_account);
		//alert("result_account="+result_account);  
        var obj_vehicle =document.manage1.elements['vehicleserial[]'];
        var result_vehicle =checkbox_selection(obj_vehicle);
		//alert("result_vehicle="+result_vehicle);  
        //alert("result="+result);      
        if(result_account!=false && result_vehicle!=false)
        {
            var form_validation=trip_form_validation(landmark_name1,landmark_point1,landmark_name2,landmark_point2,trip_startdate);
            //alert("form_validation="+form_validation);
            if(form_validation!=false)
            {
                var poststr = "action_type=" + action_type +
                    "&account_id_local=" + encodeURI(result_account) +	
					"&vehicle_ids=" + encodeURI(result_vehicle) +						
                    "&landmark_name1=" + encodeURI(landmark_name1) +
                    "&landmark_point1=" + encodeURI(landmark_point1)+
                    "&landmark_name2=" + encodeURI(landmark_name2) +
                    "&landmark_point2=" + encodeURI(landmark_point2)+							  
                    "&trip_startdate=" + encodeURI(trip_startdate);
					showManageLoadingMessage();
            }
        }
    }

    if(action_type=="close")
    {
		var account_id_local=document.getElementById("account_id_local").value;
          var obj=document.manage1.elements['trip_id[]'];
          //alert("obj="+obj+" ,account_id_local="+account_id_local);
          var trip_ids=checkbox_selection(obj);
          //alert("result="+trip_ids);
          //var trip_id=document.getElementById("trip_id").value;
		var poststr = "action_type=" + action_type +
                        "&trip_ids=" +trip_ids+
                        "&account_id_local=" +account_id_local;	
		showManageLoadingMessage();						
    }	 
    //alert("poststr="+poststr);
    
    makePOSTRequest('src/php/action_manage_vts_trip.htm', poststr);
}  
function trip_form_validation(landmark_name1,landmark_point1,landmark_name2,landmark_point2,trip_startdate)
{
    if(landmark_name1=="") 
    {
      alert("Please Enter Source Name"); 
      document.getElementById("landmark_name1").focus();
      return false;
    }
    if(landmark_name2=="") 
    {
      alert("Please Enter Destination Name"); 
      document.getElementById("landmark_name2").focus();
      return false;
    }		
    if(landmark_point1=="") 
    {
      alert("Please fill Source gps coordinate");
      document.getElementById("landmark_point1").focus();
      return false;
    }
    if(landmark_point2=="") 
    {
      alert("Please fill Destination gps coordinate");
      document.getElementById("landmark_point2").focus();
      return false;
    }
    if(landmark_point2=="") 
    {
      alert("Please fill Destination gps coordinate");
      document.getElementById("landmark_point2").focus();
      return false;
    }	
    if(trip_startdate=="") 
    {
      alert("Please fill Trip startdate");
      document.getElementById("trip_startdate").focus();
      return false;
    }	
}

function show_trip_vehicles(account_id) {
	//alert("k");
	var poststr = "account_id=" + account_id;                       
	makePOSTRequest('src/php/report_hierarchy_header_trip.htm', poststr);
}
function manage_availability_1(obj, source, type)
{
    if(document.getElementById(source).value!="")
    {
      var poststr = source+"=" +encodeURI( document.getElementById(source).value )+
                    "&type=" + encodeURI( type );
      //alert("Rizwan:source="+source+" type="+type);
      makePOSTRequest('src/php/manage_availability.htm', poststr);
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
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }
 }
 
 function show_polyline_coord(obj)
 {
    var polyline_id=document.getElementById("polyline_id").value;
    if(polyline_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "polyline_id=" + encodeURI( document.getElementById("polyline_id").value);
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }
 }
 
 function show_visit_area_coord(obj)
 {
    var geo_id=document.getElementById("geo_id").value;
    if(geo_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "visit_area_id=" + encodeURI( document.getElementById("geo_id").value);
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }
 } 
 
 function show_station_coord(obj)
 {
    var station_id=document.getElementById("station_id").value;
    if(station_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "station_id=" + encodeURI( document.getElementById("station_id").value);
      //alert(poststr);
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }
 }
 
 function show_calibration_detail(obj)
 {
    var geo_id=document.getElementById("calibration_id").value;
    if(geo_id=="select")
    {
      document.getElementById("calibration_area").style.display="none"; 
    }
    else
    {
      var poststr = "calibration_id=" + encodeURI( document.getElementById("calibration_id").value);
      //alert("poststr="+poststr);
      makePOSTRequest('src/php/manage_ajax.htm', poststr);
    }
 }
 
  function show_escalation_detail(obj)
  {
    var esclation_id=document.getElementById("escalation_id").value;
    if(esclation_id=="select")
    {
      document.getElementById("escalation_area").style.display="none"; 
    }
    else
    {
      var poststr = "escalation_id=" + encodeURI( document.getElementById("escalation_id").value);
      makePOSTRequest('src/php/manage_ajax.htm', poststr);
    }
 } 
   
 function manage_show_sector_ids()
 {
    //local_account_ids
    var account_id_local=document.getElementById("account_id_hidden").value;
    var route_id=document.getElementById("route_id").value;
    if(route_id=="select")
    {
      document.getElementById("sector_area").style.display="none"; 
    }
    else
    {
      var poststr = "route_id=" + encodeURI( document.getElementById("route_id").value)+
      "&account_id_local=" + encodeURI(account_id_local)+
      "&type=sector_ids";
      
      //alert(poststr);
      makePOSTRequest('src/php/manage_ajax_sector_coord.htm', poststr);
    }
 }
 
 function manage_show_sector_coord(obj)
 {
    var sector_id=document.getElementById("sector_id").value;
    if(sector_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "sector_id=" + encodeURI( document.getElementById("sector_id").value)+
      "&type=sector_coord";
      makePOSTRequest('src/php/manage_ajax_sector_coord.htm', poststr);
    }
 }  
   
 function test()
 {
	//alert("test");
 }
 
 function show_landmark_coord(obj)
 {
    var landmark_id1=document.getElementById("landmark_id").value;	
	var landmark_tmp=landmark_id1.split(":");
	var landmark_id=landmark_tmp[0];
   //alert("landmark_id="+landmark_id);
    if(landmark_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "landmark_id=" + encodeURI( document.getElementById("landmark_id").value);
      //alert("postr="+poststr);
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }
    document.getElementById('select_zoom_level').value=landmark_tmp[1];
 }
   
var COLORS = [["red", "#ff0000"], ["orange", "#ff8800"], ["green","#008000"],
              ["blue", "#000080"], ["purple", "#800080"]];
			  
var options = {};
var lineCounter_ = 0;
var shapeCounter_ = 0;
var markerCounter_ = 0;
var colorIndex_ = 0;
var featureTable_; 
var common_event; 



function getColor(named){return COLORS[(colorIndex_++) % COLORS.length][named ? 0 : 1];}

var poly_type; /////////it is both for polygon or polyline 
var coord;  


function clearSelection() 
	  {
        if (selectedShape) 
		{
          selectedShape.setEditable(false);
          selectedShape = null;
        }
      }

      function setSelection(shape) 
	  {
		//alert(shape);
        clearSelection();
        selectedShape = shape;
        shape.setEditable(true);
        selectColor(shape.get('fillColor') || shape.get('strokeColor'));
      }

      function deleteSelectedShape() 
	  {
        if(selectedShape) 
		{
          selectedShape.setMap(null);
        }
		  drawingManager.setOptions({
           drawingControl: true
         });
      }

      function selectColor(color) 
	  {
        selectedColor = color;
        for (var i = 0; i < colors.length; ++i) 
		{
          var currColor = colors[i];
          colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
        }

        var rectangleOptions = drawingManager.get('rectangleOptions');
        rectangleOptions.fillColor = color;
        drawingManager.set('rectangleOptions', rectangleOptions);

        var polygonOptions = drawingManager.get('polygonOptions');
        polygonOptions.fillColor = color;
        drawingManager.set('polygonOptions', polygonOptions);
      }

      function setSelectedShapeColor(color) 
	  {
        if (selectedShape) 
		{
          if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) 
		  {
            selectedShape.set('strokeColor', color);
          } 
		  else 
		  {
            selectedShape.set('fillColor', color);
          }
        }
      }

      function makeColorButton(color) 
	  {
        var button = document.createElement('span');
        button.className = 'color-button';
        button.style.backgroundColor = color;
        google.maps.event.addDomListener(button, 'click', function() 
		{
          selectColor(color);
          setSelectedShapeColor(color);
        });

        return button;
      }

       function buildColorPalette() 
	   {
         var colorPalette = document.getElementById('color-palette');
         for (var i = 0; i < colors.length; ++i) 
		 {
           var currColor = colors[i];
           var colorButton = makeColorButton(currColor);
           colorPalette.appendChild(colorButton);
           colorButtons[currColor] = colorButton;
         }
         selectColor(colors[0]);
       }
	   
	   var landmarkMarkers=new Array();
	
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
			var point=new google.maps.LatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));		
			var lat=point.lat();  
			var lng=point.lng();        
			
			var contentString = '<div style="height:10px"></div><table>'
			+'<tr><td style="font-size:11px;">'+point+'</td></tr>'               
			+'</table><div style="height:10px"></div>'			 					
			+'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\'landmark_point\')" /></center>';
			var icon1 = {
								  url: 'images/landmark.png',
								  size: new google.maps.Size(10, 10),
								scaledSize: new google.maps.Size(10, 10)
								};
			var infowindow = new google.maps.InfoWindow();
			marker = new google.maps.Marker({position: point,icon:icon1, map: map});
			infowindow.setContent(contentString);
			infowindow.open(map, marker);
			landmarkMarkers.push(marker);			  
		}
		else
		{
			//alert("in else");
			var lat_lng="";        
			landmark_map_part(lat_lng);
			//map.clearOverlays();
		}

		google.maps.event.addListener(map, 'click', function(event) 
		{
		
			deleteOverlays();
			var icon1 = {
								  url: 'images/landmark.png',
								  size: new google.maps.Size(10, 10),
								scaledSize: new google.maps.Size(10, 10)
								};
			marker = new google.maps.Marker({position: event.latLng, icon:icon1, map: map});
			document.getElementById("landmark_point").value=event.latLng.lat()+","+event.latLng.lng();
			var contentString='<div style="height:10px"></div><table>'
								+'<tr><td style="font-size:11px">'+event.latLng+'</td></tr>'               
								+'</table><div style="height:10px"></div>'			 					
								+'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\'landmark_point\')" /></center>';
		
			var infowindow = new google.maps.InfoWindow();
			infowindow.setContent(contentString);
			infowindow.open(map, marker);
			landmarkMarkers.push(marker);

		});		   
	}
	else 
	{
		alert("Sorry, the Google Maps API is not compatible with this browser");
	}
	
	
	function deleteOverlays() 
	{
		for (var i = 0; i < landmarkMarkers.length; i++) 
		{
			landmarkMarkers[i].setMap(null);
		}
	}
}
	function manage_landmark_trip(param)
	{	
            var landmark_point = param+"_point";
            var prev_landmark_point = "prev_"+param+"_point";
            //alert("param="+param);
		if(GBrowserIsCompatible())
		{	
		var lat_lng=document.getElementById(landmark_point).value; 
		if(lat_lng!="")  
		{     
                    landmark_map_part(lat_lng);
                    document.getElementById(prev_landmark_point).value = lat_lng;
                    lat_lng=lat_lng.split(","); 
                    var point=new google.maps.LatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));		
                    var lat=point.lat();  
                    var lng=point.lng();        

                    var contentString = '<div style="height:10px"></div><table>'
                    +'<tr><td style="font-size:11px;">'+point+'</td></tr>'               
                    +'</table><div style="height:10px"></div>'			 					
                    +'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\''+landmark_point+'\')" /></center>';
                    var icon1 = {
                        url: 'images/landmark.png',
                        size: new google.maps.Size(10, 10),
                        scaledSize: new google.maps.Size(10, 10)
                    };
                    var infowindow = new google.maps.InfoWindow();
                    marker = new google.maps.Marker({position: point,icon:icon1, map: map});
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                    landmarkMarkers.push(marker);			  
		}
		else
		{
                    //alert("in else");
                    var lat_lng="";        
                    landmark_map_part(lat_lng);
                    //map.clearOverlays();
		}

		google.maps.event.addListener(map, 'click', function(event) 
		{		
                    deleteOverlays();
                    var icon1 = {
                        url: 'images/landmark.png',
                        size: new google.maps.Size(10, 10),
                        scaledSize: new google.maps.Size(10, 10)
                    };
                    marker = new google.maps.Marker({position: event.latLng, icon:icon1, map: map});
                    document.getElementById(landmark_point).value=event.latLng.lat()+","+event.latLng.lng();
                    var contentString='<div style="height:10px"></div><table>'
                        +'<tr><td style="font-size:11px">'+event.latLng+'</td></tr>'               
                        +'</table><div style="height:10px"></div>'			 					
                        +'<center><input type="button" value="OK" onclick="javascript:return save_landmark_details(\''+landmark_point+'\')" /></center>';

                    var infowindow = new google.maps.InfoWindow();
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                    landmarkMarkers.push(marker);
		});		   
            }
            else 
            {
                alert("Sorry, the Google Maps API is not compatible with this browser");
            }        
            
            function deleteOverlays() 
            {
                for (var i = 0; i < landmarkMarkers.length; i++) 
                {
                    landmarkMarkers[i].setMap(null);
                }
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
		
		map = new google.maps.Map(document.getElementById('landmark_map'), 
		{
		  zoom: 5,
		  center: new google.maps.LatLng(22.755920681486405, 78.2666015625),
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  disableDefaultUI: true,
		  zoomControl: true
		});
	}
	
function manage_draw_geofencing_route()                                                    
{ 
	var map = new google.maps.Map(document.getElementById('map_div'), 
	{
	  zoom: 5,
	  center: new google.maps.LatLng(22.755920681486405, 78.2666015625),
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  disableDefaultUI: true,
	  zoomControl: true
	});
	var coord=document.getElementById("geo_coord").value;
	//alert("coord="+coord);
	if(coord!="")
    {         
      var coord_test = (((((coord.split('),(')).join(':')).split('(')).join('')).split(')')).join(''); 
      var coord1 = coord_test.split(":");
    
    
		var latlngbounds = new google.maps.LatLngBounds();
		var polygonCoords=new Array();
    	for(var z=0;z<coord1.length;z++)
    	{
    		var coord2 = coord1[z].split(",");
			//alert("lat="+parseFloat(coord2[0])+"lng="+parseFloat(coord2[1]));
    		polygonCoords[z] = new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1]));	 
			latlngbounds.extend(new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1])));			
    	}		
		var polygonsShape = new google.maps.Polygon({
			paths: polygonCoords,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#FF0000',
			fillOpacity: 0.35
		  });
		 
		  map.setCenter(latlngbounds.getCenter());
			map.fitBounds(latlngbounds);
		  polygonsShape.setMap(map); 
		 
google.maps.event.addListener(polygonsShape, 'click', function() {
					//alert('newShape='+polygonsShape);
					 var polygonPoints=[];
				 polygonsShape.getPath().forEach(function(latLng){polygonPoints.push(latLng.toString());})
				document.getElementById("geo_coord").value=polygonPoints;
					setSelection(polygonsShape);
            });		
			
		
    }	
	var polyOptions = 
	{
	  strokeWeight: 0,
	  fillOpacity: 0.45,
	  editable: true
	};
        drawingManager = new google.maps.drawing.DrawingManager(
		{
		   drawingControlOptions: {
            drawingModes: [
              google.maps.drawing.OverlayType.POLYGON
            ]
          },
          markerOptions: 
		  {
            draggable: true
          },
          polylineOptions: 
		  {
            editable: true
          },
          rectangleOptions: polyOptions,
          circleOptions: polyOptions,
          polygonOptions: polyOptions,
          map: map
        });
			
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) 
		{		
            if (e.type != google.maps.drawing.OverlayType.MARKER) 
			{
				// Switch back to non-drawing mode after drawing a shape.
				drawingManager.setDrawingMode(null);
				drawingManager.setOptions(
				{
					drawingControl: true
				});      
				var newShape = e.overlay;
				newShape.type = e.type;				
			   var polygonPoints=[];
				newShape.getPath().forEach(function(latLng){polygonPoints.push(latLng.toString());})				
				document.getElementById("geo_coord").value=polygonPoints;				
				google.maps.event.addListener(newShape, 'click', function() {
				 var polygonPoints=[];
				 //var arr=[];
    newShape.getPath().forEach(function(latLng){polygonPoints.push(latLng.toString());})
					document.getElementById("geo_coord").value=polygonPoints;
					setSelection(newShape);
            });
			
            setSelection(newShape);
          }
        });
        google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
        google.maps.event.addListener(map, 'click', clearSelection);
        //google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
        buildColorPalette();
}// JavaScript Document

var poly;
var flightPath;

function manage_draw_polyline_route()                                                    
{ 
	
	 map_div = new google.maps.Map(document.getElementById('map_div'), 
	{
	  zoom: 5,
	  center: new google.maps.LatLng(22.755920681486405, 78.2666015625),
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  disableDefaultUI: true,
	  zoomControl: true
	});
	//alert(map_div);
	var coord=document.getElementById("polyline_coord").value;
	//alert("coord="+coord);
	if(coord!="")
    {         
      var coord_test = (((((coord.split('),(')).join(':')).split('(')).join('')).split(')')).join(''); 
      var coord1 = coord_test.split(":");
    
    
		var latlngbounds = new google.maps.LatLngBounds();
		var polygonCoords=new Array();
		//var polygonCoords = new google.maps.MVCArray(); // collects coordinates
    	for(var z=0;z<coord1.length;z++)
    	{
    		var coord2 = coord1[z].split(",");
			//alert("lat="+parseFloat(coord2[0])+"lng="+parseFloat(coord2[1]));
    		polygonCoords[z] = new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1]));	 
			latlngbounds.extend(new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1])));			
    	}	

		var polyOptions_temp = {
			strokeColor: '#000000',
			strokeOpacity: 1.0,
			strokeWeight: 3,
			editable: true
		};
		poly = new google.maps.Polyline(polyOptions_temp);
		map_div.setCenter(latlngbounds.getCenter());
		map_div.fitBounds(latlngbounds);
		poly.setMap(map_div);	
		
		//alert(flightPath);
		
		flightPath = new google.maps.Polyline({
		path: polygonCoords,
		geodesic: true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2
	  });
	  
		flightPath.setMap(map_div);
		
		setSelection(flightPath);		
		
    }
	else
	{
		var polyOptions = 
		{
			strokeColor: '#000000',
			strokeOpacity: 1.0,
			strokeWeight: 3,
			editable: true
		};
		
		drawingManager = new google.maps.drawing.DrawingManager(
		{
		   drawingControlOptions: {
			drawingModes: [
			  google.maps.drawing.OverlayType.POLYLINE
			]
		  },
		  markerOptions: 
		  {
			draggable: true
		  },
		  polylineOptions: 
		  {
			editable: true
		  },
		  rectangleOptions: polyOptions,
		  circleOptions: polyOptions,
		  polygonOptions: polyOptions,
		  map: map_div
		});
				
	  
		google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) 
		{		
			if (e.type != google.maps.drawing.OverlayType.MARKER) 
			{
				// Switch back to non-drawing mode after drawing a shape.
				drawingManager.setDrawingMode(null);
				drawingManager.setOptions(
				{
					drawingControl: true
				});      
				var newShape = e.overlay;
				newShape.type = e.type;				
				var polyPoints=[];
				//var polyPoints = new google.maps.MVCArray(); // collects coordinates
				newShape.getPath().forEach(function(latLng){polyPoints.push(latLng.toString());})				
				document.getElementById("polyline_coord").value=polyPoints;				
				google.maps.event.addListener(newShape, 'click', function() {
				var polyPoints=[];
				//var arr=[];
				newShape.getPath().forEach(function(latLng){polyPoints.push(latLng.toString());})
				document.getElementById("polyline_coord").value=polyPoints;
				setSelection(newShape);
			});
			
			setSelection(newShape);
		  }
		});
		google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
		google.maps.event.addListener(map_div, 'click', clearSelection);	
		buildColorPalette();
	}
	
}
function setSelection_re(shape) 
	  {
		//alert(shape);
		if(shape!=undefined )
		{
			document.getElementById("polyline_coord").value="";
			for ( var i = 0; i < shape.getPath().getLength(); i++ ) {
				//alert( shape.getPath().getAt(i));
				if(i==0)
				{
					document.getElementById("polyline_coord").value=shape.getPath().getAt(i);	
				}
				else
				{
					document.getElementById("polyline_coord").value=document.getElementById("polyline_coord").value+","+shape.getPath().getAt(i);	
				}
				
			}
		
		}
		
		
      }


  ///////////////////////

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
function showCoordinateInterface(param_1)
{
    ///////////for visibility of map in the hidden div pop /////////////////
    document.getElementById("blackout").style.visibility = "visible";
    document.getElementById("divpopup").style.visibility = "visible";
    document.getElementById("blackout").style.display = "block";
    document.getElementById("divpopup").style.display = "block"; 
    ///////////////////////////////////////////////////////////////////////////
    
    common_event=param_1; 
    //alert(common_event);
    //alert(common_event);
    if(common_event=="landmark") ////////only for landmark
    {
        manage_landmark(common_event);   
    }
    else if( (common_event=="landmark1") || (common_event=="landmark2") ) ////////only for landmark
    {
        manage_landmark_trip(common_event);   
    }
    else if(common_event=="location")
    {
        manage_location(common_event);
    }
    //for making polyline
    else if(common_event=="polyline")
    {
       //document.getElementById("close_geo_route_coord").value = document.getElementById("polyline_coord").value; // kept last geo coord details for closing pop up div
        refreshIntervalId = setInterval(keep_alive,8400000);  //My session expires at 140 minutes
        manage_draw_polyline_route();
    }
    else///////for geofencing and route both ////////////
    {   	 
      if(common_event=="geofencing")
      {
         document.getElementById("close_geo_route_coord").value = document.getElementById("geo_coord").value; // kept last geo coord details for closing pop up div 
      }   
      else if(common_event=="sector")
      {
        document.getElementById("close_geo_route_coord").value = document.getElementById("sector_coord").value; // kept last geo coord details for closing pop up div
      } 

      refreshIntervalId = setInterval(keep_alive,840000);  //My session expires at 15 minutes
      manage_draw_geofencing_route();
    } 
}

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
	 else if(common_event=="polyline")
     { 
        document.getElementById("polyline_coord").value = "";        
     }
    if (GBrowserIsCompatible())
    {	
  			map = new GMap2(document.getElementById("map_div"));
  		  map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
  	    map.setUIToDefault();   																	  
  	} 
	clearInterval(refreshIntervalId);
}  
 
 function save_route_or_geofencing()
 {
	//alert(common_event);
	clearInterval(refreshIntervalId);
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
	else if(common_event=="polyline")
    {
	
	  setSelection_re(flightPath);	
	  //var flightPath;
      var coord_point=document.getElementById("polyline_coord").value;
      var div_id_string="polyline_coord";
    }
    
    if(coord_point=="")
    {
      alert("Please Draw Geofencing/Polyline");
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
clearInterval(refreshIntervalId);
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
   document.getElementById("landmark_point").value=document.getElementById("prev_landmark_point").value;  ///at the time of edit landmark
   prev_landmark_point
   div_close_block();
}

function close_trip_div(close_pararm)
{
   var txt="Are You Sure You Want To Close Without Saving Points";
   if(!confirm(txt))
   {
     return false; 
   }
   document.getElementById("landmark1_point").value="";    /////// at the time of add landmark
   document.getElementById("landmark1_point").value=document.getElementById("prev_landmark1_point").value;  ///at the time of edit landmark

   document.getElementById("landmark2_point").value="";    /////// at the time of add landmark
   document.getElementById("landmark2_point").value=document.getElementById("prev_landmark2_point").value;  ///at the time of edit landmark
    
   div_close_block();
}


function close_landmark_div_station(close_pararm)
{
   var txt="Are You Sure You Want To Close Without Saving Points";
   if(!confirm(txt))
   {
     return false; 
   }
   document.getElementById("landmark_point").value="";    /////// at the time of add landmark
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
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/module_manage_selection_information.htm', poststr);
}
function select_manage_assignment_options(options)
{
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/manage_assignment_selection_information.htm', poststr);
}

function select_manage_deassignment_options(options)
{
	var poststr = "display_type1=" + encodeURI(options);
	makePOSTRequest('src/php/manage_deassignment_selection_information.htm', poststr);
}

function select_manage_register_options(options)
{
	var poststr = "display_type1=" + encodeURI(options);
	makePOSTRequest('src/php/manage_register_selection_information.htm', poststr);
}

function select_manage_deregister_options(options)
{
	var poststr = "display_type1=" + encodeURI(options);
	//alert("patstr="+poststr);
	makePOSTRequest('src/php/manage_deregister_selection_information.htm', poststr);
}
function portal_manage_vehicle_information(value)
{
	var poststr = "vehicle_id=" + encodeURI(value);
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/portal_manage_vehicle_information.htm', poststr);
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
				makePOSTRequest('src/php/action_manage_group.htm', poststr);			
			}
		}		
	}
}

function radio_selection(obj)
{
	//alert("obj="+obj);
	
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
	//alert("obj="+obj+" ,len="+obj.length+" checked="+obj.checked)
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
		//alert("In else");
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
function device_checkbox_selection(obj)
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
		id="";
		return id
	}
	else
	{	  
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
				makePOSTRequest('src/php/action_manage_account.htm', poststr);			
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
		showManageLoadingMessage();
		manage_edit_prev_interface(file_name,result);
	}
}


function manage_deassign_sector_escalation_prev(file_name)
{	
	var account_id = document.getElementById('account_id_hidden').value;	
	//alert(file_name+" #"+account_id);
  manage_edit_prev_interface(file_name,account_id);
}


function manage_edit_prev_1(file_name,action_type)
{	
	var obj=document.manage1.manage_id;
	var result=radio_selection(obj);  
  //alert("result="+result); 	
	if(result!=false)
	{		
		manage_edit_prev_interface_1(file_name,result,action_type);
	}
}

function manage_action_edit_prev(file_name)
{	
  var poststr=document.getElementById("common_id").value;
  //alert("poststr="+poststr);	
  makePOSTRequest(file_name, poststr);
}

function manage_edit_prev_checkbox(file_name)
{	
	var obj=document.manage1.elements['manage_id[]'];
	var result=checkbox_selection(obj);		
	if(result!=false)
	{		
		manage_edit_prev_interface(file_name,result);
	}
}

function manage_option_vehicle_prev(file_name)
{	
	var obj=document.manage1.manage_id;
	var obj1=document.manage1;
	
	var result=radio_selection(obj);
	var vehicle_result=radio_vehicle_option_selection(obj);	
	//alert("vehicle_result="+vehicle_result);
	if(vehicle_result=='all')
	{
		var options_value="all";
	}
	else
	{
		var options_value=manage_tree_validation(obj1);
	}
	if(result!=false && vehicle_result!=false && options_value!=false)
	{
		showManageLoadingMessage();
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
		//if(vehicle_option=obj[i].value!='all')
		//{
			if(obj[i].checked==true)
			{
				var vehicle_option=obj[i].value;
				//alert(vehicle_option);
				flag=1;
			}
		//}
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
	//alert("vehicle_option="+vehicle_option);
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
			//alert("pasword="+password);
			var password2 = document.getElementById("re_enter_password").value;
			//var perm_type = get_radio_button_value1(obj.elements["perm_type"]);								
		}
		if(action_type=="add" || action_type=="edit")
		{
			var user_name = document.getElementById("user_name").value;	
			if(action_type=="edit")
			{
				var ac_type = get_radio_button_value1(obj.elements["perm_type"]);
			}
			var account_feature1=obj.elements['account_feature[]'];
			var feature_count=obj.elements['feature_count'];      	
		}		

		if(login=="")
		{alert("Login field can not be Empty!");return false;}
		
		if(password=="")
		{alert("Password field can not be Empty!");return false;}
		
		if(password!=password2)
		{alert("Password field do not match!");return false;} 
		
		if(user_name=="")
		{alert("User Name can do not be Empty!");return false;} 
		
		/*if(document.getElementById("distance_variable").value=="")
		{alert("Distance Variable can not be Empty!");return false;}*/ 
               
		if(parseFloat(document.getElementById("distance_variable").value)>10.0 || parseFloat(document.getElementById("distance_variable").value)<0.1)
		{alert("Distance Variable should between (0.1 - 10.0) more than 10 km.Please Enter between (0.1 - 10.0)");return false;}
		
		
		if(ac_type==-1)
		{alert("Must Select Atleast one Permission option!");return false;}	
		
		/*if(perm_type==-1)
		{alert("Must Select Atleast one Admin Permission option!");return false;} */
		
    if(action_type=="add" || action_type=="edit")
		{
			var account_feature2 = "";
			var flag=0;
			
      var usertype = document.manage1.elements['user_type[]'];
      //alert("utype="+usertype);
      var usertype_len = usertype.length;
      var feature_str ="";
    	var post_feature_name = new Array();
    	var p=0;
    	var flag_feature_name=1;
    	var x=0,skip=false;
    	
    	if(usertype_len!=undefined)
    	{    		    		
        for(var i=0;i<(usertype_len);i++)
    		{	                                  
          if(i>0)
          {
            x++;
          }
          
          if(usertype[i].checked==true)
        	{
            var feature = document.manage1.elements['feature'+i+'[]'];            
            var feature_len = feature.length; 
            
            var post_feature = document.manage1.elements['post_feature'+i+'[]'];
            var post_feature_len = post_feature.length; 
               	
            flag_feature_name = 1;
            
            for(var j=0;j<(feature_len);j++)
            {	  
               if(feature[j].checked == true)
               { 
                  if(i>0)    // MATCH WITH NEXT ROW
                  {
                    skip=false;
                    for(var k=0; k<p; k++)
                    {                     
                       //alert("name="+post_feature_name[k]+", value="+post_feature[j].value+" ,i="+i+" ,j="+j+",k="+k)
                       if(post_feature_name[k] == post_feature[j].value)
                       {
                          skip=true;
                          //alert("skip");
                          break;
                       }
                    }
                  }
                  if(skip==false)
                  {
                    //alert("add");
                    post_feature_name[p++] = post_feature[j].value; 
                    feature_str = feature_str+post_feature[j].value+",";   // GET FEATURES
                  } 
                }                 
            }  // inner for
          }   // inner if
        }   // outer for
      }   // outer if
      else
      {
        if(usertype.checked==true)
        {
           var feature = document.manage1.elements['feature'+0+'[]'];            
           var feature_len = feature.length;
            
            var post_feature = document.manage1.elements['post_feature'+0+'[]'];
            var post_feature_len = post_feature.length;            
            if(feature_len!=undefined)
            { 
              
              for(var i=0;i<(feature_len);i++)
              {	  
                 if(feature[i].checked == true)
                 {
                     feature_str = feature_str+post_feature[i].value+",";   // GET FEATURES
                 }
              }
            }
            else
            {
                if(post_feature.checked == true)
                {
                     feature_str = feature_str+post_feature.value+",";   // GET FEATURES
                }
            }
        }
      }
      
      if(feature_str !="")
      flag=1; 
		}

		if(flag==0)
		{
			alert("Please Select Atleast One Account Feature");
			return false;
		}
		//alert("str="+feature_str);
		return feature_str;
	}  // if add or edit closed
}  

function IntegerAndDecimal(e,obj,isDecimal)
	{
		if ([e.keyCode||e.which]==8) //this is to allow backspace
		return true;

		if ([e.keyCode||e.which]==46) //this is to allow decimal point
		{
			if(isDecimal=='true')
			{
				var val = obj.value;
				if(val.indexOf(".") > -1)
				{
					e.returnValue = false;
					return false;
				}
				return true;
			}
			else
			{
				e.returnValue = false;
				return false;
			}
		}
		if ([e.keyCode||e.which] < 48 || [e.keyCode||e.which] > 57)
		e.preventDefault? e.preventDefault() : e.returnValue = false; 
	}
	
	function DisableRightClick(event)
	{
		//For mouse right click 
		if (event.button==2)
		{
			alert("Right Clicking not allowed!");
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
	//alert("vidobj:"+vehicle_id_obj.length);
	var i;
	var num1=0;   
	var veh_count=0;	
	var type_count=0; 
	var engine_type_value;	
	var ac_type_value;
	var sos_type_value;
	
	if(vehicle_id_obj.length!=undefined)
	{
		for(i=0;i<vehicle_id_obj.length;i++)
		{
			if(vehicle_id_obj[i].checked)
			{				
                var id_string='io_type'+vehicle_id_obj[i].value+'[]';		
				var io_type_obj=document.manage.elements[id_string];			
				var num2=0;	
				//alert("vehicleID="+vehicle_id_obj[i].value+"length="+io_type_obj.length);
				if(io_type_obj.length!=undefined)
				{
					for(j=0;j<io_type_obj.length;j++)
					{
						engine_type_value=0;	
						ac_type_value=0;
						sos_type_value=0;
						var io_name=io_type_obj[j].value;							
						var id_string_1=vehicle_id_obj[i].value+io_name;										
						var io_name_value=document.getElementById(id_string_1).value;					
						//alert("io_name_value="+io_name_value);
						if(io_name=="engine")
						{
							var engineType='engineType'+vehicle_id_obj[i].value;
							//alert("engineType="+engineType);
							//alert("engineType="+document.getElementById(engineType).checked);
							if(document.getElementById(engineType).checked==true)
							{
								engine_type_value=1;
							}
							var typeName='engine_type';
							var typeValue=engine_type_value;
						}
						if(io_name=="ac")
						{
							var acType='acType'+vehicle_id_obj[i].value;
							if(document.getElementById(acType).checked==true)
							{
								ac_type_value=1;
							}
							var typeName='ac_type';
							var typeValue=ac_type_value;						
						}
						if(io_name=="sos")
						{
							var sosType='sosType'+vehicle_id_obj[i].value;
							if(document.getElementById(sosType).checked==true)
							{
								sos_type_value=1;
							}
							var typeName='sos_type';
							var typeValue=sos_type_value;						
						}
						if(io_name_value!="select")
						{
							io_type[type_count]=io_name;
							io_type_value[type_count]=io_name_value;
							if(io_name=="engine" || io_name=="sos" || io_name=="ac")
							{
								
								final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":"+typeName+","+typeValue+":";
							}
							else
							{
								final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":";
							}
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
				else
				{
					engine_type_value=0;	
					ac_type_value=0;
					sos_type_value=0;
					var io_name=io_type_obj.value;							
					var id_string_1=vehicle_id_obj[i].value+io_name;										
					var io_name_value=document.getElementById(id_string_1).value;					
					//alert("io_name_value="+io_name_value);
					if(io_name=="engine")
					{
						var engineType='engineType'+vehicle_id_obj[i].value;
						//alert("engineType="+engineType);
						//alert("engineType="+document.getElementById(engineType).checked);
						if(document.getElementById(engineType).checked==true)
						{
							engine_type_value=1;
						}
						var typeName='engine_type';
						var typeValue=engine_type_value;
					}
					if(io_name=="ac")
					{
						var acType='acType'+vehicle_id_obj[i].value;
						if(document.getElementById(acType).checked==true)
						{
							ac_type_value=1;
						}
						var typeName='ac_type';
						var typeValue=ac_type_value;						
					}
					if(io_name=="sos")
					{
						var sosType='sosType'+vehicle_id_obj[i].value;
						if(document.getElementById(sosType).checked==true)
						{
							sos_type_value=1;
						}
						var typeName='sos_type';
						var typeValue=sos_type_value;						
					}
					if(io_name_value!="select")
					{
						io_type[type_count]=io_name;
						io_type_value[type_count]=io_name_value;
						if(io_name=="engine" || io_name=="sos" || io_name=="ac")
						{
							
							final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":"+typeName+","+typeValue+":";
						}
						else
						{
							final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":";
						}
						io_type_value1[type_count]=vehicle_id_obj[i].value+","+io_name_value; // io for validateion			
						num2 = 1;
						type_count++;
					}
					vehicle_id[veh_count] =  vehicle_id_obj[i].value;				
					final_type_and_value=final_type_and_value+"#";	
					final_vehicle_id=final_vehicle_id+vehicle_id[veh_count]+",";	
					veh_count++;				
					num1 = 1;
				}
			}
		}
	}
	else
	{
		if(vehicle_id_obj.checked)
		{
                    var id_string='io_type'+vehicle_id_obj.value+'[]';		
                    var io_type_obj=document.manage.elements[id_string];			
                    var num2=0;	
                    if(vehicle_id_obj.length!=undefined)
                    {
			for(j=0;j<io_type_obj.length;j++)
			{
				engine_type_value=0;	
				ac_type_value=0;
				sos_type_value=0;
				var io_name=io_type_obj[j].value;							
				var id_string_1=vehicle_id_obj.value+io_name;										
				var io_name_value=document.getElementById(id_string_1).value;
				
				if(io_name=="engine")
				{
                                    var engineType='engineType'+vehicle_id_ob.value;
                                    //alert("engineType="+engineType);
                                    if(document.getElementById(engineType).checked==true)
                                    {
                                        engine_type_value=1;
                                    }
                                    var typeName='engine_type';
                                    var typeValue=engine_type_value;
				}
				if(io_name=="ac")
				{
                                    var acType='acType'+vehicle_id_ob.value;
                                    if(document.getElementById(acType).checked==true)
                                    {
                                        ac_type_value=1;
                                    }
                                    var typeName='ac_type';
                                    var typeValue=ac_type_value;					
				}
				if(io_name=="sos")
				{	
                                    var sosType='sosType'+vehicle_id_ob.value;
                                    if(document.getElementById(sosType).checked==true)
                                    {
                                        sos_type_value=1;
                                    }
                                    var typeName='sos_type';
                                    var typeValue=sos_type_value;
				}
					//alert("io_name_value="+io_name_value);				
				if(io_name_value!="select")
				{
                                    io_type[type_count]=io_name;
                                    io_type_value[type_count]=io_name_value;
                                    if(io_name=="engine" || io_name=="sos" || io_name=="ac")
                                    {
                                        final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":"+typeName+","+typeValue+":";
                                    }
                                    else
                                    {
                                        final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":";
                                    }				
                                    io_type_value1[type_count]=vehicle_id_obj.value+","+io_name_value; // io for validateion			
                                    num2 = 2;
                                    type_count++;
				}				
			}
                    }
                    else
                    {
                        engine_type_value=0;	
                        ac_type_value=0;
                        sos_type_value=0;
                        var io_name=io_type_obj.value;							
                        var id_string_1=vehicle_id_obj.value+io_name;										
                        var io_name_value=document.getElementById(id_string_1).value;
                        if(io_name=="engine")
                        {
                            var engineType='engineType'+vehicle_id_obj.value;
                            //alert("engineType="+engineType);
                            if(document.getElementById(engineType).checked==true)
                            {
                                engine_type_value=1;
                            }
                            var typeName='engine_type';
                            var typeValue=engine_type_value;
                        }
                        if(io_name=="ac")
                        {
                            var acType='acType'+vehicle_id_ob.value;
                            if(document.getElementById(acType).checked==true)
                            {
                                ac_type_value=0;
                            }
                            var typeName='ac_type';
                            var typeValue=ac_type_value;					
                        }
                        if(io_name=="sos")
                        {	
                            var sosType='sosType'+vehicle_id_ob.value;
                            if(document.getElementById(sosType).checked==true)
                            {
                                sos_type_value=0;
                            }
                            var typeName='sos_type';
                            var typeValue=sos_type_value;
                        }
                                //alert("io_name_value="+io_name_value);				
                        if(io_name_value!="select")
                        {
                            io_type[type_count]=io_name;
                            io_type_value[type_count]=io_name_value;
                            if(io_name=="engine" || io_name=="sos" || io_name=="ac")
                            {
                                final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":"+typeName+","+typeValue+":";
                                //alert("finalTypeAndValue1="+final_type_and_value);
                            }
                            else
                            {
                                final_type_and_value=final_type_and_value+io_type[type_count]+","+io_type_value[type_count]+":";
                            }

                            io_type_value1[type_count]=vehicle_id_obj.value+","+io_name_value; // io for validateion			
                            num2 = 2;
                            type_count++;
                        }
                    }
                   
                    vehicle_id[veh_count] =  vehicle_id_obj.value;				
                    final_type_and_value=final_type_and_value+"#";	
                    final_vehicle_id=final_vehicle_id+vehicle_id[veh_count]+",";	
                    veh_count++;				
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
            showManageLoadingMessage();
            var poststr = "vehicle_ids=" +final_vehicle_id+
            "&types=" +final_type_and_value;
            //alert("poststr="+poststr);
            makePOSTRequest('src/php/action_final_io_assignment.htm', poststr);
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

function select_all_assigned_plant(obj)
{
	if(obj.all_plant.checked)
	{
		var i;
		var s = obj.elements['plant_id[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all_plant.checked==false)
	{
		var i;
		var s = obj.elements['plant_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}
function select_all_stations(obj)
{
	if(obj.all_station.checked)
	{
		var i;
		var s = obj.elements['station_id[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all_station.checked==false)
	{
		var i;
		var s = obj.elements['station_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

function select_all_stations2(obj)
{
	if(obj.all_station2.checked)
	{
		var i;
		var s = obj.elements['station_id2[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all_station2.checked==false)
	{
		var i;
		var s = obj.elements['station_id2[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

function select_all_visit_area(obj)
{
  if(obj.all_visit_area.checked)
	{
		var i;
		var s = obj.elements['visit_area_id[]'];
		//alert("s1="+s.length);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all_visit_area.checked==false)
	{
		var i;
		var s = obj.elements['visit_area_id[]'];
		//alert("s2="+s.length);
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
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
		  if(obj[i].checked==true)
		  {
			//var obj_1=obj[i].value;
			var user_id=obj[i].value;
			cnt++;
		  }	  
		}
	}
	else
	{
		if(obj.checked)
		{var user_id=obj.value;cnt++;}
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

function all_check(obj)
{
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['manage_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['manage_id[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

function IO_SelectAll(obj)
{
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['vehicle_id[]'];
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
			document.thisform.action="AddMilestoneAction.htm";
			document.thismyform.target="_blank";
			document.thismyform.submit();	
		}	
}

function manage_get_edit_latlng_fields(number,id)
{
    var poststr="number=" + encodeURI(number) +
    "&id=" + encodeURI( id );       
    makePOSTRequest('src/php/manage_get_edit_latlng_fields.htm', poststr);       
}

	
	function set_device_option(search_obj,select_obj)
	{
		var device_obj =document.manage1.lo;     /////////ro == right option ////
		if(device_obj.length!=undefined)
		{
			for(var i=0;i<device_obj.length;i++)
			{
				if(device_obj[i].checked==true)
				{
					//var vehicle_obj1=vehicle_obj[i].value.split(',');
						document.getElementById("enter_button").disabled=false;
					document.getElementById(search_obj).value=device_obj[i].value;
				}
			}
		}
		else
		{
			if(device_obj.checked==true)
			{
					//var device_obj1=device_obj.value.split(',');
					document.getElementById("enter_button").disabled=false;
					document.getElementById(search_obj).value=device_obj.value;
			}
		}
	}
	
	function set_vehicle_option(search_obj,select_obj)
	{
		var vehicle_obj =document.manage1.ro;     /////////ro == right option ////
		if(vehicle_obj.length!=undefined)
		{
			for(var i=0;i<vehicle_obj.length;i++)
			{
				if(vehicle_obj[i].checked==true)
				{
					var vehicle_obj1=vehicle_obj[i].value.split(',');
					document.getElementById(search_obj).value=vehicle_obj1[1];
						document.getElementById("enter_button").disabled=false;
				}
			}
		}
		else
		{
			if(vehicle_obj.checked==true)
			{
					var vehicle_obj1=vehicle_obj.value.split(',');
					document.getElementById(search_obj).value=vehicle_obj1[1];
						document.getElementById("enter_button").disabled=false;
			}
		}
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

 function action_manage_studentclass(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_studentclass_name=document.getElementById("add_student_class_name").value;
      var add_studentclass_section=document.getElementById("add_student_class_section").value;        
      var add_class_lat=document.getElementById("add_student_class_lat").value;  
      var add_class_lng=document.getElementById("add_student_class_lng").value;
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_studentclass_name=="") 
		  {
			alert("Please Enter Student Class"); 
			document.getElementById("add_studentclass_name").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&studentclass_name="+encodeURI(add_studentclass_name)+ 
						"&studentclass_section="+encodeURI(add_studentclass_section)+
						"&studentclass_lat="+encodeURI(add_class_lat) +
					  "&studentclass_lng="+encodeURI(add_class_lng) ;
		}
    }
    else if(action_type=="edit")                                               
    {      
      var studentclass_id=document.getElementById("studentclass_id").value;
      //alert(studentclass_id);
      if(studentclass_id=="select")
      {
        alert("Please Select Class"); 
        document.getElementById("studentclass_id").focus();
        return false;
      }       
      var edit_studentclass_name=document.getElementById("studentclass_name").value; 
      //alert(studentclass_name);
      if(edit_studentclass_name=="") 
      {
        alert("Please Enter Class Name"); 
        document.getElementById("studentclass_name").focus();
        return false;
      }
      var edit_studentclass_section=document.getElementById("studentclass_section").value;        
      var edit_class_lat=document.getElementById("class_lat").value;  
      var edit_class_lng=document.getElementById("class_lng").value;
		
      var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                    "&studentclass_id="+encodeURI( studentclass_id ) +
                    "&edit_studentclass_name="+encodeURI(edit_studentclass_name)+
                    "&edit_studentclass_section="+encodeURI(edit_studentclass_section)+
                    "&edit_class_lat="+encodeURI(edit_class_lat)+
                    "&edit_class_lng="+encodeURI(edit_class_lng);  
                      
    }
    else if(action_type=="delete")
    {
      var studentclass_id=document.getElementById("studentclass_id").value;
      //alert(studentclass_id);
      if(studentclass_id=="select")
      {
        alert("Please Select Class"); 
        document.getElementById("studentclass_id").focus();
        return false;
      }   
      var txt="Are You Sure You Want To Delete Card";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&studentclass_id=" + encodeURI(studentclass_id);  
    }

    makePOSTRequest('src/php/action_manage_studentclass.htm', poststr);
 } 
 
 function show_studentclass_record(obj)
 { // alert("hello");
    var studentclass_id=document.getElementById("studentclass_id").value;
    //alert("dfdf "+studentclass_id);
    
    if(studentclass_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "studentclass_id=" + encodeURI( document.getElementById("studentclass_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.htm', poststr);
    } 
 }
//==end of class module//
 function show_busdriver_record(obj)
 { // alert("hello");
    var drivername_id=document.getElementById("drivername_id").value;
    alert("dfdf "+drivername_id);
    
    if(drivername_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "drivername_id=" + encodeURI( document.getElementById("drivername_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.htm', poststr);
    } 
 }
 
 function show_student_classwise(obj)
 { // alert("hello");
    var student_classwise_id=document.getElementById("student_classwise_id").value;
    alert("dfdf "+student_classwise_id);
    
    if(student_classwise_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "student_classwise_id=" + encodeURI( document.getElementById("student_classwise_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.htm', poststr);
    } 
 }
 
 
 function removeRow(tbl,row,rows_count) 
  { 
    //alert("rowIndex ="+row.rowIndex);
    var table = document.getElementById(tbl);
    var busstoplist=document.getElementById("busstops").value;
    var name="busstop"+rows_count;
    var remove_busstop="#"+document.getElementById(name).value;
    var new_list = busstoplist.replace(remove_busstop , '');
    document.getElementById("busstops").value= new_list;
    load_busstops();
    try { 
      
      table.deleteRow(row.rowIndex); 
    } catch (ex) { 
      alert(ex); 
    } 
  } 
	
  function get_latlng_fields(number)
  {
    var poststr= "number="+number;
  	makePOSTRequest('src/php/manage_get_latlng_fields.htm', poststr);   
  }
  
  function action_manage_milestone(action_type)
  {
      var obj=document.manage1;
			if(action_type == "add")
			{			
        var obj1=obj.manage_id;       
        var result=radio_selection(obj1);
      
        if(result!=false)
        {
          var milestone_result = milestone_validation(obj);          
          if(milestone_result!=false)
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
            "&local_group_id=" + encodeURI(result)+							
          	"&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
          	"&ms_type=" + encodeURI(document.getElementById("ms_type").value )+							
          	"&points="+points;
          }
         }       
      }             
			
      else if(action_type == "edit")
			{
			 if(obj.ms_id.value=="select")
			   {
             alert("Please Select Milstone")
             obj.ms_id.focus();
         }
         else
         {
    			  var result = milestone_validation(obj); 			  	
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
      							"&ms_id=" + encodeURI( document.getElementById("ms_id").value )+
                    "&ms_name=" + encodeURI( document.getElementById("ms_name").value )+
                	  "&ms_type=" + encodeURI(document.getElementById("ms_type").value )+												
      							"&points="+points;
            }
          }       
      }
			
      else if(action_type == "delete")
			{
			   if(obj.ms_id.value=="select")
			   {
             alert("Please Select Milstone")
             obj.ms_id.focus();
         }
         else
         {
			    txt="Are You Sure You Want To Delete this One";
					if(!confirm(txt))
					{return false;}
					else
					{
							var poststr="action_type=" + encodeURI(action_type)+
							              "&ms_id=" + encodeURI( document.getElementById("ms_id").value );
					} 
         }              
			}		        			
			//alert("poststr="+poststr);	
			makePOSTRequest('src/php/action_manage_milestone.htm', poststr);     
  }  
  
  function milestone_validation(obj)
  {
		if(obj.ms_name.value == "")
		{
			alert("Please Enter Mile Stone Name");
			obj.ms_name.focus();
			return false;
		}
		var thisval = obj.lat_lng.value; 	
		for(var i=0;i<thisval;i++)
		{
			if(document.getElementById("lat"+i).value=="")
			{
				alert("Please Enter Lat/Long");
				document.getElementById("lat"+i).focus(); 
				return false;
			}     			
		}
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

function show_intermediate_halt_tr(landmark_id)
{
	if(landmark_id=="select")
	{
		document.getElementById("intermediate_halt_tr").style.display="none";
		document.getElementById("intermediate_check_halt_time").style.display="none";
		document.manage1.intermediate_halt_numbers[0].selected=true;		
	}
	else
	{
		document.getElementById("intermediate_halt_tr").style.display="";
	}
}
	
 function action_manage_schedule(action_type)
 {  
    //alert("action_type1="+action_type);	
	if(action_type=="add" || action_type=="edit") 
	{
		var location_name=document.getElementById("location_name").value;     
		var geo_point=document.getElementById("geo_point").value;  
	}
    if(action_type=="add") 
    {        
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
		//alert("result="+result);      
  		if(result!=false)
  		{
			var form_validation=location_form_validation(location_name,geo_point);
			//alert("form_validation="+form_validation);
			if(form_validation!=false)
			{
				var poststr = "action_type=" + action_type +
							  "&account_id_local=" + encodeURI(result) +		 
							  "&location_name=" + encodeURI(location_name) +
							  "&geo_point=" + encodeURI(geo_point); 
			}
		}                          
    }
    else if(action_type=="edit")
    {
	  var account_id_local=document.getElementById("account_id_local").value;
      var location_id=document.getElementById("location_id").value; 
	  var form_validation=location_form_validation(location_name,geo_point);
		//alert("form_validation="+form_validation);
		if(form_validation!=false)
		{
		 var poststr = "action_type=" + action_type +
					   "&location_id=" +location_id+
					   "&account_id_local=" +account_id_local+
					   "&location_name="+location_name+
					   "&geo_point="+geo_point;	
		}     			 
    }
  	else if(action_type=="delete")
  	{
  		var location_id=document.getElementById("location_id").value
  		if(location_id=="select") 
  		{
  			alert("Please Select Location Name");        
  			document.getElementById("location_id").focus();
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
  			"&location_id=" + encodeURI(location_id);    
  		}
	} 
  	else if(action_type=="assign")
  	{
		var nonpoi_halt_hr_1;
		var nonpoi_halt_min_1;
		var form_obj1=document.manage1.vehicle_id;
		var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
		if(radio_result!=false)
		{
			if(document.getElementById("date1").value=="")
			{
			       alert("Please Enter Date From");			       
			       document.getElementById("date1").focus();
			       return false;
			}			
			if(document.getElementById("date2").value=="")
			{
			       alert("Please Enter Date To");			       
			       document.getElementById("date2").focus();
			       return false;
			}
			if(document.manage1.day_check.checked==true)
			{
				var table = document.getElementById("dataTable");
				var rowCount = table.rows.length;
				//var row = table.insertRow(rowCount);
				var colCount = table.rows[0].cells.length;
				
				var arr=new Array();
				var day_str="";
				for(var i=0; i<rowCount; i++)
				{
					if(table.rows[i].cells[2].getElementsByTagName('select')[0].value=="select")
					{
						alert("Please select day.");
						return false;
						break;
					}
					day_str=day_str+table.rows[i].cells[2].getElementsByTagName('select')[0].value+",";
					arr[i]=table.rows[i].cells[2].getElementsByTagName('select')[0].value;	
					//alert("1="+table.rows[i].cells[2].getElementsByTagName('select')[0].value);
				}
				
				var day_exist_flag=similar_value_arr(arr);				
				if(day_exist_flag==1)
				{
					alert("Day can not be same");
					return false;				
				}
				var by_day=1;
			}			
			else if(document.manage1.day_check.checked==false)
			{
				day_str="";
				var by_day=0;
			}
			var flag=0;
			if(document.getElementById("min_ot_hr").value!="")
			{
				var min_ot_hr=IsNumeric(document.getElementById("min_ot_hr").value,"min_ot_hr");				
				if(min_ot_hr==true)
				{
					var min_ot_hr_1=min_max_hr_minute_validation(document.getElementById("min_ot_hr").value,"Min operation","hr","min_ot_hr");
					if(min_ot_hr_1!=false)
					{
						min_ot_hr_1=document.getElementById("min_ot_hr").value;													
					}
					else
					{
						return false;
					}			
				}
				else
				{
					return false;
				}
			}				
			if(document.getElementById("min_ot_minute").value!="")
			{
				var min_ot_minute=IsNumeric(document.getElementById("min_ot_minute").value,"min_ot_minute");
				if(min_ot_minute==true)
				{
					var min_ot_minute_1=min_max_hr_minute_validation(document.getElementById("min_ot_minute").value,"Min operation","min","min_ot_minute");
					if(min_ot_minute_1!=false)
					{
						min_ot_minute_1=document.getElementById("min_ot_minute").value;												
					}
					else
					{
						return false;
					}			
				}
			}			
			if(document.getElementById("max_ot_hr").value!="")
			{				
				var max_ot_hr=IsNumeric(document.getElementById("max_ot_hr").value,"max_ot_hr");				
				if(max_ot_hr==true)
				{
					var max_ot_hr_1=min_max_hr_minute_validation(document.getElementById("max_ot_hr").value,"Max operation","hr","max_ot_hr");
					if(max_ot_hr_1!=false)
					{
						flag=1;
						max_ot_hr_1=document.getElementById("max_ot_hr").value;					
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}				
			
			if(document.getElementById("max_ot_minute").value!="")
			{
				var max_ot_minute=IsNumeric(document.getElementById("max_ot_minute").value,"max_ot_minute");
				if(max_ot_minute==true)
				{
					var max_ot_minute_1=min_max_hr_minute_validation(document.getElementById("max_ot_minute").value,"Max operation","min","max_ot_minute");
					if(max_ot_minute_1!=false)
					{
						max_ot_minute_1=document.getElementById("max_ot_minute").value;	
						flag=1;							
					}
					else
					{
						return false;
					}			
				}
				else
				{
					return false;
				}
			}
			
			if(parseInt(max_ot_hr_1)==parseInt(min_ot_hr_1))
			{
				if(max_ot_minute_1<min_ot_minute_1)
				{
					alert("Max operation minute should not less than Min operation minute");
					document.getElementById("max_ot_minute").focus();
					return false;
				}		
			}
			
			if (parseInt(max_ot_hr_1)<parseInt(min_ot_hr_1))
			{
				alert("Max operation Hour should not less than Min operation hour");
				document.getElementById("max_ot_hr").focus();
				return false;
			}
			
			/*if(document.getElementById("rest_time_hr").value!="")
			{
				var rest_time_hr=IsNumeric(document.getElementById("rest_time_hr").value,"rest_time_hr");
				if(rest_time_hr==false)
				{
					return false;			
				}				
			}
			
			if(document.getElementById("rest_time_min").value!="")
			{
				var rest_time_min=IsNumeric(document.getElementById("rest_time_min").value,"rest_time_hr");
				if(rest_time_min==false)
				{
					return false;		
				}				
			}*/
			
			if(document.getElementById("min_distance").value!="")
			{
				var min_distance=IsNumeric(document.getElementById("min_distance").value,"min_distance");
				if(min_distance==true)
				{
					var min_distance_1=document.getElementById("min_distance").value;
				}
				else
				{
					return false;
				}
			}
			
			if(document.getElementById("max_distance").value!="")
			{
				var max_distance=IsNumeric(document.getElementById("max_distance").value,"max_distance");
				if(max_distance==true)
				{
					var max_distance_1=document.getElementById("max_distance").value;
				}
				else
				{
					return false;
				}
				//alert("min_distance_1="+min_distance_1+"max_distance_1="+max_distance_1);
				if (parseInt(min_distance_1) > parseInt(max_distance_1))
				{
					//alert("1");
					alert("Maximum distance should not less than Minimum distance");
					document.getElementById("max_distance").focus();
					return false;
				}
			}
			
			var halt_numbers=document.getElementById("halt_numbers").value;
			if (halt_numbers!="select")
			{
				var total_halt_no=document.getElementById("total_halt_no").value;
				var validation_string="";
				for(var i=1;i<=total_halt_no;i++)
				{					
					validation_string=validation_string+"textfield:"+"min_halt_time_hr_"+i+":Minimum halt hour,textfield:"+
									"min_halt_time_min_"+i+":Minimum halt minute,textfield:"+"max_halt_time_hr_"+i+":Maximum halt hour,"+
									"textfield:max_halt_time_min_"+i+":Maximum halt minute,select:"+"min_max_halt_location_"+i+
									":Location,";
				}
				var validation_string_1=validation_string.slice(0, -1);
				var result_wockhardt_for=schedule_form_validation(validation_string_1);
				if(result_wockhardt_for!=false)
				{
					var min_halt_time="";
					var max_halt_time="";
					var min_max_halt_locations="";
					var location_arr=new Array();
					for(var i=1;i<=total_halt_no;i++)
					{						
						var min_halt_time_hr_1="";
						var max_halt_time_hr_1="";
						var min_halt_time_min_1="";
						var max_halt_time_min_1="";
													
						var min_halt_time_hr=IsNumeric(document.getElementById("min_halt_time_hr_"+i).value,"min_halt_time_hr_"+i);
						if(min_halt_time_hr==true)
						{
							var min_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("min_halt_time_hr_"+i).value,"Minimum halt hour","hr","min_halt_time_hr_"+i);
							if(min_halt_time_hr_1!=false)
							{
								min_halt_time_hr_1=document.getElementById("min_halt_time_hr_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}

						var min_halt_time_min=IsNumeric(document.getElementById("min_halt_time_min_"+i).value,"min_halt_time_min_"+i);
						if(min_halt_time_min==true)
						{
							var min_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("min_halt_time_min_"+i).value,"Minimum halt hour","min","min_halt_time_min_"+i);
							if(min_halt_time_min_1!=false)
							{
								min_halt_time_min_1=document.getElementById("min_halt_time_min_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}
						var max_halt_time_hr=IsNumeric(document.getElementById("max_halt_time_hr_"+i).value,"max_halt_time_hr_"+i);
						if(max_halt_time_hr==true)
						{
							var max_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("max_halt_time_hr_"+i).value,"Minimum halt hour","hr","max_halt_time_hr_"+i);
							if(max_halt_time_hr_1!=false)
							{
								max_halt_time_hr_1=document.getElementById("max_halt_time_hr_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}
						var max_halt_time_min=IsNumeric(document.getElementById("max_halt_time_min_"+i).value,"max_halt_time_min_"+i);
						if(max_halt_time_min==true)
						{
							var max_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("max_halt_time_min_"+i).value,"Minimum halt hour","min","max_halt_time_min_"+i);
							if(max_halt_time_min_1!=false)
							{
								max_halt_time_min_1=document.getElementById("max_halt_time_min_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}
						//alert("max_halt_time_min_1="+max_halt_time_min_1+"min_halt_time_min_1="+min_halt_time_min_1);
						if(parseInt(max_halt_time_hr_1)==parseInt(min_halt_time_hr_1))
						{						
							if(parseInt(max_halt_time_min_1)<parseInt(min_halt_time_min_1))
							{
								alert("Maximum halt Minute should not less than Minimum halt minute");
								document.getElementById("max_halt_time_min_"+i).focus();
								return false;
								break;
							}
						}
						if(parseInt(max_halt_time_hr_1)<parseInt(min_halt_time_hr_1))
						{
							alert("Maximum halt Hour should not less than Min halt hour");
							document.getElementById("max_halt_time_hr_"+i).focus();
							return false;
							break;
						}
						location_arr[i]=document.getElementById("min_max_halt_location_"+i).value;
						min_max_halt_locations=min_max_halt_locations+document.getElementById("min_max_halt_location_"+i).value+",";
						min_halt_time=min_halt_time+min_halt_time_hr_1+":"+min_halt_time_min_1+",";
						max_halt_time=max_halt_time+max_halt_time_hr_1+":"+max_halt_time_min_1+",";
					}
					var location_exist_flag=similar_value_arr(location_arr);
					if(location_exist_flag==1)
					{
						alert("Laction Should Not be be same.");
						return false;				
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				var min_max_halt_locations="";
				var min_halt_time="";
				var max_halt_time="";
			}
			
			if(document.getElementById("base_station").value=="select")
			{
				var base_station="";
				var intermediate_time="";
			}
			else
			{
				var base_station=document.getElementById("base_station").value;
			}	
			
			if(document.getElementById("base_station").value!="select")
			{
				var intermediate_halt_numbers=document.getElementById("intermediate_halt_numbers").value;
				if(intermediate_halt_numbers!="select")
				{
					var total_intermediate_halt_no=document.getElementById("total_intermediate_halt_no").value;
					var intermediate_validation_string="";
					for(var i=1;i<=total_intermediate_halt_no;i++)
					{
						//alert("in for");
						intermediate_validation_string=intermediate_validation_string+"textfield:"+"intermediate_min_halt_time_hr_"+i+":Intermediate minimum halt hour,textfield:"+
										"intermediate_min_halt_time_min_"+i+":Intermediate minimum halt minute,";
					}
					//alert("intermediate_validation_string="+intermediate_validation_string);
					var intermediate_validation_string_1=intermediate_validation_string.slice(0, -1);
					var result_wockhardt_for_1=schedule_form_validation(intermediate_validation_string_1);
					//alert("A="+result_wockhardt_for_1)
					if(result_wockhardt_for_1!=false)
					{
						var intermediate_halt_hr=new Array();
						var intermediate_halt_min=new Array();
						var tmp1="";
						var tmp2="";
						var intermediate_time="";
						if(total_intermediate_halt_no==1)
						{
							//alert("in one");
							var intermediate_min_halt_time_hr=IsNumeric(document.getElementById("intermediate_min_halt_time_hr_1").value,"intermediate_min_halt_time_hr_1");
							if(intermediate_min_halt_time_hr==true)
							{
								var intermediate_min_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_hr_1").value,"Minimum halt hour","hr","intermediate_min_halt_time_hr_1");
								if(intermediate_min_halt_time_hr_1!=false)
								{
									intermediate_min_halt_time_hr_1=document.getElementById("intermediate_min_halt_time_hr_1").value;	
								}
								else
								{
									return false;
								}
							}
							else
							{
								return false;
							}
							var intermediate_min_halt_time_min=IsNumeric(document.getElementById("intermediate_min_halt_time_min_1").value,"intermediate_min_halt_time_min_1");
							if(intermediate_min_halt_time_min==true)
							{
								var intermediate_min_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_min_1").value,"Minimum halt hour","min","intermediate_min_halt_time_min_1");
								if(intermediate_min_halt_time_min_1!=false)
								{
									intermediate_min_halt_time_min_1=document.getElementById("intermediate_min_halt_time_min_1").value;	
								}
								else
								{
									return false;
								}
								intermediate_time=intermediate_time+intermediate_min_halt_time_hr_1+":"+intermediate_min_halt_time_min_1+",";							
							}
							else
							{
								return false;
							}
						}
						else
						{
							for(var i=1;i<=total_intermediate_halt_no;i++)
							{
								var intermediate_min_halt_time_hr_1="";
								var intermediate_min_halt_time_min_1="";
								
								var intermediate_min_halt_time_hr=IsNumeric(document.getElementById("intermediate_min_halt_time_hr_"+i).value,"intermediate_min_halt_time_hr_"+i);
								//alert("intermediate_min_halt_time_hr1="+intermediate_min_halt_time_hr);
								if(intermediate_min_halt_time_hr==true)
								{
									var intermediate_min_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_hr_"+i).value,"Minimum halt hour","hr","intermediate_min_halt_time_hr_"+i);
									if(intermediate_min_halt_time_hr_1!=false)
									{
										intermediate_min_halt_time_hr_1=document.getElementById("intermediate_min_halt_time_hr_"+i).value;
										intermediate_halt_hr[i]=intermediate_min_halt_time_hr_1;
										//var tmp1=i+1;
										//alert("tem="+tmp1+"total_halt="+total_intermediate_halt_no);
										if(tmp1<=(total_intermediate_halt_no-1))
										{
											var tmp1=i+1;
											//alert("tmp1="+tmp1);
											intermediate_halt_hr[i+1]=document.getElementById("intermediate_min_halt_time_hr_"+tmp1).value;
										}
									}
									else
									{
										return false;
										break;
									}
									//alert(intermediate_halt_hr[i]);
								}
								else
								{
									return false;
									break;
								}

								var intermediate_min_halt_time_min=IsNumeric(document.getElementById("intermediate_min_halt_time_min_"+i).value,"intermediate_min_halt_time_min_"+i);
								//alert("intermediate_min_halt_time_min2="+intermediate_min_halt_time_min);
								if(intermediate_min_halt_time_min==true)
								{
									var intermediate_min_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_min_"+i).value,"Minimum halt minute","min","intermediate_min_halt_time_min_"+i);
									if(intermediate_min_halt_time_min_1!=false)
									{
										intermediate_min_halt_time_min_1=document.getElementById("intermediate_min_halt_time_min_"+i).value;
										intermediate_halt_min[i]=intermediate_min_halt_time_min_1;								
										if(tmp2<=(total_intermediate_halt_no-1))
										{
											var tmp2=i+1;
											//alert("tmp2="+tmp2);									
											intermediate_halt_min[i+1]=document.getElementById("intermediate_min_halt_time_min_"+tmp2).value;
										}
									}
									else
									{
										return false;
										break;
									}
								}
								else
								{
									return false;
									break;
								}
								//alert("hr_val1="+intermediate_halt_hr[i]+"hr_val2="+intermediate_halt_hr[i+1]);
								if(intermediate_halt_hr[i]==intermediate_halt_hr[i+1])
								{
									//alert("min_val1="+intermediate_halt_min[i]+"min_val2="+intermediate_halt_min[i+1]);
									if(parseInt(intermediate_halt_min[i+1])<parseInt(intermediate_halt_min[i]))
									{
										alert("Maximum halt Minute should not less than Minimum halt minute");
										document.getElementById("intermediate_min_halt_time_min_"+tmp1).focus();
										return false;
										break;
									}
								}
								//alert("hr_val1="+intermediate_halt_hr[i]+"hr_val2="+intermediate_halt_hr[i+1]);
								if(parseInt(intermediate_halt_hr[i+1])<parseInt(intermediate_halt_hr[i]))
								{
									//alert("in if");
									alert("Maximum halt Hour should not less than Min halt hour");
									document.getElementById("intermediate_min_halt_time_hr_"+tmp2).focus();
									return false;
									break;
								}
								intermediate_time=intermediate_time+intermediate_halt_hr[i]+":"+intermediate_halt_min[i]+",";
							}
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					var intermediate_time="";
				}
			}
			if(document.getElementById("nonpoi_halt_hr").value!="")
			{				
				var nonpoi_halt_hr=IsNumeric(document.getElementById("nonpoi_halt_hr").value,"nonpoi_halt_hr");				
				if(nonpoi_halt_hr==true)
				{
					nonpoi_halt_hr_1=min_max_hr_minute_validation(document.getElementById("nonpoi_halt_hr").value,"Max Non Poi Halt Time","hr","nonpoi_halt_hr");
					if(nonpoi_halt_hr_1!=false)
					{
						flag=1;
						nonpoi_halt_hr_1=document.getElementById("nonpoi_halt_hr").value;					
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			if(document.getElementById("nonpoi_halt_min").value!="")
			{
				var nonpoi_halt_min=IsNumeric(document.getElementById("nonpoi_halt_min").value,"nonpoi_halt_min");
				if(nonpoi_halt_min==true)
				{
					nonpoi_halt_min_1=min_max_hr_minute_validation(document.getElementById("nonpoi_halt_min").value,"Non Poi Halt Time","min","nonpoi_halt_min");
					if(nonpoi_halt_min_1!=false)
					{
						nonpoi_halt_min_1=document.getElementById("nonpoi_halt_min").value;	
						flag=1;							
					}
					else
					{
						return false;
					}			
				}
				else
				{
					return false;
				}
			}
			
		}
		else
		{
			return false;
		}
		
		var poststr="action_type="+encodeURI(action_type)+
			"&vehicle_id="+radio_result+ 
			"&date_from="+document.getElementById("date1").value+
			"&date_to="+document.getElementById("date2").value+
			"&by_day="+by_day+
			"&day_str="+day_str+
			"&min_operation_time="+min_ot_hr_1+":"+min_ot_minute_1+
			"&max_operation_time="+max_ot_hr_1+":"+max_ot_minute_1+
			//"&allow_rest_time="+document.getElementById("rest_time_hr").value+":"+document.getElementById("rest_time_min").value+			
			"&minimum_distance="+document.getElementById("min_distance").value+
			"&maximum_distance="+document.getElementById("max_distance").value+
			"&min_max_halt_locations="+min_max_halt_locations+  
			"&min_halt_time="+min_halt_time+
			"&max_halt_time="+max_halt_time+
			"&base_station_id="+base_station+
			"&intermediate_time="+intermediate_time+
			"&nonpoi_halt_time="+nonpoi_halt_hr_1+":"+nonpoi_halt_min_1;			
  	}
  	else if(action_type=="deassign")
  	{
  		var form_obj=document.manage1.elements['vid_string[]'];
  		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
      			
  		if(checkbox_result!=false)
  		{		
  			var poststr="action_type="+encodeURI(action_type)+
  					"&vid_string="+checkbox_result;					// vehicle id also includes station ids with : separator	
  		}			
  	}
	//alert("poststr="+poststr);
	showManageLoadingMessage();
	makePOSTRequest('src/php/action_manage_schedule.htm', poststr);
 }
 
function similar_value_arr(arr)
{
	var sorted_arr = arr.sort(); // find similar kind of value in an array. 		
	var exist_flag=0;
	for (var i = 0; i < arr.length - 1; i++) 
	{
		if (sorted_arr[i + 1] == sorted_arr[i]) 
		{
			exist_flag=1;
			return exist_flag;
		}
	}
}
				
 function schedule_form_validation(validate_option)
{
	//alert("validation_option_length="+validate_option);
	var validation_option_length=validate_option.split(",");
	//alert("validation_option_length="+validation_option_length.length);
	var flag=0;
	for(var i=0;i<validation_option_length.length;i++)
	{
		var exact_validation_option=validation_option_length[i].split(":");
		if(exact_validation_option[0]=="textfield")
		{					
			//alert("a="+document.getElementById(exact_validation_option[1]).value+"b="+exact_validation_option[1]);
			if(document.getElementById(exact_validation_option[1]).value=="")
			{
				alert("Plesae Enter "+exact_validation_option[2]);
				document.getElementById(exact_validation_option[1]).focus();
				//alert("exact_validation_option="+exact_validation_option[1]+"exact_validation_option="+exact_validation_option[1]);
				return false;
				flag=1;
				break;
			}
		}
		else if(exact_validation_option[0]=="select")
		{
			if(document.getElementById(exact_validation_option[1]).value=="select")
			{
				alert("Plesae Select "+exact_validation_option[2]);
				document.getElementById(exact_validation_option[1]).focus();
				//alert("exact_validation_option="+exact_validation_option[1]+"exact_validation_option="+exact_validation_option[1]);
				return false;
				flag=1;
				break;
			}
		}
	}
	if(flag==0)
	{
		return true;
	}
}
 
function min_max_hr_minute_validation(condition_value,alert_string,time_format,text_location_id)
{
	//alert("condition_value="+condition_value+"alert_string="+alert_string+"time_format="+time_format+"text_location_id="+text_location_id);
	if(time_format=="hr")
	{
		if(condition_value>23 || condition_value<0)
		{						
			alert(alert_string+" hour should not more than 24 and not less than 1");
			document.getElementById(text_location_id).focus();
			return false;
		}
	}
	else if(time_format=="min")
	{
		if(condition_value>59 || condition_value<0)
		{						
			alert(alert_string+" minute should not more than 60 and not less than 1");
			document.getElementById(text_location_id).focus();
			return false;
		}
	}
}
////////////////// 
function IsNumeric(input,text_location_id)
{
	if(((input - 0) == input && input.length > 0)==false)
	{
		alert("Please Enter Number Only");
		document.getElementById(text_location_id).focus();
		return false;
	}
	else
	{
		return true;
	} 
}
function IsNumericA(input,text_location_id)
{
	if(((input - 0) == input && input.length > 0)==false)
	{
		//alert("Please Enter Number Only");
		document.getElementById(text_location_id).value="";
		//document.getElementById(text_location_id).focus();
		return false;
	}
	else
	{
		return true;
	} 
}

function location_form_validation(location_name,geo_point)
{
	if(location_name=="") 
	{
	  alert("Please Enter Location Name"); 
	  document.getElementById("location_name").focus();
	  return false;
	}
	else if(geo_point=="") 
	{
	  alert("Please Draw Geo Point");
	  document.getElementById("geo_point").focus();
	  return false;
	}		
}

 function show_selected_halt_no(no_of_halt)
 {
	var poststr ="no_of_halt="+no_of_halt+
				 "&account_id_local="+document.getElementById("account_id_hidden").value; 
	//alert("poststr="+poststr);
	 makePOSTRequest('src/php/manage_min_max_halt.htm', poststr);
 
 }
 
  function show_selected_intermediate_halt_no(no_of_intermediate_halt)
 {
	var poststr ="no_of_intermediate_halt="+no_of_intermediate_halt; 
	//alert("poststr="+poststr);
	 makePOSTRequest('src/php/manage_intermediate_check_halt_time.htm', poststr); 
 }

function show_location_coord(obj)
 {
    var location_id=document.getElementById("location_id").value;
    if(location_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "location_id=" + encodeURI( document.getElementById("location_id").value);
      //alert("postr="+poststr);
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }  
 }



	var locationMarkers=new Array();
	function manage_location(param)
	{		
		if(GBrowserIsCompatible())
		{	
			var lat_lng=document.getElementById("geo_point").value; 
			if(lat_lng!="")  
			{     
				location_map_part(lat_lng);
				document.getElementById("prev_geo_point").value=lat_lng;
				lat_lng=lat_lng.split(",");   
				//var point=new GLatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));
				var point=new google.maps.LatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));
				var lat=point.lat();  
				var lng=point.lng();       
				
				var icon1 = {
								  url: 'images/landmark.png',
								  size: new google.maps.Size(10, 10),
								scaledSize: new google.maps.Size(10, 10)
								};
				var marker = new google.maps.Marker({position: point, icon:icon1, map: map});
				//var marker = new GMarker(point, lnmark);
				//alert(marker);
				document.getElementById("geo_point").value=lat+","+lng;
				//alert(lat+" "+lng);
				var contentString='<div style="height:10px"></div><table>'
					 +'<tr><td style="font-size:11px">('+lat+', '+lng+')</td></tr>'               
					 +'</table><div style="height:10px"></div>'			 					
						   +'<center><input type="button" value="OK" onclick="javascript:return save_location_details(\'geo_point\')" /></center>';
			    //alert(contentString);
				var infowindow = new google.maps.InfoWindow();
				infowindow.setContent(contentString);
				infowindow.open(map, marker);
				locationMarkers.push(marker);
			
			}
			else
			{
				//alert("in else");
				var lat_lng="";        
				location_map_part(lat_lng);
				//map.clearOverlays();
				deleteOverlays();
			}

			var lastmarker; 		
			google.maps.event.addListener(map,"click",function(event)			
			{
			
				deleteOverlays();
			var icon1 = {
								  url: 'images/landmark.png',
								  size: new google.maps.Size(10, 10),
								scaledSize: new google.maps.Size(10, 10)
								};
			marker = new google.maps.Marker({position: event.latLng, icon:icon1, map: map});
			document.getElementById("geo_point").value=event.latLng.lat()+","+event.latLng.lng();
			var contentString='<div style="height:10px"></div><table>'
                 +'<tr><td style="font-size:11px">('+event.latLng.lat()+', '+event.latLng.lng()+')</td></tr>'               
                 +'</table><div style="height:10px"></div>'			 					
			           +'<center><input type="button" value="OK" onclick="javascript:return save_location_details(\'geo_point\')" /></center>';
		
			var infowindow = new google.maps.InfoWindow();
			infowindow.setContent(contentString);
			infowindow.open(map, marker);
			locationMarkers.push(marker);
				
			});     
		}
		else 
		{
			alert("Sorry, the Google Maps API is not compatible with this browser");
		}
		function deleteOverlays() 
		{
			for (var i = 0; i < locationMarkers.length; i++) 
			{
				locationMarkers[i].setMap(null);
			}
		}
	}
	
	function location_map_part(lat_lng)
	{
		//if(lat_lng=="")
		{
			var zoom=5;
		}
		/*else
		{
			var zoom=parseInt(document.getElementById("zoom_level").value);
		}*/
		/*map = new GMap2(document.getElementById("location_map"));	
		map.setCenter(new GLatLng(22.755920681486405,78.2666015625), zoom);
		map.setUIToDefault();
		map.enableGoogleBar();
		alert('s'+map);*/
		map = new google.maps.Map(document.getElementById('location_map'), 
		{
		  zoom: 5,
		  center: new google.maps.LatLng(22.755920681486405, 78.2666015625),
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  disableDefaultUI: true,
		  zoomControl: true
		});
	}
	
	
	function createLocationInputMarker(point) 
{
    var lat_1=point.lat();
    var lng_1=point.lng();  
     alert('ss'); 
    document.getElementById("geo_point").value=lat_1+","+lng_1;   
   
    var iwform = '<div style="height:10px"></div><table>'
                 +'<tr><td style="font-size:11px">('+lat_1+', '+lng_1+')</td></tr>'               
                 +'</table><div style="height:10px"></div>'			 					
			           +'<center><input type="button" value="OK" onclick="javascript:return save_location_details(\'geo_point\')" /></center>';
			           
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

function save_location_details(point_id)
 {
	//alert(point_id);
    var coord_point=document.getElementById("geo_point").value;
    if(coord_point=="")
    {
      alert("Please Enter Points");
      return false;
    }
    else
    {
      div_close_block();
      document.getElementById("geo_point").value =  coord_point;
    }
    return false;
 }
 
 function close_location_div(close_pararm)
{
   var txt="Are You Sure You Want To Close Without Saving Points";
   if(!confirm(txt))
   {
     return false; 
   }
   document.getElementById("geo_point").value="";    /////// at the time of add landmark
   document.getElementById("geo_point").value=document.getElementById("prev_geo_point").value;  ///at the time of edit landmark
   div_close_block();
}


  function select_all_vehicle(obj)
  {
  	if(obj.all.checked)
  	{
  		var i;
  		var s = obj.elements['vehicle_id[]'];
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

 function action_manage_vtrip(action_type)
 {    
	if(action_type=="assign")
	{
		var form_obj1=document.manage1.station_id;
		var radio_result=radio_selection(form_obj1);  //////////validate and get geofence

		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false && radio_result!=false)
		{
			//var form_obj1=document.manage1.vehicle_id;
			//var radio_result=radio_selection(form_obj1);  //////////validate and get geofence

			var poststr="action_type="+encodeURI(action_type ) + 
					"&station_id="+radio_result + 
                    "&vehicle_ids=" +checkbox_result;
      //alert(poststr);				
		}			
	}
	else if(action_type=="deassign")
	{
		var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
    			
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result;					// vehicle id also includes station ids with : separator	
		}			
	}
	  //alert("poststr="+poststr);
	  showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_vtrip.htm', poststr);
 } 
  
	 function action_manage_upload_file_format(action_type)
	{
		//alert("test"+action_type);
		var form_obj1=document.manage1.manage_id;
		var account_id_local=radio_selection(form_obj1);  //////////validate and get geofence
		if(account_id_local==false)
		{
			return false;
		}			
		
		var form_obj1=document.manage1.upload_format_type;
		var upload_format_type=radio_selection(form_obj1);  //////////validate and get geofence			
		if(upload_format_type==false)
		{
			return false;
		}
		
		if(action_type=="add" || action_type=="edit")
		{		
			if(document.getElementById('upload_format_id').value=="")
			{
				alert("Please Enter Upload Format ID");
				document.getElementById('upload_format_id').focus();
				return false;				
			}
			if(document.getElementById('file_format_name').value=="")
			{
				alert("Please Enter File Format Name");
				document.getElementById('file_format_name').focus();
				return false;	
			}
			if(document.getElementById('no_of_files').value=="select")
			{
				alert("Please Select No Of File");
				document.getElementById('no_of_files').focus();
				return false;
			}
			
			var total_no_of_files=document.getElementById('no_of_files').value;
			//alert("total_no_of_files="+total_no_of_files);
			var file_names_1="";
			var file_ids_1="";	
			for(var i=1;i<=total_no_of_files;i++)
			{			
				if(document.getElementById('filename_fields_'+i).value=="")
				{
					alert("Please Select File Name");
					document.getElementById('filename_fields_'+i).focus();
					return false;
					break;
				}
				if(document.getElementById('file_id_'+i).value=="")
				{
					alert("Please Select File ID");
					document.getElementById('file_id_'+i).focus();
					return false;
					break;
				}
				
				file_names_1=file_names_1+document.getElementById('filename_fields_'+i).value+",";
				file_ids_1=file_ids_1+document.getElementById('file_id_'+i).value+",";
			}
			var file_names=file_names_1.slice(0, -1);
			var file_ids=file_ids_1.slice(0, -1);			
			var temp_poststr="action_type="+action_type+
					  "&account_id_local="+account_id_local+
					  "&upload_format_type="+upload_format_type+		
					  "&upload_format_id="+document.getElementById('upload_format_id').value+
					  "&file_format_names="+document.getElementById('file_format_name').value+
					  "&no_of_files="+document.getElementById('no_of_files').value+
					  "&file_names="+file_names+
					  "&file_ids="+file_ids;			
			if(action_type=="add")
			{	
				var poststr=temp_poststr+
							"&remark="+document.getElementById('remark').value;
			}
			else if(action_type=="edit")
			{
				var poststr=temp_poststr
			}
			//alert("poststr="+poststr);						
		} 
		else if(action_type=="delete")
		{
			if(document.getElementById('upload_format_id_select').value=="select")
			{
				alert("Please Select Upload Format ID");
				document.getElementById('upload_format_id_select').focus();
				return false;				
			}
			var poststr="action_type=delete"+
						"&upload_format_type="+upload_format_type+
						"&account_id_local="+account_id_local+
						"&upload_format_id="+document.getElementById('upload_format_id_select').value;
			
		}
		showManageLoadingMessage();
		makePOSTRequest('src/php/action_manage_upload_file_format.htm', poststr);
	}
	function show_format_type_file(format_type)
	{
		var form_obj1=document.manage1.manage_id;
		var account_id_local=radio_selection(form_obj1);  //////////validate and get geofence
		if(account_id_local==false)
		{
			return false;
		}
		var poststr = "upload_format_type="+format_type+
					  "&account_id_local="+account_id_local;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/ajax_ed_upload_file_format.htm', poststr);		
	}
	function show_upload_format_child_file(upload_format_id,format_id_type)
	{
		var form_obj1=document.manage1.manage_id;
		var account_id_local=radio_selection(form_obj1);  //////////validate and get geofence
		if(account_id_local==false)
		{
			return false;
		}
		var poststr = "upload_format_id="+upload_format_id+
					  "&account_id_local="+account_id_local+
					  "&format_id_type="+format_id_type;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/ajax_ed_upload_file_format.htm', poststr);		
	}
	function show_edit_selected_filename_field(no_of_files)
	{
		var file_names_1="";
		var file_ids_1="";
		//alert("no_of_files="+no_of_files);
		var pre_no_of_file=document.getElementById("pre_no_of_file").value;
		for(var i=1;i<=pre_no_of_file;i++)
		{			
			if(document.getElementById('filename_fields_'+i).value=="")
			{
				alert("Please Select File Name");
				document.getElementById('filename_fields_'+i).focus();
				return false;
				break;
			}
			if(document.getElementById('file_id_'+i).value=="")
			{
				alert("Please Select File ID");
				document.getElementById('file_id_'+i).focus();
				return false;
				break;
			}
			
			file_names_1=file_names_1+document.getElementById('filename_fields_'+i).value+",";
			file_ids_1=file_ids_1+document.getElementById('file_id_'+i).value+",";
		}
		var poststr ="action_type=edit"+
					 "&no_of_files="+no_of_files+
					 "&file_names="+file_names_1+
					 "&file_ids="+file_ids_1;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_filename_field.htm', poststr); 
	}
  
	 function show_selected_filename_field(no_of_files)
	 {
		var poststr ="action_type=add"+
					 "&no_of_files="+no_of_files; 
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_filename_field.htm', poststr); 
	 }
	 
	 
	 //=====================raw milk=======================
	 var counter = 0;
	 
	 function addfield(){	   
	   document.getElementById("tnum").value="";
		counter++;
		var newFields = document.getElementById('readroot').cloneNode(true);
		newFields.id = '';
		newFields.style.display = 'block';
		var tid="";
		var newField = newFields.childNodes;
		for (var i=0;i<newField.length;i++) {
			var theName = newField[i].name
			var theid = newField[i].id
			if (theName)
			{
				newField[i].name = theName + counter;
				newField[i].id = theid + counter;
				tid=counter;   			
				document.getElementById("tnum").value=tid;

			}
		}
		var insertHere = document.getElementById('writeroot');
		insertHere.parentNode.insertBefore(newFields,insertHere);
	  //alert(tid);
	  document.getElementById("num:"+tid).value=tid;
	  document.getElementById("butt:"+tid).value="DEL-"+tid;
	 //alert("hello"+document.getElementById("tnum").value);

}

	 function addfieldTotal(tot){
		
		//alert("hello"+document.getElementById("tnum").value);
	   counter=0;
	  
	   for(var c=0;c<parseInt(tot-1);c++){
		   
		document.getElementById("tnum").value="";
		counter++;
		var newFields = document.getElementById('readroot').cloneNode(true);
		newFields.id = 'x'+c;
		newFields.style.display = 'block';
		var tid="";
		var newField = newFields.childNodes;
			//alert(newField.length);
		
				for (var i=0;i<newField.length;i++) {
						var theName = newField[i].name
						var theid = newField[i].id
						if (theName)
						{
							//alert('g');
								newField[i].name = theName + counter;
								newField[i].id = theid + counter;
								tid=counter;   			
								document.getElementById("tnum").value=tid;

						}
				}
				var insertHere = document.getElementById('writeroot');
				insertHere.parentNode.insertBefore(newFields,insertHere);
				//alert(tid);
				document.getElementById("num:"+tid).value=tid;
				document.getElementById("butt:"+tid).value="DEL-"+tid;
			}

	}

function lessFields(id){
  //alert(id);
  id1=id.split(":");
  //alert(id1[1]);
  counter--;
  flagset=0;
  tmp_counter=document.getElementById("tnum").value;
  document.getElementById("tnum").value=counter;
  //alert(tmp_counter);
  for( i=1;i<parseInt(tmp_counter);i++)
  {
	
	if(id1[1] <= parseInt(tmp_counter)){
		//("less="+i);
	  if(id1[1]==i){

		z=i+1;
		//alert( "Z="+z);
		document.getElementById("num:"+z).value=i;
		document.getElementById("num:"+z).id="num:"+i;
		document.getElementById("butt:"+z).value="DEL-"+i;
		document.getElementById("butt:"+z).id="butt:"+i;
		
		document.getElementById("lrno:"+z).id="lrno:"+i;
		document.getElementById("vehno:"+z).id="vehno:"+i;
		document.getElementById("tankertype:"+z).id="tankertype:"+i;
		document.getElementById("email:"+z).id="email:"+i;
		document.getElementById("mobile:"+z).id="mobile:"+i;
		document.getElementById("driver:"+z).id="driver:"+i;
		document.getElementById("drivermobile:"+z).id="drivermobile:"+i;
		document.getElementById("qty:"+z).id="qty:"+i;
		document.getElementById("fat_per:"+z).id="fat_per:"+i;
		document.getElementById("snf_per:"+z).id="snf_per:"+i;
		document.getElementById("fat_kg:"+z).id="fat_kg:"+i;
		document.getElementById("snf_kg:"+z).id="snf_kg:"+i;	
		document.getElementById("milk_age:"+z).id="milk_age:"+i;
		document.getElementById("disp_time:"+z).id="disp_time:"+i;
		document.getElementById("target_time:"+z).id="target_time:"+i;
		document.getElementById("plant:"+z).id="plant:"+i;
		document.getElementById("chillplant:"+z).id="chillplant:"+i;
		document.getElementById("docketflag:"+z).id="docketflag:"+i;
                
		id1[1]=z;
	  }
	  else{
		 //document.getElementById("num:"+i).value=i;
	  }

	}
	
  }
  z=0;
  tmp_counter=0;
  //service_manupulation_del(id);

}

function lessFieldswithTransporter(id){
  //alert(id);
  id1=id.split(":");
  //alert(id1[1]);
  counter--;
  flagset=0;
  tmp_counter=document.getElementById("tnum").value;
  document.getElementById("tnum").value=counter;
  //alert(tmp_counter);
  for( i=1;i<parseInt(tmp_counter);i++)
  {
	
	if(id1[1] <= parseInt(tmp_counter)){
		//("less="+i);
	  if(id1[1]==i){

		z=i+1;
		//alert( "Z="+z);
		document.getElementById("num:"+z).value=i;
		document.getElementById("num:"+z).id="num:"+i;
		document.getElementById("butt:"+z).value="DEL-"+i;
		document.getElementById("butt:"+z).id="butt:"+i;
		
		document.getElementById("lrno:"+z).id="lrno:"+i;
		document.getElementById("vehno:"+z).id="vehno:"+i;
		document.getElementById("transporter:"+z).id="transporter:"+i;
		document.getElementById("tankertype:"+z).id="tankertype:"+i;
		document.getElementById("email:"+z).id="email:"+i;
		document.getElementById("mobile:"+z).id="mobile:"+i;
		document.getElementById("driver:"+z).id="driver:"+i;
		document.getElementById("drivermobile:"+z).id="drivermobile:"+i;
		document.getElementById("qty:"+z).id="qty:"+i;
		document.getElementById("fat_per:"+z).id="fat_per:"+i;
		document.getElementById("snf_per:"+z).id="snf_per:"+i;
		document.getElementById("fat_kg:"+z).id="fat_kg:"+i;
		document.getElementById("snf_kg:"+z).id="snf_kg:"+i;	
		document.getElementById("milk_age:"+z).id="milk_age:"+i;
		document.getElementById("disp_time:"+z).id="disp_time:"+i;
		document.getElementById("target_time:"+z).id="target_time:"+i;
		document.getElementById("plant:"+z).id="plant:"+i;
		document.getElementById("chillplant:"+z).id="chillplant:"+i;
		document.getElementById("docketflag:"+z).id="docketflag:"+i;
                
		id1[1]=z;
	  }
	  else{
		 //document.getElementById("num:"+i).value=i;
	  }

	}
	
  }
  z=0;
  tmp_counter=0;
  //service_manupulation_del(id);

}

function manage_csv(target_file)
{
	document.forms[0].action = target_file; 	
	document.forms[0].submit();
	
}
function manage_csv_post(target_file)
{
	//alert(document.getElementById('csv_string').value);
	/*var csv_string=document.getElementById('csv_string').value
	var csv_type=document.getElementById('csv_type').value
	var poststr = "csv_string="+csv_string+
				"&csv_type="+csv_type;
				
	//alert(poststr);
	makePOSTRequest(target_file,poststr);*/
	document.invoice_form_csv.action = target_file; 	
	document.invoice_form_csv.submit();
}
  
function show_lorry_pre(sno)
{
    document.getElementById('tmp_serial').value = sno;
    document.getElementById("blackout").style.visibility = "visible";
    document.getElementById("divpopup_lorry").style.visibility = "visible";
    document.getElementById("blackout").style.display = "block";
    document.getElementById("divpopup_lorry").style.display = "block"; 
    var lorryno=document.getElementById('lorry_'+sno).value;
    document.getElementById('temp_lorryno').value=lorryno;
    document.getElementById('edit_lorryno').value=lorryno;    
        
}

function close_lorry_pre()
{
    if(document.getElementById('edit_lorryno').value!="")
    {
        var flag_not=0;
        if(document.getElementById('edit_lorryno').value!=document.getElementById('temp_lorryno').value)
        {
             /* //temporary disabled
            //validating lorry no
            var lorry_val=document.getElementById('edit_lorryno').value;
            var final_lrno=document.getElementById('final_lrno').value;                    

            final_lrno=final_lrno.split(',');
            for (var i = 0; i < final_lrno.length; i++)
            {
                    if (final_lrno[i] == lorry_val) 
                    {
                            alert('Lorry No '+lorry_val +' already exist in Previous Open! Please Enter other Lorry Number');                       
                            //return false;
                            flag_not=1;
                            break;
                    }
            }
            //checking in self list
            if(flag_not==0)
            {
                var tmp_tot_list=document.getElementById('tmp_tot_list').value;  
                for (var i = 1; i < tmp_tot_list; i++)
                {
                        var self_lorry=document.getElementById('lorry_cnt_'+i).value;
                        if(self_lorry == lorry_val) 
                        {
                                alert('Lorry No '+lorry_val +' already exist in List ! Please Enter other Lorry Number');                       
                                //return false;
                                flag_not=1;
                                break;
                        }
                }
            }
            */
            /////
            
            var serial = document.getElementById('tmp_serial').value;
            if(flag_not==0)
            {
                document.getElementById('lorry_'+serial).value = document.getElementById('edit_lorryno').value;
                document.getElementById('label_lorry_'+serial).innerHTML = document.getElementById('edit_lorryno').value;	
            }
            else
            {
                 document.getElementById('lorry_'+serial).value = document.getElementById('temp_lorryno').value;
                 document.getElementById('label_lorry_'+serial).innerHTML = document.getElementById('temp_lorryno').value;	
            }
		
        }
    }
    var param1 ="blackout";
    var param2 ="divpopup_lorry";
    //alert("param1="+param1+"param2="+param2);
    document.getElementById(param1).style.visibility = "hidden";
    document.getElementById(param2).style.visibility = "hidden";
    document.getElementById(param1).style.display = "none";
    document.getElementById(param2).style.display = "none";
    
}

function close_lorry_pre_cancel()
{
    var param1 ="blackout";
    var param2 ="divpopup_lorry";
    //alert("param1="+param1+"param2="+param2);
    document.getElementById(param1).style.visibility = "hidden";
    document.getElementById(param2).style.visibility = "hidden";
    document.getElementById(param1).style.display = "none";
    document.getElementById(param2).style.display = "none";
}

function show_vehicle_no_pre(sno)
{
    //alert(sno);
    document.getElementById('tmp_serial').value = sno;
    document.getElementById("blackout").style.visibility = "visible";
    document.getElementById("divpopup_vehicle_no").style.visibility = "visible";
    document.getElementById("blackout").style.display = "block";
    document.getElementById("divpopup_vehicle_no").style.display = "block"; 
    var vehicle_no=document.getElementById('vehicle_no_'+sno).value;
    document.getElementById('temp_vehicle_no').value=vehicle_no;
    document.getElementById('vehicle_list').value=vehicle_no;  
        
}

function close_vehicle_no_pre()
{
    //alert(document.getElementById('vehicle_list').value);
    if(document.getElementById('vehicle_list').value!="")
    {
        var flag_not=0;
        if(document.getElementById('vehicle_list').value!=document.getElementById('temp_vehicle_no').value)
        {
             /* //temporary disabled
            //validating vehicle no
            var vehicle_val=document.getElementById('vehicle_list').value;
            var final_vehicleno=document.getElementById('final_vehicleno').value;                    

            final_vehicleno=final_vehicleno.split(',');
            for (var i = 0; i < final_vehicleno.length; i++)
            {
                    if (final_vehicleno[i] == vehicle_val) 
                    {
                            alert('Vehicle No '+vehicle_val +' already exist in Previous Open! Please Enter other Vehicle Number');                       
                            flag_not=1;
                            break;
                    }
            }
            /////
            //checking in self list
            if(flag_not==0)
            {
                var tmp_tot_list=document.getElementById('tmp_tot_list').value; 
                //alert(tmp_tot_list);
                for (var i = 1; i < tmp_tot_list; i++)
                {
                        var self_vehicle=document.getElementById('vehicle_no_cnt_'+i).value;
                        //alert(self_vehicle);
                        if(self_vehicle == vehicle_val) 
                        {
                                alert('Vehicle No '+vehicle_val +' already exist in List! Please Enter other Vehicle Number');                       
                                //return false;
                                flag_not=1;
                                break;
                        }
                }
            }
            */
            /////
            var serial = document.getElementById('tmp_serial').value;
            if(flag_not==0)
            {
                document.getElementById('vehicle_no_'+serial).value = document.getElementById('vehicle_list').value.toUpperCase();
                document.getElementById('label_vehicle_no_'+serial).innerHTML = document.getElementById('vehicle_list').value.toUpperCase();	
		
            }
            else
            {
                 document.getElementById('vehicle_no_'+serial).value = document.getElementById('temp_vehicle_no').value;
                 document.getElementById('label_vehicle_no_'+serial).innerHTML = document.getElementById('temp_vehicle_no').value;	
		
            }
            
        }
    }
    var param1 ="blackout";
    var param2 ="divpopup_vehicle_no";
    //alert("param1="+param1+"param2="+param2);
    document.getElementById(param1).style.visibility = "hidden";
    document.getElementById(param2).style.visibility = "hidden";
    document.getElementById(param1).style.display = "none";
    document.getElementById(param2).style.display = "none";
}

function close_vehicle_no_pre_cancel()
{
    var param1 ="blackout";
    var param2 ="divpopup_vehicle_no";
    //alert("param1="+param1+"param2="+param2);
    document.getElementById(param1).style.visibility = "hidden";
    document.getElementById(param2).style.visibility = "hidden";
    document.getElementById(param1).style.display = "none";
    document.getElementById(param2).style.display = "none"; 
}

function show_qtykg_pre(sno,fat_per,snf_per,fat_kg,snf_kg)
{
    document.getElementById('tmp_serial').value = sno;
    
    var qty_kg=document.getElementById('qty_kg_'+sno).value;
    document.getElementById('temp_qty_kg').value=qty_kg;
    document.getElementById('temp_fat_per').value=fat_per;  
    document.getElementById('temp_snf_per').value=snf_per;  
    document.getElementById('temp_fat_kg').value=fat_kg;  
    document.getElementById('temp_snf_kg').value=snf_kg;  
    
    document.getElementById("blackout").style.visibility = "visible";
    document.getElementById("divpopup_invoice_qty").style.visibility = "visible";
    document.getElementById("blackout").style.display = "block";
    document.getElementById("divpopup_invoice_qty").style.display = "block"; 
    
    
}

function close_qtykg_pre()
{
    //alert("#"+document.getElementById('temp_qty_kg').value+"#");
    if(document.getElementById('temp_fat_kg').value!="NaN" || document.getElementById('temp_snf_kg').value!="NaN")
    {
        //if((document.getElementById('temp_qty_kg').value!='') || (document.getElementById('temp_qty_kg').value!="0") || (document.getElementById('temp_qty_kg').value!="0.0"))
        if((document.getElementById('temp_qty_kg').value!='') && (document.getElementById('temp_qty_kg').value!='0.0') && (document.getElementById('temp_qty_kg').value!='0') )
        {
            //alert(document.getElementById('temp_qty_kg').value);
            var serial = document.getElementById('tmp_serial').value;
            document.getElementById('qty_kg_'+serial).value = document.getElementById('temp_qty_kg').value;
            document.getElementById('label_qtykg_'+serial).innerHTML = document.getElementById('temp_qty_kg').value;	

            document.getElementById('fat_per_'+serial).value = document.getElementById('temp_fat_per').value;
            document.getElementById('label_fat_per_'+serial).innerHTML = document.getElementById('temp_fat_per').value;	

            document.getElementById('snf_per_'+serial).value = document.getElementById('temp_snf_per').value;
            document.getElementById('label_snf_per_'+serial).innerHTML = document.getElementById('temp_snf_per').value;	

            document.getElementById('fat_kg_'+serial).value = document.getElementById('temp_fat_kg').value;
            document.getElementById('label_fat_kg_'+serial).innerHTML = document.getElementById('temp_fat_kg').value;	

            document.getElementById('snf_kg_'+serial).value = document.getElementById('temp_snf_kg').value;
            document.getElementById('label_snf_kg_'+serial).innerHTML = document.getElementById('temp_snf_kg').value;
        }
        
        
    }
    var param1 ="blackout";
    var param2 ="divpopup_invoice_qty";
    //alert("param1="+param1+"param2="+param2);
    document.getElementById(param1).style.visibility = "hidden";
    document.getElementById(param2).style.visibility = "hidden";
    document.getElementById(param1).style.display = "none";
    document.getElementById(param2).style.display = "none";
}

function close_qtykg_pre_cancel()
{
    var param1 ="blackout";
    var param2 ="divpopup_invoice_qty";
    //alert("param1="+param1+"param2="+param2);
    document.getElementById(param1).style.visibility = "hidden";
    document.getElementById(param2).style.visibility = "hidden";
    document.getElementById(param1).style.display = "none";
    document.getElementById(param2).style.display = "none"; 
}

function put_fat_snf_kg_edit(val){
	//alert(val);
	
	document.getElementById('temp_fat_kg').value="";
	document.getElementById('temp_snf_kg').value="";
	var fat_per=document.getElementById('temp_fat_per').value;
	var snf_per=document.getElementById('temp_snf_per').value;

	var fatkg=(val*fat_per)/100;
	fatkg=Math.round(fatkg*100)/100; 
	document.getElementById('temp_fat_kg').value=fatkg;

	var snfkg=(val*snf_per)/100;
	snfkg=Math.round(snfkg*100)/100; 
	document.getElementById('temp_snf_kg').value=snfkg;
}


function put_fat_kg_edit(val){
	//alert(val);
	
	document.getElementById('temp_fat_kg').value="";
	var qty=document.getElementById('temp_qty_kg').value;
	if(qty==""){
		alert("Please Enter Qty Value");
		document.getElementById('temp_fat_per').value="";
	}
	else{
		var fatkg=(qty*val)/100;
		fatkg=Math.round(fatkg*100)/100; 
		document.getElementById('temp_fat_kg').value=fatkg;
	}
	//alert(ids);
}
function put_snf_kg_edit(val){
	//alert(val);
	
	document.getElementById('temp_snf_kg').value="";
	var qty=document.getElementById('temp_qty_kg').value;
	if(qty==""){
		alert("Please Enter Qty(kg) Value");
		document.getElementById('temp_snf_per').value="";
	}
	else{
		var snfkg=(qty*val)/100;
		snfkg=Math.round(snfkg*100)/100; 
		document.getElementById('temp_snf_kg').value=snfkg;
	}
	//alert(ids);
}


function getScriptPage_raw_milk_for_edit(val,ids,box){
        //alert(val);
        
	//var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
	var iChars = "! @#$%^&*()+=-[]\\\';,.{}|/\":<>?";
	flag=0;
	for (var k = 0; k < val.length; k++) {
		if (iChars.indexOf(val.charAt(k)) != -1) {
			//alert(iChars[iChars.indexOf(val.charAt(k))]);
			alert ("The box has special characters: "+ iChars[iChars.indexOf(val.charAt(k))] +" \n These are not allowed.\n");
			document.getElementById('vehicle_list').focus();
			//return false;
			
			//document.getElementById('vehicle_list').value="";replace(/blue/gi, "red");
			var replace_str=val.replace(iChars[iChars.indexOf(val.charAt(k))], "");
			document.getElementById('vehicle_list').value=replace_str; 
			flag=1;
		
		} 
	} 
    if(flag==0){
		var vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;	 
		var poststr ="action_type=rawmilkvehicle"+
					 "&all_vehicles="+vehicle_list_hidden+
					 "&content="+val+
					 "&box="+box+
					 "&ids="+ids;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/datalog_script_search_rawmilk.htm', poststr); 
	}
	else{
		return false;
	}
	   
}
///////////////////////////

function show_plant_list(sno)
{
	//alert(sno);
	document.getElementById('tmp_serial').value = sno;
	//document.getElementById('label_'+sno).innerHTML = "Changed";
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 
	/*var flagacceptance=document.getElementById('acceptancetime_'+sno).value;
	if(flagacceptance!=""){
		document.getElementById('approve_pre_visible').style.visibility='hidden';
	}
	else{
		document.getElementById('approve_pre_visible').style.visibility='visible';
	}
	
	if(document.getElementById('approval_'+sno).value=="approved"){
		document.getElementById('accept_pre').checked=true;
	}
	else{
		document.getElementById('accept_pre').checked=false;
	}*/
	
}
function show_plant_list_pre(sno,plantno)
{
	//alert(sno);
	document.getElementById('tmp_serial').value = sno;
	//document.getElementById('label_'+sno).innerHTML = "Changed";
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 
	document.getElementById('plant_list').value=plantno;
	/*
	var flagacceptance=document.getElementById('acceptancetime_'+sno).value;
	if(flagacceptance!=""){
		//alert('hidden');
		document.getElementById('approve_pre_visible').style.visibility='hidden';
	}
	else{
		//alert('visible');
		document.getElementById('approve_pre_visible').style.visibility='visible';
		if(document.getElementById('approval_'+sno).value=="approved"){
		document.getElementById('accept_pre').checked=true;
		}
		else{
			document.getElementById('accept_pre').checked=false;
		}
	}
	*/
	
	
}

function close_plant_list(value)
{
	if(document.getElementById('plant_list').value!=0)
	{
		//var flag_later=1;
		var serial = document.getElementById('tmp_serial').value;
		document.getElementById('plant_'+serial).value = document.getElementById('plant_list').value;
		var flagacceptance=document.getElementById('acceptancetime_'+serial).value;
		if(flagacceptance==""){
			document.getElementById('label_'+serial).innerHTML = "<font color=red>"+document.getElementById('plant_list').value+"</font>";
		}
		else{
			document.getElementById('label_'+serial).innerHTML = "<font color=green>"+document.getElementById('plant_list').value+"</font>";
			/*if(document.getElementById('pre_plant_'+serial).value!=document.getElementById('plant_list').value){
				//alert("false");
				document.getElementById('approval_'+serial).value="approved";
				flag_later=0;
			}
			else{
				//alert("same");
				document.getElementById('approval_'+serial).value="";
				flag_later=1;
			}*/
		}
		
		/*if(flag_later==1){
			//alert(document.getElementById('accept_pre').checked);
			if(document.getElementById('accept_pre').checked==true){
			document.getElementById('approval_'+serial).value="approved";
			}
			else{
				document.getElementById('approval_'+serial).value="";
			}
		}*/
		
		
				
	}
	
	var param1 ="blackout";
	var param2 ="divpopup_plant";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}

function show_plant_list_transporter(sno)
{
	//alert(sno);
	document.getElementById('tmp_serial').value = sno;
	//document.getElementById('label_'+sno).innerHTML = "Changed";
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 
	/*var flagacceptance=document.getElementById('acceptancetime_'+sno).value;
	if(flagacceptance!=""){
		document.getElementById('approve_pre_visible').style.visibility='hidden';
	}
	else{
		document.getElementById('approve_pre_visible').style.visibility='visible';
	}*/
	
}
function close_plant_list_transporter(value)
{
	if(document.getElementById('plant_list').value!=0)
	{
		var serial = document.getElementById('tmp_serial').value;
		document.getElementById('plant_'+serial).value = document.getElementById('plant_list').value;
		document.getElementById('label_'+serial).innerHTML = document.getElementById('plant_list').value;	
		var flagacceptance=document.getElementById('acceptancetime_'+serial).value;
		if(flagacceptance==""){
			document.getElementById('label_'+serial).innerHTML = "<font color=red>"+document.getElementById('plant_list').value+"</font>";
		}
		else{
			document.getElementById('label_'+serial).innerHTML = "<font color=green>"+document.getElementById('plant_list').value+"</font>";
		}
	}
	
	var param1 ="blackout";
	var param2 ="divpopup_plant";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}

function setapproval(sno){
	if(document.getElementById('approvalcheck_'+sno).checked==true){
	document.getElementById('approval_'+sno).value="approved";
	}
	else{
		document.getElementById('approval_'+sno).value="";
	}
}

function setclosetime(sno){
	
	
	document.getElementById('tmp_serial').value = sno;
	document.getElementById('popup_action').value = "add";
	if(document.getElementById('close_chk_'+sno).checked==true){
		var param1 ="blackout";
		var param2 ="divpopup_milkage";		
		document.getElementById(param1).style.visibility = "visible";
		document.getElementById(param2).style.visibility = "visible";
		document.getElementById(param1).style.display = "block";
		document.getElementById(param2).style.display = "block"; 
		if(document.getElementById('invoice_material_'+sno).value=="")
                {
                    document.getElementById('temp_inv_material').value =0;
                }
                else
                {
                    document.getElementById('temp_inv_material').value =document.getElementById('invoice_material_'+sno).value;
                }
		document.getElementById('temp_unload_estimate_datetime').style.display=''; /*tempo not visible */
		document.getElementById('temp_unload_estimate_datetime').value =''; /*tempo not visible */
		 if(document.getElementById('unload_estimated_datetime_'+sno).value=='0000-00-00 00:00:00')
                {
                     document.getElementById('temp_unload_estimate_datetime').value ="";
                }
                else
                {
                    document.getElementById('temp_unload_estimate_datetime').value =document.getElementById('unload_estimated_datetime_'+sno).value; 
                }
                //document.getElementById('temp_unload_estimate_datetime').value =document.getElementById('unload_estimated_datetime_'+sno).value; 
		document.getElementById('temp_unload_estimate_time').value = document.getElementById('unload_estimated_time_'+sno).value;
		document.getElementById('temp_unload_estimate_datetime_label').style.display='none';
		document.getElementById('temp_unload_estimate_time').readOnly = true;
		document.getElementById('temp_unload_estimate_time').style.backgroundColor = "ghostwhite";
		document.getElementById('temp_unload_accept_time').style.display=''; /* visible */		
		document.getElementById('temp_unload_accept_time').value = document.getElementById('unload_accept_time_'+sno).value;
		document.getElementById('temp_unload_accept_time').readOnly = true;
		document.getElementById('temp_unload_accept_time').style.backgroundColor = "white";
		document.getElementById('temp_fat_per_ft').value = document.getElementById('fat_per_ft_'+sno).value;
		document.getElementById('temp_fat_per_ft').readOnly = false;
		document.getElementById('temp_fat_per_ft').style.backgroundColor = "white";
		document.getElementById('temp_snf_per_ft').value = document.getElementById('snf_per_ft_'+sno).value;
		document.getElementById('temp_snf_per_ft').readOnly = false;
		document.getElementById('temp_snf_per_ft').style.backgroundColor = "white";
		
		if(document.getElementById('temp_unload_estimate_time').value !='' && document.getElementById('temp_unload_accept_time').value !='')
		{
			var datum_pre=(document.getElementById('temp_unload_accept_time').value).replace(/-/g, "/"); 
			//alert(datum_pre)
			var datum = Date.parse( datum_pre);
			//alert(datum);
			var datum_pre1=document.getElementById('temp_unload_estimate_time').value;
			//alert(datum_pre1);
			var final_datum=datum - (datum_pre1 * 60000);
			//alert(final_datum);
			var theDate = new Date (final_datum);
			
			var dd=theDate.getDate();
			var final_dd=dd;
			if(dd.toString().length==1)
			{
				final_dd="0"+ dd;				
			}			
			var hr=theDate.getHours();
			var final_hr=hr;
			if(hr.toString().length==1)
			{
				final_hr="0"+ hr;				
			}
			var mi=theDate.getMinutes();
			var final_mi=mi;			
			if(mi.toString().length==1)
			{
				final_mi="0"+ mi;				
			}
			var ss=theDate.getSeconds();
			var final_ss=ss;			
			if(ss.toString().length==1)
			{
				final_ss="0"+ ss;				
			}
			
			if(theDate.getMonth()<10)
			{
				var cMonth="0"+theDate.getMonth();
				cMonth= +cMonth+1;
				//cMonth=cMonth.substr(1);
				//alert(cMonth);
			}
			else
			{
				var cMonth=theDate.getMonth();
				cMonth= +cMonth+1;
			}
			
			if(cMonth<10)
			{
				cMonth="0"+cMonth;
			}
			
			//var resverseDate=theDate.getFullYear()+ "/" +   theDate.getMonth()+1 + "/" + final_dd + " " + final_hr + ":" + final_mi + ":" + final_ss;
			var resverseDate=theDate.getFullYear()+ "/" +   cMonth + "/" + final_dd + " " + final_hr + ":" + final_mi + ":" + final_ss;
			if(resverseDate=="NaN/NaN/NaN NaN:NaN:NaN")
			{
				resverseDate="";
			}
                        //alert(resverseDate);
			document.getElementById('temp_unload_estimate_datetime').value=resverseDate;
			//document.getElementById('temp_unload_accept_time').value=(document.getElementById('temp_unload_accept_time').value).replace(/-/g, "/"); 
                        if(document.getElementById('unload_estimated_time_'+sno).value!="NaN")
			{
				document.getElementById('temp_unload_estimate_time').value = document.getElementById('unload_estimated_time_'+sno).value;
			}
			else
			{
				document.getElementById('temp_unload_estimate_time').value = "";
			}
		}
		
                document.getElementById('temp_unload_accept_time').value=(document.getElementById('unload_accept_time_'+sno).value).replace(/-/g, "/"); 		
		document.getElementById('temp_unload_estimate_datetime').value=(document.getElementById('temp_unload_estimate_datetime').value).replace(/-/g, "/");
		
                document.getElementById('temp_unload_accept_time').value=(document.getElementById('unload_accept_time_'+sno).value).replace(/-/g, "/"); 		
		document.getElementById('temp_unload_estimate_datetime').value=(document.getElementById('temp_unload_estimate_datetime').value).replace(/-/g, "/");
		
		var testing_status=document.getElementById('testing_status_'+sno).value;
		
		var radioObj = document.popupform.temp_accept_reject_sampling;		
		var radioLength = radioObj.length;		
		for(var i = 0; i < radioLength; i++) 
		{
			if(testing_status=='Accept')
			{
				//alert(testing_status);
				radioObj[0].checked=true;
			}
			else if(testing_status=='Reject')
			{
				//alert("R="+testing_status);
				radioObj[1].checked=true;
			}
			
		}
		document.getElementById('temp_qty_ct').value = document.getElementById('qty_ct_'+sno).value;
		document.getElementById('temp_degree_ct').value = document.getElementById('temp_ct_'+sno).value;
		document.getElementById('temp_acidity_ct').value = document.getElementById('acidity_ct_'+sno).value;
		document.getElementById('temp_mbrt_min_ct').value = document.getElementById('mbrt_min_ct_'+sno).value;
		document.getElementById('temp_mbrt_rm_ct').value = document.getElementById('mbrt_rm_ct_'+sno).value;
		document.getElementById('temp_mbrt_br_ct').value = document.getElementById('mbrt_br_ct_'+sno).value;
		document.getElementById('temp_protien_ct').value = document.getElementById('protien_per_ct_'+sno).value;
		document.getElementById('temp_sodium_ct').value = document.getElementById('sodium_ct_'+sno).value;
		
		if(document.getElementById('fat_per_rt_'+sno).value!="" && document.getElementById('snf_per_rt_'+sno).value!="" )
		{
			document.getElementById('resampling_chk').checked=true;
			document.getElementById('resamplingTest').style.display = "";
		}
		
		document.getElementById('temp_fat_per_rt').value = document.getElementById('fat_per_rt_'+sno).value;
		document.getElementById('temp_snf_per_rt').value = document.getElementById('snf_per_rt_'+sno).value;
		
		$('#temp_adultration_ct option:selected').each(function(){
				$("#temp_adultration_ct option[value='"+$(this).val()+"']").attr("selected",false);
			});
		var value_adultration=document.getElementById('adultration_ct_'+sno).value;
		if(value_adultration!="")
		{
			$.each(value_adultration.split(","),function(i,e){
				//alert(e);
				$("#temp_adultration_ct option[value='"+ e +"']").attr("selected",true);
			  });
		}
		
		document.getElementById('temp_otheradultration_ct').value=document.getElementById('otheradultration_ct_'+sno).value;
		
		document.getElementById('popupbutton').value="Ok";
		
	}
	else{
		//document.getElementById('unload_estimated_time_'+sno).value="";
		//document.getElementById('unload_accept_time_'+sno).value="";
		document.getElementById('closetime_'+sno).value="";
		var param1 ="blackout";
		var param2 ="divpopup_milkage";
		document.getElementById(param1).style.visibility = "hidden";
		document.getElementById(param2).style.visibility = "hidden";
		document.getElementById(param1).style.display = "none";
		document.getElementById(param2).style.display = "none";
	}
}
function setclosetimeedit(sno){
	
	    document.getElementById('tmp_serial').value = sno;
		
		document.getElementById('popup_action').value = "edit";
		var param1 ="blackout";
		var param2 ="divpopup_milkage";		
		document.getElementById(param1).style.visibility = "visible";
		document.getElementById(param2).style.visibility = "visible";
		document.getElementById(param1).style.display = "block";
		document.getElementById(param2).style.display = "block"; 
		document.getElementById('temp_unload_estimate_datetime').style.display='none'; /*tempo not visible */
		document.getElementById('temp_unload_estimate_datetime').value =document.getElementById('unload_estimated_datetime_'+sno).value; /*tempo not visible */
		
		document.getElementById('temp_unload_estimate_time').value = document.getElementById('unload_estimated_time_'+sno).value;
		document.getElementById('temp_unload_estimate_time').readOnly = true;
		document.getElementById('temp_unload_estimate_time').style.backgroundColor = "#FFE4C4";		
		document.getElementById('temp_unload_accept_time').value = document.getElementById('unload_accept_time_'+sno).value;
		document.getElementById('temp_unload_accept_time').readOnly = true;	
		document.getElementById('temp_unload_accept_time').style.display='none'; /*tempo not visible */
		document.getElementById('temp_unload_accept_time').style.backgroundColor = "#FFE4C4";	
		
		document.getElementById('temp_unload_accept_time_label').style.display='';/*dummy visiblity*/
		document.getElementById('temp_unload_accept_time_label').value = document.getElementById('unload_accept_time_'+sno).value;
		document.getElementById('temp_unload_accept_time_label').readOnly = true;	
		document.getElementById('temp_unload_accept_time_label').style.backgroundColor = "#FFE4C4";
		
		document.getElementById('temp_unload_estimate_datetime_label').style.display='';/*dummy visiblity*/
		document.getElementById('temp_unload_estimate_datetime_label').value = document.getElementById('unload_estimated_datetime_'+sno).value;
		document.getElementById('temp_unload_estimate_datetime_label').readOnly = true;	
		document.getElementById('temp_unload_estimate_datetime_label').style.backgroundColor = "#FFE4C4";	
		
		document.getElementById('temp_fat_per_ft').value = document.getElementById('fat_per_ft_'+sno).value;
		document.getElementById('temp_fat_per_ft').readOnly = true;
		document.getElementById('temp_fat_per_ft').style.backgroundColor = "#FFE4C4";	
		document.getElementById('temp_snf_per_ft').value = document.getElementById('snf_per_ft_'+sno).value;
		document.getElementById('temp_snf_per_ft').readOnly = true;
		document.getElementById('temp_snf_per_ft').style.backgroundColor = "#FFE4C4";	
		
		var testing_status=document.getElementById('testing_status_'+sno).value;
		
		var radioObj = document.popupform.temp_accept_reject_sampling;		
		var radioLength = radioObj.length;		
		for(var i = 0; i < radioLength; i++) 
		{
			if(testing_status=='Accept')
			{
				//alert(testing_status);
				radioObj[0].checked=true;
			}
			else if(testing_status=='Reject')
			{
				//alert("R="+testing_status);
				radioObj[1].checked=true;
			}
			
		}
                document.getElementById('temp_inv_material').value =document.getElementById('invoice_material_'+sno).value;
		document.getElementById('temp_qty_ct').value = document.getElementById('qty_ct_'+sno).value;
		document.getElementById('temp_degree_ct').value = document.getElementById('temp_ct_'+sno).value;
		document.getElementById('temp_acidity_ct').value = document.getElementById('acidity_ct_'+sno).value;
		document.getElementById('temp_mbrt_min_ct').value = document.getElementById('mbrt_min_ct_'+sno).value;
		document.getElementById('temp_mbrt_rm_ct').value = document.getElementById('mbrt_rm_ct_'+sno).value;
		document.getElementById('temp_mbrt_br_ct').value = document.getElementById('mbrt_br_ct_'+sno).value;
		document.getElementById('temp_protien_ct').value = document.getElementById('protien_per_ct_'+sno).value;
		document.getElementById('temp_sodium_ct').value = document.getElementById('sodium_ct_'+sno).value;
		document.getElementById('temp_fat_per_rt').value ="";
		document.getElementById('temp_snf_per_rt').value ="";
		if(document.getElementById('fat_per_rt_'+sno).value!="" && document.getElementById('snf_per_rt_'+sno).value!="" )
		{
			document.getElementById('resampling_chk').checked=true;
			document.getElementById('resamplingTest').style.display = "";
		}
		
		document.getElementById('temp_fat_per_rt').value = document.getElementById('fat_per_rt_'+sno).value;
		document.getElementById('temp_snf_per_rt').value = document.getElementById('snf_per_rt_'+sno).value;
		
		
		$('#temp_adultration_ct option:selected').each(function(){
			$("#temp_adultration_ct option[value='"+$(this).val()+"']").attr("selected",false);
		});
		var value_adultration=document.getElementById('adultration_ct_'+sno).value;
		if(value_adultration!="")
		{
			$.each(value_adultration.split(","),function(i,e){
				//alert(e);
				$("#temp_adultration_ct option[value='"+ e +"']").attr("selected",true);
			  });
		}
		document.getElementById('temp_otheradultration_ct').value=document.getElementById('otheradultration_ct_'+sno).value;
		  
		document.getElementById('popupbutton').value="Edit";
		
	
}
function close_milkage()
{
		var temp_fat_per_rt="";
		var temp_snf_per_rt="";
		
		
		if(document.getElementById('resampling_chk').checked==true)
		{
			temp_fat_per_rt = document.getElementById('temp_fat_per_rt').value;
			temp_snf_per_rt = document.getElementById('temp_snf_per_rt').value;
			if(temp_fat_per_rt.trim()=="" || temp_snf_per_rt.trim()=="")
			{
				alert("Please Enter the value of resampling unless uncheck the Resampling");
				return false;
			}
		}
		var temp_accept_reject_sampling ="";
		var radioObj = document.popupform.temp_accept_reject_sampling;		
		var radioLength = radioObj.length;		
		for(var i = 0; i < radioLength; i++) 
		{
			if(radioObj[i].checked==true)
			{
				temp_accept_reject_sampling=radioObj[i].value;
			}
					
		}
		
		var serial = document.getElementById('tmp_serial').value;
                var temp_inv_material = document.getElementById('temp_inv_material').value;
		var temp_unload_estimate_time = document.getElementById('temp_unload_estimate_time').value;
		var temp_unload_estimate_datetime = document.getElementById('temp_unload_estimate_datetime').value;
		var temp_unload_accept_time = document.getElementById('temp_unload_accept_time').value;
		var temp_fat_per_ft = document.getElementById('temp_fat_per_ft').value;
		var temp_snf_per_ft = document.getElementById('temp_snf_per_ft').value;
		
		var temp_qty_ct = document.getElementById('temp_qty_ct').value;
		var temp_degree_ct = document.getElementById('temp_degree_ct').value;
		var temp_acidity_ct = document.getElementById('temp_acidity_ct').value;
		var temp_mbrt_min_ct = document.getElementById('temp_mbrt_min_ct').value;
		var temp_mbrt_rm_ct = document.getElementById('temp_mbrt_rm_ct').value;
		var temp_mbrt_br_ct = document.getElementById('temp_mbrt_br_ct').value;
		var temp_protien_ct = document.getElementById('temp_protien_ct').value;
		var temp_sodium_ct = document.getElementById('temp_sodium_ct').value;
		
		var temp_adultration_ct="";
		$('#temp_adultration_ct option:selected').each(function(){
			//alert($(this).val());
			temp_adultration_ct = temp_adultration_ct +  $(this).val() + ",";
		});
		if(temp_adultration_ct !="")
		{
			temp_adultration_ct=temp_adultration_ct.substring(0,temp_adultration_ct.length - 1);
		}
	    
		var temp_otheradultration_ct = document.getElementById('temp_otheradultration_ct').value;
		//alert(temp_adultration_ct);
		
		
		
		//alert(temp_unload_estimate_time);
		//alert(temp_unload_accept_time);
		/*if((temp_unload_estimate_time.trim()=="" ) || (temp_unload_accept_time.trim()=="") || (temp_fat_per_ft.trim()=="")|| (temp_snf_per_ft.trim()==""))
		{
			document.getElementById('close_chk_'+serial).checked=false;
			//document.getElementById('unload_estimated_time_'+serial).value="";
			//document.getElementById('unload_accept_time_'+serial).value="";
			//document.getElementById('closetime_'+serial).value="";
			alert("First Plant Testing required Field Value");
		    return false;
			
		}
		else*/
		{
                        document.getElementById('invoice_material_'+serial).value=temp_inv_material;
			document.getElementById('unload_estimated_time_'+serial).value=temp_unload_estimate_time;
			document.getElementById('unload_estimated_datetime_'+serial).value=temp_unload_estimate_datetime;
			document.getElementById('unload_accept_time_'+serial).value=temp_unload_accept_time;
			document.getElementById('fat_per_ft_'+serial).value = temp_fat_per_ft;
			document.getElementById('snf_per_ft_'+serial).value = temp_snf_per_ft;
			//alert(temp_accept_reject_sampling);
			if(temp_accept_reject_sampling=="1")
			{
				document.getElementById('testing_status_'+serial).value='Accept';
			}
			else if(temp_accept_reject_sampling=="0")
			{
				document.getElementById('testing_status_'+serial).value='Reject';
			}
			if(temp_otheradultration_ct!='' || temp_adultration_ct!="")//for reject
			{
				document.getElementById('testing_status_'+serial).value='Reject';
			}
       		
			document.getElementById('qty_ct_'+serial).value=temp_qty_ct;
			document.getElementById('temp_ct_'+serial).value=temp_degree_ct;
			document.getElementById('acidity_ct_'+serial).value=temp_acidity_ct;
			document.getElementById('mbrt_min_ct_'+serial).value=temp_mbrt_min_ct;
			document.getElementById('mbrt_br_ct_'+serial).value=temp_mbrt_br_ct;
			document.getElementById('mbrt_rm_ct_'+serial).value=temp_mbrt_rm_ct;
			document.getElementById('protien_per_ct_'+serial).value=temp_protien_ct;
			document.getElementById('sodium_ct_'+serial).value=temp_sodium_ct;
		    if(temp_fat_per_rt!="")
			{
				document.getElementById('fat_per_rt_'+serial).value=temp_fat_per_rt;
			}
			if(temp_snf_per_rt!="")
			{
				document.getElementById('snf_per_rt_'+serial).value=temp_snf_per_rt;
			}
			
			document.getElementById('adultration_ct_'+serial).value=temp_adultration_ct;
			document.getElementById('otheradultration_ct_'+serial).value=temp_otheradultration_ct;
			
			document.getElementById('closetime_'+serial).value=temp_unload_accept_time;
			
			
			
			//checking close condition
			if((temp_unload_estimate_time.trim()!="" ) && (temp_unload_accept_time.trim()!="") && (temp_fat_per_ft.trim()!="")&& (temp_snf_per_ft.trim()!="")
			&& (temp_sodium_ct.trim()!="") && (temp_protien_ct.trim()!="")  && (temp_mbrt_rm_ct.trim()!="") && (temp_mbrt_br_ct.trim()!="") 
				&& (temp_mbrt_min_ct.trim()!="") && (temp_acidity_ct.trim()!="")  && (temp_degree_ct.trim()!="") && (temp_qty_ct.trim()!=""))
			{
				//alert("All Field is Ok. It will be closed on final Submition");
			}
			
			if(document.getElementById('popup_action').value == "edit") //only that flag should be updated only
			{
				document.getElementById('edit_close_chk_'+serial).value=1;
			}
			
		}
		
		var param1 ="blackout";
		var param2 ="divpopup_milkage";
		document.getElementById(param1).style.visibility = "hidden";
		document.getElementById(param2).style.visibility = "hidden";
		document.getElementById(param1).style.display = "none";
		document.getElementById(param2).style.display = "none";
		document.getElementById('temp_unload_accept_time_label').style.display='none';/*dummy not visiblity*/
		document.getElementById('temp_unload_estimate_datetime_label').style.display='none';/*dummy not visiblity*/
}

function close_milkage_cal_min()
{
	var temp_unload_accept_time = document.getElementById('temp_unload_accept_time').value;
	var temp_unload_estimate_datetime = document.getElementById('temp_unload_estimate_datetime').value;
	if(temp_unload_accept_time!="" &&  temp_unload_estimate_datetime!="")
	{
		
		var datum = Date.parse(temp_unload_accept_time);	
		//alert(datum);
		var datum1 = Date.parse(temp_unload_estimate_datetime);
		//alert(datum1);
		if(datum1>datum)
		{
			//alert('Error:Unload Estimate Time must be smaller than Unload Accept Time !');
			document.getElementById('temp_unload_estimate_time').value='';
			return false;
		}
		var difftime=(datum-datum1)/60000;
		//alert(difftime);
		document.getElementById('temp_unload_estimate_time').value=difftime;;
	}
}

function cancel_milkage()
{
	var serial = document.getElementById('tmp_serial').value;
	if(document.getElementById('popup_action').value=="add")
	{
		document.getElementById('close_chk_'+serial).checked=false;
	}
	$('#temp_adultration_ct option:selected').each(function(){
		$("#temp_adultration_ct option[value='"+$(this).val()+"']").attr("selected",false);
	});
	
	
		var temp_unload_estimate_time = document.getElementById('temp_unload_estimate_time').value;
		var temp_unload_accept_time = document.getElementById('temp_unload_accept_time').value;
		var temp_fat_per_ft = document.getElementById('temp_fat_per_ft').value;
		var temp_snf_per_ft = document.getElementById('temp_snf_per_ft').value;
		
		var temp_qty_ct = document.getElementById('temp_qty_ct').value;
		var temp_degree_ct = document.getElementById('temp_degree_ct').value;
		var temp_acidity_ct = document.getElementById('temp_acidity_ct').value;
		var temp_mbrt_min_ct = document.getElementById('temp_mbrt_min_ct').value;
		var temp_mbrt_rm_ct = document.getElementById('temp_mbrt_rm_ct').value;
		var temp_mbrt_br_ct = document.getElementById('temp_mbrt_br_ct').value;
		var temp_protien_ct = document.getElementById('temp_protien_ct').value;
		var temp_sodium_ct = document.getElementById('temp_sodium_ct').value;
	
	var param1 ="blackout";
	var param2 ="divpopup_milkage";
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
	document.getElementById('temp_unload_accept_time_label').style.display='none';/*dummy not visiblity*/
}


function show_resampling_block(status)
{
 if(status==true)
 {
	
	document.getElementById('resamplingTest').style.display = "";
 }
 else
 {
	document.getElementById('resamplingTest').style.display = "none";
 }
}

function show_vehicle_list(sno)
{
	//alert(sno);	
	document.getElementById('tmp_serial').value = sno;	
	document.getElementById('vehicle_list').value = document.getElementById(sno).value;
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 	
	document.getElementById("vehicle_list").focus();	
	
}
function close_vehicle_list(value)
{
	
	var serial = document.getElementById('tmp_serial').value;
	document.getElementById(serial).value = document.getElementById('vehicle_list').value;	
	document.getElementById('vehicle_list').value="";		
	
	//alert(document.getElementById('vehicle_list_hidden').value);
	var put_string=document.getElementById(serial).value;
	if(put_string!='')
	{
		put_string=put_string.replace(/ /g,'%20'); //all occurance str.replace(/ /g, '+');
		var to = document.getElementById('vehicle_list_hidden').value;
		var toSplit = to.split(",");
		var flag_uppercase=0;
		//alert(put_string);
		for (var i = 0; i < toSplit.length; i++)
		{		
			if(toSplit[i]!=put_string)
			{
				flag_uppercase=1;
			}
			else
			{
				//alert(toSplit[i]);
				flag_uppercase=0;
				break;
			}
		}
		if(flag_uppercase==1)
		{	
			put_string=put_string.replace(/%20/g,' ');
			document.getElementById(serial).value =put_string.toUpperCase();			
		}
	}
	
	
	var param1 ="blackout";
	var param2 ="divpopup_plant";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
	
    //invoice_lorry_unique(serial,put_string.toUpperCase() ); //temporary blocked
	
	
}

function alphanumeric_ucase(alphane,id)
{
	//alert(alphane);
	//alert(id);
	if(isNaN(alphane)==true)
	{
		alphane=alphane.toUpperCase();
	}
	
	var numaric = alphane;
	for(var j=0; j<numaric.length; j++)
	{
		var alphaa = numaric.charAt(j);
		var hh = alphaa.charCodeAt(0);
		//if((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123))
		
		if((hh > 47 && hh<58) || (hh > 64 && hh<91) )
		{
		}else
		{
			alert("Please Enter Valid Alphnumeric with Upper Case and Number Only"); 
			alphane=alphane.substr(0,alphane.length-1);
			document.getElementById(id).value=alphane;
			return false;
		}
	}
	//alert("Your Alpha Numeric Test Passed");
	document.getElementById(id).value=alphane;
	return true;
}

function alphanumeric_ucase_upload(alphane,id)
{
	//alert(alphane);
	//alert(id);
	 var ids1=id.split(":");
	 var ids=ids1[1];
	 //var lineno=+ids + +1;
	if(isNaN(alphane)==true)
	{
		alphane=alphane.toUpperCase();
	}
	
	var numaric = alphane;
	for(var j=0; j<numaric.length; j++)
	{
		var alphaa = numaric.charAt(j);
		var hh = alphaa.charCodeAt(0);
		//if((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123))
		
		if((hh > 47 && hh<58) || (hh > 64 && hh<91) )
		{
		}
		else
		{
			//alert("LR No not Valid Alphnumeric"); 
			document.getElementById('div_display_error_upload').innerHTML= document.getElementById('div_display_error_upload').innerHTML + "<font color=red>LR No:"+alphane+" not Valid Alphnumeric at SL:"+ids+"</font><br>";
			alphane=alphane.substr(0,alphane.length-1);
			document.getElementById(id).value="";
			//flag_alpha=0;
			return false;
			
		}
	}
	//alert("Your Alpha Numeric Test Passed");
	document.getElementById(id).value=alphane;
	return true;
}

function invoice_lorry_unique_pre(val,id)
{
	//alert(val);
	var final_lrno=document.getElementById('final_lrno').value; 
		final_lrno=final_lrno.split(',');
		for (var i = 0; i < final_lrno.length; i++)
		{
			if (final_lrno[i] == val) 
			{
				alert('Lorry No '+val +' already exist in Previous Open! Please Enter other Lorry Number');
				document.getElementById(id).value="";//lorry =""
				return false;
			}
		}
	
	var tnum_tmp=document.getElementById('tnum').value; //totol loop
	for (var j = 0; j < tnum_tmp; j++)
	{
		if(document.getElementById('vehno:'+j).value !="" )
		{
			if(id != "lrno:"+j) //skip self value
			{
				if(document.getElementById('lrno:'+j).value==val)
				{
					alert('Lorry No '+val +' already exist in the List Open! Please Enter other Lorry Number');
					document.getElementById(id).value="";//lorry =""
					return false;
				}
			}
		}
	}
		
}

function invoice_lorry_unique_pre_upload(val,id)
{
		//alert(val);
		//alert(id);
		var ids1=id.split(":");
		var ids=ids1[1];
		var lineno=+ids + +1;
		var final_lrno=document.getElementById('final_lrno').value; 
		var veh=document.getElementById('vehno:'+ids).value;
		final_lrno=final_lrno.split(',');
		for (var i = 0; i < final_lrno.length; i++)
		{
			if (final_lrno[i] == val && veh!="") 
			{
				//alert('Lorry No '+val +' already exist in Previous Open! Please Enter other Lorry Number');
				//document.getElementById('div_display_error_upload').innerHTML= document.getElementById('div_display_error_upload').innerHTML + "<font color=red>LR No:"+val+" already exist in Previous Open at Line:"+lineno+"</font><br>";
				document.getElementById(id).value="";//lorry =""
				return false;
			}
		}
		
	
	var tnum_tmp=document.getElementById('tnum').value; //totol loop
	for (var j = 0; j < tnum_tmp; j++)
	{
		if(document.getElementById('vehno:'+j).value !="" )
		{
			if(id != "lrno:"+j) //skip self value
			{
				if(document.getElementById('lrno:'+j).value==val)
				{
					//alert('Lorry No '+val +' already exist in the List Open! Please Enter other Lorry Number');
					//document.getElementById('div_display_error_upload').innerHTML= document.getElementById('div_display_error_upload').innerHTML + "<font color=red>LR No:"+val+" already exist in the List Open! at Line:"+lineno+"</font><br>";
					document.getElementById(id).value="";//lorry =""
					return false;
				}
			}
		}
	}
		
}

function invoice_lorry_unique(id,vehicle_no)
{
	   var final_lrno=document.getElementById('final_lrno').value; 
	   var ids1=id.split(":");
	   var ids=ids1[1];
	   var lorry_val=document.getElementById('lrno:'+ids).value;
	   if(lorry_val=="")
	   {
		alert("Lorry Number cant be blank, Please Fill Lorry Number First!");
		document.getElementById(id).value=""; //vehicle blank
		return false;
	   }
		final_lrno=final_lrno.split(',');
		for (var i = 0; i < final_lrno.length; i++)
		{
			if (final_lrno[i] == lorry_val) 
			{
				alert('Lorry No '+lorry_val +' already exist in Previous Open! Please Enter other Lorry Number for Vehicle '+vehicle_no);
				document.getElementById(id).value=""; //vehicle blank
				return false;
			}
		}
	var tnum_tmp=document.getElementById('tnum').value; //totol loop
	for (var j = 0; j < tnum_tmp; j++)
	{
		if(document.getElementById('vehno:'+j).value !="" )
		{
			if(id != "vehno:"+j) //skip self value
			{
				if(document.getElementById('lrno:'+j).value==lorry_val)
				{
					alert('Lorry No '+lorry_val +' already exist in the List Open in Form! Please Enter other Lorry Number');
					document.getElementById(id).value=""; //vehicle blank
					return false;
				}
			}
		}
	}
		
}

function invoice_lorry_unique_upload(id,vehicle_no)
{
	   var final_lrno=document.getElementById('final_lrno').value; 
	   //alert("f"+final_lrno);
	   var ids1=id.split(":");
	   var ids=ids1[1];
	   //var lineno=+ids + +1;
	   var lorry_val=document.getElementById('lrno:'+ids).value;
	   var veh=document.getElementById('vehno:'+ids).value;
	   if(lorry_val=="")
	   {
		//alert("Lorry Number cant be blank!");
		document.getElementById('div_display_error_upload').innerHTML= document.getElementById('div_display_error_upload').innerHTML + "<font color=red>Vehicle No :"+vehicle_no+" not allowed at SL:"+ids+"</font><br>";
		document.getElementById(id).value=""; //vehicle blank
		return false;
	   }
		final_lrno=final_lrno.split(',');
		for (var i = 0; i < final_lrno.length; i++)
		{
			if (final_lrno[i] == lorry_val && veh!="") 
			{
				//alert('Lorry No '+lorry_val +' already exist in Previous Open!');
				document.getElementById('div_display_error_upload').innerHTML= document.getElementById('div_display_error_upload').innerHTML + "<font color=red>Lorry No :"+lorry_val+" not allowed for Vehicle :"+vehicle_no+ " at SL:"+ids+" already exist in Previous Open.</font><br>";
				document.getElementById(id).value=""; //vehicle blank
				document.getElementById('lrno:'+ids).value=""; //lorry blank
				return false;
			}
		}
	var tnum_tmp=document.getElementById('tnum').value; //totol loop
	for (var j = 0; j < tnum_tmp; j++)
	{
		if(document.getElementById('vehno:'+j).value !="" )
		{
			if(id != "vehno:"+j) //skip self value
			{
				if(document.getElementById('lrno:'+j).value==lorry_val)
				{
					//alert('Lorry No '+lorry_val +' already exist in the List Open in Form!');
					document.getElementById('div_display_error_upload').innerHTML= document.getElementById('div_display_error_upload').innerHTML + "<font color=red>Lorry No :"+lorry_val+" not allowed at SL:"+ids+" already assigned above in form.</font><br>";
					document.getElementById(id).value=""; //vehicle blank
					document.getElementById('lrno:'+ids).value=""; //lorry blank
					return false;
				}
			}
		}
	}
		
}

function show_vehicle_list_pending_tanker(sno)
{
	//alert(sno);
	
	
	document.getElementById('tmp_serial').value = sno;	
	document.getElementById('vehicle_list').value = document.getElementById(sno).value;
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 	
	document.getElementById("vehicle_list").focus();	
	
}

function close_vehicle_list_pending_tanker(value)
{
	
	var serial = document.getElementById('tmp_serial').value;
	document.getElementById(serial).value = document.getElementById('vehicle_list').value;	
	document.getElementById('vehicle_list').value="";		
	
	//alert(document.getElementById('vehicle_list_hidden').value);
	var put_string=document.getElementById(serial).value;
	if(put_string!='')
	{
		put_string=put_string.replace(/ /g,'%20'); //all occurance str.replace(/ /g, '+');
		var to = document.getElementById('vehicle_list_hidden').value;
		var toSplit = to.split(",");
		var flag_uppercase=0;
		//alert(put_string);
		for (var i = 0; i < toSplit.length; i++)
		{		
			if(toSplit[i]!=put_string)
			{
				flag_uppercase=1;
			}
			else
			{
				//alert(toSplit[i]);
				flag_uppercase=0;
				break;
			}
		}
		if(flag_uppercase==1)
		{	
			put_string=put_string.replace(/%20/g,' ');
			document.getElementById(serial).value =put_string.toUpperCase();			
		}
	}
	
	
	var param1 ="blackout";
	var param2 ="divpopup_plant";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}

function action_manage_invoice_update_prev(file_name)
{	
	var order = document.getElementById('order').value;
	var targetplant=document.getElementById('targetplant').value;
	//alert("o"+order);
	if(order==6)
	{
	}
	else
	{
		var startdate = document.getElementById('startdate').value;
		var enddate = document.getElementById('enddate').value;
		if(startdate=="" || enddate=="")
		{
			alert("Please Select Date");
			return false;
		}
	}
	var poststr = "startdate="+startdate+
				"&enddate="+enddate+
				"&order="+order+
				"&targetplant="+targetplant;
	//alert("poststr="+poststr);
	showManageLoadingMessage();
	makePOSTRequest(file_name,poststr);
}

function action_manage_invoice_update(action_type)
{
	if(action_type=="tracking")
	{
		
		var vehicle_serials="";
		var vehicle_status_serial = document.invoice_form.elements['vehicle_status_serial[]'];
		if(vehicle_status_serial.length!=undefined)
		{
			for (var i=0;i<vehicle_status_serial.length;i++)
			{
				if(vehicle_status_serial[i].checked)
				{
					vehicle_serials = vehicle_serials + ""+vehicle_status_serial[i].value+",";					
				}						
			}
		}
		else
		{
			if(vehicle_status_serial.checked)
			{
				vehicle_serials = vehicle_serials + ""+vehicle_status_serial.value+",";				
			}					
		}
		//alert("live tracking of vehicle"+vehicle_serials);
		
		document.getElementById('tot_vehicle_live').value=vehicle_serials;
		var target_file='src/php/main_live_invoice.php'
		document.invoice_form_live.action = target_file; 	
		document.invoice_form_live.submit();			
	}
	
	if(action_type=="edit")
	{
		var invoice_arr_cancel = document.invoice_form.elements['invoice_serial_cancel[]'];
		var invoice_arr_close = document.invoice_form.elements['invoice_serial_close[]'];
		var delete_serials = "";
		var close_serials = "";
		var update_serials = "";
		var plant_serials = "";
		var plant_pre_serials = "";
		var approval="";
		var closetime="";
		//--lorry no---//
                var lorry_serials = "";
		var lorry_pre_serials = "";
                //--vehicle_no==//
                var vehicle_no_serials = "";
		var vehicle_no_pre_serials = "";
                //--qty==//
                var qty_kg_serials = "";
                var qty_kg_pre_serials="";
                //==fatper==//
                var fat_per_serials = "";
                var fat_per_pre_serials = "";
                //==snfper===//
                var snf_per_serials="";
                var snf_per_pre_serials="";
                //==fat kg==//
                var fat_kg_serials="";
                var fat_kg_pre_serials="";
                //==snf kg==//
                var snf_kg_serials="";
                var snf_kg_pre_serials="";
                
                var invoice_material_arr = document.invoice_form.elements['invoice_material[]'];
		var invoice_material="";
                
		var unload_arr_estimated_time = document.invoice_form.elements['unload_estimated_time[]'];
		var unload_estimatetime="";
		
		var unload_arr_estimated_datetime = document.invoice_form.elements['unload_estimated_datetime[]'];
		var unload_estimatedatetime="";
		
		var unload_arr_accept_time = document.invoice_form.elements['unload_accept_time[]'];
		var unload_accepttime="";
				
		var arr_fat_per_ft = document.invoice_form.elements['fat_per_ft[]'];
		var fat_per_ft="";
		
		var arr_snf_per_ft = document.invoice_form.elements['snf_per_ft[]'];
		var snf_per_ft="";
		
		var arr_testing_status = document.invoice_form.elements['testing_status[]'];
		var testing_status="";
		
		var arr_qty_ct = document.invoice_form.elements['qty_ct[]'];
		var qty_ct="";
		
		var arr_temp_ct = document.invoice_form.elements['temp_ct[]'];
		var temp_ct="";
		
		var arr_acidity_ct = document.invoice_form.elements['acidity_ct[]'];
		var acidity_ct="";
		
		var arr_mbrt_min_ct = document.invoice_form.elements['mbrt_min_ct[]'];
		var mbrt_min_ct="";
		
		var arr_mbrt_br_ct = document.invoice_form.elements['mbrt_br_ct[]'];
		var mbrt_br_ct="";
		
		var arr_mbrt_rm_ct = document.invoice_form.elements['mbrt_rm_ct[]'];
		var mbrt_rm_ct="";
		
		var arr_protien_per_ct = document.invoice_form.elements['protien_per_ct[]'];
		var protien_per_ct="";
		
		var arr_sodium_ct = document.invoice_form.elements['sodium_ct[]'];
		var sodium_ct="";
		
		var arr_fat_per_rt = document.invoice_form.elements['fat_per_rt[]'];
		var fat_per_rt="";
		
		var arr_snf_per_rt = document.invoice_form.elements['snf_per_rt[]'];
		var snf_per_rt="";
		
		var arr_adultration_ct = document.invoice_form.elements['adultration_ct[]'];
		var adultration_ct="";
		
		var arr_otheradultration_ct = document.invoice_form.elements['otheradultration_ct[]'];
		var otheradultration_ct="";
		
		var arr_edit_close_chk = document.invoice_form.elements['edit_close_chk[]'];
		var edit_close_chk="";
		
		
		//######## DELETE SERIALS
		if(invoice_arr_cancel.length!=undefined)
		{
			for (var i=0;i<invoice_arr_cancel.length;i++)
			{				
				update_serials = update_serials + ""+invoice_arr_cancel[i].value+",";
				plant_serials = plant_serials + ""+document.getElementById('plant_'+invoice_arr_cancel[i].value).value+",";
				plant_pre_serials = plant_pre_serials + ""+document.getElementById('plant_pre_'+invoice_arr_cancel[i].value).value+",";
				approval=approval + ""+document.getElementById('approval_'+invoice_arr_cancel[i].value).value+",";	
                                lorry_serials = lorry_serials + ""+document.getElementById('lorry_'+invoice_arr_cancel[i].value).value+",";
				lorry_pre_serials = lorry_pre_serials + ""+document.getElementById('lorry_pre_'+invoice_arr_cancel[i].value).value+",";
                                vehicle_no_serials = vehicle_no_serials + ""+document.getElementById('vehicle_no_'+invoice_arr_cancel[i].value).value+",";
				vehicle_no_pre_serials = vehicle_no_pre_serials + ""+document.getElementById('vehicle_no_pre_'+invoice_arr_cancel[i].value).value+",";
                                
                                 qty_kg_serials = qty_kg_serials + ""+document.getElementById('qty_kg_'+invoice_arr_cancel[i].value).value+",";
				qty_kg_pre_serials = qty_kg_pre_serials + ""+document.getElementById('qty_kg_pre_'+invoice_arr_cancel[i].value).value+",";
                                
                                fat_per_serials = fat_per_serials + ""+document.getElementById('fat_per_'+invoice_arr_cancel[i].value).value+",";
				fat_per_pre_serials = fat_per_pre_serials + ""+document.getElementById('fat_per_pre_'+invoice_arr_cancel[i].value).value+",";
                                
                                snf_per_serials = snf_per_serials + ""+document.getElementById('snf_per_'+invoice_arr_cancel[i].value).value+",";
				snf_per_pre_serials = snf_per_pre_serials + ""+document.getElementById('snf_per_pre_'+invoice_arr_cancel[i].value).value+",";
                               
                                fat_kg_serials = fat_kg_serials + ""+document.getElementById('fat_kg_'+invoice_arr_cancel[i].value).value+",";
				fat_kg_pre_serials = fat_kg_pre_serials + ""+document.getElementById('fat_kg_pre_'+invoice_arr_cancel[i].value).value+",";
                               
                                snf_kg_serials = snf_kg_serials + ""+document.getElementById('snf_kg_'+invoice_arr_cancel[i].value).value+",";
				snf_kg_pre_serials = snf_kg_pre_serials + ""+document.getElementById('snf_kg_pre_'+invoice_arr_cancel[i].value).value+",";
                               
			}
		}
		else
		{
			update_serials = update_serials + ""+invoice_arr_cancel.value+",";
			plant_serials = plant_serials + ""+document.getElementById('plant_'+invoice_arr_cancel.value).value+","	;
			plant_pre_serials = plant_pre_serials + ""+document.getElementById('plant_pre_'+invoice_arr_cancel.value).value+","	;
			approval=approval + ""+document.getElementById('approval_'+invoice_arr_cancel.value).value+",";	
                        lorry_serials = lorry_serials + ""+document.getElementById('lorry_'+invoice_arr_cancel.value).value+","	;
			lorry_pre_serials = lorry_pre_serials + ""+document.getElementById('lorry_pre_'+invoice_arr_cancel.value).value+",";
                        vehicle_no_serials = vehicle_no_serials + ""+document.getElementById('vehicle_no_'+invoice_arr_cancel.value).value+","	;
			vehicle_no_pre_serials = vehicle_no_pre_serials + ""+document.getElementById('vehicle_no_pre_'+invoice_arr_cancel.value).value+",";
                        
                         qty_kg_serials = qty_kg_serials + ""+document.getElementById('qty_kg_'+invoice_arr_cancel.value).value+",";
                        qty_kg_pre_serials = qty_kg_pre_serials + ""+document.getElementById('qty_kg_pre_'+invoice_arr_cancel.value).value+",";

                        fat_per_serials = fat_per_serials + ""+document.getElementById('fat_per_'+invoice_arr_cancel.value).value+",";
                        fat_per_pre_serials = fat_per_pre_serials + ""+document.getElementById('fat_per_pre_'+invoice_arr_cancel.value).value+",";

                        snf_per_serials = snf_per_serials + ""+document.getElementById('snf_per_'+invoice_arr_cancel.value).value+",";
                        snf_per_pre_serials = snf_per_pre_serials + ""+document.getElementById('snf_per_pre_'+invoice_arr_cancel.value).value+",";

                        fat_kg_serials = fat_kg_serials + ""+document.getElementById('fat_kg_'+invoice_arr_cancel.value).value+",";
                        fat_kg_pre_serials = fat_kg_pre_serials + ""+document.getElementById('fat_kg_pre_'+invoice_arr_cancel.value).value+",";

                        snf_kg_serials = snf_kg_serials + ""+document.getElementById('snf_kg_'+invoice_arr_cancel.value).value+",";
                        snf_kg_pre_serials = snf_kg_pre_serials + ""+document.getElementById('snf_kg_pre_'+invoice_arr_cancel.value).value+",";
		}

		//######## CANCEL SERIALS
		if(invoice_arr_cancel.length!=undefined)
		{
			for (var i=0;i<invoice_arr_cancel.length;i++)
			{
				if(invoice_arr_cancel[i].checked)
				{
					delete_serials = delete_serials + ""+invoice_arr_cancel[i].value+",";					
				}						
			}
		}
		else
		{
			if(invoice_arr_cancel.checked)
			{
				delete_serials = delete_serials + ""+invoice_arr_cancel.value+",";				
			}					
		}		
		//######## CLOSE SERIALS
		if(invoice_arr_close.length!=undefined)
		{
			for (var i=0;i<invoice_arr_close.length;i++)
			{
				if(invoice_arr_close[i].checked)
				{					
					close_serials = close_serials + invoice_arr_close[i].value+",";	
					closetime=closetime + ""+document.getElementById('closetime_'+invoice_arr_close[i].value).value+",";
				}							
			}
		}
		else
		{
			if(invoice_arr_close.checked)
			{
				close_serials = close_serials + invoice_arr_close.value+",";
				closetime=closetime + ""+document.getElementById('closetime_'+invoice_arr_close.value).value+",";
			}					
		}
		
		if(unload_arr_estimated_time.length!=undefined)
		{
			for (var i=0;i<unload_arr_estimated_time.length;i++)
			{	
                                invoice_material = invoice_material + ""+invoice_material_arr[i].value+",";
				unload_estimatetime = unload_estimatetime + ""+unload_arr_estimated_time[i].value+",";
				unload_estimatedatetime = unload_estimatedatetime + ""+unload_arr_estimated_datetime[i].value+",";
				unload_accepttime = unload_accepttime + ""+unload_arr_accept_time[i].value+",";
				
				fat_per_ft = fat_per_ft + ""+arr_fat_per_ft[i].value+",";
				snf_per_ft = snf_per_ft + ""+arr_snf_per_ft[i].value+",";
				testing_status = testing_status + ""+arr_testing_status[i].value+",";
				qty_ct = qty_ct + ""+arr_qty_ct[i].value+",";
				temp_ct = temp_ct + ""+arr_temp_ct[i].value+",";
				acidity_ct = acidity_ct + ""+arr_acidity_ct[i].value+",";
				mbrt_min_ct = mbrt_min_ct + ""+arr_mbrt_min_ct[i].value+",";
				mbrt_br_ct = mbrt_br_ct + ""+arr_mbrt_br_ct[i].value+",";
				mbrt_rm_ct = mbrt_rm_ct + ""+arr_mbrt_rm_ct[i].value+",";
				protien_per_ct = protien_per_ct + ""+arr_protien_per_ct[i].value+",";
				sodium_ct = sodium_ct + ""+arr_sodium_ct[i].value+",";
				fat_per_rt = fat_per_rt + ""+arr_fat_per_rt[i].value+",";
				snf_per_rt = snf_per_rt + ""+arr_snf_per_rt[i].value+",";
				
				adultration_ct=adultration_ct+ ""+arr_adultration_ct[i].value+":";
				otheradultration_ct=otheradultration_ct+ ""+arr_otheradultration_ct[i].value+":";
				edit_close_chk=edit_close_chk+ ""+arr_edit_close_chk[i].value+",";		
			}
		}
		else
		{
                        invoice_material = invoice_material + ""+invoice_material_arr.value+",";
			unload_estimatetime = unload_estimatetime + ""+unload_arr_estimated_time.value+",";
			unload_estimatedatetime = unload_estimatedatetime + ""+unload_arr_estimated_datetime.value+",";
			unload_accepttime = unload_accepttime + ""+unload_arr_accept_time.value+",";
			
			fat_per_ft = fat_per_ft + ""+arr_fat_per_ft.value+",";
			snf_per_ft = snf_per_ft + ""+arr_snf_per_ft.value+",";
			testing_status = testing_status + ""+arr_testing_status.value+",";
			qty_ct = qty_ct + ""+arr_qty_ct.value+",";
			temp_ct = temp_ct + ""+arr_temp_ct.value+",";
			acidity_ct = acidity_ct + ""+arr_acidity_ct.value+",";
			mbrt_min_ct = mbrt_min_ct + ""+arr_mbrt_min_ct.value+",";
			mbrt_br_ct = mbrt_br_ct + ""+arr_mbrt_br_ct.value+",";
			mbrt_rm_ct = mbrt_rm_ct + ""+arr_mbrt_rm_ct.value+",";
			protien_per_ct = protien_per_ct + ""+arr_protien_per_ct.value+",";
			sodium_ct = sodium_ct + ""+arr_sodium_ct.value+",";
			fat_per_rt = fat_per_rt + ""+arr_fat_per_rt.value+",";
			snf_per_rt = snf_per_rt + ""+arr_snf_per_rt.value+",";
			adultration_ct=adultration_ct+ ""+arr_adultration_ct.value+":";
			otheradultration_ct=otheradultration_ct+ ""+arr_otheradultration_ct.value+":";
			edit_close_chk=edit_close_chk+ ""+arr_edit_close_chk.value+",";
							
		}
		
		//alert(edit_close_chk);
		
		
		var poststr ="action_type="+action_type+
					"&update_serials="+update_serials+
					"&plant_serials="+plant_serials+
					"&plant_pre_serials="+plant_pre_serials+
                                        "&lorry_serials="+lorry_serials+
					"&lorry_pre_serials="+lorry_pre_serials+
                                        "&vehicle_no_serials="+vehicle_no_serials+
					"&vehicle_no_pre_serials="+vehicle_no_pre_serials+
                                        
                                        "&qty_kg_serials="+qty_kg_serials+
					"&qty_kg_pre_serials="+qty_kg_pre_serials+
                                        
                                        "&fat_per_serials="+fat_per_serials+
					"&fat_per_pre_serials="+fat_per_pre_serials+
                                        
                                        "&snf_per_serials="+snf_per_serials+
					"&snf_per_pre_serials="+snf_per_pre_serials+
                                        
                                        "&fat_kg_serials="+fat_kg_serials+
					"&fat_kg_pre_serials="+fat_kg_pre_serials+
                                        
                                        "&snf_kg_serials="+snf_kg_serials+
					"&snf_kg_pre_serials="+snf_kg_pre_serials+
                                        
					"&close_serials="+close_serials+
					"&closetime_serials="+ closetime +
					"&approval_serials="+ approval +
                                        "&invoice_material_serials="+ invoice_material +
					"&unload_estimatetime_serials="+ unload_estimatetime +
					"&unload_estimatedatetime_serials="+ unload_estimatedatetime +
					"&unload_accepttime_serials="+ unload_accepttime +
					
					"&fat_per_ft_serials="+ fat_per_ft +
					"&snf_per_ft_serials="+ snf_per_ft +
					
					"&testing_status_serials="+ testing_status +
					"&qty_ct_serials="+ qty_ct +
					
					"&temp_ct_serials="+ temp_ct +
					"&acidity_ct_serials="+ acidity_ct +
					
					"&mbrt_min_ct_serials="+ mbrt_min_ct +
					"&mbrt_br_ct_serials="+ mbrt_br_ct +
					
					"&mbrt_rm_ct_serials="+ mbrt_rm_ct +
					"&protien_per_ct_serials="+ protien_per_ct +
					
					"&sodium_ct_serials="+ sodium_ct +
					"&fat_per_rt_serials="+ fat_per_rt +
					
					"&snf_per_rt_serials="+ snf_per_rt +
					
					"&adultration_ct_serials="+ adultration_ct +
					"&otheradultration_ct_serials="+ otheradultration_ct +
					
					"&edit_close_chk_serials="+ edit_close_chk +
					
					
					"&approval_serials="+ approval +
					 "&delete_serials="+delete_serials;	
		//alert(poststr);
	}
	/*else if(action_type=="delete")	//subuser
	{
		var invoice_arr = document.invoice_form.elements['invoice_serial[]'];
		var delete_serials = "";
		if(invoice_arr.length!=undefined)
		{
			for (var i=0;i<invoice_arr.length;i++)
			{
				if(invoice_arr[i].checked)
				{
					delete_serials = delete_serials + ""+invoice_arr[i].value+",";
				}
			}
		}
		
		var poststr ="action_type="+action_type+
					 "&delete_serials="+delete_serials;
		//alert("poststr="+poststr);		
	}*/
	
	makePOSTRequest('src/php/action_manage_invoice_raw_milk.htm', poststr);		
}

function action_manage_pending_tanker_show(action_type)
{
	if(action_type=='edit')
	{
		//alert("one");
		var vid_chk="";
		var sno_chk_arr = document.pending_form.elements['sno_chk[]'];
		//alert("two");
		var vid_update_pre="";
		var remarks="";
		var update_status="";
		var type_new_old="";
		var vname="";
		//alert("one"+sno_chk_arr);
		if(sno_chk_arr!=undefined)
		{
			if(sno_chk_arr.length!=undefined)
			{
				//alert("two1");
				for (var i=0;i<sno_chk_arr.length;i++)
				{
					//alert("two");
					if(sno_chk_arr[i].checked)
					{	
						//alert(document.getElementById('sno_chk_'+sno_chk_arr[i].value).value);
						//alert(sno_chk_arr[i].value);
						vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr[i].value).value+",";	
						vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr[i].value).value+",";
						remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr[i].value).value+",";
						update_status=update_status + "1,"; 
						type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr[i].value).value+",";
						vname=vname + ""+document.getElementById('vname'+sno_chk_arr[i].value).value+",";
						//alert('vname'+sno_chk_arr[i].value);
						//alert(document.getElementById('vname'+sno_chk_arr[i].value).value);
					}
					else
					{
						if(document.getElementById('sno_update_'+sno_chk_arr[i].value).value !='0' ) //checked previous pending tanker status=1
						{
							vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr[i].value).value+",";	
							vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr[i].value).value+",";
							remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr[i].value).value+",";
							update_status=update_status + "0,"; 
							type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr[i].value).value+",";
							vname=vname + ""+document.getElementById('vname'+sno_chk_arr[i].value).value+",";
						}
					}
				}
			}
			else
			{
				//alert("two2");
				if(sno_chk_arr.checked)
				{
					vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr.value).value+",";	
					vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr.value).value+",";
					remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr.value).value+",";
					update_status=update_status + "1,"; 
					type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr.value).value+",";
					vname=vname + ""+document.getElementById('vname'+sno_chk_arr.value).value+",";
				}
				else
				{
					if(document.getElementById('sno_update_'+sno_chk_arr.value).value !='0' ) //checked previous pending tanker status=1
					{
						vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr.value).value+",";	
						vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr.value).value+",";
						remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr.value).value+",";
						update_status=update_status + "0,"; 
						type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr.value).value+",";
						vname=vname + ""+document.getElementById('vname'+sno_chk_arr.value).value+",";
					}
				}		
			}
		}
		
	}
	
	//gettng nongps new entry
	//alert(document.getElementById('gpsvlist').value);
	
	
	var nongps_flag=0;
	//alert(document.getElementById('vehicle_list_from_db').value);
	var vehicle_list_from_db=document.getElementById('vehicle_list_from_db').value.split(",");
	//alert("one");
	for(j=0; j< vehicle_list_from_db.length;j++)
	{
		if(vehicle_list_from_db[j]==document.getElementById('vehno').value)
		{
			alert("Same Entry of Vehicle is also in Pending List. Remove the Previous Pending Order of that Vehicles");
			return false;
		
		}
	}
	if(document.getElementById('vehno').value !="" && document.getElementById('veh_rem').value !="" )
	{
		
		var gpsvlist=document.getElementById('gpsvlist').value.split(",");
		for(i=0; i< gpsvlist.length;i++)
		{
			if(gpsvlist[i]==document.getElementById('vehno').value)
			{				
				nongps_flag=0;				
				break;
			}
			else
			{
				nongps_flag=1;
				
			}
		}
		
		
	}
	//alert("two");
	if(nongps_flag==1)
	{
		var ngps_vehicle=document.getElementById('vehno').value;
		ngps_vehicle=ngps_vehicle.replace(/ /g,'');
		//alert(ngps_vehicle);
		vid_chk=vid_chk+"N_"+document.getElementById('vehno').value +",";
		vid_update_pre=vid_update_pre + "0,";
		remarks=remarks + ""+document.getElementById('veh_rem').value+",";
		update_status=update_status + "1,"; 
		type_new_old=type_new_old + "1,";
		vname=vname + ""+ngps_vehicle+",";
	}
	else
	{
		vid_chk = vid_chk + document.getElementById('vehno').value +",";
		vid_update_pre=vid_update_pre + "0,";
		remarks=remarks + ""+document.getElementById('veh_rem').value+",";
		update_status=update_status + "1,"; 
		type_new_old=type_new_old + "1,";
		vname=vname + ""+document.getElementById('vehno').value +",";
	}
	//alert("three");
	var poststr ="action_type="+action_type+
					"&remarks="+remarks+
					"&vid_update_pre="+vid_update_pre+
					"&update_status="+update_status+
					"&type_new_old="+type_new_old+
					"&vname="+vname+
					"&vid_chk="+vid_chk;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/action_manage_pending_tanker_show.htm', poststr);	
}
/*
function action_manage_pending_tanker_show_backup(action_type)
{
	if(action_type=='edit')
	{
		var vid_chk="";
		var sno_chk_arr = document.pending_form.elements['sno_chk[]'];
		var vid_update_pre="";
		var remarks="";
		var update_status="";
		var type_new_old="";
		var vname="";
		
		if(sno_chk_arr.length!=undefined)
		{
			for (var i=0;i<sno_chk_arr.length;i++)
			{
				if(sno_chk_arr[i].checked)
				{	
					//alert(document.getElementById('sno_chk_'+sno_chk_arr[i].value).value);
					//alert(sno_chk_arr[i].value);
					vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr[i].value).value+",";	
					vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr[i].value).value+",";
					remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr[i].value).value+",";
					update_status=update_status + "1,"; 
					type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr[i].value).value+",";
					vname=vname + ""+document.getElementById('vname'+sno_chk_arr[i].value).value+",";
					//alert('vname'+sno_chk_arr[i].value);
					//alert(document.getElementById('vname'+sno_chk_arr[i].value).value);
				}
				else
				{
					if(document.getElementById('sno_update_'+sno_chk_arr[i].value).value !='0' ) //checked previous pending tanker status=1
					{
						vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr[i].value).value+",";	
						vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr[i].value).value+",";
						remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr[i].value).value+",";
						update_status=update_status + "0,"; 
						type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr[i].value).value+",";
						vname=vname + ""+document.getElementById('vname'+sno_chk_arr[i].value).value+",";
					}
				}
			}
		}
		else
		{
			if(sno_chk_arr.checked)
			{
				vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr.value).value+",";	
				vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr.value).value+",";
				remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr.value).value+",";
				update_status=update_status + "1,"; 
				type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr.value).value+",";
				vname=vname + ""+document.getElementById('vname'+sno_chk_arr.value).value+",";
			}
			else
			{
				if(document.getElementById('sno_update_'+sno_chk_arr.value).value !='0' ) //checked previous pending tanker status=1
				{
					vid_chk = vid_chk + document.getElementById('sno_chk_'+sno_chk_arr.value).value+",";	
					vid_update_pre=vid_update_pre + ""+document.getElementById('sno_update_'+sno_chk_arr.value).value+",";
					remarks=remarks + ""+document.getElementById('sno_remarks_'+sno_chk_arr.value).value+",";
					update_status=update_status + "0,"; 
					type_new_old=type_new_old + ""+document.getElementById('type_new_old_'+sno_chk_arr.value).value+",";
					vname=vname + ""+document.getElementById('vname'+sno_chk_arr.value).value+",";
				}
			}		
		}
	}
	
	//gettng nongps new entry
	//alert(document.getElementById('gpsvlist').value);
	
	
	var nongps_flag=0;
	if(document.getElementById('NGPS').value !="" && document.getElementById('NREM').value !="" )
	{
		var gpsvlist=document.getElementById('gpsvlist').value.split(",");
		for(i=0; i< gpsvlist.length;i++)
		{
			if(gpsvlist[i]==document.getElementById('NGPS').value)
			{
				alert("Vehicle "+ gpsvlist[i] +" you entered in the NonGPS Field is already A GPS Vehicle.Please Enter non gps vehicle or leave Blank!");
				nongps_flag=0;
				return false;
				break;
			}
			else
			{
				nongps_flag=1;
				
			}
		}
		
		
	}
	
	if(nongps_flag==1)
	{
		var ngps_vehicle=document.getElementById('NGPS').value;
		ngps_vehicle=ngps_vehicle.replace(/ /g,'');
		//alert(ngps_vehicle);
		vid_chk=vid_chk+"N_"+document.getElementById('NGPS').value +",";
		vid_update_pre=vid_update_pre + "0,";
		remarks=remarks + ""+document.getElementById('NREM').value+",";
		update_status=update_status + "1,"; 
		type_new_old=type_new_old + "1,";
		vname=vname + ""+ngps_vehicle+",";
	}
	
	var poststr ="action_type="+action_type+
					"&remarks="+remarks+
					"&vid_update_pre="+vid_update_pre+
					"&update_status="+update_status+
					"&type_new_old="+type_new_old+
					"&vname="+vname+
					"&vid_chk="+vid_chk;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/action_manage_pending_tanker_show.htm', poststr);	
}
*/

function action_manage_invoice_raw_milk(){
   //alert("hello");
   var flag=0;
   var sno_id="";
   var lrno="";
   var vehno="";
   var transporter="";
   var email="";   
   var mobile=""; 
   
   var qty="";
   var fat_per="";
   var snf_per="";   
   var fat_kg=""; 
   
   var snf_kg="";
   var milk_age="";
   var disp_time="";
   var target_time="";   
   var docketflag=""; 
   
    var plant=""; 
	var chillplant=""; 
	var driver=""; 
	var drivermobile=""; 
    var tankertype="";
  
   var tnum=document.getElementById('tnum').value;
   if(document.getElementById('lrno:0').value==""){
       alert("Please Enter Field Value");
       return false;
   }
   for(i=0;i<=parseInt(tnum);i++){
       //if(document.getElementById('vehno:'+i).value!="" ){
	   if(document.getElementById('vehno:'+i).value!="" || document.getElementById('transporter:'+i).value!="select" ){
			sno_id=sno_id + document.getElementById('sno_id:'+i).value+",";
			lrno=lrno + document.getElementById('lrno:'+i).value+",";
			vehno=vehno +document.getElementById('vehno:'+i).value +",";
			transporter=transporter +document.getElementById('transporter:'+i).value +",";
			email=email +document.getElementById('email:'+i).value +",";
			mobile=mobile +document.getElementById('mobile:'+i).value +",";
			
			driver=driver +document.getElementById('driver:'+i).value +",";
			drivermobile=drivermobile +document.getElementById('drivermobile:'+i).value +",";
			
			qty=qty + document.getElementById('qty:'+i).value +",";
			fat_per=fat_per +document.getElementById('fat_per:'+i).value +",";
			snf_per=snf_per +document.getElementById('snf_per:'+i).value +",";
			fat_kg=fat_kg +document.getElementById('fat_kg:'+i).value +",";
			
			snf_kg=snf_kg + document.getElementById('snf_kg:'+i).value +",";
			milk_age=milk_age + document.getElementById('milk_age:'+i).value +",";
			disp_time=disp_time +document.getElementById('disp_time:'+i).value +",";
			target_time=target_time +document.getElementById('target_time:'+i).value +",";
			plant=plant +document.getElementById('plant:'+i).value +",";
			chillplant=chillplant +document.getElementById('chillplant:'+i).value +",";
			docketflag=docketflag +document.getElementById('docketflag:'+i).value +",";
			
			tankertype=tankertype +document.getElementById('tankertype:'+i).value +",";
       }       
   }
   document.getElementById('offset_sno_id').value=sno_id;
   document.getElementById('offset_lrno').value=lrno;
   document.getElementById('offset_vehno').value=vehno;
   document.getElementById('offset_transporter').value=transporter;
   document.getElementById('offset_email').value=email;
   document.getElementById('offset_mobile').value=mobile;
   
   document.getElementById('offset_qty').value=qty;
   document.getElementById('offset_fat_per').value=fat_per;
   document.getElementById('offset_snf_per').value=snf_per;
   document.getElementById('offset_fat_kg').value=fat_kg;
   
   document.getElementById('offset_snf_kg').value=snf_kg;
   document.getElementById('offset_milk_age').value=milk_age;
   document.getElementById('offset_disp_time').value=disp_time;
   document.getElementById('offset_target_time').value=target_time;
   document.getElementById('offset_docketflag').value=docketflag;
   
   document.getElementById('offset_driver').value=driver;
   document.getElementById('offset_drivermobile').value=drivermobile;
   document.getElementById('offset_plant').value=plant;
   document.getElementById('offset_chillplant').value=chillplant;
   
   document.getElementById('offset_tankertype').value=tankertype;
   
   //var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
   var iChars = ",";
   for(j=0;j<=parseInt(tnum);j++){
        if(document.getElementById('lrno:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('lrno:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('lrno:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('lrno:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('lrno:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
             return false;
        }
        if(document.getElementById('vehno:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('vehno:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('vehno:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('vehno:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('vehno:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
        /* else{
            alert("Field Required");
            return false;
        }*/
		//transporter
		if(document.getElementById('transporter:'+j).value!="select" ){
            
            for (var k = 0; k < document.getElementById('transporter:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('transporter:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('transporter:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('transporter:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
		else{
            alert("Transporter Field Required");
            return false;
        }
		//email
		if(document.getElementById('email:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('email:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('email:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('email:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('email:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//mobile
		if(document.getElementById('mobile:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('mobile:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('mobile:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('mobile:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('mobile:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            //alert("Field Required");
            //return false;
        }
		
		//driver
		if(document.getElementById('driver:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('driver:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('driver:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('driver:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('driver:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//drivermobile
		if(document.getElementById('drivermobile:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('drivermobile:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('drivermobile:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('drivermobile:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('drivermobile:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//plant
		//alert(document.getElementById('plant:'+j).value);
		if(document.getElementById('plant:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('plant:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('plant:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('plant:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('plant:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
		else{
            alert("Field Required");
            return false;
        }
		
		//chillplant
		//alert(document.getElementById('chillplant:'+j).value);
		if(document.getElementById('chillplant:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('chillplant:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('chillplant:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('chillplant:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('chillplant:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
		else{
            alert("Field Required");
            return false;
        }
        
		
		//qty
		if(document.getElementById('qty:'+j).value!=""){
            if(!IsNumericA(document.getElementById('qty:'+j).value,'qty:'+j))
			{
				alert("Please Enter Value in Numeric for Kg Field");
				document.getElementById('qty:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('qty:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('qty:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('qty:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('qty:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//fat_per
		if(document.getElementById('fat_per:'+j).value!=""){
            if(!IsNumericA(document.getElementById('fat_per:'+j).value,'fat_per:'+j))
			{
				alert("Please Enter Value in Numeric for fat per Field");
				document.getElementById('fat_per:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('fat_per:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('fat_per:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('fat_per:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('fat_per:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//snf_per
		if(document.getElementById('snf_per:'+j).value!=""){
            if(!IsNumericA(document.getElementById('snf_per:'+j).value,'snf_per:'+j))
			{
				alert("Please Enter Value in Numeric for snf Percent Field");
				document.getElementById('snf_per:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('snf_per:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('snf_per:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('snf_per:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('snf_per:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//fat_kg
		if(document.getElementById('fat_kg:'+j).value!=""){
            if(!IsNumericA(document.getElementById('fat_kg:'+j).value,'fat_kg:'+j))
			{
				alert("Please Enter Value in Numeric for fat kg Field");
				document.getElementById('fat_kg:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('fat_kg:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('fat_kg:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('fat_kg:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('fat_kg:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//snf_kg
		if(document.getElementById('snf_kg:'+j).value!=""){
            if(!IsNumericA(document.getElementById('snf_kg:'+j).value,'snf_kg:'+j))
			{
				alert("Please Enter Value in Numeric for snf kg Field");
				document.getElementById('snf_kg:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('snf_kg:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('snf_kg:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('snf_kg:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('snf_kg:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//milk_age
		if(document.getElementById('milk_age:'+j).value!=""){
            if(!IsNumericA(document.getElementById('milk_age:'+j).value,'milk_age:'+j))
			{
				alert("Please Enter Value in Numeric for milk_age Field");
				document.getElementById('milk_age:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('milk_age:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('milk_age:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('milk_age:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('milk_age:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
			if(!IsNumeric(document.getElementById('milk_age:'+j).value)){
				alert ("Please Enter Milk Age Value in Hours (Decimal Format).");
				return false;
			}
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//disp_time
		if(document.getElementById('disp_time:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('disp_time:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('disp_time:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('disp_time:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('disp_time:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                } 
					
            }
			var datetime=document.getElementById('disp_time:'+j).value.split(" ");
			if(datetime[1]=="00:00:00"){
				alert("Dispatch Time not allowed  00:00:00, Please Increment the time");
				document.getElementById('disp_time:'+j).focus();
				return false;
			}
			//---new code update on 09 sept 2014
			/*var startHour = new Date(datetime[0] +" "+ datetime[1]);
			var endHour =  new Date();
			
			var hr_diff_sec=(endHour-startHour)/1000;
			var hr_diff=parseInt(hr_diff_sec/3600);
			//alert(hr_diff);
			if(hr_diff > 24){
				alert("Dispatch Time is not allowed more than 24 Hours back from Current Invoice Entry Time ");
				document.getElementById('disp_time:'+j).focus();
				document.getElementById('disp_time:'+j).value="";
				return false;
			}*/
			//---end of code updated---
			//---new code update on 25 may 2015
			var startHour = new Date(datetime[0] +" "+ datetime[1]);
			var endHour =  new Date();
			
			var hr_diff_sec=(endHour-startHour)/1000;
			var hr_diff=parseInt(hr_diff_sec/3600);
			//alert(hr_diff);
			if(hr_diff > 120){ //5 day
				alert("Dispatch Time is not allowed more than 120 Hours (5 day) back from Current Invoice Entry Time ");
				document.getElementById('disp_time:'+j).focus();
				document.getElementById('disp_time:'+j).value="";
				return false;
			}
			
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//target_time
		if(document.getElementById('target_time:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('target_time:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('target_time:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('target_time:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('target_time:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
			//checking 00:00:00
			var datetime=document.getElementById('target_time:'+j).value.split(" ");
			if(datetime[1]=="00:00:00"){
				alert("Target Time not allowed  00:00:00, Please Increment the time");
				document.getElementById('target_time:'+j).focus();
				return false;
			}
        }
         else{
            alert("Field Required");
            return false;
        }
		
		if(document.getElementById('target_time:'+j).value < document.getElementById('disp_time:'+j).value)
		{
			alert("Target time must be greater than or equal to dispatch time");
			return false;
		}
		
		
		
		if(document.getElementById('tankertype:'+j).value!=""){
            
           //do nothing
        }
         else{
            alert("Field Required");
            return false;
        }
		
		
		
		
        
   }
   /*
   if(flag==1){
       return false;
   }
   else{
       return true;
	   
		//makePOSTRequest('src/php/action_manage_invoice_raw_milk.htm', poststr); 
   }*/
	   var offset_sno_id="";
	   var offset_lrno ="";
	   var offset_vehno ="";
	   var offset_transporter="";
	   var offset_email ="";
	   var offset_mobile ="";
	   var offset_qty ="";
	   var offset_fat_per ="";
	   var offset_snf_per ="";
	   var offset_fat_kg ="";
	   var offset_snf_kg ="";
	   var offset_milk_age ="";
	   
	   var offset_disp_time ="";
	   var offset_target_time ="";
	   var offset_docketflag ="";
	   
	   var offset_driver ="";
	   var offset_drivermobile ="";
	   var offset_plant ="";
	   var offset_chillplant ="";
	   var offset_tankertype="";
	   
	   offset_sno_id=document.getElementById('offset_sno_id').value;
	   offset_lrno=document.getElementById('offset_lrno').value;
	   offset_vehno=document.getElementById('offset_vehno').value;
	   offset_transporter=document.getElementById('offset_transporter').value;
	   offset_email=document.getElementById('offset_email').value;
	   offset_mobile=document.getElementById('offset_mobile').value;
	   offset_qty=document.getElementById('offset_qty').value;	
	   offset_fat_per=document.getElementById('offset_fat_per').value;
	   offset_snf_per=document.getElementById('offset_snf_per').value;
	   offset_fat_kg=document.getElementById('offset_fat_kg').value;
	   offset_snf_kg=document.getElementById('offset_snf_kg').value;
	   offset_milk_age=document.getElementById('offset_milk_age').value;
	   
	   offset_disp_time=document.getElementById('offset_disp_time').value;
	   offset_target_time=document.getElementById('offset_target_time').value;
	   offset_docketflag=document.getElementById('offset_docketflag').value;
	   
	   offset_driver=document.getElementById('offset_driver').value;
	   offset_drivermobile=document.getElementById('offset_drivermobile').value;
	   offset_plant=document.getElementById('offset_plant').value;
	   offset_chillplant=document.getElementById('offset_chillplant').value;
	   offset_tankertype=document.getElementById('offset_tankertype').value;
	   var vehicle_list_hidden="";
	   vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;
	   
	   
	   
	   var poststr ="action_type=add"+
					 "&offset_sno_id="+offset_sno_id+
					 "&offset_lrno="+offset_lrno+
					 "&offset_vehno="+offset_vehno+
					 "&offset_transporter="+offset_transporter+
					 "&offset_email="+offset_email+
					 "&offset_mobile="+offset_mobile+
					 "&offset_qty="+offset_qty+
					 "&offset_fat_per="+offset_fat_per+
					 "&offset_snf_per="+offset_snf_per+
					 "&offset_fat_kg="+offset_fat_kg+
					 "&offset_snf_kg="+offset_snf_kg+
					 "&offset_milk_age="+offset_milk_age+
					 "&offset_disp_time="+offset_disp_time+
					 "&offset_target_time="+offset_target_time+					
					 "&offset_docketflag="+offset_docketflag+
					 "&vehicle_list="+vehicle_list_hidden+
					 "&offset_driver="+offset_driver+					
					 "&offset_drivermobile="+offset_drivermobile+
					 "&offset_plant="+offset_plant+
					 "&offset_chillplant="+offset_chillplant+
					 "&offset_tankertype="+offset_tankertype;
		//alert("poststr="+poststr);
		document.getElementById('enter_button').disabled=true;
		document.getElementById('loading_status').style.display="";
		document.getElementById('loading_status').innerHTML = '<font color=blue>Please wait..</font>';
		
		
		
		makePOSTRequest('src/php/action_manage_invoice_raw_milk.htm', poststr); 
   
   
}

function action_manage_invoice_raw_milk_from_admin(){
   //alert("System Updation...Please try after some time");
   var flag=0;
   var sno_id="";
   var lrno="";
   var vehno="";
   var transporter="";
   var email="";   
   var mobile=""; 
   
   var qty="";
   var fat_per="";
   var snf_per="";   
   var fat_kg=""; 
   
   var snf_kg="";
   var milk_age="";
   var disp_time="";
   var target_time="";   
   var docketflag=""; 
   
    var plant=""; 
	var chillplant=""; 
	var driver=""; 
	var drivermobile=""; 
    
	var tankertype="";
  
   var tnum=document.getElementById('tnum').value;
   if(document.getElementById('lrno:0').value==""){
       alert("Please Enter Field Value");
       return false;
   }
   for(i=0;i<=parseInt(tnum);i++){
       if(document.getElementById('vehno:'+i).value!="" || document.getElementById('transporter:'+i).value!="select" ){
			sno_id=sno_id + document.getElementById('sno_id:'+i).value+",";
			lrno=lrno + document.getElementById('lrno:'+i).value+",";
			vehno=vehno +document.getElementById('vehno:'+i).value +",";
			transporter=transporter +document.getElementById('transporter:'+i).value +",";
			email=email +document.getElementById('email:'+i).value +",";
			mobile=mobile +document.getElementById('mobile:'+i).value +",";
			
			driver=driver +document.getElementById('driver:'+i).value +",";
			drivermobile=drivermobile +document.getElementById('drivermobile:'+i).value +",";
			
			qty=qty + document.getElementById('qty:'+i).value +",";
			fat_per=fat_per +document.getElementById('fat_per:'+i).value +",";
			snf_per=snf_per +document.getElementById('snf_per:'+i).value +",";
			fat_kg=fat_kg +document.getElementById('fat_kg:'+i).value +",";
			
			snf_kg=snf_kg + document.getElementById('snf_kg:'+i).value +",";
			milk_age=milk_age + document.getElementById('milk_age:'+i).value +",";
			disp_time=disp_time +document.getElementById('disp_time:'+i).value +",";
			target_time=target_time +document.getElementById('target_time:'+i).value +",";
			plant=plant +document.getElementById('plant:'+i).value +",";
			chillplant=chillplant +document.getElementById('chillplant:'+i).value +",";
			tankertype=tankertype +document.getElementById('tankertype:'+i).value +",";
			docketflag=docketflag +document.getElementById('docketflag:'+i).value +",";
       }
	   else
	   {
			alert("Either Select transporter or Enter Vehicle from Invoice Form");
			return false;
	   }
   }
   document.getElementById('offset_sno_id').value=sno_id;
   document.getElementById('offset_lrno').value=lrno;
   document.getElementById('offset_vehno').value=vehno;
   document.getElementById('offset_transporter').value=transporter;
   document.getElementById('offset_email').value=email;
   document.getElementById('offset_mobile').value=mobile;
   
   document.getElementById('offset_qty').value=qty;
   document.getElementById('offset_fat_per').value=fat_per;
   document.getElementById('offset_snf_per').value=snf_per;
   document.getElementById('offset_fat_kg').value=fat_kg;
   
   document.getElementById('offset_snf_kg').value=snf_kg;
   document.getElementById('offset_milk_age').value=milk_age;
   document.getElementById('offset_disp_time').value=disp_time;
   document.getElementById('offset_target_time').value=target_time;
   document.getElementById('offset_docketflag').value=docketflag;
   
   document.getElementById('offset_driver').value=driver;
   document.getElementById('offset_drivermobile').value=drivermobile;
   document.getElementById('offset_plant').value=plant;
   document.getElementById('offset_chillplant').value=chillplant;
   document.getElementById('offset_tankertype').value=tankertype;
   
   //var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
   var iChars = ",";
   for(j=0;j<=parseInt(tnum);j++){
        if(document.getElementById('lrno:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('lrno:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('lrno:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('lrno:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('lrno:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
             return false;
        }
        if(document.getElementById('vehno:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('vehno:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('vehno:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('vehno:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('vehno:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
        /* else{
            alert("Field Required");
            return false;
        }*/
		//transporter
		if(document.getElementById('transporter:'+j).value!="select" ){
            
            for (var k = 0; k < document.getElementById('transporter:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('transporter:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('transporter:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('transporter:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
		else{
            alert("Transporter Required");
            return false;
        }
		//email
		if(document.getElementById('email:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('email:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('email:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('email:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('email:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//mobile
		if(document.getElementById('mobile:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('mobile:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('mobile:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('mobile:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('mobile:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            //alert("Field Required");
            //return false;
        }
		
		//driver
		if(document.getElementById('driver:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('driver:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('driver:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('driver:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('driver:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//drivermobile
		if(document.getElementById('drivermobile:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('drivermobile:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('drivermobile:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('drivermobile:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('drivermobile:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//plant
		if(document.getElementById('plant:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('plant:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('plant:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('plant:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('plant:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
		 else{
            alert("Field Required");
            return false;
        }
		
		//chillplant
		if(document.getElementById('chillplant:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('chillplant:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('chillplant:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('chillplant:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('chillplant:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
		 else{
            alert("Field Required");
            return false;
        }
        
		
		//qty
		if(document.getElementById('qty:'+j).value!=""){
            if(!IsNumericA(document.getElementById('qty:'+j).value,'qty:'+j))
			{
				alert("Please Enter Value in Numeric for Kg Field");
				document.getElementById('fat_per:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('qty:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('qty:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('qty:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('qty:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//fat_per
		if(document.getElementById('fat_per:'+j).value!=""){
            if(!IsNumericA(document.getElementById('fat_per:'+j).value,'fat_per:'+j))
			{
				alert("Please Enter Value in Numeric for Fat Percent Field");
				document.getElementById('fat_per:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('fat_per:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('fat_per:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('fat_per:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('fat_per:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//snf_per
		if(document.getElementById('snf_per:'+j).value!=""){
            if(!IsNumericA(document.getElementById('snf_per:'+j).value,'snf_per:'+j))
			{
				alert("Please Enter Value in Numeric for snf Percent Field");
				document.getElementById('snf_per:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('snf_per:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('snf_per:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('snf_per:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('snf_per:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//fat_kg
		if(document.getElementById('fat_kg:'+j).value!=""){
            if(!IsNumericA(document.getElementById('fat_kg:'+j).value,'fat_kg:'+j))
			{
				alert("Please Enter Value in Numeric for fat kg Field");
				document.getElementById('fat_kg:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('fat_kg:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('fat_kg:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('fat_kg:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('fat_kg:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//snf_kg
		if(document.getElementById('snf_kg:'+j).value!=""){
            if(!IsNumericA(document.getElementById('snf_kg:'+j).value,'snf_kg:'+j))
			{
				alert("Please Enter Value in Numeric for snf kg Field");
				document.getElementById('snf_kg:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('snf_kg:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('snf_kg:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('snf_kg:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('snf_kg:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//milk_age
		if(document.getElementById('milk_age:'+j).value!=""){
            if(!IsNumericA(document.getElementById('milk_age:'+j).value,'milk_age:'+j))
			{
				alert("Please Enter Value in Numeric for milk_age Field");
				document.getElementById('milk_age:'+j).focus();
				return false;
			}
            for (var k = 0; k < document.getElementById('milk_age:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('milk_age:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('milk_age:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('milk_age:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
			if(!IsNumeric(document.getElementById('milk_age:'+j).value)){
				alert ("Please Enter Milk Age Value in Hours (Decimal Format).");
				return false;
			}
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//disp_time
		if(document.getElementById('disp_time:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('disp_time:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('disp_time:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('disp_time:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('disp_time:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                } 
					
            }
			var datetime=document.getElementById('disp_time:'+j).value.split(" ");
			if(datetime[1]=="00:00:00"){
				alert("Dispatch Time not allowed  00:00:00, Please Increment the time");
				document.getElementById('disp_time:'+j).focus();
				return false;
			}
			/*not for admin
			//---new code update on 09 sept 2014
			var startHour = new Date(datetime[0] +" "+ datetime[1]);
			var endHour =  new Date();
			
			var hr_diff_sec=(endHour-startHour)/1000;
			var hr_diff=parseInt(hr_diff_sec/3600);
			//alert(hr_diff);
			if(hr_diff > 24){
				alert("Dispatch Time is not allowed more than 24 Hours back from Current Invoice Entry Time ");
				document.getElementById('disp_time:'+j).focus();
				document.getElementById('disp_time:'+j).value="";
				return false;
			}
			//---end of code updated---
			*/
			//---new code update on 25 may 2015
			var startHour = new Date(datetime[0] +" "+ datetime[1]);
			var endHour =  new Date();
			
			var hr_diff_sec=(endHour-startHour)/1000;
			var hr_diff=parseInt(hr_diff_sec/3600);
			//alert(hr_diff);
			if(hr_diff > 360){ //15 day
				alert("Dispatch Time is not allowed more than 360 Hours (15 day) back from Current Invoice Entry Time ");
				document.getElementById('disp_time:'+j).focus();
				document.getElementById('disp_time:'+j).value="";
				return false;
			}
			
			if(document.getElementById('tankertype:'+j).value!=""){
				
			   //do nothing
			}
			 else{
				alert("Field Required");
				return false;
			}
			
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//target_time
		if(document.getElementById('target_time:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('target_time:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('target_time:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('target_time:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('target_time:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
			//checking 00:00:00
			var datetime=document.getElementById('target_time:'+j).value.split(" ");
			if(datetime[1]=="00:00:00"){
				alert("Target Time not allowed  00:00:00, Please Increment the time");
				document.getElementById('target_time:'+j).focus();
				return false;
			}
        }
         else{
            alert("Field Required");
            return false;
        }
		
		if(document.getElementById('target_time:'+j).value < document.getElementById('disp_time:'+j).value)
		{
			alert("Target time must be greater than or equal to dispatch time");
			return false;
		}
		
		
		
        
   }  
   
	   var offset_sno_id="";
	   var offset_lrno ="";
	   var offset_vehno ="";
	   var offset_transporter ="";
	   var offset_email ="";
	   var offset_mobile ="";
	   var offset_qty ="";
	   var offset_fat_per ="";
	   var offset_snf_per ="";
	   var offset_fat_kg ="";
	   var offset_snf_kg ="";
	   var offset_milk_age ="";
	   
	   var offset_disp_time ="";
	   var offset_target_time ="";
	   var offset_docketflag ="";
	   
	   var offset_driver ="";
	   var offset_drivermobile ="";
	   var offset_plant ="";
	   var offset_chillplant ="";
	   var tankertype="";
	   
	   offset_sno_id=document.getElementById('offset_sno_id').value;
	   offset_lrno=document.getElementById('offset_lrno').value;
	   offset_vehno=document.getElementById('offset_vehno').value;
	   offset_transporter=document.getElementById('offset_transporter').value;
	   offset_email=document.getElementById('offset_email').value;
	   offset_mobile=document.getElementById('offset_mobile').value;
	   offset_qty=document.getElementById('offset_qty').value;	
	   offset_fat_per=document.getElementById('offset_fat_per').value;
	   offset_snf_per=document.getElementById('offset_snf_per').value;
	   offset_fat_kg=document.getElementById('offset_fat_kg').value;
	   offset_snf_kg=document.getElementById('offset_snf_kg').value;
	   offset_milk_age=document.getElementById('offset_milk_age').value;
	   
	   offset_disp_time=document.getElementById('offset_disp_time').value;
	   offset_target_time=document.getElementById('offset_target_time').value;
	   offset_docketflag=document.getElementById('offset_docketflag').value;
	   
	   offset_driver=document.getElementById('offset_driver').value;
	   offset_drivermobile=document.getElementById('offset_drivermobile').value;
	   offset_plant=document.getElementById('offset_plant').value;
	   offset_chillplant=document.getElementById('offset_chillplant').value;
	   offset_tankertype=document.getElementById('offset_tankertype').value;
	   var vehicle_list_hidden="";
	   vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;
	   
	   var poststr ="action_type=add"+
					"&offset_sno_id="+offset_sno_id+
					 "&offset_lrno="+offset_lrno+
					 "&offset_vehno="+offset_vehno+
					 "&offset_transporter="+offset_transporter+
					 "&offset_email="+offset_email+
					 "&offset_mobile="+offset_mobile+
					 "&offset_qty="+offset_qty+
					 "&offset_fat_per="+offset_fat_per+
					 "&offset_snf_per="+offset_snf_per+
					 "&offset_fat_kg="+offset_fat_kg+
					 "&offset_snf_kg="+offset_snf_kg+
					 "&offset_milk_age="+offset_milk_age+
					 "&offset_disp_time="+offset_disp_time+
					 "&offset_target_time="+offset_target_time+					
					 "&offset_docketflag="+offset_docketflag+
					 "&vehicle_list="+vehicle_list_hidden+
					 "&offset_driver="+offset_driver+					
					 "&offset_drivermobile="+offset_drivermobile+
					 "&offset_plant="+offset_plant+
					 "&offset_chillplant="+offset_chillplant+
					 "&offset_tankertype="+offset_tankertype;
					 
		//alert("poststr="+poststr);
		
		document.getElementById('enter_button').disabled=true;
		document.getElementById('loading_status').style.display="";
		document.getElementById('loading_status').innerHTML = '<font color=blue>Please wait..</font>';
		
		

		makePOSTRequest('action_manage_invoice_raw_milk.htm', poststr); 
   
   
}

function put_fat_snf_kg(val,elements){
	//alert(val);
	//alert(elements);
	var ids=getelementid(elements);
	document.getElementById('fat_kg:'+ids).value="";
	document.getElementById('snf_kg:'+ids).value="";
	var fat_per=document.getElementById('fat_per:'+ids).value;
	var snf_per=document.getElementById('snf_per:'+ids).value;

	var fatkg=(val*fat_per)/100;
	fatkg=Math.round(fatkg*100)/100; 
	document.getElementById('fat_kg:'+ids).value=fatkg;

	var snfkg=(val*snf_per)/100;
	snfkg=Math.round(snfkg*100)/100; 
	document.getElementById('snf_kg:'+ids).value=snfkg;
}


function put_fat_kg(val,elements){
	//alert(val);
	//alert(elements);
	
	var ids=getelementid(elements);
	document.getElementById('fat_kg:'+ids).value="";
	var qty=document.getElementById('qty:'+ids).value;
	if(qty==""){
		alert("Please Enter Qty Value");
		document.getElementById('fat_per:'+ids).value="";
	}
	else{
		var fatkg=(qty*val)/100;
		fatkg=Math.round(fatkg*100)/100; 
		document.getElementById('fat_kg:'+ids).value=fatkg;
	}
	//alert(ids);
}
function put_snf_kg(val,elements){
	//alert(val);
	//alert(elements);
	
	var ids=getelementid(elements);
	document.getElementById('snf_kg:'+ids).value="";
	var qty=document.getElementById('qty:'+ids).value;
	if(qty==""){
		alert("Please Enter Qty(kg) Value");
		document.getElementById('snf_per:'+ids).value="";
	}
	else{
		var snfkg=(qty*val)/100;
		snfkg=Math.round(snfkg*100)/100; 
		document.getElementById('snf_kg:'+ids).value=snfkg;
	}
	//alert(ids);
}

function getScriptPage_raw_milk(val,ids,box){	
	//var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
	var iChars = "! @#$%^&*()+=-[]\\\';,.{}|/\":<>?";
	flag=0;
	for (var k = 0; k < val.length; k++) {
		if (iChars.indexOf(val.charAt(k)) != -1) {
			//alert(iChars[iChars.indexOf(val.charAt(k))]);
			alert ("The box has special characters: "+ iChars[iChars.indexOf(val.charAt(k))] +" \n These are not allowed.\n");
			document.getElementById('vehicle_list').focus();
			//return false;
			/*for (var i = 0; i < iChars.length; i++) {
			document.getElementById('vehicle_list').value.replace(new RegExp("\\" + iChars[i], ' '), '');
			}*/
			//document.getElementById('vehicle_list').value="";replace(/blue/gi, "red");
			var replace_str=val.replace(iChars[iChars.indexOf(val.charAt(k))], "");
			document.getElementById('vehicle_list').value=replace_str; 
			flag=1;
		
		} 
	} 
    if(flag==0){
		var vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;	 
		var poststr ="action_type=rawmilkvehicle"+
					 "&all_vehicles="+vehicle_list_hidden+
					 "&content="+val+
					 "&box="+box+
					 "&ids="+ids;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/datalog_script_search_rawmilk.htm', poststr); 
	}
	else{
		return false;
	}
	   
}

function getScriptPage_raw_milk_from_admin(val,ids,box){	
	//var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
	var iChars = "! @#$%^&*()+=-[]\\\';,.{}|/\":<>?";
	flag=0;
	for (var k = 0; k < val.length; k++) {
		if (iChars.indexOf(val.charAt(k)) != -1) {
			//alert(iChars[iChars.indexOf(val.charAt(k))]);
			alert ("The box has special characters: "+ iChars[iChars.indexOf(val.charAt(k))] +" \n These are not allowed.\n");
			document.getElementById('vehicle_list').focus();
			//return false;
			/*for (var i = 0; i < iChars.length; i++) {
			document.getElementById('vehicle_list').value.replace(new RegExp("\\" + iChars[i], ' '), '');
			}*/
			//document.getElementById('vehicle_list').value="";replace(/blue/gi, "red");
			var replace_str=val.replace(iChars[iChars.indexOf(val.charAt(k))], "");
			document.getElementById('vehicle_list').value=replace_str; 
			flag=1;
		
		} 
	} 
    if(flag==0){
		var vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;	 
		var poststr ="action_type=rawmilkvehicle"+
					 "&all_vehicles="+vehicle_list_hidden+
					 "&content="+val+
					 "&box="+box+
					 "&ids="+ids;
		//alert("poststr="+poststr);
		makePOSTRequest('datalog_script_search_rawmilk.htm', poststr); 
	}
	else{
		return false;
	}
	   
}


function getelementid(elements){
	var ids= elements.split(':');
	return ids[1];
}

function display_raw_milk_vehicle(word,ids)
{
	//alert("d");
	//alert("word:"+word);
	//alert("ids:"+ids);
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
	document.getElementById('vehicle_list').value = vehicle_name;
	//document.getElementById('device_imei_no').value = device_imei_no;
	document.getElementById('box2').style.display = 'none';
	
}

function invoice_uploder_div(val)
{
	if(val=="excelupload")	{
		document.getElementById('uploader_div').style.display='';
		
	}
	else{
		document.getElementById('uploader_div').style.display='none';
		var poststr ="upload_status=0";
					 
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_add_raw_milk_usertype.htm', poststr); 
	}
}
function invoice_uploder_div_admin(val)
{
	if(val=="excelupload")	{
		document.getElementById('uploader_div').style.display='';
		
	}
	else if(val=="manual"){
		document.getElementById('uploader_div').style.display='none';
		var poststr ="upload_status=0";
					 
		//alert("poststr="+poststr);
		makePOSTRequest('manage_invoice_milk_add_upload.htm', poststr); 
	}
	/*else if(val=="pendingupload"){
		document.getElementById('uploader_div').style.display='none';
		//onclick ='src/php/manage_invoice_milk_add_upload.php?pending=2  target='_blank'
		var poststr ="pending=2";
					 
		//alert("poststr="+poststr);
		makePOSTRequest('manage_invoice_milk_add_upload.htm', poststr); 
	}*/
	
}
function open_pending_tanker_view()
{

	var param1 ="blackout_pending_tanker";
	var param2 ="divpopup_pending_tanker";
	
	document.getElementById(param1).style.visibility = "visible";
	document.getElementById(param2).style.visibility = "visible";
	document.getElementById(param1).style.display = "block";
	document.getElementById(param2).style.display = "block";
	document.getElementById('loading_pending_tanker').innerHTML="<font color=red><b>Please wait while Loading...</b></font>";
	var poststr = "x=1";		
	 //alert(poststr);
	$.ajax({
	type: "POST",
	url:'src/php/manage_pending_tanker_show.php',
	data: poststr,
	success: function(response){
		//console.log(response);
		//alert("response="+response);		
		document.getElementById('manage_pending_tanker_show').innerHTML="";	
		$("#manage_pending_tanker_show").html(response);
		document.getElementById('loading_pending_tanker').innerHTML="";	
	},
	error: function()
	{
		alert('An unexpected error has occurred! Please try later.');
	}
	});
		
}


function close_pending_tanker_view()
{

	var param1 ="blackout_pending_tanker";
	var param2 ="divpopup_pending_tanker";
	
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}

function close_pending_tanker_view_final()
{

	
	var param1 ="blackout_pending_tanker";
	var param2 ="divpopup_pending_tanker";
	
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
	
	/*
	var poststr ="tripid_post="+tripid+
	"&vname="+trip_vehicle+
	"&startdate="+trip_startdate+
	"&alert_name="+alert_name+	
	"&trigger_sno="+trigger_sno;
	//alert(poststr);
	makePOSTRequest('src/php/manage_close_alert_final_pending.htm', poststr); 
	*/
	
}


function show_vehicle_list_hindalco(sno)
{
	//alert(sno);
	
	
	document.getElementById('tmp_serial').value = sno;	
	//document.getElementById('vehicle_list').value = document.getElementById(sno).value;
	var vehile_self_other=document.getElementById(sno).value;
	var vehile_self_other_array=vehile_self_other.split("#");
	document.getElementById('vehicle_list_self').value=vehile_self_other_array[0] ;
	if(vehile_self_other_array[1]==undefined){
		document.getElementById('vehicle_list_other').value="";	
	}
	else{
		document.getElementById('vehicle_list_other').value=vehile_self_other_array[1];	
	}
	
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 	
	//document.getElementById("vehicle_list").focus();	
	
}
function close_vehicle_list_hindalco(value)
{
	
	var serial = document.getElementById('tmp_serial').value;
	
		var radio_self_other1 = document.getElementsByName('radio_self_other');
		
		for(var i = 0; i < radio_self_other1.length; i++){
			if(radio_self_other1[i].checked){
				rate_value = radio_self_other1[i].value;
				if( radio_self_other1[i].value==0)
				{
					var vehile_self_other=document.getElementById('vehicle_list_self').value;
				}
				if( radio_self_other1[i].value==1)
				{
					var vehile_self_other="#"+document.getElementById('vehicle_list_other').value;	
				}
			}
		}

	
	
	
	
	//var vehile_self_other=document.getElementById('vehicle_list_self').value +"#"+document.getElementById('vehicle_list_other').value;	
	//document.getElementById(serial).value = document.getElementById('vehicle_list').value;	
	document.getElementById(serial).value = vehile_self_other;	
	//document.getElementById('vehicle_list').value="";		
	document.getElementById('vehicle_list_self').value="" ;
	document.getElementById('vehicle_list_other').value="";	
	
	
	var param1 ="blackout";
	var param2 ="divpopup_plant";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}

function getScriptPage_hindalco_self_vehicle(val,ids,box){	
	//var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
	var iChars = "! @#$%^&*()+=-[]\\\';,.{}|\":<>?";
	flag=0;
	for (var k = 0; k < val.length; k++) {
		if (iChars.indexOf(val.charAt(k)) != -1) {
			//alert(iChars[iChars.indexOf(val.charAt(k))]);
			alert ("The box has special characters: "+ iChars[iChars.indexOf(val.charAt(k))] +" \n These are not allowed.\n");
			document.getElementById('vehicle_list').focus();
			//return false;
			/*for (var i = 0; i < iChars.length; i++) {
			document.getElementById('vehicle_list').value.replace(new RegExp("\\" + iChars[i], ' '), '');
			}*/
			//document.getElementById('vehicle_list').value="";replace(/blue/gi, "red");
			var replace_str=val.replace(iChars[iChars.indexOf(val.charAt(k))], "");
			document.getElementById('vehicle_list_self').value=replace_str; 
			flag=1;
		
		} 
	} 
    if(flag==0){
		var vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;	 
		var poststr ="action_type=rawmilkvehicle"+
					 "&all_vehicles="+vehicle_list_hidden+
					 "&content="+val+
					 "&box="+box+
					 "&ids="+ids;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/datalog_script_search_hindalco_invoice_self.htm', poststr); 
	}
	else{
		return false;
	}
	   
}

function getScriptPage_hindalco_other_vehicle(val,ids,box){	
	//var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
	var iChars = "! @#$%^&*()+=-[]\\\';,.{}|\":<>?";
	flag=0;
	for (var k = 0; k < val.length; k++) {
		if (iChars.indexOf(val.charAt(k)) != -1) {
			//alert(iChars[iChars.indexOf(val.charAt(k))]);
			alert ("The box has special characters: "+ iChars[iChars.indexOf(val.charAt(k))] +" \n These are not allowed.\n");
			document.getElementById('vehicle_list').focus();
			//return false;
			/*for (var i = 0; i < iChars.length; i++) {
			document.getElementById('vehicle_list').value.replace(new RegExp("\\" + iChars[i], ' '), '');
			}*/
			//document.getElementById('vehicle_list').value="";replace(/blue/gi, "red");
			var replace_str=val.replace(iChars[iChars.indexOf(val.charAt(k))], "");
			document.getElementById('vehicle_list_other').value=replace_str; 
			flag=1;
		
		} 
	} 
    if(flag==0){
		var vehicle_list_hidden=document.getElementById('vehicle_list_other_hidden').value;	 
		var poststr ="action_type=rawmilkvehicle"+
					 "&all_vehicles="+vehicle_list_hidden+
					 "&content="+val+
					 "&box="+box+
					 "&ids="+ids;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/datalog_script_search_hindalco_invoice_other.htm', poststr); 
	}
	else{
		return false;
	}
	   
}


function display_hindalco_invoice_self_vehicle(word,ids)
{
	//alert("d");
	//alert("word:"+word);
	//alert("ids:"+ids);
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
	document.getElementById('vehicle_list_self').value = vehicle_name;
	//document.getElementById('device_imei_no').value = device_imei_no;
	document.getElementById('box2').style.display = 'none';
	
}
function display_hindalco_invoice_other_vehicle(word,ids)
{
	//alert("d");
	//alert("word:"+word);
	//alert("ids:"+ids);
	var word=word.split(":");
	var vehicle_name= word[0];
	var veh_id= word[1];
	document.getElementById('vehicle_list_other').value = vehicle_name;
	//document.getElementById('device_imei_no').value = device_imei_no;
	document.getElementById('box3').style.display = 'none';
	
}

//=====================Hindalco Transporter=======================
var counter = 0;
	 
function addfield_Hindalco(){	   
	   document.getElementById("tnum").value="";
		counter++;
		var newFields = document.getElementById('readroot').cloneNode(true);
		newFields.id = '';
		newFields.style.display = 'block';
		var tid="";
		var newField = newFields.childNodes;
		for (var i=0;i<newField.length;i++) {
			var theName = newField[i].name
			var theid = newField[i].id
			if (theName)
			{
				newField[i].name = theName + counter;
				newField[i].id = theid + counter;
				tid=counter;   			
				document.getElementById("tnum").value=tid;

			}
		}
		var insertHere = document.getElementById('writeroot');
		insertHere.parentNode.insertBefore(newFields,insertHere);
	  //alert(tid);
	  document.getElementById("num:"+tid).value=tid;
	  document.getElementById("butt:"+tid).value="DEL-"+tid;
	 

}

function lessFields_Hindalco(id){
  //alert(id);
  id1=id.split(":");
  //alert(id1[1]);
  counter--;
  flagset=0;
  tmp_counter=document.getElementById("tnum").value;
  document.getElementById("tnum").value=counter;
  //alert(tmp_counter);
  for( i=1;i<parseInt(tmp_counter);i++)
  {
	
	if(id1[1] <= parseInt(tmp_counter)){
		//("less="+i);
	  if(id1[1]==i){

		z=i+1;
		//alert( "Z="+z);
		document.getElementById("num:"+z).value=i;
		document.getElementById("num:"+z).id="num:"+i;
		document.getElementById("butt:"+z).value="DEL-"+i;
		document.getElementById("butt:"+z).id="butt:"+i;
		
		document.getElementById("lrno:"+z).id="lrno:"+i;
		document.getElementById("vehno:"+z).id="vehno:"+i;
		//document.getElementById("plantspan:"+z).id="plantspan:"+i;
		document.getElementById("email:"+z).id="email:"+i;
		document.getElementById("mobile:"+z).id="mobile:"+i;
		document.getElementById("driver:"+z).id="driver:"+i;
		document.getElementById("drivermobile:"+z).id="drivermobile:"+i;
		document.getElementById("qty:"+z).id="qty:"+i;
		document.getElementById("customer:"+z).id="customer:"+i;				
		document.getElementById("disp_time:"+z).id="disp_time:"+i;
		document.getElementById("target_time:"+z).id="target_time:"+i;
		document.getElementById("product_type:"+z).id="product_type:"+i;
		
                
		id1[1]=z;
	  }
	  else{
		 //document.getElementById("num:"+i).value=i;
	  }

	}
	
  }
  z=0;
  tmp_counter=0;
  //service_manupulation_del(id);

}

function action_manage_invoice_hindalco(){
   //alert("hello");
	var flag=0;

	var lrno="";
	var vehno="";
	var email="";   
	var mobile="";   
	var qty="";
	var customer="";   
	var disp_time="";
	var target_time="";   
	var product_type=""; 
	var driver=""; 
	var drivermobile=""; 

  
   var tnum=document.getElementById('tnum').value;
   if(document.getElementById('lrno:0').value==""){
       alert("Please Enter Field Value");
       return false;
   }
   //alert(tnum);
   for(i=0;i<=parseInt(tnum);i++){
       if(document.getElementById('vehno:'+i).value!="" ){
			lrno=lrno + document.getElementById('lrno:'+i).value+",";
			vehno=vehno +document.getElementById('vehno:'+i).value +",";
			email=email +document.getElementById('email:'+i).value +",";
			mobile=mobile +document.getElementById('mobile:'+i).value +",";			
			driver=driver +document.getElementById('driver:'+i).value +",";
			drivermobile=drivermobile +document.getElementById('drivermobile:'+i).value +",";			
			qty=qty + document.getElementById('qty:'+i).value +",";
			customer=customer +document.getElementById('customer:'+i).value +",";			
			disp_time=disp_time +document.getElementById('disp_time:'+i).value +",";
			target_time=target_time +document.getElementById('target_time:'+i).value +",";
			product_type=product_type +document.getElementById('product_type:'+i).value +",";
			
       }       
   }
   document.getElementById('offset_lrno').value=lrno;
   document.getElementById('offset_vehno').value=vehno;
   document.getElementById('offset_email').value=email;
   document.getElementById('offset_mobile').value=mobile;
   
   document.getElementById('offset_qty').value=qty;
   document.getElementById('offset_customer').value=customer;
   
   document.getElementById('offset_disp_time').value=disp_time;
   document.getElementById('offset_target_time').value=target_time;
     
   document.getElementById('offset_driver').value=driver;
   document.getElementById('offset_drivermobile').value=drivermobile;
   document.getElementById('offset_product_type').value=product_type;
  
  
   //var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
   var iChars = ",";
   for(j=0;j<=parseInt(tnum);j++){
        if(document.getElementById('lrno:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('lrno:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('lrno:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('lrno:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('lrno:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
             return false;
        }
        if(document.getElementById('vehno:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('vehno:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('vehno:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('vehno:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('vehno:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		//email
		if(document.getElementById('email:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('email:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('email:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('email:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('email:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//mobile
		if(document.getElementById('mobile:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('mobile:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('mobile:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('mobile:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('mobile:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//driver
		if(document.getElementById('driver:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('driver:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('driver:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('driver:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('driver:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//drivermobile
		if(document.getElementById('drivermobile:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('drivermobile:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('drivermobile:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('drivermobile:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('drivermobile:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//product_type
		if(document.getElementById('product_type:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('product_type:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('product_type:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('product_type:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('product_type:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
        
		
		//qty
		if(document.getElementById('qty:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('qty:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('qty:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('qty:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('qty:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//customer
		if(document.getElementById('customer:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('customer:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('customer:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('customer:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('customer:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
        }
         else{
            alert("Field Required");
            return false;
        }
		
		
		//disp_time
		if(document.getElementById('disp_time:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('disp_time:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('disp_time:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('disp_time:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('disp_time:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                } 
					
            }
			var datetime=document.getElementById('disp_time:'+j).value.split(" ");
			if(datetime[1]=="00:00:00"){
				alert("Dispatch Time not allowed  00:00:00, Please Increment the time");
				document.getElementById('disp_time:'+j).focus();
				return false;
			}
        }
         else{
            alert("Field Required");
            return false;
        }
		
		//target_time
		if(document.getElementById('target_time:'+j).value!=""){
            
            for (var k = 0; k < document.getElementById('target_time:'+j).value.length; k++) {
            if (iChars.indexOf(document.getElementById('target_time:'+j).value.charAt(k)) != -1) {
                alert ("The box has special characters comma. \nThese are not allowed.\n");
				document.getElementById('target_time:'+j).focus();
                return false;
                for (var i = 0; i < iChars.length; i++) {
                document.getElementById('target_time:'+j).value.replace(new RegExp("\\" + iChars[i], ' '), '');
                }
                flag=1;
                
                }        
            }
			//checking 00:00:00
			var datetime=document.getElementById('target_time:'+j).value.split(" ");
			if(datetime[1]=="00:00:00"){
				alert("Target Time not allowed  00:00:00, Please Increment the time");
				document.getElementById('target_time:'+j).focus();
				return false;
			}
        }
         else{
            alert("Field Required");
            return false;
        }
		
		
		
		
        
   }
   
   
	   var offset_lrno ="";
	   var offset_vehno ="";
	   var offset_email ="";
	   var offset_mobile ="";
	   var offset_qty ="";
	   var offset_customer ="";
	   var offset_disp_time ="";
	   var offset_target_time ="";	   
	   var offset_driver ="";
	   var offset_drivermobile ="";
	   var offset_product_type ="";
	   
	   
	   offset_lrno=document.getElementById('offset_lrno').value;
	   offset_vehno=document.getElementById('offset_vehno').value;
	   offset_email=document.getElementById('offset_email').value;
	   offset_mobile=document.getElementById('offset_mobile').value;
	   offset_qty=document.getElementById('offset_qty').value;	
	   offset_customer=document.getElementById('offset_customer').value;
	   offset_disp_time=document.getElementById('offset_disp_time').value;
	   offset_target_time=document.getElementById('offset_target_time').value;
	   
	   offset_driver=document.getElementById('offset_driver').value;
	   offset_drivermobile=document.getElementById('offset_drivermobile').value;
	   offset_product_type=document.getElementById('offset_product_type').value;
	   
	   var vehicle_list_hidden="";
	   vehicle_list_hidden=document.getElementById('vehicle_list_hidden').value;
	   var vehicle_list_other_hidden="";
	   vehicle_list_other_hidden=document.getElementById('vehicle_list_other_hidden').value;
	   
	   var poststr ="action_type=add"+
					 "&offset_lrno="+offset_lrno+
					 "&offset_vehno="+offset_vehno+
					 "&offset_email="+offset_email+
					 "&offset_mobile="+offset_mobile+
					 "&offset_qty="+offset_qty+
					 "&offset_customer="+offset_customer+
					 
					 "&offset_disp_time="+offset_disp_time+
					 "&offset_target_time="+offset_target_time+	
					 
					 "&vehicle_list="+vehicle_list_hidden+
					 "&vehicle_list_other_hidden="+vehicle_list_other_hidden+
					 
					 "&offset_driver="+offset_driver+					
					 "&offset_drivermobile="+offset_drivermobile+
					 "&offset_product_type="+offset_product_type;
		//alert("poststr="+poststr);
		document.getElementById('enter_button').disabled=true;		
		document.getElementById('loading_status').style.display="";
		document.getElementById('loading_status').innerHTML = '<font color=blue>Please wait..</font>';
		
		makePOSTRequest('src/php/action_manage_invoice_hindalco.htm', poststr); 
   
   
}

function action_manage_hindalco_invoice_update(action_type)
{
	if(action_type=="edit")
	{
		var invoice_arr_cancel = document.invoice_form.elements['invoice_serial_cancel[]'];
		var invoice_arr_close = document.invoice_form.elements['invoice_serial_close[]'];
		var delete_serials = "";
		var close_serials = "";
		var update_serials = "";
		var product_type_serials = "";
		var approval="";
		var closetime="";
		//######## DELETE SERIALS
		if(invoice_arr_cancel.length!=undefined)
		{
			for (var i=0;i<invoice_arr_cancel.length;i++)
			{				
				update_serials = update_serials + ""+invoice_arr_cancel[i].value+",";
				product_type_serials = product_type_serials + ""+document.getElementById('product_type_'+invoice_arr_cancel[i].value).value+",";
				approval=approval + ""+document.getElementById('approval_'+invoice_arr_cancel[i].value).value+",";							
			}
		}
		else
		{
			update_serials = update_serials + ""+invoice_arr_cancel.value+",";
			product_type_serials = product_type_serials + ""+document.getElementById('product_type_'+invoice_arr_cancel.value).value+","	;
			approval=approval + ""+document.getElementById('approval_'+invoice_arr_cancel.value).value+",";					
		}

		//######## CANCEL SERIALS
		if(invoice_arr_cancel.length!=undefined)
		{
			for (var i=0;i<invoice_arr_cancel.length;i++)
			{
				if(invoice_arr_cancel[i].checked)
				{
					delete_serials = delete_serials + ""+invoice_arr_cancel[i].value+",";					
				}						
			}
		}
		else
		{
			if(invoice_arr_cancel.checked)
			{
				delete_serials = delete_serials + ""+invoice_arr_cancel.value+",";				
			}					
		}		
		//######## CLOSE SERIALS
		if(invoice_arr_close.length!=undefined)
		{
			for (var i=0;i<invoice_arr_close.length;i++)
			{
				if(invoice_arr_close[i].checked)
				{					
					close_serials = close_serials + invoice_arr_close[i].value+",";	
					closetime=closetime + ""+document.getElementById('closetime_'+invoice_arr_close[i].value).value+",";
				}							
			}
		}
		else
		{
			if(invoice_arr_close.checked)
			{
				close_serials = close_serials + invoice_arr_close.value+",";
				closetime=closetime + ""+document.getElementById('closetime_'+invoice_arr_close.value).value+",";
			}					
		}
		
		var poststr ="action_type="+action_type+
					"&update_serials="+update_serials+
					"&product_type_serials="+product_type_serials+
					"&close_serials="+close_serials+
					"&closetime_serials="+ closetime +
					"&approval_serials="+ approval +
					 "&delete_serials="+delete_serials;	
		//alert(poststr);
	}
	
	makePOSTRequest('src/php/action_manage_invoice_hindalco.htm', poststr);		
}


///////---third party---///
function select_all_destination_account(obj)
{
	//alert(obj);
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['destination_account[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['destination_account[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

function show_destination_account(val)
{
	if(val!='select')
	{
		var poststr ="source_account_id="+val;
		//alert(poststr);
		makePOSTRequest('src/php/manage_account_thirdparty_destination.htm', poststr);	
	}
	else
	{
		document.getElementById('destination_account').style.display ="none";              
          document.getElementById('destination_account').innerHTML = "";
	}
}
function action_manage_account_thirdparty(file_name)
{
	var source_account=document.getElementById('source_account').value;
	//var destination_account=document.getElementById('destination_account').value;
	var obj=document.manage1.elements['destination_account[]'];
	var destination_account=checkbox_selection(obj);		
	if(destination_account!=false)
	{	
		var poststr = "source_account=" + encodeURI(source_account)+
					"&destination_account=" + encodeURI(destination_account);
		//alert(poststr);
		makePOSTRequest('src/php/action_manage_account_thirdparty.htm', poststr);	
	}
	
}

function show_source_vehicle(val)
{
	if(val!='select')
	{
		var poststr ="destination_account_id="+val;
		//alert(poststr);
		makePOSTRequest('src/php/manage_vehicle_thirdparty_source.htm', poststr);	
	}
	else
	{
		document.getElementById('source_vehicle').style.display ="none";              
          document.getElementById('source_vehicle').innerHTML = "";
	}
}

function action_manage_vehicle_thirdparty(file_name)
{
	var destination_account=document.getElementById('destination_account_id').value;	
	var obj=document.manage1.elements['vehicle_id[]'];
	var vehicle_id=checkbox_selection(obj);		
	if(vehicle_id!=false)
	{	
		var poststr = "destination_account=" + encodeURI(destination_account)+
					"&vehicle_id=" + encodeURI(vehicle_id);
		//alert(poststr);
		makePOSTRequest('src/php/action_manage_vehicle_thirdparty.htm', poststr);	
	}
        else
        {
            var poststr = "destination_account=" + encodeURI(destination_account)+
					"&vehicle_id=";
		//alert(poststr);
		makePOSTRequest('src/php/action_manage_vehicle_thirdparty.htm', poststr);
        }
	
}

function toggle_visibility(id) 
{
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
}

//-----------------UPL-----------------------------------//
function show_date_option_upl(val)
{	
	var account_id=document.getElementById('account_id_hidden').value;
	var poststr = "schedule_type="+val+
		"&account_id="+account_id;	
	var file_name = 'src/php/manage_location_assignment_upl_ajax.htm';
	//alert(poststr);
	
	counter=0;
	$.ajax({
		type: "POST",
		url:file_name,
		data: poststr,
		success: function(response){
		//console.log(response);
		//alert("response="+response);		
		document.getElementById('get_date_option_upl').innerHTML="";	
		$("#get_date_option_upl").html(response);		
	},
	error: function()
	{
		alert('An unexpected error has occurred! Please try later.');
	}
	});
}
function select_all_upl_village(obj)
{

	//alert(obj);
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['upl_village[]'];
		//alert("obj="+obj);
		//alert("len="+len);
		for(i=0;i<s.length;i++)
			s[i].checked="true";			
	}
	else if(obj.all.checked==false)
	{
		var i;
		var s = obj.elements['upl_village[]'];
		for(i=0;i<s.length;i++)
			s[i].checked=false;			
	}
}

function show_village_list_upl(sno)
{
	//alert(sno);	
	document.getElementById('tmp_serial').value = sno;	
	document.getElementById("blackout").style.visibility = "visible";
	document.getElementById("divpopup_plant").style.visibility = "visible";
	document.getElementById("blackout").style.display = "block";
	document.getElementById("divpopup_plant").style.display = "block"; 
		
	
}
function close_village_list_upl(value)
{
	
	if(document.manage1.elements['upl_village[]'])
	{
		var upl_village_arr = document.manage1.elements['upl_village[]'];
		
		var update_serials="";
		var update_serials_id="";
		var update_serials_name="";
		if(upl_village_arr.length!=undefined)
		{
			for (var i=0;i<upl_village_arr.length;i++)
			{	
				if(upl_village_arr[i].checked)
				{
					update_serials = update_serials + ""+upl_village_arr[i].value+",";
					upl_village_tmp=upl_village_arr[i].value.split(':');
					update_serials_id= update_serials_id + ""+upl_village_tmp[0]+",";
					update_serials_name= update_serials_name + ""+upl_village_tmp[1]+",";
				}			
			}
		}
		else
		{
			if(upl_village_arr.checked)
			{
				update_serials = update_serials + ""+upl_village_arr.value+",";
				upl_village_tmp=upl_village_arr.value.split(':');
				update_serials_id= update_serials_id + ""+upl_village_tmp[0]+",";
				update_serials_name= update_serials_name + ""+upl_village_tmp[1]+",";
			}
							
		}
		
		//alert(update_serials);
		
		var serial = document.getElementById('tmp_serial').value;
		document.getElementById(serial).value=update_serials_name.slice(0,-1);
		var serial_id=serial.split(':');
		document.getElementById('village_id:'+serial_id[1]).value=update_serials_id.slice(0,-1);
	}
	var param1 ="blackout";
	var param2 ="divpopup_plant";
	//alert("param1="+param1+"param2="+param2);
	document.getElementById(param1).style.visibility = "hidden";
	document.getElementById(param2).style.visibility = "hidden";
	document.getElementById(param1).style.display = "none";
	document.getElementById(param2).style.display = "none";
}


function lessFieldswithUPL_day(id){
  //alert(id);
  id1=id.split(":");
  //alert(id1[1]);
  counter--;
  flagset=0;
  tmp_counter=document.getElementById("tnum").value;
  document.getElementById("tnum").value=counter;
  //alert(tmp_counter);
  for( i=1;i<parseInt(tmp_counter);i++)
  {
	
	if(id1[1] <= parseInt(tmp_counter)){
		//("less="+i);
	  if(id1[1]==i){

		z=i+1;
		//alert( "Z="+z);
		document.getElementById("num:"+z).value=i;
		document.getElementById("num:"+z).id="num:"+i;
		document.getElementById("butt:"+z).value="DEL-"+i;
		document.getElementById("butt:"+z).id="butt:"+i;
		
		document.getElementById("day:"+z).id="day:"+i;
		document.getElementById("village:"+z).id="village:"+i;
		document.getElementById("village_id:"+z).id="village_id:"+i;
		
		id1[1]=z;
	  }
	  else{
		 //document.getElementById("num:"+i).value=i;
	  }

	}
	
  }
  z=0;
  tmp_counter=0;
  //service_manupulation_del(id);

}
function lessFieldswithUPL_date(id){
  //alert(id);
  id1=id.split(":");
  //alert(id1[1]);
  counter--;
  flagset=0;
  tmp_counter=document.getElementById("tnum").value;
  document.getElementById("tnum").value=counter;
  //alert(tmp_counter);
  for( i=1;i<parseInt(tmp_counter);i++)
  {
	
	if(id1[1] <= parseInt(tmp_counter)){
		//("less="+i);
	  if(id1[1]==i){

		z=i+1;
		//alert( "Z="+z);
		document.getElementById("num:"+z).value=i;
		document.getElementById("num:"+z).id="num:"+i;
		document.getElementById("butt:"+z).value="DEL-"+i;
		document.getElementById("butt:"+z).id="butt:"+i;
		
		document.getElementById("datefrom:"+z).id="datefrom:"+i;
		document.getElementById("dateto:"+z).id="dateto:"+i;
		document.getElementById("village:"+z).id="village:"+i;
		document.getElementById("village_id:"+z).id="village_id:"+i;
		
		id1[1]=z;
	  }
	  else{
		 //document.getElementById("num:"+i).value=i;
	  }

	}
	
  }
  z=0;
  tmp_counter=0;
  //service_manupulation_del(id);

}
var fDate_array= new Array();
var lDate_array= new Array();
var cDate_array= new Array();
var fDate_array1= new Array();
var lDate_array1= new Array();
var cDate_array1= new Array();

 function action_manage_schedule_upl(action_type)
 {  
    //alert("action_type1="+action_type);	
	if(action_type=="add" || action_type=="edit") 
	{
		var location_name=document.getElementById("location_name").value;     
		var geo_point=document.getElementById("geo_point").value;  
	}
    if(action_type=="add") 
    {        
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
		//alert("result="+result);      
  		if(result!=false)
  		{
			var form_validation=location_form_validation(location_name,geo_point);
			//alert("form_validation="+form_validation);
			if(form_validation!=false)
			{
				var poststr = "action_type=" + action_type +
							  "&account_id_local=" + encodeURI(result) +		 
							  "&location_name=" + encodeURI(location_name) +
							  "&geo_point=" + encodeURI(geo_point); 
			}
		}                          
    }
    else if(action_type=="edit")
    {
	  var account_id_local=document.getElementById("account_id_local").value;
      var location_id=document.getElementById("location_id").value; 
	  var form_validation=location_form_validation(location_name,geo_point);
		//alert("form_validation="+form_validation);
		if(form_validation!=false)
		{
		 var poststr = "action_type=" + action_type +
					   "&location_id=" +location_id+
					   "&account_id_local=" +account_id_local+
					   "&location_name="+location_name+
					   "&geo_point="+geo_point;	
		}     			 
    }
  	else if(action_type=="delete")
  	{
  		var location_id=document.getElementById("location_id").value
  		if(location_id=="select") 
  		{
  			alert("Please Select Location Name");        
  			document.getElementById("location_id").focus();
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
  			"&location_id=" + encodeURI(location_id);    
  		}
	} 
  	else if(action_type=="assign")
  	{
		//alert("ds");
		 fDate_array= new Array();
		lDate_array= new Array();
		cDate_array= new Array();
		 fDate_array1= new Array();
		lDate_array1= new Array();
		cDate_array1= new Array();
		var vehicle_select=document.manage1.vehicle_id.value;
		var schedule_type=document.manage1.schedule_type.value;
		if(vehicle_select!='select')
		{
			if(schedule_type!='select')
			{
				if(schedule_type=='day')
				{
					if(document.getElementById("date1").value=="")
					{
						   alert("Please Enter Date From");			       
						   document.getElementById("date1").focus();
						   return false;
					}			
					if(document.getElementById("date2").value=="")
					{
						   alert("Please Enter Date To");			       
						   document.getElementById("date2").focus();
						   return false;
					}
					var tnum=document.getElementById('tnum').value;
					   if(document.getElementById('day:0').value==""){
						   alert("Please Enter Day Field Value");
						   return false;
					   }
					   var day="";
					   var village_id="";
					   var flag=0;
					   for(i=0;i<=parseInt(tnum);i++){
						   //if(document.getElementById('vehno:'+i).value!="" ){
						   if(document.getElementById('day:'+i).value!="" || document.getElementById('village_id:'+i).value!="select" ){
								day=day + document.getElementById('day:'+i).value+":";
								village_id=village_id +document.getElementById('village_id:'+i).value +":";
								}       
					   }
					   document.getElementById('offset_day').value=day;
					   document.getElementById('offset_village_id').value=village_id;
					  
						var iChars = ",";
					   for(j=0;j<=parseInt(tnum);j++){
							if(document.getElementById('day:'+j).value!="" && document.getElementById('village_id:'+j).value!=""){
								flag=1;
							}
							 else{
								alert("Field Required");
								 return false;
							}
						}
						var offset_day="";
						var offset_village_id ="";						
						offset_day=document.getElementById('offset_day').value;
						offset_village_id=document.getElementById('offset_village_id').value;
						//checking repeat day
						day_tmp=offset_day.split(':');
						var sorted_day_tmp = day_tmp.sort();
						for (var i = 0; i < day_tmp.length - 1; i++) {
							if (sorted_day_tmp[i + 1] == sorted_day_tmp[i]) {
								alert("Duplicate Day not Allowed");
								return false;
							}
						}
						//alert(offset_day);alert(offset_village_id);						
						var poststr="action_type="+encodeURI(action_type)+	
						"&for_action_type="+schedule_type+
						"&vehicle_id="+vehicle_select +
						"&date_from="+document.getElementById("date1").value+
						"&date_to="+document.getElementById("date2").value+	
						"&day="+offset_day+
						"&village_id="+offset_village_id;	
						//alert(poststr);
				}
				else if(schedule_type=='date')
				{
				   var datefrom="";
				   var dateto="";
				   var village_id="";
				   var flag=0;
				   var tnum=document.getElementById('tnum').value;
				   if(document.getElementById('datefrom:0').value==""){
					   alert("Please Enter Datefrom Field Value");
					   return false;
				   }
				   for(i=0;i<=parseInt(tnum);i++){					
					   if(document.getElementById('datefrom:'+i).value!="" || document.getElementById('dateto:'+i).value!="" || document.getElementById('village_id:'+i).value!="select" ){
							datefrom=datefrom + document.getElementById('datefrom:'+i).value+":";
							dateto=dateto + document.getElementById('dateto:'+i).value+":";
							village_id=village_id +document.getElementById('village_id:'+i).value +":";
							}       
				   }
				   document.getElementById('offset_datefrom').value=datefrom;
				   document.getElementById('offset_dateto').value=dateto;
				   document.getElementById('offset_village_id').value=village_id;
				   var iChars = ",";
				   for(j=0;j<=parseInt(tnum);j++){
						if(document.getElementById('datefrom:'+j).value!="" && document.getElementById('dateto:'+j).value!="" && document.getElementById('village_id:'+j).value!=""){
							//checking date exist between
							if(j>0)
							{
								var dateFrom = document.getElementById('datefrom:'+(j-1)).value;
								var dateTo = document.getElementById('dateto:'+(j-1)).value;
								var dateCheckfrom = document.getElementById('datefrom:'+j).value;
								
								if(dateCheckOverlapFrom(dateFrom,dateTo,dateCheckfrom))
									{
										alert("Please Check the Date Overlap From the Form");
										return false;
									}
								else
									{
										//alert("Not Availed");
									}
								var dateCheckto = document.getElementById('dateto:'+j).value;
								if(dateCheckOverlapTo(dateFrom,dateTo,dateCheckto))
									{
										alert("Please Check the Date Overlap From the Form");
										return false;
									}
								else
									{
										//alert("Not Availed");
									}
								
							}
							
							flag=1;
						}
						 else{
							alert("Field Required");
							 return false;
						}
					}
					var offset_datefrom="";
					var offset_dateto="";
					var offset_village_id ="";						
					offset_datefrom=document.getElementById('offset_datefrom').value;
					offset_dateto=document.getElementById('offset_dateto').value;
					offset_village_id=document.getElementById('offset_village_id').value;
					var poststr="action_type="+encodeURI(action_type)+	
					"&for_action_type="+schedule_type+
					"&vehicle_id="+vehicle_select +
					"&date_from="+offset_datefrom+
					"&date_to="+offset_dateto+					
					"&village_id="+offset_village_id;	
					//alert(poststr);
					//alert(offset_datefrom); alert(offset_dateto);alert(offset_village_id);  
				}
			}
			else
			{
				alert('Please Select Schedule');
				return false;
			}
		}
		else
		{
			alert('Please Select Vehicle First');
			return false;
		}
	}
	else if(action_type=="assign1")
  	{
		var nonpoi_halt_hr_1;
		var nonpoi_halt_min_1;
		var form_obj1=document.manage1.vehicle_id;
		alert(form_obj1.value);
		//var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
		//if(radio_result!=false)
		if(form_obj1.value!='select')
		{
			if(document.getElementById("date1").value=="")
			{
			       alert("Please Enter Date From");			       
			       document.getElementById("date1").focus();
			       return false;
			}			
			if(document.getElementById("date2").value=="")
			{
			       alert("Please Enter Date To");			       
			       document.getElementById("date2").focus();
			       return false;
			}
			if(document.manage1.day_check.checked==true)
			{
				var table = document.getElementById("dataTable");
				var rowCount = table.rows.length;
				//var row = table.insertRow(rowCount);
				var colCount = table.rows[0].cells.length;
				
				var arr=new Array();
				var day_str="";
				for(var i=0; i<rowCount; i++)
				{
					if(table.rows[i].cells[2].getElementsByTagName('select')[0].value=="select")
					{
						alert("Please select day.");
						return false;
						break;
					}
					day_str=day_str+table.rows[i].cells[2].getElementsByTagName('select')[0].value+",";
					arr[i]=table.rows[i].cells[2].getElementsByTagName('select')[0].value;	
					//alert("1="+table.rows[i].cells[2].getElementsByTagName('select')[0].value);
				}
				
				var day_exist_flag=similar_value_arr(arr);				
				if(day_exist_flag==1)
				{
					alert("Day can not be same");
					return false;				
				}
				var by_day=1;
			}			
			else if(document.manage1.day_check.checked==false)
			{
				day_str="";
				var by_day=0;
			}
			var flag=0;
			if(document.getElementById("min_ot_hr").value!="")
			{
				var min_ot_hr=IsNumeric(document.getElementById("min_ot_hr").value,"min_ot_hr");				
				if(min_ot_hr==true)
				{
					var min_ot_hr_1=min_max_hr_minute_validation(document.getElementById("min_ot_hr").value,"Min operation","hr","min_ot_hr");
					if(min_ot_hr_1!=false)
					{
						min_ot_hr_1=document.getElementById("min_ot_hr").value;													
					}
					else
					{
						return false;
					}			
				}
				else
				{
					return false;
				}
			}				
			if(document.getElementById("min_ot_minute").value!="")
			{
				var min_ot_minute=IsNumeric(document.getElementById("min_ot_minute").value,"min_ot_minute");
				if(min_ot_minute==true)
				{
					var min_ot_minute_1=min_max_hr_minute_validation(document.getElementById("min_ot_minute").value,"Min operation","min","min_ot_minute");
					if(min_ot_minute_1!=false)
					{
						min_ot_minute_1=document.getElementById("min_ot_minute").value;												
					}
					else
					{
						return false;
					}			
				}
			}			
			if(document.getElementById("max_ot_hr").value!="")
			{				
				var max_ot_hr=IsNumeric(document.getElementById("max_ot_hr").value,"max_ot_hr");				
				if(max_ot_hr==true)
				{
					var max_ot_hr_1=min_max_hr_minute_validation(document.getElementById("max_ot_hr").value,"Max operation","hr","max_ot_hr");
					if(max_ot_hr_1!=false)
					{
						flag=1;
						max_ot_hr_1=document.getElementById("max_ot_hr").value;					
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}				
			
			if(document.getElementById("max_ot_minute").value!="")
			{
				var max_ot_minute=IsNumeric(document.getElementById("max_ot_minute").value,"max_ot_minute");
				if(max_ot_minute==true)
				{
					var max_ot_minute_1=min_max_hr_minute_validation(document.getElementById("max_ot_minute").value,"Max operation","min","max_ot_minute");
					if(max_ot_minute_1!=false)
					{
						max_ot_minute_1=document.getElementById("max_ot_minute").value;	
						flag=1;							
					}
					else
					{
						return false;
					}			
				}
				else
				{
					return false;
				}
			}
			
			if(parseInt(max_ot_hr_1)==parseInt(min_ot_hr_1))
			{
				if(max_ot_minute_1<min_ot_minute_1)
				{
					alert("Max operation minute should not less than Min operation minute");
					document.getElementById("max_ot_minute").focus();
					return false;
				}		
			}
			
			if (parseInt(max_ot_hr_1)<parseInt(min_ot_hr_1))
			{
				alert("Max operation Hour should not less than Min operation hour");
				document.getElementById("max_ot_hr").focus();
				return false;
			}
			
			/*if(document.getElementById("rest_time_hr").value!="")
			{
				var rest_time_hr=IsNumeric(document.getElementById("rest_time_hr").value,"rest_time_hr");
				if(rest_time_hr==false)
				{
					return false;			
				}				
			}
			
			if(document.getElementById("rest_time_min").value!="")
			{
				var rest_time_min=IsNumeric(document.getElementById("rest_time_min").value,"rest_time_hr");
				if(rest_time_min==false)
				{
					return false;		
				}				
			}*/
			
			if(document.getElementById("min_distance").value!="")
			{
				var min_distance=IsNumeric(document.getElementById("min_distance").value,"min_distance");
				if(min_distance==true)
				{
					var min_distance_1=document.getElementById("min_distance").value;
				}
				else
				{
					return false;
				}
			}
			
			if(document.getElementById("max_distance").value!="")
			{
				var max_distance=IsNumeric(document.getElementById("max_distance").value,"max_distance");
				if(max_distance==true)
				{
					var max_distance_1=document.getElementById("max_distance").value;
				}
				else
				{
					return false;
				}
				//alert("min_distance_1="+min_distance_1+"max_distance_1="+max_distance_1);
				if (parseInt(min_distance_1) > parseInt(max_distance_1))
				{
					//alert("1");
					alert("Maximum distance should not less than Minimum distance");
					document.getElementById("max_distance").focus();
					return false;
				}
			}
			
			var halt_numbers=document.getElementById("halt_numbers").value;
			if (halt_numbers!="select")
			{
				var total_halt_no=document.getElementById("total_halt_no").value;
				var validation_string="";
				for(var i=1;i<=total_halt_no;i++)
				{					
					validation_string=validation_string+"textfield:"+"min_halt_time_hr_"+i+":Minimum halt hour,textfield:"+
									"min_halt_time_min_"+i+":Minimum halt minute,textfield:"+"max_halt_time_hr_"+i+":Maximum halt hour,"+
									"textfield:max_halt_time_min_"+i+":Maximum halt minute,select:"+"min_max_halt_location_"+i+
									":Location,";
				}
				var validation_string_1=validation_string.slice(0, -1);
				var result_wockhardt_for=schedule_form_validation(validation_string_1);
				if(result_wockhardt_for!=false)
				{
					var min_halt_time="";
					var max_halt_time="";
					var min_max_halt_locations="";
					var location_arr=new Array();
					for(var i=1;i<=total_halt_no;i++)
					{						
						var min_halt_time_hr_1="";
						var max_halt_time_hr_1="";
						var min_halt_time_min_1="";
						var max_halt_time_min_1="";
													
						var min_halt_time_hr=IsNumeric(document.getElementById("min_halt_time_hr_"+i).value,"min_halt_time_hr_"+i);
						if(min_halt_time_hr==true)
						{
							var min_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("min_halt_time_hr_"+i).value,"Minimum halt hour","hr","min_halt_time_hr_"+i);
							if(min_halt_time_hr_1!=false)
							{
								min_halt_time_hr_1=document.getElementById("min_halt_time_hr_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}

						var min_halt_time_min=IsNumeric(document.getElementById("min_halt_time_min_"+i).value,"min_halt_time_min_"+i);
						if(min_halt_time_min==true)
						{
							var min_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("min_halt_time_min_"+i).value,"Minimum halt hour","min","min_halt_time_min_"+i);
							if(min_halt_time_min_1!=false)
							{
								min_halt_time_min_1=document.getElementById("min_halt_time_min_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}
						var max_halt_time_hr=IsNumeric(document.getElementById("max_halt_time_hr_"+i).value,"max_halt_time_hr_"+i);
						if(max_halt_time_hr==true)
						{
							var max_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("max_halt_time_hr_"+i).value,"Minimum halt hour","hr","max_halt_time_hr_"+i);
							if(max_halt_time_hr_1!=false)
							{
								max_halt_time_hr_1=document.getElementById("max_halt_time_hr_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}
						var max_halt_time_min=IsNumeric(document.getElementById("max_halt_time_min_"+i).value,"max_halt_time_min_"+i);
						if(max_halt_time_min==true)
						{
							var max_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("max_halt_time_min_"+i).value,"Minimum halt hour","min","max_halt_time_min_"+i);
							if(max_halt_time_min_1!=false)
							{
								max_halt_time_min_1=document.getElementById("max_halt_time_min_"+i).value;
							}
							else
							{
								return false;
								break;
							}
						}
						else
						{
							return false;
							break;
						}
						//alert("max_halt_time_min_1="+max_halt_time_min_1+"min_halt_time_min_1="+min_halt_time_min_1);
						if(parseInt(max_halt_time_hr_1)==parseInt(min_halt_time_hr_1))
						{						
							if(parseInt(max_halt_time_min_1)<parseInt(min_halt_time_min_1))
							{
								alert("Maximum halt Minute should not less than Minimum halt minute");
								document.getElementById("max_halt_time_min_"+i).focus();
								return false;
								break;
							}
						}
						if(parseInt(max_halt_time_hr_1)<parseInt(min_halt_time_hr_1))
						{
							alert("Maximum halt Hour should not less than Min halt hour");
							document.getElementById("max_halt_time_hr_"+i).focus();
							return false;
							break;
						}
						location_arr[i]=document.getElementById("min_max_halt_location_"+i).value;
						min_max_halt_locations=min_max_halt_locations+document.getElementById("min_max_halt_location_"+i).value+",";
						min_halt_time=min_halt_time+min_halt_time_hr_1+":"+min_halt_time_min_1+",";
						max_halt_time=max_halt_time+max_halt_time_hr_1+":"+max_halt_time_min_1+",";
					}
					var location_exist_flag=similar_value_arr(location_arr);
					if(location_exist_flag==1)
					{
						alert("Laction Should Not be be same.");
						return false;				
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				var min_max_halt_locations="";
				var min_halt_time="";
				var max_halt_time="";
			}
			
			if(document.getElementById("base_station").value=="select")
			{
				var base_station="";
				var intermediate_time="";
			}
			else
			{
				var base_station=document.getElementById("base_station").value;
			}	
			
			if(document.getElementById("base_station").value!="select")
			{
				var intermediate_halt_numbers=document.getElementById("intermediate_halt_numbers").value;
				if(intermediate_halt_numbers!="select")
				{
					var total_intermediate_halt_no=document.getElementById("total_intermediate_halt_no").value;
					var intermediate_validation_string="";
					for(var i=1;i<=total_intermediate_halt_no;i++)
					{
						//alert("in for");
						intermediate_validation_string=intermediate_validation_string+"textfield:"+"intermediate_min_halt_time_hr_"+i+":Intermediate minimum halt hour,textfield:"+
										"intermediate_min_halt_time_min_"+i+":Intermediate minimum halt minute,";
					}
					//alert("intermediate_validation_string="+intermediate_validation_string);
					var intermediate_validation_string_1=intermediate_validation_string.slice(0, -1);
					var result_wockhardt_for_1=schedule_form_validation(intermediate_validation_string_1);
					//alert("A="+result_wockhardt_for_1)
					if(result_wockhardt_for_1!=false)
					{
						var intermediate_halt_hr=new Array();
						var intermediate_halt_min=new Array();
						var tmp1="";
						var tmp2="";
						var intermediate_time="";
						if(total_intermediate_halt_no==1)
						{
							//alert("in one");
							var intermediate_min_halt_time_hr=IsNumeric(document.getElementById("intermediate_min_halt_time_hr_1").value,"intermediate_min_halt_time_hr_1");
							if(intermediate_min_halt_time_hr==true)
							{
								var intermediate_min_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_hr_1").value,"Minimum halt hour","hr","intermediate_min_halt_time_hr_1");
								if(intermediate_min_halt_time_hr_1!=false)
								{
									intermediate_min_halt_time_hr_1=document.getElementById("intermediate_min_halt_time_hr_1").value;	
								}
								else
								{
									return false;
								}
							}
							else
							{
								return false;
							}
							var intermediate_min_halt_time_min=IsNumeric(document.getElementById("intermediate_min_halt_time_min_1").value,"intermediate_min_halt_time_min_1");
							if(intermediate_min_halt_time_min==true)
							{
								var intermediate_min_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_min_1").value,"Minimum halt hour","min","intermediate_min_halt_time_min_1");
								if(intermediate_min_halt_time_min_1!=false)
								{
									intermediate_min_halt_time_min_1=document.getElementById("intermediate_min_halt_time_min_1").value;	
								}
								else
								{
									return false;
								}
								intermediate_time=intermediate_time+intermediate_min_halt_time_hr_1+":"+intermediate_min_halt_time_min_1+",";							
							}
							else
							{
								return false;
							}
						}
						else
						{
							for(var i=1;i<=total_intermediate_halt_no;i++)
							{
								var intermediate_min_halt_time_hr_1="";
								var intermediate_min_halt_time_min_1="";
								
								var intermediate_min_halt_time_hr=IsNumeric(document.getElementById("intermediate_min_halt_time_hr_"+i).value,"intermediate_min_halt_time_hr_"+i);
								//alert("intermediate_min_halt_time_hr1="+intermediate_min_halt_time_hr);
								if(intermediate_min_halt_time_hr==true)
								{
									var intermediate_min_halt_time_hr_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_hr_"+i).value,"Minimum halt hour","hr","intermediate_min_halt_time_hr_"+i);
									if(intermediate_min_halt_time_hr_1!=false)
									{
										intermediate_min_halt_time_hr_1=document.getElementById("intermediate_min_halt_time_hr_"+i).value;
										intermediate_halt_hr[i]=intermediate_min_halt_time_hr_1;
										//var tmp1=i+1;
										//alert("tem="+tmp1+"total_halt="+total_intermediate_halt_no);
										if(tmp1<=(total_intermediate_halt_no-1))
										{
											var tmp1=i+1;
											//alert("tmp1="+tmp1);
											intermediate_halt_hr[i+1]=document.getElementById("intermediate_min_halt_time_hr_"+tmp1).value;
										}
									}
									else
									{
										return false;
										break;
									}
									//alert(intermediate_halt_hr[i]);
								}
								else
								{
									return false;
									break;
								}

								var intermediate_min_halt_time_min=IsNumeric(document.getElementById("intermediate_min_halt_time_min_"+i).value,"intermediate_min_halt_time_min_"+i);
								//alert("intermediate_min_halt_time_min2="+intermediate_min_halt_time_min);
								if(intermediate_min_halt_time_min==true)
								{
									var intermediate_min_halt_time_min_1=min_max_hr_minute_validation(document.getElementById("intermediate_min_halt_time_min_"+i).value,"Minimum halt minute","min","intermediate_min_halt_time_min_"+i);
									if(intermediate_min_halt_time_min_1!=false)
									{
										intermediate_min_halt_time_min_1=document.getElementById("intermediate_min_halt_time_min_"+i).value;
										intermediate_halt_min[i]=intermediate_min_halt_time_min_1;								
										if(tmp2<=(total_intermediate_halt_no-1))
										{
											var tmp2=i+1;
											//alert("tmp2="+tmp2);									
											intermediate_halt_min[i+1]=document.getElementById("intermediate_min_halt_time_min_"+tmp2).value;
										}
									}
									else
									{
										return false;
										break;
									}
								}
								else
								{
									return false;
									break;
								}
								//alert("hr_val1="+intermediate_halt_hr[i]+"hr_val2="+intermediate_halt_hr[i+1]);
								if(intermediate_halt_hr[i]==intermediate_halt_hr[i+1])
								{
									//alert("min_val1="+intermediate_halt_min[i]+"min_val2="+intermediate_halt_min[i+1]);
									if(parseInt(intermediate_halt_min[i+1])<parseInt(intermediate_halt_min[i]))
									{
										alert("Maximum halt Minute should not less than Minimum halt minute");
										document.getElementById("intermediate_min_halt_time_min_"+tmp1).focus();
										return false;
										break;
									}
								}
								//alert("hr_val1="+intermediate_halt_hr[i]+"hr_val2="+intermediate_halt_hr[i+1]);
								if(parseInt(intermediate_halt_hr[i+1])<parseInt(intermediate_halt_hr[i]))
								{
									//alert("in if");
									alert("Maximum halt Hour should not less than Min halt hour");
									document.getElementById("intermediate_min_halt_time_hr_"+tmp2).focus();
									return false;
									break;
								}
								intermediate_time=intermediate_time+intermediate_halt_hr[i]+":"+intermediate_halt_min[i]+",";
							}
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					var intermediate_time="";
				}
			}
			if(document.getElementById("nonpoi_halt_hr").value!="")
			{				
				var nonpoi_halt_hr=IsNumeric(document.getElementById("nonpoi_halt_hr").value,"nonpoi_halt_hr");				
				if(nonpoi_halt_hr==true)
				{
					nonpoi_halt_hr_1=min_max_hr_minute_validation(document.getElementById("nonpoi_halt_hr").value,"Max Non Poi Halt Time","hr","nonpoi_halt_hr");
					if(nonpoi_halt_hr_1!=false)
					{
						flag=1;
						nonpoi_halt_hr_1=document.getElementById("nonpoi_halt_hr").value;					
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			if(document.getElementById("nonpoi_halt_min").value!="")
			{
				var nonpoi_halt_min=IsNumeric(document.getElementById("nonpoi_halt_min").value,"nonpoi_halt_min");
				if(nonpoi_halt_min==true)
				{
					nonpoi_halt_min_1=min_max_hr_minute_validation(document.getElementById("nonpoi_halt_min").value,"Non Poi Halt Time","min","nonpoi_halt_min");
					if(nonpoi_halt_min_1!=false)
					{
						nonpoi_halt_min_1=document.getElementById("nonpoi_halt_min").value;	
						flag=1;							
					}
					else
					{
						return false;
					}			
				}
				else
				{
					return false;
				}
			}
			
		}
		else
		{
			return false;
		}
		
		var poststr="action_type="+encodeURI(action_type)+
			
			"&vehicle_id="+form_obj1.value +
			"&date_from="+document.getElementById("date1").value+
			"&date_to="+document.getElementById("date2").value+
			"&by_day="+by_day+
			"&day_str="+day_str+
			"&min_operation_time="+min_ot_hr_1+":"+min_ot_minute_1+
			"&max_operation_time="+max_ot_hr_1+":"+max_ot_minute_1+
			//"&allow_rest_time="+document.getElementById("rest_time_hr").value+":"+document.getElementById("rest_time_min").value+			
			"&minimum_distance="+document.getElementById("min_distance").value+
			"&maximum_distance="+document.getElementById("max_distance").value+
			"&min_max_halt_locations="+min_max_halt_locations+  
			"&min_halt_time="+min_halt_time+
			"&max_halt_time="+max_halt_time+
			"&base_station_id="+base_station+
			"&intermediate_time="+intermediate_time+
			"&nonpoi_halt_time="+nonpoi_halt_hr_1+":"+nonpoi_halt_min_1;			
  	}
  	else if(action_type=="deassign")
  	{
  		var form_obj=document.manage1.elements['vid_string[]'];
  		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
      			
  		if(checkbox_result!=false)
  		{		
  			var poststr="action_type="+encodeURI(action_type)+
  					"&vid_string="+checkbox_result;					// vehicle id also includes station ids with : separator	
  		}			
  	}
	//alert("poststr="+poststr);
	showManageLoadingMessage();
	makePOSTRequest('src/php/action_manage_schedule_upl.htm', poststr);
 }
 
 function dateCheckOverlapFrom(from1,to1,check1) {
	
    var fDate,lDate,cDate;
	var from2=from1.split("/");
	var from=from2[2]+"/"+from2[1]+"/"+from2[0];
	var to2=to1.split("/");
	var to=to2[2]+"/"+to2[1]+"/"+to2[0];
	var check2=check1.split("/");
	var check=check2[2]+"/"+check2[1]+"/"+check2[0];
    fDate = Date.parse(from);
    lDate = Date.parse(to);
    cDate = Date.parse(check);
	
	if(fDate_array.length==0)
	{
		if((cDate <= lDate && cDate >= fDate)) {
				fDate_array.push(fDate);
				lDate_array.push(lDate);
				cDate_array.push(cDate);
				return true;
		}
		
	}
	else
	{
		for(var i=0;i<fDate_array.length;i++)
		{
			//alert(fDate_array[i]);
			if((cDate <= lDate_array[i] && cDate >= fDate_array[i])) {
				fDate_array.push(fDate);
				lDate_array.push(lDate);
				cDate_array.push(cDate);
				return true;
			}
			
		}
	}
	
	fDate_array.push(fDate);
	lDate_array.push(lDate);
	cDate_array.push(cDate);
	return false;
   
}
 function dateCheckOverlapTo(from1,to1,check1) {
	
    var fDate,lDate,cDate;
	var from2=from1.split("/");
	var from=from2[2]+"/"+from2[1]+"/"+from2[0];
	var to2=to1.split("/");
	var to=to2[2]+"/"+to2[1]+"/"+to2[0];
	var check2=check1.split("/");
	var check=check2[2]+"/"+check2[1]+"/"+check2[0];
    fDate = Date.parse(from);
    lDate = Date.parse(to);
    cDate = Date.parse(check);
	
	if(fDate_array1.length==0)
	{
		if((cDate <= lDate && cDate >= fDate)) {
				fDate_array1.push(fDate);
				lDate_array1.push(lDate);
				cDate_array1.push(cDate);
				return true;
		}
		
	}
	else
	{
		for(var i=0;i<fDate_array1.length;i++)
		{
			//alert(fDate_array[i]);
			if((cDate <= lDate_array1[i] && cDate >= fDate_array1[i])) {
				fDate_array1.push(fDate);
				lDate_array1.push(lDate);
				cDate_array1.push(cDate);
				return true;
			}
			
		}
	}
	
	fDate_array1.push(fDate);
	lDate_array1.push(lDate);
	cDate_array1.push(cDate);
	return false;
  
}

 function show_and_clear_assigned_location_upl(vehicle_id)
 {
	
  var poststr ="vehicleid_to_location="+vehicle_id; 
//alert(vehicle_id);  
 makePOSTRequest('src/php/manage_clear_assigned_location_upl.htm', poststr);
 }
 
 function manage_delete_schedule_assignment_upl(serial,vehicle_id,vehicle_name)
{
	var txt="Are You Sure You Want To Delete This One";
	if(!confirm(txt))
	{
		return false; 
	}
	else
	{
		var poststr = "cla_serial_upl=" + serial+  //cla=clear loaction assignment
					  "&cla_vehicle_id_upl="+vehicle_id+
					  "&cla_vehicle_name_upl="+vehicle_name;						  
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr);   
	}		
}

function manage_edit_schedule_assignment_upl(serial,vehicle_id,vehicle_name,to_date)
{
	document.getElementById("blackout_edit_sa").style.visibility = "visible";
	document.getElementById("divpopup_edit_sa").style.visibility = "visible";
	document.getElementById("blackout_edit_sa").style.display = "block";
	document.getElementById("divpopup_edit_sa").style.display = "block"; 
	document.getElementById("date1").value=to_date;
	document.getElementById("cmp_date1").value=to_date;
	document.getElementById("edit_sa_serial").value=serial;
	document.getElementById("edit_sa_vehicle_id").value=vehicle_id;
	document.getElementById("edit_sa_vehicle_name").value=vehicle_name;
}
function manage_edit_schedule_assignment_1_upl(serial,vehicle_id,vehicle_name,to_date)
{
	//alert("date1="+document.getElementById("date1").value+"date2="+document.getElementById("cmp_date1").value);
	if(document.getElementById("date1").value<document.getElementById("cmp_date1").value)
	{
		alert("Date to should not be less than previous date to");
		return false;
	}
	else
	{
		document.getElementById("blackout_edit_sa").style.visibility = "hidden";
		document.getElementById("divpopup_edit_sa").style.visibility = "hidden";
		document.getElementById("blackout_edit_sa").style.display = "none";
		var poststr = "edit_sa_serial=" +document.getElementById("edit_sa_serial").value+  //cla=clear loaction assignment
		"&date_to="+document.getElementById("date1").value+
						  "&edit_sa_vehicle_id="+document.getElementById("edit_sa_vehicle_id").value+
						  "&edit_sa_vehicle_name="+document.getElementById("edit_sa_vehicle_name").value;						  
			//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.htm', poststr); 
	}
}
function show_location_coord_upl(obj)
 {
    var location_id=document.getElementById("location_id").value;
    if(location_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "location_id_upl=" + encodeURI( document.getElementById("location_id").value);
      //alert("postr="+poststr);
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }  
 }
 function show_selected_halt_no_upl(no_of_halt)
 {
	var poststr ="no_of_halt="+no_of_halt+
				 "&account_id_local="+document.getElementById("account_id_hidden").value; 
	//alert("poststr="+poststr);
	 makePOSTRequest('src/php/manage_min_max_halt_upl.htm', poststr);
 
 }
 
function show_targetplantwise(val)
{
	//alert(val);
	if(val=="7")
	{
		document.getElementById('target_plant').style.display='';
	}
	else
	{
		document.getElementById('target_plant').style.display='none';
	}
	if(val=="6")
	{
		document.getElementById('startdatefrom').style.display='none';
		document.getElementById('enddateto').style.display='none';
	}
	else
	{
		document.getElementById('startdatefrom').style.display='';
		document.getElementById('enddateto').style.display='';
	}
}

function action_manage_invoiceMaterial(action_type)
 {  
    if(action_type=="add")  
    {
	 
		  var material_name=document.getElementById("material_name").value; 
		  var material_code=document.getElementById("material_code").value;
		  if(material_name=="") 
		  {
			alert("Please Enter Material Name"); 
			document.getElementById("material_name").focus();
			return false;
		  }
		  else if( material_code=="") 
		  {
			alert("Please Enter Material Code");
			document.getElementById("material_code").focus();
			return false;
		  }
		  var poststr = "action_type="+encodeURI(action_type ) + 						
						"&material_name="+encodeURI(material_name) +
						"&material_code="+encodeURI(material_code); 
		
    }
    else if(action_type=="edit")
    {		var snoid=document.getElementById("snoid").value;
                if(snoid=="")
		{
			alert("Some thing Problem with Database"); 			
			return false;
		}
		var material_name=document.getElementById("material_name").value; 
		if(material_name=="")
		{
			alert("Please Enter Material Name"); 			
			return false;
		}       
		var material_code=document.getElementById("material_code").value; 
		
		if(material_code=="") 
		{
			alert("Some thing Problem with Database"); 
			return false;
		}
		
		var poststr ="action_type="+encodeURI(action_type ) + 
		"&snoid="+encodeURI(snoid) +
		"&material_name="+encodeURI(material_name) +
		"&material_code="+encodeURI(document.getElementById("material_code").value ); 

    }
   
	//alert("poststr="+poststr);
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_invoiceMaterial.htm', poststr);
 }
function show_invoiceRawMIlkMaterial()
 {
    var material_id=document.getElementById("material_id").value;
    if(material_id=="select")
    {
       
    }
    else
    {
      var poststr = "material_id=" + encodeURI( document.getElementById("material_id").value) ;
      //alert(poststr);
      makePOSTRequest('src/php/manage_ajax_invoiceRawmilkMaterial.htm', poststr);
      //alert(poststr);
    }
 }
 
 /////////////person
 function manage_newtarget_file(file_name)
{	
	var obj=document.manage1.manage_id;
	var result=radio_selection(obj);		
	if(result!=false)
	{
            //alert(file_name);
            var win = window.open(file_name+"?common_id="+result+"", '_blank');
            if (win) {
                //Browser has allowed it to be opened
                win.focus();
            } else {
                //Browser has blocked it
                alert('Please allow popups for this website');
            };


            /*
             document.getElementById('manage1').onsubmit=function() 
             {
      	     document.getElementById('manage1').target = '_blank';
		showManageLoadingMessage();
		manage_edit_prev_interface(file_name,result);
	}*/
    }
    }
 
 function action_manage_person_station(action_type)
 {  
    //alert("action_type="+action_type);    
    if(action_type=="add")  
    {
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
  		//alert("result"+result);
  		if(result!=false)
  		{
  		var add_station_no=document.getElementById("add_station_no").value;   	    
                    var add_station_name=document.getElementById("add_station_name").value; 
    		  var station_coord=document.getElementById("landmark_point").value;
         

                  if(add_station_no=="")
                  {
                        alert("Please Enter Station Number");
                        document.getElementById("add_station_no").focus();
                        return false;
                  }
 

    		  if(add_station_name=="") 
    		  {
      			alert("Please Enter Station Name"); 
      			document.getElementById("add_station_name").focus();
      			return false;
    		  }
    		  if(station_coord=="") 
    		  {
      			alert("Please Add Station");
      			document.getElementById("landmark_point").focus();
      			return false;
    		  }    		  
	        var poststr = "action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                                        "&station_no="+encodeURI(add_station_no) +
					"&station_name="+encodeURI(add_station_name) +
					"&station_coord="+encodeURI(station_coord);
			//alert(poststr);
  		}
    }
    else if(action_type=="edit")
    {    
  		var station_id1=document.getElementById("station_id").value; 
  		if(station_id1=="select")
  		{
  			alert("Please Select Station"); 
  			document.getElementById("station_id").focus();
  			return false;
  		}       
  		var station_name=document.getElementById("station_name").value;
      var customer_no=document.getElementById("customer_no").value; 
  		var station_coord=document.getElementById("landmark_point").value;
  		var distance_variable=document.getElementById("distance_variable").value; 
  		
      if(station_name=="") 
  		{
  			alert("Please Enter Station Name"); 
  			document.getElementById("station_name").focus();
  			return false;
  		}
  		if(customer_no=="") 
  		{
  			alert("Please Add Customer No");
  			document.getElementById("customer_no").focus();
  			return false;
  		}  		
  		if(station_coord=="") 
  		{
  			alert("Please Add Station");
  			document.getElementById("landmark_point").focus();
  			return false;
  		}
		  if(distance_variable=="") 
		  {
  			alert("Please Enter Distance variable"); 
  			document.getElementById("distance_variable").focus();
  			return false;
		  }  		
  		var poststr ="action_type="+encodeURI(action_type ) + 
  		"&station_id="+station_id1 +
  		"&station_name="+station_name +
  		"&customer_no="+customer_no +
  		"&station_coord="+station_coord +
  		"&distance_variable="+distance_variable;
    }
    else if(action_type=="edit_dist_var")
    {  		
      var form_obj=document.manage1.elements['station_id2[]'];      
	   	var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		      
      var dist_var = document.getElementById('distance_variable').value;
          
      if(checkbox_result==false)
      {
        alert("Please Select One Station");
        return false;
      }      
      if( (dist_var == "") || (isNaN(dist_var)) )
      {
        alert("Please Enter valid Distance variable");
        return false;
      }
      
      var poststr = "action_type="+encodeURI(action_type ) +                     
                    "&station_ids=" + encodeURI(checkbox_result)+"&distance_variable="+dist_var;
    }    
    else if(action_type=="delete")
    {
  		var form_obj=document.manage1.elements['station_id[]'];
	   	var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
      
      if(station_id1=="select")
      {
        alert("Please Select Station"); 
        document.getElementById("station_id1").focus();
        return false;
      }    
      var poststr = "action_type="+encodeURI(action_type ) +                     
                    "&station_ids=" + encodeURI(checkbox_result);
    }
	
	  //alert("poststr="+poststr);
	showManageLoadingMessage();
    makePOSTRequest('src/php/action_manage_person_station.htm', poststr);
 }
  function show_station_person(value)
  {
		var account_id_local = document.getElementById('account_id_local').value;
		
    if(value == "select")
		{
		  alert("Please select one Station type");
    }
    else
    {
      var poststr="station_type="+value+"&account_id_local="+account_id_local;
      //alert("POST="+poststr);    
      makePOSTRequest('src/php/manage_edit_person_station_distance_variable.htm', poststr);
    }
  }
  
 function show_person_station_coord(obj)
 {
    var station_id=document.getElementById("station_id").value;
    if(station_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "person_station_id=" + encodeURI( document.getElementById("station_id").value);
      //alert(poststr);
      
      makePOSTRequest('src/php/manage_ajax_geo_coord.htm', poststr);
    }
 }
