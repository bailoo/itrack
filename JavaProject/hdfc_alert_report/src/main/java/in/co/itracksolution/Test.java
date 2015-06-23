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
//		double angle = get_angle_between_two_points();
		int angle = get_turning_angle();
	
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
	
/*	public static double get_angle_between_two_points()
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
*/	
	public static final int EARTH_RADIUS = 6371*1000; 
	
	public static double lat_start = 28.780298212186658;
	public static double lng_start = 77.19818115234375;
	
	public static double lat_middle = 28.99913237453024;
	public static double lng_middle = 76.97845458984375;
	
	public static double lat_end = 28.77066855563607;
	public static double lng_end = 76.8548583984375;
	
	
	/*public static double lat_start = 28.621295347525884;
	public static double lng_start = 77.222900390625;
	
	public static double lat_middle = 28.912615763699517;
	public static double lng_middle = 77.44537353515625;
	
	public static double lat_end = 28.647812595218262;
	public static double lng_end = 77.30255126953125;*/

	
	/*public static double lat_start = 28.864519767126602;
	public static double lng_start = 77.12677001953125;
	
	public static double lat_middle = 28.640581283147768;
	public static double lng_middle = 76.72027587890625;
	
	public static double lat_end = 28.592359801121564;
	public static double lng_end = 77.2723388671875;*/

	
	public static int get_turning_angle() {
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
		double B = distFrom(lat_middle, lng_middle, lng_end, lng_end);
		//System.out.println("B "+(int)B);
		double C = distFrom(lng_end, lng_end, lat_start, lng_start);
		//find the angle between the the three edges

		double cosTheata = (-(C*C-A*A-B*B)/(2*A*B));

		//convert in degrees
		int angle = (int)Math.toDegrees(Math.acos(cosTheata));
		System.out.println("Math.toDegrees(Math.acos(cosTheata))   "+angle);
		return angle;
	}
	
	public static double distFrom(double lat1, double lng1, double lat2, double lng2) {
	    double earthRadius = 3958.75;
	    double dLat = Math.toRadians(lat2-lat1);
	    double dLng = Math.toRadians(lng2-lng1);
	    double a = Math.sin(dLat/2) * Math.sin(dLat/2) +
	               Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) *
	               Math.sin(dLng/2) * Math.sin(dLng/2);
	    double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	    double dist = earthRadius * c;

	    int meterConversion = 1609;

	    //return new Double(dist * meterConversion).doubleValue();
	    return Double.valueOf(dist * meterConversion).doubleValue();
	}	
	
}
