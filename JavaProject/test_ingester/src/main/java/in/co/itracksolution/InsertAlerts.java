package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.SpeedAlert;
import in.co.itracksolution.model.TurnAlert;
import in.co.itracksolution.model.XroadLog;
import in.co.itracksolution.model.DistanceLog;
import in.co.itracksolution.model.NightLog;
import in.co.itracksolution.model.GapLog;
import in.co.itracksolution.model.TravelLog;
import in.co.itracksolution.model.XroadLog;
import in.co.itracksolution.dao.SpeedAlertDao;
import in.co.itracksolution.dao.TurnAlertDao;
import in.co.itracksolution.dao.XroadLogDao;
import in.co.itracksolution.dao.DistanceLogDao;
import in.co.itracksolution.dao.NightLogDao;
import in.co.itracksolution.dao.GapLogDao;
import in.co.itracksolution.dao.TravelLogDao;
import in.co.itracksolution.dao.XroadLogDao;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.lang.String;
import java.util.Date;
import java.util.TimeZone;
import java.util.Calendar;
import java.util.Properties;

import com.datastax.driver.core.Session;

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
		String type = "nogps";
		String dtime = "2015-06-30 12:17:18";
		String stime = "2015-06-30 12:17:38";
		String starttime = "2015-06-30 12:27:18";
		String endtime = "2015-06-30 15:37:38";
		int duration = (int)84;
		int haltduration = (int)34;
		float speed = (float)30.1;
		float avgspeed = (float)44.1;
		float maxspeed = (float)75.1;
		float distance = (float)369.4;
		float angle = (float)35.3;
		String locationId = "98765";
		String locationName = "Dwarka Mor";
		String latitude = "21.4568N";
		String longitude = "82.22434E";
		String startlocationid = "54321";
		String startlocationname = "Dwarka Mor";
		String startlatitude = "20.4568N";
		String startlongitude = "85.23234E";
		String endlocationid = "6543";
		String endlocationname = "C R Park";
		String endlatitude = "22.1568N";
		String endlongitude = "79.88434E";
		String roadId = "4321";
		String roadName = "Shahjahan Road";
		
		InsertAlerts st = new InsertAlerts();
		Session session = st.conn.getSession();

		SpeedAlertDao speedAlertDao = new SpeedAlertDao(session);
		speedAlertDao.insertSpeedAlert(imei, dtime, stime, speed, locationId, locationName, latitude, longitude, roadId, roadName);
		
		TurnAlertDao turnAlertDao = new TurnAlertDao(session);
		turnAlertDao.insertTurnAlert(imei, dtime, stime, speed, angle, locationId, locationName, latitude, longitude, roadId, roadName);
		
		XroadLogDao xroadLogDao = new XroadLogDao(session);
		xroadLogDao.insertXroadLog(imei, dtime, stime, roadId, roadName, haltduration, speed, locationId, locationName, latitude, longitude);
		
		DistanceLogDao distanceLogDao = new DistanceLogDao(session);
		distanceLogDao.insertDistanceLog(imei, starttime, endtime, avgspeed, distance, maxspeed); 

		NightLogDao nightLogDao = new NightLogDao(session);
		nightLogDao.insertNightLog(imei, starttime, startlatitude, startlongitude, startlocationid, startlocationname, endtime, endlatitude, endlongitude, endlocationid, endlocationname, duration, avgspeed, distance, maxspeed);

		GapLogDao gapLogDao = new GapLogDao(session);
		gapLogDao.insertGapLog(imei, type, starttime, startlatitude, startlongitude, startlocationid, startlocationname, endtime, endlatitude, endlongitude, endlocationid, endlocationname);

		TravelLogDao travelLogDao = new TravelLogDao(session);
		travelLogDao.insertTravelLog(imei, starttime, startlatitude, startlongitude, startlocationid, startlocationname, endtime, endlatitude, endlongitude, endlocationid, endlocationname, duration, avgspeed, distance, maxspeed);

		st.close();
	
		System.out.println("The End");
	}
		
	
}
