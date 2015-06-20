package com.hdfc.logic;

import in.co.itracksolution.pull_full_data_cassandra;
import in.co.itracksolution.dao.FullDataDao;
import in.co.itracksolution.model.FullData;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.hdfc.db.mysql.mysql_handler;
import com.hdfc.init.init;
import com.hdfc.report.report_chauraha;
import com.hdfc.report.report_distance;
import com.hdfc.report.report_travel;
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
		mysql_handler mh = new mysql_handler();
		mh.getVehicleInformation(account_id);
		
		String previous_day = utility_class.getYesterdayDateString();
		previous_date1 = previous_day+" 00:00:00";
		previous_date2 = previous_day+" 23:59:59";
		
		init init_var = new init();
		
		for(int i=0;i<init_var.vehicle_id.size();i++) {			
			report_distance.firstdata_flag_distance = 0;
			report_distance.firstdata_flag_speed = 0;
			report_travel.firstdata_flag_travel = 0;
			report_travel.firstdata_flag_halt = 0;
			report_chauraha.firstdata_flag_chauraha = 0;
			
			pull_and_process_data(init_var.vehicle_id.get(i), init_var.vehicle_name.get(i), init_var.max_speed.get(i), init_var.device_imei_no.get(i), previous_date1, previous_date2);
			
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
		String device_time ="", sts ="", lat_str ="", lng_str ="";
		double lat=0.0, lng=0.0;

		Float speed = 0.0F, ax = 0.0f, ay = 0.0f, az =0.0f,	bx=0.0f, by=0.0f, bz=0.0f, cx=0.0f, cy=0.0f, cz=0.0f;
		report_distance rep_distance = new report_distance();
		report_travel rep_travel = new report_travel();

		//data.setImei("862170011627815"); //Make sure this imei exists
		//data.setDate("2015-01-29");
		//ResultSet rs= dao.selectByImeiAndDate(data.getImei(), data.getDate());	
		/*String imei = "359231030125239";
		String startDateTime = "2015-01-01 10:00:00";
		String endDateTime = "2015-01-01 15:00:00";*/
		ResultSet rs = dao.selectByImeiAndDateTimeSlice(imei, startDateTime, endDateTime);
		List<Row> rowlist = rs.all();
		int data_size = rowlist.size();
		int record_count =1;
		for (Row row : rowlist) {
			System.out.print("imei: "+row.getString("imei")+" ");
			System.out.print("device time: "+sdf.format(row.getDate("dtime"))+" ");
			System.out.print("server time: "+sdf.format(row.getDate("stime"))+" ");
			System.out.print("data: "+row.getString("data")+" ");
			System.out.println();
			if(!(utility_class.isDouble(lat_str)) || !(utility_class.isDouble(lng_str))) {
				continue;
			}			
						
			//CHECK ALERTS
			CHECK_ALERTS(vehicle_id, imei, startDateTime, endDateTime, interval, device_time, sts, lat, lng, speed, max_speed, data_size, record_count, rep_distance, rep_travel);
			record_count++;
		}
		
		fd.close();		
	}
	
	
	public static void CHECK_ALERTS(int vehicle_id, String imei, String startdate, String enddate, double interval, String device_time, String sts, double lat, double lng, double speed, Float max_speed, int data_size, int record_count, report_distance rep_distance, report_travel rep_travel) {
		//CHECK AND PUSH
		report_distance.action_report_distance(vehicle_id, imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
		report_travel.action_report_travel(vehicle_id, imei, device_time, startdate, enddate, interval, lat, lng, speed, data_size, record_count);
		
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
