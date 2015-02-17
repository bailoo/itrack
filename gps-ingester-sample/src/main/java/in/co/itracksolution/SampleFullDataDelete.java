package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
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
		data.setImeih("862170011627815@2015-2-17@0"); //Make sure this imeih exists
		
		Calendar cal = Calendar.getInstance();
		cal.set(Calendar.MINUTE, 0);//minute, second and millisecond must be zero because our granularity only untuil hour
		cal.set(Calendar.SECOND, 0);
		cal.set(Calendar.MILLISECOND, 0);
		cal.set(Calendar.HOUR_OF_DAY, 0);
		
		
		data.setDTime(cal.getTime());//make sure the device time exist for that imei
		
		FullDataDao dao = new FullDataDao(st.conn.getSession());
		dao.delete(data);
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		
		
		System.out.println("Full Data with imeih: "+data.getImeih()+" and dtime: "+sdf.format(data.getDTime())+" is deleted");
		
		st.close();	
	}
}
