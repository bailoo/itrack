package com.hdfc.report;

import java.util.ArrayList;

import com.hdfc.utils.utility_class;
import com.iespl.gisgraphy.LatLng;

public class report_turning_violation {

	public static int halt_flag = 0;	
	public static String xml_date_latest="1900-00-00 00:00:00", devicetime_start="", devicetime_middle="", devicetime_end="", sts_start="", sts_middle="", sts_end="", datetime_cr="", road_code="", last_time="", last_time1="";
	public static double lat_start=0.0, lng_start=0.0, lat_middle=0.0, lng_middle=0.0, lat_end = 0.0, lng_end=0.0, speed_start=0.0, speed_middle=0.0, speed_end=0.0;
	public static double xml_date_latest_sec=0.0, device_time_sec=0.0, startdate_sec=0.0, enddate_sec=0.0;
	public static double CurrentLong = 0.0, tmp_time_diff1=0.0, tmp_time_diff=0.0;
	public static double distance =0.0, distance1=0.0, starttime=0.0, stoptime=0.0, halt_dur=0.0;	
	public static int start_flag =0, middle_flag =0, angle=0;
	public static boolean out_flag=false;
	public static final int EARTH_RADIUS = 6371*1000;
	
    //###### FINAL ARRAY
    public static ArrayList<String> IMEI_No = new ArrayList<String>();
    public static ArrayList<String> turningDeviceTime = new ArrayList<String>();
    public static ArrayList<String> turningServerTime = new ArrayList<String>();
    public static ArrayList<Double> turningSpeed = new ArrayList<Double>();
    public static ArrayList<Float> turningAngle = new ArrayList<Float>();
    public static ArrayList<Double> turningLatitude = new ArrayList<Double>();
    public static ArrayList<Double> turningLongitude = new ArrayList<Double>();
    public static ArrayList<LatLng> latLngObj = new ArrayList<LatLng>();
    public static ArrayList<String> locationCode = new ArrayList<String>();
    public static ArrayList<String> location = new ArrayList<String>();
    public static ArrayList<String> roadID = new ArrayList<String>();
    public static ArrayList<String> roadName = new ArrayList<String>();
      
	
	//IF ANGLE > 30 DEG && Speed > 10 Km/hr
	public static void action_report_truning_violation(String imei, String device_time, String sts, String startdate, String enddate, double interval, double lat, double lng, double speed, int data_size, int record_count)
	{
		if(device_time!=null) {	
			//System.out.println(" imei="+imei+" device_time="+device_time+" startdate="+startdate+" enddate="+enddate+" interval="+interval+" lat="+lat+" lng="+lng+" speed="+speed+" data_size="+data_size+" record_count="+record_count);
			try{
				//xml_date_latest_sec = utility_class.get_seconds(xml_date_latest);
				//System.out.println("xml_date_latest_sec="+xml_date_latest_sec);
				device_time_sec = utility_class.get_seconds(device_time);
				//System.out.print("device_time_sec="+device_time_sec);
				startdate_sec = utility_class.get_seconds(startdate);
				//System.out.println("startdate_sec="+startdate_sec);
				enddate_sec = utility_class.get_seconds(enddate);
				//System.out.println("enddate_sec="+enddate_sec);			
			}catch(Exception ed) {System.out.println(ed.getMessage());}
			//System.out.println("ValidDeviceTime Found2");
			
			//System.out.println("BeforeValid="+device_time+" ,startdate="+startdate+" ,enddate="+enddate+" ,xml_date_latest="+xml_date_latest);
			
			if( (device_time_sec >= startdate_sec) && (device_time_sec <= enddate_sec) ) {
				  				
				//System.out.println("Valid="+device_time);
				
				if(start_flag==0){	//START POINT
					//System.out.println("IMEI_START="+imei);
					start_flag = 1;
												
					lat_start = lat;						
					lng_start = lng;
					devicetime_start = device_time;
					sts_start = sts;
					speed_start = speed; 					
				}
				else if((start_flag==1) && (middle_flag==0)){	//MIDDLE POINT

					//System.out.println("IMEI_MIDDLE="+imei);
					middle_flag = 1;
												
					lat_middle = lat;						
					lng_middle = lng;								

					devicetime_middle = device_time;
					sts_middle = sts;
					speed_middle = speed; 
				}			
				else if((start_flag==1) && (middle_flag==1)) { 	//END POINT

					//System.out.println("IMEI_END="+imei);
					lat_end = lat;						
					lng_end = lng;
					devicetime_end = device_time;
					sts_end = sts;
					speed_end = speed; 
					
					//####### CHECK RANGE OF CHAURAHA
					//get_turning_angle(lat_ref, lng_ref, lat_cr, lng_cr);
					/*lat_start = 30.12768;
					lng_start = 78.2944;
					
					lat_middle = 30.1282;
					lng_middle = 78.29454;
					
					lat_end = 30.12544;
					lng_end = 78.29692;*/
					
					angle = get_turning_angle(lat_start, lng_start, lat_middle, lng_middle, lat_end, lng_end);
					//###############################
					//System.out.println("Angle="+angle+",lat_start="+lat_start+",lng_start="+lng_start+",lat_middle="+lat_middle+" ,lng_middle="+lng_middle+",lat_end="+lat_end+",lng_end="+lng_end);
					
					//if(angle > 30) {
					if(angle > 90.0f) {
						angle = (180 - angle);
					}
					
					if( (angle > 30.0f) && (speed>1.0) ) {
						
						//System.out.println("Angle Found="+angle+" ,DeviceTime="+device_time);
						IMEI_No.add(imei);
						turningDeviceTime.add(devicetime_middle);
						turningServerTime.add(sts_middle);
						turningSpeed.add(speed_middle);
						turningAngle.add((float)angle);
						turningLatitude.add(lat_middle);
						turningLongitude.add(lng_middle);
						
						LatLng tmpobj = new LatLng(Double.toString(lat_middle), Double.toString(lng_middle),"","","","");
						latLngObj.add(tmpobj);
					}				
					
					lat_start = lat_middle;
					lng_start = lng_middle;
					devicetime_start = devicetime_middle;
					sts_start = sts_middle;
					speed_start = speed_middle; 
					
					lat_middle = lat_end;
					lng_middle = lng_end;
					devicetime_middle = devicetime_end;
					sts_middle = sts_end;
					speed_middle = speed_end; 					
				}
			}
		}
	}
	
	
	public static int get_turning_angle(double lat_start, double lng_start, double lat_middle, double lng_middle, double lat_end, double lng_end) {
		//find the points on plain surface from latitude and longitude
		double ax = EARTH_RADIUS * Math.sin(Math.toRadians(lat_start))* Math.cos(Math.toRadians(lng_start));
		//System.out.println("x "+ax);
		double ay = EARTH_RADIUS * Math.sin(Math.toRadians(lat_start))* Math.sin(Math.toRadians(lng_start));
		//System.out.println("y "+ay);
		double bx = EARTH_RADIUS * Math.sin(Math.toRadians(lat_middle))* Math.cos(Math.toRadians(lng_middle));
		//System.out.println("x "+bx);
		double by = EARTH_RADIUS * Math.sin(Math.toRadians(lat_middle))* Math.sin(Math.toRadians(lng_middle));
		//System.out.println("y "+by);
		double cx = EARTH_RADIUS * Math.sin(Math.toRadians(lat_end))* Math.cos(Math.toRadians(lng_end));
		//System.out.println("x "+cx);
		double cy = EARTH_RADIUS * Math.sin(Math.toRadians(lat_end))* Math.sin(Math.toRadians(lng_end));
		//System.out.println("y "+cy);

		//get the edges length

		double A = distFrom(lat_start, lng_start, lat_middle, lng_middle);
		//System.out.println("A "+(int)A);
		double B = distFrom(lat_middle, lng_middle, lat_end, lng_end);
		//System.out.println("B "+(int)B);
		double C = distFrom(lat_end, lng_end, lat_start, lng_start);
		//find the angle between the the three edges

		double cosTheata = (-(C*C-A*A-B*B)/(2*A*B));

		//convert in degrees
		int angle = (int)Math.toDegrees(Math.acos(cosTheata));
		//System.out.println("Math.toDegrees(Math.acos(cosTheata))   "+angle);
		return angle;
	}
	
	public static double distFrom(double lat1, double lng1, double lat2, double lng2) {
	    double earthRadius = 3958.75;
	    double dLat = Math.toRadians(lat2-lat1);
	    double dLng = Math.toRadians(lng2-lng1);
	    double a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) * Math.sin(dLng/2) * Math.sin(dLng/2);
	    double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	    double dist = earthRadius * c;

	    int meterConversion = 1609;

	    //return new Double(dist * meterConversion).doubleValue();
	    return Double.valueOf(dist * meterConversion).doubleValue();
	}
}
