package com.hdfc.utils;

import java.text.DateFormat;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;


public class utility_class {

	public static String getYesterdayDateString() {
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
        Calendar cal = Calendar.getInstance();
        cal.add(Calendar.DATE, -1);
        return dateFormat.format(cal.getTime());
	}
	
	/************* METHOD- CALCULATE DISTANCE ************/
	public static double calculateDistance(double lat1, double lng1, double lat2, double lng2) {
		double earthRadius = 3958.75;
		double dLat = Math.toRadians(lat2-lat1);
		double dLng = Math.toRadians(lng2-lng1);
		double a = Math.sin(dLat/2) * Math.sin(dLat/2) +
		Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) *
		Math.sin(dLng/2) * Math.sin(dLng/2);
		double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
		double dist = earthRadius * c;

		int meterConversion = 1609;

		double meters = new Double(dist * meterConversion).doubleValue();
		return (meters/1000.0);
	}
	
	/************* METHOD- CALCULATE TIME DIFFERENCE ************/
	public static long calculateTimeDiff(String time1, String time2){
		//System.out.println("Time1="+time1+" ,Time2="+time2);
		//System.out.println();		
		/*if(time1.equalsIgnoreCase(""))
		{
			return 600;
		}*/
		
		try{
			int timediff=0;
			String[] temp,temp1,temp2;
			Calendar calendar1 = Calendar.getInstance();
			Calendar calendar2 = Calendar.getInstance();
	
			/*temp=split(time1," ");
			temp1=split(temp[0],"-");
			temp2=split(temp[1],":");*/
			
			temp= time1.split(" ");
			temp1=temp[0].split("-");
			temp2=temp[1].split(":");
			
	
			calendar1.set(Integer.parseInt(temp1[0]), Integer.parseInt(temp1[1]), Integer.parseInt(temp1[2]) , Integer.parseInt(temp2[0]), Integer.parseInt(temp2[1]), Integer.parseInt(temp2[2]) );
	
			/*temp=split(time2," ");
			temp1=split(temp[0],"-");
			temp2=split(temp[1],":");*/
			
			temp=time2.split(" ");
			temp1=temp[0].split("-");
			temp2=temp[1].split(":");
			
	
			calendar2.set(Integer.parseInt(temp1[0]), Integer.parseInt(temp1[1]), Integer.parseInt(temp1[2]) , Integer.parseInt(temp2[0]), Integer.parseInt(temp2[1]), Integer.parseInt(temp2[2]) );
	
			long milliseconds1 = calendar1.getTimeInMillis();
			long milliseconds2 = calendar2.getTimeInMillis();
			long diff = milliseconds2 - milliseconds1;
			long diffMinutes = diff / (60 * 1000);
	
			//System.out.println("Time in minutes2: " + diffMinutes + " minutes.");        		
			//"yyyy-MM-dd HH:mm:ss"
			return diffMinutes;
		}catch(Exception e){System.out.println("Exception in time");}
		
		return 0;
	}

	/************* METHOD- ROUND TO TWO DECIMAL ************/
	public static double roundTwoDecimals(double d) {
    	DecimalFormat twoDForm = new DecimalFormat("#.##");
		return Double.valueOf(twoDForm.format(d));
	}
	
	public static double get_seconds(String date_str){
		//System.out.println("date_str="+date_str);
		SimpleDateFormat format = null;
		double seconds = 0;

		format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		
		try{
			Date date = (Date) format.parse(date_str);	  	
			//System.out.println("date= " +date);
			seconds = (double) ((date.getTime()) / 1000);
			//System.out.println("Seconds is " +seconds);
		}catch(Exception esec) {System.out.println("Error in date conversion to seconds="+esec.getMessage());}
		
		return seconds;		
	}
	
	public static String get_hms(long timeInSeconds) {
		   
		if(timeInSeconds>86400)
		 timeInSeconds = 86400;
			  
		long hours, minutes, seconds;
		String hours_1, minutes_1, seconds_1;
		hours = timeInSeconds / 3600;
		timeInSeconds = timeInSeconds - (hours * 3600);
		minutes = timeInSeconds / 60;
		timeInSeconds = timeInSeconds - (minutes * 60);
		seconds = timeInSeconds;
		//System.out.println(hours + " hour(s) " + minutes + " minute(s) " + seconds + " second(s)");
		if(hours<10){ hours_1="0"+hours;} else {hours_1= ""+hours;}
		if(minutes<10){ minutes_1="0"+minutes;} else {minutes_1=""+minutes;}
		if(seconds<10){ seconds_1="0"+seconds;} else {seconds_1=""+seconds;}
		return (hours_1+":"+minutes_1+":"+seconds_1);
	}
	
	
	public static boolean check_with_range_station(double lat1, double lng1, double lat2, double lng2, double radius) {
		boolean status_station = false; 
			   
		//System.out.println("HC1::DISTANCE_VAR1="+distance_var);
		//System.out.println("IN DISTANCE::lat1="+ Float.parseFloat(lat1)+" ,lat2="+Float.parseFloat(lat2)+" ,lng1="+Float.parseFloat(lng1)+" ,lng2="+Float.parseFloat(lng2));	
		double distance1 = calculateDistance(lat1,lat2,lng1,lng2);
		
		//System.out.println("HC2::DISTANCE="+ distance1+" ,DISTANCE_VAR2="+distance_var);
		    		
		if(distance1 < radius)	  
		{                                                        
			status_station = true; 
		}  
		else
		{
			status_station = false;
		}	  
		return status_station;
	}
	
    private static String removeLastChar(String str) {
        return str.substring(0,str.length()-1);
    }
    
    public static boolean isDouble(String str) {
        try {
            Double.parseDouble(str);
            return true;
        } catch (NumberFormatException e) {
            return false;
        }
    }
    
	public static double speed_correction(double speed)
	{
		if(speed > 200.0)
			speed =0.0;
		
		String speed_str = Double.toString(speed);
		String speed_tmp = "";				
		
		String[] result = speed_str.split("\\s");
		for (int x=0; x<result.length; x++){
			
			if((Integer.parseInt(result[x])>=0) && (Integer.parseInt(result[x])<=9))
			{
				speed_tmp = speed_tmp+""+result[x];
			}      
			else
			{
				speed_tmp = speed_tmp+".";
			}  
			//System.out.println(result[x]);
		 }
		
		speed = Double.parseDouble(speed_tmp);
		speed = roundTwoDecimals(speed);
		
		return speed;
	}
	
}
