package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.Date;
import java.util.Properties;

import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.Session;

public class InserterFullDataManyForTest {

	public CassandraConn conn;
	
	public InserterFullDataManyForTest(){
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
	
	public void close(){
		if (conn !=null)
			conn.close();
	}
	
	public static void main(String[] args) {
		
		InserterFullDataManyForTest st = new InserterFullDataManyForTest();
		
		Session session = st.conn.getSession();
		
		PreparedStatement insertStatement = session.prepare("INSERT INTO full_data "
				+ "(imei, date_hour, device_time, data) "
				+ "VALUES ("+
				"?,?,?,?);");
		
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		
		Calendar cal = Calendar.getInstance(); // gets a calendar using the default time zone and locale.
		cal.set(Calendar.MINUTE, 0);
		cal.set(Calendar.SECOND, 0);
		cal.set(Calendar.HOUR_OF_DAY, 0);
		cal.set(Calendar.MILLISECOND, 0);
		
		Date dateHour = cal.getTime();
		Date devTime = dateHour;
		
		int imeinumber = 0;
		
		//inserting for 5000 devices
		for (imeinumber = 0; imeinumber < 5000; imeinumber++){
			
			//inserting for one month
			for (int k=0; k < 30; k++){
				
				//inserting for one day
				for(int j = 0; j < 24; j++){
					
					//Inserting for an hour
					for(int i = 0; i < 3600; i++){
						session.execute(boundStatement.bind(
								""+imeinumber,
								dateHour,
								devTime,
								"<x a=\"NORMAL\" b=\"v1.45C\" c=\"1\" d=\"26.25148N\" e=\"79.86157E\" f=\"0.06\" g=\"2015-01-29 00:00:09\" "
								+ "h=\"2015-01-29 00:00:08\" i=\"2\" j=\"5\" k=\"3\" l=\"5\" m=\"6\" n=\"6\" "
								+ "o=\"3\" p=\"5\" q=\"0\" r=\"12.88\"/>"
						) );
						
						//add devtime with 10 second (each device send every 10 seconds)
						cal = Calendar.getInstance();
						cal.setTime(devTime);
						cal.add(Calendar.SECOND, 10);
						devTime = cal.getTime();
					}
					
					//add one hour of date_hour
					cal = Calendar.getInstance();
					cal.setTime(dateHour);
					cal.add(Calendar.HOUR_OF_DAY, 1);
					dateHour = cal.getTime();
					
					//Set minutes, second and milliseconds in device time back to 00:00:00 and add 1 hour to it
					cal = Calendar.getInstance();
					cal.setTime(devTime);
					cal.add(Calendar.HOUR_OF_DAY, 1);
					cal.set(Calendar.MINUTE, 0);
					cal.set(Calendar.SECOND, 0);
					cal.set(Calendar.MILLISECOND, 0);
					
					devTime = cal.getTime();
					
				}
				
				//add one day of date_hour in date_hour, set hour, min, sec, ms to 00:00:00 for date_hours and devtime
				cal = Calendar.getInstance();
				cal.setTime(dateHour);
				cal.add(Calendar.DATE, 1);
				cal.set(Calendar.HOUR_OF_DAY, 0);
				cal.set(Calendar.MINUTE, 0);
				cal.set(Calendar.SECOND, 0);
				cal.set(Calendar.MILLISECOND, 0);
				dateHour = cal.getTime();
				devTime = dateHour;
			}
			
			//set hour, min, second, miliseonds in date_hour and device_time back to 00:00:00
			cal = Calendar.getInstance(); // gets a calendar using the default time zone and locale.
			cal.set(Calendar.HOUR_OF_DAY, 0);
			cal.set(Calendar.MINUTE, 0);
			cal.set(Calendar.SECOND, 0);
			cal.set(Calendar.MILLISECOND, 0);
			
			dateHour = cal.getTime();
			devTime = dateHour;
			
			//change the device imei
			imeinumber += 1;
		}
		
		
		
		st.close();	
	}
}
