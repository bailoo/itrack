//import java.beans.Statement;
import java.net.URL;
import java.sql.Statement;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.ResultSet;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import org.w3c.dom.NodeList;


public class action_report_customer {

	public static void get_report(int account_id, String date_folder, String date_folder_path, String file_name, String file_path, String file_name_actual, String format, String sequence, alert_variable_plant_customer av_pc, String extension)
	{
		//System.out.println("account_id="+account_id+",date_folder="+date_folder+" ,date_folder_path="+date_folder_path+" ,filename="+file_name+" ,file_path="+file_path+" ,file_name_actual="+file_name_actual+" ,format="+format+" ,extension="+extension);
		String read_excel_filepath = "";
		String tmp_str = "", serverdatetime ="";
		boolean format_valid_master = false;
		//boolean debug = true;
		
		//if(debug) System.out.println("1");
		SimpleDateFormat formatter;
		Date date = new Date();
		formatter = new SimpleDateFormat("yyyy-MM-dd");
		serverdatetime = formatter.format(date);							
		//System.out.println("FORMAT-1::CUSTOMER");		
								
		read_excel_filepath = file_path + "/"+file_name; 
									
		//String file_path_master = "C:\\gps_report/"+account_id+"/master";
		String file_path_master = "/var/www/html/vts/beta/src/php/gps_report/"+account_id+"/master";	//UPLOADED STATION FILE PATH

		File file_folder = new File(file_path_master);
		//System.out.println("file_path:"+file_path);
		File[] listOfFiles = file_folder.listFiles();
		//System.out.println("testA");
		//System.out.println("files="+listOfFiles.length);			
		 
		String filename_master ="", extension_master="", file_format_master="";
		//if(debug) System.out.println("ListOfFiles:"+listOfFiles.length);
		//###### CHECKING MASTER FILE FORMAT AND READING
		for (int z = 0; z < listOfFiles.length; z++)
		{						// CURRENT FILES
			if (listOfFiles[z].isFile())
			{
				filename_master = listOfFiles[z].getName();
				
				String[] tmp = filename_master.split("\\.");		
				//System.out.println("tmp0="+tmp[0]+" ,tmp1="+tmp[1]);
				
				tmp_str = "";
				
				for(int len1=0;len1<tmp.length;len1++)
				{			
					if(len1==tmp.length-1)
					{
						extension_master = tmp[len1].trim();
						//System.out.println("EX="+tmp[len1]);
					}
					else
					{								
						if(tmp.length>2)
						{
							if(len1==0)
							{
								tmp_str = tmp_str+""+tmp[len1];
							}
							else
							{
								tmp_str = tmp_str+"."+tmp[len1];
							}
						}
						else
						{								
							tmp_str = tmp_str+""+tmp[len1];
						}
						//System.out.println(tmp[len1]);
					}
				}						
																	
				//System.out.println("filename_master="+filename_master+ ",tmp_str="+tmp_str);					
				//read_excel_filepath = file_path + "/"+file_name; 
										
				String[] name_location = tmp_str.split("#");	//TMP[0]= FILENAME WITHOUT EXTENSION
				file_format_master = name_location[2];
				//System.out.println("file_format_master="+file_format_master);
				
				if(file_format_master.equalsIgnoreCase("1"))
				{
					format_valid_master = true;
					//#######READ MASTER FILE
					String read_master_path = file_path_master+"/"+filename_master;
					//System.out.println("MASTER_FILE_PATH:CUSTOMER="+read_master_path);
					//System.out.println("extension_master="+extension_master);
					
					if(extension_master.equals("csv"))
					{  
						///#### INCLUDE READ STATION CSV FILE
						try{
							read_plant_customer_master.readCSVFile_CUSTOMER_MASTER(read_master_path, av_pc);
						} catch(Exception rr1) {System.out.println("Err:CustomerMaster1="+rr1.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xls"))
					{
						 ///#### INCLUDE READ STATION XLS FILE		
						try{
							read_plant_customer_master.readXLSFile_CUSTOMER_MASTER(read_master_path, av_pc);
						} catch(Exception rr2) {System.out.println("Err:CustomerMaster2="+rr2.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xlsx"))
					{
						///#### INCLUDE READ STATION XLSX FILE *******					
						try{
							read_plant_customer_master.readXLSXFile_CUSTOMER_MASTER(read_master_path, av_pc);
						} catch(Exception rr3) {System.out.println("Err:CustomerMaster3="+rr3.getMessage());}
						///////////////////////////////////////////////////////////////
					}
					//System.out.println("After read_plant_customer_master");
				}				
			}
		}
							
		if(format_valid_master)
		{
			//if(debug) System.out.println("3");
			String vname_tmp ="";				
			//System.out.println("vname="+av_pc.vname.size());
			
			for(int i=0;i<av_pc.vname.size();i++)
			{
				if(i==0)
				{
				   vname_tmp = vname_tmp+"'"+(String)av_pc.vname.get(i)+"'";
				}
				else
				{
				  vname_tmp = vname_tmp+",'"+(String)av_pc.vname.get(i)+"'";
				}
			}						
			
			//System.out.println("vname_tmp="+vname_tmp);
			Connection con = null;
			Statement stmt1 = null,stmt2 = null;
			ResultSet res1 = null, res2 = null;
			
			//utility_classes util = new utility_classes();
			con = utility_classes.get_connection();
			
			String query = "SELECT DISTINCT device_imei_no,vehicle_id FROM vehicle_assignment WHERE "+
			"vehicle_id IN(SELECT vehicle_id FROM vehicle WHERE vehicle_name IN("+vname_tmp+") AND status=1)"+
			" AND status=1"; 
			
			//System.out.println("Q="+query);
			
			try{
				stmt1 = con.createStatement();
				res1 = stmt1.executeQuery(query);	
				
				String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="";
				while(res1.next())
				{
					imei_db = res1.getString("device_imei_no");
					av_pc.vserial.add(imei_db);
	
					tmp_vid = res1.getString("vehicle_id");
					//av.vid.add(tmp_vid);
					
					query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id="+tmp_vid+"";	
	
					stmt2 = con.createStatement();
					res2 = stmt2.executeQuery(query2);
					
					if(res2.next())
					{
						tmp_vname1 = res2.getString("vehicle_name");
						av_pc.vname1.add(tmp_vname1);
					}				
				}
			}catch (Exception e) { System.out.println("EXCEPTION IN GETTING ASSIGNED IMEI::CUSTOMER:"+e.getMessage()); }		
					
			
			//CODE FOR MULTIPLE DATES
			//if(debug) System.out.println("4");
			//System.out.println("av_pc.vname1.size()="+av_pc.vname1.size());
			
			for(int i=0;i<av_pc.vname1.size();i++)		//SIZE OF VNAME IN DATABASE 
			{			
				String in_min_date = "3000-00-00 00:00:00";
				String in_max_date = "0000-00-00 00:00:00";
				
				String out_min_date = "3000-00-00 00:00:00";		
				String out_max_date = "0000-00-00 00:00:00";
			
				//System.out.println("av.vname.size()="+av.vname1.size());
				
				String in_min_date_final_tmp="",in_max_date_final_tmp="",out_min_date_final_tmp = "",out_max_date_final_tmp ="";
				
				for(int j=0;j<av_pc.vname.size();j++)	//SIZE OF VNAME IN CSV
				{				
					//System.out.println("av.vname1="+(String)av_pc.vname1.get(i)+" ,av.vname="+(String)av_pc.vname.get(j));
					
					if( ((String)av_pc.vname1.get(i).trim()).equals(((String)av_pc.vname.get(j).trim())) )
					{
						//System.out.println("VEHICLE MATCHED");
						//if(debug) System.out.println("5");
						//DATE1
						String[] datetmp_input1 = ((String)av_pc.date1_csv.get(j)).split("-");
						String in_date_csv = datetmp_input1[2]+"-"+datetmp_input1[1]+"-"+datetmp_input1[0]+" "+((String)av_pc.time1_csv.get(j));
						
						//DATE2
						String[] datetmp_input2 = ((String)av_pc.date2_csv.get(j)).split("-");
						String out_date_csv = datetmp_input2[2]+"-"+datetmp_input2[1]+"-"+datetmp_input2[0]+" "+((String)av_pc.time2_csv.get(j));			
						
						//System.out.println("in_date_csv="+in_date_csv+" ,out_date_csv="+out_date_csv+" ,datetmp_input1[0]="+datetmp_input1[0]+" ,datetmp_input1[1]="+datetmp_input1[1]);
						
						long in_date_csv_secs = utility_classes.get_seconds(in_date_csv,2);
						long in_min_date_secs = utility_classes.get_seconds(in_min_date,2);
						long in_max_date_secs = utility_classes.get_seconds(in_max_date,2);
						long out_date_csv_secs = utility_classes.get_seconds(out_date_csv,2);
						long out_min_date_secs = utility_classes.get_seconds(out_min_date,2);
						long out_max_date_secs = utility_classes.get_seconds(out_max_date,2);
																
						//System.out.println("in_date_csv_secs="+in_date_csv_secs+" , out_date_csv_secs="+out_date_csv_secs+" ,in_min_date_secs="+in_min_date_secs);											
						if(in_date_csv_secs < in_min_date_secs)				//IN DATE
						{
							//System.out.println("IN_MIN_DATE ADD");			
							//in_min_date_final_tmp = in_date_csv;
							//av.in_min_date_final.add(in_date_csv);
							in_min_date = in_date_csv;
						}
						if(in_date_csv_secs > in_max_date_secs)
						{
							//System.out.println("IN_MAX_DATE ADD");	
							//in_max_date_final_tmp = in_date_csv;
							in_max_date = in_date_csv;
						}
										
						//echo "<br>OUT::in_date_csv=".$in_date_csv." ,out_min_date=".$out_min_date." out_max_date=".$out_max_date;
						if(out_date_csv_secs < out_min_date_secs)				//OUT DATE
						{										
							//System.out.println("OUT_MIN_DATE ADD");	
							//out_min_date_final_tmp = out_date_csv;
							out_min_date = out_date_csv; 
						}
						if(out_date_csv_secs > out_max_date_secs)
						{
							//System.out.println("OUT_MAX_DATE ADD="+out_date_csv);	
							//out_max_date_final_tmp = out_date_csv;
							out_max_date = out_date_csv;
						}						
																	
					} // IF VNAME CLOSED
				} // INNER FOR CLOSED
				
				//System.out.println("in_min:"+in_min_date+" in_max:"+in_max_date+" out_min:"+out_min_date+" out_max:"+out_max_date);
				
				av_pc.in_min_date_final.add(in_min_date);
				av_pc.in_max_date_final.add(in_max_date);
				av_pc.out_min_date_final.add(out_min_date);
				av_pc.out_max_date_final.add(out_max_date);														
				
			} // OUTER FOR CLOSED
			/////////////////////////	
			
			//String datetmp_start_xls = (String)av.date1_csv.get(0);
			//String[] datetmp_input = datetmp_start_xls.split("-");
	
			//String date1 = datetmp_input[2]+"-"+datetmp_input[1]+"-"+datetmp_input[0]+" 00:00:00";
			//String date2 = datetmp_input[2]+"-"+datetmp_input[1]+"-"+datetmp_input[0]+" 23:59:59";
							
			int vsize = av_pc.vserial.size();
			//System.out.println("vsize:"+vsize);
			
			if(vsize>0)
			{		  
				//if(debug) System.out.println("6");
				av_pc.stringData = av_pc.stringData + ",Google Location, ServerTime, DeviceTime";
				
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,SCHEDULE_TIME,DELAY,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)";
				av_pc.stringData = av_pc.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s),IN Distance from Station (km),OUT Distance from Station (km)\n";
								
				//System.out.println("AV.STRINGDATA="+av.stringData);				
				write_station_report(account_id, av_pc);
								
				//###### WRITE STATION FILES
				write_download_station_files(account_id, file_name_actual, date_folder, av_pc);
								
			} // IF VSIZE CLOSED
		}	// IF FORMAT VALID						
				
	}  // MAIN CLOSED
	

	
	//##### WRITE STATION REPORT XML
	public static void write_station_report(int account_id, alert_variable_plant_customer av_pc)
	{
		int maxPoints = 1000;
		boolean file_exist = false;			
		//System.out.println("IN WRITE_STATION_REPORT::CUSTOMER");
		
		Connection con = null;
		Statement stmt1 = null;
		ResultSet res1 = null;
		String geo_coord = "";
		int i=0;
		con = utility_classes.get_connection();
		
		//System.out.println("CUST_SIZE::CUSTOMER="+av_pc.customer_no.size());
		
		for(i=0;i<av_pc.customer_no.size();i++)
		{
			String query_geo = "SELECT DISTINCT station_coord,station_name, CAST(customer_no AS UNSIGNED INTEGER) as customer_no_int,distance_variable,google_location FROM station WHERE user_account_id ="+account_id+" AND CAST(customer_no AS UNSIGNED INTEGER)="+(Integer)av_pc.customer_no.get(i)+" AND status=1";				
			//System.out.println(query_geo);
			
			try{
				stmt1 = null;
				res1 = null;				
				stmt1 = con.createStatement();
				res1 = stmt1.executeQuery(query_geo);					
				
				String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="", location="";
				if(res1.next())
				{
					//System.out.println("IF STATION-1");
					geo_coord = res1.getString("station_coord");
					geo_coord = geo_coord.replaceAll(", ",",");					
					av_pc.geo_coord_station.add(geo_coord);
					//System.out.println("IF STATION-2");
					
					//String[] coord_tmp = geo_coord.split(",");					
					//location = get_url_location(coord_tmp[0],coord_tmp[1]);
					location = "-";					
					location = res1.getString("google_location");
					//System.out.println("IF STATION-2A:"+location+" ,geo_coord="+geo_coord);
					if((location!=null))
					{
						//System.out.println("IF");
						location = location.replaceAll(",",":");	
					}
					//if( (!location.equals("-")) || (!location.equals("")) || (location!=null) )
					//{
						//System.out.println("IF");
						//location = location.replaceAll(",",":");					
					//}					
					else if(location == null)
					{
						//System.out.println("ELSE IF");
						location ="-";
					}
					else
					{
						//System.out.println("ELSE");
					}
					
					//System.out.println("IF STATION-3");
					av_pc.google_location.add(location);
										
					//System.out.println("location="+location);
					String tmp_station_name = res1.getString("station_name");					
			      	tmp_station_name = tmp_station_name.replaceAll("/", "by");
			      	tmp_station_name = tmp_station_name.replaceAll("\\\\", "by");
			      	tmp_station_name = tmp_station_name.replaceAll("&", "and"); 
			      	
			      	//System.out.println("IF STATION-4");
			      	av_pc.geo_station.add(tmp_station_name);
					int customer_no_int = Integer.parseInt(res1.getString("customer_no_int"));
					av_pc.customer_no_db.add(customer_no_int);
					
					//System.out.println("IF STATION-5");
					float distance_variable_tmp = 0.0f;
					distance_variable_tmp = res1.getFloat("distance_variable");
					//distance_variable_tmp = 0.1f;	//ON REQUREMENT
					av_pc.distance_variable.add(distance_variable_tmp);
					av_pc.entered_flag.add(0);
					av_pc.datetime_counter.add(0);				
					//System.out.println("IF STATION-6");
				}
				else
				{
					//System.out.println("ELSE STATION");
					geo_coord = "";
					av_pc.geo_coord_station.add("");
					av_pc.geo_station.add("");
					av_pc.google_location.add("");
					av_pc.customer_no_db.add(0);
					av_pc.distance_variable.add(0.0f);
					av_pc.entered_flag.add(0);
					av_pc.datetime_counter.add(0);      
				}
			}catch (Exception e2) { 
			
					//System.out.println("EXCEPTION STATION");
					geo_coord = "";
					av_pc.geo_coord_station.add("");
					av_pc.geo_station.add("");
					av_pc.google_location.add("");
					av_pc.customer_no_db.add(0);
					av_pc.distance_variable.add(0.0f);
					av_pc.entered_flag.add(0);
					av_pc.datetime_counter.add(0);  
					
					System.out.println("EXCEPTION IN SELECT DISTINCT station_coord::CUSTOMER:"+e2.getMessage()); 
			}		
		}
	  
		//System.out.println("vserial_size="+av.vserial.size());
		
		for(i=0;i<av_pc.vserial.size();i++)
		{  	
			//System.out.println("Test1:"+i);
			String startdate = (String)av_pc.in_min_date_final.get(i);
			String enddate = (String)av_pc.out_max_date_final.get(i);		
			
			//System.out.println("startdate="+startdate+" ,enddate="+enddate);
			//###### GET XML DATA
			get_station_xml_data((String)av_pc.vserial.get(i), (String)av_pc.vname1.get(i), startdate, enddate, av_pc);			
			//System.out.println("AFTER get_station_xml_data");
			//#################
		}
		//System.out.println("Test2:"+i);
		//System.out.println("STATION_SIZE::CUSTOMER="+av_pc.geo_coord_station.size());
		
		String[] startdate1 = new String[2];
		String[] enddate1 = new String[2];

		String message_customer_final = "", sts="",devicetime="";
		boolean vehicle_exist = false;
		
		try{
			for(i=0;i<av_pc.geo_coord_station.size();i++)
			{
				message_customer_final = "";
				vehicle_exist = false;
				boolean valid_record = false;
				
				//GET SERVERT TIME AND DEVICE TIME
				sts = av_pc.server_time_2d[i][0];
				devicetime = av_pc.device_time_2d[i][0];
				//
				
				for(int x=0;x<av_pc.vserial.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
				{  											
					//System.out.println("V1="+ (String)av_pc.vname1.get(x)+" ,V2="+(String)av_pc.vname.get(i));
					if(( (String)av_pc.vname1.get(x).trim()).equalsIgnoreCase((String)av_pc.vname.get(i).trim()))
					{
						//System.out.println("VehicleMatchFound");
						vehicle_exist = true;
						String msg_customer_tmp = (String)av_pc.message_customer.get(x);
						
						///////////
						String tmp_d1 = (String)av_pc.in_min_date_final.get(x);
						String tmp_d2 = (String)av_pc.out_max_date_final.get(x);						
							
						startdate1 = tmp_d1.split(" ");			
						enddate1 = tmp_d2.split(" ");
						///////////
						
						if(!msg_customer_tmp.equals(""))
						{
							message_customer_final = msg_customer_tmp;
						}
						break;
					}
				}
				
				if(!vehicle_exist)
				{
					message_customer_final = "Vehicle Does Not Exist";
				}				
				
				av_pc.stringData = av_pc.stringData + (String)av_pc.date1_csv.get(i) + "," + (String) av_pc.time1_csv.get(i) +"," + (String) av_pc.date2_csv.get(i) +"," + (String) av_pc.time2_csv.get(i) +"," + (String) av_pc.doctype.get(i) +"," + (String) av_pc.plant.get(i) +"," + (String) av_pc.route.get(i) +"," + (String) av_pc.vname.get(i) + "," + (String) av_pc.vendor_name.get(i) + "," + (Integer) av_pc.customer_no.get(i);					
				av_pc.stringData = av_pc.stringData + "," + (String) av_pc.google_location.get(i)+","+sts+","+devicetime;
				
				/*if((av.datetime_counter.get(i)) > 0)
				{
					System.out.println("av.datetime_counter.get(i)="+(av.datetime_counter.get(i))+" ,i="+i);
				}*/
				
				//########## MATCH SHIFT WITH MASTER #################
				String shift = "", schedule_in_time="";
				int customer_no=0, customer_no_db=0;
				
				try{					
					shift = (String)av_pc.doctype.get(i);
					customer_no = (Integer)av_pc.customer_no.get(i);
					customer_no_db = (Integer)av_pc.customer_no_db.get(i);
				} catch(Exception e2) {System.out.println("EXCEPTION IN READING -MASTER DATA FILTER::CUSTOMER:"+e2.getMessage());};									
				
				boolean customer_found_in_route = false;
				try{
					for(int k=0;k<av_pc.mc_point.size();k++)
					{						
						String mc_shift = (String)(av_pc.mc_shift.get(k));	
						int mc_customer = (int)(av_pc.mc_point.get(k)); //IS ACTUALLY CUSTOMER WITH TYPE=1
						
						String mc_in_time = (String)av_pc.mc_timing.get(k);
						
						//System.out.println("shift="+shift+" ,mc_shift="+mc_shift+" ,customer_no="+customer_no+", mc_customer="+mc_customer);
						
						if( (customer_no == mc_customer) && (customer_no_db>0) )
						{
							//System.out.println("Customer Matched in first");
							customer_found_in_route = true;
						}						
						if( (shift.equals(mc_shift)) && (customer_no == mc_customer) && (customer_no_db>0) )
						{							
							//System.out.println("Customer Matched in second");												
							customer_found_in_route = true;
							//System.out.println("Matched");
							schedule_in_time = mc_in_time;							
							break;	//BREAK THE LOOP								
						}
					}
				} catch(Exception e2) {System.out.println("EXCEPTION IN LOOP INNER2 -MASTER DATA FILTER::CUSTOMER:"+e2.getMessage());};						
							
				//System.out.println("MessageCustomerFinal="+message_customer_final+" ,CustDb="+customer_no_db+" ,cust_route="+customer_found_in_route);

				if(message_customer_final.equalsIgnoreCase(""))
				{
					if(customer_no_db ==0)
					{
						message_customer_final = "Customer Does Not Exist In DataBase";
					}
					else if(!customer_found_in_route)
					{
						message_customer_final = "Customer Does Not Exist In this Route";
					}
				}
				//####################################################
				
				//System.out.println("datetime_counter="+av_pc.datetime_counter.get(i)+" ,post_msg="+message_customer_final);
				
				if(message_customer_final.equals(""))
				{
				try{
					//System.out.println("\nDatetimeCounter="+av_pc.datetime_counter.get(i));

					for(int j=0;j<(av_pc.datetime_counter.get(i));j++)
					{		
						valid_record = true;
											
						String[] in_date_arr = av_pc.intime_halt_2d[i][j].split(" ");
						String[] out_date_arr = av_pc.outime_halt_2d[i][j].split(" ");
						
						//FORMAT DATE TO DD-MM-YY
						String[] in_date = in_date_arr[0].split("-");              
						String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
						
						String[] out_date = out_date_arr[0].split("-");              
						String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];
						//echo "<br>in_date=".$in_date_tmp." ,out_date=".$out_date_tmp;
						/////////////////////////        
						float in_dist_tmp = utility_classes.Round(av_pc.in_distance_2d[i][j],2);
						float out_dist_tmp = utility_classes.Round(av_pc.out_distance_2d[i][j],2);
						String time_delay = "",diff_out_time="";						
						//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][j]="+av.time_dur_halt_2d[i][j]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
						
						if(j == 0)
						{							
							String in_time_str_report = av_pc.intime_halt_2d[i][j]; 		//REPORT TIME STR
							String[] in_date1 = ((String)av_pc.input_date1.get(i)).split(" ");							
							String in_time_str_master = in_date1[0]+" "+schedule_in_time;   //SCHEDULE TIME STR 					
							
							//########## CALCULATE TIME DELAY FROM SCHEDULED TIME
							//System.out.println("in_time_str_report="+in_time_str_report+" ,in_time_str_master="+in_time_str_master);
							//long time1 = (long)(Math.abs((utility_classes.get_seconds(in_time_str_report, 2)) - (utility_classes.get_seconds(in_time_str_master, 2))));														

							//System.out.println("IN_TIME_EXCEL="+in_time_str_report+" ,IN_TIME_REPORT="+in_time_str_master);
							
							//################ CHECK DIFFERENT INPUT DATA -REINITIALIZE DATE TIME MATCH WITH SCHEDULE #######################
							String tmp_date_input = in_date_arr[0];						
							String tmp_time_input = startdate1[0]+ " " +startdate1[1];
							String tmp_time_schedule = startdate1[0]+ " " +schedule_in_time;
							
							long tmp_input = utility_classes.get_seconds((String)av_pc.input_date1.get(i), 2);  //REPORT TIME
							long tmp_schedule = utility_classes.get_seconds(tmp_time_schedule, 2);  //SCHEDULE TIME
							
							//System.out.println("Final: startdate1="+startdate1[0]+" ,enddate1="+enddate1[0]);
							
							String final_date = "";
							
							//System.out.println("tmp_date_input="+tmp_date_input);
							//System.out.println("tmp_time_input="+tmp_time_input);
							//System.out.println("tmp_time_schedule="+tmp_time_schedule);							
							//System.out.println("tmp_input="+tmp_input+" ,tmp_schedule="+tmp_schedule);
							
							if(tmp_input <= tmp_schedule)
							{
								final_date = startdate1[0];
							}								
							else
							{
								final_date = enddate1[0];
							}
							
							//System.out.println("FINAL DATE="+final_date+" ,schedule_in_time="+schedule_in_time);
							//String[] in_date1 = ((String)av_pc.input_date1.get(i)).split(" ");							
							String in_time_str_excel = final_date+" "+schedule_in_time;														
													
							String in_time_str = av_pc.intime_halt_2d[i][j];							
							String out_time_str = av_pc.outime_halt_2d[i][j];						
							
							//#################################################################################################################
																					
							//long time1 = (long)(Math.abs((utility_classes.get_seconds(in_time_str_excel, 2)) - (utility_classes.get_seconds(in_time_str, 2))));
							long time1 = (long)(((utility_classes.get_seconds(in_time_str, 2)) - (utility_classes.get_seconds(in_time_str_excel, 2))));
							//System.out.println("in_time_str="+in_time_str+" in_time_str_excel="+in_time_str_excel+" time1="+time1);
							
							if(time1>0)
							{
								time_delay = utility_classes.get_hms(time1);
							}
							else
							{
								//time_delay = "00:00:00";
								time1 = Math.abs(time1);								
								time_delay = utility_classes.get_hms(time1);
								time_delay = "-"+time_delay;
								//System.out.println("ELSE TIME1="+time1+" ,time_delay="+time_delay);								
							}

							//System.out.println("TimeDelay="+time_delay);
							
							av_pc.stringData = av_pc.stringData+","+in_date_tmp+","+in_date_arr[1]+","+schedule_in_time+","+time_delay+","+out_date_tmp+","+out_date_arr[1]+","+av_pc.time_dur_halt_2d[i][j]+","+in_dist_tmp+","+out_dist_tmp;
						}
						else
						{
							//############# FINAL STRING
							av_pc.stringData = av_pc.stringData+","+in_date_tmp+","+in_date_arr[1]+","+out_date_tmp+","+out_date_arr[1]+","+av_pc.time_dur_halt_2d[i][j]+","+in_dist_tmp+","+out_dist_tmp;					
						}						
					}  // FOR DATETIME COUNTER CLOSED
				} catch(Exception ef) { System.out.println("EXCEPTION IN FINAL BLOCK(STATION)::CUSTOMER:MSG="+ef.getMessage());}   
				
				} //IF MSG IS BLANK

				//if((av_pc.datetime_counter.get(i))==0)
				if(!valid_record)
				{
					try{
						/*if(av_pc.intime_halt_2d[i].length>0)
						{
							if( !(av_pc.intime_halt_2d[i][0].equals("")) )
							{
								String[] in_date_arr = av_pc.intime_halt_2d[i][0].split(" ");
								//String[] out_date_arr = av.outime_halt_2d[i][0].split(" ");
								
								//FORMAT DATE TO DD-MM-YY
								String[] in_date = in_date_arr[0].split("-");              
								String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
								
								//String[] out_date = out_date_arr[0].split("-");              
								//String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];							
								/////////////////////////        
								
								float in_dist_tmp = utility_classes.Round(av_pc.in_distance_2d[i][0],2);
								//float out_dist_tmp = utility_classes.Round(av.out_distance_2d[i][0],2);
								
								String blank = "";
								//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][0]="+av.time_dur_halt_2d[i][0]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
		
								av_pc.stringData = av_pc.stringData+","+in_date_tmp+","+in_date_arr[1]+","+blank+","+blank +","+blank+","+in_dist_tmp+","+blank;
							}	
						}					
						else
						{*/
							if(message_customer_final.equals(""))
							{
								message_customer_final = "Vehicle Too Far From Plant";
							}
							
							av_pc.stringData = av_pc.stringData+","+message_customer_final+", ,";
							
						//}
					}catch(Exception e0) { System.out.println("Exception in datetime counter zero::CUSTOMER"); }
				}

				av_pc.stringData = av_pc.stringData+"\n";
			}   // FOR GEO COORD SIZE CLOSED	
		}catch(Exception ef2) { System.out.println("EXCEPTION IN GEO BLOCK(STATION)::CUSTOMER:MSG="+ef2.getMessage());}
		
		//System.out.println("av.stringData ="+av.stringData);
	}
	

	public static void get_station_xml_data(String vehicle_serial, String vname1, String startdate, String enddate, alert_variable_plant_customer av_pc)
	{
		int fix_tmp = 1;
		String xml_date_latest="1900-00-00 00:00:00";
		double CurrentLat = 0.0;
		double CurrentLong = 0.0;
		double LastLat = 0.0;
		double LastLong = 0.0;
		int firstData = 0;
		float distance =0.0f;
		String linetowrite="";
		int firstdata_flag =0;		
		SimpleDateFormat formatter1, formatter2;
	
		String[] date_1 = startdate.split(" ");
		String[] date_2 = enddate.split(" ");

		String datefrom = date_1[0];
		String dateto = date_2[0];
		String timefrom = date_1[1];
		String timeto = date_2[1];

		ArrayList<String> userdates = utility_classes.get_All_Dates(datefrom, dateto);

		//date_default_timezone_set("Asia/Calcutta");
		//current_datetime = date("Y-m-d H:i:s");
		//current_date = date("Y-m-d");
		
		Date date = new Date();
		formatter1 = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		String current_datetime = formatter1.format(date);
		
		formatter2 = new SimpleDateFormat("yyyy-MM-dd");
		String current_date = formatter2.format(date);
		
		//System.out.println("IN GET_XML_DATA");
		
		//print "<br>CurrentDate=".$current_date;
		int date_size = userdates.size();
		System.out.println("DateSize="+date_size);
		
		int j = 0;
		int total_dist = 0; 									  
		int outflag=0, CurrentFile=0;          	
       
		//String abspath = "C:\\itrack_vts";
		String abspath = "/var/www/html/itrack_vts";
		String abspath_current = "/mnt/volume3";
		String abspath_sorted = "/mnt/volume3/current_data/sorted_xml_data";
		String xml_file ="", xml_original_tmp="", xml_unsorted_file="", xml_unsorted_file_path="";
		String filename_tmp="", xml_unsorted_folder="", xml_original_folder="", folderDate="";
		String enter_time = "", leave_time ="", enter_time_tmp="", leave_time_tmp="", duration ="";
		String fix="", vehicleserial="", lat="", lng="", xml_date="", sts="", error_future_date ="2030-01-01 00:00:00", xml_datetime="", vserial="", geo_lat="", geo_lng="";
		
		long xml_date_sec=0, startdate_sec=0, enddate_sec=0, input_date1_sec=0, input_date2_sec=0, time =0;
		int halt_complete = 0;
		boolean status_geo = false;
		float in_dist = 0.0f, out_dist= 0.0f;
		
		boolean no_gps=true, no_data=true;
		
		//####### NORMAL HALT ################
		int primary_halt_firstdata_flag=0,primary_halt_flag=0,total_lines = 0;
		float primary_halt_distance	=0.f;
		String primary_halt_lat_ref = "", primary_halt_lng_ref="", primary_halt_lat_cr="",primary_halt_lng_cr="";
		long primary_halt_xml_data_sec_ref=0, primary_halt_xml_data_sec_cr=0, halt_dur=0, f=0;
		//####################################
		long future_date_sec = 0;
		future_date_sec = utility_classes.get_seconds(error_future_date, 2);
		boolean halt_occured = false;
		int last_customer_no =0;
		
		for(int i=0;i<=(date_size-1);i++)
		{  
			f=0;			
			String xml_current = abspath_current+"/current_data/xml_data/"+((String)userdates.get(i))+"/"+vehicle_serial+".xml";	
					
			File file1 = new File(xml_current);
			boolean exist1 = file1.exists();

			if (exist1)      
			{    		
				xml_file = xml_current;
				CurrentFile = 1;
			}		
			else
			{
				xml_file = abspath_sorted+"/"+((String)userdates.get(i))+ "/" + vehicle_serial+ ".xml";
				CurrentFile = 0;
			}
		
			File file2 = new File(xml_file);
			boolean exist2 = file2.exists();
			
			System.out.println("Exist2="+exist2);
			if (exist2) 
			{			
				no_data = false;
				
				long t = new Date().getTime();
				//filename_tmp = "tmp_"+vehicle_serial+"_"+t+"_"+i+"_unsorted.xml"
				filename_tmp = "tmp_"+vehicle_serial+"_"+t+"_"+i+".xml";
				xml_original_tmp = abspath+"/java_xml_tmp/original_xml/"+filename_tmp;
									      
				xml_unsorted_folder = abspath+"/java_xml_tmp/unsorted_xml/";
				xml_unsorted_file_path = xml_unsorted_folder + filename_tmp;
				
				if(CurrentFile == 0)
				{
					utility_classes.copyfile(xml_file, xml_original_tmp);
				}
				else
				{
					xml_original_folder = abspath+"/java_xml_tmp/original_xml";
					folderDate = ((String)userdates.get(i));
					
					utility_classes.copyfile(xml_file,xml_unsorted_file_path);        // MAKE UNSORTED TMP FILE					
					sort_xml.sortfile(xml_unsorted_folder, filename_tmp, xml_original_folder, folderDate, ((String)userdates.get(i)));					
					sort_xml.deleteFile(xml_unsorted_file_path);   // DELETE UNSORTED TMP FILE
				}     

				//############ READ XML DATA				
				int total_t1=0;
				NodeList listOf_t1 = null;														
				
				try
				{					
					//SET MASTER VARIABLE
					common_xml_element cx = new common_xml_element();
					cx.set_master_variable(((String)userdates.get(i)),cx);
					
					String final_value ="";
					try{
						// Open the file that is the first 
						// command line parameter
						
						File file3 = new File(xml_original_tmp);
						boolean exist3 = file3.exists();
						/*if(exist3)
						{
							System.out.println("XML ORIGINAL FILE EXISTS");
						}*/
						
						//################ NORMAL HALT ###################
						BufferedReader reader = new BufferedReader(new FileReader(xml_original_tmp));
						
						while (reader.readLine() != null) total_lines++;		//GET TOTAL LINES
						reader.close();		
						//################################################	

						FileInputStream fstream = new FileInputStream(xml_original_tmp);
						// Get the object of DataInputStream
						DataInputStream in = new DataInputStream(fstream);
						BufferedReader br = new BufferedReader(new InputStreamReader(in));
						String strLine;
						//Read File Line By Line
						//System.out.println("Read File Started ..");
						
						String date_ref = "", date_cr="";
						
						while ((strLine = br.readLine()) != null) {
							
							int len = strLine.length();
							
							//System.out.println("STR_LEN="+len);
							
							if(len > 50)
							{
								try{
									fix = sort_xml.getXmlAttribute(strLine,""+cx.vc+"=\"[^\" ]+");
									xml_date  = sort_xml.getXmlAttribute(strLine,""+cx.vh+"=\"[^\"]+");
									sts  = sort_xml.getXmlAttribute(strLine,""+cx.vg+"=\"[^\"]+");
									
									//System.out.println("XML_DATE="+xml_date);																		
									try{
										if(xml_date!=null)
										{				    					
											xml_date_sec = utility_classes.get_seconds(xml_date, 2);
											startdate_sec = utility_classes.get_seconds(startdate, 2);
											enddate_sec = utility_classes.get_seconds(enddate, 2);
											
											//UPDATE LAST POSITION DATETIME AND SERVERTIME  
											for(int g=0; g<av_pc.geo_coord_station.size();g++)
											{												
												if(!vname1.equals( (String)av_pc.vname.get(g)))
												{
													continue;
												}																																
												
												if(xml_date_sec > future_date_sec)
												{
													continue;
												}
												av_pc.server_time_2d[g][0] = sts;
												av_pc.device_time_2d[g][0] = xml_date;
											}
											
											
											try{
												if( (xml_date_sec >= startdate_sec && xml_date_sec <= enddate_sec) && (!xml_date.equals("-")) && (fix.equals("1")) )
												{							           	              																													
													lat = sort_xml.getXmlAttribute(strLine,""+cx.vd+"=\"[^\" ]+");
													lng  = sort_xml.getXmlAttribute(strLine,""+cx.ve+"=\"[^\" ]+");											
													vserial = vehicle_serial; 
													
													lat = lat.substring(0, lat.length() - 1);
													lng = lng.substring(0, lng.length() - 1);
																										
													if( (!lat.equals("")) && (!lng.equals("")) )
													{
														no_gps = false;
													}													
													//System.out.println("VSERIAL="+vserial+" ,LAT="+lat+" ,LNG="+lng);
													//System.out.println("GEO_SIZE="+av.geo_coord_station.size());
													//###### LOGIC PART STARTED

													if(primary_halt_firstdata_flag==0)
													{							
														primary_halt_flag = 0;
														primary_halt_firstdata_flag = 1;

														primary_halt_lat_ref = lat;		
														primary_halt_lng_ref = lng;							                	
														primary_halt_xml_data_sec_ref = xml_date_sec;
														date_ref = xml_date;
													}        
													else
													{														
														primary_halt_lat_cr = lat;		
														primary_halt_lng_cr = lng;							                	
														primary_halt_xml_data_sec_cr = xml_date_sec;
														date_cr = xml_date;

														primary_halt_distance = utility_classes.calculateDistance(Float.parseFloat(primary_halt_lat_ref),Float.parseFloat(primary_halt_lat_cr),Float.parseFloat(primary_halt_lng_ref),Float.parseFloat(primary_halt_lng_cr));
																
														//if((primary_halt_distance > 0.150) || (f == total_lines-10) )														
														if(primary_halt_distance>0.1)
														{
															primary_halt_lat_ref = primary_halt_lat_cr;
															primary_halt_lng_ref = primary_halt_lng_cr;
															primary_halt_xml_data_sec_ref = primary_halt_xml_data_sec_cr; 
															halt_complete=0;
														}																												
														
														if(((primary_halt_xml_data_sec_cr - primary_halt_xml_data_sec_ref)>60) && (halt_complete==0))   // IF VEHICLE STOPS FOR 2 MINS 
														{            			
															//System.out.println("DateREf:"+date_ref+" ,DateCr:"+date_cr);
															//System.out.println("Vehicle STOPEED more than 1 min");
															primary_halt_flag = 1;
														}
														
														if((primary_halt_flag == 1) && (halt_occured == false))
														{
															//System.out.println("HALT FLAG1");
															
															halt_complete = 1;
															try
															{
																//System.out.println("Halt Duration above 60 sec");																		
																if((av_pc.geo_coord_station.size())>0)
																{                
																	//System.out.println("IN GEO_COORD");              
															
																	//System.out.println("STATION SIZE="+av.geo_coord_station.size());
																	try
																	{
																		for(int g=0; g<av_pc.geo_coord_station.size();g++)
																		{																						
																			if(!vname1.equals( (String)av_pc.vname.get(g)))
																			{
																				continue;
																			}
																						
																			input_date1_sec = utility_classes.get_seconds(((String)av_pc.input_date1.get(g)), 2);
																			input_date2_sec = utility_classes.get_seconds(((String)av_pc.input_date2.get(g)), 2);
																						
																			try
																			{
																				if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
																				{
																					//System.out.println("DATE MATCHED: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
																					status_geo = false;
																								
																					try
																					{
																						if(!((String) av_pc.geo_coord_station.get(g)).equals(""))
																						{
																							String[] geo_data = av_pc.geo_coord_station.get(g).split(",");
																							geo_lat = geo_data[0];
																							geo_lng = geo_data[1];
																										
																							//System.out.println("geo_lat="+geo_lat+" ,geo_lng="+geo_lng+ " ,lat="+lat+" ,lng="+lng+" ,distance_variable="+((Float)av_pc.distance_variable.get(g))+" ,av_pc.customer_no_db.get(g)="+av_pc.customer_no_db.get(g));			
																							status_geo = utility_classes.check_with_range_landmark(lat, lng, geo_lat, geo_lng, ((Float)av_pc.distance_variable.get(g)));
																							//System.out.println("status_geo="+status_geo);
																						} 
																					} catch(Exception e3) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN1:"+e3.getMessage());}
																																
																					try
																					{
																						if( (status_geo==true) && ((Integer)av_pc.entered_flag.get(g) == 0) )
																						{                                            
																							//System.out.println("HALT_OCCURRED_BEFORE_SET:"+vname1+" ,customer="+av_pc.customer_no_db.get(g)+", dist_variable="+av_pc.distance_variable.get(g)+" ,xml_date="+xml_date+" ,entered_flag="+(Integer)av_pc.entered_flag.get(g));															
																							halt_occured = true;
																							last_customer_no = av_pc.customer_no_db.get(g);
																											
																							av_pc.entered_flag.set(g,1);	//corresponding to g
																										
																							//System.out.println("HALT_OCCURRED_AFTER_SET: ,entered_flag="+(Integer)av.entered_flag.get(g));                     
																							enter_time = xml_date;                                              
																							in_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																										
																							//System.out.println("Final:DateREf:"+date_ref+" ,DateCr:"+date_cr);
																							//System.out.println("STATION_GEO: TRUE1, enter_time="+enter_time);
																							//System.out.println("indist_BEFORE_SET="+in_dist);
																											
																							av_pc.intime_halt_2d[g][(Integer)av_pc.datetime_counter.get(g)] = enter_time;
																							av_pc.in_distance_2d[g][(Integer)av_pc.datetime_counter.get(g)] = in_dist;
																										
																							//System.out.println("STATION_GEO TRUE2, enter_time="+av.intime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																							//System.out.println("indist_AFTER_SET="+av.in_distance_2d[g][(Integer)av.datetime_counter.get(g)]);
																							//System.out.println("HaltOccured-2");                      
																						}
																					} catch(Exception e2) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN2:"+e2.getMessage());}
																				} //IF XML_DATE
																			} catch(Exception ec1) {System.out.println("Catch in Halt1:"+ec1.getMessage());}
																		} //FOR GEO COORD CLOSED
																	} catch(Exception ec2) {System.out.println("Catch in Halt2:"+ec2.getMessage());}
																} //IF GEO CORRD
															} catch(Exception ec2) {System.out.println("Catch in Halt3:"+ec2.getMessage());}
															
															//primary_halt_lat_ref = primary_halt_lat_cr;
															//primary_halt_lng_ref = primary_halt_lng_cr;
															//primary_halt_xml_data_sec_ref = primary_halt_xml_data_sec_cr;            				
															primary_halt_flag = 0;
															
														} //IF PRIMARY HALT FLAG CLOSED
														
																								                
														//###### SECOND BLOCK -OUTSIDE HALT										
														try{
															
															//if(halt_occured)	//IF PREVIOUS HALT OCCURED TRUE
															{
																try{
																	for(int g2=0; g2<av_pc.geo_coord_station.size();g2++)
																	{
																		if(((Integer)av_pc.entered_flag.get(g2)) ==0)
																		{
																			continue;
																		}
																		
																		if(!vname1.equals( (String)av_pc.vname.get(g2)))
																		{
																			continue;
																		}
																		
																		input_date1_sec = utility_classes.get_seconds(((String)av_pc.input_date1.get(g2)), 2);
																		input_date2_sec = utility_classes.get_seconds(((String)av_pc.input_date2.get(g2)), 2);
																		
																		try{
																			if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
																			{
																						//System.out.println("DATE MATCHED-2: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
																				status_geo = false;
																				
																				try{
																					if(!((String) av_pc.geo_coord_station.get(g2)).equals(""))
																					{
																						int current_customer = av_pc.customer_no_db.get(g2);
																						
																						//if( (last_customer_no>0) && (last_customer_no == current_customer) ) 
																						{																						
																							String[] geo_data = av_pc.geo_coord_station.get(g2).split(",");
																							geo_lat = geo_data[0];
																							geo_lng = geo_data[1];
																																														
																							//System.out.println("geo_lat="+geo_lat+" ,geo_lng="+geo_lng+ " ,lat="+lat+" ,lng="+lng+" ,distance_variable="+((Float)av_pc.distance_variable.get(g2))+"  ,xml_date="+xml_date+", date1="+(String)av_pc.input_date1.get(g2)+" ,date2="+(String)av_pc.input_date2.get(g2));
																							float tmpdistance = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																							//System.out.println("tmpdistance="+tmpdistance);
																							status_geo = utility_classes.check_with_range_landmark(lat, lng, geo_lat, geo_lng, ((Float)av_pc.distance_variable.get(g2)));
																							//System.out.println("status_geo="+status_geo);
																							//last_customer_no = 0;
																							
																							long enter_time_sec = utility_classes.get_seconds(enter_time, 2);																							
																							long diff_halt = xml_date_sec - enter_time_sec;
																							
																							if( (status_geo == false) && ( ((Integer)av_pc.entered_flag.get(g2)) ==1) && (diff_halt>60) )
																							{                    
																								//System.out.println("HALT COMPLETED1:"+vname1+" ,customer="+av_pc.customer_no_db.get(g2)+" ,dist_variable="+av_pc.distance_variable.get(g2)+"  ,xml_date="+xml_date);
																								halt_occured = false;
																								last_customer_no = 0;
																							 
																								av_pc.entered_flag.set(g2,0);	//corresponding to g
																								leave_time = xml_date;
																								av_pc.outime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = leave_time;
																							  
																								//System.out.println("HALT COMPLETED2: entered_flag="+(Integer)av_pc.entered_flag.get(g2));
																								//System.out.println("HC:: outime_halt="+av.outime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																			 
																								out_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																							  
																								//System.out.println("HC::outdist_BEFORE_SET="+out_dist);
																								
																								av_pc.out_distance_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = out_dist;
																							  
																								//System.out.println("HC::outdist_AFTER_SET="+av.out_distance_2d[g][(Integer)av.datetime_counter.get(g)]);
																							  
																								enter_time_tmp = av_pc.intime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)];
																								leave_time_tmp = av_pc.outime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)];   
																							  
																								//System.out.println("FINAL:COMPLETE::enter_time_tmp="+enter_time_tmp+" ,leave_time_tmp="+leave_time_tmp);
																							  
																								input_date1_sec = utility_classes.get_seconds(leave_time_tmp, 2);
																								time = (utility_classes.get_seconds(leave_time_tmp, 2)) - (utility_classes.get_seconds(enter_time_tmp, 2));  
																							  
																									//System.out.println("HC::input_date1_sec="+input_date1_sec+" ,time="+time);
																								//$hms = secondsToTime($time);
																								duration = utility_classes.get_hms(time);	//$hms[h].":".$hms[m].":".$hms[s];
																							  
																									//System.out.println("HC::duration_BEFORE="+duration);
																							  
																								av_pc.time_dur_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = duration;
																							  
																									//System.out.println("HC::duration_AFTER="+av.time_dur_halt_2d[g][(Integer)av.datetime_counter.get(g)]);														  													 
																							  
																									//System.out.println("HC::datetime_counter_BEFORE=" +(Integer)av.datetime_counter.get(g));
																									
																								av_pc.datetime_counter.set(g2,((Integer)av_pc.datetime_counter.get(g2)) + 1);	//corresponding to g
																							  
																									//System.out.println("HC::datetime_counter_AFTER=" +(Integer)av.datetime_counter.get(g));
																							  
																							} //IF GEO STATUS CLOSED
																						}
																					} 
																				} catch(Exception e3) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN1:"+e3.getMessage());}																
																			}																			
																			 
																		} catch(Exception e1) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN3:"+e1.getMessage());}
																	} // IF INPUT CSV DATE COMPARISON               
																} catch(Exception e4) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IF INPUT CSV DATE COMPARISON :"+e4.getMessage());}
															}  // GEO COORD LOOP 
														} catch(Exception e5) {System.out.println("Exception in Main File(STATION)::CUSTOMER:GEO COORD LOOP  :"+e5.getMessage());}
																			
													}  //else closed																
													//#####NORMAL HALT CLOSED																		
												   //#### LOGIC PART CLOSED										
												} //IF XML_DATE_SEC > STARTDATE CLOSED
											} catch(Exception e7) {System.out.println("Exception in Main File(STATION)::CUSTOMER:IF XML_DATE_SEC > STARTDATE:"+e7.getMessage());}
										} // IF XML_DATE!=NULL CLOSED
									} catch(Exception e8) {System.out.println("Exception in Main File(STATION)::CUSTOMER: XML_DATE:"+e8.getMessage());}
								} catch(Exception e9) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IF LEN CLOSED:"+e9.getMessage());}
							}	// if len closed
							
							f++;	//INCREMENT TOTAL LINES
						}	// while readline closed
						//System.out.println("Read File Completed::CUSTOMER");
						//Close the input stream
						in.close();
					}catch (Exception e10){ System.err.println("ERROR IN GETTING XML ATTRIBUTE::CUSTOMER: " + e10.getMessage());}			
					/////////////// E			
				}
				catch(Exception e)			////CATCH BLOCK
				{
					System.out.println("INSIDE CATCH++++++++++++++++ EXCEPTION OCCURRED::CUSTOMER");
					//System.out.println("Total no of t1: " + total_t1);
					return;									
				}							
				
				sort_xml.deleteFile(xml_original_tmp);// DELETE XML ORIGINAL TMP FILE
			} // IF ORIGINAL TEMP EXIST CLOSED
		}  // DATE FOR CLOSED 
		
		if(no_data)
		{
			av_pc.message_customer.add("Data Not Available");
		}
		else if(no_gps)
		{
			av_pc.message_customer.add("GPS Not Available");
		}
		else
		{
			av_pc.message_customer.add("");
		}
		
	}	//METHOD CLOSED
		
	
	//######## WRITE DOWNLOAD STATION FILE ############### 
	public static void write_download_station_files(int account_id, String filename, String folder_date, alert_variable_plant_customer av_local)
	{
		//System.out.println("IN DOWNLOAD STATION FILE::CUSTOMER");
		
		//String path = "C:\\gps_report";
		String path = "/var/www/html/vts/beta/src/php/gps_report";
		
        boolean success1 = (new File(path + "/" +account_id)).mkdir();  //ACCOUNT_ID   
        path = path+"/"+account_id;
        boolean success2 = (new File(path + "/download")).mkdir();  //DOWNLOAD
        path = path+"/download";
		//Date date;
		/*SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
		String folder_date = formatter.format(date);*/
		
		boolean success3 = (new File(path + "/" + folder_date)).mkdir();	//FOLDER DATE
		path = path+"/"+folder_date;										
				
		//SimpleDateFormat formatter2 = new SimpleDateFormat("HH:mm:ss");
		//String hms = formatter2.format(date);								//HH:MM:SS				
		long millisec = new Date().getTime();								//IN MILLI		
		
		//String[] fileparts = filename.split("\\.");
		//String final_file_name = path+"/"+fileparts[0]+"_"+hms+"_"+millisec+".csv";	//IN CSV 
		String final_file_name = path+"/"+filename+"_CUSTOMER_"+millisec+".csv";	//IN CSV
	
		//System.out.println("Download_file_name="+final_file_name);
	
		/*String final_file_path = path+"/"+final_file_name;									
		File file = new File(final_file_path);
		boolean exists = file.exists();*/		
		
		BufferedWriter out1 =null;
		
		try{
			out1 = new BufferedWriter(new FileWriter(final_file_name, false));		//	DO NOT UPDATE																												
			out1.write(av_local.stringData);
			out1.close();
		}catch(Exception e) {System.out.println("EXCEPTION IN STATION FILE WRITE ::CUSTOMER:"+e.getMessage());}
		
	}		
}
