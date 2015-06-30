package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.dao.TravelLogDao;
import in.co.itracksolution.model.TravelLog;

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

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class GetTravelLog 
{

	CassandraConn conn;
	
	public GetTravelLog()
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
	
	public void deleteTravelLog()
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

		GetTravelLog st = new GetTravelLog();
			
		TravelLogDao dao = new TravelLogDao(st.conn.getSession());
		
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-29 10:00:00";
		String endDateTime = "2015-06-29 16:00:00";
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<TravelLog> travelLogList = dao.getTravelLogByDateTime(imei, startDateTime, endDateTime, orderAsc);

		for (TravelLog travelLog : travelLogList)
		{
			System.out.print("imei: "+travelLog.getImei()+" ");
			System.out.print("start time: "+sdf.format(travelLog.getStartTime())+" ");
			System.out.print("start latitude: "+travelLog.getStartLatitude()+" ");
			System.out.print("start longitude: "+travelLog.getStartLongitude()+" ");
			System.out.print("start location: "+travelLog.getStartLocation()+" ");
			System.out.print("end time: "+sdf.format(travelLog.getEndTime())+" ");
			System.out.print("end latitude: "+travelLog.getEndLatitude()+" ");
			System.out.print("end longitude: "+travelLog.getEndLongitude()+" ");
			System.out.print("end location: "+travelLog.getEndLocation()+" ");
			System.out.print("duration: "+travelLog.getDuration()+" ");
			System.out.print("avg speed: "+travelLog.getAvgSpeed()+" ");
			System.out.print("distance: "+travelLog.getDistance()+" ");
			System.out.print("max speed: "+travelLog.getMaxSpeed()+" ");
			System.out.print("logTime: "+sdf.format(travelLog.getLogTime())+" ");
			System.out.println();
		}

		st.close();
	}
}
