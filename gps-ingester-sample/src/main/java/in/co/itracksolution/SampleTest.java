package in.co.itracksolution;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.Properties;

import in.co.itracksolution.dao.FullDataInsertDao;
import in.co.itracksolution.dao.LastDataInsertDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;
import in.co.itracksolution.model.LastData;

public class SampleTest {
	
	CassandraConn conn;
	
	public SampleTest(){
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
		now.set(Calendar.HOUR, 0);
		
		
		FullData data = initFulldata(now);
		FullDataInsertDao ops = new FullDataInsertDao(conn.getSession());
		
		int i = 0;
		
		System.out.println("Inserting Full Data");
		for (i = 0; i < 5000; i++) {
			ops.insert(data);
			incrementServerTime(data, now);
		}
	}
	
	public void insert5000Lastdata(){
		
		Calendar now = Calendar.getInstance(); // gets a calendar using the default time zone and locale.
		now.set(Calendar.MINUTE, 0);
		now.set(Calendar.SECOND, 0);
		now.set(Calendar.HOUR, 0);
		
		now.set(Calendar.MINUTE, 0);
		now.set(Calendar.SECOND, 0);
		now.set(Calendar.HOUR, 0);
		
		LastData lastData = initLastdata(now);
		LastDataInsertDao lastDao = new LastDataInsertDao(conn.getSession());
		
		int i = 0;
		
		System.out.println("Inserting Last Data");
		for (i = 0; i < 5000; i++) {
			lastDao.insert(lastData);
			incrementServerTime(lastData, now);
		}
	}
	
	public static void main(String[] args) {
		
		SampleTest st = new SampleTest();
		
		st.insert5000Fulldata();
		st.insert5000Lastdata();
		
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
		
		data.setDateHour(now.getTime());
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
