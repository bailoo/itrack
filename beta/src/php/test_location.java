import java.io.*;
import java.net.*;
import java.util.*;
import java.util.Date;
import java.text.*;
import java.lang.*;
import java.util.regex.*;

class test_location
{
	public static void main(String args[])
	{
		String lat = "26.34324";
		String lng = "80.43243";		
		String location = get_url_location(lat,lng);
		System.out.println("LOCATION="+location);
	}
	///// GET URL GOOGLE LOCATION
	public static String get_url_location(String lat, String lng)
	{
		try {		
				String Request = "http://www.itracksolution.co.in/src/php/get_location_cmd.php?lat="+lat+"&lng="+lng+"";
				//URL my_url = new URL("http://www.placeofjo.blogspot.com/");
				URL my_url = new URL(Request);
				BufferedReader br = new BufferedReader(new InputStreamReader(my_url.openStream()));
				String strTemp = "";
				while(null != (strTemp = br.readLine())){
					//System.out.println(strTemp);
					return strTemp;
				}
		} catch (Exception ex) {ex.printStackTrace();}
		
		return null;
	}	
}
