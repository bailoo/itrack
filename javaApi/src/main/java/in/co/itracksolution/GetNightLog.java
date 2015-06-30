package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.dao.NightLogDao;
import in.co.itracksolution.model.NightLog;

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

public class GetNightLog 
{

	CassandraConn conn;
	
	public GetNightLog()
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
	
	public void deleteNightLog()
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

		GetNightLog st = new GetNightLog();
			
		NightLogDao dao = new NightLogDao(st.conn.getSession());
		
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-29 10:00:00";
		String endDateTime = "2015-06-29 16:00:00";
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<NightLog> nightLogList = dao.getNightLogByDateTime(imei, startDateTime, endDateTime, orderAsc);

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

		st.close();
	}
}
