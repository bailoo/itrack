<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('get_group.php');
  $group_cnt=0; 
  $root=$_SESSION['root'];  
  echo "add##";
  $DEBUG=0;    
  echo'<table align="center">
        <tr>
          <td>
              <fieldset class=\'assignment_manage_fieldset\'>
                <legend>
                    <strong>Group</strong>
                </legend>
                <table border="0" class="manage_interface">
					';GetGroup($root);echo'</table>
              </fieldset>	
          </td>
        </tr>
      </table>';
 ?>  
	<br>
	<fieldset class="report_fieldset">
	<legend><strong>Add Shift</strong></legend>
	<table border="0" class="manage_interface">
			<!--
    <tr>
			<td>Name</td>
			<td> :</td>
			<td><input type="text" name="add_school_name" id="add_school_name" onkeyup="manage_availability(this.value, 'school')" onmouseup="manage_availability(this.value, 'school')" onchange="manage_availability(this.value, 'school')"></td>
		</tr>
	
		<tr>
			<td>Coord</td>
			<td> :</td>
			<td><textarea readonly="readonly" style="width:350px;height:60px" name="route_coord" id="route_coord" onclick="javascript:showCoordinateInterface('route');"></textarea></td>	
		</tr>
		-->
		<tr>
			<td>Name</td>
			<td> :</td>
			<td><input type="text" name="add_shift_name" id="add_shift_name" onkeyup="manage_availability(this.value, 'shift')" onmouseup="manage_availability(this.value, 'shift')" onchange="manage_availability(this.value, 'shift')"></td>
		</tr>   		
		<tr>
			<td>Start time</td>
			<td> :</td>
			<td>
                   
                   <select name="add_shift_start_hr" id="add_shift_start_hr">
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
										
                   <select name="add_shift_start_min" id="add_shift_start_min">
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
			             
                   <select name="add_shift_stop_hr" id="add_shift_stop_hr">
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
										
                   <select name="add_shift_stop_min" id="add_shift_stop_min">
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
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_shift('add')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
	</table>
	</fieldset>
	<?php
		include_once('availability_message_div.php');
	?>

  