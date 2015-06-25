package com.hdfc.logic;

import in.co.itracksolution.SampleFullDataQuery;
import in.co.itracksolution.pull_full_data_cassandra;
import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.model.FullData;

import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.TimeZone;
import java.util.TreeMap;

import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.hdfc.db.mysql.connection;
import com.hdfc.db.mysql.mysql_handler;
import com.hdfc.init.init;
import com.hdfc.report.report_chauraha;
import com.hdfc.report.report_distance;
import com.hdfc.report.report_travel;
import com.hdfc.report.report_turning_violation;
import com.hdfc.utils.utility_class;

public class worker {
	
	public static ArrayList<Integer> vehicle_id = new ArrayList<Integer>();
	public static ArrayList<String> vehicle_name = new ArrayList<String>();
	public static ArrayList<Float> max_speed = new ArrayList<Float>();
	public static ArrayList<String> device_imei_no = new ArrayList<String>();
	public static SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");	
	public static TimeZone tz = TimeZone.getTimeZone("Asia/Kolkata");	
	
	//SampleFullDataQuery st = new SampleFullDataQuery();
	public static pull_full_data_cassandra fd = new pull_full_data_cassandra();
	public static FullData data = new FullData();			
	public static FullDataDao dao = new FullDataDao(fd.conn.getSession());
	
	public static String previous_date1 ="", previous_date2 ="";
	public static double interval=3600.0; //in secs : 1 hour 
	
	//####### TEMPORARY FILE WRITE
	public static FileWriter fw = null;
	public static String tDeviceTime ="", tServerTime ="", q=",", line="";
	public static double tSpeed =0.0;
	public static float tAngle =0.f;
	public static double tLatitude=0.0, tLongitude=0.0;
	//##############################

	public worker() {
		//sdf.setTimeZone(tz);
	}
	
	public static void process_data(int account_id) {				
		//init init_var = new init();
		sdf.setTimeZone(tz);
		connection conn = new connection();
		mysql_handler mh = new mysql_handler();
		mysql_handler.getVehicleInformation(conn, account_id);
		//System.out.println("AftergetVehicleInfo");
		String previous_day = utility_class.getYesterdayDateString();
//		previous_date1 = previous_day+" 00:00:00";
//		previous_date2 = previous_day+" 23:59:59";
		
		//previous_date1 = "2015-06-14 13:19:35";
		//previous_date2 = "2015-06-14 13:20:08";	
//		previous_date1 = "2015-06-13 00:00:00";
//		previous_date2 = "2015-06-15 23:30:37";
		previous_date1 = "2015-04-26 00:00:00";
		previous_date2 = "2015-06-15 23:59:00";
				
		System.out.println("SizeIMEI="+init.device_imei_no.size());
		for(int i=0;i<(init.device_imei_no.size());i++) {			
			
			//####### TEMPORARY
			report_turning_violation.IMEI_No.clear();
			report_turning_violation.turningDeviceTime.clear();
			report_turning_violation.turningServerTime.clear();
			report_turning_violation.turningSpeed.clear();
			report_turning_violation.turningAngle.clear();
			report_turning_violation.turningLatitude.clear();
			report_turning_violation.turningLongitude.clear();
			report_turning_violation.locationCode.clear();
			report_turning_violation.roadID.clear();
			//####################
			
			report_distance.firstdata_flag_distance = 0;
			report_distance.firstdata_flag_speed = 0;
			report_travel.firstdata_flag_travel = 0;
			report_travel.firstdata_flag_halt = 0;
			report_chauraha.firstdata_flag_chauraha = 0;
			
			report_turning_violation.start_flag = 0;
			report_turning_violation.middle_flag = 0;
			
			//System.out.println("Device="+init.device_imei_no.get(i));
			pull_and_process_data(init.vehicle_name.get(i), init.max_speed.get(i), init.device_imei_no.get(i), previous_date1, previous_date2);
			System.out.println("Pullprocess completed..");
			//### PUSH ::DISTANCE REPORT :: ARRAYLIST TO CASSANDRA
			/*report_distance.VehicleID.get(x);
			report_distance.StartTime.add(x);
			report_distance.EndTime.add(x);
			report_distance.AverageSpeed.add(x);
			report_distance.MaxSpeed.add(x);
			report_distance.TotalDistance.add(x);
			report_distance.AlertTime.add(x);
		    
		    push_to_distance_log_cassandra(VehicleID,StartTime,EndTime,AverageSpeed,MaxSpeed,TotalDistance,AlertTime);
		    report_distance.VehicleID.clear();
		    report_distance.StartTime.clear();
		    report_distance.EndTime.clear();
		    report_distance.AverageSpeed.clear();
		    report_distance.MaxSpeed.clear();
		    report_distance.TotalDistance.clear();
		    report_distance.AlertTime.clear();*/
			//###### TEMPORARY WRITE
			//System.out.println("CALL="+i);
			write_to_database(init.device_imei_no.get(i));
			System.out.println("Processed IMEI:"+init.device_imei_no.get(i)+" -"+i);
		}
		
		fd.close();
		//System.out.println("Point3");
	}
	
	public static void pull_and_process_data(String vehicle_name, Float max_speed, String imei, String startDateTime, String endDateTime) {				
		
		String device_time ="", sts ="", lat_str ="", lng_str ="";
		double lat=0.0, lng=0.0, speed =0.0;

		Float ax = 0.0f, ay = 0.0f, az =0.0f,	bx=0.0f, by=0.0f, bz=0.0f, cx=0.0f, cy=0.0f, cz=0.0f;
		report_distance rep_distance = new report_distance();
		report_travel rep_travel = new report_travel();
		//System.out.println("Point2");
		//data.setImei("862170011627815"); //Make sure this imei exists
		//data.setDate("2015-01-29");
		//ResultSet rs= dao.selectByImeiAndDate(data.getImei(), data.getDate());	
		/*String imei = "359231030125239";
		String startDateTime = "2015-01-01 10:00:00";
		String endDateTime = "2015-01-01 15:00:00";*/
				
		Boolean deviceTime = true;	// true for device time index, otherwise server time
		Boolean orderAsc = true;	// true for ascending , otherwise descending (default) 


		ArrayList<FullData> fullDataList = dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime, deviceTime, orderAsc);

		String tmp_lat ="", tmp_lng="";
		int data_size = fullDataList.size();		
		int record_count =1;
		//System.out.println("DataSize="+data_size);
		
		for (FullData fullData : fullDataList)
		{
			TreeMap pMap1 = new TreeMap();
			pMap1 = fullData.getPMap();
			/*System.out.print("imei: "+fullData.getImei()+" ");
			System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
			System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
			System.out.print("a: "+pMap1.get("a")+" ");
			System.out.print("b: "+pMap1.get("b")+" ");
			System.out.print("c: "+pMap1.get("c")+" ");
			System.out.print("d: "+pMap1.get("d")+" ");
			System.out.print("e: "+pMap1.get("e")+" ");
			System.out.print("f: "+pMap1.get("f")+" ");
			System.out.println();*/
				
			//System.out.println("Lat="+fullData.pMap.get("d")+" ,Lng="+fullData.pMap.get("e"));
			tmp_lat = (String) pMap1.get("d");
			tmp_lng = (String) pMap1.get("e");
			
			//System.out.println("Lat="+tmp_lat+" ,Lng="+tmp_lng);
			if( (!tmp_lat.equals("")) && (!tmp_lng.equals("")) ) {
					
				/*System.out.print("imei: "+fullData.getImei()+" ");
				System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
				System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
				System.out.print("a: "+pMap1.get("a")+" ");
				System.out.print("b: "+pMap1.get("b")+" ");
				System.out.print("c: "+pMap1.get("c")+" ");
				System.out.print("d: "+pMap1.get("d")+" ");
				System.out.print("e: "+pMap1.get("e")+" ");
				System.out.print("f: "+pMap1.get("f")+" ");
				System.out.println();*/	
				
				//System.out.println("device time: "+sdf.format(fullData.getDTime())+" ,lat="+tmp_lat+" ,tmp_lng="+tmp_lng);
				device_time = sdf.format(fullData.getDTime());
				sts = sdf.format(fullData.getSTime());
				tmp_lat = tmp_lat.substring(0,tmp_lat.length()-1);
				tmp_lng = tmp_lng.substring(0,tmp_lng.length()-1);
				lat = Double.parseDouble(tmp_lat);
				lng = Double.parseDouble(tmp_lng);
				speed = Double.parseDouble((String) pMap1.get("f"));
				//CHECK ALERTS
				CHECK_ALERTS(imei, startDateTime, endDateTime, interval, device_time, sts, lat, lng, speed, max_speed, data_size, record_count, rep_distance, rep_travel);
				record_count++;
			}
		}
	}

	
	public static void CHECK_ALERTS(String imei, String startdate, String enddate, double interval, String device_time, String sts, double lat, double lng, double speed, Float max_speed, int data_size, int record_count, report_distance rep_distance, report_travel rep_travel) {
		//CHECK AND PUSH
//		report_distance.action_report_distance(imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
//		report_travel.action_report_travel(imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
		report_turning_violation.action_report_truning_violation(imei, device_time, sts, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
		
	}
	
	/*public static void CHECK_TURNING_VIOLATION(int vehicle_id, Float max_speed, String imei, String device_time, String sts, Double lat, Double lng) {
		//CHECK AND PUSH
	}
	public static void CHECK_NIGHT_MOVEMENT(int vehicle_id, Float max_speed, String imei, String device_time, String sts, Double lat, Double lng) {
		//CHECK AND PUSH
	}
	public static void CHECK_DISTANCE(int vehicle_id, String imei, String startdate, String enddate, long interval, String device_time, String sts, double lat, double lng, double speed, Float max_speed) {
		//CHECK AND PUSH
		
	}
	public static void CHECK_CHAURAHA(int vehicle_id, Float max_speed, String imei, String device_time, String sts, Double lat, Double lng) {
		//CHECK AND PUSH
	}*/
	
	public static void write_to_database(String imei) {
		
		//String filename= "D:\\itrack_vts/hdfc_alert_report/"+imei+".csv";
		String filename= "/mnt/hdfc_report/csv/"+imei+".csv";
		line = "DeviceTime,ServerTime,Speed (Km/hr),Angle (Deg),Latitude,Longitude\n";
		try {
			fw = new FileWriter(filename,true);
		} catch (IOException e3) {
			// TODO Auto-generated catch block
			e3.printStackTrace();
		} //the true will append the new data	
		
		//System.out.println("Size="+report_turning_violation.IMEI_No.size());
		if(report_turning_violation.IMEI_No.size() > 0) {
			for(int i=0;i<report_turning_violation.IMEI_No.size();i++) {
				
				tDeviceTime = report_turning_violation.turningDeviceTime.get(i);
				tServerTime = report_turning_violation.turningServerTime.get(i);
				tSpeed = report_turning_violation.turningSpeed.get(i);
				tAngle = report_turning_violation.turningAngle.get(i);
				tLatitude = report_turning_violation.turningLatitude.get(i);
				tLongitude = report_turning_violation.turningLongitude.get(i);						
				
				line += tDeviceTime+q+tServerTime+q+tSpeed+q+tAngle+q+tLatitude+q+tLongitude+"\n";
				//System.out.println("filename="+filename+" ,line="+line);
			}
		    
			try {
				fw.write(line);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}//appends the string to the file
		}
	    
		System.out.println("Write to Alert");
		
	    try {
			fw.close();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
}
