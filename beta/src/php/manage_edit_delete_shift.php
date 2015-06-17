<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $DEBUG=0;
  echo "edit##"; 

	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';  
?>
<br> 
<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;Shift Name&nbsp:&nbsp;
    
    <select name="shift_id" id="shift_id" onchange="show_shift_record(manage1);">
      <option value="select">Select</option>
      <?php
			
			$data=getDetailAllShift($common_id1,$DbConnection);            							
			foreach($data as $dt)
			{
				$shift_id=$dt['shift_id'];
				$shift_name=$dt['shift_name'];				              								 
			    echo '<option value='.$shift_id.'>'.$shift_name.'</option>';
			}
			?>
    </select>
  </td>
</tr>
<tr>                                     					         
       <tr>                          
  <td>
   <div id="display_area" style="display:none">
     <table class="manage_interface"> 
       <tr>
  			<td>Name</td>
  			<td> :</td>
  			<td><input type="text" name="edit_shift_name" id="edit_shift_name" onkeyup="manage_availability(this.value, 'shift')" onmouseup="manage_availability(this.value, 'shift')" onchange="manage_availability(this.value, 'shift')"></td>
  		</tr>   		
  		<tr>
  			<td>Start time</td>
  			<td> :</td>
  			<td>
                   
                   <select name="edit_shift_start_hr" id="edit_shift_start_hr">
                      <option value="-1">Hour</option>
											<option value="00">00</option> <option value="01">01</option>
											<option value="02">02</option> <option value="03">03</option>
											<option value="04">04</option> <option value="05">05</option>
											<option value="06">06</option> <option value="07">07</option>
											<option value="08">08</option> <option value="09">09</option>
											<option value="10">10</option> <option value="11">11</option>
											<option value="12">12</option> <option value="13">13</option>
											<option value="14">14</option> <option value="15">15</option>
											<option value="16">16</option> <option value="17">17</option>
											<option value="18">18</option> <option value="19">19</option>
											<option value="20">20</option> <option value="21">21</option>
											<option value="22">22</option> <option value="23">23</option>
										</select>
										 &nbsp;&nbsp;
										
                   <select name="edit_shift_start_min" id="edit_shift_start_min">
											<option value="-1">Min</option>
                      <option value="00">00</option> <option value="01">01</option>
											<option value="02">02</option> <option value="03">03</option>
											<option value="04">04</option> <option value="05">05</option>
											<option value="06">06</option> <option value="07">07</option>
											<option value="08">08</option> <option value="09">09</option>
											<option value="10">10</option> <option value="11">11</option>
											<option value="12">12</option> <option value="13">13</option>
											<option value="14">14</option> <option value="15">15</option>
											<option value="16">16</option> <option value="17">17</option>
											<option value="18">18</option> <option value="19">19</option>
											<option value="20">20</option> <option value="21">21</option>
											<option value="22">22</option> <option value="23">23</option>
											<option value="24">24</option> <option value="25">25</option>
											<option value="26">26</option> <option value="27">27</option>
											<option value="28">28</option> <option value="29">29</option>
											<option value="30">30</option> <option value="31">31</option> 											
											<option value="32">32</option> <option value="33">33</option>
											<option value="34">34</option> <option value="35">35</option>
											<option value="36">36</option> <option value="37">37</option>
											<option value="38">38</option> <option value="39">39</option>
											<option value="40">40</option> <option value="41">41</option>
											<option value="42">42</option> <option value="43">43</option>
											<option value="44">44</option> <option value="45">45</option>
											<option value="46">46</option> <option value="47">47</option>
											<option value="48">48</option> <option value="49">49</option>
											<option value="50">50</option> <option value="51">51</option> 											
											<option value="52">52</option> <option value="53">53</option>
											<option value="54">54</option> <option value="55">55</option>
											<option value="56">56</option> <option value="57">57</option>
											<option value="58">58</option> <option value="59">59</option>
											
										</select>  
        </td>
  		</tr>
  		<tr>
  			<td>Stop Time</td>
  			<td> :</td>
  			<td>
			             
                   <select name="edit_shift_stop_hr" id="edit_shift_stop_hr">
											<option value="-1">Hour</option>
                      <option value="00">00</option> <option value="01">01</option>
											<option value="02">02</option> <option value="03">03</option>
											<option value="04">04</option> <option value="05">05</option>
											<option value="06">06</option> <option value="07">07</option>
											<option value="08">08</option> <option value="09">09</option>
											<option value="10">10</option> <option value="11">11</option>
											<option value="12">12</option> <option value="13">13</option>
											<option value="14">14</option> <option value="15">15</option>
											<option value="16">16</option> <option value="17">17</option>
											<option value="18">18</option> <option value="19">19</option>
											<option value="20">20</option> <option value="21">21</option>
											<option value="22">22</option> <option value="23">23</option>
										</select>
										 &nbsp;&nbsp;
										
                   <select name="edit_shift_stop_min" id="edit_shift_stop_min">
											<option value="-1">Min</option>
                      <option value="00">00</option> <option value="01">01</option>
											<option value="02">02</option> <option value="03">03</option>
											<option value="04">04</option> <option value="05">05</option>
											<option value="06">06</option> <option value="07">07</option>
											<option value="08">08</option> <option value="09">09</option>
											<option value="10">10</option> <option value="11">11</option>
											<option value="12">12</option> <option value="13">13</option>
											<option value="14">14</option> <option value="15">15</option>
											<option value="16">16</option> <option value="17">17</option>
											<option value="18">18</option> <option value="19">19</option>
											<option value="20">20</option> <option value="21">21</option>
											<option value="22">22</option> <option value="23">23</option>
											<option value="24">24</option> <option value="25">25</option>
											<option value="26">26</option> <option value="27">27</option>
											<option value="28">28</option> <option value="29">29</option>
											<option value="30">30</option> <option value="31">31</option> 											
											<option value="32">32</option> <option value="33">33</option>
											<option value="34">34</option> <option value="35">35</option>
											<option value="36">36</option> <option value="37">37</option>
											<option value="38">38</option> <option value="39">39</option>
											<option value="40">40</option> <option value="41">41</option>
											<option value="42">42</option> <option value="43">43</option>
											<option value="44">44</option> <option value="45">45</option>
											<option value="46">46</option> <option value="47">47</option>
											<option value="48">48</option> <option value="49">49</option>
											<option value="50">50</option> <option value="51">51</option> 											
											<option value="52">52</option> <option value="53">53</option>
											<option value="54">54</option> <option value="55">55</option>
											<option value="56">56</option> <option value="57">57</option>
											<option value="58">58</option> <option value="59">59</option>
										</select>       
      
          </td>
    		</tr>        
      </table>
    </div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_shift('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_shift('delete')"/>
	</td>
</tr>
</table>  

  <?php 
    include_once('availability_message_div.php');
  ?>