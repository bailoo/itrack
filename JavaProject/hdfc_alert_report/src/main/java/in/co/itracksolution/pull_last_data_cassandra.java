package in.co.itracksolution;

import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.LastData;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.List;
import java.util.Properties;
import java.util.TreeMap;

import com.datastax.driver.core.Row;

public class pull_last_data_cassandra {

	CassandraConn conn;
	
	public pull_last_data_cassandra(){
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
	
	public void deleteLastData(){
		
	}
	
	public void close(){
		if (conn !=null)
			conn.close();
	}
	

	public static void main(String[] args) {
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		LastData data = new LastData();
		
		SampleLastDataQuery st = new SampleLastDataQuery();
		
		data.setImei("865733021570015");
		LastDataDao dao = new LastDataDao(st.conn.getSession());
		LastData lastData = dao.selectByImei(data.getImei());
		
		TreeMap pMap1 = new TreeMap();
		pMap1 = lastData.getPMap();
		
		System.out.print("imei: "+lastData.getImei()+" ");
		System.out.print("stime: "+sdf.format(lastData.getSTime())+" ");
		System.out.print("c: "+pMap1.get("c")+" ");
		System.out.print("d: "+pMap1.get("d")+" ");
		System.out.print("e: "+pMap1.get("e")+" ");
		System.out.print("h: "+pMap1.get("h")+" ");
		System.out.print("s: "+pMap1.get("s")+" ");
		System.out.print("t: "+pMap1.get("t")+" ");
		System.out.println();


		FullData fullData = dao.selectByImeiAndDateTime("865733021570015", "2015-06-17 23:20:20");
		if (fullData != null)
		{			
			System.out.print("imei: "+fullData.getImei()+" ");
			System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
			System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
			System.out.print("a: "+pMap1.get("a")+" ");
			System.out.print("b: "+pMap1.get("b")+" ");
			System.out.print("c: "+pMap1.get("c")+" ");
			System.out.print("d: "+pMap1.get("d")+" ");
			System.out.print("e: "+pMap1.get("e")+" ");
			System.out.print("f: "+pMap1.get("f")+" ");
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
