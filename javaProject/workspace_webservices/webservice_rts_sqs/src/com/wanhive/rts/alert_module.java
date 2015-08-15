package com.wanhive.rts;
import java.sql.*;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.InputStreamReader;
import java.io.RandomAccessFile;
import java.net.URL;
import java.text.DecimalFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.StringTokenizer;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
//import sun.misc.BASE64Decoder;

public class alert_module {
	
   static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";  
   static final String DB_URL = "jdbc:mysql://localhost/alert_session";

   //  Database credentials
   static final String USER = "root";
   static final String PASS = "mysql";
   public static Connection conn = null;
   public static Statement stmt = null;
	   
	public static void get_escalation_detail()
	{
		//System.out.println("Fetching escalation detail for the first time..");
		String current_path = "",strLine1="";
		String imei_db="",imei_local="",imei_db_file="";
		boolean imei_db_matched = false;
				
		//######### CREATING DATABASE CONNECTION FIRSTTIME   
		conn = null;
		try{
		      //STEP 2: Register JDBC driver
		  try {
			Class.forName("com.mysql.jdbc.Driver");
		  } catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		  }
		  //STEP 3: Open a connection
		  	//System.out.println("Connecting to database 147");
		      conn = DriverManager.getConnection(DB_URL,USER,PASS);
		 }catch(SQLException se){}
		 //######### DATABASE CONNECTION
		
		try{
			//String temp_path = "D:\\SERVER_111.118.181.156/iespl_sms_sender/2012-10-04/iespl_sms_sender/xml_vts/xml_alert/iespl_sms_sender/temp_variables";
			//String temp_path = "D:\\BACKGROUND_SERVICES/iespl_sms_sender/temp_files/temp_variables";
			String temp_path = "/home/VTS/iespl_sms_sender/temp_files/temp_variables";
			
			String xml_file_temp;		
			File folder_temp = new File(temp_path);
			File[] listOfFiles_temp = folder_temp.listFiles(); 

			//System.out.println("listfilelen="+listOfFiles_temp.length);
			for (int t = 0; t < listOfFiles_temp.length; t++) 
			{
				if (listOfFiles_temp[t].isFile()) 
				{
					xml_file_temp = listOfFiles_temp[t].getName();		/*****GET WITH EXTENSION .XML *****/
					//System.out.println("xml_file_temp="+xml_file_temp);
					String[] temp;
					String delimiter = "_";
					String[] temp2;
					String delimiter2 = "\\.";
					
					temp = xml_file_temp.split(delimiter);
					if(temp[0].equals("escalation"))
					{																
						temp2 = temp[1].split(delimiter2);
						
						imei_db = temp2[0];
						//System.out.println("imei_db="+imei_db);		
						
						alert_variables.assigned_imei.put(imei_db, imei_db);
						
						//alert_variables.alert_vehicle_position_flag.put(imei_db,false);
						//alert_variables.alert_landmark_flag.put(imei_db,false);
						//alert_variables.alert_halt_start_flag.put(imei_db,false);
						//alert_variables.alert_halt_stop_flag.put(imei_db,false);
						alert_variables.alert_ignition_activated_flag.put(imei_db,false);
						alert_variables.alert_ignition_deactivated_flag.put(imei_db,false);
						alert_variables.alert_sos_flag.put(imei_db,false);
						alert_variables.alert_overspeed_flag.put(imei_db,false);
						alert_variables.alert_battery_connected_flag.put(imei_db,false);
						alert_variables.alert_battery_disconnected_flag.put(imei_db,false);
						alert_variables.alert_over_temperature_flag.put(imei_db,false);
						alert_variables.alert_door1_open_flag.put(imei_db,false);
						alert_variables.alert_door1_closed_flag.put(imei_db,false);
						alert_variables.alert_door2_open_flag.put(imei_db,false);
						alert_variables.alert_door2_closed_flag.put(imei_db,false);
						alert_variables.alert_ac_on_flag.put(imei_db,false);
						alert_variables.alert_ac_off_flag.put(imei_db,false);
						//alert_variables.alert_entered_region_flag.put(imei_db,false);
						//alert_variables.alert_exited_region_flag.put(imei_db,false);
						
						alert_variables.vehicle_id.put(imei_db,"");
						alert_variables.vehicle_name.put(imei_db,"");
						alert_variables.imei.put(imei_db,"");
						alert_variables.datetime.put(imei_db,"");
						alert_variables.sts.put(imei_db,"");
						alert_variables.lat.put(imei_db,0.0);
						alert_variables.lng.put(imei_db,0.0);
						alert_variables.speed.put(imei_db,0.0f);
						alert_variables.io1.put(imei_db,0.0f);
						alert_variables.io2.put(imei_db,0.0f);
						alert_variables.io3.put(imei_db,0.0f);
						alert_variables.io4.put(imei_db,0.0f);
						alert_variables.io5.put(imei_db,0.0f);
						alert_variables.io6.put(imei_db,0.0f);
						alert_variables.io7.put(imei_db,0.0f);
						alert_variables.io8.put(imei_db,0.0f);
						alert_variables.sup_v.put(imei_db,0.0f);
						alert_variables.halt_start_time.put(imei_db,0);
						alert_variables.halt_stop_time.put(imei_db,0);
						alert_variables.max_speed.put(imei_db,0.0f);
						alert_variables.engine_io_no.put(imei_db,"");
						alert_variables.sos_io_no.put(imei_db,"");
						alert_variables.temperature_io_no.put(imei_db,"");
						alert_variables.engine_io_value.put(imei_db,0.0f);
						alert_variables.sos_io_value.put(imei_db,0.0f);
						alert_variables.temperature_io_value.put(imei_db,0.0f);
						//alert_variables.nearest_landmark.put(imei_db,"");
						//alert_variables.inside_region_name.put(imei_db,"");
						//alert_variables.inside_region_coord.put(imei_db,"");						
												
						
						get_device_info(imei_db);		/********** GET ALERT DEVICE INFORMATION *************/							
					}
				}
			}			
		}catch (Exception e) { 
			//System.out.println("EXCEPTION IN GETTING ASSIGNED TEMP FILE IMEI:"+e.getMessage()); 
			}				
	}
		
		
	public static void write_final_alert_data(String imei, String DateTime, String ServerTS, String lat, String lng, String Speed, String io_value1, String io_value2, String io_value3, String io_value4, String io_value5, String io_value6, String io_value7, String io_value8, String SupplyVoltage)
	{					
		try {									
				if(alert_variables.assigned_imei.get(imei)!=null)
				{					
					//System.out.println("AssignedIME-OK");
					//alert_variables.imei.put(imei,imei) = vserial;
					alert_variables.datetime.put(imei, DateTime);
					alert_variables.sts.put(imei, ServerTS);
					//av.lat = Float.parseFloat(lat);
					//av.lng = Float.parseFloat(lng);
					//System.out.println("Debug1");
					lat = lat.substring(0,lat.length()-1);
					lng = lng.substring(0,lng.length()-1);
					//System.out.println("Lat="+lat+" ,Lng="+lng);
					
					alert_variables.lat.put(imei, Double.parseDouble(lat));
					alert_variables.lng.put(imei, Double.parseDouble(lng));
					
					alert_variables.speed.put(imei, Float.parseFloat(Speed));
					alert_variables.io1.put(imei, Float.parseFloat(io_value1));
					alert_variables.io2.put(imei, Float.parseFloat(io_value2));
					alert_variables.io3.put(imei, Float.parseFloat(io_value3));
					alert_variables.io4.put(imei, Float.parseFloat(io_value4));
					alert_variables.io5.put(imei, Float.parseFloat(io_value5));
					alert_variables.io6.put(imei, Float.parseFloat(io_value6));
					alert_variables.io7.put(imei, Float.parseFloat(io_value7));
					alert_variables.io8.put(imei, Float.parseFloat(io_value8));							
					alert_variables.sup_v.put(imei, Float.parseFloat(SupplyVoltage));
					//System.out.println("Debug2");
					//#### CALL FINAL PROCESS ALERTS #########//		
					process_alerts(imei);
					//#########################################					
					//System.out.println("BEFOE GET DEVICE INFO: av.imei="+imei+" ,av.datetime="+alert_variables.datetime.get(imei)+" ,av.sts="+alert_variables.sts.get(imei)+" ,av.lat="+alert_variables.lat.get(imei)+" ,av.lng="+alert_variables.lat.get(imei)+" ,av.speed="+alert_variables.speed.get(imei)+" ,av.io1="+alert_variables.io1.get(imei)+" ,av.io2="+alert_variables.io2.get(imei)+" ,av.io3.get(imei)="+alert_variables.io3.get(imei)+" ,av.io4="+alert_variables.io4.get(imei)+" ,av.io5="+alert_variables.io5.get(imei)+" ,av.io6="+alert_variables.io6.get(imei)+" ,av.io7="+alert_variables.io7.get(imei)+" ,av.io8="+alert_variables.io8.get(imei)+" ,av.sup_v="+alert_variables.sup_v.get(imei));
					//System.out.println("AFTER GET DEVICE INFO");
				}
			}
			catch(Exception e) {
				//System.out.println("EXCEPTION IN READING -THE LINE OF LAST LOCATION XML FILE:"+e.getMessage());
				}
		//########### LAST LOCATION FILE READING CLOSED ##########################//			
	}	
	
	
	/**** GET DEVICE INFO - METHOD BODY **********/
	public static void get_device_info(String imei)
	{
		//System.out.println("GETTING DEVICE INFO..");
		String Command;
		String alertName="";
		String normal_variable_file="", normal_variable_path ="", escalation_file = "", escalation_path = "";
		String landmark_file ="", landmark_path = "", region_file ="", region_path = "";
		int i=0;
		String[] temp;
											
		//CHECK IF TEMPORARY VARIABLES FILE EXISTS
		normal_variable_file = "variable_"+imei; 	//#### SINGLE ENTRY
		normal_variable_path = alert_variables.root_dir+"/temp_variables/"+normal_variable_file+".xml";
		
		escalation_file = "escalation_"+imei; 		//#### MULTIPLE ENTREIS
		escalation_path = alert_variables.root_dir+"/temp_variables/"+escalation_file+".xml";
		
		landmark_file = "landmark_"+imei; 			//#### MULTIPLE ENTREIS
		landmark_path = alert_variables.root_dir+"/temp_variables/"+landmark_file+".xml";

		region_file = "region_"+imei;				//#### MULTIPLE ENTREIS
		region_path = alert_variables.root_dir+"/temp_variables/"+region_file+".xml";
															
		//System.out.println("EscalationPath="+escalation_path);
		get_alert_configuration(normal_variable_path, escalation_path, landmark_path, region_path, imei);  	/****** CALL ALERT CONFIGURATION METHOD ******/
		
	} // METHOD CLOSED
	
	/********* GET ALERT CONFIGURATION - METHOD BODY *************/
	public static void get_alert_configuration(String normal_variable_path, String escalation_path, String landmark_path, String region_path, String imei)		/*** ALERT CONFIGURATION METHOD BODY *******/
	{
		//System.out.println("GETTING_ALERT_CONFIGURATION..");				
				
		String vehicle_id_tmp="", vehicle_name_tmp="", halt_start_time ="", halt_stop_time="", max_speed="", engine_io_no="", sos_io_no="", door1_io_no="", door2_io_no="", ac_io_no=""; 				
		String nearest_landmark = "";						
				
		//##### READ ESCALATION VARIABLE FILE -SET IT TO ALERT VARIABLES #########/	
		//##### ---------------------------------------------------------#########/
		escalation_read_set_variables(escalation_path, imei);
		//##### ---------------------------------------------------------#########/
		
		//###### READ TEMPORARY VARIABLES FILE -SET IT TO ALERT VARIABLES #########/	
		try{
			String strLine1;
			//System.out.println("Variable_path="+normal_variable_path);
			strLine1 = read_file_string(normal_variable_path);		 /******* GET SINGLE LINE STRING **********/			
			
			//System.out.println("E1");
			alert_variables.vehicle_id.put(imei, getXmlAttribute(strLine1,"vehicle_id=\"[^\"]+"));
			alert_variables.vehicle_name.put(imei, getXmlAttribute(strLine1,"vehicle_name=\"[^\"]+"));		
			halt_start_time = getXmlAttribute(strLine1,"halt_start_time=\"[^\"]+");
			halt_stop_time = getXmlAttribute(strLine1,"halt_stop_time=\"[^\"]+");
			max_speed = getXmlAttribute(strLine1,"max_speed=\"[^\"]+");
			engine_io_no = getXmlAttribute(strLine1,"engine_io_no=\"[^\"]+");
			sos_io_no = getXmlAttribute(strLine1,"sos_io_no=\"[^\"]+");
			door1_io_no = getXmlAttribute(strLine1,"door1_io_no=\"[^\"]+");
			door2_io_no = getXmlAttribute(strLine1,"door2_io_no=\"[^\"]+");
			ac_io_no = getXmlAttribute(strLine1,"ac_io_no=\"[^\"]+");
			
			//System.out.println("door1_io_no:FILE="+door1_io_no);
			alert_variables.halt_start_time.put(imei, Integer.parseInt(halt_start_time));
			alert_variables.halt_stop_time.put(imei, Integer.parseInt(halt_stop_time));
			alert_variables.max_speed.put(imei, Float.parseFloat(max_speed));
			alert_variables.engine_io_no.put(imei, engine_io_no);
			alert_variables.sos_io_no.put(imei, sos_io_no);	
			alert_variables.door1_io_no.put(imei, door1_io_no);
			alert_variables.door2_io_no.put(imei, door2_io_no);	
			alert_variables.ac_io_no.put(imei, ac_io_no);	
		}catch(Exception e) {
			//System.out.println("ERROR-ALERT_CONF FINal"+e.getMessage());
			}		
		//###### GET NEAREST LANDMARK - GET CURRENT STATUS EVERY TIME ###########/
		/*nearest_landmark = get_nearest_landmark(landmark_path, "common", imei);			//THIS IS COMPULSORY FOR EVERY IMEI
		alert_variables.nearest_landmark.put(imei, nearest_landmark);
		
		//System.out.println("AVLNMRK1="+av.nearest_landmark);
		if(alert_variables.nearest_landmark.get(imei).equalsIgnoreCase(""))
		{
			alert_variables.nearest_landmark.put(imei, get_url_location(imei));
		}*/
		//System.out.println("E3");
		//####################################//
				
		/*
		if( alert_variables.alert_ignition_activated_flag.get(imei) || alert_variables.alert_ignition_deactivated_flag.get(imei))
		{
			System.out.println(">>IGNITION ACTIVATED");
			//######### GET ENGINE IO VALUE #######//
			alert_variables.engine_io_value.put(imei, get_io_value(imei, "engine"));
			System.out.println("ENGINE IO NO="+(alert_variables.engine_io_no.get(imei)+" ,ENGINE IO VALUE="+alert_variables.engine_io_value.get(imei)+" ,MAXSPEED="+alert_variables.max_speed.get(imei)));				
			//####################################//							
		}
		System.out.println("E3");
		
		if(alert_variables.alert_sos_flag.get(imei))
		{
			System.out.println(">>SOS ACTIVATED");
			//######### GET ENGINE IO VALUE #######//
			alert_variables.sos_io_value.put(imei, get_io_value(imei, "sos"));
			System.out.println("SOS IO NO="+alert_variables.sos_io_no.get(imei)+" ,SOS IO VALUE="+alert_variables.sos_io_value.get(imei));				
			//####################################//							
		}
		System.out.println("E4");

		if(alert_variables.alert_over_temperature_flag.get(imei))
		{
			System.out.println(">>OVER TEMPERATURE ACTIVATED");
			//######### GET ENGINE IO VALUE #######//
			alert_variables.temperature_io_value.put(imei, get_io_value(imei, "temperature"));
			System.out.println("SOS IO NO="+alert_variables.temperature_io_no.get(imei)+" ,OVER TEMPERATURE IO VALUE="+alert_variables.temperature_io_value.get(imei));				
			//####################################//							
		}
		
		System.out.println("E5");
		if(alert_variables.alert_door1_open_flag.get(imei) || alert_variables.alert_door1_closed_flag.get(imei))
		{
			System.out.println(">>DOOR1 OPEN/CLOSED");
			//######### GET ENGINE IO VALUE #######//
			alert_variables.door1_io_value.put(imei, get_io_value(imei, "door1"));
			System.out.println("DOOR1 OPEN NO="+alert_variables.door1_io_no.get(imei)+" ,DOOR OEPN IO VALUE="+alert_variables.door1_io_value.get(imei));				
			//####################################//							
		}
		if(alert_variables.alert_door2_open_flag.get(imei) || alert_variables.alert_door2_closed_flag.get(imei))
		{
			System.out.println(">>DOOR2 OPEN/CLOSED");
			//######### GET ENGINE IO VALUE #######//
			alert_variables.door2_io_value.put(imei, get_io_value(imei, "door2"));
			System.out.println("DOOR2 OPEN NO="+alert_variables.door2_io_no.get(imei)+" ,DOOR OEPN IO VALUE="+alert_variables.door2_io_value.get(imei));				
			//####################################//							
		}		
		System.out.println("E6");
		if(alert_variables.alert_ac_on_flag.get(imei) || alert_variables.alert_ac_off_flag.get(imei))
		{
			System.out.println(">>AC ON/OFF");
			//######### GET ENGINE IO VALUE #######//
			alert_variables.ac_io_value.put(imei, get_io_value(imei, "ac"));
			System.out.println("AC NO="+alert_variables.ac_io_no.get(imei)+" ,AC IO VALUE="+alert_variables.ac_io_value.get(imei));				
			//####################################//							
		}
		System.out.println("E7");
		}catch(Exception e) {System.out.println("ERROR-ALERT_CONF FINal"+e.getMessage());}
		*/
	}
				
	/*********** PROCESS ALERT - METHOD BODY **************/
	public static void process_alerts(String imei)
	{		
		//System.out.println("In Process Alert");
		try{
			//######### SET IO VALUES ###################
			if( alert_variables.alert_ignition_activated_flag.get(imei) || alert_variables.alert_ignition_deactivated_flag.get(imei))
			{
				//System.out.println(">>IGNITION ACTIVATED");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.engine_io_value.put(imei, get_io_value(imei, "engine"));
				//System.out.println("ENGINE IO NO="+(alert_variables.engine_io_no.get(imei)+" ,ENGINE IO VALUE="+alert_variables.engine_io_value.get(imei)+" ,MAXSPEED="+alert_variables.max_speed.get(imei)));				
				//####################################//							
			}
			//System.out.println("E3");
			
			if(alert_variables.alert_sos_flag.get(imei))
			{
				//System.out.println(">>SOS ACTIVATED");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.sos_io_value.put(imei, get_io_value(imei, "sos"));
				//System.out.println("SOS IO NO="+alert_variables.sos_io_no.get(imei)+" ,SOS IO VALUE="+alert_variables.sos_io_value.get(imei));				
				//####################################//							
			}
			//System.out.println("E4");
	
			if(alert_variables.alert_over_temperature_flag.get(imei))
			{
				//System.out.println(">>OVER TEMPERATURE ACTIVATED");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.temperature_io_value.put(imei, get_io_value(imei, "temperature"));
				//System.out.println("SOS IO NO="+alert_variables.temperature_io_no.get(imei)+" ,OVER TEMPERATURE IO VALUE="+alert_variables.temperature_io_value.get(imei));				
				//####################################//							
			}
			
			//System.out.println("E5");
			if(alert_variables.alert_door1_open_flag.get(imei) || alert_variables.alert_door1_closed_flag.get(imei))
			{
				//System.out.println(">>DOOR1 OPEN/CLOSED");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.door1_io_value.put(imei, get_io_value(imei, "door1"));
				//System.out.println("DOOR1 OPEN NO="+alert_variables.door1_io_no.get(imei)+" ,DOOR OEPN IO VALUE="+alert_variables.door1_io_value.get(imei));				
				//####################################//							
			}
			if(alert_variables.alert_door2_open_flag.get(imei) || alert_variables.alert_door2_closed_flag.get(imei))
			{
				//System.out.println(">>DOOR2 OPEN/CLOSED");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.door2_io_value.put(imei, get_io_value(imei, "door2"));
				//System.out.println("DOOR2 OPEN NO="+alert_variables.door2_io_no.get(imei)+" ,DOOR OEPN IO VALUE="+alert_variables.door2_io_value.get(imei));				
				//####################################//							
			}
			if(alert_variables.alert_door3_open_flag.get(imei) || alert_variables.alert_door3_closed_flag.get(imei))
			{
				//System.out.println(">>DOOR2 OPEN/CLOSED");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.door3_io_value.put(imei, get_io_value(imei, "door3"));
				//System.out.println("DOOR2 OPEN NO="+alert_variables.door2_io_no.get(imei)+" ,DOOR OEPN IO VALUE="+alert_variables.door2_io_value.get(imei));				
				//####################################//							
			}			
			//System.out.println("E6");
			if(alert_variables.alert_ac_on_flag.get(imei) || alert_variables.alert_ac_off_flag.get(imei))
			{
				//System.out.println(">>AC ON/OFF");
				//######### GET ENGINE IO VALUE #######//
				alert_variables.ac_io_value.put(imei, get_io_value(imei, "ac"));
				//System.out.println("AC NO="+alert_variables.ac_io_no.get(imei)+" ,AC IO VALUE="+alert_variables.ac_io_value.get(imei));				
				//####################################//							
			}
			//System.out.println("E7");
		}catch(Exception e) {
			//System.out.println("ERROR-IO SETTINGS:"+e.getMessage());
			}		
		//######### IO VALUES SETTING CLOSED ########		
		
		//############## CALL PROCESSES #########################
		/*
		try{
			if(alert_variables.alert_ignition_activated_flag.get(imei))
			{
				System.out.println(">>IGNITION ACTIVATED");
				alert_ignition_activated_process(imei);
			}
		}catch(Exception e) {System.out.println("Errr:IgnitionActAlert="+e.getMessage());}
		try{
			if(alert_variables.alert_ignition_deactivated_flag.get(imei))
			{
				System.out.println(">>IGNITION DEACTIVATED");
				alert_ignition_deactivated_process(imei);
			}
		}catch(Exception e) {System.out.println("Errr:IgnitionDeactAlert="+e.getMessage());}
		*/
		try{
			if(alert_variables.alert_sos_flag.get(imei))
			{
				//System.out.println(">>SOS");
				alert_sos_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:SosAlert="+e.getMessage());
			}
		
		/*
		try{
			if(alert_variables.alert_over_temperature_flag.get(imei))
			{
				System.out.println(">>OVER TEMPERATURE");
				alert_over_temperature_process(imei);
			}
		}catch(Exception e) {System.out.println("Errr:TempAlert="+e.getMessage());}			
		
		try{
			if(alert_variables.alert_overspeed_flag.get(imei))
			{
				System.out.println(">>OVERSPEED");
				alert_overspeed_process(imei);
			}
		}catch(Exception e) {System.out.println("Errr:OvrspdAlert="+e.getMessage());}
		
		try{
			if(alert_variables.alert_battery_connected_flag.get(imei))
			{
				System.out.println(">>BATTERY CONNECTED");
				alert_battery_connected_process(imei);
			}
		}catch(Exception e) {System.out.println("Errr:BattrConct Alert="+e.getMessage());}
		
		try{
			if(alert_variables.alert_battery_disconnected_flag.get(imei))
			{
				System.out.println(">>BATTERY DisCONNECTED");
				alert_battery_disconnected_process(imei);
			}
		}catch(Exception e) {System.out.println("Errr:BattrDisConct Alert="+e.getMessage());}
		*/
		
		try{
			if(alert_variables.alert_door1_open_flag.get(imei))
			{
				//System.out.println(">>DOOR1 OPEN");
				alert_door1_open_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:DoorOpen1 Alert="+e.getMessage());
			}
		
		try{
			if(alert_variables.alert_door1_closed_flag.get(imei))
			{
				//System.out.println(">>DOOR1 CLOSED");
				alert_door1_closed_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:DoorClose1 Alert="+e.getMessage());
			}
		
		try{
			if(alert_variables.alert_door2_open_flag.get(imei))
			{
				//System.out.println(">>DOOR2 OPEN");
				alert_door2_open_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:DoorOpen2 Alert="+e.getMessage());
			}
		
		try{
			if(alert_variables.alert_door2_closed_flag.get(imei))
			{
				//System.out.println(">>DOOR2 CLOSED");
				alert_door2_closed_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:DoorClose2 Alert="+e.getMessage());
			}
		
		try{
			if(alert_variables.alert_ac_on_flag.get(imei))
			{
				//System.out.println(">>AC ON");
				alert_ac_on_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:AC On Alert="+e.getMessage());
			}
		
		try{
			if(alert_variables.alert_ac_off_flag.get(imei))
			{
				//System.out.println(">>AC OFF");
				alert_ac_off_process(imei);
			}
		}catch(Exception e) {
			//System.out.println("Errr:AC Off Alert="+e.getMessage());
			}			
	}

	
	
	/************** PROCESS- ALERT DOOR1 OPEN ***************/
	public static void alert_door1_open_process(String imei)
	{
		//System.out.println("IN DOOR-1 OPEN");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "door1_open";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", door_open_start="1", door_open_stop="0";
		String q="\"";
			
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;
		String sms_status="",mail_status="";
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_door1_open.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
		//System.out.println("Door1_Open_SizeMainTemp="+main_temp.length+", main_temp_string="+alert_variables.alert_door1_open.get(imei));
			
		//System.out.println("DOOR1_OPEN: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device+", mail_status="+mail_status+", sms_status="+sms_status);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		//System.out.println("SizeDoorOpen="+alert_variables.temp_alert_door1_open.get(imei));
		if (alert_variables.temp_alert_door1_open.get(imei)==null) 											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			//System.out.println("alert_variables.door1_io_value.get(imei)="+alert_variables.door1_io_value.get(imei));
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.door1_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door1_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/					
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
					alert_variables.temp_alert_door1_open.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-1 OPEN: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.door1_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door1_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/					
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
					//System.out.println("ALERT_EXISTS_DB_DOOR1_OPEN="+alert_str);
					alert_variables.temp_alert_door1_open.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-1 OPEN: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("HASH_MAP_EXISTS");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_door1_open.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_door_open_status = getXmlAttribute(strLine1,"door_open_status=\"[^\"]+");							
				int prev_door_status_numeric = Integer.parseInt(prev_door_open_status);														
				//System.out.println("prev_door_open_status="+prev_door_open_status+" ,datetime.get(imei)="+alert_variables.datetime.get(imei));
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
				//System.out.println("DoorOpenFinal:1=>,alert_variables.sts.get(imei)="+alert_variables.sts.get(imei)+", alert_variables.door1_io_value.get(imei)="+alert_variables.door1_io_value.get(imei));					
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.door1_io_value.get(imei),5000.0f) < 0) )
				{
					//System.out.println("DoorOpenFinal:2=>prev_sts="+prev_sts+" ,prev_door_status_numeric="+prev_door_status_numeric+" ,alert_variables.door1_io_value.get(imei)="+alert_variables.door1_io_value.get(imei));
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_door_status_numeric ==0) && (Float.compare(alert_variables.door1_io_value.get(imei),250.0f) > 0) )
					{
						//System.out.println("DoorOpenFinal:3");
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_start+q+"/>"; 
						alert_variables.temp_alert_door1_open.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your -vehicle : "+alert_variables.vehicle_name.get(imei)+": Delivery Door Opened at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						//System.out.println("DOOR-1 OPEN: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.door1_io_value.get(imei),250.0f) < 0) && (prev_door_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
						alert_variables.temp_alert_door1_open.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_DOOR1_OPEN");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_DOOR1_OPEN");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
												
						//write_file_string(current_path,line,"current");	
						//System.out.println("DOOR-1 OPEN: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-DOOR-1 OPEN ACTIVATED PREV FILE READ:"+e.getMessage()); 
			}										
		}
	}
	
	
	/************** PROCESS- ALERT DOOR CLOSED ***************/
	public static void alert_door1_closed_process(String imei)
	{
		//System.out.println("IN DOOR-1 CLOSE");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "door1_close";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", door_close_start="1", door_close_stop="0";
		String q="\"";
			
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
		String sms_status="",mail_status="";						
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_door1_closed.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
		//System.out.println("Door1_Closed_SizeMainTemp="+main_temp.length);
					
		//System.out.println("DOOR-1_CLOSE: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device+" ,sms_status="+sms_status+" ,mail_status="+mail_status);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		//System.out.println("SizeDoorClose="+alert_variables.temp_alert_door1_closed.get(imei));
		
		if (alert_variables.temp_alert_door1_closed.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.door1_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door1_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
					alert_variables.temp_alert_door1_closed.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-1 CLOSE: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.door1_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door1_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
					//System.out.println("ALERT_EXISTS_DB_DOOR1_CLOSE="+alert_str);
					alert_variables.temp_alert_door1_closed.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-1 CLOSE: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists:Door1Closed");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_door1_closed.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_door1_close_status = getXmlAttribute(strLine1,"door_close_status=\"[^\"]+");							
				int prev_door_status_numeric = Integer.parseInt(prev_door1_close_status);														
				//System.out.println("prev_door1_close_status="+prev_door1_close_status+" ,alert_variables.datetime.get(imei)="+alert_variables.datetime.get(imei));
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.door1_io_value.get(imei),5000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_door_status_numeric ==0) && (Float.compare(alert_variables.door1_io_value.get(imei),250.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_start+q+"/>"; 
						alert_variables.temp_alert_door1_closed.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your -vehicle : "+alert_variables.vehicle_name.get(imei)+": Delivery Door Closed at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}						
						//System.out.println("DOOR-1 CLOSED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.door1_io_value.get(imei),250.0f) > 0) && (prev_door_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
						alert_variables.temp_alert_door1_closed.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_DOOR1_CLOSE");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_DOOR1_CLOSE");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("DOOR-1 CLOSE: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-DOOR-1 CLOSED PREV FILE READ:"+e.getMessage()); 
			}									
		}
	}
	
//$$$$$$$$$$$$$$$$$$
	
	/************** PROCESS- ALERT DOOR2 OPEN ***************/
	public static void alert_door2_open_process(String imei)
	{
		//System.out.println("IN DOOR2 OPEN");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "door2_open";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", door_open_start="1", door_open_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_door2_open.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/

		//System.out.println("DOOR-2 OPEN: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_door2_open.get(imei)==null)									/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.door2_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door2_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
					alert_variables.temp_alert_door2_open.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 OPEN: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.door2_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door2_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
					//System.out.println("ALERT_STR_DB_DOOR_OPEN2="+alert_str);
					alert_variables.temp_alert_door2_open.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 OPEN: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_door2_open.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_door_open_status = getXmlAttribute(strLine1,"door_open_status=\"[^\"]+");							
				int prev_door_status_numeric = Integer.parseInt(prev_door_open_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.door2_io_value.get(imei),5000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_door_status_numeric ==0) && (Float.compare(alert_variables.door2_io_value.get(imei),250.0f) > 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_start+q+"/>"; 
						alert_variables.temp_alert_door2_open.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your -vehicle : "+alert_variables.vehicle_name.get(imei)+": Manhole Door Opened at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("DOOR-2 OPEN: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.door2_io_value.get(imei),250.0f) < 0) && (prev_door_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
						alert_variables.temp_alert_door2_open.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("DOOR-2 OPEN: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-DOOR-2 OPEN ACTIVATED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED
	}
	
	
	/************** PROCESS- ALERT DOOR2 CLOSED ***************/
	public static void alert_door2_closed_process(String imei)
	{
		//System.out.println("IN DOOR-2 CLOSED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "door2_close";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", door_close_start="1", door_close_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_door2_closed.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
		//System.out.println("DOOR-2 CLOSE: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_door2_closed.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.door2_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door2_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
					alert_variables.temp_alert_door2_closed.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 CLOSE: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.door2_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door2_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
					alert_variables.temp_alert_door2_closed.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 CLOSE: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_door2_closed.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_door_close_status = getXmlAttribute(strLine1,"door_close_status=\"[^\"]+");							
				int prev_door_status_numeric = Integer.parseInt(prev_door_close_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.door2_io_value.get(imei),5000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_door_status_numeric ==0) && (Float.compare(alert_variables.door2_io_value.get(imei),250.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_start+q+"/>"; 
						alert_variables.temp_alert_door2_closed.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your -vehicle : "+alert_variables.vehicle_name.get(imei)+": Manhole Door Closed at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("DOOR-2 CLOSED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.door2_io_value.get(imei),250.0f) > 0) && (prev_door_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
						alert_variables.temp_alert_door2_closed.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("DOOR-2 CLOSE: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-DOOR-2 CLOSED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED
	}
	
	/************** PROCESS- ALERT DOOR3 OPEN ***************/
	public static void alert_door3_open_process(String imei)
	{
		//System.out.println("IN DOOR3 OPEN");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "door3_open";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", door_open_start="1", door_open_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_door3_open.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/

		//System.out.println("DOOR-3 OPEN: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_door3_open.get(imei)==null)									/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.door3_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door3_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
					alert_variables.temp_alert_door3_open.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 OPEN: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.door3_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door3_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
					//System.out.println("ALERT_STR_DB_DOOR_OPEN2="+alert_str);
					alert_variables.temp_alert_door3_open.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 OPEN: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_door3_open.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_door_open_status = getXmlAttribute(strLine1,"door_open_status=\"[^\"]+");							
				int prev_door_status_numeric = Integer.parseInt(prev_door_open_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.door3_io_value.get(imei),5000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_door_status_numeric ==0) && (Float.compare(alert_variables.door3_io_value.get(imei),250.0f) > 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_start+q+"/>"; 
						alert_variables.temp_alert_door3_open.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your -vehicle : "+alert_variables.vehicle_name.get(imei)+": Manhole Door2 Opened at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("DOOR-2 OPEN: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.door3_io_value.get(imei),250.0f) < 0) && (prev_door_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_open_status="+q+door_open_stop+q+"/>"; 
						alert_variables.temp_alert_door3_open.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("DOOR-2 OPEN: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-DOOR-3 OPEN ACTIVATED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED
	}
	
	
	/************** PROCESS- ALERT DOOR2 CLOSED ***************/
	public static void alert_door3_closed_process(String imei)
	{
		//System.out.println("IN DOOR-2 CLOSED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "door3_close";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", door_close_start="1", door_close_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_door3_closed.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
		//System.out.println("DOOR-2 CLOSE: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_door3_closed.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.door3_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door3_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
					alert_variables.temp_alert_door3_closed.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 CLOSE: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.door3_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.door3_io_value.get(imei),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
					alert_variables.temp_alert_door3_closed.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("DOOR-2 CLOSE: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_door3_closed.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_door_close_status = getXmlAttribute(strLine1,"door_close_status=\"[^\"]+");							
				int prev_door_status_numeric = Integer.parseInt(prev_door_close_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.door3_io_value.get(imei),5000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_door_status_numeric ==0) && (Float.compare(alert_variables.door3_io_value.get(imei),250.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_start+q+"/>"; 
						alert_variables.temp_alert_door3_closed.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your -vehicle : "+alert_variables.vehicle_name.get(imei)+": Manhole Door2 Closed at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("DOOR-2 CLOSED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.door3_io_value.get(imei),250.0f) > 0) && (prev_door_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" door_close_status="+q+door_close_stop+q+"/>"; 
						alert_variables.temp_alert_door3_closed.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("DOOR-3 CLOSE: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-DOOR-3 CLOSED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED
	}		
	
	/************** PROCESS- ALERT AC ON ***************/
	public static void alert_ac_on_process(String imei)
	{
		//System.out.println("IN AC ON");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "ac_on";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 5;  //in minutes
		String line ="", msg="", ac_on_start="1", ac_on_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_ac_on.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("AC ON: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_ac_on.get(imei)==null) 											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.ac_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.ac_io_value.get(imei),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_on_stop+q+"/>"; 
					alert_variables.temp_alert_ac_on.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("AC ON: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.ac_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.ac_io_value.get(imei),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_on_stop+q+"/>"; 
					alert_variables.temp_alert_ac_on.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("AC ON: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_ac_on.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_ac_status = getXmlAttribute(strLine1,"ac_status=\"[^\"]+");							
				int prev_ac_status_numeric = Integer.parseInt(prev_ac_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.ac_io_value.get(imei),3000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_ac_status_numeric ==0) && (Float.compare(alert_variables.ac_io_value.get(imei),500.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_on_start+q+"/>"; 
						alert_variables.temp_alert_ac_on.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+": AC On at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("AC ON: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.ac_io_value.get(imei),500.0f) > 0) && (prev_ac_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_on_stop+q+"/>"; 
						alert_variables.temp_alert_ac_on.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("AC ON: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-AC ON PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}

	
	/************** PROCESS- ALERT AC OFF ***************/
	public static void alert_ac_off_process(String imei)
	{
		//System.out.println("IN AC OFF");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "ac_off";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 5;  //in minutes
		String line ="", msg="", ac_off_start="1", ac_off_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_ac_off.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("AC OFF: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_ac_off.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(alert_variables.ac_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.ac_io_value.get(imei),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_off_stop+q+"/>"; 
					alert_variables.temp_alert_ac_on.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("AC OFF: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(alert_variables.ac_io_value.get(imei),0.0f) > 0) && (Float.compare(alert_variables.ac_io_value.get(imei),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_off_stop+q+"/>"; 
					alert_variables.temp_alert_ac_on.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("AC OFF: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_ac_on.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_ac_status = getXmlAttribute(strLine1,"ac_status=\"[^\"]+");							
				int prev_ac_status_numeric = Integer.parseInt(prev_ac_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.ac_io_value.get(imei),3000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_ac_status_numeric ==0) && (Float.compare(alert_variables.ac_io_value.get(imei),500.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_off_start+q+"/>"; 
						alert_variables.temp_alert_ac_off.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+": AC Off at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("AC OFF: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.ac_io_value.get(imei),500.0f) > 0) && (prev_ac_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ac_status="+q+ac_off_stop+q+"/>"; 
						alert_variables.temp_alert_ac_off.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("AC OFF: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION-AC OFF PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}	
	
	
	/************** PROCESS- ALERT IGNITION_ACTIVATED ***************/
	public static void alert_ignition_activated_process(String imei)
	{
		//System.out.println("IN IGNITION ACTIVATED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "ignition_activated";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 5;  //in minutes
		String line ="", msg="", ignition_activated_start="1", ignition_activated_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_ignition_activated.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("IGNITION ACTIVATED: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_ignition_activated.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(Float.parseFloat(alert_variables.engine_io_no.get(imei)),0.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.engine_io_no.get(imei)),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_activated_status="+q+ignition_activated_stop+q+"/>"; 
					alert_variables.temp_alert_ignition_activated.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("IGNITION ACTIVATED: CURRENT CREATED");					
				}
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_ignition_activated.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_ignition_activated_status = getXmlAttribute(strLine1,"ignition_eactivated_status=\"[^\"]+");							
				int prev_ignition_activated_status_numeric = Integer.parseInt(prev_ignition_activated_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.engine_io_value.get(imei),3000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_ignition_activated_status_numeric ==0) && (Float.compare(alert_variables.engine_io_value.get(imei),500.0f) > 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_activated_status="+q+ignition_activated_start+q+"/>"; 
						alert_variables.temp_alert_ignition_activated.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" Ignition activated at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("IGNITION ACTIVATED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.engine_io_value.get(imei),500.0f) < 0) && (prev_ignition_activated_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_activated_status="+q+ignition_activated_stop+q+"/>"; 
						alert_variables.temp_alert_ignition_activated.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("IGNITION ACTIVATED: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION IGNITION ACTIVATED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}
	
	/************** PROCESS- ALERT IGNITION_DEACTIVATED ***************/
	public static void alert_ignition_deactivated_process(String imei)
	{
		//System.out.println("IN IGNITION DEACTIVATED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "ignition_deactivated";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 5;  //in minutes
		String line ="", msg="", ignition_deactivated_start="1", ignition_deactivated_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_ignition_deactivated.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
		//System.out.println("IGNITION DEACTIVATED: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_ignition_deactivated.get(imei)==null) 											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(Float.parseFloat(alert_variables.engine_io_no.get(imei)),0.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.engine_io_no.get(imei)),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_deactivated_status="+q+ignition_deactivated_stop+q+"/>"; 
					alert_variables.temp_alert_ignition_activated.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("IGNITION DEACTIVATED: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(Float.parseFloat(alert_variables.engine_io_no.get(imei)),0.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.engine_io_no.get(imei)),3000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_deactivated_status="+q+ignition_deactivated_stop+q+"/>"; 
					alert_variables.temp_alert_ignition_activated.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("IGNITION DEACTIVATED: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_ignition_deactivated.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_ignition_deactivated_status = getXmlAttribute(strLine1,"ignition_deactivated_status=\"[^\"]+");							
				int prev_ignition_deactivated_status_numeric = Integer.parseInt(prev_ignition_deactivated_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.engine_io_value.get(imei),3000.0f) < 0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_ignition_deactivated_status_numeric ==0) && (Float.compare(alert_variables.engine_io_value.get(imei),500.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_deactivated_status="+q+ignition_deactivated_start+q+"/>"; 
						alert_variables.temp_alert_ignition_deactivated.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" Ignition deactivated at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("IGNITION DEACTIVATED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.engine_io_value.get(imei),500.0f) > 0) && (prev_ignition_deactivated_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" ignition_activated_status="+q+ignition_deactivated_stop+q+"/>"; 
						alert_variables.temp_alert_ignition_deactivated.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("IGNITION DEACTIVATED: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION IGNITION DEACTIVATED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}
	
	/************** PROCESS- ALERT SOS ***************/
	public static void alert_sos_process(String imei)
	{
		//System.out.println("IN SOS");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "sos";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 1;  //in minutes
		String line ="", msg="", sos_start="1", sos_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_sos.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("SOS: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_sos.get(imei)==null) 											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			//System.out.println("ALERT_STR_SOS="+alert_str);
			if(alert_str==null)
			{
				if( (Float.compare(Float.parseFloat(alert_variables.sos_io_no.get(imei)),0.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.sos_io_no.get(imei)),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" sos_status="+q+sos_stop+q+"/>"; 
					alert_variables.temp_alert_sos.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("SOS: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(Float.parseFloat(alert_variables.sos_io_no.get(imei)),0.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.sos_io_no.get(imei)),5000.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" sos_status="+q+sos_stop+q+"/>"; 
					//System.out.println("ALERT_EXISTS_SOS="+alert_str);
					alert_variables.temp_alert_sos.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("SOS: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_sos.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_sos_status = getXmlAttribute(strLine1,"sos_status=\"[^\"]+");							
				int prev_sos_status_numeric = Integer.parseInt(prev_sos_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.sos_io_value.get(imei),5000.0f) < 0) )
				{					
					//if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_sos_status_numeric ==0) && ( (Float.compare(alert_variables.sos_io_value.get(imei),3500.0f) > 0) || (Float.compare(alert_variables.sos_io_value.get(imei),200.0f) < 0) ) )
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_sos_status_numeric ==0) && (Float.compare(alert_variables.sos_io_value.get(imei),200.0f) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" sos_status="+q+sos_start+q+"/>"; 
						alert_variables.temp_alert_sos.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" at Risk at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("SOS: SEND OK");							
					}					
					//else if((Float.compare(alert_variables.sos_io_value.get(imei),200.0f) > 0) && (Float.compare(alert_variables.sos_io_value.get(imei),3500.0f) < 0) )
					else if((Float.compare(alert_variables.sos_io_value.get(imei),200.0f) > 0) && (prev_sos_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" sos_status="+q+sos_stop+q+"/>"; 
						alert_variables.temp_alert_sos.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("SOS: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION SOS PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}


	/************** PROCESS- ALERT OVER TEMPERATURE ***************/
	public static void alert_over_temperature_process(String imei)
	{
		//System.out.println("IN OVER TEMPERATURE");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "over_temperature";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 5;  //in minutes
		String line ="", msg="", over_temperature_start="1", over_temperature_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_over_temperature.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("OVER TEMPERATURE: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_over_temperature.get(imei)==null) 											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( (Float.compare(Float.parseFloat(alert_variables.temperature_io_no.get(imei)),-30.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.temperature_io_no.get(imei)),70.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_temperature_status="+q+over_temperature_stop+q+"/>"; 
					alert_variables.temp_alert_over_temperature.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("OVER TEMPERATURE: CURRENT CREATED");					
				}
			}
			else
			{
				if( (Float.compare(Float.parseFloat(alert_variables.temperature_io_no.get(imei)),-30.0f) > 0) && (Float.compare(Float.parseFloat(alert_variables.temperature_io_no.get(imei)),70.0f) < 0) )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_temperature_status="+q+over_temperature_stop+q+"/>"; 
					alert_variables.temp_alert_over_temperature.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("OVER TEMPERATURE: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_over_temperature.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_overtemperature_status = getXmlAttribute(strLine1,"over_temperature_status=\"[^\"]+");							
				int prev_over_temperaure_status_numeric = Integer.parseInt(prev_overtemperature_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) && (Float.compare(alert_variables.temperature_io_value.get(imei),-30.0f) > 0) && (Float.compare(alert_variables.temperature_io_value.get(imei),70.0f) < 0))
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_over_temperaure_status_numeric ==0) && (Float.compare(alert_variables.sos_io_value.get(imei),10.0f) > 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_temperature_status="+q+over_temperature_start+q+"/>"; 
						alert_variables.temp_alert_over_temperature.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" in Over Temperature at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("OVER TEMPERATURE: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.temperature_io_value.get(imei),10.0f) < 0) && (prev_over_temperaure_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_temperature_status="+q+over_temperature_stop+q+"/>"; 
						alert_variables.temp_alert_over_temperature.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("OVER TEMPERATURE: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION OVER TEMPERATURE PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}
	
	
	/************** PROCESS- ALERT OVERSPEED ***************/
	public static void alert_overspeed_process(String imei)
	{
		//System.out.println("IN OVER SPEED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "over_speed";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		int threshold_time = 5;  //in minutes
		String line ="", msg="", over_speed_start="1", over_speed_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_overspeed.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("OVER SPEED: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_overspeed.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				if( Float.compare(alert_variables.max_speed.get(imei),30.0f) > 0 )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_speed_status="+q+over_speed_stop+q+"/>"; 
					alert_variables.temp_alert_overspeed.put(imei, line);
					//write_file_string(current_path,line,"current");	
					//System.out.println("OVER SPEED: CURRENT CREATED");					
				}
			}
			else
			{
				if( Float.compare(alert_variables.max_speed.get(imei),30.0f) > 0 )			/****** IF ENGINE IO VALUE EXISTS *****************/
				{
					//######## CREATE CURRENT FILE ###########/
					//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_speed_status="+q+over_speed_stop+q+"/>"; 
					alert_variables.temp_alert_overspeed.put(imei, alert_str);
					//write_file_string(current_path,line,"current");	
					//System.out.println("OVER SPEED: CURRENT CREATED");					
				}				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_overspeed.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_overspeed_status = getXmlAttribute(strLine1,"over_speed_status=\"[^\"]+");							
				int prev_over_speed_status_numeric = Integer.parseInt(prev_overspeed_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_over_speed_status_numeric ==0) && (Float.compare(alert_variables.speed.get(imei),alert_variables.max_speed.get(imei)) > 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_speed_status="+q+over_speed_start+q+"/>"; 
						alert_variables.temp_alert_overspeed.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" in Over Speed at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("OVER SPEED: SEND OK");							
					}
					
					else if( ((Float.compare(alert_variables.speed.get(imei), alert_variables.max_speed.get(imei)) < 0))  && (prev_over_speed_status_numeric ==1) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" over_speed_status="+q+over_speed_stop+q+"/>"; 
						alert_variables.temp_alert_overspeed.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("OVER SPEED: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION OVER SPEED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}


	/************** PROCESS- ALERT BATTERY CONNECTED ***************/
	public static void alert_battery_connected_process(String imei)
	{
		//System.out.println("IN BATTERY CONNECTED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "battery_connected";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		float battery_threshold = 9.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", battery_connected_start="1", battery_connected_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_battery_connected.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("BATTERY CONNECTED: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_battery_connected.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
				//######## CREATE CURRENT FILE ###########/
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_connected_status="+q+battery_connected_stop+q+"/>"; 
				alert_variables.temp_alert_battery_connected.put(imei, line);
				//write_file_string(current_path,line,"current");	
				//System.out.println("BATTERY CONNECTED: CURRENT CREATED");	
			}
			else
			{
				//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_connected_status="+q+battery_connected_stop+q+"/>"; 
				alert_variables.temp_alert_battery_connected.put(imei, alert_str);
				//write_file_string(current_path,line,"current");	
				//System.out.println("BATTERY CONNECTED: CURRENT CREATED");					
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_battery_connected.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_battery_connected_status = getXmlAttribute(strLine1,"battery_connected_status=\"[^\"]+");							
				int prev_battery_connected_status_numeric = Integer.parseInt(prev_battery_connected_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_battery_connected_status_numeric ==0) && (Float.compare(alert_variables.sup_v.get(imei), battery_threshold) > 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_connected_status="+q+battery_connected_start+q+"/>"; 
						alert_variables.temp_alert_battery_connected.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" Battery Connected at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("BATTERY CONNECTED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.sup_v.get(imei), battery_threshold) < 0) && (prev_battery_connected_status_numeric ==1) ) 
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_connected_status="+q+battery_connected_stop+q+"/>"; 
						alert_variables.temp_alert_battery_connected.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("BATTERY CONNECTED: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION BATTERY CONNECTED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}

	
	/************** PROCESS- ALERT BATTERY DISCONNECTED ***************/
	public static void alert_battery_disconnected_process(String imei)
	{
		//System.out.println("IN BATTERY DISCONNECTED");			//ENGINE IO DOES NOT NEED TO FETCH FROM FILE
		String alert_type = "battery_disconnected";
		String alert_string = "", folder_date="", current_path ="", current_file="", nearest_landmark="" ,send_msg_path ="", send_msg_date="",account_id="";
		String[] main_temp;
		String[] temp1;
		String[] temp2;
		String[] temp3;
		//float engine_io_value = 0.0f;
		float battery_threshold = 9.0f;
		int threshold_time = 2;  //in minutes
		String line ="", msg="", battery_disconnected_start="1", battery_disconnected_stop="0";
		String q="\"";
		String sms_status="",mail_status="";	
		String mobile_no_device="",alert_duration_device="",alert_id_device="",alert_name_device="",escalation_id_device="",person_name_device="",email_device;		
								
		/*//###### GET DB ENGINE IO ###########/
		engine_io_value = get_io(av, "engine");
		//av.engine_io = engine_io;*/
		main_temp = alert_variables.alert_battery_disconnected.get(imei).split("\\$");
		
		//####### SPLIT ALERT STRING OF DATABASE AND GET INDIVIDUAL VARIABLES #######/
			
		//System.out.println("BATTERY DISCONNECTED: mobile_no="+mobile_no_device+" ,alert_duration="+alert_duration_device+" ,alert_id="+alert_id_device+" ,alert_name="+alert_name_device+" ,escalation_id="+escalation_id_device+" ,person_name="+person_name_device+" ,email="+email_device);
							
		//########## MAKE NEW DATE FOLDER -IF FOLDER DOES NOT EXISTS ########/
		//temp2 = split(av.datetime," ");
		/*temp2 = alert_variables.datetime.get(imei).split(" ");
		folder_date = temp2[0];
		String mydir1 = alert_variables.root_dir+"/current_alerts/ignition_activated";
		boolean success1 = (new File(mydir1 + "/" + folder_date)).mkdir();*/

		temp3= alert_variables.sts.get(imei).split(" ");
		send_msg_date = temp3[0];			
		String mydir2 = alert_variables.root_dir+"/send_messages";
		boolean success2 = (new File(mydir2 + "/" + send_msg_date)).mkdir();
		send_msg_path = alert_variables.root_dir+"/send_messages/"+send_msg_date+"/"+imei+".xml";			
		
		//########## CHECK IF FILE EXISTS #########/		
		/*current_file = mobile_no_device+"_"+alert_variables.imei.get(imei); 			
		current_path = alert_variables.root_dir+"/current_alerts/ignition_activated/"+folder_date+"/"+current_file+".xml";									
		File file = new File(current_path);
		boolean exists = file.exists();*/
		
		if (alert_variables.temp_alert_battery_disconnected.get(imei)==null)											/****** CREATE FILE -IF FILE DOES NOT EXIST *********/
		{
			String alert_str = read_database_alert_status(imei, alert_string, alert_type);
			if(alert_str==null)
			{
				//######## CREATE CURRENT FILE ###########/
				line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_disconnected_status="+q+battery_disconnected_stop+q+"/>"; 
				alert_variables.temp_alert_battery_disconnected.put(imei, line);
				//write_file_string(current_path,line,"current");	
				//System.out.println("BATTERY DISCONNECTED: CURRENT CREATED");
			}
			else
			{
				//######## CREATE CURRENT FILE ###########/
				//line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_disconnected_status="+q+battery_disconnected_stop+q+"/>"; 
				alert_variables.temp_alert_battery_disconnected.put(imei, alert_str);
				//write_file_string(current_path,line,"current");	
				//System.out.println("BATTERY DISCONNECTED: CURRENT CREATED");				
			}
		}
		else													/****** READ FILE -IF FILE EXISTS ********/
		{  
			//System.out.println("File exists");
			try {
				//##### READ ALL PARAMETERS OF PREVIOUS XML_DATA #####/					
				String strLine1;
				//strLine1 = read_file_string(current_path);		 /******* GET LINE STRING **********/
				strLine1 = alert_variables.temp_alert_battery_disconnected.get(imei);	
				
				//######### GET XML PARAMETERS ###########/
				String prev_imei = getXmlAttribute(strLine1,"imei=\"[^\"]+");
				String prev_date = getXmlAttribute(strLine1,"datetime=\"[^\"]+");
				String prev_sts = getXmlAttribute(strLine1,"sts=\"[^\"]+");
				String prev_lat = getXmlAttribute(strLine1,"lat=\"[^\"]+");					
				String prev_lng = getXmlAttribute(strLine1,"lng=\"[^\"]+");
				//String prev_engine_io_value = getXmlAttribute(strLine1,""+engine_io+"\"=\"[^\"]+");
				String prev_battery_disconnected_status = getXmlAttribute(strLine1,"battery_disconnected_status=\"[^\"]+");							
				int prev_battery_disconnected_status_numeric = Integer.parseInt(prev_battery_disconnected_status);														
				//System.out.println("prev_date="+prev_date+" ,curr_date="+av.datetime);
				//COMPARE DATES
				//######## WRITE IF CONDITION SATISFIED ############/
									
				if( (calculateTimeDiff(alert_variables.sts.get(imei),alert_variables.datetime.get(imei))<60) && (threshold_time>0) )
				{					
					if( (calculateTimeDiff(prev_sts,alert_variables.sts.get(imei)) >= threshold_time) && (prev_battery_disconnected_status_numeric ==0) && (Float.compare(alert_variables.sup_v.get(imei), battery_threshold) < 0) )
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+alert_variables.imei.get(imei)+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_connected_status="+q+battery_disconnected_start+q+"/>"; 
						alert_variables.temp_alert_battery_disconnected.put(imei, line);
						//write_file_string(current_path,line,"current");
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							//System.out.println("UPDATE_DB_SOS");
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							//System.out.println("INSERT_DB_SOS");
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################						
						
						//###### UPDATE SEND MESSAGE FILE ######/	
						//MSG STRING: Your vehicle V ignition is activated at at distance D1 from L  at D2
						msg = "Your vehicle-"+alert_variables.vehicle_name.get(imei)+" Battery DisConnected at "+alert_variables.datetime.get(imei);
						//line = "\n<marker phone="+q+mobile_no_device+q+" vehicle_id="+q+av.vehicle_id+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+av.sts+q+" datetime="+q+av.datetime+q+" message="+q+msg+q+" person="+q+person_name_device+q+"/>\n</t1>";					
						for(int i=0;i<main_temp.length;i++)
						{
							alert_string = main_temp[i];
							//temp=split(alert_string,"#");
							temp1 = alert_string.split("#");
							
							mobile_no_device = temp1[0];
							alert_duration_device = temp1[1];
							alert_id_device = temp1[2];
							alert_name_device = temp1[3];
							escalation_id_device = temp1[4];
							person_name_device = temp1[5];
							email_device = temp1[6];
							sms_status = temp1[7];
							mail_status = temp1[8];
						
							line = "\n<marker vehicle_name="+q+alert_variables.vehicle_name.get(imei)+q+" alert_type="+q+alert_type+q+" account_id="+q+alert_variables.account_id+q+" phone="+q+mobile_no_device+q+" vehicle_id="+q+alert_variables.vehicle_id.get(imei)+q+" alertid="+q+alert_id_device+q+" escalationid="+q+escalation_id_device+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" message="+q+msg+q+" person="+q+person_name_device+q+" email="+q+email_device+q+" sms_status="+q+sms_status+q+" mail_status="+q+mail_status+q+"/>\n</t1>";
						
							write_file_string(send_msg_path,line,"send_msg");
						}
						
						//System.out.println("BATTERY DISCONNECTED: SEND OK");							
					}
					
					else if( (Float.compare(alert_variables.sup_v.get(imei), battery_threshold) < 0) && (prev_battery_disconnected_status_numeric ==1) ) 
					{
						//##### UPDATE CURRENT FILE #######/
						line = "<marker imei="+q+imei+q+" lat="+q+alert_variables.lat.get(imei)+q+" lng="+q+alert_variables.lng.get(imei)+q+" sts="+q+alert_variables.sts.get(imei)+q+" datetime="+q+alert_variables.datetime.get(imei)+q+" battery_connected_status="+q+battery_disconnected_stop+q+"/>"; 
						alert_variables.temp_alert_battery_disconnected.put(imei, line);
						
						//######### UPDATE ALERT DATABASE
						String alert_str = read_database_alert_status(imei, line, alert_type);
						if(alert_str!=null)
						{
							update_database_alert_status(imei, line, alert_type);
						}
						else
						{
							insert_database_alert_status(imei, line, alert_type);
						}
						//###############################
						
						//write_file_string(current_path,line,"current");	
						//System.out.println("BATTERY DISCONNECTED: FALSE");							
					}					
				}
			} 
			catch(Exception e) 
			{ 
				//System.out.println("EXCEPTION BATTERY DISCONNECTED PREV FILE READ:"+e.getMessage()); 
			}
			
		} //ELSE CLOSED						
	}
	
	
	/************* METHOD- GET XML ATTRIBUTES ************/
	public static String getXmlAttribute(String line, String param)
	{
		String str1 ="";
		String value ="";
		String[] str2;
		
		try {
			Pattern p = Pattern.compile(param);
			Matcher matcher = p.matcher(line);				
			
			while(matcher.find()){
						
				str1 = matcher.group().toString().replace("\"","");
				str2 = str1.split("=");
				//System.out.println(str2[1]);
				value = str2[1];
				//System.out.println("val="+value);
				break;
			}
		} catch(Exception e) { 
			//System.out.println("Line:"+line+" ,Error in function-Xml Attribute:"+e.getMessage());
			}
		
		return value;		
	}	
		
	/************* METHOD- READ CONTENT OF FILE ************/
	public String read_file_content(String current_file)
	{
		String strLine1 ="",file_content = "";
		
		try{
			FileInputStream fstream1 = new FileInputStream(current_file);
			// Get the object of DataInputStream
			DataInputStream in1 = new DataInputStream(fstream1);
			BufferedReader br1 = new BufferedReader(new InputStreamReader(in1));	
			
			while ((strLine1 = br1.readLine()) != null) 
			{																																						
				file_content = file_content + strLine1;							
			}
		
			fstream1.close();
			in1.close();

		} catch(Exception e) {
			//System.out.println("Exception2 in line Read:"+e.getMessage());
			}	
		
		return file_content;
	}	

	
	//############ IF CONTENT EXIST IN FILE
	public static boolean IfExist_xmlAttribute(String line, String param)
	{
		String str1 ="";
		String value ="";
		String[] str2;
		
		try {
			//System.out.println("LINE:"+line+" ,param="+param);
			Pattern p = Pattern.compile(param);
			Matcher matcher = p.matcher(line);				
			
			while(matcher.find()){
						
				//str1 = matcher.group().toString().replace("\"","");
				//str2 = str1.split("=");
				//System.out.println("MATCH str1");
				//value = str2[1];
				//System.out.println("val="+value);
				return true;
				//break;
			}
		} catch(Exception e) { 
			//System.out.println("Error in function-Xml Attribute"+e.getMessage());
			}
		
		return false;		
	}	
	
	/************* METHOD- CALCULATE DISTANCE ************/
	public static float calculateDistance(float lat1, float lat2, float lng1, float lng2) 
	{	    
		//System.out.println("In CACL DIST : lat1 : "+lat1+" lng1 : "+lng1+" lat2 : "+lat2 + " lng2 : "+lng2);
		double earthRadius = 3958.75;
		double dLat = Math.toRadians(lat2-lat1);
		double dLng = Math.toRadians(lng2-lng1);
		double a = Math.sin(dLat/2) * Math.sin(dLat/2) +
		Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) *
		Math.sin(dLng/2) * Math.sin(dLng/2);
		double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
		double dist = earthRadius * c;
		int meterConversion = 1609;
		return new Float(dist * meterConversion).floatValue();
    }
	
	/************* METHOD- CALCULATE TIME DIFFERENCE ************/
	public static long calculateTimeDiff(String time1, String time2)
	{
		//System.out.println("Time1="+time1+" ,Time2="+time2);
		//System.out.println();
		
		if(time1.equalsIgnoreCase(""))
		{
			return 600;
		}
		
		int timediff=0;
		String[] temp,temp1,temp2;
		Calendar calendar1 = Calendar.getInstance();
		Calendar calendar2 = Calendar.getInstance();

		/*temp=split(time1," ");
		temp1=split(temp[0],"-");
		temp2=split(temp[1],":");*/
		
		temp= time1.split(" ");
		temp1=temp[0].split("-");
		temp2=temp[1].split(":");
		

		calendar1.set(Integer.parseInt(temp1[0]), Integer.parseInt(temp1[1]), Integer.parseInt(temp1[2]) , Integer.parseInt(temp2[0]), Integer.parseInt(temp2[1]), Integer.parseInt(temp2[2]) );

		/*temp=split(time2," ");
		temp1=split(temp[0],"-");
		temp2=split(temp[1],":");*/
		
		temp=time2.split(" ");
		temp1=temp[0].split("-");
		temp2=temp[1].split(":");
		

		calendar2.set(Integer.parseInt(temp1[0]), Integer.parseInt(temp1[1]), Integer.parseInt(temp1[2]) , Integer.parseInt(temp2[0]), Integer.parseInt(temp2[1]), Integer.parseInt(temp2[2]) );

		long milliseconds1 = calendar1.getTimeInMillis();
		long milliseconds2 = calendar2.getTimeInMillis();
		long diff = milliseconds2 - milliseconds1;
		long diffMinutes = diff / (60 * 1000);

		//System.out.println("Time in minutes: " + diffMinutes + " minutes.");        		
		//"yyyy-MM-dd HH:mm:ss"
		return diffMinutes;
	}

	/************* METHOD- ROUND TO TWO DECIMAL ************/
	public double roundTwoDecimals(double d) 
	{
    	DecimalFormat twoDForm = new DecimalFormat("#.##");
		return Double.valueOf(twoDForm.format(d));
	}
	
	/************* METHOD- ROUND TO TWO DECIMAL ************/
	public static String read_file_string(String current_file)
	{
		String strLine1 ="";
		
		try{
			FileInputStream fstream1 = new FileInputStream(current_file);
			// Get the object of DataInputStream
			DataInputStream in1 = new DataInputStream(fstream1);
			BufferedReader br1 = new BufferedReader(new InputStreamReader(in1));	
							
			while ((strLine1 = br1.readLine()) != null) 
			{																																						
				int len = strLine1.length();
				
				if(len < 100)
				{
					continue;
				}													
				try{	
					return strLine1;

				//System.out.println("PREV SECTOR="+prev_sector_id);			
				} catch(Exception e) {
					//System.out.println("Exception1 in middle of Read file:"+e.getMessage());
					}
			}
		
			fstream1.close();
			in1.close();

		} catch(Exception e) {
			//System.out.println("Exception2 in line Read:"+e.getMessage());
			}	
		
		return strLine1;
	}
	
		
	/************* METHOD- WRITE FILE STRING ************/
	public static void write_file_string(String xml_path, String xml_line, String type)
	{
		RandomAccessFile raf1 =null;						// IF TYPE=CURRENT , WHOLE STRING WILL COME
		BufferedWriter out1 =null;							// IF TYPE=SEND_MSG, SECOND HALF STRING WILL COME
		BufferedWriter out2 =null;
		String marker1="";

		//System.out.println("type="+type);
		//System.out.println("xml_path="+xml_path+" ,xml_line="+xml_line+" ,type="+type);
		
		if(type.equalsIgnoreCase("current") || (type.equalsIgnoreCase("temp_variables")))				//## WRITE STRING -ONE TIME 
		{
			try{
			out1 = new BufferedWriter(new FileWriter(xml_path, false));																														
			out1.write(xml_line);
			out1.close();
			}catch(Exception e) {
				//System.out.println("EXCEPTION IN CURRENT FILE WRITE:"+e.getMessage());
				}
		}
		
		else if( (type.equalsIgnoreCase("send_msg")) || (type.equalsIgnoreCase("temp_escalations")) || (type.equalsIgnoreCase("temp_landmarks")) || (type.equalsIgnoreCase("temp_regions")))	//## WRITE STRING -MULTIPLE TIMES
		{			
			try{
				raf1 = new RandomAccessFile(xml_path, "rw");
				long length1 = raf1.length();

				//if(type.equals("send_msg"))
					//System.out.println("###############RAF LENGTH="+raf1.length());
				
				if(length1==0)
				{
					out1 = new BufferedWriter(new FileWriter(xml_path, false));		//CREATE FILE		
					marker1 = "<t1>";
					out1.write(marker1);
					out1.close();
				}	

				else
				{
					//System.out.println("File Length="+raf1.length());
					raf1.setLength(length1 - 6);
				}

				raf1.close();
			}catch(Exception e1){
				//System.out.println("EXCEPTION IN SEND MSG WRITE-1:"+e1.getMessage());
				}		
			
			
			try{
				//System.out.println("xml_path="+xml_path);
				out2 = new BufferedWriter(new FileWriter(xml_path, true));			 // UPDATE FILE																		
				out2.write(xml_line);
				out2.close();
			}catch(Exception e2){
				//System.out.println("EXCEPTION IN SEND MSG WRITE-2:"+e2.getMessage());
				}				
		}
		/*else if( (type.equalsIgnoreCase("error_log")) )		
		{
			try{
				//System.out.println("xml_path="+xml_path);
				out2 = new BufferedWriter(new FileWriter(xml_path, true));			 // UPDATE FILE																		
				xml_line = xml_line+"\n";
				out2.write(xml_line);
				out2.close();
			}catch(Exception e2){System.out.println("EXCEPTION IN SEND MSG WRITE-2:"+e2.getMessage());}						
		}*/		
	}
	
		
	/************* METHOD- GET DB IO VALUES ************/
	public static float get_io_value(String imei, String type)
	{		
		//System.out.println("TYPE="+type);
		String io_string ="";
		float io_value = 0.0f;
		float engine_io_value = 0.0f;
		float sos_io_value = 0.0f;
		float door1_io_value = 0.0f;
		float door2_io_value = 0.0f;
		float ac_io_value = 0.0f;
		
		try{
			if(type.equalsIgnoreCase("engine"))
			{
				if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("1"))
				{
					engine_io_value = alert_variables.io1.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("2"))
				{
					engine_io_value = alert_variables.io2.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("3"))
				{
					engine_io_value = alert_variables.io3.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("4"))
				{
					engine_io_value = alert_variables.io4.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("5"))
				{
					engine_io_value = alert_variables.io5.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("6"))
				{
					engine_io_value = alert_variables.io6.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("7"))
				{
					engine_io_value = alert_variables.io7.get(imei);
				}
				else if(alert_variables.engine_io_no.get(imei).equalsIgnoreCase("8"))
				{
					engine_io_value = alert_variables.io8.get(imei);
				}

				io_value = engine_io_value;			
			}
			if(type.equalsIgnoreCase("sos"))
			{
				if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("1"))
				{
					sos_io_value = alert_variables.io1.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("2"))
				{
					sos_io_value = alert_variables.io2.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("3"))
				{
					sos_io_value = alert_variables.io3.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("4"))
				{
					sos_io_value = alert_variables.io4.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("5"))
				{
					sos_io_value = alert_variables.io5.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("6"))
				{
					sos_io_value = alert_variables.io6.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("7"))
				{
					sos_io_value = alert_variables.io7.get(imei);
				}
				else if(alert_variables.sos_io_no.get(imei).equalsIgnoreCase("8"))
				{
					sos_io_value = alert_variables.io8.get(imei);
				}

				io_value = sos_io_value;			
			}
			
			if(type.equalsIgnoreCase("door1"))
			{
				//System.out.println("DOOR1="+alert_variables.door1_io_no.get(imei));
				if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("1"))
				{
					door1_io_value = alert_variables.io1.get(imei);
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("2"))
				{
					door1_io_value = alert_variables.io2.get(imei);
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("3"))
				{					
					door1_io_value = alert_variables.io3.get(imei);
					//System.out.println("DoorOpen-IO3 Found:Val="+door1_io_value);					
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("4"))
				{
					door1_io_value = alert_variables.io4.get(imei);
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("5"))
				{
					door1_io_value = alert_variables.io5.get(imei);
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("6"))
				{
					door1_io_value = alert_variables.io6.get(imei);
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("7"))
				{
					door1_io_value = alert_variables.io7.get(imei);
				}
				else if(alert_variables.door1_io_no.get(imei).equalsIgnoreCase("8"))
				{
					door1_io_value = alert_variables.io8.get(imei);
				}

				io_value = door1_io_value;			
			}
			
			if(type.equalsIgnoreCase("door2"))
			{
				//System.out.println("DOOR2");
				if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("1"))
				{
					door2_io_value = alert_variables.io1.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("2"))
				{
					door2_io_value = alert_variables.io2.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("3"))
				{
					door2_io_value = alert_variables.io3.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("4"))
				{
					door2_io_value = alert_variables.io4.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("5"))
				{
					door2_io_value = alert_variables.io5.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("6"))
				{
					door2_io_value = alert_variables.io6.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("7"))
				{
					door2_io_value = alert_variables.io7.get(imei);
				}
				else if(alert_variables.door2_io_no.get(imei).equalsIgnoreCase("8"))
				{
					door2_io_value = alert_variables.io8.get(imei);
				}

				io_value = door2_io_value;			
			}			
			
			if(type.equalsIgnoreCase("ac"))
			{
				if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("1"))
				{
					ac_io_value = alert_variables.io1.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("2"))
				{
					ac_io_value = alert_variables.io2.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("3"))
				{
					ac_io_value = alert_variables.io3.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("4"))
				{
					ac_io_value = alert_variables.io4.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("5"))
				{
					ac_io_value = alert_variables.io5.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("6"))
				{
					ac_io_value = alert_variables.io6.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("7"))
				{
					ac_io_value = alert_variables.io7.get(imei);
				}
				else if(alert_variables.ac_io_no.get(imei).equalsIgnoreCase("8"))
				{
					ac_io_value = alert_variables.io8.get(imei);
				}

				io_value = ac_io_value;			
			}						
			
		}catch (Exception e) { 
			//System.out.println("EXCEPTION IN GETTING IO VALUES:"+e.getMessage()); 
			}
		
		return io_value;
	}	
	
	/******* READ AND SET ESCALATION VARIABLES - METHOD BODY **************/
	public static void escalation_read_set_variables(String escalation_path, String imei)
	{
		String strLine1 ="";
		String alert_name_tmp = "", person_mobile_tmp = "", alert_duration_tmp = "", alert_id_tmp = "";
		String escalation_id_tmp = "", person_name_tmp = "", email_tmp = "", tmp_string ="", sms_status ="", mail_status ="";
		String landmark_id_tmp = "", landmark_name_tmp = "", landmark_coord_tmp = "", distance_variable_tmp="";		
		
		boolean halt_start_flag = false, halt_stop_flag = false, ignition_activated_flag = false, ignition_deactivated_flag = false,sos_flag = false, over_temperature_flag = false, overspeed_flag = false, battery_connected_flag = false, battery_disconnected_flag = false, door1_open_flag = false, door1_close_flag = false, door2_open_flag = false, door2_close_flag = false, ac_on_flag = false,ac_off_flag = false;
				
		try{
			FileInputStream fstream1 = new FileInputStream(escalation_path);
			DataInputStream in1 = new DataInputStream(fstream1);
			BufferedReader br1 = new BufferedReader(new InputStreamReader(in1));	
			
			String tmp_string_halt_start ="", tmp_string_halt_stop ="", tmp_string_ignition_activated ="", tmp_string_ignition_deactivated ="", tmp_string_sos ="", tmp_string_over_temperature ="", tmp_string_overspeed ="", tmp_string_battery_connected ="", tmp_string_battery_disconnected ="", tmp_string_door1_open ="", tmp_string_door1_closed ="", tmp_string_door2_open ="", tmp_string_door2_closed ="", tmp_string_ac_on ="", tmp_string_ac_off ="";		
			
			
			while ((strLine1 = br1.readLine()) != null) 
			{																																						
				int len = strLine1.length();
				
				if(len < 100)
				{
					continue;
				}													
				try{						
					//System.out.println(strLine1);
					
					alert_name_tmp = getXmlAttribute(strLine1,"alert_name=\"[^\"]+");
					person_mobile_tmp = getXmlAttribute(strLine1,"person_mobile=\"[^\"]+");
					alert_duration_tmp = getXmlAttribute(strLine1,"alert_duration=\"[^\"]+");
					alert_id_tmp = getXmlAttribute(strLine1,"alert_id=\"[^\"]+");
					landmark_id_tmp = getXmlAttribute(strLine1,"landmark_id=\"[^\"]+");				//IF LANDMARK ALERT IS ASSIGNED
					landmark_name_tmp = getXmlAttribute(strLine1,"landmark_name=\"[^\"]+");
					landmark_coord_tmp = getXmlAttribute(strLine1,"landmark_coord=\"[^\"]+");
					distance_variable_tmp = getXmlAttribute(strLine1,"distance_variable=\"[^\"]+");
					escalation_id_tmp = getXmlAttribute(strLine1,"escalation_id=\"[^\"]+");
					person_name_tmp = getXmlAttribute(strLine1,"person_name=\"[^\"]+");
					email_tmp = getXmlAttribute(strLine1,"person_email=\"[^\"]+");
					sms_status = getXmlAttribute(strLine1,"sms_status=\"[^\"]+");
					mail_status = getXmlAttribute(strLine1,"mail_status=\"[^\"]+");
					
					if(email_tmp.equalsIgnoreCase(""))
					{
						email_tmp = "-";
					}
						
					/********* STORE IN ARRAY LISTS ********/					
					/*if(alert_name_tmp.equalsIgnoreCase("halt_start"))
					{
						//####### ALERT HALT START #######/
						halt_start_flag = true;
						tmp_string_halt_start += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("halt_stop"))
					{
						//######## ALERT HALT STOP #######/
						halt_stop_flag = true;
						tmp_string_halt_stop += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"$";
					}*/

					if(alert_name_tmp.equalsIgnoreCase("ignition_activated"))
					{
						/*******ALERT IGNITION ACTIVATED********/
						ignition_activated_flag = true;
						tmp_string_ignition_activated += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("ignition_deactivated"))
					{
						/*******ALERT IGNITION DEACTIVATED********/
						ignition_deactivated_flag = true;
						tmp_string_ignition_deactivated += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}
					if(alert_name_tmp.equalsIgnoreCase("sos"))
					{
						/*******ALERT IGNITION DEACTIVATED********/
						sos_flag = true;
						tmp_string_sos += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}
					if(alert_name_tmp.equalsIgnoreCase("over_temperature"))
					{
						/*******ALERT IGNITION DEACTIVATED********/
						over_temperature_flag = true;
						tmp_string_over_temperature += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}					
					if(alert_name_tmp.equalsIgnoreCase("overspeed"))
					{
						/*******ALERT OVERSPEED********/
						overspeed_flag = true;
						tmp_string_overspeed += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("battery_connected"))
					{
						/*******ALERT BATTERY CONNECTED ********/
						battery_connected_flag = true;
						tmp_string_battery_connected += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("battery_disconnected"))
					{
						/*******ALERT BATTERY DISCONNTED********/
						battery_disconnected_flag = true;
						tmp_string_battery_disconnected += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					/*if(alert_name_tmp.equalsIgnoreCase("entered_region"))
					{
						/######## ALERT ENTERED REGION #######/
						tmp_string = person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp;
						alert_variables.alert_entered_region_flag.put(imei, true);
						alert_variables.alert_entered_region.add(tmp_string);
					}

					if(alert_name_tmp.equalsIgnoreCase("exited_region"))
					{
						/##### ALERT EXITED REGION #######/
						tmp_string = person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp;
						alert_variables.alert_exited_region_flag.put(imei,true);
						alert_variables.alert_exited_region.add(tmp_string); 
					}*/
					if(alert_name_tmp.equalsIgnoreCase("door1_open"))
					{
						/*******DOOR1 OPEN********/
						door1_open_flag = true;
						tmp_string_door1_open += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("door1_close"))
					{
						/*******DOOR1 CLOSE********/
						door1_close_flag = true;
						tmp_string_door1_closed += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}
					if(alert_name_tmp.equalsIgnoreCase("door2_open"))
					{
						/*******DOOR2 OPEN********/
						door2_open_flag = true;
						tmp_string_door2_open += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("door2_close"))
					{
						/*******DOOR2 CLOSE********/
						door2_close_flag = true;
						tmp_string_door2_closed += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}
					
					if(alert_name_tmp.equalsIgnoreCase("ac_on"))
					{
						/*******ALERT IGNITION ACTIVATED********/
						ac_on_flag = true;
						tmp_string_ac_on += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}

					if(alert_name_tmp.equalsIgnoreCase("ac_off"))
					{
						/*******ALERT IGNITION DEACTIVATED********/
						ac_off_flag = true;
						tmp_string_ac_off += person_mobile_tmp+"#"+alert_duration_tmp+"#"+alert_id_tmp+"#"+alert_name_tmp+"#"+escalation_id_tmp+"#"+person_name_tmp+"#"+email_tmp+"#"+sms_status+"#"+mail_status+"$";
					}					
					
				} catch(Exception e) {
					//System.out.println("EXCEPTION-2 IN READ ESCALATION FILE:"+e.getMessage());
					}
			} //WHILE CLOSED
		
			fstream1.close();
			in1.close();
			
			//####### PUT VALUES AGAINST IMEI			
			//tmp_string_halt_start = tmp_string_halt_start.substring(0,tmp_string_halt_start.length()-1);
			//tmp_string_halt_stop = tmp_string_halt_stop.substring(0,tmp_string_halt_stop.length()-1);
			
			/*if(halt_start_flag)
			{
				//######## ALERT HALT STAR ########/
				alert_variables.alert_halt_start_flag.put(imei, true);
				alert_variables.alert_halt_start.put(imei,tmp_string_halt_start);
			}

			if(halt_stop_flag)
			{
				//######## ALERT HALT STOP #######/
				alert_variables.alert_halt_stop_flag.put(imei, true);
				alert_variables.alert_halt_stop.put(imei,tmp_string_halt_stop);
			}*/

			if(ignition_activated_flag)
			{
				/*******ALERT IGNITION ACTIVATED********/
				tmp_string_ignition_activated = tmp_string_ignition_activated.substring(0,tmp_string_ignition_activated.length()-1);
				alert_variables.alert_ignition_activated_flag.put(imei, true);
				alert_variables.alert_ignition_activated.put(imei,tmp_string_ignition_activated); 
			}

			if(ignition_deactivated_flag)
			{
				/*******ALERT IGNITION DEACTIVATED********/
				tmp_string_ignition_deactivated = tmp_string_ignition_deactivated.substring(0,tmp_string_ignition_deactivated.length()-1);
				alert_variables.alert_ignition_deactivated_flag.put(imei, true);
				alert_variables.alert_ignition_deactivated.put(imei,tmp_string_ignition_deactivated); 
			}
			if(sos_flag)
			{
				/*******ALERT IGNITION DEACTIVATED********/
				//System.out.println("SOS_TEST");
				tmp_string_sos = tmp_string_sos.substring(0,tmp_string_sos.length()-1);
				alert_variables.alert_sos_flag.put(imei, true);
				alert_variables.alert_sos.put(imei,tmp_string_sos);
			}
			if(over_temperature_flag)
			{
				/*******ALERT IGNITION DEACTIVATED********/
				tmp_string_over_temperature = tmp_string_over_temperature.substring(0,tmp_string_over_temperature.length()-1);
				alert_variables.alert_over_temperature_flag.put(imei, true);
				alert_variables.alert_over_temperature.put(imei,tmp_string);
			}					
			if(overspeed_flag)
			{
				/*******ALERT OVERSPEED********/
				tmp_string_overspeed = tmp_string_overspeed.substring(0,tmp_string_overspeed.length()-1);
				alert_variables.alert_overspeed_flag.put(imei, true);
				alert_variables.alert_overspeed.put(imei,tmp_string_overspeed);  
			}

			if(battery_connected_flag)
			{
				/*******ALERT BATTERY CONNECTED ********/
				tmp_string_battery_connected = tmp_string_battery_connected.substring(0,tmp_string_battery_connected.length()-1);
				alert_variables.alert_battery_connected_flag.put(imei, true);
				alert_variables.alert_battery_connected.put(imei,tmp_string_battery_connected);  
			}

			if(battery_disconnected_flag)
			{
				/*******ALERT BATTERY DISCONNTED********/
				tmp_string_battery_disconnected = tmp_string_battery_disconnected.substring(0,tmp_string_battery_disconnected.length()-1);
				alert_variables.alert_battery_disconnected_flag.put(imei, true);
				alert_variables.alert_battery_disconnected.put(imei,tmp_string_battery_disconnected); 
			}

			if(door1_open_flag)
			{
				/*******ALERT IGNITION ACTIVATED********/
				//System.out.println("DoorOpenString:Before="+tmp_string_door1_open);
				tmp_string_door1_open = tmp_string_door1_open.substring(0,tmp_string_door1_open.length()-1);
				alert_variables.alert_door1_open_flag.put(imei, true);
				alert_variables.alert_door1_open.put(imei,tmp_string_door1_open);
				//System.out.println("DoorOpenString:After="+tmp_string_door1_open);
			}

			if(door1_close_flag)
			{
				/*******ALERT IGNITION DEACTIVATED********/
				tmp_string_door1_closed = tmp_string_door1_closed.substring(0,tmp_string_door1_closed.length()-1);
				alert_variables.alert_door1_closed_flag.put(imei, true);
				alert_variables.alert_door1_closed.put(imei,tmp_string_door1_closed); 
			}
			if(door2_open_flag)
			{
				/*******ALERT IGNITION ACTIVATED********/
				tmp_string_door2_open = tmp_string_door2_open.substring(0,tmp_string_door2_open.length()-1);
				alert_variables.alert_door2_open_flag.put(imei, true);
				alert_variables.alert_door2_open.put(imei,tmp_string_door2_open); 
			}

			if(door2_close_flag)
			{
				/*******ALERT IGNITION DEACTIVATED********/
				tmp_string_door2_closed = tmp_string_door2_closed.substring(0,tmp_string_door2_closed.length()-1);
				alert_variables.alert_door2_closed_flag.put(imei, true);
				alert_variables.alert_door2_closed.put(imei,tmp_string_door2_closed); 
			}
			
			if(ac_on_flag)
			{
				/*******ALERT IGNITION ACTIVATED********/
				tmp_string_ac_on = tmp_string_ac_on.substring(0,tmp_string_ac_on.length()-1);
				alert_variables.alert_ac_on_flag.put(imei, true);
				alert_variables.alert_ac_on.put(imei, tmp_string_ac_on); 
			}

			if(ac_off_flag)
			{
				/*******ALERT IGNITION DEACTIVATED********/
				tmp_string_ac_off = tmp_string_ac_off.substring(0,tmp_string_ac_off.length()-1);
				alert_variables.alert_ac_off_flag.put(imei, true);
				alert_variables.alert_ac_off.put(imei, tmp_string_ac_off); 
			}								
			

		} catch(Exception e) {
			//System.out.println("EXCEPTION-1 IN READ ESCALATION FILE:"+e.getMessage());
			}					
	}
	
	
	//############ ALERT STATUS
	public static String read_database_alert_status(String imei, String alert_string, String alert_type)
	{		
	   stmt = null;
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	//      System.out.println("Selecting data...");
	      stmt = conn.createStatement();
	      String sql;
	      sql = "SELECT alert_string FROM alert_detail_java WHERE imei='"+imei+"' AND alert_type='"+alert_type+"'";
	      //System.out.println("SQL="+sql);
	      ResultSet rs = stmt.executeQuery(sql);

	      //STEP 5: Extract data from result set
	      if(rs.next()){
	         //Retrieve by column name
	         String alert_str = rs.getString("alert_string");
	         return alert_str;
	         //Display values
	         //System.out.print("Alert_str=" + alert_str);
	      }
	      //STEP 6: Clean-up environment
	      rs.close();
	      stmt.close();
	      }catch(SQLException se2){}
	    	  
		return null;
	}
	
	public static void update_database_alert_status(String imei, String alert_string, String alert_type)
	{		
	   stmt = null;
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	//      System.out.println("Updating data...");
	      try {
			stmt = conn.createStatement();
	      } catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
	      }
	      String sql;
	      sql = "UPDATE alert_detail_java SET alert_string='"+alert_string+"' WHERE imei='"+imei+"' AND alert_type='"+alert_type+"'";
	      stmt.executeUpdate(sql);
	   }catch(SQLException e){}
	}		   
	
	public static void insert_database_alert_status(String imei, String alert_string, String alert_type)
	{		
	   stmt = null;
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	    //  System.out.println("Inserting data...");
	      try {
			stmt = conn.createStatement();
	      } catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
	      }
	      String sql;
	      sql = "INSERT INTO alert_detail_java(imei,alert_string,alert_type) values('"+imei+"','"+alert_string+"','"+alert_type+"')";
	      stmt.executeUpdate(sql);
	   }catch(SQLException e){}
	}		
}

