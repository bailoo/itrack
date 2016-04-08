<?php
echo'
	<div id="window" style="display:none; position:absolute; z-index:10; left:500px; top:100px; width:490px;height:320px;background-color:#dde3eb; border:1px solid #464f5a;">';
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
	{
		echo'<div style="padding-bottom:8px; width:490px;height:10px; background-color:#718191; border-bottom:1px solid #464f5a;" onMouseDown="beginDrag(this.parentNode, event);">';
	}
	else if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true))
	{	
		echo'<div style="padding-bottom:8px; width:490px;height:20px; background-color:#718191; border-bottom:1px solid #464f5a;" onMouseDown="beginDrag(this.parentNode, event);">';		 
	}     
	echo'<div style="position:absolute; top:1px; left:5px; font-size:10px; color:#FFFFFF;"> LOCATION ON MAP &nbsp;&nbsp;&nbsp;(click to drag!)</div>
			<div style="position:absolute; top:0px; left:472px; float:right;" onClick="this.parentNode.parentNode.style.display = \'none\';">
				<img src="../../images/close_btn.png" border="0"/>
			</div>
		</div>
		<div id="map" style="width:490px; height:300px; position: relative; background-color:background-color: rgb(229, 227, 223);" class="ukseries_div_map">
	
		</div> 
</div>
';

?>