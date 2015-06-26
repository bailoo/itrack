package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.SpeedAlert;
import in.co.itracksolution.model.TurnAlert;
import in.co.itracksolution.model.DistanceLog;
import in.co.itracksolution.dao.SpeedAlertDao;
import in.co.itracksolution.dao.TurnAlertDao;
import in.co.itracksolution.dao.DistanceLogDao;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.lang.String;
import java.util.Date;
import java.util.TimeZone;
import java.util.Calendar;
import java.util.Properties;

public class InsertAlerts {
	CassandraConn conn;
	
	public InsertAlerts(){
		String propFileName = "config.properties";
		Properties prop = new Properties();
		
		try {
			InputStream inputStream = getClass().getClassLoader().getResourceAsStream(propFileName);
		
			if (inputStream != null) {
				prop.load(inputStream);
				conn = new CassandraConn(prop.getProperty("nodes"), prop.getProperty("keyspace"), prop.getProperty("username"), prop.getProperty("password"));
			
			} else {
				throw new FileNotFoundException("property file '" + propFileName + "' not found in the classpath");
			}
					
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	public void close(){
		if (conn !=null)
			conn.close();
	}

	public void insertSpeedAlert(String imei, String dtime, String stime, float speed, String location, String latitude, String longitude, String roadId ) 
	{
		//TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(); //gets a calendar using time zone and locale
		//Calendar now = Calendar.getInstance(IST); //gets a calendar using time zone and locale
		//now.setTimeZone(IST);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		sdf.setTimeZone(TimeZone.getTimeZone("Asia/Kolkata"));
		
		String date = dtime.substring(0,10);
		Date dtimeObj = new Date();	
		Date stimeObj = new Date();	
		try { 
			dtimeObj = sdf.parse(dtime);
			stimeObj = sdf.parse(stime);
		}
		catch (Exception e) {
			e.printStackTrace();
		}
	
		SpeedAlert speedAlert = new SpeedAlert(imei, date, dtimeObj, stimeObj, speed, location, latitude, longitude, roadId, now.getTime());
		SpeedAlertDao ops = new SpeedAlertDao(conn.getSession());
		ops.insert(speedAlert);
		System.out.println("Inserted SpeedAlert with imei: "+imei);
	}

	public void insertDistanceLog(String imei, String starttime, String endtime, float avgspeed, float distance, float maxspeed) 
	{
		//TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(); //gets a calendar using time zone and locale
		//Calendar now = Calendar.getInstance(IST); //gets a calendar using time zone and locale
		//now.setTimeZone(IST);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		sdf.setTimeZone(TimeZone.getTimeZone("Asia/Kolkata"));
		
		String date = starttime.substring(0,10);
		Date starttimeObj = new Date();	
		Date endtimeObj = new Date();	
		try { 
			starttimeObj = sdf.parse(starttime);
			endtimeObj = sdf.parse(endtime);
		}
		catch (Exception e) {
			e.printStackTrace();
		}
	
		DistanceLog distanceLog = new DistanceLog(imei, date, starttimeObj, endtimeObj, avgspeed, distance, maxspeed, now.getTime());
		DistanceLogDao ops = new DistanceLogDao(conn.getSession());
		ops.insert(distanceLog);
		System.out.println("Inserted DistanceLog with imei: "+imei);
	}
	
	public void insertTurnAlert(String imei, String dtime, String stime, float speed, float angle, String location, String latitude, String longitude, String roadId ) 
	{
		//TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(); //gets a calendar using time zone and locale
		//Calendar now = Calendar.getInstance(IST); //gets a calendar using time zone and locale
		//now.setTimeZone(IST);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		sdf.setTimeZone(TimeZone.getTimeZone("Asia/Kolkata"));
		
		String date = dtime.substring(0,10);
		Date dtimeObj = new Date();	
		Date stimeObj = new Date();	
		try { 
			dtimeObj = sdf.parse(dtime);
			stimeObj = sdf.parse(stime);
		}
		catch (Exception e) {
			e.printStackTrace();
		}
	
		TurnAlert turnAlert = new TurnAlert(imei, date, dtimeObj, stimeObj, speed, angle, location, latitude, longitude, roadId, now.getTime());
		TurnAlertDao ops = new TurnAlertDao(conn.getSession());
		ops.insert(turnAlert);
		System.out.println("Inserted TurnAlert with imei: "+imei);
	}
	
	
	public static void main(String[] args) 
	{
		String imei = "123456";
		String dtime = "2015-06-15 20:17:18";
		String stime = "2015-06-15 20:17:38";
		String starttime = "2015-06-26 13:27:18";
		String endtime = "2015-06-26 16:37:38";
		float speed = (float)20.1;
		float avgspeed = (float)34.1;
		float maxspeed = (float)55.1;
		float distance = (float)169.4;
		float angle = (float)60.3;
		String location = "Chandni Chowk";
		String latitude = "23.4568N";
		String longitude = "79.22434E";
		String roadId = "MG Road";
		
		InsertAlerts st = new InsertAlerts();
		//st.insertSpeedAlert(imei, dtime, stime, speed, location, latitude, longitude, roadId);
		//st.insertTurnAlert(imei, dtime, stime, speed, angle, location, latitude, longitude, roadId);
		st.insertDistanceLog(imei, starttime, endtime, avgspeed, distance, maxspeed); 
		st.close();
	
		System.out.println("The End");
	}
		
	
}
