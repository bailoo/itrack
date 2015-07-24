
public class common_xml_element {
	public String va="",vb="",vc="",vd="",ve="",vf="",vg="",vh="",vi="",vj="",vk="",vl="",vm="",vn="",vo="",vp="",vq="",vr="",vs="",vt="",vu="",vv="",vw="",vx="",vy="",vz="",vaa="",vab="";
	public String old_xml_date = "2013-10-26";
	
	public void new_xml_variables(common_xml_element cx)
	{
		//common_xml_element cx = new common_xml_element();
		cx.va="a";
		cx.vb="b";
		cx.vc="c";
		cx.vd="d";
		cx.ve="e";
		cx.vf="f";
		cx.vg="g";
		cx.vh="h";
		cx.vi="i";
		cx.vj="j";
		cx.vk="k";
		cx.vl="l";
		cx.vm="m";
		cx.vn="n";
		cx.vo="o";
		cx.vp="p";
		cx.vq="q";
		cx.vr="r";
		cx.vs="s";
		cx.vt="t";
		cx.vu="u";
		cx.vv="v";
		cx.vw="w";
		cx.vx="x";
		cx.vy="y";
		cx.vz="z";
		cx.vaa="aa";
		cx.vab="ab";		
	}
	
	public void old_xml_variables(common_xml_element cx)
	{
		//common_xml_element cx = new common_xml_element();
		cx.va="msgtype";
		cx.vb="ver";
		cx.vc="fix";
		cx.vd="lat";
		cx.ve="lng";
		cx.vf="speed";
		cx.vg="sts";
		cx.vh="datetime";
		cx.vi="io1";
		cx.vj="io2";
		cx.vk="io3";
		cx.vl="io4";
		cx.vm="io5";
		cx.vn="io6";
		cx.vo="io7";
		cx.vp="io8";
		cx.vq="sig_str";
		cx.vr="sup_v";
		cx.vs="day_max_speed";
		cx.vt="day_max_speed_time";
		cx.vu="last_halt_time";
		cx.vv="vehicleserial";
		cx.vw="vname";
		cx.vx="vnumber";
		cx.vy="vtype";	
		cx.vz="cumdist";
		cx.vaa="running_status";
		cx.vab="cellname";		
	}
	
	public void set_master_variable(String conditional_date, common_xml_element cx)
	{
		//common_xml_element cx = new common_xml_element();
		long old_xml_date_sec = utility_classes.get_seconds(cx.old_xml_date, 1);
		long conditional_xml_date_sec = utility_classes.get_seconds(conditional_date, 1);		
		
		//System.out.println("cx.old_xml_date="+cx.old_xml_date+" ,conditional_date="+conditional_date);
		if(conditional_xml_date_sec < old_xml_date_sec)  /// for sorted xml
		{
			System.out.println("ONE");
			cx.old_xml_variables(cx);						
		}
		else
		{
			System.out.println("TWO");
			cx.new_xml_variables(cx);
		}		
	}
}
