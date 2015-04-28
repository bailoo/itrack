package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;
import in.co.itracksolution.model.LastData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.Calendar;
import java.util.Properties;

public class SampleInsert {
	CassandraConn conn;
	
	public SampleInsert(){
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
			e.printStackTrace();
		}
	}
	
	public void close(){
		if (conn !=null)
			conn.close();
	}

	public void insertFulldata(){
		Calendar now = Calendar.getInstance(); // gets a calendar using the default time zone and locale.
		now.set(Calendar.MINUTE, 0);
		now.set(Calendar.SECOND, 0);
		now.set(Calendar.HOUR_OF_DAY, 0);
		now.set(Calendar.MILLISECOND, 0);
		
		String imei = "862170011627815";
		String data = "N;v1.45C;1;26.25148;79.86157;0.06;2015-01-29@00:00:09;2;5;3;5;6;6;3;5;0;12.88";
		String dtime = "2015-01-29 00:00:09";
		imei += "@2015-01-29@00";
		/*imei += "@"+now.get(Calendar.YEAR)+"-"+
				(now.get(Calendar.MONTH)+1)+"-"+ //month +1 because it starts (january) as 0
				now.get(Calendar.DATE)+"@"+
				now.get(Calendar.HOUR_OF_DAY);
		*/
		
		FullData fullData = new FullData(imei, dtime, now.getTime(), data);
		FullDataDao ops = new FullDataDao(conn.getSession());
		
		System.out.println("Inserting Full Data with imeih: "+imei);

		ops.insert(fullData);
		
	}
	
	public void insertLastdata(){
			
		String imei = "862170011627815";
		
		String data = "N;v1.45C;1;26.25148;79.86157;0.06;2015-01-29@00:00:09;2015-01-29@00:00:09;2;5;3;5;6;6;3;5;0;12.88";
		
		LastData lastData = new LastData(imei, data);
		LastDataDao lastDao = new LastDataDao(conn.getSession());
		
		System.out.println("Inserting Last Data with imei: "+imei);
		lastDao.insert(lastData);
		
	}
	
	public static void main(String[] args) {
		
		SampleInsert st = new SampleInsert();
		
		st.insertFulldata();
		st.insertLastdata();
		
		st.close();	
	}
		
	
}
