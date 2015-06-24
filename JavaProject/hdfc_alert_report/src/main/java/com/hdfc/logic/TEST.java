package com.hdfc.logic;

import java.awt.List;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class TEST {

	public static HashMap<String, ArrayList> lastSecArray = new HashMap<String, ArrayList>();
	public static ArrayList value = new ArrayList();
	public static final int EARTH_RADIUS = 6371*1000;
	
	public static void main(String args[])
	{
		/*add();	    

	    lastSecArray.put("TEST", value);
	    
	    System.out.println("Size="+lastSecArray.size());
	    System.out.println("Get="+lastSecArray.get("TEST").size());
	    
	    for(int i=0;i<lastSecArray.get("TEST").size();i++)
	    {
	    	System.out.println(lastSecArray.get("TEST").get(i));
	    }
	    
	    //##### READ FROM ARRAY LIST-TEST
	    ArrayList<String> list = new ArrayList<String>();
	    ArrayList<String> list2 = new ArrayList<String>();
		list.add("ABC");
		list2.add("XYZ");		
 		
		for (int i = 0; i < list.size(); i++) {
			System.out.println(list.get(i));
			System.out.println(list2.get(i));
		}*/
		
		//System.out.println("Yesterday date="+getYesterdayDateString());
		//validate_lat_lng("23.169054, 80.714108");
//		get_max();
		
		double lat_start = 29.94744;
		double lng_start = 78.16192;
		double lat_middle = 29.94721;
		double lng_middle = 78.16219;
		double lat_end = 29.947;
		double lng_end = 78.16244;
		float angle = get_turning_angle(lat_start, lng_start, lat_middle, lng_middle, lat_end, lng_end);
		System.out.println("Angle="+angle);
		
		//get_angle_test();
	}
	
	public static void add()
	{
	    value.add(0);
	    value.add(1);
	    value.add(2);
	}
	
	private static String getYesterdayDateString() {
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
        Calendar cal = Calendar.getInstance();
        cal.add(Calendar.DATE, -1);
        return dateFormat.format(cal.getTime());
	}
	
	
	public static void validate_lat_lng(String latlon)
	{
		//String latlon = "";
	    String regex_coords = "/\\((\\-?\\d+\\.\\d+), (\\-?\\d+\\.\\d+)\\)/";
	    Pattern compiledPattern2 = Pattern.compile(regex_coords, Pattern.CASE_INSENSITIVE);
	    Matcher matcher2 = compiledPattern2.matcher(latlon);
	    while (matcher2.find()) {
	        System.out.println("Is Valid Map Coordinate: " + matcher2.group());
	    }
	    System.out.println("TEST");
	}
	

    public static void get_max() {
        ArrayList l = new ArrayList();
        l.add(30.34);
        l.add(50.34);
        l.add(20.434);
        l.add(10.324);
        l.add(90.34);
        System.out.println(Collections.max(l)); // 5
        System.out.println(Collections.min(l)); // 1
        System.out.println("Before="+l.size());
        l.clear();
        System.out.println("After="+l.size());
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
	
	
	/*public static void get_angle_test() {
	
		boolean isRight = false;
	
		double lat_start = 29.94744;
		double lng_start = 78.16192;
		double lat_middle = 29.94721;
		double lng_middle = 78.16219;
		double lat_end = 29.947;
		double lng_end = 78.16244;
	
		//find the points on plain surface from latitude and longitude
		double ax = EARTH_RADIUS * Math.sin(Math.toRadians(lat_start)) * Math.cos(Math.toRadians(lng_start));
		//System.out.println("x "+ax);
		double ay = EARTH_RADIUS * Math.sin(Math.toRadians(lat_start)) * Math.sin(Math.toRadians(lng_start));
		//System.out.println("y "+ay);
		double bx = EARTH_RADIUS * Math.sin(Math.toRadians(lat_middle)) * Math.cos(Math.toRadians(lng_middle));
		//System.out.println("x "+bx);
		double by = EARTH_RADIUS * Math.sin(Math.toRadians(lat_middle)) * Math.sin(Math.toRadians(lng_middle));
		//System.out.println("y "+by);
		double cx = EARTH_RADIUS * Math.sin(Math.toRadians(lat_end)) * Math.cos(Math.toRadians(lng_end));
		//System.out.println("x "+cx);
		double cy = EARTH_RADIUS * Math.sin(Math.toRadians(lat_end)) * Math.sin(Math.toRadians(lng_end));
		//System.out.println("y "+cy);
		isRight = (((bx - ax) * (cy - ay) - (by - ay) * (cx - ax)) > 0);
		//System.out.println("is Right "+isRight);
	
		//get the edges length
	
		double A = distFrom(lat_start, lng_start, lat_middle, lng_middle);
		//System.out.println("A "+(int)A);
		double B = distFrom(lat_middle, lng_middle, lat_end, lng_end);
		//System.out.println("B "+(int)B);
		double C = distFrom(lat_end, lng_end, lat_start,lng_start);
	
		//find the angle between the the three edges
	
		double cosTheata = (-(C * C - A * A - B * B) / (2 * A * B));
	
		//convert in degrees
		int angle = (int) Math.toDegrees(Math.acos(cosTheata));
		System.out.println("(ANGLE) Math.toDegrees(Math.acos(cosTheata)) " + angle);	
	}*/
	
	private static Date addMinutesToDate(int minutes, Date beforeTime){
	    long ONE_MINUTE_IN_MILLIS = 60000;//millisecs

	    long curTimeInMs = beforeTime.getTime();
	    Date afterAddingMins = new Date(curTimeInMs + (minutes * ONE_MINUTE_IN_MILLIS));
	    return afterAddingMins;
	}
	
}	
