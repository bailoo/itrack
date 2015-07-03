package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.dao.DistanceLogDao;
import in.co.itracksolution.model.DistanceLog;

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

public class GetDistanceLog 
{

	CassandraConn conn;
	
	public GetDistanceLog()
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
	
	public void deleteDistanceLog()
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

		GetDistanceLog st = new GetDistanceLog();
			
		DistanceLogDao dao = new DistanceLogDao(st.conn.getSession());
		
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-30 10:00:00";
		String endDateTime = "2015-06-30 16:00:00";
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<DistanceLog> distanceLogList = dao.getDistanceLogByDateTime(imei, startDateTime, endDateTime, orderAsc);

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

		st.close();
	}
}
