<div style="position:absolute; right:250px; top:6%;z-index:99;" class="form-inline">
<span id="ref_time" style="font-size:11px;color:red;"></span>
&nbsp;&nbsp;<span>
<input type="checkbox" checked id="trail_path"></span>
<span style="font-size:11px;color:green;">
<b> Add Arrow </b>
</span>
&nbsp;&nbsp;
<input type="checkbox" id="trail_path_real"><span style="font-size:11px;color:green;">
<b>Add Trail </b>
</span>
Route(if any):<span id="selected_routes" style="display:none;"></span>
</div>
<?php    
    echo ' <!--MAP DETAIL -->
    <div id="map" style="height:100%;width:100%;"></div>
     <!--MAP DETAIL CLOSED-->
  </TD>  
  
   <TD align="left" id="vlist_col">
      
      <!-- VEHICLE DETAIL -->
      <div id="vdetail" align="left" style="position:relative;height:100%;top:0px;left:0px;">
          <div align="left" id="exampleHeader2"
              style="position:absolute;
                     width:7px;
                     height:100%;
                     top:0px;
                     left:0px;
                     background:#004D96;
                     text-align:center;
                     color:#FFFFFF;"onclick="slideExample2(\'exampleHeader2\', this);">
              <img src="./images/icon/slide_arrow1.png">
          </div>
          
          <div align="left" id="examplePanel2"
              style="position:absolute;                  
                   height:95%;
                   width:95%;
                   top:0px;
                   left:10px;                   
                   overflow:auto;">                          
          </div>        
      </div>
      
      <!-- VEHICLE DETAIL CLOSED--> 
      
    	<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:40%; top:290px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="images/loading_live_vehicles.gif"><!--<img src="images/load_data.gif">-->		
      </p>
      <!--<div id="text" class="module_text">
        <div style="height:210px;"> </div>
         <center>show message</center>
      </div>-->
          
  </TD>';
  
  echo '
  <TD id="text_col" style="display:none;" width="85%" id="map_col" bgcolor="white">
	<div id="text_col_content" style="height:560px;overflow:auto;"> <font style="font-size:15px;color:#008000">  loading text mode..</font></div>
  </TD>';    
?>