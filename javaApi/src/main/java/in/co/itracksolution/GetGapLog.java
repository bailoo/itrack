package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.dao.GapLogDao;
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

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class GetGapLog 
{

	CassandraConn conn;
	
	public GetGapLog()
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
	
	public void deleteGapLog()
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

		GetGapLog st = new GetGapLog();
			
		GapLogDao dao = new GapLogDao(st.conn.getSession());
		
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-29 10:00:00";
		String endDateTime = "2015-06-29 16:00:00";
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<GapLog> gapLogList = dao.getGapLogByDateTime(imei, startDateTime, endDateTime, orderAsc);

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

		st.close();
	}
}
