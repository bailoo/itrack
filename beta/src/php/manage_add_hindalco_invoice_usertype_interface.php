<link rel="stylesheet" href="src/css/jquery-ui.css">
<script src="src/js/jquery-1.js"></script>
<script src="src/js/jquery-ui.js"></script>

<input type="hidden" name="tnum"  id="tnum" value="0">
<table  border="1" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse"  width="870px" >
<tr style="background-color:silver;">
	<!--style="width:30px;font-size: 10px"-->
	<td style="width:3%;font-size: 11px" >SL</td>
	<td style="width:6.5%;font-size: 11px" >LR.No.</td>
	<td style="width:6.5%;font-size: 11px">Vehicle No.</td>
	<td style="width:6.5%;font-size: 11px">Email</td>
	<td style="width:6.5%;font-size: 11px">Transp.Mobile</td>
	<td style="width:6.5%;font-size: 11px">Driver Name</td>
	<td style="width:6.5%;font-size: 11px">Driver Mobile</td>
	<td style="width:6.5%;font-size: 11px">Quantity</td>
	<td style="width:6.5%;font-size: 11px">CUSTOMER</td>	
	<td style="width:6.5%;font-size: 11px">Disp.Time</td>
	<td style="width:6.5%;font-size: 11px">Target Time</td>
	<td style="width:6.5%;font-size: 11px" >Product type</td>
	<td style="width:6.5%;font-size: 11px" >Action</td> 
</tr>

<tr>
	<td colspan="13">
		<table width=870px >
			<tr>
				<td><input type="text" name="num" id="num:0" value="0" style="width:30px;" readonly /></td>
				<td><input type="text" name="lrno" id="lrno:0" value="" style="width:70px;" placeholder="LRNO"  /></td>				
				<td><input type="text" name="vehno" id="vehno:0" value="" style="width:70px;" placeholder="VEHICLENO" onclick="javascript:show_vehicle_list_hindalco(this.id)" onfocus="javascript:show_vehicle_list(this.id)" readonly /></td>
		
				<td><input type="text" name="email" id="email:0" value="" style="width:70px;" placeholder="EMAIL" /></td>	
				<td><input type="text" name="mobile" id="mobile:0" value="" style="width:70px;" placeholder="MOBILE" />	</td>
				<td><input type="text" name="driver" id="driver:0" value="" style="width:70px;" placeholder="DRIVERNAME" />	</td>
				<td><input type="text" name="drivermobile" id="drivermobile:0" value="" style="width:70px;" placeholder="DRIVERMOBILE" /></td>
				<td><input type="text" name="qty" id="qty:0" value="" style="width:70px;" placeholder="QTY"  />	</td>	
				<td><input type="text" name="customer" id="customer:0" value="" style="width:70px;" placeholder="CUSTOMER" /></td>	
						
				<td><input type="text" name="disp_time" id="disp_time:0" value="" style="width:70px;" placeholder="DISP TIME" onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"  readonly /></td>	
		
				<td><input type="text" name="target_time" id="target_time:0" value="" style="width:70px;" placeholder="TARG.TIME"  onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"  readonly /></td>	
		
				<td>
					<!--<div style="width: 70px; overflow: hidden;" >-->

					<select name="product_type" id="product_type:0" style="width:70px;">
						<?php
							$i=0; 
							foreach($final_product_type_list as $productlist){
								echo"<option value='".$productlist."' >".$productlist."</option>";
								$i++;
							}
						?>
					</select>
					<!--<div>-->
				</td>
				<td><input type="button" style="width:70px;"  id="addfield1" onclick="javascript:addfield_Hindalco();" value="ADD"  /></td>
				
			</tr>
		</table>
		<span id="writeroot"></span>
      </td>
</tr>

    

</table>

<div id="readroot" name="readroot" style="display:none;">
	<input name="num" id="num:" value="" style="width:30px;" readonly />
	<input type="text" name="lrno" id="lrno:" value="" style="width:70px;" placeholder="LRNO" />
	<!--<input type="text" name="vehno" id="vehno" value="" style="width:80px;" placeholder="VEHICLENO"  autocomplete="off" class="ui-autocomplete-input" onkeydown="dk(this.id);" />-->
	<input type="text" name="vehno" id="vehno:" value="" style="width:70px;" placeholder="VEHICLENO" onclick="javascript:show_vehicle_list(this.id)" onfocus="javascript:show_vehicle_list(this.id)" readonly />

	<input type="text" name="email" id="email:" value="" style="width:70px;" placeholder="EMAIL" />	
	<input type="text" name="mobile" id="mobile:" value="" style="width:70px;" placeholder="MOBILE" />
	
	<input type="text" name="driver" id="driver:" value="" style="width:70px;" placeholder="DRIVERNAME" />	
	<input type="text" name="drivermobile" id="drivermobile:" value="" style="width:70px;" placeholder="DRIVERMOBILE" />
	
	<input type="text" name="qty" id="qty:" value="" style="width:70px;" placeholder="QTY"  />		
	
	<input type="text" name="customer" id="customer:" value="" style="width:70px;" placeholder="CUSTOMER" />
	
	<input type="text" name="disp_time" id="disp_time:"  value="" style="width:70px;" placeholder="DISP TIME"  onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"  readonly />	
	
	<input type="text" name="target_time" id="target_time:" value="" style="width:70px;" placeholder="TARG.TIME"  onclick="javascript:NewCal_SD(this.id,'yyyymmdd',true,24);"   readonly />	
	
	

	<select name="product_type" id="product_type:" style="width:70px;">
		<?php 
			
			$i=0; 
			foreach($final_product_type_list as $productlist){
				echo"<option value='".$productlist."' >".$productlist."</option>";
				$i++;
			}
		?>
	</select>
	
	<input type="button" style="width:70px;" name="butt" id="butt:" value="" onclick="this.parentNode.parentNode.removeChild(this.parentNode);lessFields_Hindalco(this.id);" />
</tr>
</div>




<input type="hidden" name="offset_lrno" id="offset_lrno"  />
<input type="hidden" name="offset_vehno" id="offset_vehno"  />
<input type="hidden" name="offset_email" id="offset_email"  />
<input type="hidden" name="offset_mobile" id="offset_mobile"  />

<input type="hidden" name="offset_driver" id="offset_driver"  />
<input type="hidden" name="offset_drivermobile" id="offset_drivermobile"  />

<input type="hidden" name="offset_qty" id="offset_qty"  />


<input type="hidden" name="offset_customer" id="offset_customer"  />
<input type="hidden" name="offset_disp_time" id="offset_disp_time"  />
<input type="hidden" name="offset_target_time" id="offset_target_time"  />

<input type="hidden" name="offset_product_type" id="offset_product_type"  />


