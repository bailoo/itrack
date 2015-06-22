package com.hdfc.report;

import com.hdfc.utils.utility_class;

public class report_turning_violation {

	public static int halt_flag = 0;	
	public static String xml_date_latest="1900-00-00 00:00:00", datetime_ref="", datetime_cr="", road_code="", last_time="", last_time1="";
	public static double lat_ref=0.0, lng_ref=0.0, lat_cr=0.0, lng_cr=0.0, CurrentLat = 0.0, latlast=0.0, lnglast=0.0, tmp_speed=0.0, tmp_speed1=0.0;
	public static double xml_date_latest_sec=0.0, device_time_sec=0.0, startdate_sec=0.0, enddate_sec=0.0;
	public static double CurrentLong = 0.0, angle=0.0, tmp_time_diff1=0.0, tmp_time_diff=0.0;
	public static double distance =0.0, distance1=0.0, starttime=0.0, stoptime=0.0, halt_dur=0.0;	
	public static int firstdata_flag =0;
	public static boolean out_flag=false;
	
	//IF ANGLE > 30 DEG && Speed > 10 Km/hr
	public static void action_report_truning_violation(int vehicle_id, String imei, String device_time, String startdate, String enddate, double interval, double lat, double lng, double speed, int data_size, int record_count)
	{
		if(device_time!=null) {	
			System.out.println(" vehicle_id="+vehicle_id+" imei="+imei+" device_time="+device_time+" startdate="+startdate+" enddate="+enddate+" interval="+interval+" lat="+lat+" lng="+lng+" speed="+speed+" data_size="+data_size+" record_count="+record_count);
			try{
				xml_date_latest_sec = utility_class.get_seconds(xml_date_latest);
				//System.out.println("xml_date_latest_sec="+xml_date_latest_sec);
				device_time_sec = utility_class.get_seconds(device_time);
				//System.out.println("device_time_sec="+device_time_sec);
				startdate_sec = utility_class.get_seconds(startdate);
				//System.out.println("startdate_sec="+startdate_sec);
				enddate_sec = utility_class.get_seconds(enddate);
				//System.out.println("enddate_sec="+enddate_sec);			
			}catch(Exception ed) {System.out.println(ed.getMessage());}
			System.out.println("ValidDeviceTime Found2");
			
			if( (device_time_sec >= startdate_sec) && (device_time_sec <= enddate_sec) && (device_time_sec >= xml_date_latest_sec) && (device_time!="-") ) {
				xml_date_latest = device_time;
				  				
				//System.out.println("Valid DateRange Found");
				
				if(firstdata_flag==0){					
					//System.out.println("InFirstFlag");
					halt_flag = 0;
					firstdata_flag = 1;
												
					lat_ref = lat;						
					lng_ref = lng;								

					datetime_ref = device_time; 	
				}
				else{						
					//######### CHECK HALT IN 
					//System.out.println("InNext");
					lat_cr = lat;																									
					lng_cr = lng;
					datetime_cr = device_time;
					
					//####### CHECK RANGE OF CHAURAHA
					angle = get_turning_angle(lat_ref, lng_ref, lat_cr, lng_cr);
					//###############################
					
					if(angle > 30) {
						
						System.out.println("\nDevice_time="+device_time+" ,Angle="+angle+" ,lat_ref="+lat_ref+" ,lng_ref="+lng_ref+", lat_cr="+lat_cr+" ,lng_cr="+lng_cr);
						/*distance = utility_class.calculateDistance(lat_ref, lat_cr, lng_ref, lng_cr);
						if(distance>2000){
							distance=0;
							lat_ref = lat_cr;
							lng_ref = lng_cr;
						}
						
						//tmp_time_diff1 = utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time1) / 3600;					
						//distance1 = utility_class.calculateDistance(latlast, lat_cr, lnglast, lng_cr);
						
						//echo "<br>latlast=".$latlast." ,lat2=".$lat2." ,lnglast=".$lnglast." ,lng2=".$lng2." ,distance1=".$distance1." , datetime=".$datetime."<br>";
						
						tmp_time_diff = (utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time)) / 3600;
						
						if(tmp_time_diff1>0) {									
							tmp_speed = ((double) (distance)) / tmp_time_diff;
							tmp_speed1 = ((double) (distance1)) / tmp_time_diff1;
						}
						else {
							tmp_speed1 = 1000.0; //very high value
						}*/						
					}
					
					//last_time = device_time;
					//latlast = lat_cr;
					//lnglast =  lng_cr;
					
					/*if(distance > 0.150) {
						//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr." datetime_cr ".$datetime_cr." datetime_ref ".$datetime_ref."<br>";
						if (halt_flag == 1) {				
							//echo "<br>In Halt1";
							starttime = utility_class.get_seconds(datetime_ref);
							stoptime = utility_class.get_seconds(datetime_cr);
							//echo "<br>".$starttime." ,".$stoptime;
							halt_dur =  (stoptime - starttime);	
							out_flag = true;
						}   //IF HALT FLAG
						lat_ref = lat_cr;
						lng_ref = lng_cr;
						datetime_ref= datetime_cr;

						halt_flag = 0;
					}
					
					else if( (utility_class.get_seconds(datetime_cr) - utility_class.get_seconds(datetime_ref)>60) && (halt_flag != 1)) {            			
						//echo "<br>normal flag set "." datetime_cr ".$datetime_cr."<br>";
						
						halt_flag = 1;
					}	*/				
				}
			}
		}
	}
	
	
	public static double get_turning_angle(double lat1, double lng1, double lat2, double lng2)
	{			
		/*double lat1 = 26.448798555558366;	//prev
		double lng1 = 80.3343915939331;
	
		double lat2 = 26.4522950421482;		//next
		double lng2 = 80.33473491668701;*/	
	
		System.out.println("lat1="+lat1+" ,lng1="+lng1+",lat2="+lat2+",lng2="+lng2);
		double yaxis = (lat1 + lat2)/2;
		double xaxis = (lng1 + lng2)/2;
				
		double angle_t = Math.atan( (lat2-lat1)/(lng2-lng1) );
		double angle_deg = 360 * angle_t/(2 * Math.PI);
	
		if((lng2-lng1)<0)
		{
				angle_deg = 180 + angle_deg;
		}
		else if((lat2-lat1)<0)
		{
				angle_deg = 360 + angle_deg;
		}
	
		System.out.println("Angle="+angle_deg);
		return angle_deg;
	}	
}
