package in.co.itracksolution;

import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.LastData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.List;
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
		
		SampleLastDataQuery st = new SampleLastDataQuery();
		
		LastData data = new LastData();
		data.setImei("862170011627815");
		
		
		LastDataDao dao = new LastDataDao(st.conn.getSession());
		
		Row row = dao.selectByImei(data.getImei());
		System.out.print("imei: "+row.getString("imei")+" ");
		System.out.print("data: "+row.getString("data")+" ");
		System.out.println();

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		row = dao.selectByImeiAndDateTime("359231030125239", "2014-12-31 23:20:20");
		if (row != null)
		{
			System.out.print("imei: "+row.getString("imei")+" ");
			System.out.print("device time: "+sdf.format(row.getDate("dtime"))+" ");
			System.out.print("server time: "+sdf.format(row.getDate("stime"))+" ");
			System.out.print("data: "+row.getString("data")+" ");
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
