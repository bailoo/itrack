function show_account_detail()
{
	//alert("test");
	document.getElementById("portal_vehicle_information").style.display='none';
}
////////////////// HTTP POST REQUEST ///////////////////////////////////////////
   
//SHOW OPTIONS
function show_entity_option(target_file_prev, target_file) // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
{	
	var poststr = "target_file="+target_file;
	if(target_file=="account_details")
	{
		makePOSTRequest("src/php/manage_add_choose_account.php", '');
	}
	else
	{
		makePOSTRequest(target_file_prev, poststr);
	}
}

function select_by_entity(options)
{
	var poststr = "display_type1=" + encodeURI(options);
	makePOSTRequest('src/php/entity_selection_information.php', poststr);
}

function show_option(type, option, target_file)      // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
{
  var poststr = "target_file="+target_file;
  //alert("type="+type+" ,option="+option+" ,poststr="+poststr);
  //alert("riz:"+type+" opt="+option);
  makePOSTRequest('src/php/' + type + '_' + option + '.php', poststr);
}

 function load_kyc(file, option)      // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
{
  var poststr = "setting_account_id="+option;
 
  //alert("riz:"+poststr +file);
  makePOSTRequest('src/php/'+ file + '.php', poststr);
}

function show_option_with_value(type, option)      // type="manage/report/setting, option=account,vehicle,device,speed,distance,fuel etc"
{

  var poststr = document.getElementById("common_id").value;
  //alert("poststr="+poststr); 
  makePOSTRequest('src/php/' + type + '_' + option + '.php', poststr);
} 

 var http_request = false;
 function makePOSTRequest(url, parameters) 
 {
    //alert("url="+url+" ,parameter="+parameters);
    http_request = false;
    if (window.XMLHttpRequest) 
    { 
       http_request = new XMLHttpRequest();
       if (http_request.overrideMimeType)
       {
       	//set type accordingly to anticipated content type
          //http_request.overrideMimeType('text/xml');
          http_request.overrideMimeType('text/html');
       }
    }
    else if (window.ActiveXObject) 
    { // IE
       try 
       {
          http_request = new ActiveXObject("Msxml2.XMLHTTP");
       }
       catch (e) 
       {
          try 
          {
             http_request = new ActiveXObject("Microsoft.XMLHTTP");
          }
          catch (e)
          {}
       }
    }
    if (!http_request) 
    {
       alert('Cannot create XMLHTTP instance');
       return false;
    }
    
    http_request.onreadystatechange = alertContents;
    http_request.open('POST', url, true);
    http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http_request.setRequestHeader("Content-length", parameters.length);
    http_request.setRequestHeader("Connection", "close");
    http_request.send(parameters);
 }

 function alertContents()
 {

	if(typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g, '');}
	}

    //alert("alertC");
    if (http_request.readyState == 4) 
    {
       if (http_request.status == 200) 
       {
          result = http_request.responseText; 
          //alert(result);                     
          var result1=result.split("##");  
         //alert("Rizwan:"+result1[0]);
                      
          if(result1[0].trim()=="success")
          {
            document.getElementById('available_message').style.display='';
            document.getElementById('available_message').innerHTML =result1[1];
            document.getElementById("enter_button").disabled=false;           
          }
          else if(result1[0].trim()=="driver_updated")
          {
              alert(result1[1]);
              document.getElementById('update_driver').style.display='none';
              document.getElementById('fade').style.display='none';
              show_data_on_map('map_report');
              //document.getElementById('driver_name_js').innerHTML = document.getElementById('driver_name').value;
              //document.getElementById('driver_mobile_js').innerHTML = document.getElementById('driver_mobile').value;              
          }
          else if(result1[0]=="group_vehicles")
          {
            document.getElementById('mining_vehicle_display').style.display='';
            document.getElementById('mining_vehicle_display').innerHTML =result1[1];
            //document.getElementById("enter_button").disabled=false;             
          } 
          else if(result1[0]=="report_vehicle_details")
          {
            document.getElementById('vehicle_details').style.display='';
            document.getElementById('vehicle_details').innerHTML =result1[1];
            //document.getElementById("enter_button").disabled=false;             
          }
		  
		   else if(result1[0]=="vendor_or_order_details")
          {
			       show_sales_div_popup(); 
			       //alert("val"+result1[1]);			
            document.getElementById('vendor_or_order_detail_div').innerHTML =result1[1];
            //document.getElementById("enter_button").disabled=false;             
          }
		  else if(result1[0].trim()=="clear_assigned_location")
		  {		
			if(result1[2].trim()>=10)
			{
				 document.getElementById(result1[0].trim()).setAttribute("style","height:200px;overflow:auto;");
			}
			 document.getElementById(result1[0].trim()).style.display='';
            document.getElementById(result1[0].trim()).innerHTML =result1[1];
		  }	
		  else if(result1[0].trim()=="success_cla") // cla=clear loaction assignment
		  {			
			alert("This Assignment Deleted Successfully");
			var poststr ="vehicleid_to_location="+result1[1].trim()+
						 "&vehicle_name="+result1[2].trim();  
			//alert("poststr="+poststr);
			 makePOSTRequest('src/php/manage_clear_assigned_location.php', poststr);		
		  }
		  
		  else if(result1[0].trim()=="failure_cla")
		  {		
			alert("Unable To Process Request");				
		  }
		   else if(result1[0].trim()=="success_edit_sa") // cla=clear loaction assignment
		  {			
			alert("This Assignment Edited Successfully");
			var poststr ="vehicleid_to_location="+result1[1].trim()+
						 "&vehicle_name="+result1[2].trim();  
			//alert("poststr="+poststr);
			 makePOSTRequest('src/php/manage_clear_assigned_location.php', poststr);		
		  }
		  
		  else if(result1[0].trim()=="ShowNewTripFormat")
		{
			document.getElementById("loading_msg").style.display = 'none';
			document.getElementById("tripLoadingBlackout").style.visibility = "visible";
			document.getElementById("tripLoadingDivPopUp").style.visibility = "visible";
			document.getElementById("tripLoadingBlackout").style.display = "block";
			document.getElementById("tripLoadingDivPopUp").style.display = "block"; 
			document.getElementById("showResult").innerHTML=result1[1];		
		}
		  
		  else if(result1[0].trim()=="failure_edit_sa")
		  {		
			alert("Unable To Process Request");				
		  }
		  else if(result1[0].trim()=="shitVehicleBlock") // cla=clear loaction assignment
			{	
				//alert("in if");
				document.getElementById("shitVehicleBlock").style.display='';
				document.getElementById("shitVehicleBlock").innerHTML=result1[1];			 
			}
		  else if(result1[0]=="show_moto_vehicle")
      		{   
      		  show_div_block('moto_manage_vehicle');
      		  document.getElementById('moto_display_vehicle').style.display='';
      		  document.getElementById('moto_display_vehicle').innerHTML =result1[1];            
      		}
		  
          ////////////////////salesssssss//////////////////		  
           else if(result1[0]=="manage_vendor_type")
            {                     
              remOption(document.getElementById("vendor_type_id"));
              addOption(document.getElementById("vendor_type_id"),'Select','select');
              /* below block only used for manage_add_vendor_type web page */              
              if( (document.getElementById("action_type").value == "add") || (document.getElementById("action_type").value == "add_order") )
              {             
                addOption(document.getElementById("vendor_type_id"),'None','0');
              }  
              /*    block closed    */          
              var tempstr=result1[1].split(",");                 
              if(tempstr.length>0)
              {
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
                     // var set_value=tempstr1[0]+","+tempstr1[1];              
                      if(tempstr1[0]!="" && tempstr1[1]!="")
                      {
                          //alert("value1 : "+tempstr1[0]+"value2 : "+tempstr1[1]);
                        // addOption(document.getElementById("vendor_type_id"),tempstr1[1],set_value);
                         addOption(document.getElementById("vendor_type_id"),tempstr1[1],tempstr1[0]);
                      }
                  }           
              }                                                             
            }			
			else if(result1[0]=="manage_vendor_type_name")
			{ 
				document.getElementById('vendor_type_name').value = result1[1];                                               
			}
         
		      else if(result1[0]=="vendor_admin_ids")
          { 
			       document.getElementById('vendor_tree').style.display= '';		  
            document.getElementById('vendor_tree').innerHTML = result1[1];                                                             
          }
		    else if(result1[0]=="ordered_vendor_ids")
          { 
			 document.getElementById('ordered_vendorids_div').style.display= '';		  
            document.getElementById('ordered_vendorids_div').innerHTML = result1[1];                                                             
          }
		   else if(result1[0]=="manage_order_list")
          { 
			 document.getElementById('orderid_div').style.display= '';		  
            document.getElementById('orderid_div').innerHTML = result1[1];                                                             
          }
		  else if(result1[0]=="manage_location_coord")
          {
			//alert("landmark="+result1[1]+"lanmark_point="+result1[2]+"zoom_level="+result1[3]);
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('location_name').value=result1[1];
            document.getElementById('geo_point').value=result1[2];       		  
          }
		 //FILE FORMAT 
		 else if(result1[0].trim()=="filename_fields")
		 {
			//alert("in else="+result1[1]);
			document.getElementById(result1[0].trim()).style.display="";  /////////for enabling coord input type in Existing option
			document.getElementById(result1[0].trim()).innerHTML=result1[1];               		  
		 }
		  else if(result1[0].trim()=="upload_format_child_file")
          {
			document.getElementById("show_child_filename_fields").style.display="none";
			document.getElementById("show_child_filename_fields").innerHTML="";	
            document.getElementById(result1[0].trim()).style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById(result1[0].trim()).innerHTML=result1[1];               		  
          }
		  
		   else if(result1[0].trim()=="show_child_filename_fields")
          {
			//alert("in else if");
            document.getElementById(result1[0].trim()).style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById(result1[0].trim()).innerHTML=result1[1];               		  
          }
          else if(result1[0]=="vendor_type_name")
          {
              //alert("vendor_type_string="+result1[0]+"vendor_type_name="+result1[1])          
             document.getElementById('vendor_type_name').value = result1[1];                                                            
          } 
		  
		  else if(result1[0].trim()=="min_max_halt")
          {
			//alert("in else if"+result1[1]);
			//alert("landmark="+result1[1]+"lanmark_point="+result1[2]+"zoom_level="+result1[3]);
            document.getElementById(result1[0].trim()).style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById(result1[0].trim()).innerHTML=result1[1];               		  
          }
		  
		  else if(result1[0].trim()=="intermediate_check_halt_time")
          {
            document.getElementById(result1[0].trim()).style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById(result1[0].trim()).innerHTML=result1[1];               		  
          }
		   else if(result1[0]=="vendor_account_edit")
          {
            //alert("result1="+result1[1]+"result2="+result1[2]);
            document.getElementById("vendor_edit_div").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('vendor_name').value=result1[1];               
          }
			else if(result1[0]=="manage_expense_type")
            { 
				//alert("in else if");
              remOption(document.getElementById("expense_type_id"));
              addOption(document.getElementById("expense_type_id"),'Select','select');
                   
              var tempstr=result1[1].split(",");                 
              if(tempstr.length>0)
              {
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
                     // var set_value=tempstr1[0]+","+tempstr1[1];              
                      if(tempstr1[0]!="" && tempstr1[1]!="")
                      {
                          //alert("value1 : "+tempstr1[0]+"value2 : "+tempstr1[1]);
                        // addOption(document.getElementById("vendor_type_id"),tempstr1[1],set_value);
                         addOption(document.getElementById("expense_type_id"),tempstr1[1],tempstr1[0]);
                      }
                  }           
              }                                                             
            }
			
			 else if(result1[0]=="manage_call_type")
            {                     
              remOption(document.getElementById("call_type_id"));
              addOption(document.getElementById("call_type_id"),'Select','select');
                   
              var tempstr=result1[1].split(",");                 
              if(tempstr.length>0)
              {
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
                     // var set_value=tempstr1[0]+","+tempstr1[1];              
                      if(tempstr1[0]!="" && tempstr1[1]!="")
                      {
                          //alert("value1 : "+tempstr1[0]+"value2 : "+tempstr1[1]);
                        // addOption(document.getElementById("vendor_type_id"),tempstr1[1],set_value);
                         addOption(document.getElementById("call_type_id"),tempstr1[1],tempstr1[0]);
                      }
                  }           
              }                                                             
            }
			
			else if(result1[0].trim()=="display_combo")
            { 
				//alert("res="+result1[0]);
              remOption(document.getElementById(result1[1]));
              addOption(document.getElementById(result1[1]),'Select','select');                   
              var tempstr=result1[2].split(","); 
				//alert("tempstr="+tempstr[0]);
				//alert("result1="+result1[1]);				
              if(tempstr.length>0)
              {
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");                                 
                      if(tempstr1[0]!="" && tempstr1[1]!="")
                      {
                         addOption(document.getElementById(result1[1]),tempstr1[1],tempstr1[0]);
                      }
                  }           
              }                                                             
            }
			else if(result1[0]=="Invoice_Success_Message")
			{
				alert("Invoice Added Successfully");
				document.getElementById("print").style.display="";
				document.getElementById("confirm").style.display="none";
			}			
			else if(result1[0]=="Invoice_Failure_Message")
			{
				alert("Invoice Not Addedd");
				document.getElementById("confirm").style.display="";
				document.getElementById("print").style.display="none";
			}
			else if(result1[0]=="report_delete_file")
          {
			var result=confirm("Are you sure to delete this file?");
			if (result==true)
			{
				document.getElementById(result1[1].trim()).style.display='none';
			}				          
          }
			else if(result1[0]=="Dispatch_Success_Message")
			{	
				document.getElementById("row"+result1[1].trim()+"").style.display="none";
				document.getElementById("available_message").innerHTML="Dispatched Successfully";
			}
			else if(result1[0]=="Dispatch_Failure_Message")
			{
				document.getElementById("row"+result1[1]).style.display="";
				document.getElementById("available_message").innerHTML="Dispatch Invoice Not Added ";
			}
            
          else if(result1[0]=="manage_call_type_name")
          { 
            document.getElementById('call_type_name').value = result1[1];                                               
          }
		  
		  else if(result1[0]=="display_table_data")
          { 
			document.getElementById('div_table_block').style.display='none';
			document.getElementById('div_table_block').style.display='';
			document.getElementById('div_table_block').innerHTML = result1[1];                                                        
          }
		  
		    else if(result1[0]=="manage_expense_type_name")
          { 
            document.getElementById('expense_type_name').value = result1[1];                                               
          }	
		  else if(result1[0]=="route_id_to_vendors")
		{ 
			//alert("result1="+result1[1]);
			document.getElementById('vendor_div_block').style.display='none';
			document.getElementById('vendor_div_block').style.display='';
			document.getElementById('vendor_div_block').innerHTML = result1[1];                                               
		}
/////////////stop sales ///////////////////		
          else if(result1[0].trim()=="show_device")
      		{   
      		  show_div_block('device_vehicle_assigment');
      		  document.getElementById('display_device').style.display='';
      		  document.getElementById('display_device').innerHTML =result1[1];            
      		}         
          else if(result1[0].trim()=="failure")
          {         
            document.getElementById('available_message').style.display='';
            document.getElementById('available_message').innerHTML =result1[1];
            document.getElementById("enter_button").disabled=true;
            //document.getElementById("u_d_enter_button").disabled=true; 
          }
          else if(result1[0]=="manage_geo_coord")
          {
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('geo_name').value=result1[1];
            document.getElementById('geo_coord').value=result1[2];           
          }
		   else if(result1[0]=="manage_polyline_coord")
          {
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('polyline_name').value=result1[1];
            document.getElementById('polyline_coord').value=result1[2];           
          }
          else if(result1[1]=="calibration_detail")
          {
            //alert("result1="+result1[1]+"result2="+result1[2]);
            document.getElementById("calibration_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('calibration_name').value=result1[2];
            document.getElementById('calibration_data').value=result1[3];           
          }
          else if(result1[0].trim()=="escalation_detail")
          {
            //alert("result1="+result1[1]+"result2="+result1[2]);
            document.getElementById("escalation_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('person_name').value=result1[1];
            document.getElementById('person_mob').value=result1[2];
			document.getElementById('person_email').value=result1[3];
            document.getElementById('other_detail').value=result1[4];           
          }
          else if(result1[0]=="order_id")
          {
            //alert("order");
            document.getElementById("order_id").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('order_id').innerHTML=result1[1];
          }          
          else if(result1[0]=="order_list")
          {
            //alert("vendor");
            document.getElementById("order_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('order_area').innerHTML=result1[1];
          }                               
          /*else if(result1[0]=="manage_route_coord")
          {
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('route_name').value=result1[1];
            document.getElementById('route_coord').value=result1[2];
          }*/
          
          else if(result1[0]=="manage_sector_coord")
          {
            //alert(result1[1]);
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('sector_name').value=result1[1];
            document.getElementById('sector_coord').value=result1[2];           
          }
          else if(result1[0]=="manage_sector_ids")
          {
            //alert("in sectorids");
            document.getElementById("sector_area").style.display=""; 
            document.getElementById('sector_area').innerHTML=result1[1];         
          }          
                     
          else if(result1[0]=="manage_landmark_coord")
          {
			      //alert("landmark="+result1[1]+"lanmark_point="+result1[2]+"zoom_level="+result1[3]);
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('landmark_name').value=result1[1];
            document.getElementById('landmark_point').value=result1[2];             
            document.getElementById('zoom_level').value=parseInt(result1[3]);
			//document.getElementById('distance_variable').value=parseFloat(result1[4]);			
		  
          }
          
          else if(result1[0]=="manage_station_coord")
          {
			      //alert("landmark="+result1[1]+"lanmark_point="+result1[2]+"zoom_level="+result1[3]);
            document.getElementById("coord_area").style.display="";  /////////for enabling coord input type in Existing option          
            document.getElementById('station_name').value=result1[1];
            document.getElementById('customer_no').value=result1[2];
            document.getElementById('landmark_point').value=result1[3];
            document.getElementById('distance_variable').value=result1[4];
            document.getElementById('station_type').value=result1[5];                          
            //document.getElementById('zoom_level').value=parseInt(result1[3]); 		  
          }
          
          else if(result1[0].trim()=="alert_assign")
          {
			      //alert("landmark="+result1[1]+"lanmark_point="+result1[2]+"zoom_level="+result1[3]);
	    hideManageLoadingMessage();
            document.getElementById('loading_status').style.display="none";
            document.getElementById('AssignedStatus').style.display="";  /////////for enabling coord input type in Existing option
            document.getElementById('AssignedStatus').innerHTML = result1[1];            	  
          }
          
          else if(result1[0]=="assign_visit_area")
          {
            //alert(result1[1]);
            document.getElementById('assign_status').innerHTML = document.getElementById('assign_status').innerHTML + "<br>"+result1[1];                                		  
          }                
                             
          else if(result1[0].trim()=="add" || result1[0].trim()=="assign" || result1[0].trim()=="register" || result1[0].trim()=="edit" || result1[0].trim()=="deassign" || result1[0].trim()=="de-register" || result1[0].trim()=="choose_account")
          {
            //alert("Rizwan-Res2="+result1[1]);
            //alert("edit_div="+document.getElementById('edit_div'));
            //document.getElementById('new').style.display='none';
            //alert(document.getElementById('edit_div').style.display);
			hideManageLoadingMessage();
            document.getElementById('edit_div').style.display ="";              
            document.getElementById('edit_div').innerHTML = result1[1];                           
          }
		  
		  else if(result1[0]=="assignDeviceOnly")
          {
            hideManageLoadingMessage();
            document.getElementById('edit_div').style.display =""; 	
			//alert("result2="+result1[2]);
            document.getElementById('edit_div').innerHTML = result1[2]; 
			
			//alert("jsonData="+jsonData.length);
			var tmpStr='';
			var tmpStr1='';
			var tmpStr2='';
			tmpStr=tmpStr+'<table border="0" class="manage_interface" align="center">'+
					'<tr>'+
						'<tr>';
							var rowChange=0
						var jsonData = JSON.parse(result1[1]);	
						for (var i = 0; i < jsonData.length; i++) 
						{
							if(rowChange==4)
							{
								tmpStr1=tmpStr1+'</tr><tr>';
								rowChange=0;
							}
							tmpStr1=tmpStr1+'<td>'+
							'<input type="radio" name="manage_id" value='+jsonData[i].device_imei_no+' onclick="javascript:manage_show_account(this.value,\'src/php/manage_show_account.php\')">'+
							'&nbsp;'+jsonData[i].device_imei_no+''
							'</td>';
							rowChange++;
						}
						var finalStr=tmpStr+tmpStr1+'</tr></table>';
					 document.getElementById('tmpTableDiv').innerHTML = finalStr;                         
          }
		  
		  
		  
		  else if(result1[0]=="deassign_substation_vehicle")          
		  {
			hideManageLoadingMessage();
            document.getElementById('substation_vehicles').style.display ="";              
            document.getElementById('substation_vehicles').innerHTML = result1[1];			
		  }
		  else if(trim(result1[0])=="deassign_substation_plant")         
		  {
			//alert(result1[0]);
			//alert(result1[1]);
			hideManageLoadingMessage();
            document.getElementById('substation_plant').style.display ="";              
            document.getElementById('substation_plant').innerHTML = result1[1];			
		  }
                  else if(trim(result1[0])=="deassign_gate_plant")          
		  {
			//alert(result1[0]);
			//alert(result1[1]);
			hideManageLoadingMessage();
                    document.getElementById('gate_plant').style.display ="";              
                    document.getElementById('gate_plant').innerHTML = result1[1];			
		  }
		    else if(result1[0]=="destination_account")          
		  {
			hideManageLoadingMessage();
            document.getElementById('destination_account').style.display ="";              
            document.getElementById('destination_account').innerHTML = result1[1];			
		  }
		   else if(result1[0]=="source_vehicle")          
		  {
			hideManageLoadingMessage();
            document.getElementById('source_vehicle').style.display ="";              
            document.getElementById('source_vehicle').innerHTML = result1[1];			
		  }
          else if(result1[0].trim()=="edit_div_station1")
          {
            //alert("edit_div_station1="+result1[1]);
			hideManageLoadingMessage();
            document.getElementById('edit_div_station1').style.display ="";              
            document.getElementById('edit_div_station1').innerHTML = result1[1];                           
          }
          else if(result1[0].trim()=="edit_div_station2")
          {
            document.getElementById('edit_div_station2').style.display ="";              
            document.getElementById('edit_div_station2').innerHTML = result1[1];                           
          }                         
          else if(result1[0]=="show_vehicle")
          {             
            //alert("show vehicle");
            document.getElementById('v_list').style.display ="";              
            document.getElementById('v_list').innerHTML = result1[1];                           
          }
          else if(result1[0].trim()=="map_show_vehicle" || result1[0].trim()=="live_show_vehicle" || result1[0].trim()=="live_show_vehicle_open")
          {
            //alert("map show vehicle");  
            //alert("result="+result1[1]);			
		        // document.getElementById('main_display_vehicle').value="1";
            //alert("disp:"+document.getElementById('loading_live').style.display);            
            /*if(result1[0]=="live_show_vehicle")
            {
              document.getElementById('loading_live').style.display = 'none';
            } */           
            //else
            if(result1[0].trim()=="map_show_vehicle")
            {
              var display_mode=document.thisform.mode;
              for(var i=0;i<display_mode.length;i++)
              {    
                if(i==0)
                {  
                  display_mode[i].checked = true;
                }
              }
              document.getElementById('show_vehicle').style.display ="";              
              document.getElementById('show_vehicle').innerHTML = result1[1]; 
			 document.getElementById('vehicleloadmessage').style.display ="none";
			  document.getElementById('vehicleloadmessage1').style.display ="";
            }
             else if(result1[0].trim()=="live_show_vehicle_open")
            { 
			  //alert("s"+result1[1]);
              document.getElementById("blackout_4").style.visibility = "visible";
              document.getElementById("divpopup_4").style.visibility = "visible";
              document.getElementById("blackout_4").style.display = "block";
              document.getElementById("divpopup_4").style.display = "block";
              
              document.getElementById('show_vehicle').style.display ="";              
              document.getElementById('show_vehicle').innerHTML = result1[1];
			  testText();
            }
            else if(result1[0].trim()=="live_show_vehicle")
            { 
              document.getElementById("blackout_4").style.visibility = "visible";
              document.getElementById("divpopup_4").style.visibility = "visible";
              document.getElementById("blackout_4").style.display = "block";
              document.getElementById("divpopup_4").style.display = "block";
              
              document.getElementById('show_vehicle').style.display ="";              
              document.getElementById('show_vehicle').innerHTML = result1[1];
            }						  
          }         
      		else if(result1[0].trim()=="map_selection_information")
      		{  
      		  //alert("result="+result1[1]);
      			//document.getElementById("all_vehicle_1").style.display="none"; 
      			// document.getElementById("all_vehicle_type").value="";
      		  show_vehicle_display_option()
      
      		  document.getElementById('selection_information').style.display ="";              
      		  document.getElementById('selection_information').innerHTML = result1[1];                           
      		} 
		      else if(result1[0].trim()=="manage_selection_information")
          {  
            //alert("result="+result1[1]);
    			  document.getElementById("all_vehicle_1").style.display="none"; 
    			  document.getElementById("all_vehicle_type").value="";			  
            document.getElementById('manage_selection_information').style.display ="";              
            document.getElementById('manage_selection_information').innerHTML = result1[1];                           
          }
		else if(result1[0].trim()=="portal_vehicle_information")
          {                          
              document.getElementById('portal_vehicle_information').style.display ="";			 			  
              document.getElementById('portal_vehicle_information').innerHTML = result1[1];
      			  //alert("result2="+result1[2]);
      			  setCombo(result1[2])	
          }		   
		     else if(result1[0].trim()=="main_vehicle_information")
          {  
          //alert("result="+result1[1]);          
          document.getElementById('main_vehicle_information').style.display ="";              
          document.getElementById('main_vehicle_information').innerHTML = result1[1];                           
          }            
          else if(result1[0]=="text_report")
          {
            document.getElementById('map').style.display ="";              
            document.getElementById('map').innerHTML = result1[1];
          }  
          else if(result1[0]=="show_latlng_fields")
          {             
            document.getElementById('number_of_fields').style.display ="";              
            document.getElementById('number_of_fields').innerHTML = result1[1];                           
          } 
          else if(result1[0]=="datagap")
          {         
            var result2=result1[1].split(":");
            //alert("r22="+result1);
                           
            var lat1 = result2[0];
            var lng1 = result2[1];
            var lat2 = result2[2];
            var lng2 = result2[3];
            var textcontent = result1[2];
            //alert(lat1+","+lng1+" "+lat2+","+lng2);            
            //alert(textcontent);
            document.getElementById('floating_div').style.display='';
            document.getElementById('floating_div').innerHTML = textcontent;
            
            //alert("r");
            if(lat1!="-" && lng1!="-" && lat2!="-" && lng2!="-")
            {                
              //alert("in load map");
              load_map(lat1,lng1,lat2,lng2);
            }
        } 
		else if(result1[0]=="validate_substation_type_user")
		{
			if(trim(result1[2])=="No Valid User")
			{
			alert("Not a Valid SubStation User or Plant Name");
			document.getElementById("login").value='';
			//document.getElementById("route_substation").checked=false;
			document.getElementById("sub_utype").value="0";
			}
		}		  
		else if(result1[0]=="showcity")
		{             
			document.getElementById('cityspan').style.display ="";              
			document.getElementById('cityspan').innerHTML = result1[1];                           
		} 
			
		// code by taseen for school bus
          else if(result1[0]=="manage_studentclass")
          {    
            //alert("ss");   
            document.getElementById("display_area").style.display='';
            document.getElementById('studentclass_id').value = result1[1];
            document.getElementById('studentclass_name').value = result1[2];
            document.getElementById('studentclass_section').value = result1[3];  
            document.getElementById('class_lat').value = result1[4];    
            document.getElementById('class_lng').value = result1[5]; 
                                                          
          }   
          else if(result1[0]=="manage_student_editescalation")
          {    
              //alert("ss1");   
            document.getElementById("display_area1").style.display='';
            document.getElementById('escalationschool_id').value = result1[1];
            document.getElementById('student_father_name').value = result1[2]; 
            document.getElementById('student_parent_mobile_no').value = result1[3]; 
            document.getElementById('person_email').value = result1[4]; 
            
                                                        
          } 
          
          else if(result1[0]=="manage_student_addescalation")
          {    
              //alert("ss1");   
            document.getElementById("display_area1").style.display='';
            document.getElementById('student_name').value = result1[1]; 
            document.getElementById('student_address').value = result1[2]; 
            document.getElementById('student_father_name').value = result1[3]; 
            document.getElementById('student_mother_name').value = result1[4]; 
            document.getElementById('student_roll_no').value = result1[5];
             
            //document.getElementById('student_class').value = result1[6]; 
             if(result1[6]!="0"){
                 
                 var tempstr0 = result1[6].split(":");
                 document.getElementById('student_class').value = tempstr0[1];
            }  
            //document.getElementById('edit_student_section').value = result1[7]; 
            document.getElementById('student_student_mobile_no').value = result1[8]; 
            document.getElementById('student_parent_mobile_no').value = result1[9];
            if(result1[11]!="0"){
                 
                 var tempstr1 = result1[11].split(":");
                 document.getElementById('studentcard_id').value = tempstr1[0];
                 document.getElementById('studentcard').value = tempstr1[1];
            }
             document.getElementById('student_id').value = result1[12];                                             
          }  
          else if(result1[0]=="manage_busdriver")
          {    
              //alert("ss");   
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_driver_name').value = result1[2];
            document.getElementById('edit_dlnumber').value = result1[3];
            document.getElementById('edit_dob').value = result1[4];  
            document.getElementById('edit_address').value = result1[5];    
           
          }  
          
          else if(result1[0]=="manage_studentclasswise")
          { 
         
            //alert("ss"+result1[1]);  
            var sizeof=result1[1];
            var student_string=result1[2];
            //alert(student_string);
            var student=student_string.split(",");
            //alert(student[0]) ;
            
            document.getElementById("display_area").style.display='';
            var ele="";
            ele="<table border='0' class='manage_interface'><tr>" ;
            var studidname="";
            
            for(var i=0 ;i<sizeof;i++)
            {
               studidname=student[i].split("->") ;
               
               ele=ele+"<td><input type='radio' name='student_id' value='"+studidname[0]+"' onclick=javascript:show_student_record_byclass(this.value)>&nbsp;"+studidname[1]+"</td>";
               if(i%3==0){ele=ele+'</tr><tr>';}	
               studidname="";
            }
            ele=ele+"</tr></table>" ;
            //alert(ele)   ;
            document.getElementById('class_student_name').innerHTML= ele ;
            //document.getElementById("display_area").style.display='';
            //document.getElementById('edit_driver_name').value = result1[1];
            //document.getElementById('edit_dlnumber').value = result1[2];
            //document.getElementById('edit_dob').value = result1[3];  
           
          } 
          
            else if(result1[0]=="manage_studentclasswise_edit")
          { 
         
            //alert("ss"+result1[1]);  
            var sizeof=result1[1];
            var student_string=result1[2];
            //alert(student_string);
            var student=student_string.split(",");
            //alert(student[0]) ;
            
            document.getElementById("display_area").style.display='';
            var ele="";
            ele="<table border='0' class='manage_interface'><tr>" ;
            var studidname="";
            
            for(var i=0 ;i<sizeof;i++)
            {
               studidname=student[i].split("->") ;
               
               ele=ele+"<td><input type='radio' name='student_id' value='"+studidname[0]+"' onclick=javascript:show_student_record_byclass_edit(this.value)>&nbsp;"+studidname[1]+"</td>";
               if(i%3==0){ele=ele+'</tr><tr>';}	
               studidname="";
            }
            ele=ele+"</tr></table>" ;
            //alert(ele)   ;
            document.getElementById('class_student_name').innerHTML= ele ;
            //document.getElementById("display_area").style.display='';
            //document.getElementById('edit_driver_name').value = result1[1];
            //document.getElementById('edit_dlnumber').value = result1[2];
            //document.getElementById('edit_dob').value = result1[3];  
           
          } 
          //==============================end of code by taseen for bus =============================
		  
          else if(result1[0]=="getbusstops")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            addrowAjax(result1[1])                                  
          }
			    else if(result1[0]=="get_section")
          {             
              //document.getElementById('busstops_assigned').value = result1[1];
              //alert(result1[1]);
              addSection(result1[1])                                                 
          } 
          else if(result1[0]=="show_student")
          {             
            //document.getElementById('student_div').value = result1[1];
            //alert(result1[1]);
            document.getElementById('student_div').innerHTML = result1[1];
            //addSection(result1[1])                                               
          }
          else if(result1[0]=="arrival")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            //alert(result1[2]);
            addrowAjax1(result1[1],result1[2]);                                  
          }
          else if(result1[0]=="get_busroute")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            addBusRoutes(result1[1],result1[2]);                                  
          }
          else if(result1[0]=="get_bus")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            addBus(result1[1],result1[2]);                                  
          }
          
		      else if(result1[0]=="get_shift")
          {             
              //document.getElementById('busstops_assigned').value = result1[1];  
              addShift(result1[1],result1[2]);                                  
          } 
          else if(result1[0]=="manage_shift")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_shift_name').value = result1[1];
            var temp=result1[2].split(":");
            document.getElementById('edit_shift_start_hr').value = temp[0];
            document.getElementById('edit_shift_start_min').value = temp[1];
            var temp1=result1[3].split(":");  
            document.getElementById('edit_shift_stop_hr').value = temp1[0];
            document.getElementById('edit_shift_stop_min').value = temp1[1];                                  
          } 
          else if(result1[0]=="manage_busroute")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_busroute_name').value = result1[1];                                               
          }
          else if(result1[0]=="manage_busstop")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_busstop_name').value = result1[1];
            document.getElementById('edit_busstop_latitude').value = result1[2];
            document.getElementById('edit_busstop_longitude').value = result1[3];                                               
          }
          else if(result1[0]=="manage_studentcard")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_studentcard_number').value = result1[1];                                                             
          }
          else if(result1[0]=="manage_student")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_student_name').value = result1[1]; 
            document.getElementById('edit_student_address').value = result1[2]; 
            document.getElementById('edit_student_father_name').value = result1[3]; 
            document.getElementById('edit_student_mother_name').value = result1[4]; 
            document.getElementById('edit_student_roll_no').value = result1[5]; 
            document.getElementById('edit_student_class').value = result1[6]; 
            document.getElementById('edit_student_section').value = result1[7]; 
            document.getElementById('edit_student_student_mobile_no').value = result1[8]; 
            document.getElementById('edit_student_parent_mobile_no').value = result1[9];
 /*         $school_id1=$row->school_id;
            $studentcard_id1=$row->studentcard_id;
            $bus_id_pick1=$row->bus_id_pick;
            $bus_id_drop1=$row->bus_id_drop;
            $route_id_pick1=$row->route_id_pick;
            $route_id_drop1=$row->route_id_drop;
            $shift_id_pick1=$row->shift_id_pick;
            $shift_id_drop1=$row->shift_id_drop;
            $busstop_id_pick1=$row->busstop_id_pick;
            $busstop_id_drop1=$row->busstop_id_drop;*/ 
            //document.getElementById('edit_student_class').value = result1[10].trim();
            if(result1[11]!="0"){
                 
                 var tempstr1 = result1[11].split(":");
                 addOption(document.getElementById("studentcard_id"),tempstr1[1],tempstr1[0]);
                 document.getElementById('studentcard_id').value = tempstr1[0];
            }
            
            document.getElementById('shift_id_pick').value = result1[16].trim();
            document.getElementById('shift_id_drop').value = result1[17].trim();
            document.getElementById('busstop_id_pick').value = result1[18].trim();
            document.getElementById('busstop_id_drop').value = result1[19].trim();
            document.getElementById('busstop_id_drop').value = result1[19];
            
			get_busroute('get_busroute','pick');
			//get_busroute('get_busroute','drop');
            setTimeout("get_busroute('get_busroute','drop')",150);
            setTimeout('setBusRoute('+result1[14]+','+result1[15]+')',300);
            
            setTimeout("get_bus('get_bus','drop')",600);
			      setTimeout("get_bus('get_bus','pick')",800);
            setTimeout('setBus('+result1[12]+','+result1[13]+')',1000);
                                                                       
          }
          else if(result1[0]=="manage_student_parent")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_student_name').value = result1[1]; 
            document.getElementById('edit_student_address').value = result1[2]; 
            document.getElementById('edit_student_father_name').value = result1[3]; 
            document.getElementById('edit_student_mother_name').value = result1[4]; 
            document.getElementById('edit_student_roll_no').value = result1[5]; 
            document.getElementById('edit_student_class').value = result1[6]; 
            document.getElementById('edit_student_section').value = result1[7]; 
            document.getElementById('edit_student_student_mobile_no').value = result1[8]; 
            document.getElementById('edit_student_parent_mobile_no').value = result1[9];   
                                                                       
          }
          else if(result1[0]=="manage_bus_deassign")
          {             
            //document.getElementById('busstops_assigned').value = result1[1];
            //document.getElementById("display_area").style.display='';
            //document.getElementById('edit_studentcard_number').value = result1[1];
            //alert(result1[1]);
			remOption(document.getElementById("shift_id"));
			addOption(document.getElementById("shift_id"),'Select','select');
			var tempstr=result1[1].split(",");
   
			  if(tempstr.length>=1)
			  {
				if(tempstr[0].length > 3)
				{                             
					//alert("tempstr 0 :"+tempstr[0].length);
					for(var i=0;i<tempstr.length;i++)
					{
						var tempstr1 = tempstr[i].split(":");
						addOption(document.getElementById("shift_id"),tempstr1[1],tempstr1[0]);
					}
				}
			  }                                                             
          } 			  
		  
          else if(result1[0]=="manage_person")
          {             
            //alert("in manage person");
            //document.getElementById('busstops_assigned').value = result1[1];
            document.getElementById("display_area").style.display='';
            document.getElementById('edit_person_name').value = result1[1];
            document.getElementById('edit_person_address').value = result1[2];
            document.getElementById('edit_mobile_no').value = result1[3];  
            document.getElementById('edit_imei_no').value = result1[4];                                               
          }    
          else if(result1[0]=="getlocations")
          {                         
            document.getElementById('route_coord').value = result1[1];                                                          
          }
          else if(result1[0]=="get_features")
          {             
            //alert("res1="+result1[1]);
            //document.getElementById('account_feature_default').style.display ='none'; 
            document.getElementById('frameset1').style.display ='';    
            document.getElementById('account_feature_usertype').style.display ='';              
            document.getElementById('account_feature_usertype').innerHTML = result1[1];      
            //alert("res2="+document.getElementById('account_feature_usertype'));                    
          }                                                                                                                
          else if(result1[0].trim()=="search_vehicle")
    	  {
    			document.getElementById("box").innerHTML =  result1[1];
          }  
          else if(result1[0].trim()=="search_vehicle1")
    	  {
			content=document.getElementById(result1[1]+"text_content").value;
			//alert("content="+content);
			//alert("result34="+result1[3]);
			if(result1[3].trim()=="No Data Found")
			{
				//alert("test");
				document.getElementById(result1[1]+"box").style.display='none';
				document.getElementById(result1[1]+"text_content").value="";
			}
			else if(result1[3].trim()=="No Data Found Next")
			{
				document.getElementById(result1[1]+"box").style.display='none';
				document.getElementById(result1[1]+"text_content").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				var tempppp=trim(result1[1]+"box");
				document.getElementById(tempppp).style.display='';
				document.getElementById(tempppp).innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById(result1[1]+'box').style.display = 'none';
			}
    	  }
          else if(result1[0].trim()=="search_vehicle2")
    	  {
			//alert("veh_name="+result1[0]);
			content=document.getElementById(result1[1]+"text_content2").value;
			//alert("content="+content);
			if(esult1[3].trim()=="No Data Found")
			{
				//alert("test");
				document.getElementById(result1[1]+"box2").style.display='none';
				document.getElementById(result1[1]+"text_content2").value="";
			}
			else if(result1[3].trim()=="No Data Found Next")
			{
				document.getElementById(result1[1]+"box2").style.display='none';
				document.getElementById(result1[1]+"text_content2").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				var tempppp=result1[1]+"box2".trim();
				document.getElementById(tempppp).style.display='';
				document.getElementById(tempppp).innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById(result1[1]+'box2').style.display = 'none';
			}
    	  }

		  else if(result1[0]=="search_vehicle_substation1")
    	  {
			content=document.getElementById(result1[1]+"text_content1").value;
			//alert("content="+content);
			//alert("result34="+result1[3]);
			if(trim(result1[3])=="No Data Found")
			{
				//alert("test");
				document.getElementById(result1[1]+"box").style.display='none';
				document.getElementById(result1[1]+"text_content1").value="";
			}
			else if(trim(result1[3])=="No Data Found Next")
			{
				document.getElementById(result1[1]+"box").style.display='none';
				document.getElementById(result1[1]+"text_content1").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				var tempppp=trim(result1[1]+"box");
				document.getElementById(tempppp).style.display='';
				document.getElementById(tempppp).innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById(result1[1]+'box').style.display = 'none';
			}
    	  }
		  else if(result1[0]=="search_vehicle_substation2")
    	  {
			content=document.getElementById(result1[1]+"text_content2").value;
			//alert("content="+content);
			//alert("result34="+result1[3]);
			if(trim(result1[3])=="No Data Found")
			{
				//alert("test");
				document.getElementById(result1[1]+"box2").style.display='none';
				document.getElementById(result1[1]+"text_content2").value="";
			}
			else if(trim(result1[3])=="No Data Found Next")
			{
				document.getElementById(result1[1]+"box2").style.display='none';
				document.getElementById(result1[1]+"text_content2").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				var tempppp=trim(result1[1]+"box2");
				document.getElementById(tempppp).style.display='';
				document.getElementById(tempppp).innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById(result1[1]+'box2').style.display = 'none';
			}
    	  }	

		else if(result1[0]=="search_vehicle_raw_milk")
    	  {
			//alert( document.getElementById("box"));
			//document.getElementById("box2").style.display='';
			//document.getElementById("box2").innerHTML =  result1[2]+result1[3];
			
			//alert( result1[2]+result1[3]);
			content=document.getElementById('vehicle_list').value;
			//alert("content="+content);
			//alert("result34="+result1[3]);
			if(trim(result1[3])=="No Data Found")
			{
				//alert("test");
				document.getElementById("box2").style.display='none';
				//document.getElementById(result1[1]+"text_content2").value="";
			}
			else if(trim(result1[3])=="No Data Found Next")
			{
				document.getElementById("box2").style.display='none';
				document.getElementById("vehicle_list").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				
				document.getElementById("box2").style.display='';
				document.getElementById("box2").innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById('box2').style.display = 'none';
			}
    	  }
		  
		  else if(result1[0]=="search_vehicle_hindalco_self")
    	  {
			//alert( document.getElementById("box"));
			//document.getElementById("box2").style.display='';
			//document.getElementById("box2").innerHTML =  result1[2]+result1[3];
			
			//alert( result1[2]+result1[3]);
			content=document.getElementById('vehicle_list_self').value;
			//alert("content="+content);
			//alert("result34="+result1[3]);
			if(trim(result1[3])=="No Data Found")
			{
				//alert("test");
				document.getElementById("box2").style.display='none';
				//document.getElementById(result1[1]+"text_content2").value="";
			}
			else if(trim(result1[3])=="No Data Found Next")
			{
				document.getElementById("box2").style.display='none';
				document.getElementById("vehicle_list_self").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				
				document.getElementById("box2").style.display='';
				document.getElementById("box2").innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById('box2').style.display = 'none';
			}
    	  }
		   else if(result1[0]=="search_vehicle_hindalco_other")
    	  {
			//alert( document.getElementById("box"));
			//document.getElementById("box2").style.display='';
			//document.getElementById("box2").innerHTML =  result1[2]+result1[3];
			
			//alert( result1[2]+result1[3]);
			content=document.getElementById('vehicle_list_other').value;
			//alert("content="+content);
			//alert("result34="+result1[3]);
			if(trim(result1[3])=="No Data Found")
			{
				//alert("test");
				document.getElementById("box3").style.display='none';
				//document.getElementById(result1[1]+"text_content2").value="";
			}
			else if(trim(result1[3])=="No Data Found Next")
			{
				document.getElementById("box3").style.display='none';
				document.getElementById("vehicle_list_other").value=result1[4];
			}				
			else
			{
				//alert("in else"+result1[1]);
				
				document.getElementById("box3").style.display='';
				document.getElementById("box3").innerHTML =  result1[2]+result1[3];
			}
			if(content.slice(-1)=="@" || content.slice(-1)=="/")
			{
				document.getElementById('box3').style.display = 'none';
			}
    	  }
		  
/*		  else if(result1[0].trim()=="vehicle_trip")
    	  {
				//alert(result1[1]);
 document.getElementById("vehicle_div").style.display ='';
				document.getElementById("vehicle_div").innerHTML = result1[1];				
    	  }*/		  
		
          /*else if(result1[0]=="route_vehicle")
          {                         
            document.getElementById("route_vehicle_div").style.display='';
            document.getElementById('route_vehicle_div').innerHTML = result1[1];                                               
          }	*/	  
          else 
          {
			//hideManageLoadingMessage();
	//alert(result1[0]);
			document.getElementById('bodyspan').innerHTML =result;
                        //$('body, html').animate({scrollTop:$('form').offset().top-50}, 'slow');
                        $("html,body").animate({ scrollTop: 0 }, "slow");
          }                  
       }
       else 
       {
          alert('There was a problem with the request.');
       }

        //SET FILTER GRID ON
        //var len = document.getElementById('filter_block[]').length();
        //alert("loading.."+len);
        //var filter_block;
        
        //for(var i=0;i<len;i++)
        //{
          /*var table3Filters = 
          {
          	paging:true,
          	sort_select:true				
          }
          //filter_block = document.getElementById('filter_block'+[i]);
          setFilterGrid("filter_block",0,table3Filters);
          //setFilterGrid(filter_block[i],0,table3Filters);*/
        //}
        
        	/*var table3Filters = {
        		btn: true,
        		col_0: "select",
        		col_5: "none",
        		col_6: "none"
        	}
        	setFilterGrid("table_id1",1,table3Filters); */   

                var table3Filters = {
                        btn: true,                        
                        col_6: "none",
                        col_7: "none"
                }
                setFilterGrid("table_id1",1,table3Filters);

      /////////////////////// 
    }
 }

////////////////// HTTP POST REQUEST CLOSED ////////////////////////////////////
function closeShowNewTripFormat()
{
document.getElementById("tripLoadingBlackout").style.visibility = "hidden";
document.getElementById("tripLoadingDivPopUp").style.visibility = "hidden";
document.getElementById("tripLoadingBlackout").style.display = "none";
}
function show_sales_div_popup()
{
	document.getElementById("blackout_sales").style.visibility = "visible";
	document.getElementById("divpopup_sales").style.visibility = "visible";
	document.getElementById("blackout_sales").style.display = "block";
	document.getElementById("divpopup_sales").style.display = "block"; 
}
function hideManageLoadingMessage()
{
	document.getElementById("loadingBlackout").style.visibility = "hidden";
	document.getElementById("loadingDivPopUp").style.visibility = "hidden";
	document.getElementById("loadingBlackout").style.display = "none";		
}
function close_sales_div_popup()
{
document.getElementById("blackout_sales").style.visibility = "hidden";
document.getElementById("divpopup_sales").style.visibility = "hidden";
document.getElementById("blackout_sales").style.display = "none";
}

function setBusRoute(pick,drop)
{
  //alert(pick+":"+drop);
  document.getElementById('busroute_id_pick').value = pick;
  document.getElementById('busroute_id_drop').value = drop;
  //alert(pick+":"+drop);
}
function setBus(pick,drop)
{
  document.getElementById('bus_id_pick').value = pick;
  document.getElementById('bus_id_drop').value = drop;
}
//-------------------------------

//-------------------------------
function setCombo(result)
{
	remOption(document.getElementById("category"));
	addOption(document.getElementById("category"),'Select','select');    		    
	var tempstr=result.split(","); 
	if(tempstr.length>=1)
	{
	  if(tempstr[0].length > 3)
	  {
		  //alert("tempstr 0 :"+tempstr[0].length);
		  for(var i=0;i<tempstr.length;i++)
		  {
			  var tempstr1 = tempstr[i].split(":");
			 // alert("id="+tempstr1[0]+"name="+tempstr1[1]);
				addOption(document.getElementById("category"),tempstr1[1],tempstr1[0]);
		  }
	  }
	}
}

function setDisplayOption()
{
	document.getElementById("vehicle_milstone").value='';
	remOption(document.getElementById("user_type_option"));
	addOption(document.getElementById("user_type_option"),'Select','select'); 
	addOption(document.getElementById("user_type_option"),'All','all');
	addOption(document.getElementById("user_type_option"),'By Group','group'); 
	addOption(document.getElementById("user_type_option"),'By User','user');
  addOption(document.getElementById("user_type_option"),'By Vehicle Tag','vehicle_tag'); 
	addOption(document.getElementById("user_type_option"),'By Vehicle Type','vehicle_type'); 
	//addOption(document.getElementById("user_type_option"),'By Vehicle','vehicle'); 
}

function addrowAjax(result1){
    var table_id = "EntryTable";
    removeRowAjax(table_id);
    remOption(document.getElementById("busstop_after"));
		addOption(document.getElementById("busstop_after"),'At Last','last');
		addOption(document.getElementById("busstop_after"),'At Top','top');
    var tempstr=result1.split("#");
     
      if(tempstr.length>=1)
      {
        if(tempstr[0].length > 3)
        {
            //alert("tempstr 0 :"+tempstr[0].length);
            for(var i=0;i<tempstr.length;i++)
            {
                 
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
                var temp_busstop_id=tempstr[i];//rows_count+":"+busstop_id
                var hidden_busstop='<input type="hidden" name="'+name+'" id="'+name+'" value="'+temp_busstop_id+'" style="font-size: xx-small;">';//for text box 
            	  var remove= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="x" onclick="removeRow(\''+table_id+'\',this.parentNode.parentNode,\''+rows_count+'\')" style="font-size: xx-small;"/>'; 
            	  
            	  var busstoplist=document.getElementById("busstops").value;
                if(busstoplist == ""){
                  document.getElementById("busstops").value= temp_busstop_id;
                }
                else{
                  document.getElementById("busstops").value= busstoplist+"#"+temp_busstop_id;
                }
                
            	  
            	   document.getElementById("rowcnt").value=rows_count;
            	   var tempstr1 = tempstr[i].split(":");
            	   addOption(document.getElementById("busstop_after"),tempstr1[2],i);
            	   try { 
            	    	    var newRow = tbl.insertRow(rows_count);//creation of new row 
                        
                        var newCell = newRow.insertCell(0);//first cell in the row 
                        newCell.innerHTML = hidden_busstop;            
                        var newCell = newRow.insertCell(1);//second cell in the row 
                        newCell.innerHTML = "<b> &#9679; </b>"+tempstr1[2];
                        var newCell = newRow.insertCell(2);//third cell in the row 
                        newCell.innerHTML = remove;
                       
            	  } catch (ex) { 
            	    //if exception occurs 
            	    alert(ex); 
            	  }             
            
            }
        }
      
      }
}

//--------------- arrival 
function addrowAjax1(result1,arrivalTimes){
    var table_id = "EntryTable";
    removeRowAjax(table_id);
    
    var tempstr=result1.split("#");
     
      if(tempstr.length>=1)
      {
        if(tempstr[0].length > 3)
        {
            //alert("tempstr 0 :"+tempstr[0].length);
            for(var i=0;i<tempstr.length;i++)
            {
                 //alert(tempstr[i]);
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
                var temp_busstop_id=tempstr[i];//rows_count+":"+busstop_id
                var hidden_busstop='<input type="hidden" name="'+name+'" id="'+name+'" value="'+temp_busstop_id+'" style="font-size: xx-small;">';//for text box 
            	  name="hour"+rows_count;
                var hour= '&nbsp;&nbsp;&nbsp;&nbsp;<select name="'+name+'" id="'+name+'" style="font-size: xx-small;">';
                    hour=hour+'<option value="-1">Hour</option>';
    								hour=hour+'<option value="00">00</option> <option value="01">01</option>';
    								hour=hour+'<option value="02">02</option> <option value="03">03</option>';
    								hour=hour+'<option value="04">04</option> <option value="05">05</option>';
										hour=hour+'<option value="06">06</option> <option value="07">07</option>';
										hour=hour+'<option value="08">08</option> <option value="09">09</option>';
										hour=hour+'<option value="10">10</option> <option value="11">11</option>';
										hour=hour+'<option value="12">12</option> <option value="13">13</option>';
										hour=hour+'<option value="14">14</option> <option value="15">15</option>';
										hour=hour+'<option value="16">16</option> <option value="17">17</option>';
										hour=hour+'<option value="18">18</option> <option value="19">19</option>';
										hour=hour+'<option value="20">20</option> <option value="21">21</option>';
										hour=hour+'<option value="22">22</option> <option value="23">23</option>';
									hour=hour+'</select>&nbsp;&nbsp;';
									
                
                name="min"+rows_count;
                var min= '&nbsp;&nbsp;&nbsp;&nbsp;<select name="'+name+'" id="'+name+'" value="x" style="font-size: xx-small;">'; 
            	            min=min+'<option value="-1">Min</option>';
                          min=min+'<option value="00">00</option> <option value="01">01</option>';
    											min=min+'<option value="02">02</option> <option value="03">03</option>';
    											min=min+'<option value="04">04</option> <option value="05">05</option>';
    											min=min+'<option value="06">06</option> <option value="07">07</option>';
    											min=min+'<option value="08">08</option> <option value="09">09</option>';
    											min=min+'<option value="10">10</option> <option value="11">11</option>';
    											min=min+'<option value="12">12</option> <option value="13">13</option>';
    											min=min+'<option value="14">14</option> <option value="15">15</option>';
    											min=min+'<option value="16">16</option> <option value="17">17</option>';
    											min=min+'<option value="18">18</option> <option value="19">19</option>';
    											min=min+'<option value="20">20</option> <option value="21">21</option>';
    											min=min+'<option value="22">22</option> <option value="23">23</option>';
    											min=min+'<option value="24">24</option> <option value="25">25</option>';
    											min=min+'<option value="26">26</option> <option value="27">27</option>';
    											min=min+'<option value="28">28</option> <option value="29">29</option>';
    											min=min+'<option value="30">30</option> <option value="31">31</option>'; 											
    											min=min+'<option value="32">32</option> <option value="33">33</option>';
    											min=min+'<option value="34">34</option> <option value="35">35</option>';
    											min=min+'<option value="36">36</option> <option value="37">37</option>';
    											min=min+'<option value="38">38</option> <option value="39">39</option>';
    											min=min+'<option value="40">40</option> <option value="41">41</option>';
    											min=min+'<option value="42">42</option> <option value="43">43</option>';
    											min=min+'<option value="44">44</option> <option value="45">45</option>';
    											min=min+'<option value="46">46</option> <option value="47">47</option>';
    											min=min+'<option value="48">48</option> <option value="49">49</option>';
    											min=min+'<option value="50">50</option> <option value="51">51</option>'; 											
    											min=min+'<option value="52">52</option> <option value="53">53</option>';
    											min=min+'<option value="54">54</option> <option value="55">55</option>';
    											min=min+'<option value="56">56</option> <option value="57">57</option>';
    											min=min+'<option value="58">58</option> <option value="59">59</option>';
    											
    										min=min+'</select>';
									
                
                var busstoplist=document.getElementById("busstops").value;
                if(busstoplist == ""){
                  document.getElementById("busstops").value= temp_busstop_id;
                }
                else{
                  document.getElementById("busstops").value= busstoplist+"#"+temp_busstop_id;
                }
                
            	  
            	   document.getElementById("rowcnt").value=rows_count;
            	   var tempstr1 = tempstr[i].split(":");
            	   //addOption(document.getElementById("busstop_after"),tempstr1[2],i);
            	   try { 
            	    	    var newRow = tbl.insertRow(rows_count);//creation of new row 
                        
                        var newCell = newRow.insertCell(0);//first cell in the row 
                        newCell.innerHTML = hidden_busstop;            
                        var newCell = newRow.insertCell(1);//second cell in the row 
                        newCell.innerHTML = "<b> &#9679; </b>"+tempstr1[2];
                        var newCell = newRow.insertCell(2);//third cell in the row 
                        newCell.innerHTML = hour;
                        var newCell = newRow.insertCell(3);//third cell in the row 
                        newCell.innerHTML = min;
                       
            	  } catch (ex) { 
            	    //if exception occurs 
            	    alert(ex); 
            	  }             
            
            }
        }
      
      }
      
      var tempTime=arrivalTimes.split("#");
      //alert(arrivalTimes);
      for(var n=0;n<tempTime.length;n++)
      {
      //alert(tempTime[n]);
      var temp=tempTime[n].split(":");
      document.getElementById("hour"+n).value=temp[1];
      document.getElementById("min"+n).value=trim(temp[2]);
      //alert(document.getElementById("min"+n).value);
      //alert(temp[1]+":"+temp[2]);
      }
}

function trim(s) 
{ 
    var l=0; var r=s.length -1; 
    while(l < s.length && s[l] == ' ') 
    {     l++; } 
    while(r > l && s[r] == ' ') 
    {     r-=1;     } 
    return s.substring(l, r+1); 
} 

//-------------------------

function removeRowAjax(tbl) 
  { 
    //alert("rowIndex ="+row.rowIndex);
     
    var table = document.getElementById(tbl);     
    document.getElementById("busstops").value= "";
    var rows_count = table.rows.length;
    //alert(rows_count); 
      for(var i=0;i<rows_count;i++)
      {     
        try { 
          //alert(i);
          table.deleteRow(0); 
        } catch (ex) { 
          alert(ex); 
        }
      }
    
  } 
  
  // --------------- remove row closed
 
 function addBusRoutes(pick_drop,result)
    {
        
        if(pick_drop=="pick")
        {
            remOption(document.getElementById("busroute_id_pick"));
    		    addOption(document.getElementById("busroute_id_pick"),'Select','select'); 
            remOption(document.getElementById("bus_id_pick"));
    		    addOption(document.getElementById("bus_id_pick"),'Select','select');    		    
            var tempstr=result.split(",");
         
            if(tempstr.length>=1)
            {
              if(tempstr[0].length > 3)
              {
                  //alert("tempstr 0 :"+tempstr[0].length);
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
            	        addOption(document.getElementById("busroute_id_pick"),tempstr1[1],tempstr1[0]);
                  }
              }
            }
        
        }
        else if(pick_drop=="drop")
        {
            remOption(document.getElementById("busroute_id_drop"));
    		    addOption(document.getElementById("busroute_id_drop"),'Select','select'); 
            remOption(document.getElementById("bus_id_drop"));
    		    addOption(document.getElementById("bus_id_drop"),'Select','select');    		    
            var tempstr=result.split(",");
         
            if(tempstr.length>=1)
            {
              if(tempstr[0].length > 3)
              {
                  //alert("tempstr 0 :"+tempstr[0].length);
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
            	        addOption(document.getElementById("busroute_id_drop"),tempstr1[1],tempstr1[0]);
                  }
              }
            }
        
        }
        
    
    } 
    
    
    
    function addBus(pick_drop,result)
    {
        
        if(pick_drop=="pick")
        {                
            remOption(document.getElementById("bus_id_pick"));
    		    addOption(document.getElementById("bus_id_pick"),'Select','select');    		    
            var tempstr=result.split(",");
         
            if(tempstr.length>=1)
            {
              if(tempstr[0].length > 3)
              {
                  //alert("tempstr 0 :"+tempstr[0].length);
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
            	        addOption(document.getElementById("bus_id_pick"),tempstr1[1],tempstr1[0]);
                  }
              }
            }
        
        }
        else if(pick_drop=="drop")
        {                
            remOption(document.getElementById("bus_id_drop"));
    		    addOption(document.getElementById("bus_id_drop"),'Select','select');    		    
            var tempstr=result.split(",");
         
            if(tempstr.length>=1)
            {
              if(tempstr[0].length > 3)
              {
                  //alert("tempstr 0 :"+tempstr[0].length);
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
            	        addOption(document.getElementById("bus_id_drop"),tempstr1[1],tempstr1[0]);
                  }
              }
            }
        
        }
        
    
    } 
  
  //---------------- add shift --------------------------
  
  function addShift(pick_drop,result)
    {
         remOption(document.getElementById("shift_id"));
    		 addOption(document.getElementById("shift_id"),'Select','select');    		    
         var tempstr=result.split("#");
         
            if(tempstr.length>=1)
            {
              if(tempstr[0].length > 3)
              {
                  //alert("tempstr 0 :"+tempstr[0].length);
                  for(var i=0;i<tempstr.length;i++)
                  {
                      var tempstr1 = tempstr[i].split(":");
            	        addOption(document.getElementById("shift_id"),tempstr1[1],tempstr1[0]);
                  }
              }
            }
    } 
   ///--------------------------- 
   
   function addSection(result)
   {
      remOption(document.getElementById("section"));
      addOption(document.getElementById("section"),'Select','select');    		    
      var tempstr=result.split(",");
    
      if(tempstr.length>=1)
      {
        if(tempstr[0].length > 3)
        {
            //alert("tempstr 0 :"+tempstr[0].length);
            for(var i=0;i<tempstr.length;i++)
            {
                var tempstr1 = tempstr[i].split(":");
      	        addOption(document.getElementById("section"),tempstr1[1],tempstr1[0]);
            }
        }
      } 
   }
  
  //-------- function to add and remove dropdown feilds
  	function addOption(selectbox,text,value )
			{
			var optn = document.createElement("OPTION");
			optn.text = text;
			optn.value = value;
			selectbox.options.add(optn);
			}		
	function remOption(selectbox)
			{
			
			var i;
				for(i=selectbox.options.length-1;i>=0;i--)
				{
				selectbox.remove(i);
				}		
			
			} 

////////////////// HTTP GET REQUEST ///////////////////////////////////////////
function getXMLHttp()
{
  var xmlHttp

  try
  {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    //Internet Explorer
    try
    {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
      try
      {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e)
      {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}

/*function getXMLHTTP()
{
	http_request=false;
	if (window.XMLHttpRequest)
	{
		http_request = new XMLHttpRequest();
	} 
	else if (window.ActiveXObject) 
	{
		http_request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return http_request;
}*/

	function GetXmlHttpObject()
	{
	 var xmlHttp=null;
	 try
	 {
	 // Firefox, Opera 8.0+, Safari
	 xmlHttp=new XMLHttpRequest();
	 }			 
	 catch (e)
	 {
		  //Internet Explorer
		  try
		  {
		  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		  }
		  catch (e)
		  {
		  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");				 
		  }
	 }
	 return xmlHttp;
	}
	
  function close_popup()
  {
    document.getElementById("blackout_4").style.visibility = "hidden";
    document.getElementById("divpopup_4").style.visibility = "hidden";
    document.getElementById("blackout_4").style.display = "none";
    document.getElementById("divpopup_4").style.display = "none";
  }	
////////////////////// HTTP GET REQUEST CLOSED /////////////////////////////////
