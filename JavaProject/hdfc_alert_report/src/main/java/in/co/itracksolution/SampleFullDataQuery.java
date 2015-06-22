package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.Calendar;
import java.util.List;
import java.util.ArrayList;
import java.util.Properties;
import java.text.SimpleDateFormat;

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class SampleFullDataQuery 
{

	CassandraConn conn;
	
	public SampleFullDataQuery()
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
		SampleFullDataQuery st = new SampleFullDataQuery();
			
		FullDataDao dao = new FullDataDao(st.conn.getSession());
		
		//ResultSet rs= dao.selectByImeiAndDate(data.getImei(), data.getDate());
	
		String imei = "865733021570015"; //Make sure this imei exists
		String startDateTime = "2015-06-17 10:00:00";
		String endDateTime = "2015-06-17 10:10:00";
		//true for dtime, false for stime
		Boolean deviceTime = true;	// true for device time index, otherwise server time
		Boolean orderAsc = true;	// true for ascending , otherwise descending (default) 
		ArrayList<FullData> fullDataList = dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime, deviceTime, orderAsc);

		for (FullData fullData : fullDataList)
		{
			System.out.print("imei: "+fullData.getImei()+" ");
			System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
			System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
			System.out.print("a: "+fullData.pMap.get("a")+" ");
			System.out.print("b: "+fullData.pMap.get("b")+" ");
			System.out.print("c: "+fullData.pMap.get("c")+" ");
			System.out.print("d: "+fullData.pMap.get("d")+" ");
			System.out.print("e: "+fullData.pMap.get("e")+" ");
			System.out.print("f: "+fullData.pMap.get("f")+" ");
			System.out.println();
			String lat = (String) fullData.pMap.get("d");
			System.out.println("lat="+lat);
		}

		st.close();
	}
}
