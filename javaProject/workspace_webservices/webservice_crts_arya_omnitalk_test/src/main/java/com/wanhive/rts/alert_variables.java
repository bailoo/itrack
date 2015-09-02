package com.wanhive.rts;
import java.io.*;
import java.net.*;
import java.util.*;
import java.util.Date;
import java.sql.*;
import java.text.*;
import java.lang.*;

public class alert_variables
{		
	public static ArrayList<String> total_imei = new ArrayList<String>();
	public static HashMap<String, String>  assigned_imei = new HashMap(new Hashtable<String, String>());	
	public static HashMap<String, String>  sms_status = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  mail_status = new HashMap(new Hashtable<String, String>());	
		
	public static boolean first_flag = true;										/********* FIRST DATA FLAG ************/
	
	/********* ALERT FLAGS ************/	
	public static HashMap<String, Boolean>  alert_halt1_start_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_halt2_start_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_movement_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_nogps_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_battery_disconnected_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_exited_region_flag = new HashMap(new Hashtable<String, Boolean>());


	public static HashMap<String, String>  alert_halt1_start = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_halt2_start = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_movement = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_nogps = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_battery_disconnected = new HashMap(new Hashtable<String, String>());	  //below 9 for 2 mins	
	public static HashMap<String, String>  alert_exited_region = new HashMap(new Hashtable<String, String>());		  //for 2 mins

	
	public static HashMap<String, String>  temp_alert_battery_disconnected = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_halt1_start = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_halt2_start = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_movement = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_nogps = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_exited_region = new HashMap(new Hashtable<String, String>());
	
	//####### REPETITIVE VARIABLES
	public static HashMap<String, String>  repetitive_alert_battery_disconnected_time = new HashMap(new Hashtable<String, String>());	  
	public static HashMap<String, String>  repetitive_alert_halt1_start_time = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_halt2_start_time = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_movement_time = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_nogps_time = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_exited_region_time = new HashMap(new Hashtable<String, String>());
	
	public static HashMap<String, String>  repetitive_alert_battery_disconnected_location = new HashMap(new Hashtable<String, String>());	  
	public static HashMap<String, String>  repetitive_alert_halt1_start_location = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_halt2_start_location = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_movement_location = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_nogps_location = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_exited_region_location = new HashMap(new Hashtable<String, String>());		

	public static HashMap<String, String>  repetitive_alert_battery_disconnected_landmark = new HashMap(new Hashtable<String, String>());	  
	public static HashMap<String, String>  repetitive_alert_halt1_start_landmark = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_halt2_start_landmark = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_movement_landmark = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_nogps_landmark = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  repetitive_alert_exited_region_landmark = new HashMap(new Hashtable<String, String>());
		
	//XML VARIABLES		
	public static HashMap<String, String>  imei = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  datetime = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  sts = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Double>  lat = new HashMap(new Hashtable<String, Double>());
	public static HashMap<String, Double>  lng = new HashMap(new Hashtable<String, Double>());
	public static HashMap<String, Float>  speed = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io1 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io2 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io3 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io4 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io5 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io6 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io7 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  io8 = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, Float>  sup_v = new HashMap(new Hashtable<String, Float>());
	
	
	public static HashMap<String, String>  nearest_landmark = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  region_code = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  region_name = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  region_coord = new HashMap(new Hashtable<String, String>());
	
	//TRIP VARIABLES
	public static HashMap<String, String>  vehicle_name = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  DFG = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  S120 = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  S30 = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  ND = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  RD = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  NG60 = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  DD30 = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  RR = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  FS = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  trip_id = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  source_coord = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  dest_coord = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  trip_startdate = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  trip_status = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  transporter_name = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  transporter_mobile = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  driver_name = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  driver_mobile = new HashMap(new Hashtable<String, String>());
	
	public static boolean exited_region_reset_flag = false;
	public static boolean halt1_reset_flag = false;
	public static boolean halt2_reset_flag = false;
	public static boolean movement_reset_flag = false;
	public static boolean nogps_reset_flag = false;
	public static boolean battery_disconnect_reset_flag = false;
	public static boolean debug = false;
		
	public static String last_datetime ="";
	public static int account_id = 1547;
	
	
	public static void reset_alert_variables(String key_imei) {

		assigned_imei.remove(key_imei);
		sms_status.remove(key_imei);
		mail_status.remove(key_imei);
		alert_halt1_start_flag.remove(key_imei);
		alert_halt2_start_flag.remove(key_imei);
		alert_movement_flag.remove(key_imei);
		alert_nogps_flag.remove(key_imei);
		alert_battery_disconnected_flag.remove(key_imei);
		alert_exited_region_flag.remove(key_imei);
		alert_halt1_start.remove(key_imei);
		alert_halt2_start.remove(key_imei);
		alert_movement.remove(key_imei);
		alert_nogps.remove(key_imei);
		alert_battery_disconnected.remove(key_imei);
		alert_exited_region.remove(key_imei);
		temp_alert_battery_disconnected.remove(key_imei);
		temp_alert_halt1_start.remove(key_imei);
		temp_alert_halt2_start.remove(key_imei);
		temp_alert_movement.remove(key_imei);
		temp_alert_nogps.remove(key_imei);
		temp_alert_exited_region.remove(key_imei);
		repetitive_alert_battery_disconnected_time.remove(key_imei);
		repetitive_alert_halt1_start_time.remove(key_imei);
		repetitive_alert_halt2_start_time.remove(key_imei);
		repetitive_alert_movement_time.remove(key_imei);
		repetitive_alert_nogps_time.remove(key_imei);
		repetitive_alert_exited_region_time.remove(key_imei);
		repetitive_alert_battery_disconnected_location.remove(key_imei);
		repetitive_alert_halt1_start_location.remove(key_imei);
		repetitive_alert_halt2_start_location.remove(key_imei);
		repetitive_alert_movement_location.remove(key_imei);
		repetitive_alert_nogps_location.remove(key_imei);
		repetitive_alert_exited_region_location.remove(key_imei);
		repetitive_alert_battery_disconnected_landmark.remove(key_imei);
		repetitive_alert_halt1_start_landmark.remove(key_imei);
		repetitive_alert_halt2_start_landmark.remove(key_imei);
		repetitive_alert_movement_landmark.remove(key_imei);
		repetitive_alert_nogps_landmark.remove(key_imei);
		repetitive_alert_exited_region_landmark.remove(key_imei);
		imei.remove(key_imei);
		datetime.remove(key_imei);
		sts.remove(key_imei);
		lat.remove(key_imei);
		lng.remove(key_imei);
		speed.remove(key_imei);
		io1.remove(key_imei);
		io2.remove(key_imei);
		io3.remove(key_imei);
		io4.remove(key_imei);
		io5.remove(key_imei);
		io6.remove(key_imei);
		io7.remove(key_imei);
		io8.remove(key_imei);
		sup_v.remove(key_imei);
		nearest_landmark.remove(key_imei);
		region_code.remove(key_imei);
		region_name.remove(key_imei);
		region_coord.remove(key_imei);
		vehicle_name.remove(key_imei);
		DFG.remove(key_imei);
		S120.remove(key_imei);
		S30.remove(key_imei);
		ND.remove(key_imei);
		RD.remove(key_imei);
		NG60.remove(key_imei);
		//DD30.remove(key_imei);
		//RR.remove(key_imei);
		//FS.remove(key_imei);
		trip_id.remove(key_imei);
		source_coord.remove(key_imei);
		dest_coord.remove(key_imei);
		trip_startdate.remove(key_imei);
		trip_status.remove(key_imei);
		transporter_name.remove(key_imei);
		transporter_mobile.remove(key_imei);
		driver_name.remove(key_imei);
		driver_mobile.remove(key_imei);
	}
}