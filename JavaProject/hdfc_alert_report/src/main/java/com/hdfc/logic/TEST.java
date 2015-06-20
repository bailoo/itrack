package com.hdfc.logic;

import java.awt.List;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.HashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class TEST {

	public static HashMap<String, ArrayList> lastSecArray = new HashMap<String, ArrayList>();
	public static ArrayList value = new ArrayList();
	
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
		get_max();
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
	
}
