package com.hdfc.logic;

import in.co.itracksolution.SampleFullDataQuery;
import in.co.itracksolution.pull_full_data_cassandra;
import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.model.FullData;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

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
	//SampleFullDataQuery st = new SampleFullDataQuery();
	public static pull_full_data_cassandra fd = new pull_full_data_cassandra();
	public static FullData data = new FullData();			
	public static FullDataDao dao = new FullDataDao(fd.conn.getSession());
	
	public static String previous_date1 ="", previous_date2 ="";
	public static double interval=3600.0; //in secs : 1 hour 

	public worker() {
		
	}
	
	public static void process_data(int account_id) {				
		//init init_var = new init();
		connection conn = new connection();
		mysql_handler mh = new mysql_handler();
		mh.getVehicleInformation(conn, account_id);
		//System.out.println("AftergetVehicleInfo");
		String previous_day = utility_class.getYesterdayDateString();
//		previous_date1 = previous_day+" 00:00:00";
//		previous_date2 = previous_day+" 23:59:59";
		
		previous_date1 = "2015-06-15 00:00:00";
		previous_date2 = "2015-06-15 23:59:59";		
				
		System.out.println("AftergetVehicleInfo="+init.vehicle_id.size());
		for(int i=0;i<init.vehicle_id.size();i++) {			
			report_distance.firstdata_flag_distance = 0;
			report_distance.firstdata_flag_speed = 0;
			report_travel.firstdata_flag_travel = 0;
			report_travel.firstdata_flag_halt = 0;
			report_chauraha.firstdata_flag_chauraha = 0;
			
			pull_and_process_data(init.vehicle_id.get(i), init.vehicle_name.get(i), init.max_speed.get(i), init.device_imei_no.get(i), previous_date1, previous_date2);
			
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
		}
	}
	
	public static void pull_and_process_data(int vehicle_id, String vehicle_name, Float max_speed, String imei, String startDateTime, String endDateTime) {				
		System.out.println("In PullProcess");
		String device_time ="", sts ="", lat_str ="", lng_str ="";
		double lat=0.0, lng=0.0, speed =0.0;

		Float ax = 0.0f, ay = 0.0f, az =0.0f,	bx=0.0f, by=0.0f, bz=0.0f, cx=0.0f, cy=0.0f, cz=0.0f;
		report_distance rep_distance = new report_distance();
		report_travel rep_travel = new report_travel();

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
		
		for (FullData fullData : fullDataList)
		{
			System.out.print("imei: "+fullData.getImei()+" ");
			System.out.print("device time: "+sdf.format(fullData.getDTime())+" ");
			System.out.print("server time: "+sdf.format(fullData.getSTime())+" ");
			System.out.print("a: "+fullData.pMap.get("a")+" ");
			System.out.print("b: "+fullData.pMap.get("b")+" ");
			System.out.print("c: "+fullData.pMap.get("c")+" ");
			System.out.print("d: "+fullData.pMap.get("d")+" ");
			System.out.print("e: "+fullData.pMap.get("e")+" ");
			System.out.print("f: "+fullData.pMap.get("f")+" ");
			System.out.println();
				
			device_time = sdf.format(fullData.getDTime());
			sts = sdf.format(fullData.getSTime());
			tmp_lat = (String) fullData.pMap.get("d");
			tmp_lng = (String) fullData.pMap.get("e");
			tmp_lat = tmp_lat.substring(0,tmp_lat.length()-1);
			tmp_lng = tmp_lng.substring(0,tmp_lng.length()-1);
			lat = Double.parseDouble(tmp_lat);
			lng = Double.parseDouble(tmp_lng);
			speed = Double.parseDouble((String) fullData.pMap.get("f"));
			//CHECK ALERTS
			CHECK_ALERTS(vehicle_id, imei, startDateTime, endDateTime, interval, device_time, sts, lat, lng, speed, max_speed, data_size, record_count, rep_distance, rep_travel);
			record_count++;
		}
		
		fd.close();		
	}
	
	
	public static void CHECK_ALERTS(int vehicle_id, String imei, String startdate, String enddate, double interval, String device_time, String sts, double lat, double lng, double speed, Float max_speed, int data_size, int record_count, report_distance rep_distance, report_travel rep_travel) {
		//CHECK AND PUSH
//		report_distance.action_report_distance(vehicle_id, imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
//		report_travel.action_report_travel(vehicle_id, imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
		report_turning_violation.action_report_truning_violation(vehicle_id, imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
		
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
	
}
