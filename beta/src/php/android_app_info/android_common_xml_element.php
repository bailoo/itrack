<?php
global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
global $old_xml_date;
$old_xml_date='2013-10-26';
function new_xml_variables()
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;	
		global $old_xml_date;
	$va="a";
		$vb="b";
		$vc="c";
		$vd="d";
		$ve="e";
		$vf="f";
		$vg="g";
		$vh="h";
		$vi="i";
		$vj="j";
		$vk="k";
		$vl="l";
		$vm="m";
		$vn="n";
		$vo="o";
		$vp="p";
		$vq="q";
		$vr="r";
		$vs="s";
		$vt="t";
		$vu="u";
		$vv="v";
		$vw='w';
		$vx='x';
		$vy='y';
		$vz="z";
		$vaa="aa";
		$vab="ab";
}
function old_xml_variables()
	{	
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;
		$va='msgtype';
		$vb='ver';
		$vc='fix';
		$vd='lat';
		$ve='lng';
		$vf='speed';
		$vg='sts';
		$vh='datetime';
		$vi='io1';
		$vj='io2';
		$vk='io3';
		$vl='io4';
		$vm='io5';
		$vn='io6';
		$vo='io7';
		$vp='io8';
		$vq='sig_str';
		$vr='sup_v';
		$vs='day_max_speed';
		$vt='day_max_speed_time';
		$vu="last_halt_time";
		$vv='vehicleserial';
		$vw='vname';
		$vx='vnumber';
		$vy="vtype";	
		$vz="cumdist";
		$vaa="running_status";
		$vab="cellname";		
	}
	
	function set_master_variable($conditional_date)
	{
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;
		if($conditional_date<$old_xml_date)  /// for sorted xml
		{
			old_xml_variables();						
		}
		else
		{
			new_xml_variables();
		}
	}
?>
