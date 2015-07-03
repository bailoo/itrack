package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;

import in.co.itracksolution.dao.SpeedAlertDao;
import in.co.itracksolution.dao.TurnAlertDao;
import in.co.itracksolution.dao.XroadLogDao;
import in.co.itracksolution.dao.DistanceLogDao;
import in.co.itracksolution.dao.TravelLogDao;
import in.co.itracksolution.dao.NightLogDao;
import in.co.itracksolution.dao.GapLogDao;

import in.co.itracksolution.model.SpeedAlert;
import in.co.itracksolution.model.TurnAlert;
import in.co.itracksolution.model.XroadLog;
import in.co.itracksolution.model.DistanceLog;
import in.co.itracksolution.model.TravelLog;
import in.co.itracksolution.model.NightLog;
import in.co.itracksolution.model.GapLog;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;

import java.util.Date;
import java.util.TimeZone;
import java.util.Calendar;
import java.util.List;
import java.util.ArrayList;
import java.util.Properties;

import java.text.SimpleDateFormat;

import com.datastax.driver.core.Session;
import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class GetAlerts
{

	CassandraConn conn;
	
	public GetAlerts()
	{
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
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void deleteSpeedAlert()
	{
		
	}
	
	public void close()
	{
		if (conn !=null)
			conn.close();
	}
	

	public static void main(String[] args) 
	{
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		TimeZone tz = TimeZone.getTimeZone("Asia/Kolkata");
		sdf.setTimeZone(tz);	
		GetAlerts st = new GetAlerts();
		Session session = st.conn.getSession();
	
		System.out.println();
	
		/* get and print speed alerts */	
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-29 10:00:00";
		String endDateTime = "2015-06-29 20:00:00";
		Boolean orderAsc = false; // true for ascending , otherwise descending (default) 
		SpeedAlertDao speedAlertDao = new SpeedAlertDao(session);
		ArrayList<SpeedAlert> speedAlertList = speedAlertDao.getSpeedAlertByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Speed Alerts");
		for (SpeedAlert speedAlert : speedAlertList)
		{
			System.out.print("imei: "+speedAlert.getImei()+" ");
			System.out.print("device time: "+sdf.format(speedAlert.getDTime())+" ");
			System.out.print("server time: "+sdf.format(speedAlert.getSTime())+" ");
			System.out.print("speed: "+speedAlert.getSpeed()+" ");
			System.out.print("location id: "+speedAlert.getLocationId()+" ");
			System.out.print("location name: "+speedAlert.getLocationName()+" ");
			System.out.print("latitude: "+speedAlert.getLatitude()+" ");
			System.out.print("longitude: "+speedAlert.getLongitude()+" ");
			System.out.print("roadId: "+speedAlert.getRoadId()+" ");
			System.out.print("roadName: "+speedAlert.getRoadName()+" ");
			System.out.print("logTime: "+sdf.format(speedAlert.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();


		/* get and print turn alerts */	
		imei = "123456"; //Make sure this imei exists
		startDateTime = "2015-06-29 10:00:00";
		endDateTime = "2015-06-29 20:00:00";
		orderAsc = false; // true for ascending , otherwise descending (default) 
		TurnAlertDao turnAlertDao = new TurnAlertDao(session);
		ArrayList<TurnAlert> turnAlertList = turnAlertDao.getTurnAlertByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Turn Alerts");
		for (TurnAlert turnAlert : turnAlertList)
		{
			System.out.print("imei: "+turnAlert.getImei()+" ");
			System.out.print("device time: "+sdf.format(turnAlert.getDTime())+" ");
			System.out.print("server time: "+sdf.format(turnAlert.getSTime())+" ");
			System.out.print("speed: "+turnAlert.getSpeed()+" ");
			System.out.print("angle: "+turnAlert.getAngle()+" ");
			System.out.print("location id: "+turnAlert.getLocationId()+" ");
			System.out.print("location name: "+turnAlert.getLocationName()+" ");
			System.out.print("latitude: "+turnAlert.getLatitude()+" ");
			System.out.print("longitude: "+turnAlert.getLongitude()+" ");
			System.out.print("roadId: "+turnAlert.getRoadId()+" ");
			System.out.print("roadName: "+turnAlert.getRoadName()+" ");
			System.out.print("logTime: "+sdf.format(turnAlert.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();


		/* get and print xroad log*/	
		imei = "123456"; //Make sure this imei exists
		startDateTime = "2015-06-29 10:17:00";
		endDateTime = "2015-06-29 16:18:00";
		orderAsc = false;	// true for ascending , otherwise descending (default) 
		XroadLogDao xroadLogDao = new XroadLogDao(session);
		ArrayList<XroadLog> xroadLogList = xroadLogDao.getXroadLogByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Xroad Log");
		for (XroadLog xroadLog : xroadLogList)
		{
			System.out.print("imei: "+xroadLog.getImei()+" ");
			System.out.print("device time: "+sdf.format(xroadLog.getDTime())+" ");
			System.out.print("server time: "+sdf.format(xroadLog.getSTime())+" ");
			System.out.print("road id: "+xroadLog.getRoadId()+" ");
			System.out.print("road name: "+xroadLog.getRoadName()+" ");
			System.out.print("halt duration: "+xroadLog.getHaltDuration()+" ");
			System.out.print("speed: "+xroadLog.getSpeed()+" ");
			System.out.print("location id: "+xroadLog.getLocationId()+" ");
			System.out.print("location name: "+xroadLog.getLocationName()+" ");
			System.out.print("latitude: "+xroadLog.getLatitude()+" ");
			System.out.print("longitude: "+xroadLog.getLongitude()+" ");
			System.out.print("logTime: "+sdf.format(xroadLog.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();


		/* get and print distance log*/	
		imei = "123456"; //Make sure this imei exists
		startDateTime = "2015-06-29 10:00:00";
		endDateTime = "2015-06-29 16:00:00";
		orderAsc = false;	// true for ascending , otherwise descending (default) 
		DistanceLogDao distanceLogDao = new DistanceLogDao(session);
		ArrayList<DistanceLog> distanceLogList = distanceLogDao.getDistanceLogByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Distance Log");
		for (DistanceLog distanceLog : distanceLogList)
		{
			System.out.print("imei: "+distanceLog.getImei()+" ");
			System.out.print("device time: "+sdf.format(distanceLog.getStartTime())+" ");
			System.out.print("server time: "+sdf.format(distanceLog.getEndTime())+" ");
			System.out.print("avg speed: "+distanceLog.getAvgSpeed()+" ");
			System.out.print("distance: "+distanceLog.getDistance()+" ");
			System.out.print("max speed: "+distanceLog.getMaxSpeed()+" ");
			System.out.print("logTime: "+sdf.format(distanceLog.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();


		/* get and print travel log*/	
		imei = "123456"; //Make sure this imei exists
		startDateTime = "2015-06-29 10:00:00";
		endDateTime = "2015-06-29 16:00:00";
		orderAsc = false;	// true for ascending , otherwise descending (default) 
		TravelLogDao travelLogDao = new TravelLogDao(session);
		ArrayList<TravelLog> travelLogList = travelLogDao.getTravelLogByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Travel Log");
		for (TravelLog travelLog : travelLogList)
		{
			System.out.print("imei: "+travelLog.getImei()+" ");
			System.out.print("start time: "+sdf.format(travelLog.getStartTime())+" ");
			System.out.print("start latitude: "+travelLog.getStartLatitude()+" ");
			System.out.print("start longitude: "+travelLog.getStartLongitude()+" ");
			System.out.print("start location id: "+travelLog.getStartLocationId()+" ");
			System.out.print("start location name: "+travelLog.getStartLocationName()+" ");
			System.out.print("end time: "+sdf.format(travelLog.getEndTime())+" ");
			System.out.print("end latitude: "+travelLog.getEndLatitude()+" ");
			System.out.print("end longitude: "+travelLog.getEndLongitude()+" ");
			System.out.print("end location id: "+travelLog.getEndLocationId()+" ");
			System.out.print("end location name: "+travelLog.getEndLocationName()+" ");
			System.out.print("duration: "+travelLog.getDuration()+" ");
			System.out.print("avg speed: "+travelLog.getAvgSpeed()+" ");
			System.out.print("distance: "+travelLog.getDistance()+" ");
			System.out.print("max speed: "+travelLog.getMaxSpeed()+" ");
			System.out.print("logTime: "+sdf.format(travelLog.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();

		/* get and print night log */	
		imei = "123456"; //Make sure this imei exists
		startDateTime = "2015-06-29 10:00:00";
		endDateTime = "2015-06-29 16:00:00";
		orderAsc = false;	// true for ascending , otherwise descending (default) 
		NightLogDao nightLogDao = new NightLogDao(session);
		ArrayList<NightLog> nightLogList = nightLogDao.getNightLogByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Night Log");
		for (NightLog nightLog : nightLogList)
		{
			System.out.print("imei: "+nightLog.getImei()+" ");
			System.out.print("start time: "+sdf.format(nightLog.getStartTime())+" ");
			System.out.print("start latitude: "+nightLog.getStartLatitude()+" ");
			System.out.print("start longitude: "+nightLog.getStartLongitude()+" ");
			System.out.print("start location id: "+nightLog.getStartLocationId()+" ");
			System.out.print("start location name: "+nightLog.getStartLocationName()+" ");
			System.out.print("end time: "+sdf.format(nightLog.getEndTime())+" ");
			System.out.print("end latitude: "+nightLog.getEndLatitude()+" ");
			System.out.print("end longitude: "+nightLog.getEndLongitude()+" ");
			System.out.print("end location id: "+nightLog.getEndLocationId()+" ");
			System.out.print("end location name: "+nightLog.getEndLocationName()+" ");
			System.out.print("duration: "+nightLog.getDuration()+" ");
			System.out.print("avg speed: "+nightLog.getAvgSpeed()+" ");
			System.out.print("distance: "+nightLog.getDistance()+" ");
			System.out.print("max speed: "+nightLog.getMaxSpeed()+" ");
			System.out.print("logTime: "+sdf.format(nightLog.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();
		
		
		/* get and print gap log */	
		imei = "123456"; //Make sure this imei exists
		startDateTime = "2015-06-29 10:00:00";
		endDateTime = "2015-06-29 16:00:00";
		orderAsc = false;	// true for ascending , otherwise descending (default) 
		GapLogDao gapLogDao = new GapLogDao(session);
		ArrayList<GapLog> gapLogList = gapLogDao.getGapLogByDateTime(imei, startDateTime, endDateTime, orderAsc);
		System.out.println("Gap Log");
		for (GapLog gapLog : gapLogList)
		{
			System.out.print("imei: "+gapLog.getImei()+" ");
			System.out.print("type: "+gapLog.getType()+" ");
			System.out.print("start time: "+sdf.format(gapLog.getStartTime())+" ");
			System.out.print("start latitude: "+gapLog.getStartLatitude()+" ");
			System.out.print("start longitude: "+gapLog.getStartLongitude()+" ");
			System.out.print("start location id: "+gapLog.getStartLocationId()+" ");
			System.out.print("start location name: "+gapLog.getStartLocationName()+" ");
			System.out.print("end time: "+sdf.format(gapLog.getEndTime())+" ");
			System.out.print("end latitude: "+gapLog.getEndLatitude()+" ");
			System.out.print("end longitude: "+gapLog.getEndLongitude()+" ");
			System.out.print("end location id: "+gapLog.getEndLocationId()+" ");
			System.out.print("end location name: "+gapLog.getEndLocationName()+" ");
			System.out.print("logTime: "+sdf.format(gapLog.getLogTime())+" ");
			System.out.println();
		}
		System.out.println();
		System.out.println();



		st.close();
	}
}
