package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;
import in.co.itracksolution.model.LastData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.TimeZone;
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
		TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(IST); // gets a calendar using the default time zone and locale.
		now.setTimeZone(IST);
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
	
	public void insertLastdata(String imei, String day, String data){
		TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(IST); // gets a calendar using the default time zone and locale.
		now.setTimeZone(IST);
		/*
		now.set(Calendar.MINUTE, 0);
		now.set(Calendar.SECOND, 0);
		now.set(Calendar.HOUR_OF_DAY, 0);
		now.set(Calendar.MILLISECOND, 0);
		
		Calendar day = Calendar.getInstance(IST);
		day.setTimeZone(IST);
		day.set(2015,1,1,0,0,0);
		*/
	
		LastData lastData = new LastData(imei, day, now.getTime(), data);
		LastDataDao lastDao = new LastDataDao(conn.getSession());
		
		System.out.println("Inserting Last Data with imei: "+imei+" on day: "+day);
		lastDao.insert(lastData);
		
	}
	
	public static void main(String[] args) {
		
		SampleInsert st = new SampleInsert();
		
		//st.insertFulldata();
		
		String imei = "862170011627815";		
		//$last_params = array('a','b','c','d','e','f','g','i','j','k','l','m','n','o','p','q','r','s','t','u','ci','ax','ay','az','mx','my','mz','bx','by','bz');
		String data = "N;v1.45C;1;26.25158;79.86557;0.06;2015-01-02@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-01-02", data);
		
		data = "N;v1.45C;1;24.25248;78.36157;0.06;2015-01-03@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-01-03", data);
		
		data = "N;v1.45C;1;27.25248;78.36757;0.06;2015-01-05@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-01-05", data);
		
		data = "N;v1.45C;1;27.25748;77.86757;0.06;2015-01-06@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-01-06", data);
		
		data = "N;v1.45C;1;16.25118;79.81157;0.06;2015-01-18@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-01-18", data);
		
		data = "N;v1.45C;1;22.25148;72.26257;0.06;2015-01-21@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-01-21", data);
		
		data = "N;v1.45C;1;29.25949;99.86957;0.06;2015-02-03@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-02-03", data);
		
		data = "N;v1.45C;1;56.25548;59.85157;0.06;2015-02-09@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-02-09", data);
		
		data = "N;v1.45C;1;28.25148;78.88157;0.06;2015-02-11@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-02-11", data);
		
		data = "N;v1.45C;1;23.35148;73.83357;0.06;2015-02-16@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-02-16", data);
		
		data = "N;v1.45C;1;22.22248;79.82257;0.06;2015-02-21@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-02-21", data);
		
		data = "N;v1.45C;1;20.20048;70.86057;0.06;2015-03-07@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-03-07", data);
		
		data = "N;v1.45C;1;28.25148;89.86857;0.06;2015-03-09@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-03-09", data);
		
		data = "N;v1.45C;1;27.25778;77.87777;0.06;2015-03-11@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-03-11", data);
		
		data = "N;v1.45C;1;29.25149;79.36157;0.06;2015-03-18@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-03-18", data);
		
		data = "N;v1.45C;1;23.25648;69.86157;0.06;2015-03-27@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-03-27", data);
		
		data = "N;v1.45C;1;28.28848;88.86157;0.06;2015-05-07@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-05-07", data);
		
		data = "N;v1.45C;1;25.25342;73.86456;0.06;2015-05-11@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-05-11", data);
		
		data = "N;v1.45C;1;23.25148;29.82137;0.06;2015-05-21@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-05-21", data);
		
		data = "N;v1.45C;1;23.23348;72.87157;0.06;2015-06-11@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-06-11", data);
		
		data = "N;v1.45C;1;22.23348;74.45157;0.06;2015-08-21@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
		st.insertLastdata(imei, "2015-08-21", data);
		
		
		st.close();	
	}
		
	
}
