package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.Properties;

public class SampleFullDataDelete {
	
	
	CassandraConn conn;
	
	public SampleFullDataDelete(){
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
		
		SampleFullDataDelete st = new SampleFullDataDelete();
		
		FullData data = new FullData();
		data.setImei("satuimei"); //the imei
		
		Calendar cal = Calendar.getInstance();
		cal.set(Calendar.MINUTE, 0);//minute, second and millisecond must be zero because our granularity only untuil hour
		cal.set(Calendar.SECOND, 0);
		cal.set(Calendar.MILLISECOND, 0);
		
		cal.set(Calendar.YEAR, 2015);//set year
		cal.set(Calendar.MONTH, Calendar.FEBRUARY);//set month
		cal.set(Calendar.DATE, 11);//set date
		cal.set(Calendar.HOUR_OF_DAY, 0);//set hour
		
		
		data.setDateHour(cal.getTime());
		
		FullDataDao dao = new FullDataDao(st.conn.getSession());
		dao.delete(data);
		
		
		st.close();	
	}
}
