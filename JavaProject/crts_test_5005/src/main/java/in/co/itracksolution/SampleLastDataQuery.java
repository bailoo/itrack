package in.co.itracksolution;

import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.LastData;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.TimeZone;
import java.util.List;
import java.util.TreeMap;
import java.util.Properties;

import com.datastax.driver.core.Row;

public class SampleLastDataQuery {

	CassandraConn conn;
	
	public SampleLastDataQuery(){
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
	
	public void deleteLastData(){
		
	}
	
	public void close(){
		if (conn !=null)
			conn.close();
	}
	

	public static void main(String[] args) {
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		TimeZone tz = TimeZone.getTimeZone("Asia/Kolkata");
		sdf.setTimeZone(tz);	

		LastData data = new LastData();
		
		SampleLastDataQuery st = new SampleLastDataQuery();
		
		data.setImei("865733021570015");
		LastDataDao dao = new LastDataDao(st.conn.getSession());
		LastData lastData = dao.selectByImei(data.getImei());
		
		System.out.print("imei: "+lastData.getImei()+" ");
		System.out.print("stime: "+sdf.format(lastData.getSTime())+" ");
		TreeMap pMap = new TreeMap();
		pMap = lastData.getPMap(); 
		System.out.print("c: "+pMap.get("c")+" ");
		System.out.print("d: "+pMap.get("d")+" ");
		System.out.print("e: "+pMap.get("e")+" ");
		System.out.print("h: "+pMap.get("h")+" ");
		System.out.print("s: "+pMap.get("s")+" ");
		System.out.print("t: "+pMap.get("t")+" ");
		System.out.println();


		FullData fullData = dao.selectByImeiAndDateTime("865733021570015", "2015-06-17 23:20:20");
		if (fullData != null)
		{
			System.out.print("imei: "+fullData.getImei()+" ");
			System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
			System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
			pMap = fullData.getPMap(); 
			System.out.print("a: "+pMap.get("a")+" ");
			System.out.print("b: "+pMap.get("b")+" ");
			System.out.print("c: "+pMap.get("c")+" ");
			System.out.print("d: "+pMap.get("d")+" ");
			System.out.print("e: "+pMap.get("e")+" ");
			System.out.print("f: "+pMap.get("f")+" ");
			System.out.println();
		}
		else
		{
			System.out.print("imei not found");
			System.out.println();
		}
		
		st.close();	
	}
}
