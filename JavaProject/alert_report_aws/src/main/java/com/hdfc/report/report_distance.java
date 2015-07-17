package com.hdfc.report;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Date;

import com.hdfc.utils.utility_class;

public class report_distance {
	
	public static double CurrentLat = 0.0, CurrentLong = 0.0, LastLat = 0.0, LastLong = 0.0;	
	public static int firstdata_flag_distance =0, firstdata_flag_speed =0, firstData = 0;
	public static double lat1 =0.0, lng1 =0.0, lat2 =0.0, lng2 =0.0;
    public static double total_speed = 0.0, tmp_speed=0.0, tmp_speed1=0.0;
    public static double total_dist =0.0, daily_dist =0.0, total_dist_tmp =0.0, avg_speed =0.0, max_speed =0.0;
    public static double xml_date_latest_sec = 0.0, device_time_sec =0.0, startdate_sec =0.0, enddate_sec =0.0;
    
    public static int speed_threshold = 1, start_runflag = 0, stop_runflag = 1;
    //int r1 =0;
    //int r2 =0;
	public static String StopTimeCnt = "", speeed_data_valid_time ="", time1_tmp ="", time2_tmp ="", current_time ="";
	public static int StopStartFlag = 0, DataValid=0;
	
	public static String time1 = "", time2 ="", last_time1 = "", last_time="";
	public static double date_secs1 = 0.0, date_secs2=0.0, tmp_time_diff1=0.0, tmp_time_diff=0.0, runtime = 0.0, total_runtime =0.0; 
	
	public static double latlast = 0.0, lnglast = 0.0, distance=0.0, distance1 =0.0;
	public static SimpleDateFormat cDate = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");//dd/MM/yyyy

    
	public static ArrayList<Double> speed_list = new ArrayList<Double>();
	public static ArrayList<String> runtime_start = new ArrayList<String>();
    public static ArrayList<String> runtime_stop = new ArrayList<String>();
    
    //###### FINAL ARRAY
    public static ArrayList<Integer> IMEI_No = new ArrayList<Integer>();
    public static ArrayList<String> StartTime = new ArrayList<String>();
    public static ArrayList<String> EndTime = new ArrayList<String>();
    public static ArrayList<String> ServerTime = new ArrayList<String>();
    public static ArrayList<Double> AverageSpeed = new ArrayList<Double>();
    public static ArrayList<Double> MaxSpeed = new ArrayList<Double>();
    public static ArrayList<Double> Distance = new ArrayList<Double>();
    public static ArrayList<String> AlertTime = new ArrayList<String>();
    
	public static void action_report_distance(String imei, String device_time, String sts, String startdate, String enddate, double interval, double lat, double lng, double speed, int data_size, int record_count) {
											  		
		if(device_time!=null) {
			//System.out.println(" imei="+imei+" device_time="+device_time+" startdate="+startdate+" enddate="+enddate+" interval="+interval+" lat="+lat+" lng="+lng+" speed="+speed+" data_size="+data_size+" record_count="+record_count);

			device_time_sec = utility_class.get_seconds(device_time);
			startdate_sec = utility_class.get_seconds(startdate);
			enddate_sec = utility_class.get_seconds(enddate);
			
			if( (device_time_sec >= startdate_sec) && (device_time_sec <= enddate_sec) ) {

				speed_list.add(speed);   
				
				if(firstdata_flag_distance==0) {					
					firstdata_flag_distance = 1;
			
					lat1 = lat;
					lng1 = lng;
						
					time1 = device_time;
					
					date_secs1 = utility_class.get_seconds(time1);					
					date_secs1 = date_secs1 + interval;
					System.out.println("time1="+time1+" ,date_secs1="+date_secs1+", interval="+interval);
					//date_secs2 = 0;  
					last_time1 = device_time;
					latlast = lat;
					lnglast = lng;
					
					
					if( (speed > speed_threshold) && (start_runflag==0) ) {
						//echo "<br>start condition1";
						//runtime_start.add(device_time);
						runtime_start.add(device_time);
						//r1++;
						start_runflag = 1;
						stop_runflag = 0; 
						StopStartFlag = 0;
					}					
					//echo "<br>FirstData:".$date_secs1." ".$time1;                 	
				}           	
				//echo "<br>k2=".$k2."<br>";
				
				else {                           					
					// echo "<br>Total lines orig=".$total_lines." ,c=".$c;
					time2 = device_time;					
					date_secs2 = utility_class.get_seconds(time2);
					System.out.println("DateSec2="+date_secs2+" ,time2="+time2);
														                                      													      					
					lat2 = lat;      				        					
					lng2 = lng; 
					//calculate_distance(lat1, lat2, lng1, lng2, &$distance);
					
					distance = utility_class.calculateDistance(lat1, lng1, lat2, lng2);
					//System.out.println("Lat1="+lat1+", Lng1="+lng1+" ,Lat2="+lat2+", lng2="+lng2+" ,distance="+distance);
					if(distance>2000)
					{
						distance=0;
						lat1 = lat2;
						lng1 = lng2;
					}
					//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance." ,datetime=".$datetime;
					
					//echo "distance=".$distance." ,datetime=".$datetime."<br>";
					
					//echo "<br>dist=".$distance;			      					
					//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
					//echo '<br>Time:'.$datetime.' ,lat1='.$lat1.' ,lng1='.$lng1.', lat2='.$lat2.' ,lng2='.$lng2.' ,dist='.$distance.' totaldist='.$total_dist;                         
					//if($distance>0.025) 
			
					tmp_time_diff1 = utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time1) / 3600;
					distance1 = utility_class.calculateDistance(latlast, lnglast, lat2, lng2);
					
					//echo "<br>latlast=".$latlast." ,lat2=".$lat2." ,lnglast=".$lnglast." ,lng2=".$lng2." ,distance1=".$distance1." , datetime=".$datetime."<br>";
					
					if(!last_time.equals("")) {
						//System.out.println("TEST3");
						tmp_time_diff = (utility_class.get_seconds(device_time) - utility_class.get_seconds(last_time)) / 3600;
					} else {
						//System.out.println("TEST4");
						tmp_time_diff = (utility_class.get_seconds(device_time)) / 3600;
					}
					
					if(tmp_time_diff1>0) {									
						tmp_speed = ((double) (distance)) / tmp_time_diff;
						tmp_speed1 = ((double) (distance1)) / tmp_time_diff1;
					}
					else {
						tmp_speed1 = 1000.0; //very high value
					}
					
					if(tmp_speed<300.0) {
						speeed_data_valid_time = device_time;
					}
					
					//System.out.println("TEST5");
					if(( utility_class.get_seconds(device_time) - utility_class.get_seconds(speeed_data_valid_time))>300) {
						lat1 = lat2;
						lng1 = lng2;
						last_time = device_time;
					}
					
					last_time1 = device_time;
					latlast = lat2;
					lnglast =  lng2;
										            
					//echo "lat1=".$lat1."lng1=".$lng1."lat2=".$lat2." lng2=".$lng2."<br>";
					//echo "datetime=".$datetime." distance=".$distance." total_dist=".$total_dist." tmpspeed=".$tmp_speed." tmpspeed1=".$tmp_speed1." tmp_time_diff=".$tmp_time_diff." tmp_time_diff1=".$tmp_time_diff1."<br>";																	

					//System.out.println("TmpSpeed="+tmp_speed+" ,tmp_speed="+tmp_speed1+" ,distance="+distance+",tmp_time_diff="+tmp_time_diff+", tmp_time_diff1="+tmp_time_diff1);
					if(tmp_speed<300.0 && tmp_speed1<300.0 && distance>0.1 && tmp_time_diff>0.0 && tmp_time_diff1>0) {								

						total_dist = total_dist + distance;						
						daily_dist= daily_dist + distance;	
						daily_dist = utility_class.roundTwoDecimals(daily_dist);
						System.out.println("daily_dist="+daily_dist);
						lat1 = lat2;
						lng1 = lng2;
						last_time = device_time;
			
						//////// TMP VARIABLES TO CALCULATE LAST RECORD  //////
						time1_tmp = time1;
						time2_tmp = time2;
						total_dist_tmp = total_dist;
						////// TMP CLOSED	////////
						
						//SPEED CONDITION OPENS
						if((speed < speed_threshold) && (stop_runflag ==0)) {
							
							double sec_tmp = 0.0;
							
							if(!StopTimeCnt.equals("")) {
								//System.out.println("TEST6");								
								sec_tmp = utility_class.get_seconds(device_time) - utility_class.get_seconds(StopTimeCnt);
							} else {
								//System.out.println("TEST7");
								sec_tmp = utility_class.get_seconds(device_time);
							}							
									
							if(((sec_tmp)>15.0) && (StopStartFlag==1)) {
								//echo ", stop<br>";
								runtime_stop.add(device_time);
								//$r2++;
								stop_runflag = 1;
								start_runflag = 0;
							}
							else if(StopStartFlag==0) {
								StopTimeCnt = device_time;
								StopStartFlag = 1;
							}
						}
								  
						if(speed > speed_threshold && (start_runflag ==0) && (distance>0.1)  ) {
							//echo "<br>start";
							runtime_start.add(device_time);
							//$r1++;
							start_runflag =1;
							stop_runflag = 0;
							StopStartFlag = 0;
						}
						//SPEED CONDITION CLOSED
					}      					

					//System.out.println("dateSec1="+date_secs1+" ,dateSec2="+date_secs2);
					if( (date_secs2 >= date_secs1)) {								
						
						//System.out.println("TEST8A");
						if(runtime_start.size() == 0)
							total_runtime =0;
		  
						//if( (sizeof($runtime_stop) == 0) && (sizeof($runtime_start)>0) )
						if( ((runtime_stop.size()) == (runtime_start.size())-1)) {
							//echo "<br>A:RunStop";
							runtime_stop.add(device_time);
							stop_runflag = 1;
							start_runflag = 0; 
							//r2++;
						}
		  
						total_runtime = 0;
						//int retval = runtime_start.size();
						for(int m=0; m<runtime_start.size(); m++) {
							//echo "<br>A:run1=".$runtime_stop[$m]." ,run2=".$runtime_start[$m]."<br>";                   
							//System.out.println("TEST8");
							runtime = utility_class.get_seconds(runtime_stop.get(m)) - utility_class.get_seconds(runtime_start.get(m));
							total_runtime = total_runtime + runtime;							               
						} 
						
						if(total_dist>0.0 && total_runtime>0.0) {
							
							avg_speed = (total_dist / total_runtime)*3600;
							avg_speed = utility_class.roundTwoDecimals(avg_speed);
							System.out.println("total_dist="+total_dist+" ,total_runtime="+total_runtime+",avg_speed="+avg_speed);
						}
						//max_speed = max(speed_list);
						max_speed = Collections.max(speed_list);
						max_speed = utility_class.roundTwoDecimals(max_speed);
						
						System.out.println("AvgSpeed="+avg_speed+" ,MaxSpeed="+max_speed);

						if( (avg_speed > max_speed) && (max_speed > 2.0) ) {
							avg_speed = max_speed - 2;
						}              
						else if( (avg_speed > max_speed) && (max_speed > 0.2) && (max_speed <= 2.0) ) {								
							avg_speed = max_speed - 0.2;
						}							              							
						
						if(avg_speed>150) {
							avg_speed = 0;
						}						
						
						Date now = new Date();
						current_time = cDate.format(now);
						
					    StartTime.add(time1);
					    EndTime.add(time2);
					    ServerTime.add(sts);
					    AverageSpeed.add(avg_speed);
					    MaxSpeed.add(max_speed);
					    Distance.add(total_dist);
					    AlertTime.add(current_time);
						
						System.out.println("Time1="+time1+", Time2="+time2+" ,AvgSpd="+avg_speed+" ,MaxSpd="+max_speed+" ,TotalDist="+total_dist+" ,CurrentTime="+current_time);
					    //echo "<br>IN DATESEC";                                                  						
						//$distance_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" distance=\"".$total_dist."\"/>";			
						//reassign time1
						time1 = device_time;
						date_secs1 = utility_class.get_seconds(time1);
						date_secs1 = date_secs1 + interval;		    									    						    						
						//echo "<br>datesec1=".$datetime;    						                  
						total_dist = 0.0;
						avg_speed = 0.0;
						total_dist = 0.0;
						
						//speed_list.clear();
						//runtime_start.clear();
						//runtime_stop.clear();
						speed_list = new ArrayList<Double>();
						runtime_start = new ArrayList<String>();
					    runtime_stop = new ArrayList<String>();						

						start_runflag = 0;
						stop_runflag = 1;

						total_runtime =0; 

						//$r1 = 0;
						//$r2 = 0;						
						//$lat1 = $lat2;
						//$lng1 = $lng2;
						///////////////////////																
					}  //if datesec2
					
					
					if(record_count==data_size) {		//##### LAST DATA		
						
						if(runtime_start.size() == 0)
							total_runtime =0;
		  
						//if( (sizeof($runtime_stop) == 0) && (sizeof($runtime_start)>0) )
						if( ((runtime_stop.size()) == (runtime_start.size())-1)) {
							//echo "<br>A:RunStop";
							runtime_stop.add(device_time);
							stop_runflag = 1;
							start_runflag = 0; 
							//r2++;
						}
		  
						total_runtime = 0;
						//int retval = runtime_start.size();
						for(int m=0; m<runtime_start.size(); m++) {
							//echo "<br>A:run1=".$runtime_stop[$m]." ,run2=".$runtime_start[$m]."<br>";                   
							//System.out.println("TEST8");
							runtime = utility_class.get_seconds(runtime_stop.get(m)) - utility_class.get_seconds(runtime_start.get(m));
							total_runtime = total_runtime + runtime;							               
						} 
						
						if(total_dist>0.0 && total_runtime>0.0) {
							
							avg_speed = (total_dist / total_runtime)*3600;
							avg_speed = utility_class.roundTwoDecimals(avg_speed);
							System.out.println("total_dist="+total_dist+" ,total_runtime="+total_runtime+",avg_speed="+avg_speed);
						}
						//max_speed = max(speed_list);
						max_speed = Collections.max(speed_list);
						max_speed = utility_class.roundTwoDecimals(max_speed);
						
						System.out.println("AvgSpeed="+avg_speed+" ,MaxSpeed="+max_speed);

						if( (avg_speed > max_speed) && (max_speed > 2.0) ) {
							avg_speed = max_speed - 2;
						}              
						else if( (avg_speed > max_speed) && (max_speed > 0.2) && (max_speed <= 2.0) ) {								
							avg_speed = max_speed - 0.2;
						}							              							
						
						if(avg_speed>150) {
							avg_speed = 0;
						}						
					    
						StartTime.add(time1);
					    EndTime.add(time2);
					    ServerTime.add(sts);
					    AverageSpeed.add(avg_speed);
					    MaxSpeed.add(max_speed);
					    Distance.add(total_dist);
					    AlertTime.add(current_time);
					    System.out.println("LAST::Time1="+time1+", Time2="+time2+" ,AvgSpd="+avg_speed+" ,MaxSpd="+max_speed+" ,TotalDist="+total_dist+" ,CurrentTime="+current_time);
												
						time1 = device_time;
						date_secs1 = utility_class.get_seconds(time1);
						date_secs1 = date_secs1 + interval;		    									    						    						
												                  
						total_dist = 0.0;	 
						lat1 = lat2;
						lng1 = lng2;
					}
				}
			}
		}
	}	
}
