package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.TimeZone;
import java.util.Calendar;
import java.util.List;
import java.util.TreeMap;
import java.util.ArrayList;
import java.util.Properties;
import java.text.SimpleDateFormat;

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class pull_full_data_cassandra 
{

	public CassandraConn conn;
	
	public pull_full_data_cassandra()
	{
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
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void deleteFullData()
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

		SampleFullDataQuery st = new SampleFullDataQuery();
			
		FullDataDao dao = new FullDataDao(st.conn.getSession());
		
		String imei = "865733021562939"; //Make sure this imei exists
		String startDateTime = "2015-06-14 09:30:15";
		String endDateTime = "2015-06-14 09:30:37";
		//true for dtime, false for stime
		Boolean deviceTime = true;	// true for device time index, otherwise server time
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<FullData> fullDataList = dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime, deviceTime, orderAsc);

		for (FullData fullData : fullDataList)
		{
			System.out.print("imei: "+fullData.getImei()+" ");
			System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
			System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
			TreeMap pMap1 = new TreeMap();
			pMap1 = fullData.getPMap(); 
			System.out.print("a: "+pMap1.get("a")+" ");
			System.out.print("b: "+pMap1.get("b")+" ");
			System.out.print("c: "+pMap1.get("c")+" ");
			System.out.print("d: "+pMap1.get("d")+" ");
			System.out.print("e: "+pMap1.get("e")+" ");
			System.out.print("f: "+pMap1.get("f")+" ");
			System.out.println();
		}

		st.close();
	}
}
