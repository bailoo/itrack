package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.SpeedAlert;
import in.co.itracksolution.model.TurnAlert;
import in.co.itracksolution.dao.SpeedAlertDao;
import in.co.itracksolution.dao.TurnAlertDao;

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
				conn = new CassandraConn(prop.getProperty("nodes"), prop.getProperty("keyspace"));
			
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
		String dtime = "2015-06-16 16:17:18";
		String stime = "2015-06-17 17:17:38";
		float speed = (float)40.1;
		float angle = (float)80.3;
		String location = "AIIMS";
		String latitude = "23.1568N";
		String longitude = "79.12434E";
		String roadId = "MG Road";
		
		InsertAlerts st = new InsertAlerts();
//		st.insertSpeedAlert(imei, dtime, stime, speed, location, latitude, longitude, roadId);
		st.insertTurnAlert(imei, dtime, stime, speed, angle, location, latitude, longitude, roadId);
		st.close();
	
		System.out.println("The End");
	}
		
	
}
