package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.SpeedAlert;
import in.co.itracksolution.model.TurnAlert;
import in.co.itracksolution.model.DistanceLog;
import in.co.itracksolution.model.NightLog;
import in.co.itracksolution.dao.SpeedAlertDao;
import in.co.itracksolution.dao.TurnAlertDao;
import in.co.itracksolution.dao.DistanceLogDao;
import in.co.itracksolution.dao.NightLogDao;

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

	public static void main(String[] args) 
	{
		String imei = "123456";
		String dtime = "2015-06-29 13:17:18";
		String stime = "2015-06-29 13:17:38";
		String starttime = "2015-06-29 12:27:18";
		String endtime = "2015-06-29 18:37:38";
		float speed = (float)30.1;
		float avgspeed = (float)44.1;
		float maxspeed = (float)75.1;
		float distance = (float)369.4;
		float angle = (float)35.3;
		String location = "Dwarka Mor";
		String latitude = "21.4568N";
		String longitude = "82.22434E";
		String roadId = "Shahjahan Road";
		
		InsertAlerts st = new InsertAlerts();
		SpeedAlertDao speedAlertDao = new SpeedAlertDao(st.conn.getSession());
		speedAlertDao.insertSpeedAlert(imei, dtime, stime, speed, location, latitude, longitude, roadId);
		
		TurnAlertDao turnAlertDao = new TurnAlertDao(st.conn.getSession());
		turnAlertDao.insertTurnAlert(imei, dtime, stime, speed, angle, location, latitude, longitude, roadId);
		//dao.insertDistanceLog(imei, starttime, endtime, avgspeed, distance, maxspeed); 
		st.close();
	
		System.out.println("The End");
	}
		
	
}
