package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.List;
import java.util.Properties;
import java.text.SimpleDateFormat;

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

public class pull_full_data_cassandra {

	public CassandraConn conn;
	
	public pull_full_data_cassandra(){
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
	
	public void deleteFullData(){
		
	}
	
	public void close(){
		if (conn !=null)
			conn.close();
	}
	

	public static void main(String[] args) {
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		SampleFullDataQuery st = new SampleFullDataQuery();
		FullData data = new FullData();
			
		FullDataDao dao = new FullDataDao(st.conn.getSession());
		
		//data.setImei("862170011627815"); //Make sure this imei exists
		//data.setDate("2015-01-29");
		//ResultSet rs= dao.selectByImeiAndDate(data.getImei(), data.getDate());
	
		String imei = "359231030125239";
		String startDateTime = "2015-01-01 10:00:00";
		String endDateTime = "2015-01-01 15:00:00";
		ResultSet rs = dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime);
		List<Row> rowlist = rs.all();	
		for (Row row : rowlist) {
			System.out.print("imei: "+row.getString("imei")+" ");
			System.out.print("device time: "+sdf.format(row.getDate("dtime"))+" ");
			System.out.print("server time: "+sdf.format(row.getDate("stime"))+" ");
			System.out.print("data: "+row.getString("data")+" ");
			System.out.println();
		}
		st.close();	
	}
}