
import java.io.*;
import java.net.*;
import java.util.*;
import java.util.Date;
import java.sql.*;
import java.text.*;
import java.lang.*;

public class alert_variables
{		
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
	
	
	//public static HashMap<String, String>  nearest_landmark = new HashMap(new Hashtable<String, String>());
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
	
	public static String root_dir = "/home/VTS/iespl_sms_sender/temp_files_itc";	
	public static String temp_path = "/home/VTS/iespl_sms_sender/temp_files_itc/temp_variables_itc";
	public static String landmark_path = "/home/VTS/iespl_sms_sender/temp_files_itc/temp_variables_itc/landmarks.xml";
	
	/*public static String root_dir = "D:\\ICICI_PROJECT/ITC";	
	public static String temp_path = "D:\\ICICI_PROJECT/ITC/temp_variables_itc";
	public static String landmark_path = "D:\\ICICI_PROJECT/ITC/temp_variables_itc/landmarks.xml";*/

	public alert_variables()
	{
		
	}
}