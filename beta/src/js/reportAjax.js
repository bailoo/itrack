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

			if(typeof String.prototype.trim !== 'function') 
			{
				String.prototype.trim = function() 
				{
					return this.replace(/^\s+|\s+$/g, '');
				}
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

					if(result1[0]=="report_vehicle_details")
					{
						document.getElementById('vehicle_details').style.display='';
						document.getElementById('vehicle_details').innerHTML =result1[1];
						//document.getElementById("enter_button").disabled=false;             
					}
					else if(result1[0].trim()=="portal_vehicle_information")
					{                          
						document.getElementById('portal_vehicle_information').style.display ="";			 			  
						document.getElementById('portal_vehicle_information').innerHTML = result1[1];
						//alert("result2="+result1[2]);
						setCombo(result1[2])	
					}	

					else if(result1[0].trim()=="reportPrevPage")
					{
						//alert("result="+result1[1]);
						hideManageLoadingMessage();
						document.getElementById('rightMenu').style.display="none";
						document.getElementById('bodyspan').style.display="none";			
						document.getElementById('reportPrevPage').style.display="";
						document.getElementById('reportPrevPage').innerHTML=result1[1];	
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
                                        else if (result1[0].trim()=="edit")
                                        {
                                            hideManageLoadingMessage();
                                            document.getElementById('edit_div').style.display =""; 
                                            document.getElementById('edit_div').innerHTML = result1[1]; 
                                        }
					else 
					{
                                            //alert("result="+result);
                                            hideManageLoadingMessage();
                                            document.getElementById('reportPrevPage').style.display="none";
                                            document.getElementById('rightMenu').style.display="";
                                            document.getElementById('bodyspan').style.display="";
                                            document.getElementById('bodyspan').innerHTML =result;
					}                  
				}
                                
				else 
				{
					alert('There was a problem with the request.');
				}

				var table3Filters = 
				{
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
