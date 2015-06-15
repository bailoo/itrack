package in.co.itracksolution;

import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.db.CassandraConn;
import in.co.itracksolution.model.FullData;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.Calendar;
import java.util.List;
import java.util.ArrayList;
import java.util.Properties;
import java.text.SimpleDateFormat;

import com.datastax.driver.core.Row;
import com.datastax.driver.core.ResultSet;

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
		
		//data.setImei("862170011627815"); //Make sure this imei exists
		//data.setDate("2015-01-29");
		//ResultSet rs= dao.selectByImeiAndDate(data.getImei(), data.getDate());
	
		String imei = "12345";
		String startDateTime = "2015-06-15 00:00:00";
		String endDateTime = "2015-06-15 23:59:59";
		//true for dtime, false for stime
		ArrayList<ArrayList> rowList = dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime, true);
		//ArrayList fullParams = new ArrayList("a","b","c","d","e","f","i","j","k","l","m","n","o","p","q","r","ci","ax","ay","az","mx","my","mz","bx","by","bz");
		for (ArrayList row : rowList) {

			imei = (String)row.get(0);
			Date dtime = (Date)row.get(1);
			Date stime = (Date)row.get(2);
			String a = (String)row.get(3);
			String b = (String)row.get(4);
			String c = (String)row.get(5);
			String d = (String)row.get(6);
			String e = (String)row.get(7);
			String f = (String)row.get(8);
			String i = (String)row.get(9);
			String j = (String)row.get(10);
			String k = (String)row.get(11);
			String l = (String)row.get(12);
			String m = (String)row.get(13);
			String n = (String)row.get(14);
			String o = (String)row.get(15);
			String p = (String)row.get(16);
			String q = (String)row.get(17);
			String r = (String)row.get(18);
			/*String ci = (String)row.get(19);
			String ax = (String)row.get(20);
			String ay = (String)row.get(21);
			String az = (String)row.get(22);
			String mx = (String)row.get(23);
			String my = (String)row.get(24);
			String mz = (String)row.get(25);
			String bx = (String)row.get(26);
			String by = (String)row.get(27);
			String bz = (String)row.get(28);
			*/
			System.out.print("imei: "+imei+" ");
			System.out.print("device time: "+sdf.format(dtime)+" ");
			System.out.print("server time: "+sdf.format(stime)+" ");
			System.out.print("a: "+a+" ");
			System.out.print("b: "+b+" ");
			System.out.print("c: "+c+" ");
			System.out.print("d: "+d+" ");
			System.out.print("e: "+e+" ");
			System.out.print("f: "+f+" ");
			System.out.println();
		}
		st.close();
	}
}
