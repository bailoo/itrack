package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.dao.XroadLogDao;
import in.co.itracksolution.model.XroadLog;

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

public class GetXroadLog 
{

	CassandraConn conn;
	
	public GetXroadLog()
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
	
	public void deleteXroadLog()
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

		GetXroadLog st = new GetXroadLog();
			
		XroadLogDao dao = new XroadLogDao(st.conn.getSession());
		
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-29 10:17:00";
		String endDateTime = "2015-06-29 16:18:00";
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<XroadLog> xroadLogList = dao.getXroadLogByDateTime(imei, startDateTime, endDateTime, orderAsc);

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

		st.close();
	}
}
