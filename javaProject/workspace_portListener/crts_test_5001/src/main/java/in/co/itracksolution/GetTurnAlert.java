package in.co.itracksolution;

import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.dao.TurnAlertDao;
import in.co.itracksolution.model.TurnAlert;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.TimeZone;
import java.util.Calendar;
import java.util.List;
import java.util.ArrayList;
import java.util.Properties;
import java.text.SimpleDateFormat;

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class GetTurnAlert 
{

	CassandraConn conn;
	
	public GetTurnAlert()
	{
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
	
	public void deleteTurnAlert()
	{
		
	}
	
	public void close()
	{
		if (conn !=null)
			conn.close();
	}
	

	public static void main(String[] args) 
	{
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		TimeZone tz = TimeZone.getTimeZone("Asia/Kolkata");
		sdf.setTimeZone(tz);	

		GetTurnAlert st = new GetTurnAlert();
			
		TurnAlertDao dao = new TurnAlertDao(st.conn.getSession());
		
		String imei = "123456"; //Make sure this imei exists
		String startDateTime = "2015-06-15 20:17:00";
		String endDateTime = "2015-06-15 20:18:00";
		Boolean orderAsc = false;	// true for ascending , otherwise descending (default) 
		ArrayList<TurnAlert> turnAlertList = dao.getTurnAlertByDateTime(imei, startDateTime, endDateTime, orderAsc);

		for (TurnAlert turnAlert : turnAlertList)
		{
			System.out.print("imei: "+turnAlert.getImei()+" ");
			System.out.print("device time: "+sdf.format(turnAlert.getDTime())+" ");
			System.out.print("server time: "+sdf.format(turnAlert.getSTime())+" ");
			System.out.print("speed: "+turnAlert.getSpeed()+" ");
			System.out.print("angle: "+turnAlert.getAngle()+" ");
			System.out.print("location: "+turnAlert.getLocation()+" ");
			System.out.print("latitude: "+turnAlert.getLatitude()+" ");
			System.out.print("longitude: "+turnAlert.getLongitude()+" ");
			System.out.print("roadId: "+turnAlert.getRoadId()+" ");
			System.out.print("logTime: "+sdf.format(turnAlert.getLogTime())+" ");
			System.out.println();
		}

		st.close();
	}
}
