package com.hdfc.report;
import java.util.ArrayList;

import com.hdfc.utils.utility_class;
import com.iespl.gisgraphy.class_pop_junction;

public class report_chauraha {

	public static int halt_flag = 0;	
	public static String xml_date_latest="1900-00-00 00:00:00", datetime_ref="", datetime_cr="", chauraha_detail_str="";
	public static double lat_ref=0.0, lng_ref=0.0, lat_cr=0.0, lng_cr=0.0, CurrentLat = 0.0;
	public static double xml_date_latest_sec=0.0, device_time_sec=0.0, startdate_sec=0.0, enddate_sec=0.0;
	public static double CurrentLong = 0.0;
	public static double firstData = 0;
	public static double distance =0.0, starttime=0.0, stoptime=0.0, halt_dur=0.0;	
	public static int firstdata_flag =0;
	public static int firstdata_flag_chauraha =0;
	public static boolean out_flag=false;
	
	public static void action_report_chauraha(int vehicle_id, String imei, String device_time, String startdate, String enddate, double interval, double lat, double lng, double speed, int data_size, int record_count)
	{
		if(device_time!=null) {	
			xml_date_latest_sec = utility_class.get_seconds(xml_date_latest);
			device_time_sec = utility_class.get_seconds(device_time);
			startdate_sec = utility_class.get_seconds(startdate);
			enddate_sec = utility_class.get_seconds(enddate);
			
			if( (device_time_sec >= startdate_sec) && (device_time_sec <= enddate_sec) && (device_time_sec >= xml_date_latest_sec) && (device_time!="-") ) {
				xml_date_latest = device_time;
				  				
				if(firstdata_flag==0){					
					halt_flag = 0;
					firstdata_flag = 1;
												
					lat_ref = lat;						
					lng_ref = lng;								

					datetime_ref = device_time; 	
				}
				else{							
					//######### CHECK HALT IN 
					lat_cr = lat;																									
					lng_cr = lng;
					datetime_cr = device_time;
					
					distance = utility_class.calculateDistance(lat_ref, lat_cr, lng_ref, lng_cr);
					if(distance>2000){
						distance=0;
						lat_ref = lat_cr;
						lng_ref = lng_cr;
					}					
					
					if(distance > 0.150) {
											
						//####### CHECK RANGE OF CHAURAHA
						chauraha_detail_str = get_chauraha_code(lat_cr, lng_cr);
						String churaha_detail[] = chauraha_detail_str.split(":");
						//###############################
					
						if( (churaha_detail[3]!="") && (!out_flag) ) {
						
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
						}
					}
				}
			}
		}
	}
	

	public static String get_chauraha_code(double lat_cr, double lng_cr) {
		String lat = Double.toString(lat_cr);
		String lng = Double.toString(lng_cr);
		class_pop_junction jct_lat_lng = new class_pop_junction(lat, lng);
		ArrayList<String> values1 = jct_lat_lng.Data();
		/*for(String data : values1){
			System.out.println("data : "+data);
		}*/
		
		//System.out.println(values1.get(0));
		return (values1.get(0));
	}
	
}
