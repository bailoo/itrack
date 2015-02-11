package in.co.itracksolution;

import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.LastData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.Properties;

public class SampleLastDataDelete {
	CassandraConn conn;
	
	public SampleLastDataDelete(){
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
	
	public void close(){
		if (conn !=null)
			conn.close();
	}
	

	public static void main(String[] args) {
		
		SampleLastDataDelete st = new SampleLastDataDelete();
		
		LastData data = new LastData();
		data.setImei("satuimei"); //the imei
		
		LastDataDao dao = new LastDataDao(st.conn.getSession());
		dao.delete(data);
		
		
		st.close();	
	}
}