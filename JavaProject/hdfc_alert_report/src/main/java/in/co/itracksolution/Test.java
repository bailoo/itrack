package in.co.itracksolution;

import java.io.IOException;
import java.io.InputStream;
//import java.sql.Date;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

public class Test
{

	public static class FullData
	{
		public String imei;
		public FullData()
		{
			super();
		}
		public String getImei() {
			return this.imei;
		}
		public void setImei(String imei) {
			this.imei = imei;
		}
		public FullData(FullData f)
		{
			this.imei = f.imei;			
		}
	}	

	public static void main(String[] args) 
	{
		/*ArrayList<FullData> fullDataList = new ArrayList<FullData>();
		
		FullData x = new FullData();
		x.setImei("111");
		System.out.println("x imei = "+x.getImei());
		fullDataList.add(new FullData(x));

		x.setImei("222");
		System.out.println("x imei = "+x.getImei());
		fullDataList.add(new FullData(x));

		FullData f = fullDataList.get(0);	
		System.out.println("f imei = "+f.getImei());*/
		
		//double sec = get_seconds("2015-06-15 12:00:00");
		//System.out.println("SECONDS="+sec);
		double angle = get_turning_angle();		
	
	}

	/*public static double get_seconds(String date_str){
		System.out.println("date_str="+date_str);
		SimpleDateFormat format = null;
		double seconds = 0;

		format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		
		try{
			Date date = (Date) format.parse(date_str);	  	
			System.out.println("date= " +date);
			seconds = (double) ((date.getTime()) / 1000);
			System.out.println("Seconds is " +seconds);
		}catch(Exception esec) {System.out.println("Error in date conversion to seconds="+esec.getMessage());}
		
		return seconds;		
	}*/
	
	public static double get_turning_angle()
	{			
		double lat1 = 26.448798555558366;	//prev
		double lng1 = 80.3343915939331;
	
		double lat2 = 26.4522950421482;		//next
		double lng2 = 80.33473491668701;
	
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
	
	
	/*private double angleBetween(Point center, Point current, Point previous) {

		  return Math.toDegrees(Math.atan2(current.x - center.x,current.y - center.y)-
		                        Math.atan2(previous.x- center.x,previous.y- center.y));
	}*/
}
