package com.hdfc.report;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Date;

import com.hdfc.utils.utility_class;
import com.iespl.gisgraphy.LatLng;

public class report_speed_violation {
	
	public static double CurrentLat = 0.0, CurrentLong = 0.0, LastLat = 0.0, LastLong = 0.0;	
	public static int firstdata_flag_speed =0, firstData = 0;
	public static double speed_threshold = 80.0, lat1 =0.0, lng1 =0.0, lat2 =0.0, lng2 =0.0;
    public static double total_speed = 0.0, tmp_speed=0.0, tmp_speed1=0.0;
    public static double total_dist =0.0, daily_dist =0.0, total_dist_tmp =0.0, avg_speed =0.0, max_speed =0.0;
    public static double xml_date_latest_sec = 0.0, device_time_sec =0.0, startdate_sec =0.0, enddate_sec =0.0;
    
    public static int start_runflag = 0, stop_runflag = 1;
    //int r1 =0;
    //int r2 =0;
	public static String StopTimeCnt = "", speeed_data_valid_time ="", time1_tmp ="", time2_tmp ="", current_time ="";
	public static int StopStartFlag = 0, DataValid=0;
	
	public static String time1 = "", time2 ="", last_time1 = "", last_time="";
	public static double date_secs1 = 0.0, date_secs2=0.0, tmp_time_diff1=0.0, tmp_time_diff=0.0, runtime = 0.0, total_runtime =0.0; 
	
	public static double latlast = 0.0, lnglast = 0.0, speed=0.0, distance1 =0.0;
	public static SimpleDateFormat cDate = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");//dd/MM/yyyy

    
	public static ArrayList<Double> speed_list = new ArrayList<Double>();
	public static ArrayList<String> runtime_start = new ArrayList<String>();
    public static ArrayList<String> runtime_stop = new ArrayList<String>();
    
    //###### FINAL ARRAY
    public static ArrayList<String> IMEI_No = new ArrayList<String>();
    public static ArrayList<String> DeviceTime = new ArrayList<String>();
    public static ArrayList<String> ServerTime = new ArrayList<String>();
    public static ArrayList<Double> Speed = new ArrayList<Double>();
    public static ArrayList<Double> Latitude = new ArrayList<Double>();
    public static ArrayList<Double> Longitude = new ArrayList<Double>();
    public static ArrayList<LatLng> latLngObj = new ArrayList<LatLng>();
    public static ArrayList<String> locationId = new ArrayList<String>();
    public static ArrayList<String> location = new ArrayList<String>();
    public static ArrayList<String> roadID = new ArrayList<String>();
    public static ArrayList<String> roadName = new ArrayList<String>();
    
    
	public static void action_report_speed_violation(String imei, String device_time, String startdate, String enddate, double interval, double lat, double lng, double speed, int data_size, int record_count) {
											  		
		if(device_time!=null) {
			//System.out.println(" imei="+imei+" device_time="+device_time+" startdate="+startdate+" enddate="+enddate+" interval="+interval+" lat="+lat+" lng="+lng+" speed="+speed+" data_size="+data_size+" record_count="+record_count);

			device_time_sec = utility_class.get_seconds(device_time);
			startdate_sec = utility_class.get_seconds(startdate);
			enddate_sec = utility_class.get_seconds(enddate);
			
			if( (device_time_sec >= startdate_sec) && (device_time_sec <= enddate_sec) ) {
					
					if(speed > speed_threshold) {
						
						IMEI_No.add(imei);
						DeviceTime.add(device_time);
						//ServerTime.add(sts);
						Speed.add(speed);
						Latitude.add(lat);
						Longitude.add(lng);
						
						LatLng tmpobj = new LatLng(Double.toString(lat), Double.toString(lng),"","","","");
						latLngObj.add(tmpobj);
						
						System.out.println("Time1="+time1+", Time2="+time2+" ,AvgSpd="+avg_speed+" ,MaxSpd="+max_speed+" ,TotalDist="+total_dist+" ,CurrentTime="+current_time);
					    //echo "<br>IN DATESEC";
					}  //if datesec2					
				}
			}
		}
	}