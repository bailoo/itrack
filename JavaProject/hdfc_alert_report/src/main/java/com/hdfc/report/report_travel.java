package com.hdfc.report;

import com.hdfc.utils.utility_class;

public class report_travel {
	
	public static int halt_flag =0,firstdata_flag=0, start_point_display =0;
	public static long date_secs1 = 0, date_secs2 =0, starttime =0, stoptime =0, halt_dur =0;
	public static String xml_date_latest ="1900-00-00 00:00:00", datetime_ref ="", datetime_cr ="", arrivale_time ="", depature_time ="", datetime_S ="", datetime_E = "", datetime_travel_start = "", last_time="", last_time1 ="", speeed_data_valid_time = "", datetime_travel_end ="";
	public static int firstdata_flag_travel =0, firstdata_flag_halt =0;
	public static double lat_ref =0.0, lng_ref =0.0, lat_cr =0.0, lng_cr =0.0, lat_E=0.0, lng_E=0.0, CurrentLat = 0.0, CurrentLong = 0.0, LastLat = 0.0, LastLong = 0.0, lat_S=0.0, lng_S=0.0, latlast =0.0, lnglast = 0.0, max_speed=0.0, lat_travel_start =0.0, lng_travel_start = 0.0, distance_incriment =0.0;
	public static boolean haltFlag = false;
	public static double xml_date_latest_sec = 0.0, device_time_sec =0.0, startdate_sec =0.0, enddate_sec =0.0, tmp_time_diff=0.0, tmp_time_diff1=0.0, distance1=0.0;
	public static double tmp_speed=0.0, tmp_speed1=0.0, distance_travel=0.0, distance_total=0.0, distance_incrimenttotal=0.0, datetime_diff=0.0, lat_travel_end=0.0, lng_travel_end=0.0;
	
	
	public static void action_report_travel(int vehicle_id, String imei, String device_time, String startdate, String enddate, double datetime_threshold, double lat, double lng, double speed, int data_size, int record_count) {

		if(device_time!=null) {

			xml_date_latest_sec = utility_class.get_seconds(xml_date_latest);
			device_time_sec = utility_class.get_seconds(device_time);
			startdate_sec = utility_class.get_seconds(startdate);
			enddate_sec = utility_class.get_seconds(enddate);
			
			if( (device_time_sec >= startdate_sec) && (device_time_sec <= enddate_sec) && (device_time_sec >= xml_date_latest_sec) && (device_time!="-") ) {
			
				xml_date_latest = device_time;
				
				if(firstdata_flag==0) {                                
					firstdata_flag = 1;
					haltFlag= true;
					distance_travel=0;                                    
	
					lat_S = lat;
					lng_S = lng;
					datetime_S = device_time;
					datetime_travel_start = datetime_S;              		
					lat_travel_start = lat_S;
					lng_travel_start = lng_S;       
					start_point_display =0;                  
					last_time1 = device_time;
					latlast = lat;
					lnglast =  lng;  
					max_speed	=0.0;								
				}           	              	
				else {           
					lat_E = lat;
					lng_E = lng; 
					datetime_E = device_time; 					
					distance_incriment = utility_class.calculateDistance(lat_S, lat_E, lng_S, lng_E);
					tmp_time_diff1 = utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time1) / 3600;               
					
					distance1 = utility_class.calculateDistance(latlast, lat_E, lnglast, lng_E);					 
					tmp_time_diff = (utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time)) / 3600;
					
					if(tmp_time_diff1>0) {
						tmp_speed = distance_incriment / tmp_time_diff;
						tmp_speed1 = (distance1) / tmp_time_diff1;
					}
					else {
						tmp_speed1 = 1000.0; //very high value
					}
					                                               
					if(tmp_speed<300.0) {
						speeed_data_valid_time = device_time;
					}
					
					tmp_time_diff = (utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time)) / 3600;
					
					if(( utility_class.get_seconds(device_time) - utility_class.get_seconds(speeed_data_valid_time) )>300) {
						lat_S = lat_E;
						lng_S = lng_E;
						last_time = device_time;
					}

					last_time1 = device_time;
					latlast = lat_E;
					lnglast =  lng_E;
					//echo"maxspeed=".$max_speed."speed=".$speed."<br>";
					if(max_speed < speed) {
						max_speed = speed;
					}
					
													
					if(tmp_speed<300.0 && tmp_speed1<300.0 && distance_incriment>0.1 && tmp_time_diff>0.0 && tmp_time_diff1>0) {
						if(haltFlag) {
							datetime_travel_start = datetime_E;
							lat_travel_start = lat_E;
							lng_travel_start = lng_E;
							distance_travel = 0.0;
							distance_total = 0.0;
							max_speed = 0.0;
							haltFlag = false;
						}
						distance_total += distance_incriment;
						distance_travel += distance_incriment;
						lat_S = lat_E;
						lng_S = lng_E;
						datetime_S = datetime_E;
						
						//$distance_incrimenttotal += $distance_incriment;
						// echo $datetime_E . " -- " . $lat_E .",". $lng_E . "\tDelta Distance = " . $distance_incriment . "\tTotal Distance = " . $distance_total . "\n";
					}
										
					datetime_diff = utility_class.get_seconds(datetime_E) - utility_class.get_seconds(datetime_S);
					        
					if((datetime_diff > datetime_threshold) && (!haltFlag)) {
						
						datetime_travel_end = datetime_S;
					
						//echo "start_date1=".$datetime_travel_start."end_date1=".$datetime_travel_end."<br>";
						lat_travel_end = lat_S;
						lng_travel_end = lng_S;
						newTravel(vehicle_id, imei, datetime_travel_start, datetime_travel_end, distance_travel, lat_travel_start, lng_travel_start, lat_travel_end, lng_travel_end, distance_travel,max_speed);
						haltFlag = true;
						//j=0;
					}
					
					if(record_count == data_size) {
						
						if(haltFlag==false) {
							datetime_travel_end = datetime_E;
							lat_travel_end = lat_S;
							lng_travel_end = lng_S;
							//$max_speed = max($speed_arr);
							//$max_speed = round($max_speed,2);
							newTravel(vehicle_id, imei, datetime_travel_start, datetime_travel_end, distance_travel, lat_travel_start, lng_travel_start, lat_travel_end, lng_travel_end, distance_travel,max_speed);
						}
					}
				}
			} 
		}
	}
	
	
	public static void newTravel(int vehicle_id, String imei, String datetime_S, String datetime_E, double distance, double lat_travel_start, double lng_travel_start, double lat_travel_end, double lng_travel_end, double distance_travel, double max_speed)
	{
		double travel_dur =  utility_class.get_seconds(datetime_E) - utility_class.get_seconds(datetime_S);                                                    
		//hms = secondsToTime(travel_dur);
		String travel_time = utility_class.get_hms(travel_dur);
		//travel_time = hms[h].":".hms[m].":".hms[s];
		//echo "avg_speed=".$distance_travel."travel_dur=".$travel_dur."<br>";
		double avg_speed = distance_travel/(travel_dur/3600);
		distance_travel = utility_class.roundTwoDecimals(distance_travel);
		avg_speed = utility_class.roundTwoDecimals(avg_speed);
		//echo "avg_speed=".$avg_speed."<br>";
		if(max_speed < avg_speed)
		{
			max_speed = avg_speed;
		}

	//	total_travel = "\n< marker imei=\"".vserial."\" vname=\"".vname."\" time1=\"".datetime_S."\" time2=\"".datetime_E."\" lat_start=\"".lat_travel_start."\" lng_start=\"".lng_travel_start."\" lat_end=\"".lat_travel_end."\" lng_end=\"".lng_travel_end."\" distance_travelled=\"".distance_travel."\" travel_time=\"".travel_time."\" max_speed=\"".max_speed."\" avg_speed=\"".avg_speed."\"/>";						          						

	} 	
}
