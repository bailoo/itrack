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

public class SampleFullDataQuery {

	CassandraConn conn;
	
	public SampleFullDataQuery(){
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
		
		data.setImeih("862170011627815@2015-01-29@02"); //Make sure this imeih exists
		//List<Row> rs= dao.selectByImeiAndDateHour(data.getImeih());
	
		String imei = "862170011627815";	
		String startDateTime = "2015-01-29 10:00:00";	
		String endDateTime = "2015-01-30 15:00:00";	
		List<Row> rs= dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime);
		
		for (Row row : rs) {
			System.out.print("imeih: "+row.getString("imeih")+" ");
			System.out.print("device time: "+sdf.format(row.getDate("dtime"))+" ");
			System.out.print("server time: "+sdf.format(row.getDate("stime"))+" ");
			System.out.print("data: "+row.getString("data")+" ");
			System.out.println();
		}
		
		
		st.close();	
	}
}
