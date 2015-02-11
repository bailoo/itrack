package in.co.itracksolution;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.Properties;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.dao.LastDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;
import in.co.itracksolution.model.LastData;

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
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void close(){
		if (conn !=null)
			conn.close();
	}

	public void insert5000Fulldata(){
		Calendar now = Calendar.getInstance(); // gets a calendar using the default time zone and locale.
		now.set(Calendar.MINUTE, 0);
		now.set(Calendar.SECOND, 0);
		now.set(Calendar.HOUR_OF_DAY, 0);
		now.set(Calendar.MILLISECOND, 0);
		
		
		FullData data = initFulldata(now);
		FullDataDao ops = new FullDataDao(conn.getSession());
		
		int i = 0;
		
		System.out.println("Inserting Full Data");
		for (i = 0; i < 5000; i++) {
			ops.insert(data);
			incrementServerTime(data, now);
		}
	}
	
	public void insertLastdata(){
		
		Calendar now = Calendar.getInstance(); // gets a calendar using the default time zone and locale.
		now.set(Calendar.MINUTE, 0);
		now.set(Calendar.SECOND, 0);
		now.set(Calendar.HOUR_OF_DAY, 0);
		now.set(Calendar.MILLISECOND, 0);
		
		LastData lastData = initLastdata(now);
		LastDataDao lastDao = new LastDataDao(conn.getSession());
		
		System.out.println("Inserting Last Data");
		lastDao.insert(lastData);
		
	}
	
	public static void main(String[] args) {
		
		SampleInsert st = new SampleInsert();
		
		st.insert5000Fulldata();
		st.insertLastdata();
		
		st.close();	
	}
	
	public void incrementServerTime(FullData data, Calendar cal){
		cal.add(Calendar.SECOND, 1);
		data.setServerTime(cal.getTime());
	}
	
	public LastData initLastdata(Calendar now){
		LastData data = new LastData();
		data.setFix(1);
		data.setImei("satuimei");
		data.setIoOne(2219);
		data.setIoTwo(2219);
		data.setIoThree(2219);
		data.setIoFour(2219);
		data.setIoFive(2219);
		data.setIoSix(2219);
		data.setIoSeven(2219);
		data.setIoEight(2219);
		data.setLat(27.35942);
		data.setLon(82.06958);
		data.setMessageType("normal");
		data.setSignalStrength(1);
		data.setSpeed(2);
		data.setSupplyVoltage((float)2.5);
		data.setVersion("v1.53T");
		
		data.setDeviceTime(now.getTime());
		data.setServerTime(now.getTime());
		
		data.setDayMaxSpeed((float)1.2);
		data.setDayMaxSpeedTime(now.getTime());
		data.setLastHaltTime(now.getTime());
		
		
		return data;
	}
	
	public static FullData initFulldata(Calendar now){
		FullData data = new FullData();
		data.setFix(1);
		data.setImei("satuimei");
		data.setIoOne(2219);
		data.setIoTwo(2219);
		data.setIoThree(2219);
		data.setIoFour(2219);
		data.setIoFive(2219);
		data.setIoSix(2219);
		data.setIoSeven(2219);
		data.setIoEight(2219);
		data.setLat(27.35942);
		data.setLon(82.06958);
		data.setMessageType("normal");
		data.setSignalStrength(1);
		data.setSpeed(2);
		data.setSupplyVoltage((float)2.5);
		data.setVersion("v1.53T");
		
		data.setDateHour(now.getTime());
		data.setDeviceTime(now.getTime());
		data.setServerTime(now.getTime());
		
		return data;
	}

}
