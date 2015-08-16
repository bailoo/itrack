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
	//public static Hashtable<String, String> source = new Hashtable<String,String>();
	//public static HashMap<String, String>  assigned_imei = new HashMap(source);
		
	public static HashMap<String, String>  assigned_imei = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  sms_status = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  mail_status = new HashMap(new Hashtable<String, String>());	
	
	/*public static HashMap<String, String>  battery_connected = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  battery_disconnected = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  entered_region = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  exited_region = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  halt_start = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  halt_stop = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  ignition_activated = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  ignition_deactivated = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  landmark = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  over_temperature = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  overspeed = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  sos = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  vehicle_position = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  door_open = new HashMap(new Hashtable<String, String>());*/
	
	public static boolean first_flag = true;										/********* FIRST DATA FLAG ************/
	
	//public static Connection connection=null;	
	//public Hashtable<String,Boolean> tmp = new Hashtable<String,Boolean>();
	
	/********* ALERT FLAGS ************/
	/*public static boolean alert_vehicle_position_flag = false;						
	public static boolean alert_landmark_flag = false;									
	public static boolean alert_halt_start_flag = false;
	public static boolean alert_halt_stop_flag = false;
	public static boolean alert_ignition_activated_flag = false;
	public static boolean alert_ignition_deactivated_flag = false;
	public static boolean alert_sos_flag = false;
	public static boolean alert_overspeed_flag = false;	
	public static boolean alert_battery_connected_flag = false;
	public static boolean alert_battery_disconnected_flag = false;
	public static boolean alert_over_temperature_flag = false;
	public static boolean alert_entered_region_flag = false;
	public static boolean alert_exited_region_flag = false;*/
	
	//public static HashMap<String, Boolean>  alert_vehicle_position_flag = new HashMap(new Hashtable<String, Boolean>());
	//public static HashMap<String, Boolean>  alert_landmark_flag = new HashMap(new Hashtable<String, Boolean>());
	//public static HashMap<String, Boolean>  alert_halt_start_flag = new HashMap(new Hashtable<String, Boolean>());
	//public static HashMap<String, Boolean>  alert_halt_stop_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_ignition_activated_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_ignition_deactivated_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_sos_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_overspeed_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_battery_connected_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_battery_disconnected_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_over_temperature_flag = new HashMap(new Hashtable<String, Boolean>());
	//public static HashMap<String, Boolean>  alert_entered_region_flag = new HashMap(new Hashtable<String, Boolean>());
	//public static HashMap<String, Boolean>  alert_exited_region_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_door1_open_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_door1_closed_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_door2_open_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_door2_closed_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_door3_open_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_door3_closed_flag = new HashMap(new Hashtable<String, Boolean>());	
	public static HashMap<String, Boolean>  alert_ac_on_flag = new HashMap(new Hashtable<String, Boolean>());
	public static HashMap<String, Boolean>  alert_ac_off_flag = new HashMap(new Hashtable<String, Boolean>());
	
/*
	//public static ArrayList<String>  alert_vehicle_position = new ArrayList<String>();
	//public static ArrayList<String>  alert_landmark = new ArrayList<String>();
	public static ArrayList<String>  alert_halt_start = new ArrayList<String>();
	public static ArrayList<String>  alert_halt_stop = new ArrayList<String>();
	public static ArrayList<String>  alert_ignition_activated = new ArrayList<String>();	
	public static ArrayList<String>  alert_ignition_deactivated = new ArrayList<String>();	  //for 2 mins
	public static ArrayList<String>  alert_sos = new ArrayList<String>();
	public static ArrayList<String>  alert_overspeed = new ArrayList<String>();			  //FROM DATABASE SPEED
	public static ArrayList<String>  alert_battery_connected = new ArrayList<String>();
	public static ArrayList<String>  alert_battery_disconnected = new ArrayList<String>();	  //below 9 for 2 mins
	public static ArrayList<String>  alert_over_temperature = new ArrayList<String>();
	//public static ArrayList<String>  alert_entered_region = new ArrayList<String>();		  //for 2 mins
	//public static ArrayList<String>  alert_exited_region = new ArrayList<String>();		  //for 2 mins
	public static ArrayList<String>  alert_door_open = new ArrayList<String>();	
	public static ArrayList<String>  alert_door_closed = new ArrayList<String>();	  //for 2 mins
	public static ArrayList<String>  alert_ac_on = new ArrayList<String>();	
	public static ArrayList<String>  alert_ac_off = new ArrayList<String>();	  //for 2 mins
*/
	
	//public static HashMap<String, String>  alert_vehicle_position = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  alert_landmark = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  alert_halt_start = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  alert_halt_stop = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_ignition_activated = new HashMap(new Hashtable<String, String>());	
	public static HashMap<String, String>  alert_ignition_deactivated = new HashMap(new Hashtable<String, String>());	  //for 2 mins
	public static HashMap<String, String>  alert_sos = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_overspeed = new HashMap(new Hashtable<String, String>());			  //FROM DATABASE SPEED
	public static HashMap<String, String>  alert_battery_connected = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_battery_disconnected = new HashMap(new Hashtable<String, String>());	  //below 9 for 2 mins
	public static HashMap<String, String>  alert_over_temperature = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  alert_entered_region = new HashMap(new Hashtable<String, String>());		  //for 2 mins
	//public static HashMap<String, String>  alert_exited_region = new HashMap(new Hashtable<String, String>());		  //for 2 mins
	public static HashMap<String, String>  alert_door1_open = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_door1_closed = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_door2_open = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_door2_closed = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_door3_open = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_door3_closed = new HashMap(new Hashtable<String, String>());	
	public static HashMap<String, String>  alert_ac_on = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  alert_ac_off = new HashMap(new Hashtable<String, String>());

	
	public static HashMap<String, String>  temp_alert_ignition_activated = new HashMap(new Hashtable<String, String>());	
	public static HashMap<String, String>  temp_alert_ignition_deactivated = new HashMap(new Hashtable<String, String>());	  //for 2 mins
	public static HashMap<String, String>  temp_alert_sos = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_overspeed = new HashMap(new Hashtable<String, String>());			  //FROM DATABASE SPEED
	public static HashMap<String, String>  temp_alert_battery_connected = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_battery_disconnected = new HashMap(new Hashtable<String, String>());	  //below 9 for 2 mins
	public static HashMap<String, String>  temp_alert_over_temperature = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_door1_open = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_door1_closed = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_door2_open = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_door2_closed = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_door3_open = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_door3_closed = new HashMap(new Hashtable<String, String>());	
	public static HashMap<String, String>  temp_alert_ac_on = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  temp_alert_ac_off = new HashMap(new Hashtable<String, String>());
	
		
	//XML VARIABLES		
	public static HashMap<String, String>  vehicle_id = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, String>  vehicle_name = new HashMap(new Hashtable<String, String>());
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
	public static HashMap<String, Integer>  halt_start_time = new HashMap(new Hashtable<String, Integer>());
	public static HashMap<String, Integer>  halt_stop_time = new HashMap(new Hashtable<String, Integer>());
	public static HashMap<String, Float>  max_speed = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, String>  engine_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  engine_io_value = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, String>  sos_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  sos_io_value = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, String>  temperature_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  temperature_io_value = new HashMap(new Hashtable<String, Float>());

	public static HashMap<String, String>  door1_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  door1_io_value = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, String>  door2_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  door2_io_value = new HashMap(new Hashtable<String, Float>());
	public static HashMap<String, String>  door3_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  door3_io_value = new HashMap(new Hashtable<String, Float>());	
	public static HashMap<String, String>  ac_io_no = new HashMap(new Hashtable<String, String>());
	public static HashMap<String, Float>  ac_io_value = new HashMap(new Hashtable<String, Float>());
	
	
	//public static HashMap<String, String>  nearest_landmark = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  inside_region_name = new HashMap(new Hashtable<String, String>());
	//public static HashMap<String, String>  inside_region_coord = new HashMap(new Hashtable<String, String>());
	
	/*public static String vehicle_id="";
	public static String vehicle_name="";
	public static String imei="";
	public static String datetime="";
	public static String sts="";
	public static double lat=0.0;
	public static double lng=0.0;
	public static float speed = 0.0f;
	public static float io1=0.0f, io2=0.0f, io3=0.0f, io4=0.0f, io5=0.0f, io6=0.0f, io7=0.0f, io8=0.0f;
	//public float engine_io_value= 0.0f;
	public static float sup_v =0.0f;
	
	//DB VARIABLES
	public static int halt_start_time =0;
	public static int halt_stop_time =0;
	public static float max_speed = 0.0f;	
	public static String engine_io_no ="";
	public static String sos_io_no ="";
	public static String over_temperature_io_no ="";
	public static float engine_io_value = 0.0f;
	public static float sos_io_value = 0.0f;
	public static float over_temperature_io_value = 0.0f;
	public static String nearest_landmark ="";
	public static String inside_region_name ="";
	public static String inside_region_coord ="";*/	
	public static String account_id = "";
	
	//public String root_dir = "D:\\SERVER_111.118.181.156/iespl_sms_sender/2012-10-05/iespl_sms_sender";
	//public String root_dir = "/var/www/html/itrack_vts";
	
	
	public static String root_dir = "/home/VTS/iespl_sms_sender/temp_files";
	//public static String root_dir = "D:\\BACKGROUND_SERVICES/iespl_sms_sender/temp_files";

	
	public alert_variables()
	{
	
	}
}