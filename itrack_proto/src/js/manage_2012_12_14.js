  function delete_order_item(counter)
  {
	//alert("counter="+counter);
    //alert(order_item_serial);    
    //document.getElementById('order_item'+order_item_serial).disable = true;
    //document.getElementById('order_qty'+order_item_serial).disable = true;
    //alert("before:"+document.getElementById("status"+counter).value);
	 /*if(counter==1)
	  {
		alert("Please Select atleast one entry for finlise");
		return false;
	  }
	  else
	  {*/
		document.getElementById('row'+counter).style.display = 'none';
		document.getElementById("order_item"+counter).value='delete';
		//}
	//document.getElementById('row'+counter).innerHTML = '';    
    //document.getElementById("status"+counter).value = '0';
    //alert("after:"+document.getElementById("status"+counter).value);           
  }  
  
  function show_order_id(vendor_id, status)
  {
	  var poststr="vendor_id="+vendor_id+
	             "&current_status="+status;		
		
		//alert("Poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);    
  }
    
  function show_order_list(vendor_id_to_orderlist)
  {
	//alert("test"+vendor_id_to_orderlist);
	  var poststr="vendor_id_to_orderlist="+vendor_id_to_orderlist;
	  //alert(poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);    
  }
  
   function show_invoiceorder_list(id_to_orderlist)
  {
	//alert("test"+vendor_id_to_orderlist);
	  var poststr="invoice_orderlist="+id_to_orderlist;
	  //alert(poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);    
  }
  
  
  function show_finaliseorder_list(id_to_orderlist)
  {
	//alert("test"+vendor_id_to_orderlist);
	  var poststr="finalise_orderlist="+id_to_orderlist;
	  //alert(poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);    
  }
  
  
  
  function show_orderlist_to_finalise(count)
  {
	 // var poststr="finalise_order_id="+id_to_orderlist;
	  var poststr="finalise_order_id="+document.getElementById("order_id"+count).value+
				  "&finalise_vendor_id="+document.getElementById("vendor_id"+count).value;
	  //alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);    
  }
  
  function show_orderlist_to_invoice(count)
  {
	  var poststr="invoice_order_id="+document.getElementById("order_id"+count).value+
				"&invoice_vendor_id="+document.getElementById("vendor_id"+count).value;
	  //alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);    
  }
  
  function show_vendor_details(vendor_id)
  {
	// var poststr="finalise_order_id="+id_to_orderlist;
	var poststr="vendor_id_to_vendor_details="+vendor_id;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/manage_sales_ajax.php', poststr);    
  }
  
 function show_order_details(order_id)
  {
	// var poststr="finalise_order_id="+id_to_orderlist;
	var poststr="order_id_to_orde_details="+order_id;
	//alert("poststr="+poststr);
	makePOSTRequest('src/php/manage_sales_ajax.php', poststr);    
  }  
  
  /////////// DYNAMIC ROW ADDITION OPEN ///////////
  var counter;
  
  function addRowToTable()
  {
    //alert("add row");
    var tbl = document.getElementById('tblSample');
    var lastRow = tbl.rows.length;
    // if there's no header row in the table, then iteration = lastRow + 1
    //alert(lastRow);
    
    //if(lastRow<16)
    //{
      var iteration = lastRow;
      counter = iteration;
      //alert("c1="+counter);
      
      var row = tbl.insertRow(lastRow);
      
      // left cell
      var cellLeft = row.insertCell(0);
      var textNode = document.createTextNode(iteration);
      cellLeft.appendChild(textNode);
      
      // right cell
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
    //}
    /*else
    {
      alert("Maximum 15 Item allowed at once");
    }*/ 
  }
  
  function removeRowFromTable()
  {
    var tbl = document.getElementById('tblSample');
    var lastRow = tbl.rows.length;
    if (lastRow > 2) tbl.deleteRow(lastRow - 1);
    counter = lastRow-2;
    //alert("c2="+counter);
  }
   /////////// DYNAMIC ROW ADDITION CLOSED ///////////
 
 function add_geo_manually()
 {
   //var coord = prompt("Enter Geo coord:", "");
   var coord = prompt("Enter Geo coord in format:(latA, lngA),(latB, lngB), (latC, lngC)..(latA lngA) [ends with first set]","");
   //alert("coord: "+coord)   
   document.getElementById('geo_coord').value = coord;
 }
 
 function add_sector_manually()
 {
   //var coord = prompt("Enter Geo coord:", "");
   var coord = prompt("Enter Sector coord in format:(latA, lngA),(latB, lngB), (latC, lngC)..(latA lngA) [ends with first set]","");
   //alert("coord: "+coord)   
   document.getElementById('sector_coord').value = coord;
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
			makePOSTRequest('src/php/manage_register_device.php', poststr);
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
	makePOSTRequest('src/php/manage_get_features.php', poststr);   
}

function show_vehicle_register(type, option)   // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
{
	//alert("type="+type+"option="+option);
	if(option=="add_vehicle")
	{
		var poststr="show_form=1";
		makePOSTRequest('src/php/' +type+ '_' +option+ '.php', poststr);
	}
	else
	{
		var account_id_local=document.getElementById('local_account_id').value;
		var poststr = "account_id_local="+account_id_local+
				"&vehicle_display_option="+document.getElementById('vehicle_display_option').value+
				"&page_type="+"no_register"+
				"&options_value="+document.getElementById('options_value').value;
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/' +type+ '_' +option+ '.php', poststr);
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
	makePOSTRequest('src/php/manage_show_account.php', poststr); 
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
	makePOSTRequest('src/php/manage_checkbox_account_new.php', poststr); 
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
	
	function action_manage_listing_files()
	{
		var obj=document.manage1.manage_id;
		var account_id=radio_selection(obj);
		//alert("account_id="+account_id);
		if(account_id!=false)
		{
			document.getElementById("listing_account_id").value="";
			document.getElementById("listing_account_id").value=account_id;		
			document.manage1.action = "src/php/action_manage_listing_files.php";
			document.manage1.target="_blank";
			document.manage1.submit();		
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
	function manage_show_file_1(file_name,action_type)
	{
		var poststr="action_type="+action_type; 
		//alert("file_name="+file_name+" action_type="+action_type);
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
	//alert(module+action+type);	 //alert("common_accoount_id="+common_account_id);
      var poststr = action_type + "=" + encodeURI(common_account_id);  
      makePOSTRequest(file_name, poststr);   
  }
  
  function manage_edit_prev_interface(file_name,common_account_id)    
  { 
	//alert(module+action+type);	 //alert("common_accoount_id="+common_account_id);
      var poststr = "common_id=" + encodeURI(common_account_id); 
	  //alert("poststr="+poststr);

      makePOSTRequest(file_name, poststr);   
  }
  
    function manage_edit_prev_interface_1(file_name,common_account_id,action_type)    
  { 
	//alert(module+action+type);	 //alert("common_accoount_id="+common_account_id);
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
          //alert("in utype2");        
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
		var result1;		var result2;         
    var obj1=document.manage1;
    result1 = validate_manage_add_account(action_type);      ////////it is for form validation;
		result2 = validate_usertype_checkbox(obj1);              ////////it check and set value of usertype it may be fleet,mining,courior etc
	  //alert("result1="+result1+" ,result2="+result2+"action_type="+action_type);       		 
		if(result1!=false && result2!=false)
		{
			if(action_type == "add")
			{ 
        //alert("a="+document.getElementById("password").value);		
				var poststr="action_type=" + encodeURI(action_type)+
				"&login=" + encodeURI( document.getElementById("login").value )+
				"&group_id=" + encodeURI( document.getElementById("group_id").value )+
				"&user_name=" + encodeURI( document.getElementById("user_name").value )+
				"&add_account_id=" + encodeURI( document.getElementById("add_account_id").value )+
				//"user_type=" + encodeURI( document.getElementById("user_type").value )+
				"&user_type=test"+
				"&password="+document.getElementById("password").value+
				//"&user_type=" + encodeURI(get_radio_button_value1(obj1.elements["user_type"]))+
				"&ac_type=" + encodeURI(get_radio_button_value1(obj1.elements["ac_type"]))+
				"&company_type=" + encodeURI( get_radio_button_value1(obj1.elements["company_type"]) )+
				"&perm_type=" + encodeURI( get_radio_button_value1(obj1.elements["perm_type"]) )+
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
				makePOSTRequest('src/php/action_manage_account.php', poststr);
		}
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
			//alert("result="+result+"result_validaton="+result_validaton);	
			//if(result!= false && result_validaton!=false)
			if(result_validaton!=false)
			{
				//alert("in if");
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
				var io_ids=checkbox_selection(obj1);
				if(io_ids!=false)
				{
					var poststr = "action_type=" + encodeURI( action_type )+
								"&device_imei_no=" +device_imei_no+				
								"&io_ids=" +io_ids;
				}
			}
		}
		
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_manage_device.php', poststr); 
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
				if(veh_validation!=false)
				{
					var poststr = "action_type=" +action_type1[0]+ 
					"&account_ids=" +result+			
					"&vehicle_name=" + document.getElementById("vehicle_name").value+
					"&vehicle_number=" +document.getElementById("vehicle_number").value+conditional_string+						
					"&vehicle_type=" +document.getElementById("vehicle_type").value+
					"&category=" +document.getElementById("category").value; 
				}
			}
			var file_name="src/php/action_manage_vehicle.php";
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
			var poststr = "action_type=" +action_type1[0]+ 
					"&account_ids=" +account_result+
					"&device_imei_no="+dev_result+					
					"&vehicle_name=" + document.getElementById("vehicle_name").value+
					"&vehicle_number=" +document.getElementById("vehicle_number").value+conditional_string+						
					"&vehicle_type=" +document.getElementById("vehicle_type").value+
          "&category=" +document.getElementById("category").value;	
          //alert("poststr="+poststr);   
			}
				var file_name="src/php/action_manage_vehicle.php";		
			
		}
		else if(action_type=="edit_action,Person" || action_type=="edit_action,Vehicle")
		{
			var veh_validation=vehicle_validation(action_type);
			if(veh_validation!=false)
			{
			var poststr = "action_type=" + encodeURI(action_type1[0]) +
                        "&vehicle_id=" + encodeURI( document.getElementById("vehicle_id").value ) +
                        "&vehicle_name=" + encodeURI( document.getElementById("vehicle_name").value ) +
                        "&vehicle_number=" + encodeURI( document.getElementById("vehicle_number").value )+conditional_string+
                        "&vehicle_type=" + encodeURI( document.getElementById("vehicle_type").value )+
                        "&category1=" + encodeURI( document.getElementById("category").value); 
            //alert("poststr="+poststr);
						var file_name="src/php/action_manage_vehicle.php";
			}
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
			var file_name="src/php/action_manage_vehicle.php";		
		}	
		else if(action_type=="deregister")
		{
			var selected_values = get_selected_values(obj,"deregister");				
			var poststr = "device=" + encodeURI( selected_values )+
						  "&action_type=" + encodeURI( "deregister" );
			var file_name="src/php/action_manage_vehicle.php";	
		}
		else if(action_type=="assign")
		{
			var result=accounts_for_device();
			poststr="action_type=" + encodeURI( action_type )+
					"&account_string1="+result+
					"&vehicle_ids1="+document.getElementById("common_id").value;
			var file_name="src/php/action_manage_vehicle.php";						
		}
		else if(action_type=="deassign")
		{
				var selected_values = get_selected_values(obj,"deassign");              
				var poststr = "vehicle_ids1=" +selected_values+
								"&action_type=" +"deassign"                  
				var file_name="src/php/action_manage_vehicle.php";		
		}	
		//alert("poststr="+poststr);
		makePOSTRequest(file_name, poststr);  
	} 	
	function vehicle_validation(action_type)
	{

		var action_type1=action_type.split(",");
		//alert("action_type="+action_type1[1]);
		var vehicle_name=document.getElementById('vehicle_name').value;
		if(vehicle_name=="")
		{alert("Vehicle Name field can not be Empty!");document.getElementById('vehicle_name').focus();return false;}
		
		if(vehicle_number=="")
		{alert("Vehicle Number field can not be Empty!");document.getElementById('vehicle_number').focus();return false;}
		var vehicle_number=document.getElementById('vehicle_number').value;
		
		/*if(action_type1[1]!="Person")
		{
			var max_speed=document.getElementById('max_speed').value;
			if(max_speed=="")
			{alert("Max Speed field can not be Empty!");document.getElementById('max_speed').focus();return false;}
			var vehicle_tag=document.getElementById('vehicle_tag').value;
			if(vehicle_tag=="")
			{alert("Vehicle Tag field can not be Empty!");document.getElementById('vehicle_tag').focus();return false;}		
		}*/
		
		var vehicle_type=document.getElementById('vehicle_type').value;

		if(vehicle_type=="select")
		{alert("Select Vehicle Type option!");document.getElementById('vehicle_type').focus();return false;}
    var category1=document.getElementById('category').value;

		if(category1=="select")
		{alert("Select Category option!");document.getElementById('category').focus();return false;}		
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
        makePOSTRequest('src/php/action_manage_group.php', poststr);
      }
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
            /*var poststr = "action_type=" + encodeURI( action_type ) +
						"&local_account_ids=" + encodeURI(result) +
                        "&date=" + encodeURI( document.getElementById("date1").value ) +
                        "&imei=" + encodeURI( document.getElementById("imei").value ) +
                        "&before_load=" + encodeURI( document.getElementById("before_load").value ) +
                        "&after_load=" + encodeURI( document.getElementById("after_load").value ) +
                        "&remark=" + encodeURI( remark ); */
                        
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
        makePOSTRequest('src/php/action_manage_load_cell.php', poststr);
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
				"&file_type=" + encodeURI(file_type);
			}
			//alert("poststr="+poststr);
			makePOSTRequest('src/php/manage_availability.php', poststr);
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
   makePOSTRequest1('src/php/manage_add_geofence.php', '');
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
    //var duration_tmp = document.manage1.duration.value;
        
  	
    //********** SELECT PERSON
    
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
			//var form_obj1=document.manage1.calibration_id;
			//var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			//if(radio_result!=false)
			//{
			var poststr="action_type="+encodeURI(action_type) +
                  "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&escalation_id="+escalation_result +
                  "&alert_ids="+alertid_result + 
                  "&sector_id=" +radio_result4 +
                  "&duration=" +duration_result;
			//}					
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
    makePOSTRequest('src/php/action_manage_sector.php', poststr);
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
    makePOSTRequest('src/php/action_manage_visit_area.php', poststr);
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
        //var poststr = "action_type="+encodeURI(action_type ) + 
        //"&local_account_ids="+encodeURI(result);
         //alert("before upload"+document.getElementById('file_upload_form'));          
         
      	 document.getElementById('file_upload_form').action_type.value = action_type;
      	 document.getElementById('file_upload_form').local_account_ids.value = result;
      	                        
         document.getElementById('file_upload_form').onsubmit=function() {
      	 document.getElementById('file_upload_form').target = '_blank'; //'upload_target' is the name of the iframe
      	 }
         
         document.getElementById('file_upload_form').submit();              
      }
    }
 }

 function action_manage_station(action_type)
 {  
    if(action_type=="add")  
    {
  		var obj=document.manage1.elements['manage_id[]'];
  		var result=checkbox_selection(obj);
  		//alert("result"+result);
  		if(result!=false)
  		{   	    
          var add_station_name=document.getElementById("add_station_name").value; 
    		  var station_coord=document.getElementById("landmark_point").value;    		  
    		  
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
					"&station_name="+encodeURI(add_station_name) +
					"&station_coord="+encodeURI(station_coord);
  		}
    }
    else if(action_type=="edit")
    {		
      //var obj1=document.manage1.manage_id;
      //var result_radio1=radio_selection(obj1);

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
    else if(action_type=="delete")
    {
      //var obj2=document.manage1.manage_id;
      //var result_radio2=radio_selection(obj2);
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
    makePOSTRequest('src/php/action_manage_station.php', poststr);
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
    makePOSTRequest('src/php/action_manage_calibration.php', poststr);
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
		makePOSTRequest('src/php/manage_ajax.php', poststr);
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
			makePOSTRequest('src/php/manage_ajax.php', poststr);	
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
			makePOSTRequest('src/php/manage_ajax.php', poststr);	
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
			makePOSTRequest('src/php/manage_ajax.php', poststr);	
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
			makePOSTRequest('src/php/manage_ajax.php', poststr);	
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
			makePOSTRequest('src/php/manage_ajax.php', poststr);	
		}
	}
	
	
	
	
	
	function show_distributor_retailer_detials(value)
	{		
		var poststr = "dis_retailer_id="+value;
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/manage_sales_ajax.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax.php', poststr);
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
    makePOSTRequest('src/php/action_manage_vendor_type.php', poststr);    
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
		makePOSTRequest('src/php/action_manage_vendor_account.php', poststr);
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
          //alert("duration="+duration_id_tmp);         
          //alert("duration_value1="+document.getElementById(duration_id_tmp).value);
          
          if( (document.getElementById(duration_id_tmp).value == "") && (form_obj1[i].value==1 || form_obj1[i].value==2 || form_obj1[i].value==10 || form_obj1[i].value==12 || form_obj1[i].value==13) )
          {
            alert("Please fill the duration field");
            return false; 
          }
          //alert("dur1:"+form_obj3[i].value);          
          //var duration_id = document.manage1.elements[duration_id_tmp];
          
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
			//var form_obj1=document.manage1.calibration_id;
			//var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			//if(radio_result!=false)
			//{
      var poststr="action_type="+encodeURI(action_type) +
                  "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&landmark_id="+landmark +
					        "&distance_variable="+distance_variable +					        
                  "&alert_id="+alert_id +	
                  "&duration=" +duration +	
                  "&sms_status=" +sms_status +
                  "&mail_status=" +mail_status +      
                  "&vehicle_ids=" +vehicle_ids +            			        
                  "&escalation_ids="+checkbox_result;
			//}	
      //alert(poststr);						  
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
    makePOSTRequest('src/php/action_manage_escalation.php', poststr);
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

  
  //////////// ORDER MODULE ////////////// 
  
  
  //////////saless module
  function action_manage_expense_type(action_type)
{
    var title=document.getElementById("title").value;  // common field for add edit delete event //////
	if(action_type=="delete") /* for confirmation alert of delete action for delete it will be true or false and other action it always true */
	{       
	  var result=delete_confirmation();
	}
	else
	{
	   var result=true;
	}
 
    if(action_type=="add") 
    {       
        var group_id=document.getElementById("expense_group_id").value;            
        var expense_type_name=document.getElementById("expense_type_name").value;
        var remark=document.getElementById("remark").value;
        if(group_id=="select") 
        {
          alert("Please Select Group Name"); 
          document.getElementById("group_id").focus();
          return false;
        }               
        else if(expense_type_name=="") 
        {
          alert("Please Enter Expense Type Name");
          document.getElementById("expense_type_name").focus();
          return false;
        }
          var poststr = "action_type="+encodeURI(action_type) + 
          "&title="+title+
          "&group_id="+encodeURI(group_id) +
          "&expense_type_name="+encodeURI(expense_type_name) +
          "&remark="+encodeURI(remark);          
    } 
    else if(action_type=="edit" || action_type=="delete") 
    {
      var group_id=document.getElementById("expense_group_id").value; 
      var expense_type_id=document.getElementById("expense_type_id").value;
      var expense_type_name="";   
      if(group_id=="select") 
      {
        alert("Please Select Group Name"); 
        document.getElementById("group_id").focus();
        return false;        
      }
      else if(expense_type_id=="select")
      {
        alert("Please Select Expense Type Name"); 
        document.getElementById("expense_type_id").focus();
        return false;
      } 
      if(action_type=="edit")
      {
        expense_type_name=document.getElementById("expense_type_name").value;
        if(expense_type_name=="") 
        {
          alert("Please Enter Expense Type Name");
          document.getElementById("expense_type_name").focus();
          return false;
        }
      } 
     var poststr="action_type="+action_type+
                  "&title="+title+
                  "&group_id="+group_id+
                  "&expense_type_id="+expense_type_id+
                  "&expense_type_name="+expense_type_name;  
    }
    //alert("poststr="+poststr);
    makePOSTRequest('src/php/action_manage_expense_type.php', poststr);    
}

function action_manage_expense(action_type)
	{
		 var title=document.getElementById("title").value; 
		if(action_type=="add")
		{
			if(document.getElementById("sales_id").value=="")
			{
				alert("Please Select Sales Person");
				document.getElementById("sales_id").focus();
				return false;
			} 
			
			var exp_arr=document.manage1.elements['expense_size[]'];
			var expensetypeid_and_amount="";
			if(exp_arr.length!=undefined)
			{
				for(var i=0;i<exp_arr.length;i++)
				{
					var id1='A'+i;
					var id2='B'+i;
					//alert("tmp="+tmp);
					var expense_type_id=document.getElementById(id1).value;	
					var amount=document.getElementById(id2).value;
					if(amount=="")
					{
						alert("Please Enter Amount");
						document.getElementById(id2).focus();
						break;
					}
					expensetypeid_and_amount=expensetypeid_and_amount+expense_type_id+","+amount+":";
				}
			}
			else
			{	
				var sales_id=document.getElementById('A0').value;	
				var amount=document.getElementById('B0').value;
				amount_with_salesid=amount_with_salesid+sales_id+","+amount+":";
			}
			var poststr="action_type="+action_type+
			"&common_match_id="+document.getElementById("sales_id").value+
			"&title="+title+
			"&expensetypeid_and_amount="+expensetypeid_and_amount+
			"&remark="+document.getElementById("remark").value;
		}
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_manage_expense.php', poststr);		
	}
	

function action_manage_call_type(action_type)
{
	//alert("tst");
    var title=document.getElementById("title").value;  // common field for add edit delete event //////
	 if(action_type=="delete") /* for confirmation alert of delete action for delete it will be true or false and other action it always true */
	{       
	  var result=delete_confirmation();
	}
	else
	{
	   var result=true;
	}
	if(action_type=="add") 
    {       
        var group_id=document.getElementById("call_group_id").value;            
        var call_type_name=document.getElementById("call_type_name").value;
        var remark=document.getElementById("remark").value;
        if(group_id=="select") 
        {
          alert("Please Select Group Name"); 
          document.getElementById("group_id").focus();
          return false;
        }               
        else if(call_type_name=="") 
        {
          alert("Please Enter Call Type Name");
          document.getElementById("call_type_name").focus();
          return false;
        }
          var poststr = "action_type="+encodeURI(action_type) + 
          "&title="+title+
          "&group_id="+encodeURI(group_id) +
          "&call_type_name="+encodeURI(call_type_name) +
          "&remark="+encodeURI(remark);          
    } 
    else if(action_type=="edit" || action_type=="delete") 
    {
      var group_id=document.getElementById("call_group_id").value; 
      var call_type_id=document.getElementById("call_type_id").value;
      var expense_call_name="";   
      if(group_id=="select") 
      {
        alert("Please Select Group Name"); 
        document.getElementById("group_id").focus();
        return false;        
      }
      else if(call_type_id=="select")
      {
        alert("Please Select Call Type Name"); 
        document.getElementById("call_type_id").focus();
        return false;
      } 
      if(action_type=="edit")
      {
        call_type_name=document.getElementById("call_type_name").value;
        if(call_type_name=="") 
        {
          alert("Please Enter Call Type Name");
          document.getElementById("call_type_name").focus();
          return false;
        }
      } 
     var poststr="action_type="+action_type+
                  "&title="+title+
                  "&group_id="+group_id+
                  "&call_type_id="+call_type_id+
                  "&call_type_name="+call_type_name;  
    }
    //alert("poststr="+poststr);
    makePOSTRequest('src/php/action_manage_call_type.php', poststr);    
}

function action_manage_call_detail(action_type)
	{
		 var title=document.getElementById("title").value; 
		if(action_type=="add")
		{
			if(document.getElementById("sales_id").value=="select")
			{
				alert("Please Select Sales Person");
				document.getElementById("sales_id").focus();
				return false;
			} 
			else if(document.getElementById("vendor_id").value=="select")
			{
				alert("Please Select Vendor");
				document.getElementById("vendor_id").focus();
				return false;
			} 
			
			var obj=document.manage1.manage_id;
			var result=radio_selection(obj);
			if(result!=false)
			{		
				var poststr="action_type="+action_type+
				"&title="+title+
				"&common_match_id="+document.getElementById("sales_id").value+				
				"&vendor_id="+document.getElementById("vendor_id").value+
				"&call_type_id="+result+				
				"&remark="+document.getElementById("remark").value;
			}
		}
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_manage_call_detail.php', poststr);		
	}
	
	
	function action_manage_sales_route(action_type)
	{
		 var title=document.getElementById("title").value; 
		if(action_type=="add")
		{
			if(document.getElementById("vendor_type_id").value=="select")
			{
				alert("Please Select Vendor Type");
				document.getElementById("vendor_type_id").focus();
				return false;
			}			
			var form_obj=document.manage1.elements['manage_id[]'];		
			var result=checkbox_selection(form_obj);
			if(result!=false)
			{			
				if(document.getElementById("route_name").value=="")
				{
					alert("Please Enter Route Name");
					document.getElementById("route_name").focus();
					return false;
				} 
				var poststr="action_type="+action_type+
							"&title="+title+
							"&group_id_local="+document.getElementById("vendor_group_id").value+
							"&vendor_ids="+result+
							"&route_name="+document.getElementById("route_name").value;
			}			
		}
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_manage_sales_route.php', poststr);		
	}
	
	function action_manage_add_day_plan(action_type)
	{
		 var title=document.getElementById("title").value; 
		 //alert("title="+title);
		if(action_type=="add")
		{
			/*if(document.getElementById("account_id_local").value=="select")
			{
				alert("Please Select One Account");
				document.getElementById("account_id_local").focus();
				return false;
			}*/
			if(document.getElementById("sales_id").value=="select")
			{
				alert("Please Select One Sales Person");
				document.getElementById("sales_id").focus();
				return false;
			}
			if(document.getElementById("route_id").value=="select")
			{
				alert("Please Select One Route Name");
				document.getElementById("route_id").focus();
				return false;
			}
			
			var form_obj=document.manage1.elements['manage_id[]'];		
			var result=checkbox_selection(form_obj);
			
			if(result!=false)
			{			
				var poststr="action_type="+action_type+
							"&title="+title+
							"&sales_id="+document.getElementById("sales_id").value+
							"&route_id="+document.getElementById("route_id").value+
							"&vendor_ids="+result;
			}			
		}
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/action_manage_add_day_plan.php', poststr);		
	}

 function show_expense_type(obj)
 {
    var expense_group_id=document.getElementById("expense_group_id").value;
    if(expense_group_id=="select")
    {
      remOption(document.getElementById("expense_type_id"));
  	  addOption(document.getElementById("expense_type_id"),'Select','select');
  	  addOption(document.getElementById("expense_type_id"),'None','0');
      alert("Please Select Group"); 
      document.getElementById("expense_group_id").focus();          
      return false; 
    }
    else
    {
      var poststr = "expense_group_id="+expense_group_id;
      //alert("poststr="+poststr);
      makePOSTRequest('src/php/manage_ajax.php', poststr);
    }
 }
 

 function show_expense_type_name(obj)
 {   
    var expense_type_id=document.getElementById("expense_type_id").value;
	//alert("vendor_type="+vendor_type_id);
    if(expense_type_id=="select")
    {     
      alert("Please Select Expense Type"); 
      document.getElementById("expense_type_id").focus();          
      return false; 
    }	
    else
    {
  		var poststr = "expense_type_id="+expense_type_id;
  		//alert("poststr1="+poststr);
  		makePOSTRequest('src/php/manage_ajax.php', poststr);
    }
 }
 
  function show_call_type(obj)
 {
    var call_group_id=document.getElementById("call_group_id").value;
    if(call_group_id=="select")
    {
      remOption(document.getElementById("call_type_id"));
  	  addOption(document.getElementById("call_type_id"),'Select','select');
  	  addOption(document.getElementById("call_type_id"),'None','0');
      alert("Please Select Group"); 
      document.getElementById("call_group_id").focus();          
      return false; 
    }
    else
    {
      var poststr = "call_group_id="+call_group_id;
      //alert("poststr="+poststr);
      makePOSTRequest('src/php/manage_ajax.php', poststr);
    }
 }
 
  function show_call_type_name(obj)
 {   
    var call_type_id=document.getElementById("call_type_id").value;
	//alert("vendor_type="+vendor_type_id);
    if(call_type_id=="select")
    {     
      alert("Please Select Call Type"); 
      document.getElementById("call_type_id").focus();          
      return false; 
    }	
    else
    {
  		var poststr = "call_type_id="+call_type_id;
  		//alert("poststr1="+poststr);
  		makePOSTRequest('src/php/manage_ajax.php', poststr);
    }
 }
 
  function action_manage_order(action_type)
  {     
    //alert("action_type="+action_type);           
    var order_item_all;
    var order_qty_all;
    var unit_price_all;
    var any_other_cost_all;
    var discount_all;
    var total_price_all;
    
    order_item_all = "";
    order_qty_all = "";
    unit_price_all = "";
    any_other_cost_all = "";
    discount_all = "";
    total_price_all = "";
      
    if(action_type=="add")  
    {    
      //alert("counter="+counter);   
      if(counter==undefined || counter==0)
      {
        counter=1;
      }
      
      for(var i=1;i<=counter;i++)
      {
        //alert(i+"counter="+counter);  
        var order_item;
        var order_qty;
        var unit_price;
        var any_other_cost;
        var discount;
        var total_price;
        var val;
        
        order_item = document.getElementById('order_item'+i).value;
        order_qty = document.getElementById('order_qty'+i).value;
        unit_price = document.getElementById('unit_price'+i).value;
        any_other_cost = document.getElementById('any_other_cost'+i).value;
        discount = document.getElementById('discount'+i).value;
        total_price = document.getElementById('total_price'+i).value;
        
        if(order_item=="")
        {
          alert("Please Enter Order Item in cell ("+i+",1)");
          document.getElementById('order_item'+i).value="";
          document.getElementById('order_item'+i).focus();
          return false;
        } 
                
        val = checkforInteger(order_qty);        
        if(!val)
        {
          alert("Please Enter valid Order Quantity In cell ("+i+",2)");
          document.getElementById('order_qty'+i).value="";
          document.getElementById('order_qty'+i).focus();
          return false;
        }              
        val = checkforPrice(unit_price)
        if(!val)
        {
          alert("Please Enter valid Unit Price In cell ("+i+",3)");
          document.getElementById('unit_price'+i).value="";
          document.getElementById('unit_price'+i).focus();
          return false;
        }
        val = checkforPrice(total_price)
        if(!val)
        {
          alert("Please Enter valid Total Price In cell ("+i+",6)");
          document.getElementById('total_price'+i).value ="";
          document.getElementById('total_price'+i).focus();
          return false;
        }                    
                                                                                                 
        if(any_other_cost=="")
          any_other_cost = "0";
        
        if(discount=="")
          discount = "0";
  
        // SUM THE ELEMENTS    
        if(i==1)
        {
          order_item_all = order_item_all+""+order_item;
          order_qty_all = order_qty_all+""+order_qty;
          unit_price_all = unit_price_all+""+unit_price;
          any_other_cost_all = any_other_cost_all+""+any_other_cost;
          discount_all = discount_all+""+discount;
          total_price_all = total_price_all+""+total_price;
          //alert("order_itemA="+order_item);
        }
        else
        {        
          order_item_all = order_item_all+","+order_item;
          //alert("order_itemB="+order_item);
          order_qty_all = order_qty_all+","+order_qty;
          unit_price_all = unit_price_all+","+unit_price;
          any_other_cost_all = any_other_cost_all+","+any_other_cost;
          discount_all = discount_all+","+discount;
          total_price_all = total_price_all+","+total_price;
        }                
      }  
      
      //var obj=document.manage1.elements['manage_id[]'];
      //var result=checkbox_selection(obj);
      //alert("result"+result);
      /*var result = true;
      var vendor_id = 1;
      var ref_id = 2;
      var ref_type_id = 4;*/
        
	  
	  var obj1 = document.manage1.manage_id;
	  var ref_id = radio_selection(obj1);
	  
	  var obj2 = document.manage1.vendor_id;
	  var vendor_id1 = radio_selection(obj2);	  
	  
	  //alert("ref_id="+ref_id+" ,vendor_id="+vendor_id);
	  
	  if(result!=false)
      {   	                                   
        /*if(vendor_id=="select") 
        {
          alert("Please Select Vendor ID"); 
          document.getElementById("vendor_id").focus();
          return false;
        }
        
        if(ref_id=="select") 
        {
          alert("Please Select Reference ID"); 
          document.getElementById("ref_id_id").focus();
          return false;
        }
        
        if(ref_type_id=="select") 
        {
          alert("Please Select Reference Type"); 
          document.getElementById("ref_type_id").focus();
          return false;
        } */                                                                               

        var poststr = "action_type="+encodeURI(action_type ) +
		"&title="+encodeURI(document.getElementById("title").value)+		
        "&order_item_all="+encodeURI(order_item_all) +
        "&order_qty_all="+encodeURI(order_qty_all) +
        "&unit_price_all="+encodeURI(unit_price_all) +        
        "&any_other_cost_all="+encodeURI(any_other_cost_all) +
        "&discount_all="+encodeURI(discount_all) + 
        "&total_price_all="+encodeURI(total_price_all) +
        "&vendor_id="+encodeURI(vendor_id1) +
        "&ref_id="+encodeURI(ref_id) +
        "&ref_type_id="+encodeURI(document.getElementById("vendor_type_id").value); 
			//alert("poststr="+poststr);		
      }
    }
    else if(action_type=="finalise")               
    {		
		var order_item_all ="";
		var record ="";		
		var item_count = document.getElementById("item_count").value;       
		for(var i=1;i<=item_count;i++)
		{ 
			if(document.getElementById("order_item"+i).value!='delete')
			{
				order_item = document.getElementById("order_item"+i).value;                                   
				order_qty = document.getElementById('order_qty'+i).value;
				unit_price = document.getElementById('unit_price'+i).value;
				any_other_cost = document.getElementById('any_other_cost'+i).value;
				discount = document.getElementById('discount'+i).value;
				total_price = document.getElementById('total_price'+i).value;

				if(order_item=="")
				{
				alert("Please Enter Order Item in cell ("+i+",1)");
				document.getElementById('order_item'+i).value="";
				document.getElementById('order_item'+i).focus();
				return false;
				} 

				val = checkforInteger(order_qty);        
				if(!val)
				{
				alert("Please Enter valid Order Quantity In cell ("+i+",2)");
				document.getElementById('order_qty'+i).value="";
				document.getElementById('order_qty'+i).focus();
				return false;
				}              
				val = checkforPrice(unit_price)
				if(!val)
				{
				alert("Please Enter valid Unit Price In cell ("+i+",3)");
				document.getElementById('unit_price'+i).value="";
				document.getElementById('unit_price'+i).focus();
				return false;
				}
				val = checkforPrice(total_price)
				if(!val)
				{
				alert("Please Enter valid Total Price In cell ("+i+",6)");
				document.getElementById('total_price'+i).value ="";
				document.getElementById('total_price'+i).focus();
				return false;
				}                    
				   
				if(any_other_cost=="")
				any_other_cost = "0";

				if(discount=="")
				discount = "0";

				// SUM THE ELEMENTS    
				if(i==1)
				{          
				order_item_all = order_item_all+""+order_item;
				order_qty_all = order_qty_all+""+order_qty;
				unit_price_all = unit_price_all+""+unit_price;
				any_other_cost_all = any_other_cost_all+""+any_other_cost;
				discount_all = discount_all+""+discount;
				total_price_all = total_price_all+""+total_price;
				}
				else
				{
				order_item_all = order_item_all+","+order_item;		
				order_qty_all = order_qty_all+","+order_qty;
				unit_price_all = unit_price_all+","+unit_price;
				any_other_cost_all = any_other_cost_all+","+any_other_cost;
				discount_all = discount_all+","+discount;
				total_price_all = total_price_all+","+total_price;          
				}
			}
		}
		
		var poststr = "action_type="+encodeURI(action_type) + 
		"&title="+encodeURI(document.getElementById("title").value)+
		"&vendor_id="+encodeURI(document.getElementById("vendor_id").value) +	
		"&order_id="+encodeURI(document.getElementById("order_id").value)+
		"&order_item_all="+encodeURI(order_item_all) +
		"&order_qty_all="+encodeURI(order_qty_all) +
		"&unit_price_all="+encodeURI(unit_price_all) +        
		"&any_other_cost_all="+encodeURI(any_other_cost_all) +
		"&discount_all="+encodeURI(discount_all) + 
		"&total_price_all="+encodeURI(total_price_all);	
    }
    else if(action_type=="delete")
    {
      var escalation_id1=document.getElementById("order_id").value;
      if(escalation_id1=="select")
      {
        alert("Please Select Order"); 
        document.getElementById("order_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete this Order!";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
                    "&order_id=" + encodeURI(order_id1);
    }
	
	else if(action_type=="invoice")
    {
        var poststr = "action_type="+encodeURI(action_type) + 
                    "&invoice_id="+encodeURI(document.getElementById("invoice_id").value)+
					"&order_id="+encodeURI(document.getElementById("order_id").value)+ 
					"&item_detail="+encodeURI(document.getElementById("item_detail").value)+
					"&total_invoice="+encodeURI(document.getElementById("total_invoice").value)+
					"&total_tax="+encodeURI(document.getElementById("total_tax").value);
					
    }
    
   //alert(poststr);
    makePOSTRequest('src/php/action_manage_order.php', poststr);        
  }
  
  function action_manage_dispatch(count)
  {
	
	var poststr = "action_type=dispatch"+ 
				"&invoice_id="+encodeURI(document.getElementById("invoice_id"+count).value)+
				"&dispatch_detail="+encodeURI(document.getElementById("dispatch_detail"+count).value)+
				"&count="+count;
				
		//document.getElementById("row"+count).style.display='none';	
	//alert(poststr);
    makePOSTRequest('src/php/action_manage_order.php', poststr);  
  }
  
  function manage_generate_invoice(parameter)
  {  
	var order_item_all;
	var order_qty_all;
	var unit_price_all;
	var any_other_cost_all;
	var discount_all;
	var total_price_all;
	var tax_all;

	order_item_all = "";
	order_qty_all = "";
	unit_price_all = "";
	any_other_cost_all = "";
	discount_all = "";
	total_price_all = "";
	tax_all = "";
	//alert("test");	
	var item_count = document.getElementById("item_count").value;       
	for(var i=1;i<=item_count;i++)
	{
		var order_item;
		var order_qty;
		var unit_price;
		var any_other_cost;
		var discount;
		var total_price;
		var tax;
		var val;	
		
		order_item = document.getElementById("order_item"+i).value;                                   
		order_qty = document.getElementById('order_qty'+i).value;
		unit_price = document.getElementById('unit_price'+i).value;	
		any_other_cost = document.getElementById('any_other_cost'+i).value;		
		discount = document.getElementById('discount'+i).value;
		total_price = document.getElementById('total_price'+i).value;
		tax = document.getElementById('tax'+i).value;

		val = checkforPrice(tax)
		if(!val)
		{
			alert("Please Enter valid Tax Price In cell ("+i+",7)");
			document.getElementById('tax'+i).value ="";
			document.getElementById('tax'+i).focus();
			return false;
		}  
		
		if(discount=="")
		discount = "0";

		// SUM THE ELEMENTS    
		if(i==1)
		{          
			order_item_all = order_item_all+""+order_item;
			order_qty_all = order_qty_all+""+order_qty;
			unit_price_all = unit_price_all+""+unit_price;
			any_other_cost_all = any_other_cost_all+""+any_other_cost;
			discount_all = discount_all+""+discount;
			total_price_all = total_price_all+""+total_price;
			tax=total_price*(tax*1/100);
			tax_all=tax_all+""+tax;
		}
		else
		{
			order_item_all = order_item_all+","+order_item;		
			order_qty_all = order_qty_all+","+order_qty;
			unit_price_all = unit_price_all+","+unit_price;
			any_other_cost_all = any_other_cost_all+","+any_other_cost;
			discount_all = discount_all+","+discount;
			total_price_all = total_price_all+","+total_price;
			tax=total_price*(tax*1/100);
			tax_all=tax_all+","+tax;		
		}
	}
		var poststr = "action_type=inovoice_format"+ 
		"&title="+encodeURI(parameter)+
		"&vendor_id="+encodeURI(document.getElementById("vendor_id").value) +	
		"&order_id="+encodeURI(document.getElementById("order_id").value)+
		"&order_item_all="+encodeURI(order_item_all) +
		"&order_qty_all="+encodeURI(order_qty_all) +
		"&unit_price_all="+encodeURI(unit_price_all) + 	
		"&any_other_cost_all="+encodeURI(any_other_cost_all) +
		"&discount_all="+encodeURI(discount_all) + 
		"&total_price_all="+encodeURI(total_price_all)+
		"&tax_all="+encodeURI(tax_all);
		//var poststr="parameter="+parameter;

		//alert("poststr="+poststr);
		makePOSTRequest('src/php/manage_invoice_format.php', poststr);    
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
		makePOSTRequest('src/php/' + type + '_' + option + '.php', poststr);
	}
	function show_group_accounts(group_id_to_account)
  {		
    var poststr = "group_id_to_account="+group_id_to_account;
    //alert("poststr1="+poststr);
    makePOSTRequest('src/php/manage_ajax.php', poststr);
  }
  
  function show_route_vendor(route_id_to_vendor)
  {	
	if(route_id_to_vendor=="select")
	{
		document.getElementById('vendor_div_block').style.display='none';
		return false;
	}
	else
	{
		var poststr = "route_id_to_vendor="+route_id_to_vendor;
		//alert("poststr1="+poststr);
		makePOSTRequest('src/php/manage_ajax.php', poststr);
	}
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
		makePOSTRequest('src/php/manage_ajax.php', poststr);
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
			makePOSTRequest('src/php/manage_ajax.php', poststr);
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
		makePOSTRequest('src/php/manage_ajax.php', poststr);
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
/////////////////////////////////////////////stop sales ///////////////////
	
 
  /* 
 function assigned_alert_info(escalation_id)
 {
  alert(escalation_id);
  var poststr="escalation_assigned_id="+encodeURI(escalation_id);
  makePOSTRequest('src/php/manage_ajax_escalation.php', poststr);
 }
 */
 //////////// ESCALATION CLOSED ///////
 

 /* function action_manage_calibration(action_type)
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
			alert("Please Enter Geofence Name"); 
			document.getElementById("calibration_name").focus();
			return false;
		}
		else if(calibration_data=="") 
		{
			alert("Please Draw Calibration");
			document.getElementById("calibration_data").focus();
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
    
	//alert("poststr="+poststr);
    makePOSTRequest('src/php/action_manage_calibration.php', poststr);
 }  */
    
 function manage_add_route() 
 { 
   makePOSTRequest('src/php/manage_add_route.php', '');
 }
 
 function show_vehicles(obj) 
 {
   poststr="create_id="+encodeURI(obj);
   makePOSTRequest('src/php/manage_availability.php', poststr);
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
    makePOSTRequest('src/php/action_manage_route.php', poststr);
 }
   
      
   
 function manage_add_landmark() 
 {
   makePOSTRequest('src/php/manage_add_landmark.php', '');
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
	makePOSTRequest('src/php/action_manage_landmark.php', poststr);
	}   
	function landmark_form_validation(landmark_name,landmark_point,zoom_level)
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
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax_sector_coord.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax_sector_coord.php', poststr);
    }
 }  
   
 function test()
 {
	//alert("test");
 }
 
 function show_landmark_coord(obj)
 {
    var landmark_id=document.getElementById("landmark_id").value;
	 
   //alert("landmark_id="+landmark_id);
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
    document.getElementById('select_zoom_level').value=document.getElementById('set_zoom_level').value;
 }
 
 /*function show_station_coord(obj)
 {
    var landmark_id=document.getElementById("landmark_id").value;
	 
   //alert("landmark_id="+landmark_id);
    if(landmark_id=="select")
    {
      document.getElementById("coord_area").style.display="none"; 
    }
    else
    {
      var poststr = "station_id=" + encodeURI( document.getElementById("landmark_id").value);
      //alert("postr="+poststr);
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
    }
    //document.getElementById('select_zoom_level').value=document.getElementById('set_zoom_level').value;
 } */
   
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
    else if(common_event=="sector")
    {
        coord=document.getElementById("sector_coord").value;       
        poly_type=GPolyline;        
        var id_param="sector_coord";
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
      if(common_event=="geofencing" || common_event=="sector") 
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
			//alert("markers="+marker);
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
		map.enableGoogleBar();

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


function close_landmark_div_station(close_pararm)
{
   var txt="Are You Sure You Want To Close Without Saving Points";
   if(!confirm(txt))
   {
     return false; 
   }
   document.getElementById("landmark_point").value="";    /////// at the time of add landmark
   //document.getElementById("landmark_point").value=document.getElementById("prev_landmark_point").value;  ///at the time of edit landmark
   //prev_landmark_point
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
			var perm_type = get_radio_button_value1(obj.elements["perm_type"]);								
		}
		if(action_type=="add" || action_type=="edit")
		{
			var user_name = document.getElementById("user_name").value;	
			var ac_type = get_radio_button_value1(obj.elements["perm_type"]);			
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
		
		if(ac_type==-1)
		{alert("Must Select Atleast one Permission option!");return false;}	
		
		if(perm_type==-1)
		{alert("Must Select Atleast one Admin Permission option!");return false;} 
		
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
      vehicle_id[veh_count] =  vehicle_id_obj.value;
			io_type_value[veh_count]=document.getElementById(vehicle_id_obj.value).value;	
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


//------------------------- For School Option --------------------------------------------------
//============New code is updated by taseen for school vts=======================================//
//module for class//
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

    makePOSTRequest('src/php/action_manage_studentclass.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
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
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    } 
 }
 
 function show_studentclass_section_add(obj)
 { // alert("hello");
  var add_student_class=document.getElementById("add_student_class").value;
  //alert("dfdf "+add_student_class);
  var class_sec=  add_student_class.split(":");
  //alert(class_sec[1]);
  document.getElementById("add_student_section").value  = class_sec[1];
 }
 
 function show_studentclass_section_edit(obj)
 { // alert("hello");
  var edit_student_class=document.getElementById("edit_student_class").value;
  //alert("dfdf "+edit_student_class);
  var class_sec=  edit_student_class.split(":");
  //alert(class_sec[1]);
  document.getElementById("edit_student_section").value  = class_sec[1];
 }
 
 function show_student_classwise_edit(obj)
 { // alert("hello");
    var student_classwise_id=document.getElementById("student_classwise_id").value;
    //alert("df "+student_classwise_id);
    
    if(student_classwise_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "student_classwise_id_edit=" + encodeURI( document.getElementById("student_classwise_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    } 
 }
 
  function show_student_record_byclass(obj)
 {
    //var student_id=document.getElementById("student_id").value;
    var student_id=obj;
    alert(student_id);
    
      var poststr = "student_id_byclass=" + student_id;
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
   
 }
 
 function show_student_record_byclass_edit(obj)
 {
    //var student_id=document.getElementById("student_id").value;
    var selected_account_id= document.getElementById("selected_account_id").value;
    var student_id=obj;
    //alert(student_id);
    //alert("selected_account_id"+selected_account_id);
      var poststr = "student_id_byclass_edit=" + student_id + "&student_id_byclass_edit_selected_account_id=" +selected_account_id;
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
   
 }
//===========End of taseen updation=============================================================//
 function action_manage_school(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_school_name=document.getElementById("add_school_name").value; 
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_school_name=="") 
		  {
			alert("Please Enter School Name"); 
			document.getElementById("add_school_name").focus();
			return false;
		  }
		  		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&school_name="+encodeURI(add_school_name); 
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
    makePOSTRequest('src/php/action_manage_school.php', poststr);
 }
 
function action_manage_student1(obj, action_type)
{
if(action_type=="assign")
		{
			//alert(action_type);
			var result=accounts_for_device();
			poststr="action_type=" + encodeURI( action_type )+
					"&account_string1="+result+
					"&student_ids1="+document.getElementById("common_id").value;
			var file_name="src/php/action_manage_student.php";						
		}
		makePOSTRequest('src/php/action_manage_student.php', poststr);
}
 function action_manage_student(action_type)
 {
    //alert(action_type);
	if(action_type=="add")  
    {
		//var obj=document.manage1.manage_id;
		//var result=radio_selection(obj);
		//alert("result"+result);
		//alert("hiiii");
		//if(result!=false)
		//{
		  var account_id=document.getElementById("account_id_hidden").value;
		  var add_student_name=document.getElementById("add_student_name").value;
		  var add_student_address=document.getElementById("add_student_address").value;
		  var add_student_father_name=document.getElementById("add_student_father_name").value;
		  var add_student_mother_name=document.getElementById("add_student_mother_name").value;
		  var add_student_roll_no=document.getElementById("add_student_roll_no").value;
		  var add_student_class=document.getElementById("add_student_class").value;
		  var class_sec=  add_student_class.split(":");
		  var add_student_section=document.getElementById("add_student_section").value;		  
		  var add_student_student_mobile_no=document.getElementById("add_student_student_mobile_no").value;
		  var add_student_parent_mobile_no=document.getElementById("add_student_parent_mobile_no").value;
		  var studentcard_id=document.getElementById("studentcard_id").value;
		  var bus_id_pick=document.getElementById("bus_id_pick").value;
		  var bus_id_drop=document.getElementById("bus_id_drop").value;
		  var busroute_id_pick=document.getElementById("busroute_id_pick").value;
		  var busroute_id_drop=document.getElementById("busroute_id_drop").value;
		  var shift_id_pick=document.getElementById("shift_id_pick").value;
		  var shift_id_drop=document.getElementById("shift_id_drop").value;
		  var busstop_id_pick=document.getElementById("busstop_id_pick").value;
		  var busstop_id_drop=document.getElementById("busstop_id_drop").value;
              
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_student_name=="") 
		  {
			alert("Please Enter Student Name "); 
			document.getElementById("add_student_name").focus();
			return false;
		  }
		  if(add_student_roll_no=="") 
		  {
			alert("Please Enter roll number "); 
			document.getElementById("add_student_roll_no").focus();
			return false;
		  }
		  if(add_student_class=="") 
		  {
			alert("Please Enter Class "); 
			document.getElementById("add_student_class").focus();
			return false;
		  }
		  if(add_student_section=="") 
		  {
			alert("Please Enter Section "); 
			document.getElementById("add_student_section").focus();
			return false;
		  }
		  if(add_student_parent_mobile_no=="") 
		  {
			alert("Please Enter Parent Mobile No "); 
			document.getElementById("add_student_parent_mobile_no").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(account_id) +
						"&student_name="+encodeURI(add_student_name)+
            "&student_address="+encodeURI(add_student_address) +
            "&student_father_name="+encodeURI(add_student_father_name) +
            "&student_mother_name="+encodeURI(add_student_mother_name) +
            "&student_roll_no="+encodeURI(add_student_roll_no) +
            "&student_class="+encodeURI(class_sec[0]) +
            "&student_section="+encodeURI(add_student_section) +
            "&student_student_mobile_no="+encodeURI(add_student_student_mobile_no) +
            "&student_parent_mobile_no="+encodeURI(add_student_parent_mobile_no) +
            "&studentcard_id="+encodeURI(studentcard_id) +
            "&bus_id_pick="+encodeURI(bus_id_pick) +
            "&bus_id_drop="+encodeURI(bus_id_drop) +
            "&busroute_id_pick="+encodeURI(busroute_id_pick) +
            "&busroute_id_drop="+encodeURI(busroute_id_drop) +
            "&shift_id_pick="+encodeURI(shift_id_pick) +
            "&shift_id_drop="+encodeURI(shift_id_drop) +
            "&busstop_id_pick="+encodeURI(busstop_id_pick) +
            "&busstop_id_drop="+encodeURI(busstop_id_drop); 
            
            //alert(poststr);
		//}
    } 
    else if(action_type=="edit")                                               
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
		  var edit_student_father_name=document.getElementById("edit_student_father_name").value;
		  var edit_student_mother_name=document.getElementById("edit_student_mother_name").value;
		  var edit_student_roll_no=document.getElementById("edit_student_roll_no").value;
		  var edit_student_class=document.getElementById("edit_student_class").value;
		  var class_sec=  edit_student_class.split(":");
		  var edit_student_section=document.getElementById("edit_student_section").value;
		  var edit_student_student_mobile_no=document.getElementById("edit_student_student_mobile_no").value;
		  var edit_student_parent_mobile_no=document.getElementById("edit_student_parent_mobile_no").value;
		  var studentcard_id=document.getElementById("studentcard_id").value;
		  var bus_id_pick=document.getElementById("bus_id_pick").value;
		  var bus_id_drop=document.getElementById("bus_id_drop").value;
		  var busroute_id_pick=document.getElementById("busroute_id_pick").value;
		  var busroute_id_drop=document.getElementById("busroute_id_drop").value;
		  var shift_id_pick=document.getElementById("shift_id_pick").value;
		  var shift_id_drop=document.getElementById("shift_id_drop").value;
		  var busstop_id_pick=document.getElementById("busstop_id_pick").value;
		  var busstop_id_drop=document.getElementById("busstop_id_drop").value;
              
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(edit_student_name=="") 
		  {
			alert("Please Enter Student Name "); 
			document.getElementById("edit_student_name").focus();
			return false;
		  }
		  if(edit_student_roll_no=="") 
		  {
			alert("Please Enter roll number "); 
			document.getElementById("edit_student_roll_no").focus();
			return false;
		  }
		  if(edit_student_class=="") 
		  {
			alert("Please Enter Class "); 
			document.getElementById("edit_student_class").focus();
			return false;
		  }
		  if(edit_student_section=="") 
		  {
			alert("Please Enter Section "); 
			document.getElementById("edit_student_section").focus();
			return false;
		  }
		  if(edit_student_parent_mobile_no=="") 
		  {
			alert("Please Enter Parent Mobile No "); 
			document.getElementById("edit_student_parent_mobile_no").focus();
			return false;
		  }
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(account_id) +
            "&student_id="+encodeURI( student_id ) +
            "&student_name="+encodeURI(edit_student_name)+
            "&student_address="+encodeURI(edit_student_address) +
            "&student_father_name="+encodeURI(edit_student_father_name) +
            "&student_mother_name="+encodeURI(edit_student_mother_name) +
            "&student_roll_no="+encodeURI(edit_student_roll_no) +
            "&student_class="+encodeURI(class_sec[0]) +
            "&student_section="+encodeURI(edit_student_section) +
            "&student_student_mobile_no="+encodeURI(edit_student_student_mobile_no) +
            "&student_parent_mobile_no="+encodeURI(edit_student_parent_mobile_no) +
            "&studentcard_id="+encodeURI(studentcard_id) +
            "&bus_id_pick="+encodeURI(bus_id_pick) +
            "&bus_id_drop="+encodeURI(bus_id_drop) +
            "&busroute_id_pick="+encodeURI(busroute_id_pick) +
            "&busroute_id_drop="+encodeURI(busroute_id_drop) +
            "&shift_id_pick="+encodeURI(shift_id_pick) +
            "&shift_id_drop="+encodeURI(shift_id_drop) +
            "&busstop_id_pick="+encodeURI(busstop_id_pick) +
            "&busstop_id_drop="+encodeURI(busstop_id_drop);    
    }
    else if(action_type=="parent_edit")                                               
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
		  /*var edit_student_father_name=document.getElementById("edit_student_father_name").value;
		  var edit_student_mother_name=document.getElementById("edit_student_mother_name").value;
		  var edit_student_roll_no=document.getElementById("edit_student_roll_no").value;
		  var edit_student_class=document.getElementById("edit_student_class").value;
		  var edit_student_section=document.getElementById("edit_student_section").value;*/
		  var edit_student_student_mobile_no=document.getElementById("edit_student_student_mobile_no").value;
		  var edit_student_parent_mobile_no=document.getElementById("edit_student_parent_mobile_no").value;
		  /*var studentcard_id=document.getElementById("studentcard_id").value;
		  var bus_id_pick=document.getElementById("bus_id_pick").value;
		  var bus_id_drop=document.getElementById("bus_id_drop").value;
		  var busroute_id_pick=document.getElementById("busroute_id_pick").value;
		  var busroute_id_drop=document.getElementById("busroute_id_drop").value;
		  var shift_id_pick=document.getElementById("shift_id_pick").value;
		  var shift_id_drop=document.getElementById("shift_id_drop").value;
		  var busstop_id_pick=document.getElementById("busstop_id_pick").value;
		  var busstop_id_drop=document.getElementById("busstop_id_drop").value;
       */       
        
		 // var route_coord=document.getElementById("route_coord").value;
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
    else if(action_type=="delete")
    {
      var student_id=document.getElementById("student_id").value;
      if(student_id=="select")
      {
      alert("Please Select Student");
      document.getElementById("student_id").focus();
      return false;
      }
      var txt="Are You Sure You Want To Delete Student ?";
      if(!confirm(txt))
      {
      return false;
      }
      var poststr = "action_type="+encodeURI(action_type ) +
      "&student_id="+encodeURI( student_id );
    }
	/*
	else if(action_type=="assign")
		{
			var result=accounts_for_device();
			poststr="action_type=" + encodeURI( action_type )+
					"&account_string1="+result+
					"&student_ids1="+document.getElementById("common_id").value;
			var file_name="src/php/action_manage_student.php";						
		}
	*/
		else if(action_type=="deassign")
		{
				var selected_values = get_selected_values(obj,"deassign");              
				var poststr = "vehicle_ids1=" +selected_values+
								"&action_type=" +"deassign"                  
				var file_name="src/php/action_manage_vehicle.php";		
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
    makePOSTRequest('src/php/action_manage_student.php', poststr);
 } 
 
 function action_manage_studentcard(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_studentcard_name=document.getElementById("add_studentcard_name").value;
              
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_studentcard_name=="") 
		  {
			alert("Please Enter Student Card Number"); 
			document.getElementById("add_studentcard_name").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&studentcard_name="+encodeURI(add_studentcard_name); 
		}
    }
    else if(action_type=="edit")                                               
    {      
      var studentcard_id=document.getElementById("studentcard_id").value;
      if(studentcard_id=="select")
      {
        alert("Please Select Card"); 
        document.getElementById("studentcard_id").focus();
        return false;
      }       
      var edit_studentcard_number=document.getElementById("edit_studentcard_number").value; 
      
      if(edit_studentcard_number=="") 
      {
        alert("Please Enter Card Number"); 
        document.getElementById("edit_studentcard_number").focus();
        return false;
      }
      
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                    "&studentcard_id="+encodeURI( studentcard_id ) +
                    "&edit_studentcard_number="+encodeURI(edit_studentcard_number);    
    }
    else if(action_type=="delete")
    {
     var studentcard_id=document.getElementById("studentcard_id").value;
      if(studentcard_id=="select")
      {
        alert("Please Select Card"); 
        document.getElementById("studentcard_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete Card";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&studentcard_id=" + encodeURI(studentcard_id);  
    }
	else if(action_type=="assign")
	{
	/*	var form_obj=document.manage1.elements['vehicle_id[]'];
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
		}*/
    var studentcard_id=document.getElementById("studentcard_id").value;
    var student_id=document.getElementById("student_id").value;      
    var school_id=document.getElementById("account_id_hidden").value;
    
    if(studentcard_id=="select") 
     {
        alert("Please Select Studentcard ");        
        document.getElementById("studentcard_id").focus();
        return false;
     }
     if(student_id=="select") 
     {
        alert("Please Select Student ");        
        document.getElementById("student_id").focus();
        return false;
     }
        
			var poststr="action_type="+encodeURI(action_type ) + 
					"&studentcard_id="+studentcard_id + 
          "&student_id="+student_id +
          "&school_id="+school_id;				
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
    makePOSTRequest('src/php/action_manage_studentcard.php', poststr);
 } 
  function action_manage_busdriver(action_type)
 {
  //alert("busdriver");
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_driver_name=document.getElementById("add_driver_name").value;
      var add_dlnumber=document.getElementById("add_dlnumber").value;        
      var add_dob=document.getElementById("add_dob").value;  
      var add_address=document.getElementById("add_address").value;
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_driver_name=="") 
		  {
			alert("Please Enter Bus Driver Name"); 
			document.getElementById("add_driver_name").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&add_driver_name="+encodeURI(add_driver_name)+ 
						"&add_dlnumber="+encodeURI(add_dlnumber)+
						"&add_dob="+encodeURI(add_dob) +
					  "&add_address="+encodeURI(add_address) ;
		}
    }
    else if(action_type=="edit")                                               
    {      
      var drivername_id=document.getElementById("drivername_id").value;
      //alert(studentclass_id);
      if(drivername_id=="select")
      {
        alert("Please Select Bus Driver"); 
        document.getElementById("drivername_id").focus();
        return false;
      }       
      var edit_driver_name=document.getElementById("edit_driver_name").value; 
      //alert(studentclass_name);
      if(edit_driver_name=="") 
      {
        alert("Please Enter Driver Name"); 
        document.getElementById("edit_driver_name").focus();
        return false;
      }
      var edit_dlnumber=document.getElementById("edit_dlnumber").value;        
      var edit_dob=document.getElementById("edit_dob").value;  
      var edit_address=document.getElementById("edit_address").value;
		
      var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                    "&drivername_id="+encodeURI( drivername_id ) +
                    "&edit_driver_name="+encodeURI(edit_driver_name)+
                    "&edit_dlnumber="+encodeURI(edit_dlnumber)+
                    "&edit_dob="+encodeURI(edit_dob)+
                    "&edit_address="+encodeURI(edit_address);  
                      
    }  
    else if(action_type=="delete")
    {
     var drivername_id=document.getElementById("drivername_id").value;
      //alert(studentclass_id);
      if(drivername_id=="select")
      {
        alert("Please Select Bus Driver"); 
        document.getElementById("drivername_id").focus();
        return false;
      }  
      var txt="Are You Sure You Want To Delete Card";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&drivername_id=" + encodeURI(drivername_id);  
    }

    makePOSTRequest('src/php/action_manage_busdriver.php', poststr);
 }
  function action_manage_bus(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.elements['manage_id[]'];
		var result=checkbox_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_busstop_name=document.getElementById("add_busstop_name").value;		  
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_busstop_name=="") 
		  {
			alert("Please Enter Busstop Name"); 
			document.getElementById("add_busstop_name").focus();
			return false;
		  }
		  		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&busstop_name="+encodeURI(add_busstop_name); 
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
						
      var shift_id=document.getElementById("shift_id").value;
      var busroute_id=document.getElementById("busroute_id").value;
      var bus_id=document.getElementById("bus_id").value;
      var school_id=document.getElementById("account_id_hidden").value;
      var driver_id=document.getElementById("driver_id").value;
      if(shift_id=="select") 
       {
          alert("Please Select Shift ");        
          document.getElementById("shift_id").focus();
          return false;
       }
       if(busroute_id=="select") 
       {
          alert("Please Select Busroute ");        
          document.getElementById("busroute_id").focus();
          return false;
       }
       if(bus_id=="select") 
       {
          alert("Please Select Bus ");        
          document.getElementById("bus_id").focus();
          return false;
       }
      
  			var poststr="action_type="+encodeURI(action_type ) + 
  					"&shift_id="+shift_id + 
            "&busroute_id="+busroute_id +
            "&school_id="+school_id + 
                      "&bus_id=" +bus_id +
                      "&driver_id="+driver_id;						
	}
	else if(action_type=="deassign")
	{
		/*var form_obj=document.manage1.elements['vehicle_id[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) + 
					"&vehicle_ids="+checkbox_result;							
		}	*/
		  var shift_id=document.getElementById("shift_id").value;      
      var bus_id=document.getElementById("bus_id").value;
      var school_id=document.getElementById("account_id_hidden").value;
              
       if(bus_id=="select") 
       {
          alert("Please Select Bus ");        
          document.getElementById("bus_id").focus();
          return false;
       }
        if(shift_id=="select") 
       {
          alert("Please Select Shift ");        
          document.getElementById("shift_id").focus();
          return false;
       }
  			var poststr="action_type="+encodeURI(action_type ) + 
  					"&shift_id="+shift_id + 
            "&school_id="+school_id + 
            "&bus_id=" +bus_id;				
    		
	}
    makePOSTRequest('src/php/action_manage_bus.php', poststr);
 }
 
 ////////////////SCHOOL ESCALATION//////////
function action_manage_escalation_school(action_type)
  {  
    alert("action_type="+action_type);  
    var result=document.getElementById("selected_account_id").value; 
    
   // alert(result);
    if(action_type=="add")  
    {
    
        var student_id=document.getElementById("student_id").value;   
        var studentcard_id=document.getElementById("studentcard_id").value;       
        var student_father_name=document.getElementById("student_father_name").value;
        var student_parent_mobile_no=document.getElementById("student_parent_mobile_no").value;
        var person_email=document.getElementById("person_email").value; 
          
        if(studentcard_id=="") 
        {
          alert("Student RF Card ID is must for Escalation..Please Modify Student Profile through Manage"); 
          document.getElementById("studentcard").focus();
          return false;
        }
        if(student_father_name=="") 
        {
          alert("Person Name is required ...Please Modify student Profile first"); 
          document.getElementById("student_father_name").focus();
          return false;
        }
        if(student_parent_mobile_no=="") 
        {
          alert("Person Mobile Number is required for Sending Alert..Please Modify First"); 
          document.getElementById("student_parent_mobile_no").focus();
          return false;
        }        
          alert("result"+encodeURI(result));
        var poststr = "action_type="+encodeURI(action_type ) + 
        "&local_account_ids="+encodeURI(result) +
        "&student_id="+encodeURI(student_id) +
        "&studentcard_id="+encodeURI(studentcard_id) +
        "&student_father_name="+encodeURI(student_father_name) +
        "&student_parent_mobile_no="+encodeURI(student_parent_mobile_no) +
        "&person_email="+encodeURI(person_email); 
         alert("inside"+poststr);
    }   
  else if(action_type=="assign")
	{
	  
		var form_obj1 = document.manage1.elements['escalation_id'];
    var radio_result1 = radio_selection(form_obj1);	
    //alert(radio_result1);
    var form_obj2 = document.manage1.elements['alert_id[]'];
    var form_obj3 = document.manage1.elements['duration[]'];
    //alert(form_obj3[0]) ;
    //var checkbox_result=checkbox_selection(form_obj2); /////////validate and get vehicleids
    
		var form_obj4 = document.manage1.elements['vehicle_id'];
    var radio_result4 = radio_selection(form_obj4);
    
    var form_obj5 = document.manage1.elements['sms_status[]'];
    var form_obj6 = document.manage1.elements['mail_status[]'];
    
    //var duration_tmp = document.manage1.duration.value;
        
  	var cnt=0;
  	var duration;
  	var checkbox_result="";
  	var duration_result="";
  	var sms_result="";
  	var mail_result="";
  	var sms;
  	var mail;
  	var flag=0;
  	
    if(form_obj2.length!=undefined)
  	{
  		for (var i=0;i<form_obj2.length;i++)
  		{
  			if(form_obj2[i].checked==true)
  			{				
          //alert("f1="+form_obj5[i].checked+" ,f2="+form_obj6[i].checked);
           //alert("dur1:"+form_obj3[i].value);
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
                         
           if(form_obj3.length==undefined)
           {
            //alert("hello");
             if(form_obj3.value == "" && form_obj5[0].checked == true)
              {
                alert("Please fill the km field");
                return false; 
              }
              
              if(form_obj3.value >=0 && form_obj3.value <=30)
              {
                duration = form_obj3.value; 
              }        			
              else
              {
                alert("Please enter valid km between 0.2Km to 10 Km");
                return false;
              }
           }
          
           if(form_obj3.length!=undefined)
           {
               if(form_obj3[i].value == "")
                {
                  alert("Please fill the km field");
                  return false; 
                }
                
                if(form_obj3[i].value >=0 && form_obj3[i].value <=30)
                {
                  duration = form_obj3[i].value; 
                }        			
                else
                {
                  alert("Please enter valid km between 0.2Km to 30 Km");
                  return false;
                }
           }
           
          
            				
          if(cnt==0)
  				{
  					checkbox_result= checkbox_result + form_obj2[i].value;
  					duration_result = duration_result + duration;
  					sms_result = sms_result + ""+sms;
  					mail_result = mail_result + ""+mail;
  					//alert("IF="+sms_result+ " : "+mail_result);
  					cnt=1;
  				}
  				else
  				{
  					checkbox_result=checkbox_result +","+ form_obj2[i].value;
  					duration_result = duration_result + ","+duration;
  					
  					sms_result = sms_result + ","+ sms;
  					mail_result = mail_result + ","+ mail;
  					//alert("ELSE="+sms_result+ " : "+mail_result);
  				}		  				
  				flag=1;
  			}
 
  		}
  	}
  	
    //alert(checkbox_result);
    //alert(radio_result1);
    //alert(radio_result4);
      		
    if(checkbox_result!=false && radio_result1!=false && radio_result4!=false)
		{
			//var form_obj1=document.manage1.calibration_id;
			//var radio_result=radio_selection(form_obj1);  //////////validate and get geofence
			//if(radio_result!=false)
			//{
			var poststr="action_type="+encodeURI(action_type) +
			            "&local_account_ids="+encodeURI(result) +
                  //"&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					        "&escalation_id="+radio_result1 +
                  "&alert_ids="+checkbox_result + 
                  "&vehicle_id=" +radio_result4 +
                  "&duration=" +duration_result +
                  "&sms_status=" +sms_result +
                  "&mail_status=" +mail_result;
			//}	
      //alert(poststr);				
		}			
	}
      //////=========end of assign=====//////////////
  else if(action_type=="deassign")
	{
	 //alert("go for deassign") ;
		var form_obj=document.manage1.elements['escalation_serial_number[]'];
		var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids		
		
		if(checkbox_result!=false)
		{		
			var poststr="action_type="+encodeURI(action_type ) +
          "&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
					"&escalation_serial_number="+checkbox_result;							
		}			
	}  
	//-------end of deassign-----------//
	//-------edit-------------//
	else if(action_type=="edit")               
    {
    
		var escalation_id1=document.getElementById("escalationschool_id").value; 
			    
		var person_name=document.getElementById("student_father_name").value;
    var person_mob=document.getElementById("student_parent_mobile_no").value;
    var person_email=document.getElementById("person_email").value;    
		//alert(person_email);
		if(person_name=="") 
		{
			alert("Please Enter person_name"); 
			document.getElementById("student_father_name").focus();
			return false;
		}
		if(person_mob=="") 
		{
			alert("Please Enter Person Mobile"); 
			document.getElementById("student_parent_mobile_no").focus();
			return false;
		}		

		var poststr ="action_type="+encodeURI(action_type) + 
		//"&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+
		"&escalationschool_id="+encodeURI(document.getElementById("escalationschool_id").value) +
		"&person_name="+encodeURI(document.getElementById("student_father_name").value ) +
		"&person_mob="+encodeURI(document.getElementById("student_parent_mobile_no").value ) +
		"&person_email="+encodeURI(document.getElementById("person_email").value ); 
    }
    //alert(poststr)  ;
	//------end of edit-------//
	//---------delete---------------//
	else if(action_type=="delete")
    {
     var escalation_id1=document.getElementById("escalationschool_id").value; 
      
      var txt="Are You Sure You Want To Delete this Escalation!";
      if(!confirm(txt))
      {
       return false; 
      }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    //"&local_account_ids="+encodeURI(document.getElementById("account_id_hidden").value)+ 
                    "&escalationschool_id=" + encodeURI(document.getElementById("escalationschool_id").value);
  }
	//-------end of delete----------//
    alert("welcome");
    makePOSTRequest('src/php/action_manage_escalation_school.php', poststr);
  }
///////////////////////////////////////////
 
 function action_manage_busstop(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.manage_id;
		var result=radio_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_busstop_name=document.getElementById("add_busstop_name").value;
      var add_busstop_latitude=document.getElementById("add_busstop_latitude").value;
      var add_busstop_longitude=document.getElementById("add_busstop_longitude").value;		  
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_busstop_name=="") 
		  {
			alert("Please Enter Busstop Name"); 
			document.getElementById("add_busstop_name").focus();
			return false;
		  }
		  		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&latitude="+encodeURI(add_busstop_latitude) +
						"&longitude="+encodeURI(add_busstop_longitude) +
						"&busstop_name="+encodeURI(add_busstop_name); 
		}
    }
    else if(action_type=="edit")                                               
    {      
      var busstop_id=document.getElementById("busstop_id").value;
      if(busstop_id=="select")
      {
        alert("Please Select Busstop"); 
        document.getElementById("busstop_id").focus();
        return false;
      }       
      var edit_busstop_name=document.getElementById("edit_busstop_name").value; 
      var edit_busstop_latitude=document.getElementById("edit_busstop_latitude").value;
      var edit_busstop_longitude=document.getElementById("edit_busstop_longitude").value;
      if(edit_busstop_name=="") 
      {
        alert("Please Enter Route Name"); 
        document.getElementById("edit_busstop_name").focus();
        return false;
      }
      else if(edit_busstop_latitude=="") 
      {
        alert("Please Enter Latitude");
        document.getElementById("edit_busstop_latitude").focus();
        return false;
      }
      else if(edit_busstop_longitude=="") 
      {
        alert("Please Enter Longitude");
        document.getElementById("edit_busstop_longitude").focus();
        return false;
      }
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                    "&busstop_id="+encodeURI( busstop_id ) +
                    "&edit_busstop_name="+encodeURI(edit_busstop_name) +
                    "&edit_busstop_latitude="+encodeURI(edit_busstop_latitude) +
                    "&edit_busstop_longitude="+encodeURI(edit_busstop_longitude);    
    }
    else if(action_type=="delete")
    {
     var busstop_id=document.getElementById("busstop_id").value;
     if(busstop_id=="select") 
     {
        alert("Please Select Busstop");        
        document.getElementById("busstop_id").focus();
        return false;
     }
      var txt="Are You Sure You Want To Delete Busstop";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&busstop_id=" + encodeURI(busstop_id);  
    }
	else if(action_type=="assign")
	{
						
      var result_busstopserial=document.getElementById("busstops").value;
       
      if(result_busstopserial!="")
      {
        var form_obj1=document.manage1.busroute_id;
  			var radio_result=radio_selection(form_obj1);  //////////validate and get bus route id
  			if(radio_result!=false)
  			{
  			var poststr="action_type="+encodeURI(action_type ) + 
  					"&busstop_serials="+result_busstopserial +  
                      "&busroute_id=" +radio_result;
  			}
      }
      else{
          alert("Please Add Bus Stops"); 
          document.getElementById("busstops").focus();
          return false;
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
	else if(action_type=="setarrival")
	{
		//var form_obj=document.manage1.elements['vehicle_id[]'];
		//var checkbox_result=checkbox_selection(form_obj); /////////validate and get vehicleids
		var bus_id=document.getElementById("bus_id").value;
		var shift_id=document.getElementById("shift_id").value;
		var hrs,mins,busstopids,temp,tempid;
		
    //var result_busstopserial=document.getElementById("busstops").value;
    var rowcnt=document.getElementById("rowcnt").value;
    
    for(var i=0;i<=rowcnt;i++)
    {
      temp=document.getElementById("busstop"+i).value;
      //alert(temp);
      tempid=temp.split(":");
      if(i==0){
          hrs=document.getElementById("hour"+i).value;
          mins=document.getElementById("min"+i).value;
          busstopids=tempid[1];
      }
      else{
           hrs=hrs+"#"+document.getElementById("hour"+i).value;
           mins=mins+"#"+document.getElementById("min"+i).value;
           busstopids=busstopids+"#"+tempid[1];
      }
      
    }
    
    //alert(busstopids);    
    if(rowcnt>0)
		{		
			var poststr="action_type="+encodeURI(action_type ) + 
					"&bus_id="+bus_id +
					"&shift_id="+shift_id +
					"&hrs="+hrs +
					"&mins="+mins +
					"&busstopids="+busstopids +					
          "&rowcnt="+rowcnt;							
		}			
	} 
    makePOSTRequest('src/php/action_manage_busstop.php', poststr);
 }

// function for string of bus stop serials
 function busstop_serial(checkbox_result){
    var strar = checkbox_result.split(",");
    var result_String="";
    if(strar.length>1)
					 {
					 for(var i=0;i<strar.length;i++)
								 {
								 var serail=document.getElementById(strar[i]).value;
								  if(serail=="select") 
                   {
                      alert("Please Select Serial For All selected Bus Stops");        
                      document.getElementById(strar[i]).focus();
                      return false;
                   }
                  if(i==0){
								      result_String=result_String+serail;
                  }
                  else{
                  result_String=result_String+","+serail;
                  }
                    
								}
             return result_String; 					 
					 }   
 }


 
  function action_manage_busroute(action_type)
 {
    if(action_type=="add")  
    {
		var obj=document.manage1.manage_id;
		var result=radio_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_busroute_name=document.getElementById("add_busroute_name").value;
              
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_busroute_name=="") 
		  {
			alert("Please Enter Busroute Name"); 
			document.getElementById("add_busroute_name").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&busroute_name="+encodeURI(add_busroute_name); 
		}
    }
    else if(action_type=="edit")                                               
    {      
      var busroute_id=document.getElementById("busroute_id").value;
      if(busroute_id=="select")
      {
        alert("Please Select Busroute "); 
        document.getElementById("busroute_id").focus();
        return false;
      }       
      var edit_busroute_name=document.getElementById("edit_busroute_name").value;
       
		 // var route_coord=document.getElementById("route_coord").value;
		  if(edit_busroute_name=="") 
		  {
			alert("Please Enter Busroute Name"); 
			document.getElementById("edit_busroute_name").focus();
			return false;
		  }
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
                    "&busroute_id="+encodeURI( busroute_id ) +
                    "&edit_busroute_name="+encodeURI(edit_busroute_name);    
    }
    else if(action_type=="delete")
    {
     var busroute_id=document.getElementById("busroute_id").value;
      if(busroute_id=="select")
      {
        alert("Please Select Busroute "); 
        document.getElementById("busroute_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete Busroute";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&busroute_id=" + encodeURI(busroute_id);  
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
    makePOSTRequest('src/php/action_manage_busroute.php', poststr);
 } 
  
 function action_manage_shift(action_type)
 {
    if(action_type=="add")  
    {
		//var obj=document.manage1.elements['manage_id[]'];
		//var result=checkbox_selection(obj);
		var obj=document.manage1.manage_id;
		var result=radio_selection(obj);
		//alert("result"+result);
		if(result!=false)
		{
		  var add_shift_name=document.getElementById("add_shift_name").value;
      var add_shift_start_hr=document.getElementById("add_shift_start_hr").value;
      var add_shift_start_min=document.getElementById("add_shift_start_min").value;
      var add_shift_stop_hr=document.getElementById("add_shift_stop_hr").value;
      var add_shift_stop_min=document.getElementById("add_shift_stop_min").value;
         
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_shift_name=="") 
		  {
			alert("Please Enter Shift Name"); 
			document.getElementById("add_shift_name").focus();
			return false;
		  }
		  else if(add_shift_start_hr=="-1")
      {
      alert("Please Select Shift Start Hour"); 
			document.getElementById("add_shift_start_hr").focus();
			return false;
      }
      else if(add_shift_start_min=="-1")
      {
      alert("Please Select Shift Start Min"); 
			document.getElementById("add_shift_start_min").focus();
			return false;
      }
      else if(add_shift_stop_hr=="-1")
      {
      alert("Please Select Shift Stop Hour"); 
			document.getElementById("add_shift_stop_hr").focus();
			return false;
      }
      else if(add_shift_stop_min=="-1")
      {
      alert("Please Select Shift Stop Min"); 
			document.getElementById("add_shift_stop_min").focus();
			return false;
      }	
      
      var add_shift_starttime = add_shift_start_hr + add_shift_start_min +"00";
      var add_shift_stoptime = add_shift_stop_hr + add_shift_stop_min +"00";
        		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(result) +
						"&shift_name="+encodeURI(add_shift_name) +
            "&shift_starttime="+encodeURI(add_shift_starttime) +
            "&shift_stoptime="+encodeURI(add_shift_stoptime); 
		}
    }
    else if(action_type=="edit")                                               
    {      
     var shift_id=document.getElementById("shift_id").value;
      if(shift_id=="select")
      {
        alert("Please Select Shift "); 
        document.getElementById("shift_id").focus();
        return false;
      }       
      var edit_shift_name=document.getElementById("edit_shift_name").value;
      var edit_shift_start_hr=document.getElementById("edit_shift_start_hr").value;
      var edit_shift_start_min=document.getElementById("edit_shift_start_min").value;
      var edit_shift_stop_hr=document.getElementById("edit_shift_stop_hr").value;
      var edit_shift_stop_min=document.getElementById("edit_shift_stop_min").value;
         
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(edit_shift_name=="") 
		  {
			alert("Please Enter Shift Name"); 
			document.getElementById("edit_shift_name").focus();
			return false;
		  }
		  else if(edit_shift_start_hr=="-1")
      {
      alert("Please Select Shift Start Hour"); 
			document.getElementById("edit_shift_start_hr").focus();
			return false;
      }
      else if(edit_shift_start_min=="-1")
      {
      alert("Please Select Shift Start Min"); 
			document.getElementById("edit_shift_start_min").focus();
			return false;
      }
      else if(edit_shift_stop_hr=="-1")
      {
      alert("Please Select Shift Stop Hour"); 
			document.getElementById("edit_shift_stop_hr").focus();
			return false;
      }
      else if(edit_shift_stop_min=="-1")
      {
      alert("Please Select Shift Stop Min"); 
			document.getElementById("edit_shift_stop_min").focus();
			return false;
      }	
      
      var edit_shift_starttime = edit_shift_start_hr + edit_shift_start_min +"00";
      var edit_shift_stoptime = edit_shift_stop_hr + edit_shift_stop_min +"00";
        		  
		  var poststr = "action_type="+encodeURI(action_type ) +
            "&shift_id="+encodeURI( shift_id ) + 
						"&local_account_ids="+encodeURI(result) +
						"&shift_name="+encodeURI(edit_shift_name) +
            "&shift_starttime="+encodeURI(edit_shift_starttime) +
            "&shift_stoptime="+encodeURI(edit_shift_stoptime);     
    }
    else if(action_type=="delete")
    {
     var shift_id=document.getElementById("shift_id").value;
      if(shift_id=="select")
      {
        alert("Please Select Shift "); 
        document.getElementById("shift_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete Shift";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&shift_id=" + encodeURI(shift_id);  
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
    makePOSTRequest('src/php/action_manage_shift.php', poststr);
 }
       

 function action_setting_alert(action_type)
 {
    if(action_type=="configure")  
    {
		var obj=document.manage1.elements['manage_id[]']; 		
		var result=checkbox_selection(obj);
		var alerts=document.manage1.elements['alert[]'];
		var selected_alerts=checkbox_selection(alerts);
		
		if(result!=false && selected_alerts!=false)
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
						"&local_account_ids="+encodeURI(result) +
						"&selected_alerts="+encodeURI(selected_alerts) +
            "&time_before_min="+encodeURI(time_before_min); 
		}
    }

    makePOSTRequest('src/php/action_setting_alert.php', poststr);
 }


 function action_manage_person(action_type)
 {
    if(action_type=="add")  
    {
		//var obj=document.manage1.manage_id;
		//var result=radio_selection(obj);
		//alert("result"+result);
		//alert("hiiii");
		//if(result!=false)
		//{
		  var account_id=document.getElementById("account_id_hidden").value;
      var add_person_name=document.getElementById("add_person_name").value;
		  var add_person_address=document.getElementById("add_person_address").value;
		  var add_mobile_no=document.getElementById("add_mobile_no").value;
		  var add_imei_no=document.getElementById("add_imei_no").value;
		  		                
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(add_person_name=="") 
		  {
			alert("Please Enter Person Name "); 
			document.getElementById("add_person_name").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(account_id) +
						"&person_name="+encodeURI(add_person_name)+
            "&person_address="+encodeURI(add_person_address) +
            "&person_mobile_no="+encodeURI(add_mobile_no) +
            "&person_imei_no="+encodeURI(add_imei_no); 
            
            //alert(poststr);
		//}
    } 
    else if(action_type=="edit")                                               
    {      
      var person_id=document.getElementById("person_id").value;
      if(person_id=="select")
      {
        alert("Please Select Person"); 
        document.getElementById("person_id").focus();
        return false;
      }       
      
      var account_id=document.getElementById("account_id_hidden").value;
      var edit_person_name=document.getElementById("edit_person_name").value;
		  var edit_person_address=document.getElementById("edit_person_address").value;
		  var edit_mobile_no=document.getElementById("edit_mobile_no").value;
		  var edit_imei_no=document.getElementById("edit_imei_no").value;
		                
        
		 // var route_coord=document.getElementById("route_coord").value;
		  if(edit_person_name=="") 
		  {
			alert("Please Enter Person Name "); 
			document.getElementById("edit_person_name").focus();
			return false;
		  }
       var poststr ="action_type="+encodeURI(action_type ) + 
					"&local_account_ids="+encodeURI(result) +
            "&person_id="+encodeURI( person_id ) +
            "&person_name="+encodeURI(edit_person_name)+
            "&person_address="+encodeURI(edit_person_address) +
            "&person_mobile_no="+encodeURI(edit_mobile_no) +
            "&person_imei_no="+encodeURI(edit_imei_no);   
    }

    else if(action_type=="delete")
    {
     var person_id=document.getElementById("person_id").value;
      if(person_id=="select")
      {
        alert("Please Select Person"); 
        document.getElementById("person_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete Record";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) + 
                    "&person_id=" + encodeURI(person_id);  
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
	

    makePOSTRequest('src/php/action_manage_person.php', poststr);
 } 


function action_manage_visit(action_type)
{
    if(action_type=="add")  
    {		
		  var account_id=document.getElementById("account_id_hidden").value;
      var date1=document.getElementById("date1").value;
      var person_id=document.getElementById("person_id").value;
      var route_coord=document.getElementById("route_coord").value;
		 
		 if(date1=="") 
		  {
			alert("Please Enter Date");
			document.getElementById("date1").focus();
			return false;
		  }  
		 else if(person_id=="select")
      {
        alert("Please Select Person "); 
        document.getElementById("person_id").focus();
        return false;
      }
		  else if(route_coord=="") 
		  {
			alert("Please Enter Locations");
			document.getElementById("route_coord").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(account_id) +
						"&date1="+encodeURI(date1)+
            "&person_id="+encodeURI(person_id) +
            "&route_coord="+encodeURI(route_coord); 
            
            //alert(poststr);
		//}
    }
    
  if(action_type=="getlocations")  
    {
		
		  var account_id=document.getElementById("account_id_hidden").value;
      var date1=document.getElementById("date1").value;
      var person_id=document.getElementById("person_id").value;
     
		 if(date1=="") 
		  {
			alert("Please Enter Date");
			document.getElementById("date1").focus();
			return false;
		  }  
		 else if(person_id=="select")
      {
        alert("Please Select Person "); 
        document.getElementById("person_id").focus();
        return false;
      }
		         		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(account_id) +
						"&date1="+encodeURI(date1)+
            "&person_id="+encodeURI(person_id); 
            
    } 
    else if(action_type=="edit")                                               
    {      
           
      var account_id=document.getElementById("account_id_hidden").value;
      var date1=document.getElementById("date1").value;
      var person_id=document.getElementById("person_id").value;
      var route_coord=document.getElementById("route_coord").value;
		 
		 if(date1=="") 
		  {
			alert("Please Enter Date");
			document.getElementById("date1").focus();
			return false;
		  }  
		 else if(person_id=="select")
      {
        alert("Please Select Person "); 
        document.getElementById("person_id").focus();
        return false;
      }
		  else if(route_coord=="") 
		  {
			alert("Please Enter Locations");
			document.getElementById("route_coord").focus();
			return false;
		  }
		          		  
		  var poststr = "action_type="+encodeURI(action_type ) + 
						"&local_account_ids="+encodeURI(account_id) +
						"&date1="+encodeURI(date1)+
            "&person_id="+encodeURI(person_id) +
            "&route_coord="+encodeURI(route_coord);   
    }

    else if(action_type=="delete")
    {
     var date1=document.getElementById("date1").value;
      var person_id=document.getElementById("person_id").value;
     // var route_coord=document.getElementById("route_coord").value;
		 
		 if(date1=="") 
		  {
			alert("Please Enter Date");
			document.getElementById("date1").focus();
			return false;
		  }  
		 else if(person_id=="select")
      {
        alert("Please Select Person "); 
        document.getElementById("person_id").focus();
        return false;
      }
      var txt="Are You Sure You Want To Delete Record";
     if(!confirm(txt))
     {
       return false; 
     }
      var poststr = "action_type="+encodeURI(action_type ) +
                    "&date1="+encodeURI(date1)+ 
                    "&person_id=" + encodeURI(person_id);  
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
	

    makePOSTRequest('src/php/action_manage_visit.php', poststr);
 } 


//*********************** Code to add and remove row ******************************
	
  var initial_count = new Array(); 
  var rows_limit = 0; // Set to 0 to disable limitation  
  
  function addRow(table_id){
    
    var form_obj1=document.manage1.busroute_id;
  	var radio_result=radio_selection(form_obj1);  //////////validate and get bus route id 
  	if(radio_result==false){
  	 return false;
    }
    var busstop_id=document.getElementById("busstop_id").value;
    if(busstop_id=="select")
      {
        alert("Please Select Busstop"); 
        document.getElementById("busstop_id").focus();
        return false;
      }
      
    
    var tbl = document.getElementById(table_id); 
	  // counting rows in table 
	  var rows_count = tbl.rows.length; 
	  if (initial_count[table_id] == undefined) 
	  { 
	    // if it is first adding in this table setting initial rows count 
	    initial_count[table_id] = rows_count; 
	  } 
	  // determining real count of added fields 
	  var tFielsNum =  rows_count - initial_count[table_id]; 
	  if (rows_limit!=0 && tFielsNum >= rows_limit) return false; 
	  var name="";
	  
	      
    name="busstop"+rows_count;
    var temp_busstop_id=rows_count+":"+busstop_id
    var hidden_busstop='<input type="hidden" name="'+name+'" id="'+name+'" value="'+temp_busstop_id+'" style="font-size: xx-small;">';//for text box 
	  var remove= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="x" onclick="removeRow(\''+table_id+'\',this.parentNode.parentNode,\''+rows_count+'\')" style="font-size: xx-small;"/>'; 
	  
	  var busstoplist=document.getElementById("busstops").value;
	  //var mystr = "these dogs are dirty"
    //var found=mystr.indexOf("dogs")
    var found =  busstoplist.indexOf(busstop_id);
    //alert("Found : "+found);
	  if(found >0)
      {
        alert("Busstop Already Added to Selected Route"); 
        document.getElementById("busstop_id").focus();
        return false;
      }
	  
	  
	  var busstop_after= document.getElementById("busstop_after").value;
	  //alert("busstop_after : "+busstop_after);
    if(busstop_after=="last"){
        if(busstoplist == ""){
            document.getElementById("busstops").value = temp_busstop_id;
            //alert("temp_busstop_id 1 : "+temp_busstop_id);
          }
          else{
            document.getElementById("busstops").value = busstoplist+"#"+temp_busstop_id;
            //alert("temp_busstop_id 2 : "+temp_busstop_id);
          }
     }
     else if(busstop_after=="top"){
        document.getElementById("busstops").value= temp_busstop_id +"#"+ busstoplist;
     }
     else{
          var newbusstoplist="";
          var tempstr = busstoplist.split("#");
          if(tempstr.length>1)
          {             
            for(var i=0;i<tempstr.length;i++)
            {
              if(i==0){
                  newbusstoplist= tempstr[i];
              }
              else{
                   newbusstoplist= newbusstoplist +"#"+tempstr[i];
              }
               if(busstop_after==i){
                   newbusstoplist= newbusstoplist +"#"+temp_busstop_id;
               }
              
      	     }
      	  }
      	  else{
      	      
          }
          
          document.getElementById("busstops").value = newbusstoplist;
     }
    
    
    var result1=document.getElementById("busstops").value;
    addrowAjax(result1);
    //load_busstops();
    
	   document.getElementById("rowcnt").value=rows_count;
	  /* var tempstr = busstop_id.split(":");
	   try { 
	    	var newRow = tbl.insertRow(rows_count);//creation of new row 
            //var newCell = newRow.insertCell(0);//first  cell in the row 
            //newCell.innerHTML = textbox1;//insertion of the 'text' variable in first cell 
            //var newCell = newRow.insertCell(0);//second cell in the row 
            //newCell.innerHTML = select1;
            var newCell = newRow.insertCell(0);//first cell in the row 
            newCell.innerHTML = hidden_busstop;            
            var newCell = newRow.insertCell(1);//second cell in the row 
            newCell.innerHTML = "<b> &#9679; </b>"+tempstr[1];
            var newCell = newRow.insertCell(2);//third cell in the row 
            newCell.innerHTML = remove;
           
	  } catch (ex) { 
	    //if exception occurs 
	    alert(ex); 
	  } */
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
    //alert("remove_busstop = "+remove_busstop);
    //alert("busstoplist ="+busstoplist);
    //alert("new_list ="+new_list);
     load_busstops();
    try { 
      
      table.deleteRow(row.rowIndex); 
    } catch (ex) { 
      alert(ex); 
    } 
  } 
	
	function  get_assigned_busstop(action_type,busroute_id)
	{
    var account_id=document.getElementById("account_id_hidden").value;
    var poststr="action_type="+encodeURI(action_type ) + 
					"&busroute_id="+busroute_id + 
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_busstop.php', poststr);
  }
  
	function  get_busstop_for_arrival(action_type,obj)
	{
    var account_id=document.getElementById("account_id_hidden").value;
    var bus_id=document.getElementById("bus_id").value;
    var shift_id=document.getElementById("shift_id").value;
    
    var poststr="action_type="+encodeURI(action_type ) + 
					"&bus_id="+bus_id +
          "&shift_id="+shift_id + 
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_busstop.php', poststr);
  }
  
  function  set__arrival_time()
	{
    var action_type="set__arrival_time";
    var account_id=document.getElementById("account_id_hidden").value;
    var bus_id=document.getElementById("bus_id").value;
    var shift_id=document.getElementById("shift_id").value;
    
    var poststr="action_type="+encodeURI(action_type ) + 
					"&bus_id="+bus_id +
          "&shift_id="+shift_id + 
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_busstop.php', poststr);
  }

  //------ function to clear drop down values
  function  clear_all(action_type,pick_drop)
	{         
    if(pick_drop=="pick")
    {
          remOption(document.getElementById("busroute_id_pick"));
  		    addOption(document.getElementById("busroute_id_pick"),'Select','select'); 
          remOption(document.getElementById("bus_id_pick"));
  		    addOption(document.getElementById("bus_id_pick"),'Select','select');           
          document.getElementById("busstop_id_pick").focus();          
          return false;
        
    }
    else if(pick_drop=="drop")
    {
         remOption(document.getElementById("busroute_id_drop"));
  		    addOption(document.getElementById("busroute_id_drop"),'Select','select'); 
          remOption(document.getElementById("bus_id_drop"));
  		    addOption(document.getElementById("bus_id_drop"),'Select','select');          
          document.getElementById("busstop_id_drop").focus();          
          return false;
        
    }   
  }

  //-------------- Function to get bus routes ------------------------ 
  
  function  get_busroute(action_type,pick_drop)
	{
        
    if(pick_drop=="pick")
    {
      var shift_id=document.getElementById("shift_id_pick").value;
      var busstop_id=document.getElementById("busstop_id_pick").value;
      
      if(shift_id=="select")
        {
          alert("Please Select Shift Pick"); 
          document.getElementById("shift_id_pick").focus();
          return false;
        }
      if(busstop_id=="select")
        {
          remOption(document.getElementById("busroute_id_pick"));
  		    addOption(document.getElementById("busroute_id_pick"),'Select','select'); 
          remOption(document.getElementById("bus_id_pick"));
  		    addOption(document.getElementById("bus_id_pick"),'Select','select');
          alert("Please Select Busstop Pick"); 
          document.getElementById("busstop_id_pick").focus();          
          return false;
        }
    }
    else if(pick_drop=="drop")
    {
      var shift_id=document.getElementById("shift_id_drop").value;
      var busstop_id=document.getElementById("busstop_id_drop").value;
      
      if(shift_id=="select")
        {
          alert("Please Select Shift Drop"); 
          document.getElementById("shift_id_drop").focus();
          return false;
        }
      if(busstop_id=="select")
        {
          remOption(document.getElementById("busroute_id_drop"));
  		    addOption(document.getElementById("busroute_id_drop"),'Select','select'); 
          remOption(document.getElementById("bus_id_drop"));
  		    addOption(document.getElementById("bus_id_drop"),'Select','select');
          alert("Please Select Busstop Drop"); 
          document.getElementById("busstop_id_drop").focus();          
          return false;
        }
    }
    
    var group_id=document.getElementById("group_id_hidden").value;
	var selected_ac_id=document.getElementById("selected_account_id_hidden").value;
	var account_id=document.getElementById("account_id_hidden").value;
	
    var poststr="action_type="+encodeURI(action_type ) + 
					"&pick_drop="+pick_drop +
          "&shift_id="+shift_id + 
          "&busstop_id="+busstop_id + 
		  "&group_id="+group_id + 
		  "&selected_ac_id="+selected_ac_id + 
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_student.php', poststr);
  }
	
	//-------------- Function to get bus ------------------------
  function  get_bus(action_type,pick_drop)
	{
        
    if(pick_drop=="pick")
    {
      var shift_id=document.getElementById("shift_id_pick").value;
      var busstop_id=document.getElementById("busstop_id_pick").value;
      var busroute_id=document.getElementById("busroute_id_pick").value;
      
      if(shift_id=="select")
        {
          remOption(document.getElementById("busroute_id_pick"));
  		    addOption(document.getElementById("busroute_id_pick"),'Select','select'); 
          remOption(document.getElementById("bus_id_pick"));
  		    addOption(document.getElementById("bus_id_pick"),'Select','select');
          alert("Please Select Shift Pick"); 
          document.getElementById("shift_id_pick").focus();
          return false;
        }
      if(busstop_id=="select")
        {
          remOption(document.getElementById("busroute_id_pick"));
  		    addOption(document.getElementById("busroute_id_pick"),'Select','select'); 
          remOption(document.getElementById("bus_id_pick"));
  		    addOption(document.getElementById("bus_id_pick"),'Select','select');
          alert("Please Select Busstop Pick"); 
          document.getElementById("busstop_id_pick").focus();          
          return false;
        }
        if(busroute_id=="select")
        {            
          remOption(document.getElementById("bus_id_pick"));
  		    addOption(document.getElementById("bus_id_pick"),'Select','select');
          alert("Please Select Busroute Pick"); 
          document.getElementById("busroute_id_pick").focus();          
          return false;
        }
    }
    else if(pick_drop=="drop")
    {
      var shift_id=document.getElementById("shift_id_drop").value;
      var busstop_id=document.getElementById("busstop_id_drop").value;
      var busroute_id=document.getElementById("busroute_id_drop").value;
      
      if(shift_id=="select")
        {
          remOption(document.getElementById("busroute_id_drop"));
  		    addOption(document.getElementById("busroute_id_drop"),'Select','select'); 
          remOption(document.getElementById("bus_id_drop"));
  		    addOption(document.getElementById("bus_id_drop"),'Select','select');
          alert("Please Select Shift Drop"); 
          document.getElementById("shift_id_drop").focus();
          return false;
        }
      if(busstop_id=="select")
        {
          remOption(document.getElementById("busroute_id_drop"));
  		    addOption(document.getElementById("busroute_id_drop"),'Select','select'); 
          remOption(document.getElementById("bus_id_drop"));
  		    addOption(document.getElementById("bus_id_drop"),'Select','select');
          alert("Please Select Busstop Drop"); 
          document.getElementById("busstop_id_drop").focus();          
          return false;
        }
        if(busroute_id=="select")
        {            
          remOption(document.getElementById("bus_id_drop"));
  		    addOption(document.getElementById("bus_id_drop"),'Select','select');
          alert("Please Select Busroute Drop"); 
          document.getElementById("busroute_id_drop").focus();          
          return false;
        }
    }
    
    var group_id=document.getElementById("group_id_hidden").value;
	var selected_ac_id=document.getElementById("selected_account_id_hidden").value;
	var account_id=document.getElementById("account_id_hidden").value;
	
    var poststr="action_type="+encodeURI(action_type ) + 
					"&pick_drop="+pick_drop +
          "&shift_id="+shift_id + 
          "&busstop_id="+busstop_id +
          "&busroute_id="+busroute_id + 
		  "&group_id="+group_id + 
		  "&selected_ac_id="+selected_ac_id +           
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_student.php', poststr);
  }
  
  
	 //-------------- Function to get shift ------------------------
  function  get_shift(action_type,pick_drop)
	{
        
      var bus_id=document.getElementById("bus_id").value;
      var shift_id=document.getElementById("shift_id").value;
      
      if(bus_id=="select")
        {
          remOption(document.getElementById("shift_id"));
  		    addOption(document.getElementById("shift_id"),'Select','select');          
          alert("Please Select Bus"); 
          document.getElementById("bus_id").focus();
          return false;
        }
      
    
    var account_id=document.getElementById("account_id_hidden").value;
    var poststr="action_type="+encodeURI(action_type ) + 
					"&pick_drop="+pick_drop +
          "&bus_id="+bus_id + 
          "&account_id="+account_id;							
		
    makePOSTRequest('src/php/action_manage_bus.php', poststr);
  }
  //--------------------- function to load busstops ------------------------------------	
	function load_busstops()
	{
    var tmp=document.getElementById("busstops").value;
    //addrowAjax(result1);
    var tempstr = tmp.split("#");
    if(tempstr.length>1)
    {
      remOption(document.getElementById("busstop_after"));
			addOption(document.getElementById("busstop_after"),'At Last','last');
			addOption(document.getElementById("busstop_after"),'At Top','top');
      for(var i=0;i<tempstr.length;i++)
      {
        var tempstr1 = tempstr[i].split(":");
        addOption(document.getElementById("busstop_after"),tempstr1[2],i);
	     }
	  }
	  else{
	      remOption(document.getElementById("busstop_after"));
        addOption(document.getElementById("busstop_after"),'At Last','last');
			  addOption(document.getElementById("busstop_after"),'At Top','top');
    }
  }
	
//************************** End *********************************


 function show_shift_record(obj)
 {
    var shift_id=document.getElementById("shift_id").value;
    //alert(shift_id);
    if(shift_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "shift_id=" + encodeURI( document.getElementById("shift_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 }

  function show_busroute_record(obj)
 {
    var busroute_id=document.getElementById("busroute_id").value;
    //alert(busroute_id);
    if(busroute_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "busroute_id=" + encodeURI( document.getElementById("busroute_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 }
 
function show_busstop_record(obj)
 {
    var busstop_id=document.getElementById("busstop_id").value;
    //alert(busroute_id);
    if(busstop_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "busstop_id=" + encodeURI( document.getElementById("busstop_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 } 
 
function show_studentcard_record(obj)
 {
    var studentcard_id=document.getElementById("studentcard_id").value;
    //alert(busroute_id);
    if(studentcard_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "studentcard_id=" + encodeURI( document.getElementById("studentcard_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 }
 
 
function show_student_record(obj)
 {
    var student_id=document.getElementById("student_id").value;
    //alert(busroute_id);
    if(student_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "student_id=" + encodeURI( document.getElementById("student_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 }
 
 
 

 
function show_assigned_record(obj)
 {
    var bus_id=document.getElementById("bus_id").value;
    //alert(busroute_id);
    if(bus_id=="select")
    {
      remOption(document.getElementById("shift_id"));
	    addOption(document.getElementById("shift_id"),'Select','select');
      alert("Please Select Bus"); 
      document.getElementById("bus_id").focus();          
      return false; 
    }
    else
    {
      var poststr = "bus_id=" + encodeURI( document.getElementById("bus_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 }

 function show_person_record(obj)
 {
    var person_id=document.getElementById("person_id").value;
    //alert(busroute_id);
    if(person_id=="select")
    {
      document.getElementById("display_area").style.display="none"; 
    }
    else
    {
      var poststr = "person_id=" + encodeURI( document.getElementById("person_id").value);
      makePOSTRequest('src/php/manage_ajax_shift.php', poststr);
    }
 }
 

//************************** For School Option End *********************************	
  
  /////////////// MILESTONE ///////////////////////////

  function get_latlng_fields(number)
  {
    var poststr= "number="+number;
  	makePOSTRequest('src/php/manage_get_latlng_fields.php', poststr);   
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
			makePOSTRequest('src/php/action_manage_milestone.php', poststr);     
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
			
			if(document.getElementById("rest_time_hr").value!="")
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
			}
			
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
			"&allow_rest_time="+document.getElementById("rest_time_hr").value+":"+document.getElementById("rest_time_min").value+			
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
	makePOSTRequest('src/php/action_manage_schedule.php', poststr);
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
	 makePOSTRequest('src/php/manage_min_max_halt.php', poststr);
 
 }
 
  function show_selected_intermediate_halt_no(no_of_intermediate_halt)
 {
	var poststr ="no_of_intermediate_halt="+no_of_intermediate_halt; 
	//alert("poststr="+poststr);
	 makePOSTRequest('src/php/manage_intermediate_check_halt_time.php', poststr); 
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
      makePOSTRequest('src/php/manage_ajax_geo_coord.php', poststr);
    }  
 }


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
  else if(common_event=="location")
  {
	manage_location(common_event);
  }
  else///////for geofencing and route both ////////////
  {   	 
    if(common_event=="geofencing")
    {
       document.getElementById("close_geo_route_coord").value = document.getElementById("geo_coord").value; // kept last geo coord details for closing pop up div 
    }   
    else if(common_event=="sector")
    {
      //alert("one="+document.getElementById("close_geo_route_coord"));
      //alert("two="+document.getElementById("sector_coord"));
      document.getElementById("close_geo_route_coord").value = document.getElementById("sector_coord").value; // kept last geo coord details for closing pop up div
    }
  	
    map = new GMap2(document.getElementById("map_div")); 	
		map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5); 
		map.setUIToDefault();
    map.enableGoogleBar();		
    manage_draw_geofencing_route();
  } 
}

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
				var point=new GLatLng(parseFloat(lat_lng[0]), parseFloat(lat_lng[1]));
				var lat=point.lat();  var lng=point.lng();        
				
				var iwform = '<div style="height:10px"></div><table>'
				+'<tr><td style="font-size:11px;">'+point+'</td></tr>'               
				+'</table><div style="height:10px"></div>'			 					
				+'<center><input type="button" value="OK" onclick="javascript:return save_location_details(\'geo_point\')" /></center>';
													   
				var marker = new GMarker(point, lnmark);
				//alert("markers="+marker);
				GEvent.addListener(marker, "click", function()
				{
					lastmarker = marker;
					document.getElementById("geo_point").value=lat+","+lng;
					marker.openInfoWindowHtml(iwform);
				});
				map.addOverlay(marker);    
			}
			else
			{
				//alert("in else");
				var lat_lng="";        
				location_map_part(lat_lng);
				map.clearOverlays();
			}

			var lastmarker;    
			GEvent.addListener(map,"click",function(overlay,point)
			{
				//document.getElementById("zoom_level").value=map.getZoom();
				if (!overlay)
				{
					//alert("point_id="+point_id+"zoome_id="+zoom_id)
					map.clearOverlays(); 
					createLocationInputMarker(point);
				}
			});     
		}
		else 
		{
			alert("Sorry, the Google Maps API is not compatible with this browser");
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
		map = new GMap2(document.getElementById("location_map"));	
		map.setCenter(new GLatLng(22.755920681486405,78.2666015625), zoom);
		map.setUIToDefault();
		map.enableGoogleBar();
	}
	
	
	function createLocationInputMarker(point) 
{
    var lat_1=point.lat();
    var lng_1=point.lng();  
       
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
    makePOSTRequest('src/php/action_manage_vtrip.php', poststr);
 } 
