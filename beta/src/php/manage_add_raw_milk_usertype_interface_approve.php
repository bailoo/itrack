<link rel="stylesheet" href="src/css/jquery-ui.css">
<script src="src/js/jquery-1.js"></script>
<script src="src/js/jquery-ui.js"></script>

<input type="hidden" name="tnum"  id="tnum" value="0">
<table  border="1" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse"   width="1290px" >
<tr style="background-color:silver;">
	<!--style="width:30px;font-size: 10px"-->
	<td style="width:3%;font-size: 11px" >SL</td>
	<td style="width:6.5%;font-size: 11px" >LR.No.</td>
	<td style="width:6.5%;font-size: 11px">Vehicle No.</td>	
	<td style="width:6.5%;font-size: 11px">Transporter</td>
	<td style="width:6.5%;font-size: 11px">Email</td>
	<td style="width:6.5%;font-size: 11px">Transp.Mobile</td>
	<td style="width:6.5%;font-size: 11px">Driver Name</td>
	<td style="width:6.5%;font-size: 11px">Driver Mobile</td>
	<td style="width:6.5%;font-size: 11px">Quantity(kg)</td>
	<td style="width:6.5%;font-size: 11px">FAT(%)</td>
	<td style="width:6.5%;font-size: 11px">SNF(%)</td>
	<td style="width:6.5%;font-size: 11px">FAT(kg)</td>
	<td style="width:6.5%;font-size: 11px">SNF(kg)</td>
	<td style="width:6.5%;font-size: 11px">MILK_Ag(Hrs)</td>
	<td style="width:6.5%;font-size: 11px">Disp.Time</td>
	<td style="width:6.5%;font-size: 11px">Target Time</td>
	<td style="width:6.5%;font-size: 11px" >Plant</td>
	<td style="width:6.5%;font-size: 11px" >ChillingPlant</td>
	<td style="width:6.5%;font-size: 11px">TankerType</td>
	<!--<td style="width:6.5%;font-size: 11px" >Action</td> -->
</tr>

<tr>
	<td colspan="19">
		<table width=1290px >
			<tr>
				<td><input type="text" name="num" id="num:0" value="0" style="width:30px;" readonly />
				<input type="hidden" name="sno_id" id="sno_id:0" value=""  />
				</td>
				<td><input type="text" name="lrno" id="lrno:0" value="" style="width:70px;" placeholder="LRNO"  /></td>
				<!--<input type="text" name="vehno" id="vehno0" value="" style="width:80px;" placeholder="VEHICLENO"  autocomplete="off" class="ui-autocomplete-input" onkeydown="dk(this.id);" />-->
				<td><input type="text" name="vehno" id="vehno:0" value="" style="width:70px;" placeholder="VEHICLENO" onclick="javascript:show_vehicle_list(this.id)" onfocus="javascript:show_vehicle_list(this.id)" readonly /></td>
				<td>					
					<select name="transporter" id="transporter:0" style="width:70px;">
						<?php							
							echo $option_transporter;							
						?>
					</select>
				</td>
				<td><input type="text" name="email" id="email:0" value="" style="width:70px;" placeholder="EMAIL" /></td>	
				<td><input type="text" name="mobile" id="mobile:0" value="" style="width:70px;" placeholder="MOBILE" />	</td>
				<td><input type="text" name="driver" id="driver:0" value="" style="width:70px;" placeholder="DRIVERNAME" />	</td>
				<td><input type="text" name="drivermobile" id="drivermobile:0" value="" style="width:70px;" placeholder="DRIVERMOBILE" /></td>
				<td><input type="text" name="qty" id="qty:0" value="" style="width:70px;" placeholder="QTY" onkeyup="javascript:put_fat_snf_kg(this.value,this.id);" />	</td>	
				<td><input type="text" name="fat_per" id="fat_per:0" value="" style="width:70px;" placeholder="FAT%" onkeyup="javascript:put_fat_kg(this.value,this.id);" /></td>	
				<td><input type="text" name="snf_per" id="snf_per:0" value="" style="width:70px;" placeholder="SNF%" onkeyup="javascript:put_snf_kg(this.value,this.id);" /></td>	
				<td><input type="text" name="fat_kg" id="fat_kg:0" value="" style="width:70px;" placeholder="FAT(KG)" readonly /></td>	
				<td><input type="text" name="snf_kg" id="snf_kg:0" value="" style="width:70px;" placeholder="SNF(KG)" readonly /></td>	
				<td><input type="text" name="milk_age" id="milk_age:0" value="" style="width:70px;" placeholder="MilkAge(Hr)" /></td>
				<td><input type="text" name="disp_time" id="disp_time:0" value="" style="width:70px;" placeholder="DISP TIME" onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"  readonly /></td>	
		
				<td><input type="text" name="target_time" id="target_time:0" value="" style="width:70px;" placeholder="TARG.TIME"  onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"  readonly /></td>	
		
				<td>
					<!--<div style="width: 70px; overflow: hidden;" >-->

					<select name="plant" id="plant:0" style="width:70px;">
						<?php
							$i=0; 
							foreach($final_plant_list as $plantlist){
								if($i==0){
									echo"<option value=''></option>";
								}
								else
								{
								echo"<option value=".$plantlist." >".$final_plant_name_list[$i]."(".$plantlist.")</option>";
								}
								$i++;
							}
						?>
					</select>
					<!--<div>-->
				</td>
				<td>
					<!--<div style="width: 70px; overflow: hidden;" >-->

					<select name="chillplant" id="chillplant:0" style="width:70px;">
						<?php
							$i=0; 
							foreach($final_chillplant_list as $chillplantlist){
								if($i==0){
									if($default_customer_no==$chillplantlist)
									{
										echo"<option value=".$chillplantlist." selected></option>";
									}
									else
									{
										echo"<option value=''></option>";
									}
								}
								else{
									if($default_customer_no==$chillplantlist)
									{
										echo"<option value=".$chillplantlist." selected>".$final_chillplant_name_list[$i]."(".$chillplantlist.")</option>";
									}
									else{
										echo"<option value=".$chillplantlist." >".$final_chillplant_name_list[$i]."(".$chillplantlist.")</option>";
									}
								}
								$i++;
							}
						?>
					</select>
					<!--<div>-->
				</td>
				<td>
					<select name="tankertype" id="tankertype:0" style="width:70px;">
						<option value='1' selected >Production Type</option>
						<option value='2'>Conversion Type</option>
					</select>					
				</td>
				<!--<input type="text" name="plant" id="plant0" value="" style="width:70px;" placeholder="PLANT"  />-->	
		
				<input type="hidden" name="docketflag" id="docketflag:0" value="1" style="width:70px;"  />	
		
				<input type="hidden" style="width:70px;"  id="addfield1" onclick="javascript:addfield();" value="ADD"  />
				<!--<td><input type="button" style="width:70px;"  id="addfield1" onclick="javascript:addfield();" value="ADD"  /></td>-->
				
			</tr>
		</table>
		<span id="writeroot"></span>
      </td>
</tr>

    

</table>

<div id="readroot" name="readroot" style="display:none;">
	<input name="num" id="num:" value="" style="width:30px;" readonly /><input type="hidden" name="sno_id" id="sno_id:" value=""  />
	<input type="text" name="lrno" id="lrno:" value="" style="width:70px;" placeholder="LRNO" />
	<!--<input type="text" name="vehno" id="vehno" value="" style="width:80px;" placeholder="VEHICLENO"  autocomplete="off" class="ui-autocomplete-input" onkeydown="dk(this.id);" />-->
	<input type="text" name="vehno" id="vehno:" value="" style="width:70px;" placeholder="VEHICLENO" onclick="javascript:show_vehicle_list(this.id)" onfocus="javascript:show_vehicle_list(this.id)" readonly />
	<select name="transporter" id="transporter:" style="width:70px;">
		<?php							
			echo $option_transporter;							
		?>
	</select>
						
	
	<input type="text" name="email" id="email:" value="" style="width:70px;" placeholder="EMAIL" />	
	<input type="text" name="mobile" id="mobile:" value="" style="width:70px;" placeholder="MOBILE" />
	
	<input type="text" name="driver" id="driver:" value="" style="width:70px;" placeholder="DRIVERNAME" />	
	<input type="text" name="drivermobile" id="drivermobile:" value="" style="width:70px;" placeholder="DRIVERMOBILE" />
	
	<input type="text" name="qty" id="qty:" value="" style="width:70px;" placeholder="QTY" onkeyup="javascript:put_fat_snf_kg(this.value,this.id);" />		
	<input type="text" name="fat_per" id="fat_per:" value="" style="width:70px;"  placeholder="FAT%" onkeyup="javascript:put_fat_kg(this.value,this.id);" />	
	<input type="text" name="snf_per" id="snf_per:" value="" style="width:70px;" placeholder="SNF%" onkeyup="javascript:put_snf_kg(this.value,this.id);" />	
	<input type="text" name="fat_kg" id="fat_kg:" value="" style="width:70px;" placeholder="FAT(KG)"  readonly />	
	<input type="text" name="snf_kg" id="snf_kg:" value="" style="width:70px;" placeholder="SNF(KG)" readonly />
	<input type="text" name="milk_age" id="milk_age:" value="" style="width:70px;" placeholder="MilkAge(Hr)" />
	<input type="text" name="disp_time" id="disp_time:"  value="" style="width:70px;" placeholder="DISP TIME"  onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"  readonly />	
	
	<input type="text" name="target_time" id="target_time:" value="" style="width:70px;" placeholder="TARG.TIME"  onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"   readonly />	
	
	

	<select name="plant" id="plant:" style="width:70px; position:relative; z-index:+1;"
   onactivate="this.style.width='auto';"
   onchange="this.blur();"
   onblur="this.style.width='70px';">
		<?php 
			$i=0;
			foreach($final_plant_list as $plantlist){
				if($i==0){
					echo"<option value=''></option>";
				}
				else{
					echo"<option value=".$plantlist.">".$final_plant_name_list[$i]."(".$plantlist.")</option>";
				}
				//echo"<option value=".$plantlist.">".$final_plant_list[$i]."(".$plantlist.")</option>";
				$i++;
			}
		?>
	</select>
	<select name="chillplant" id="chillplant:" style="width:70px; position:relative; z-index:+1;"
   onactivate="this.style.width='auto';"
   onchange="this.blur();"
   onblur="this.style.width='70px';">
		<?php 
			$i=0;
			foreach($final_chillplant_list as $chillplantlist){
				if($i==0){
					echo"<option value=''></option>";
				}
				else
				{
					echo"<option value=".$chillplantlist.">".$final_chillplant_name_list[$i]."(".$chillplantlist.")</option>";
				}
				//echo"<option value=".$plantlist.">".$final_plant_list[$i]."(".$plantlist.")</option>";
				$i++;
			}
		?>
	</select>
	<select name="tankertype" id="tankertype:" style="width:70px; position:relative; z-index:+1;">
			<option value='1' selected>Production Type</option>
			<option value='2'>Conversion Type</option>
		</select>	
	<!--<input type="text" name="plant" id="plant" value="" style="width:70px;" placeholder="PLANT" autocomplete="off" class="ui-autocomplete-input" onkeydown="plant_get(this.id);" />-->
	<input type="hidden" name="docketflag" id="docketflag:" value="1" style="width:70px;"  />
	
	<input type="hidden" style="width:70px;" name="butt" id="butt:" value="" onclick="this.parentNode.parentNode.removeChild(this.parentNode);lessFieldswithTransporter(this.id);" />
	
	<!--<input type="button" style="width:70px;" name="butt" id="butt:" value="" onclick="this.parentNode.parentNode.removeChild(this.parentNode);lessFieldswithTransporter(this.id);" />-->
</tr>
</div>




<input type="hidden" name="offset_lrno" id="offset_lrno"  />
<input type="hidden" name="offset_vehno" id="offset_vehno"  />
<input type="hidden" name="offset_transporter" id="offset_transporter"  />
<input type="hidden" name="offset_email" id="offset_email"  />
<input type="hidden" name="offset_mobile" id="offset_mobile"  />

<input type="hidden" name="offset_driver" id="offset_driver"  />
<input type="hidden" name="offset_drivermobile" id="offset_drivermobile"  />

<input type="hidden" name="offset_qty" id="offset_qty"  />
<input type="hidden" name="offset_fat_per" id="offset_fat_per"  />
<input type="hidden" name="offset_snf_per" id="offset_snf_per"  />
<input type="hidden" name="offset_fat_kg" id="offset_fat_kg"  />

<input type="hidden" name="offset_snf_kg" id="offset_snf_kg"  />
<input type="hidden" name="offset_milk_age" id="offset_milk_age"  />

<input type="hidden" name="offset_disp_time" id="offset_disp_time"  />
<input type="hidden" name="offset_target_time" id="offset_target_time"  />

<input type="hidden" name="offset_plant" id="offset_plant"  />
<input type="hidden" name="offset_chillplant" id="offset_chillplant"  />
<input type="hidden" name="offset_docketflag" id="offset_docketflag"  />
<input type="hidden" name="offset_tankertype" id="offset_tankertype"  />
<input type="hidden" name="offset_sno_id" id="offset_sno_id"  />
