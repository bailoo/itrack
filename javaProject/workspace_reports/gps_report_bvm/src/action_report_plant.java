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

public class action_report_plant {

	public static void get_report(int account_id, String date_folder, String date_folder_path, String file_name, String file_path, String file_name_actual, String format, String sequence, alert_variable_plant_customer av_pc, String extension)
	{
		String read_excel_filepath = "";
		String tmp_str = "", serverdatetime ="";
		boolean format_valid_master = false;
		
		SimpleDateFormat formatter;
		Date date = new Date();
		formatter = new SimpleDateFormat("yyyy-MM-dd");
		serverdatetime = formatter.format(date);							
		System.out.println("FORMAT-1::PLANT");
								
		read_excel_filepath = file_path + "/"+file_name; 
									
		//String file_path_master = "C:\\gps_report/"+account_id+"/master";
		String file_path_master = "/var/www/html/vts/beta/src/php/gps_report/"+account_id+"/master";	//UPLOADED STATION FILE PATH

		File file_folder = new File(file_path_master);
		//System.out.println("file_path_master:"+file_path_master);
		File[] listOfFiles = file_folder.listFiles();
		//System.out.println("testA");
		//System.out.println("files="+listOfFiles.length);			
		 
		String filename_master ="", extension_master="", file_format_master="";
		
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
				
				if(file_format_master.equalsIgnoreCase("2"))
				{
					format_valid_master = true;
					//#######READ MASTER FILE
					String read_master_path = file_path_master+"/"+filename_master;
					//System.out.println("READ_MASTER_PATH_PLANT="+read_master_path);
					
					if(extension_master.equals("csv"))
					{  
						///#### INCLUDE READ STATION CSV FILE
						try{
							read_plant_customer_master.readCSVFile_PLANT_MASTER(read_master_path, av_pc);
						} catch(Exception rr1) {System.out.println("Err:PlantMaster1="+rr1.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xls"))
					{
						 ///#### INCLUDE READ STATION XLS FILE		
						try{
							read_plant_customer_master.readXLSFile_PLANT_MASTER(read_master_path, av_pc);
						} catch(Exception rr2) {System.out.println("Err:PlantMaster2="+rr2.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xlsx"))
					{
						///#### INCLUDE READ STATION XLSX FILE *******					
						try{
							read_plant_customer_master.readXLSXFile_PLANT_MASTER(read_master_path, av_pc);
						} catch(Exception rr3) {System.out.println("Err:PlantMaster3="+rr3.getMessage());}
						///////////////////////////////////////////////////////////////
					}					
				}				
			}
		}
							
		if(format_valid_master)
		{
			alert_variable_plant_customer av_pc2 = new alert_variable_plant_customer();		//CREATE NEW OBJECT FOR FILTERING DATA			
			prepare_final_plant_data(av_pc, av_pc2);				

			//NOTE : AV_PC = MASTER, AV_PC2 = INPUT 
			
			/*System.out.println("Size_AV_PC1="+av_pc2.vname.size());
			for(int x=0;x<av_pc2.vname.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
			{  											
				System.out.println("VendorNameOUTSIDE_AFTER_PREPARE="+(String) av_pc2.vendor_name.get(x));
			}*/
			
			///////////////////////////
			String vname_tmp ="";				
			//System.out.println("vname="+av_pc2.vname.size());
			
			for(int i=0;i<av_pc2.vname.size();i++)
			{
				if(i==0)
				{
				   vname_tmp = vname_tmp+"'"+(String)av_pc2.vname.get(i)+"'";
				}
				else
				{
				  vname_tmp = vname_tmp+",'"+(String)av_pc2.vname.get(i)+"'";
				}
			}						
			
			Connection con = null;
			Statement stmt1 = null,stmt2 = null;
			ResultSet res1 = null, res2 = null;
			
			//utility_classes util = new utility_classes();
			con = utility_classes.get_connection();
			
			String query = "SELECT DISTINCT device_imei_no,vehicle_id FROM vehicle_assignment WHERE "+
			"vehicle_id IN(SELECT vehicle_id FROM vehicle WHERE vehicle_name IN("+vname_tmp+") AND status=1)"+
			" AND status=1"; 
			
			//System.out.println("Query="+query);
			
			try{
				stmt1 = con.createStatement();
				res1 = stmt1.executeQuery(query);	
				
				String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="";
				while(res1.next())
				{
					imei_db = res1.getString("device_imei_no");
					av_pc2.vserial.add(imei_db);
	
					tmp_vid = res1.getString("vehicle_id");
					//av.vid.add(tmp_vid);
					
					query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id="+tmp_vid+"";	
	
					stmt2 = con.createStatement();
					res2 = stmt2.executeQuery(query2);
					
					if(res2.next())
					{
						tmp_vname1 = res2.getString("vehicle_name");
						av_pc2.vname1.add(tmp_vname1);
					}				
				}
			}catch (Exception e) { System.out.println("EXCEPTION IN GETTING ASSIGNED IMEI::PLANT:"+e.getMessage()); }		
								
			//CODE FOR MULTIPLE DATES
			//System.out.println("av.vname1.size()="+av.vname1.size());

			/*System.out.println("Size_AV_PC2="+av_pc2.vserial.size());
			for(int x=0;x<av_pc2.vname.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
			{  											
				System.out.println("VendorNameOUTSIDE_AFTER_PREPARE="+(String) av_pc2.vendor_name.get(x));
			}*/
			
			for(int i=0;i<av_pc2.vname1.size();i++)			//DATABASE 
			{			
				String in_min_date = "3000-00-00 00:00:00";
				String in_max_date = "0000-00-00 00:00:00";
				
				String out_min_date = "3000-00-00 00:00:00";		
				String out_max_date = "0000-00-00 00:00:00";
			
				//System.out.println("av.vname.size()="+av_pc2.vname1.size());
				
				String in_min_date_final_tmp="",in_max_date_final_tmp="",out_min_date_final_tmp = "",out_max_date_final_tmp ="";
				
				for(int j=0;j<av_pc2.vname.size();j++)		//FILE
				{				
					//System.out.println("av.vname1="+(String)av_pc2.vname1.get(i)+" ,av.vname="+(String)av_pc2.vname.get(j));
					
					if( ((String)av_pc2.vname1.get(i).trim()).equals(((String)av_pc2.vname.get(j).trim())) )
					{
						//System.out.println("VEHICLE MATCHED");
						//DATE1
						String[] datetmp_input1 = ((String)av_pc2.date1_csv.get(j)).split("-");
						String in_date_csv = datetmp_input1[2]+"-"+datetmp_input1[1]+"-"+datetmp_input1[0]+" "+((String)av_pc2.time1_csv.get(j));
						
						//DATE2
						String[] datetmp_input2 = ((String)av_pc2.date2_csv.get(j)).split("-");
						String out_date_csv = datetmp_input2[2]+"-"+datetmp_input2[1]+"-"+datetmp_input2[0]+" "+((String)av_pc2.time2_csv.get(j));			
						
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
				
				av_pc2.in_min_date_final.add(in_min_date);
				av_pc2.in_max_date_final.add(in_max_date);
				av_pc2.out_min_date_final.add(out_min_date);
				av_pc2.out_max_date_final.add(out_max_date);														
				
			} // OUTER FOR CLOSED
			/////////////////////////	
			
			//String datetmp_start_xls = (String)av.date1_csv.get(0);
			//String[] datetmp_input = datetmp_start_xls.split("-");	
			//String date1 = datetmp_input[2]+"-"+datetmp_input[1]+"-"+datetmp_input[0]+" 00:00:00";
			//String date2 = datetmp_input[2]+"-"+datetmp_input[1]+"-"+datetmp_input[0]+" 23:59:59";

			/*System.out.println("Size_AV_PC3="+av_pc2.vserial.size());
			for(int x=0;x<av_pc2.vname.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
			{  											
				System.out.println("VendorNameOUTSIDE_AFTER_PREPARE="+(String) av_pc2.vendor_name.get(x));
			}*/
			
			int vsize = av_pc2.vserial.size();		//DATABASE
			//System.out.println("vsize::PLANT"+vsize);
			av_pc2.stringData = "";
			
			if(vsize>0)
			{		  								
				//#####################################################################################
				//#################### MATCH WITH MASTER DATA AND WRITE REPORT ########################											
				av_pc2.stringData = "";	//RESET STRING DATA STRING TO BLANK				
				av_pc2.stringData = av_pc2.stringData + "Date, Route No, Vehicle No, Vendor Name, Plant, ServerTime, DeviceTime, Schedule IN Time, Schedule OUT Time,IN Time Plant,Out Time Plant,Difference IN TIME (actual vs schedule),Difference OUT TIME (actual vs schedule),Report RUN time";								

				av_pc2.stringData = av_pc2.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s)";
				av_pc2.stringData = av_pc2.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s)";
				av_pc2.stringData = av_pc2.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s)";
				av_pc2.stringData = av_pc2.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s)";
				av_pc2.stringData = av_pc2.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s)";
				av_pc2.stringData = av_pc2.stringData+",IN DATE,IN TIME,OUT DATE,OUT TIME,DURATION(H:m:s)\n";
				
				//System.out.println("AV.STRINGDATA="+av.stringData);

				//################# WRITE FINAL PLANT REPORT ##########				

				/*System.out.println("Size_AV_PC4="+av_pc2.vserial.size());
				for(int x=0;x<av_pc2.vserial.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
				{  											
					System.out.println("VendorNameOUTSIDE_AFTER_PREPARE="+(String) av_pc2.vendor_name.get(x));
				}*/
				
				write_station_report(account_id, av_pc, av_pc2);
								
				//###### WRITE STATION FILES
				write_download_station_files(account_id, file_name_actual, date_folder, av_pc2);
								
			} // IF VSIZE CLOSED
		}	// IF FORMAT VALID						
				
	}  // MAIN CLOSED	

	
	//##### WRITE STATION REPORT XML
	public static void write_station_report(int account_id, alert_variable_plant_customer av_pc_master, alert_variable_plant_customer av_pc)
	{
		System.out.println("PlantSizeeeeee="+av_pc_master.plant.size());
		/*System.out.println("Size="+av_pc.vserial.size());
		for(int x=0;x<av_pc.vserial.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
		{  											
			System.out.println("VendorNameOUTSIDE="+(String) av_pc.vendor_name.get(x));
		}*/
		
		//AV_PC = AV_PC2, CUSTOMER_NO = PLANT_NO
		int maxPoints = 1000;
		boolean file_exist = false;
				
		//####### DEFINE ALERT VARIABLE FOR ERROR VEHICLE : VEHICLE TOO FAR FROM PLANT
		alert_variable_plant_customer avpc_error = new alert_variable_plant_customer(); 
		
		//System.out.println("IN WRITE_STATION_REPORT::PLANT");		
		Connection con = null;
		Statement stmt1 = null;
		ResultSet res1 = null;
		String geo_coord = "";
		int i=0;
		con = utility_classes.get_connection();
		
		//System.out.println("CUST_SIZE::PLANT="+av_pc.customer_no.size());
		
		for(i=0;i<av_pc.customer_no.size();i++)
		{									
			String query_geo = "SELECT DISTINCT station_coord,station_name, CAST(customer_no AS UNSIGNED INTEGER) as customer_no_int,distance_variable,google_location,type FROM station WHERE user_account_id ="+account_id+" AND CAST(customer_no AS UNSIGNED INTEGER)="+(Integer)av_pc.customer_no.get(i)+" AND type=1 AND status=1";				
		    //System.out.println("QUERY_GEO="+query_geo);
			  											
			//System.out.println("INSIDE CUSTOMER: VNAME="+(String) av_pc.vname.get(i)+" ,VENDOR_NAME="+(String) av_pc.vendor_name.get(i));			
			
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
					int customer_no_int = Integer.parseInt(res1.getString("customer_no_int"));	//THIS IS  AV_PC2->ACTUALLY HERE CUSTOMER_NO IS TREATED AS PLANT NO
					av_pc.customer_no_db.add(customer_no_int);
					
					//System.out.println("IF STATION-5");
					float distance_variable_tmp = 0.0f;
					distance_variable_tmp = res1.getFloat("distance_variable");
					distance_variable_tmp = 0.1f;
					av_pc.distance_variable.add(distance_variable_tmp);
					av_pc.entered_flag.add(0);
					av_pc.datetime_counter.add(0);				
					//System.out.println("IF STATION-6");
										
					String type_tmp = res1.getString("type");
					//System.out.println("TYPE="+type_tmp+" ,PLANT_NO="+customer_no_int+" ,COORD="+geo_coord);	
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
					
					System.out.println("EXCEPTION IN SELECT DISTINCT station_coord::PLANT:"+e2.getMessage()); 
			}		
		} //CUSTOMER NO CLOSED
	  
		//System.out.println("vserial_size="+av_pc.vserial.size());
		
		for(i=0;i<av_pc.vserial.size();i++)
		{  	
			//System.out.println("Test1:"+i);
			//String startdate = (String)av_pc.in_min_date_final.get(i);
			//String enddate = (String)av_pc.out_max_date_final.get(i);
			
			//String startdate = (String)av_pc.in_min_date_final.get(i);
			//String[] in_date_tmp = startdate.split(" ");
			//startdate = in_date_tmp[0]+" 00:00:00";			
			//String enddate = in_date_tmp[0]+" 23:59:59";
			
			String startdate = (String)av_pc.in_min_date_final.get(i);			
			String enddate = (String)av_pc.out_max_date_final.get(i);
			
			//startdate = in_date_tmp[0]+" 12:28:00";			
			//String enddate = in_date_tmp[0]+" 12:32:00";			
			
			//System.out.println("startdate="+startdate+" ,enddate="+enddate);
			//###### GET XML DATA
			get_station_xml_data((String)av_pc.vserial.get(i), (String)av_pc.vname1.get(i), startdate, enddate, av_pc, 1);			
			//System.out.println("AFTER get_station_xml_data");
			//#################
		}
		//System.out.println("Test2:"+i);
		//System.out.println("STATION_SIZE::PLANT="+av_pc.geo_coord_station.size());
		
		long in_time_plant_sec=0,out_time_plant_sec=0,diff_in_time_sec=0,diff_out_time_sec=0, lowest_time_diff=0;
		String in_time_plant="",out_time_plant="",diff_in_time="",diff_out_time="",report_run_time="";
		String[] startdate1 = new String[2];
		String[] enddate1 = new String[2];
		String message_plant_final = "";
		boolean vehicle_exist = false, too_far_flag = false;
		String error_date1="", error_date2="",error_vehicle_startdate="";
		String error_vserial = "", error_vname ="", error_date1_csv ="", error_route ="", error_vendor_name = "", vserial_file = "";
		int error_customer_no = 0, exception_flag=0;
		String sts="",devicetime="";
		
		/*try{			
			//for(int j=0;j<av_pc.geo_coord_station.size();j++)		//LOOP THROUGH TOTAL CUSTOMER RECORDS IN EXCEL
			for(int j=0;j<2;j++)
			{
				System.out.println("TMP_PLANT1");
				Integer tmp = (Integer)av_pc.customer_no.get(j);	
				System.out.println("TMP_PLANTTTTT="+tmp);
			}
		}
		catch(Exception et){System.out.println("Exception et="+et.getLocalizedMessage());}
		*/
		
		try{
			for(i=0;i<av_pc.geo_coord_station.size();i++)		//LOOP THROUGH TOTAL CUSTOMER RECORDS IN EXCEL
			{
				//GET SERVERT TIME AND DEVICE TIME
				sts = av_pc.server_time_2d[i][0];
				devicetime = av_pc.device_time_2d[i][0];
				//
				
				message_plant_final = "";
				error_date1 = "";
				error_date2 = "";
				vehicle_exist = false;
				//String startdate1="", enddate1="";				
				
				error_vserial = "";
				error_vname = "";
				error_date1_csv = "";
				error_route = "";
				error_vendor_name = "";
				error_customer_no = 0;					
				vserial_file = "";
				exception_flag = 0;
				boolean valid_data = false;
				
				for(int x=0;x<av_pc.vserial.size();x++)			//LOOP THROUGH TOTAL VEHICLE RECORDS IN DATABASE
				{  											
					//System.out.println("VendorName="+(String) av_pc.vendor_name.get(x));
					
					if(( (String)av_pc.vname1.get(x).trim()).equalsIgnoreCase((String)av_pc.vname.get(i).trim()))
					{
						vserial_file = (String)av_pc.vserial.get(x);
						
						String tmp_d1 = (String)av_pc.in_min_date_final.get(x);
						String tmp_d2 = (String)av_pc.out_max_date_final.get(x);
						
						error_date1 = tmp_d1; 
						error_date2 = tmp_d2;
							
						startdate1 = tmp_d1.split(" ");			
						enddate1 = tmp_d2.split(" ");
																		
						vehicle_exist = true;						
						
						String msg_plant_tmp = (String)av_pc.message_plant.get(x);
						
						if(!msg_plant_tmp.equals(""))
						{
							message_plant_final = msg_plant_tmp;
						}
						break;
					}
				}
												
				if(!vehicle_exist)
				{
					message_plant_final = "Vehicle Does Not Exist";
				}
				
				int customer_no=0, customer_no_db=0, route =0, plant=0;
				boolean plant_found_in_route = false;				
				
				try{										
					customer_no = (Integer)av_pc.customer_no.get(i);
					customer_no_db = (Integer)av_pc.customer_no_db.get(i);					
					System.out.println("route before="+(String)av_pc.route.get(i));
					System.out.println("plant before="+(Integer)av_pc.customer_no.get(i));
					route = Integer.parseInt((String)av_pc.route.get(i));
					//plant = Integer.parseInt((String)av_pc.plant.get(i));
					plant = (Integer)av_pc.customer_no.get(i);					
					System.out.println("route_after="+route+" ,plant_after="+plant);
					
				} catch(Exception e2) {System.out.println("EXCEPTION IN READING -MASTER DATA FILTER::PLANT-1:"+e2.getMessage());}				

				try{
					System.out.println("av_pc.mp_plant="+av_pc_master.mp_plant.size());
					for(int k=0;k<av_pc_master.mp_plant.size();k++)
					{												
						int mp_route = (int)(av_pc_master.mp_route_no.get(k));
						int mp_plant = (int)(av_pc_master.mp_plant.get(k)); //IS ACTUALLY CUSTOMER WITH TYPE=1
												
						//System.out.println("Route="+route+" ,mp_route="+mp_route+" ,plant="+plant+" ,mp_plant="+mp_plant+" ,cusomer_no_db="+customer_no_db);
						
						if( (route == mp_route) && (plant == mp_plant) && (customer_no_db>0) )
						{																			
							plant_found_in_route = true;							
						}
					}
				} catch(Exception e2) {System.out.println("EXCEPTION IN LOOP INNER2 -MASTER DATA FILTER::CUSTOMER:"+e2.getMessage());};						
				
				//if( (message_plant_final.equalsIgnoreCase("")) && (av_pc.datetime_counter.get(i) == 0) )
				if(message_plant_final.equalsIgnoreCase(""))
				{
					if(customer_no_db ==0)
					{
						message_plant_final = "Plant Does Not Exist In DataBase";
					}
					else if(!plant_found_in_route)
					{
						message_plant_final = "Plant Does Not Exist In this Route";
					}
				}
				
				System.out.println("MSG="+message_plant_final);
				//###### STORE FOR ERROR VEHICLES : IF TOO FAR EXISTS
				error_vserial = vserial_file;
				error_vname = (String)av_pc.vname.get(i);						
				error_date1_csv = (String)av_pc.date1_csv.get(i);
				error_route = (String) av_pc.route.get(i);
				error_vendor_name = (String) av_pc.vendor_name.get(i); 
				error_customer_no = (Integer) av_pc.customer_no.get(i);
				//###################################################
				
				//System.out.println("ERROR_vname="+error_vname+" ,error_vendor_name="+error_vendor_name+" ,error_route="+error_route+" ,error_customer_no="+error_customer_no);
				
				//System.out.println((String)av_pc.date1_csv.get(i));
				//System.out.println((String)av_pc.route.get(i));
				//System.out.println((String)av_pc.vname.get(i));
				//System.out.println((Integer)av_pc.customer_no.get(i));
				//System.out.println((String)av_pc.schedule_time.get(i));
				//System.out.println((String)av_pc.schedule_in_time.get(i));
				//System.out.println((String)av_pc.schedule_out_time.get(i));
				av_pc.stringData = av_pc.stringData + (String)av_pc.date1_csv.get(i)  +"," + (String) av_pc.route.get(i) +"," + (String) av_pc.vname.get(i)+"," + (String) av_pc.vendor_name.get(i) + "," + (Integer) av_pc.customer_no.get(i) +","+sts+","+devicetime+ "," + (String) av_pc.schedule_in_time.get(i)+ "," + (String) av_pc.schedule_out_time.get(i);
				
				//System.out.println("datetime_counter="+av_pc.datetime_counter.get(i));
				
				lowest_time_diff = 0;
				String stringDataTmp = "";

				/*String route_error="",schedule_in_error="",schedule_out_error="";				
				route_error = (String)av_pc.route.get(i);
				schedule_in_error = (String)av_pc.schedule_in_time.get(i);							
				schedule_out_error = (String)av_pc.schedule_out_time.get(i);*/
								
				try{
					exception_flag = 0;
					
					//#### MAKE 'SCHEDULE' AND 'START' DATE TIME STRING
					String schedule_in_time_str = startdate1[0]+" "+(String)av_pc.schedule_in_time.get(i);
					String schedule_out_time_str = startdate1[0]+" "+(String)av_pc.schedule_out_time.get(i);
					String start_time_str = startdate1[0]+" "+startdate1[1];
					
					//System.out.println("schedule_in_time_str=" +schedule_in_time_str+" ,schedule_out_time_str="+schedule_out_time_str+" ,start_time_str="+start_time_str);
							
					long schedule_time_in_sec = utility_classes.get_seconds(schedule_in_time_str, 2); 
					long schedule_time_out_sec = utility_classes.get_seconds(schedule_out_time_str, 2);
					//long start_time_sec = utility_classes.get_seconds(start_time_str, 2);
					long start_time_sec = utility_classes.get_seconds((String)av_pc.input_date1.get(i), 2);
					
					String tmp_time_schedule1 ="", tmp_time_schedule2="";	
					
					if( schedule_time_out_sec >= start_time_sec )
					{
						tmp_time_schedule1 = startdate1[0]+ " " +(String)av_pc.schedule_in_time.get(i);
						tmp_time_schedule2 = startdate1[0]+ " " +(String)av_pc.schedule_out_time.get(i);
						
						
					}
					else
					{
						tmp_time_schedule2 = enddate1[0]+ " " +(String)av_pc.schedule_out_time.get(i);
						
						if( schedule_time_in_sec >= schedule_time_out_sec)
						{
							tmp_time_schedule1 = startdate1[0]+ " " +(String)av_pc.schedule_in_time.get(i);
						}
						else
						{
							tmp_time_schedule1 = enddate1[0]+ " " +(String)av_pc.schedule_in_time.get(i);
						}
					}
					
					//System.out.println("tmp_time_schedule1="+tmp_time_schedule1+" ,tmp_time_schedule2="+tmp_time_schedule2);
					
					/*if( schedule_time_in_sec > start_time_sec )
					{
						tmp_time_schedule1 = startdate1[0]+ " " +(String)av_pc.schedule_in_time.get(i);
						
						if( schedule_time_in_sec >= schedule_time_out_sec)
						{
							tmp_time_schedule2 = startdate1[0]+ " " +(String)av_pc.schedule_out_time.get(i);
						}
						else
						{
							tmp_time_schedule2 = enddate1[0]+ " " +(String)av_pc.schedule_out_time.get(i);
						}
					}
					else
					{
						tmp_time_schedule1 = enddate1[0]+ " " +(String)av_pc.schedule_in_time.get(i);
						tmp_time_schedule2 = enddate1[0]+ " " +(String)av_pc.schedule_out_time.get(i);
					}*/
					
					int ScheduleinLoc =0;
					long TimeDiffPrev = 864000;
					long TimeDiffCurr = 864000;
					
					if(message_plant_final.equals(""))
					{
						try{
							//System.out.println("\nDatetimeCounter="+av_pc.datetime_counter.get(i));

							for(int j=0;j<(av_pc.datetime_counter.get(i));j++)
							{				
								//System.out.println("INSIDE LOOP::error_vserial+"+error_vserial+" ,error_vname="+error_vname+" ,error_date1_csv="+error_date1_csv+" ,error_vendor_name="+error_vendor_name);
								
								exception_flag = 0;
								//System.out.println("FINAL GET, j="+j);						
								String[] in_date_arr = av_pc.intime_halt_2d[i][j].split(" ");
								String[] out_date_arr = av_pc.outime_halt_2d[i][j].split(" ");
								
								//FORMAT DATE TO DD-MM-YY
								String[] in_date = in_date_arr[0].split("-");              
								String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
								
								String[] out_date = out_date_arr[0].split("-");              
								String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];       						
														
								//################ CHECK DIFFERENT INPUT DATA #######################
								String tmp_date_input1 = in_date_arr[0];						
								//String tmp_time_input1 = av_pc.intime_halt_2d[i][j];
								String tmp_time_input1 = startdate1[0]+ " " +startdate1[1];
								
								//System.out.println("tmp_time_input1="+tmp_time_input1+" ,tmp_time_schedule1="+tmp_time_schedule1);
								//System.out.println("startdate1="+startdate1[0]+" ,enddate1="+enddate1[0]);
								
								//long tmp_input1 = utility_classes.get_seconds(tmp_time_input1, 2);  //REPORT TIME
								long tmp_input1 = utility_classes.get_seconds((String)av_pc.input_date1.get(i), 2);
								long tmp_schedule1 = utility_classes.get_seconds(tmp_time_schedule1, 2);  //SCHEDULE TIME
								
								//System.out.println("Final: startdate1="+startdate1[0]+" ,enddate1="+enddate1[0]);
								TimeDiffCurr = Math.abs(tmp_input1 - tmp_schedule1);
								
								if(TimeDiffCurr<TimeDiffPrev)
								{
									TimeDiffPrev = TimeDiffCurr;
									ScheduleinLoc = j;
								}
								/*String final_date1 = "";
								if(tmp_input1 <= tmp_schedule1)
								{
									final_date1 = startdate1[0];
								}								
								else
								{
									final_date1 = enddate1[0];
								}
																				
								System.out.println("FinalDate1="+final_date1+"\n");
								/////////////////////						
								String tmp_date_input2 = out_date_arr[0];						
								String tmp_time_input2 = startdate1[0]+ " " +startdate1[1];
								String tmp_time_schedule2 = startdate1[0]+ " " +(String)av_pc.schedule_out_time.get(i);
								
								long tmp_input2 = utility_classes.get_seconds(tmp_time_input2, 2);
								long tmp_schedule2 = utility_classes.get_seconds(tmp_time_schedule2, 2);
								
								//System.out.println("tmp_time_input2="+tmp_time_input2+" ,tmp_time_schedule2="+tmp_time_schedule2);
								//System.out.println("Final: startdate1="+startdate1[0]+" ,enddate1="+enddate1[0]);
								
								String final_date2 = "";
								//if(tmp_input > tmp_schedule)
								if(tmp_input2 <= tmp_schedule2)
								{
									final_date2 = startdate1[0];
								}								
								else
								{
									final_date2 = enddate1[0];
								}						
								//###################################################################
								
								//System.out.println("FINAL_DATE1="+final_date1+" ,FINAL_DATE2="+final_date2);
								//String[] in_date1 = ((String)av_pc.input_date1.get(i)).split(" ");							
								String in_time_str_excel = final_date1+" "+(String)av_pc.schedule_in_time.get(i);							
								String out_time_str_excel = final_date2+" "+(String)av_pc.schedule_out_time.get(i);
														
								String in_time_str = av_pc.intime_halt_2d[i][j];							
								String out_time_str = av_pc.outime_halt_2d[i][j];						
								
								System.out.println("IN_TIME_STR_EXCEL="+in_time_str_excel+" ,IN_TIME_STR="+in_time_str);
								long time1 = (long)(Math.abs((utility_classes.get_seconds(in_time_str, 2)) - (utility_classes.get_seconds(in_time_str_excel, 2))));							
														
								if(j==0)
								{
									lowest_time_diff = time1;
								}
								if( time1 <= lowest_time_diff )	//IN TIME
								{
									lowest_time_diff = time1;	//ASSIGN LOWEST TIME DIFF							
									//diff_in_time = utility_classes.get_hms(time1);
									
									if(time1>0)
									{
										diff_in_time = utility_classes.get_hms(time1);
									}
									else
									{
										time1 = Math.abs(time1);
										//diff_in_time = "00:00:00";
										diff_in_time = utility_classes.get_hms(time1);
										diff_in_time = "-"+diff_in_time;
									}
									
									in_time_plant = in_date_arr[1];
									out_time_plant = out_date_arr[1];
										
									//System.out.println("in_time_plant="+in_time_plant+" ,out_time_plant="+out_time_plant);
									//long time2 = (long)(Math.abs((utility_classes.get_seconds(out_time_str_excel, 2)) - (utility_classes.get_seconds(out_time_str, 2))));
									long time2 = (long)((utility_classes.get_seconds(out_time_str, 2)) - (utility_classes.get_seconds(out_time_str_excel, 2)));
									//diff_out_time = utility_classes.get_hms(time2);
									
									if(time2>0)
									{
										diff_out_time = utility_classes.get_hms(time2);
									}
									else
									{
										time2 = Math.abs(time2);
										//diff_out_time = "00:00:00";
										diff_out_time = utility_classes.get_hms(time2);
										diff_out_time = "-"+diff_out_time;
									}
																
									//System.out.println("FinalString, intime="+in_time_plant+" ,out_time="+out_time_plant+" ,diff_in_time="+diff_in_time+" ,diff_out_time="+diff_out_time);							
								}*/
		
								//PRINT ALL RECORDS
								//stringDataTmp = stringDataTmp+","+in_time_plant+","+out_time_plant+","+diff_in_time+","+diff_out_time+","+report_run_time;
								//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][j]="+av.time_dur_halt_2d[i][j]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
								//stringDataTmp = stringDataTmp+""+in_date_tmp+","+in_date_arr[1]+","+out_date_tmp+","+out_date_arr[1]+","+av_pc.time_dur_halt_2d[i][j]+",";					
							}  // FOR DATETIME COUNTER CLOSED
						} catch(Exception e2) { System.out.println("Error in Datetime counter="+e2.getMessage()); }					
					} //IF FUNCTION CLOSED
						//stringDataTmp = stringDataTmp+""+in_date_tmp+","+in_date_arr[1]+","+out_date_tmp+","+out_date_arr[1]+","+av_pc.time_dur_halt_2d[i][ScheduleinLoc]+",";
									
						//System.out.println("AFTER LOOP::error_vserial="+error_vserial+" ,error_vname="+error_vname+" ,error_date1_csv="+error_date1_csv+" ,error_vendor_name="+error_vendor_name);
						
						//########### FINAL BLOCK ################//
						//System.out.println("DATE_TIME_COUNTER="+av_pc.datetime_counter.get(i));
						
						try{
							if( ((av_pc.datetime_counter.get(i))>0) && (message_plant_final.equals("")))
							{
								String in_date = av_pc.intime_halt_2d[i][ScheduleinLoc];
								String out_date = av_pc.outime_halt_2d[i][ScheduleinLoc];																
								
								String[] in_date_arr = in_date.split(" ");
								String[] out_date_arr = out_date.split(" ");
																		
								in_time_plant = in_date_arr[1];
								out_time_plant = out_date_arr[1];
								//tmp_time_schedule1 = tmp_time_schedule1+":00";
								long time1 = (long)(((utility_classes.get_seconds(in_date, 2)) - (utility_classes.get_seconds(tmp_time_schedule1, 2))));
								
								//System.out.println("in_date="+in_date+" ,tmp_time_schedule1="+tmp_time_schedule1+" ,Time1="+time1);
								
								if(time1>0)
								{
									//System.out.println("IF TIME1");
									diff_in_time = utility_classes.get_hms(time1);
								}
								else
								{
									//System.out.println("ELSE TIME1");
									diff_in_time = "-"+utility_classes.get_hms(Math.abs(time1)); 
								}
								//System.out.println("Diff In Time="+diff_in_time);
								
								long time2 = (long)(((utility_classes.get_seconds(out_date, 2)) - (utility_classes.get_seconds(tmp_time_schedule2, 2))));
								if(time2>0)
								{
									diff_out_time = utility_classes.get_hms(time2);
								}
								else
								{
									diff_out_time = "-"+utility_classes.get_hms(Math.abs(time2)); 
								}
								
								SimpleDateFormat formatter;
								Date date = new Date();
								formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
								report_run_time = formatter.format(date);
								av_pc.stringData = av_pc.stringData+","+in_time_plant+","+out_time_plant+","+diff_in_time+","+diff_out_time+","+report_run_time;
								//av_pc.stringData = av_pc.stringData + "," +stringDataTmp;
								//System.out.println("stringData2="+av_pc.stringData);
							}
							else
							{
								//System.out.println("IN ELSEEEEEEEEE="+message_plant_final);
								//if(message_plant_final.equals("") && (exception_flag == 0))
								if(message_plant_final.equals(""))
								{
									message_plant_final = "Vehicle Too Far From Plant";
									
									//############ ERROR VEHICLES VARIABLES ###############//					
									too_far_flag = true;
									
									//System.out.println("VEHICLES TOO FAR: vserial="+error_vserial+" ,vname="+error_vname+" ,error_vendor_name="+error_vendor_name);
									avpc_error.vname1.add(error_vname);		//DATABASE
									avpc_error.vname.add(error_vname);		//EXCEL
									avpc_error.vserial.add(error_vserial);
									avpc_error.in_min_date_final.add(error_date1);
									avpc_error.out_max_date_final.add(error_date2);
									
									//avpc_error.string_error_vehicle.add(av_pc.stringData);						
									avpc_error.date1_csv.add(error_date1_csv);
									avpc_error.route.add(error_route);
									avpc_error.vendor_name.add(error_vendor_name); 
									avpc_error.customer_no.add(error_customer_no);
			
									//avpc_error.schedule_in_time.add((String) av_pc.schedule_in_time.get(i)); 
									//avpc_error.schedule_out_time.add((String) av_pc.schedule_out_time.get(i));
									avpc_error.error_vehicle_startdate.add(startdate1[0]);
									avpc_error.error_vehicle_enddate.add(enddate1[0]);
									//System.out.println("TOO FAR VEHICLES DATA STORED IN AVPC_ERROR");
									
									/*avpc_error.route.add(route_error);
									avpc_error.schedule_in_time.add(schedule_in_error);
									avpc_error.schedule_in_time.add(schedule_out_error);*/
									//######### ERROR VEHICLES VARIABLES CLOSED ############//
								}
								av_pc.stringData = av_pc.stringData+","+message_plant_final+", , ,";
							}
						} catch(Exception e3) { System.out.println("In DateTime counter2="+e3.getMessage());}						
					
					av_pc.stringData = av_pc.stringData+"\n";
				} 
				catch(Exception ef) { 
					
					//exception_flag = 1;
					System.out.println("EXCEPTION IN FINAL BLOCK(STATION)::PLANT:MSG="+ef.getMessage());
				}   				

			}   // FOR GEO COORD SIZE CLOSED	
		}catch(Exception ef2) { System.out.println("EXCEPTION IN GEO BLOCK(STATION)::PLANT:MSG="+ef2.getMessage());}
		
		//System.out.println("av.stringData ="+av.stringData);
		
		
		//################ ERROR VEHICLES TOO FAR FROM PLANT (THIS BLOCK MAY BE DELETED IN FUTURE) ##########################
		if(too_far_flag)
		{			
			avpc_error.stringData = "";
			try{
				av_pc.stringData = av_pc.stringData+"\n";
				process_error_vehicles(avpc_error, account_id, av_pc_master);
				av_pc.stringData = av_pc.stringData+"\nLIST OF ERROR VEHICLES : CHECKING OF VEHICLES WITH EVERY PLANT WHICH ARE TOO FAR FROM ANY PLANT \n"+avpc_error.stringData+"\n";
			}catch(Exception ee) {System.out.println("Errro IN PROCESS ERROR VEHICLES");}
		}
		//############################### ERROR VEHICLES TOO FAR FROM PLANT CLOSED ##########################################
	}
		

	public static void get_station_xml_data(String vehicle_serial, String vname1, String startdate, String enddate, alert_variable_plant_customer av_pc, int type)
	{
		//System.out.println("startdate="+startdate+" ,enddate="+enddate+" ,vname1="+vname1);
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
		String primary_halt_lat_ref = "",primary_halt_lng_ref="", primary_halt_lat_cr="",primary_halt_lng_cr="";
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
			
			//System.out.println("Exist2="+exist2+" ,xml_file="+xml_file);
			
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
					String final_value ="";
					try{
						
						//SET MASTER VARIABLE
						common_xml_element cx = new common_xml_element();
						cx.set_master_variable(((String)userdates.get(i)),cx);
						
						// Open the file that is the first 
						// command line parameter
						
						File file3 = new File(xml_original_tmp);
						boolean exist3 = file3.exists();
						
						/*if(exist3)
						{
							System.out.println("XML ORIGINAL FILE EXISTS:xml_original_tmp="+xml_original_tmp);
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
						
						while ((strLine = br.readLine()) != null) {
							
							int len = strLine.length();
							
							//System.out.println("strLine="+strLine);
							
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
											
											for(int g=0; g<av_pc.geo_coord_station.size();g++)
											{
												//echo "<br>vname=".$vname1." ,vname[g]=".$vname[$g];
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
													//System.out.println("strLine="+strLine);
													lat = sort_xml.getXmlAttribute(strLine,""+cx.vd+"=\"[^\" ]+");
													lng  = sort_xml.getXmlAttribute(strLine,""+cx.ve+"=\"[^\" ]+");											
													vserial = vehicle_serial; 
													
													lat = lat.substring(0, lat.length() - 1);
													lng = lng.substring(0, lng.length() - 1);
													
													if( (!lat.equals("")) && (!lng.equals("")) )
													{
														no_gps = false;
													}
													
													if(primary_halt_firstdata_flag==0)
													{							
														primary_halt_flag = 0;
														primary_halt_firstdata_flag = 1;

														primary_halt_lat_ref = lat;		
														primary_halt_lng_ref = lng;							                	
														primary_halt_xml_data_sec_ref = xml_date_sec;									      
													}        
													else
													{														
														primary_halt_lat_cr = lat;		
														primary_halt_lng_cr = lng;							                	
														primary_halt_xml_data_sec_cr = xml_date_sec;									      

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
															//echo "<br>normal flag set";
															primary_halt_flag = 1;
														}
														
														if((primary_halt_flag == 1) && (halt_occured == false))
														{
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
																							//System.out.println("PLANT:HALT_OCCURRED_BEFORE_SET:"+vname1+" ,customer="+av_pc.customer_no_db.get(g)+", dist_variable="+av_pc.distance_variable.get(g)+" ,xml_date="+xml_date+" ,entered_flag="+(Integer)av_pc.entered_flag.get(g));															
																							halt_occured = true;
																							last_customer_no = av_pc.customer_no_db.get(g);
																											
																							av_pc.entered_flag.set(g,1);	//corresponding to g
																										
																							//System.out.println("HALT_OCCURRED_AFTER_SET: ,entered_flag="+(Integer)av.entered_flag.get(g));                     
																							enter_time = xml_date;                                              
																							in_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																										
																							//System.out.println("STATION_GEO TRUE1, enter_time="+enter_time);
																							//System.out.println("indist_BEFORE_SET="+in_dist);
																											
																							av_pc.intime_halt_2d[g][(Integer)av_pc.datetime_counter.get(g)] = enter_time;
																							av_pc.in_distance_2d[g][(Integer)av_pc.datetime_counter.get(g)] = in_dist;
																										
																							//System.out.println("STATION_GEO TRUE2, enter_time="+av.intime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																							//System.out.println("indist_AFTER_SET="+av.in_distance_2d[g][(Integer)av.datetime_counter.get(g)]);																							                      
																						}
																					} catch(Exception e2) {System.out.println("Exception in Main File(STATION)::PLANT: IN2:"+e2.getMessage());}
																				} //IF XML_DATE
																			} catch(Exception ec1) {System.out.println("Catch in Halt1:PLANT:"+ec1.getMessage());}
																		} //FOR GEO COORD CLOSED
																	} catch(Exception ec2) {System.out.println("Catch in Halt2:PLANT:"+ec2.getMessage());}
																} //IF GEO CORRD
															} catch(Exception ec2) {System.out.println("Catch in Halt3:PLANT:"+ec2.getMessage());}
															
															//primary_halt_lat_ref = primary_halt_lat_cr;
															//primary_halt_lng_ref = primary_halt_lng_cr;
															//primary_halt_xml_data_sec_ref = primary_halt_xml_data_sec_cr;            				
															primary_halt_flag = 0;
															
														} //IF PRIMARY HALT FLAG CLOSED
														
																								                
														//###### SECOND BLOCK -OUTSIDE HALT										
														try{
															
															if(halt_occured)	//IF PREVIOUS HALT OCCURED TRUE
															{
																try{
																	for(int g2=0; g2<av_pc.geo_coord_station.size();g2++)
																	{
																		//echo "<br>vname=".$vname1." ,vname[g]=".$vname[$g];
																		if(!vname1.equals( (String)av_pc.vname.get(g2)))
																		{
																			continue;
																		}
																		
																		input_date1_sec = utility_classes.get_seconds(((String)av_pc.input_date1.get(g2)), 2);
																		input_date2_sec = utility_classes.get_seconds(((String)av_pc.input_date2.get(g2)), 2);
																		
																		try{
																			if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
																			{
																						//System.out.println("DATE MATCHED: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
																				status_geo = false;
																				
																				try{
																					if(!((String) av_pc.geo_coord_station.get(g2)).equals(""))
																					{
																						int current_customer = av_pc.customer_no_db.get(g2);
																						
																						if( (last_customer_no>0) && (last_customer_no == current_customer) ) 
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
																							
																							if((status_geo == false) && ( ((Integer)av_pc.entered_flag.get(g2)) ==1) && (diff_halt>60) )
																							{                    
																								if(type==2)	//#ERROR VEHICLES
																								{
																									av_pc.plant_error_vname[g2][0] = vname1;
																								}
																								//System.out.println("HALT COMPLETED1:"+vname1+" ,customer="+av_pc.customer_no_db.get(g2)+" ,dist_variable="+av_pc.distance_variable.get(g2)+"  ,xml_date="+xml_date);
																								halt_occured = false;
																								last_customer_no = 0;
																							 
																								av_pc.entered_flag.set(g2,0);	//corresponding to g
																								leave_time = xml_date;
																								av_pc.outime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = leave_time;
																							  
																								//System.out.println("HALT COMPLETED2: entered_flag="+(Integer)av.entered_flag.get(g));
																								//System.out.println("HC:: outime_halt="+av.outime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																			 
																								out_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																							  
																								//System.out.println("HC::outdist_BEFORE_SET="+out_dist);
																								
																								av_pc.out_distance_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = out_dist;
																							  
																								//System.out.println("HC::outdist_AFTER_SET="+av.out_distance_2d[g][(Integer)av.datetime_counter.get(g)]);
																							  
																								enter_time_tmp = av_pc.intime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)];
																								leave_time_tmp = av_pc.outime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)];   
																							  
																								//System.out.println("HC::enter_time_tmp="+enter_time_tmp+" ,leave_time_tmp="+leave_time_tmp);
																							  
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
																				} catch(Exception e3) {System.out.println("Exception in Main File(STATION)::PLANT: IN1:"+e3.getMessage());}																
																			}
																																						 
																		} catch(Exception e1) {System.out.println("Exception in Main File(STATION)::PLANT: IN3:"+e1.getMessage());}
																	} // IF INPUT CSV DATE COMPARISON               
																} catch(Exception e4) {System.out.println("Exception in Main File(STATION)::PLANT: IF INPUT CSV DATE COMPARISON :"+e4.getMessage());}
															}  // GEO COORD LOOP 
														} catch(Exception e5) {System.out.println("Exception in Main File(STATION)::PLANT COORD LOOP  :"+e5.getMessage());}
																			
													}  //else closed																
													//#####NORMAL HALT CLOSED																		
												   //#### LOGIC PART CLOSED										
												} //IF XML_DATE_SEC > STARTDATE CLOSED
											} catch(Exception e7) {System.out.println("Exception in Main File(STATION)::PLANT:IF XML_DATE_SEC > STARTDATE:"+e7.getMessage());}
										} // IF XML_DATE!=NULL CLOSED
									} catch(Exception e8) {System.out.println("Exception in Main File(STATION)::PLANT: XML_DATE:"+e8.getMessage());}
								} catch(Exception e9) {System.out.println("Exception in Main File(STATION)::PLANT: IF LEN CLOSED:"+e9.getMessage());}
							}	// if len closed
							f++;	//INCREMENT TOTAL LINES
						}	// while readline closed
						//System.out.println("Read File Completed.");
						//Close the input stream
						in.close();
					}catch (Exception e10){ System.err.println("ERROR IN GETTING XML ATTRIBUTE::PLANT: " + e10.getMessage());}			
					/////////////// E			
				}
				catch(Exception e)			////CATCH BLOCK
				{
					System.out.println("INSIDE CATCH++++++++++++++++ EXCEPTION OCCURRED::PLANT");
					//System.out.println("Total no of t1: " + total_t1);
					return;									
				}							
				
				sort_xml.deleteFile(xml_original_tmp);// DELETE XML ORIGINAL TMP FILE
			} // IF ORIGINAL TEMP EXIST CLOSED
		}  // DATE FOR CLOSED 
		
		if(no_data)
		{
			av_pc.message_plant.add("Data Not Available");
		}
		else if(no_gps)
		{
			av_pc.message_plant.add("GPS Not Available");
		}
		else
		{
			av_pc.message_plant.add("");
		}
	}	//METHOD CLOSED
	
	//############# WRITE DOWNLOAD FILE ################# 
	public static void write_download_station_files(int account_id, String filename, String folder_date, alert_variable_plant_customer av_local)
	{
		//System.out.println("IN DOWNLOAD STATION FILE::PLANT");		
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
		String final_file_name = path+"/"+filename+"_PLANT_"+millisec+".csv";	//IN CSV
	
		//System.out.println("Download_file_name="+final_file_name);
	
		/*String final_file_path = path+"/"+final_file_name;									
		File file = new File(final_file_path);
		boolean exists = file.exists();*/		
		
		BufferedWriter out1 =null;
		
		try{
			out1 = new BufferedWriter(new FileWriter(final_file_name, false));		//	DO NOT UPDATE																												
			out1.write(av_local.stringData);
			out1.close();
		}catch(Exception e) {System.out.println("EXCEPTION IN STATION FILE WRITE:PLANT"+e.getMessage());}
		
	}
	
	//########### PREPARE FINAL DATA FOR PLANT ###########
	public static void prepare_final_plant_data(alert_variable_plant_customer av_pc, alert_variable_plant_customer av_pc2)
	{			
		boolean duplicate_entry = false;
		String date1="",time1="",date2="",time2="",input_date1="",input_date2="", vname="", vendor_name="";
		int plant=0, route=0, customer_no=0;
		boolean extra_vehicle_flag = false;
		
		System.out.println("PLANT SIZE:"+av_pc.plant.size());
		
		try{
			for(int i=0;i<av_pc.plant.size();i++)	//INPUT FILE PARAMETERS
			{
				try{
					date1 = (String)av_pc.date1_csv.get(i);					
					time1 = (String)av_pc.time1_csv.get(i);
					date2 = (String)av_pc.date2_csv.get(i);
					time2 = (String)av_pc.time2_csv.get(i);	
					//System.out.println("FILTER1:date1="+date1+" ,time1="+time1);
					//System.out.println("FILTER2:date2="+date2+" ,time2="+time2);
					
					input_date1 = (String)av_pc.input_date1.get(i);
					input_date2 = (String)av_pc.input_date2.get(i);
					//String doctype = (String)av_pc.doctype.get(i);
					//System.out.println("FILTER2:input_date1="+input_date1+" ,input_date2="+input_date2);
				//	plant = Integer.parseInt((String)av_pc.plant.get(i));
				//	route = Integer.parseInt((String)av_pc.route.get(i));
					plant = (int)Float.parseFloat((String)av_pc.plant.get(i));
					route = (int)Float.parseFloat((String)av_pc.route.get(i));
					
					//System.out.println("FILTER3:plant="+plant+" ,route="+route);
					
					vname = (String)av_pc.vname.get(i);
					vendor_name = (String)av_pc.vendor_name.get(i);
					//System.out.println("FILTER:VENDOR_NAME="+vendor_name+" ,vname="+vname);
					customer_no = (Integer)av_pc.customer_no.get(i);
					//System.out.println("FILTER:customer_no="+customer_no);
					
				} catch(Exception e2) {System.out.println("EXCEPTION IN READING -MASTER DATA FILTER::PLANT:"+e2.getMessage());};
	
					duplicate_entry = false;
				
				try{					
					for(int k=0;k<av_pc2.customer_no.size();k++)
					{
						int route_final = Integer.parseInt((String)av_pc2.route.get(k));	
						int plant_final = (Integer)av_pc2.customer_no.get(k); //IS ACTUALLY CUSTOMER WITH TYPE=1						
						String vname_final = (String) av_pc2.vname.get(k);
						
						if( (route == route_final) && (plant == plant_final) && (vname.equalsIgnoreCase(vname_final)) )
						{
							duplicate_entry = true;
						}
					}
				} catch(Exception e2) {System.out.println("EXCEPTION IN CHECKING DUPLICATE ENTRY -MASTER DATA FILTER::PLANT:"+e2.getMessage());};
				
					
				if(!duplicate_entry)
				{															
					//System.out.println("Unique Entry");
					
					extra_vehicle_flag = true;
					
					try{
						//System.out.println("Unique:av_pc.mp_plant.size()="+av_pc.mp_plant.size());
						
						for(int j=0;j<av_pc.mp_plant.size();j++)	//MASTER PLANT SIZE
						{
							/*int mp_route1 = (Integer)av_pc.mp_route_no.get(j);	
							int mp_plant1 = (Integer)av_pc.mp_plant.get(j); //IS ACTUALLY CUSTOMER WITH TYPE=1
							*/

							int mp_route1 = (int)(av_pc.mp_route_no.get(j));	
							int mp_plant1 = (int)(av_pc.mp_plant.get(j)); //IS ACTUALLY CUSTOMER WITH TYPE=1
							
							String mp_schedule_in_time1 = (String)av_pc.mp_schedule_in_time.get(j);
							String mp_schedule_out_time1 = (String)av_pc.mp_schedule_out_time.get(j);
							
							//System.out.println("Unique:Route="+route+" ,mp_route1="+mp_route1+" ,plant="+plant+" ,mp_plant1="+mp_plant1);
							
							if( (route == mp_route1) && (plant == mp_plant1) )
							{							
								System.out.println("Unique:Route and Plant matched2");
								//System.out.println("FILTER-A:VENDOR_NAME="+vendor_name);
								
								av_pc2.route.add(Integer.toString(route));  //ADD ROUTE (KEY)								
								av_pc2.customer_no.add(plant);				//ADD PLANT (KEY)
								
								av_pc2.date1_csv.add(date1);					
								av_pc2.time1_csv.add(time1);
					  
								av_pc2.date2_csv.add(date2);
								av_pc2.time2_csv.add(time2);
					              
								av_pc2.input_date1.add(input_date1);
								av_pc2.input_date2.add(input_date2);
								av_pc2.vname.add(vname);
								//System.out.println("FILTER-X:VENDOR_NAME="+vendor_name);
								av_pc2.vendor_name.add(vendor_name);
								av_pc2.schedule_in_time.add(mp_schedule_in_time1);
								av_pc2.schedule_out_time.add(mp_schedule_out_time1);
								
								extra_vehicle_flag = false;
								break;	//BREAK THE LOOP								
							}
						}
					} catch(Exception e2) {System.out.println("EXCEPTION IN LOOP INNER2 -MASTER DATA FILTER::PLANT:"+e2.getMessage());};
					
					
					//System.out.println("FILTER-B:VENDOR_NAME="+vendor_name);					
					if(extra_vehicle_flag)
					{
						//System.out.println("IN EXTRA VEHICLE FLAG");												
						av_pc2.route.add(Integer.toString(route));  //ADD ROUTE (KEY)
						av_pc2.customer_no.add(plant);				//ADD PLANT (KEY)
						
						av_pc2.date1_csv.add(date1);					
						av_pc2.time1_csv.add(time1);
			  
						av_pc2.date2_csv.add(date2);
						av_pc2.time2_csv.add(time2);
			              
						av_pc2.input_date1.add(input_date1);
						av_pc2.input_date2.add(input_date2);
						av_pc2.vname.add(vname);						
						//System.out.println("FILTER-C:VENDOR_NAME="+vendor_name);
						av_pc2.vendor_name.add(vendor_name);
						av_pc2.schedule_in_time.add("");
						av_pc2.schedule_out_time.add("");						
					}
						
				} // DUPLICATE ENTRY CLOSED
			}
		}catch(Exception e) {System.out.println("EXCEPTION IN LOOP OUTER -MASTER DATA FILTER::PLANT:"+e.getMessage());}		
	}
		
	
	//################# METHOD FOR ERROR VEHICLES : WHERE VEHICLE IS TOO FAR FROM PLANT #############################	
	public static void process_error_vehicles(alert_variable_plant_customer avpc_error, int account_id, alert_variable_plant_customer av_pc_master)
	{		
		String query_geo = "SELECT DISTINCT station_coord,station_name, CAST(customer_no AS UNSIGNED INTEGER) as customer_no_int,distance_variable,google_location,type FROM station WHERE user_account_id ="+account_id+" AND type=1 AND status=1";				
	    //System.out.println("IN PROCESS ERROR VEHICLES="+query_geo);
		
		Connection con = null;
		Statement stmt1 = null;
		ResultSet res1 = null;
		String geo_coord = "";
		int i=0;
		con = utility_classes.get_connection();

		try{
			stmt1 = null;
			res1 = null;				
			stmt1 = con.createStatement();
			res1 = stmt1.executeQuery(query_geo);					
			
			String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="", location="";
			while(res1.next())
			{
				//System.out.println("IF STATION-1");
				geo_coord = res1.getString("station_coord");
				geo_coord = geo_coord.replaceAll(", ",",");					
				avpc_error.geo_coord_station.add(geo_coord);
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
				avpc_error.google_location.add(location);
									
				//System.out.println("location="+location);
				String tmp_station_name = res1.getString("station_name");					
		      	tmp_station_name = tmp_station_name.replaceAll("/", "by");
		      	tmp_station_name = tmp_station_name.replaceAll("\\\\", "by");
		      	tmp_station_name = tmp_station_name.replaceAll("&", "and"); 
		      	
		      	//System.out.println("IF STATION-4");
		      	avpc_error.geo_station.add(tmp_station_name);
				int customer_no_int = Integer.parseInt(res1.getString("customer_no_int"));	//THIS IS  AV_PC2->ACTUALLY HERE CUSTOMER_NO IS TREATED AS PLANT NO
				avpc_error.customer_no_db.add(customer_no_int);
				
				//System.out.println("MpScheduleInTime Size:Error Vehicle="+av_pc_master.mp_schedule_in_time.size());
				
				for(int s=0;s<av_pc_master.mp_schedule_in_time.size();s++)
				{		  		
		  			int plant = (Integer)av_pc_master.mp_plant.get(s);
		  								
		  			//System.out.println("customer_no_int:Error Vehicle="+customer_no_int+" ,plant:Error Vehicle="+plant);		  					
		  			if(customer_no_int == plant)
					{
			  			//System.out.println("CustomerNo Matched In Error Vehicle");
						String schedule_in = (String)av_pc_master.mp_schedule_in_time.get(s);
						String schedule_out = (String)av_pc_master.mp_schedule_out_time.get(s);
						
						avpc_error.schedule_in_time.add(schedule_in);
						avpc_error.schedule_out_time.add(schedule_out);
						break;
					}
				}
				
				//System.out.println("IF STATION-5");
				float distance_variable_tmp = 0.0f;
				distance_variable_tmp = res1.getFloat("distance_variable");
				distance_variable_tmp = 0.1f;
				avpc_error.distance_variable.add(distance_variable_tmp);
				avpc_error.entered_flag.add(0);
				avpc_error.datetime_counter.add(0);				
				//System.out.println("IF STATION-6");
									
				String type_tmp = res1.getString("type");
				//System.out.println("TYPE="+type_tmp+" ,PLANT_NO="+customer_no_int+" ,COORD="+geo_coord);	
			} //WHILE CLOSED
			/*else
			{
				//System.out.println("ELSE STATION");
				geo_coord = "";
				avpc_error.geo_coord_station.add("");
				avpc_error.geo_station.add("");
				avpc_error.google_location.add("");
				avpc_error.customer_no_db.add(0);
				avpc_error.distance_variable.add(0.0f);
				avpc_error.entered_flag.add(0);
				avpc_error.datetime_counter.add(0);      
			}*/
		}catch (Exception e2) { 
		
				//System.out.println("EXCEPTION STATION");
				geo_coord = "";
				avpc_error.geo_coord_station.add("");
				avpc_error.geo_station.add("");
				avpc_error.google_location.add("");
				avpc_error.customer_no_db.add(0);
				avpc_error.distance_variable.add(0.0f);
				avpc_error.entered_flag.add(0);
				avpc_error.datetime_counter.add(0);  
				
				System.out.println("EXCEPTION IN SELECT DISTINCT station_coord::PLANT:ERROR_VEHICLES:"+e2.getMessage()); 
		}		
		
		//System.out.println("AVPC_ERROR_VSIZE="+avpc_error.vserial.size());
		
		for(i=0;i<avpc_error.vserial.size();i++)
		{  				
			String startdate = (String)avpc_error.in_min_date_final.get(i);			
			String enddate = (String)avpc_error.out_max_date_final.get(i);
	
			//###### GET XML DATA
			// TYPE=1 = NORMAL, TYPE=0 = ERROR
			//System.out.println("Vserial="+(String)avpc_error.vserial.get(i)+" ,Vname="+(String)avpc_error.vname1.get(i)+" ,Startdate="+startdate+" ,Enddate="+enddate);
			get_station_xml_data((String)avpc_error.vserial.get(i), (String)avpc_error.vname1.get(i), startdate, enddate, avpc_error, 2);			
			//System.out.println("AFTER get_station_xml_data");
			//#################
		}
		
		
		//#### NEXT ERROR BLOCK
		long in_time_plant_sec=0,out_time_plant_sec=0,diff_in_time_sec=0,diff_out_time_sec=0, lowest_time_diff=0;
		String in_time_plant="",out_time_plant="",diff_in_time="",diff_out_time="",report_run_time="";
		String[] startdate1 = new String[2];
		String[] enddate1 = new String[2];
		String message_plant_final = "";
		boolean vehicle_exist = false, too_far_flag = false;
		String error_date1="", error_date2="", error_vehicle_startdate="", error_vehicle_enddate="";
		
		try{			
			//System.out.println("AVPC_GEO_COORD_STATION_VSIZE="+avpc_error.geo_coord_station.size());
			boolean vexist = false;
						
			for(i=0;i<avpc_error.geo_coord_station.size();i++)		//LOOP THROUGH TOTAL CUSTOMER RECORDS IN EXCEL
			{
				message_plant_final = "";
				error_date1 = "";
				error_date2 = "";
				
				lowest_time_diff = 0;
				String stringDataTmp = "";
				
				error_vehicle_startdate="";
				error_vehicle_enddate="";								
				vexist = false;
				
				try{
					//System.out.println("DATETIME COUNTER ERROR_VEHICLES="+avpc_error.datetime_counter.get(i));					
										
					for(int j=0;j<(avpc_error.datetime_counter.get(i));j++)
					{										
						if(j==0)		// GET VEHICLE NAME
						{
							//System.out.println("avpc_error.vname.size()=" +avpc_error.vname.size());									
							for(int k=0;k<avpc_error.vname.size();k++)	//TOTAL SIZE OF ERROR 
							{																
								String vname_error = (String)avpc_error.vname.get(k); 
								String vname_entered = avpc_error.plant_error_vname[i][0];
								
								//System.out.println("vname_error="+vname_error+" ,vname_entered="+vname_entered);									
								if(vname_error.equalsIgnoreCase(vname_entered))
								{
									// ONLY CUSTOMER_NO = PLANT_NO IS CHANGED HERE BECAUSE IT MAY BE DIFFERENT FROM ORIGINAL
									avpc_error.stringData = avpc_error.stringData + (String)avpc_error.date1_csv.get(k)  +"," + (String) avpc_error.route.get(k) +"," + (String) avpc_error.vname.get(k)+"," + (String) avpc_error.vendor_name.get(k) + "," + (Integer) avpc_error.customer_no_db.get(i) + ","  + (String) avpc_error.schedule_in_time.get(i)+ "," + (String) avpc_error.schedule_out_time.get(i);
									//System.out.println("AVPC_ERROR_STR_DATA="+avpc_error.stringData);
									error_vehicle_startdate = (String)avpc_error.error_vehicle_startdate.get(k);
									error_vehicle_enddate = (String)avpc_error.error_vehicle_enddate.get(k);
									
									String tmp_d1 = (String)avpc_error.in_min_date_final.get(k);
									String tmp_d2 = (String)avpc_error.out_max_date_final.get(k);
									
									//error_date1 = tmp_d1; 
									//error_date2 = tmp_d2;
										
									startdate1 = tmp_d1.split(" ");			
									enddate1 = tmp_d2.split(" ");
									
									vexist = true;									
									break;
								}
							}													
						}
						
						if(vexist)
						{
							//System.out.println("FINAL GET, j="+j);						
							String[] in_date_arr = avpc_error.intime_halt_2d[i][j].split(" ");
							String[] out_date_arr = avpc_error.outime_halt_2d[i][j].split(" ");
							
							//FORMAT DATE TO DD-MM-YY
							String[] in_date = in_date_arr[0].split("-");              
							String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
							
							String[] out_date = out_date_arr[0].split("-");              
							String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];       						
													
							//System.out.println("ErrorVehicle1: in_date_tmp="+in_date_tmp+" ,out_date_tmp="+in_date_tmp);
													
							//################ CHECK DIFFERENT INPUT DATA #######################
							String tmp_date_input1 = in_date_arr[0];						
							String tmp_time_input1 = startdate1[0]+ " " +startdate1[1];
							
							String tmp_time_schedule1 = startdate1[0]+ " " +(String)avpc_error.schedule_in_time.get(i);
							
							//long tmp_input1 = utility_classes.get_seconds(tmp_time_input1, 2);
							long tmp_input1 = utility_classes.get_seconds((String)avpc_error.input_date1.get(i), 2);
							long tmp_schedule1 = utility_classes.get_seconds(tmp_time_schedule1, 2);
							
							//System.out.println("Final: startdate1="+startdate1[0]+" ,enddate1="+enddate1[0]);
							
							String final_date1 = "";
							//if(tmp_input > tmp_schedule)
							if(tmp_input1 <= tmp_schedule1)
							{
								final_date1 = startdate1[0];
							}								
							else
							{
								final_date1 = enddate1[0];
							}
							
							/////////////////////						
							String tmp_date_input2 = out_date_arr[0];						
							String tmp_time_input2 = startdate1[0]+ " " +startdate1[1];
							String tmp_time_schedule2 = startdate1[0]+ " " +(String)avpc_error.schedule_out_time.get(i);
							
							//long tmp_input2 = utility_classes.get_seconds(tmp_time_input2, 2);
							long tmp_input2 = utility_classes.get_seconds((String)avpc_error.input_date2.get(i), 2);
							long tmp_schedule2 = utility_classes.get_seconds(tmp_time_schedule2, 2);
							
							//System.out.println("Final: startdate1="+startdate1[0]+" ,enddate1="+enddate1[0]);
							
							String final_date2 = "";
							//if(tmp_input > tmp_schedule)
							if(tmp_input2 <= tmp_schedule2)
							{
								final_date2 = startdate1[0];
							}								
							else
							{
								final_date2 = enddate1[0];
							}						
							//###################################################################
							
							//String[] in_date1 = ((String)av_pc.input_date1.get(i)).split(" ");							
							String in_time_str_excel = final_date1+" "+(String)avpc_error.schedule_in_time.get(i);							
							String out_time_str_excel = final_date2+" "+(String)avpc_error.schedule_out_time.get(i);
							
							String in_time_str = avpc_error.intime_halt_2d[i][j];							
							String out_time_str = avpc_error.outime_halt_2d[i][j];						
							
							//System.out.println("ErrorVehicles::IN_TIME_STR_EXCEL="+in_time_str_excel+" ,OUT_TIME_STR_EXCEL="+out_time_str_excel+" ,IN_TIME_STR="+in_time_str+" ,OUT_TIME_STR="+out_time_str);
							
							long time1 = 0;
							
							//time1= (long)(Math.abs((utility_classes.get_seconds(in_time_str_excel, 2)) - (utility_classes.get_seconds(in_time_str, 2))));
							time1= (long)((utility_classes.get_seconds(in_time_str, 2)) - (utility_classes.get_seconds(in_time_str_excel, 2)));
																				
							//System.out.println("TIME1::"+time1);
							
							if(j==0)
							{
								lowest_time_diff = time1;
							}
							if( time1 <= lowest_time_diff )	//IN TIME
							{
								lowest_time_diff = time1;	//ASSIGN LOWEST TIME DIFF								
								//diff_in_time = utility_classes.get_hms(time1);
								
								if(time1>0)
								{
									diff_in_time = utility_classes.get_hms(time1);
								}
								else
								{
									//diff_in_time = "00:00:00";
									time1 = Math.abs(time1);
									//diff_out_time = "00:00:00";
									diff_in_time = utility_classes.get_hms(time1);
									diff_in_time = "-"+diff_in_time;									
								}
																
								//System.out.println("ERROR_V::DIFF_IN_TIME:"+diff_in_time);
								
								in_time_plant = in_date_arr[1];
								out_time_plant = out_date_arr[1];
									
								//System.out.println("in_time_plant="+in_time_plant+" ,out_time_plant="+out_time_plant);
								//long time2 = (long)(Math.abs((utility_classes.get_seconds(out_time_str_excel, 2)) - (utility_classes.get_seconds(out_time_str, 2))));
								long time2 = (long)((utility_classes.get_seconds(out_time_str, 2)) - (utility_classes.get_seconds(out_time_str_excel, 2)));
								//diff_out_time = utility_classes.get_hms(time2);
								if(time2>0)
								{
									diff_out_time = utility_classes.get_hms(time2);
								}
								else
								{
									//diff_out_time = "00:00:00";
									time2 = Math.abs(time2);
									//diff_out_time = "00:00:00";
									diff_out_time = utility_classes.get_hms(time2);
									diff_out_time = "-"+diff_out_time;									
								}								
								
								//System.out.println("Error_vehicles::FinalString, intime="+in_time_plant+" ,out_time="+out_time_plant+" ,diff_in_time="+diff_in_time+" ,diff_out_time="+diff_out_time);							
							}
	
							//PRINT ALL RECORDS
							//stringDataTmp = stringDataTmp+","+in_time_plant+","+out_time_plant+","+diff_in_time+","+diff_out_time+","+report_run_time;
	
							//System.out.println("ERROR_VEHICLE::in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][j]="+avpc_error.time_dur_halt_2d[i][j]);
							stringDataTmp = stringDataTmp+""+in_date_tmp+","+in_date_arr[1]+","+out_date_tmp+","+out_date_arr[1]+","+avpc_error.time_dur_halt_2d[i][j]+",";														
						
						} //IF VEXIST FLAG CLOSED

					}  // FOR DATETIME COUNTER CLOSED
										
				} catch(Exception ef) { System.out.println("EXCEPTION IN FINAL BLOCK(STATION)::PLANT:ERROR_VEHICLES:MSG="+ef.getMessage());}   
				
				
				if((avpc_error.datetime_counter.get(i))>0)
				{
					SimpleDateFormat formatter;
					Date date = new Date();
					formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
					report_run_time = formatter.format(date);
					avpc_error.stringData = avpc_error.stringData+","+in_time_plant+","+out_time_plant+","+diff_in_time+","+diff_out_time+","+report_run_time;
					avpc_error.stringData = avpc_error.stringData + "," +stringDataTmp+"\n";
					//System.out.println("stringData Error="+avpc_error.stringData);					
					//System.out.println("AVPC_ERROR_STRING DATA="+avpc_error.stringData);
				}
				/*else
				{
					if(message_plant_final.equals(""))
					{
						message_plant_final = "Vehicle Too Far From Plant";												
					}
					avpc_error.stringData = avpc_error.stringData+","+message_plant_final+", , ,";
				}
				avpc_error.stringData = avpc_error.stringData+"\n";
				*/				

			}   // FOR GEO COORD SIZE CLOSED
			
			avpc_error.stringData = avpc_error.stringData+"\n";
			
		}catch(Exception ef2) { System.out.println("EXCEPTION IN GEO BLOCK(STATION)::PLANT:ERROR_VEHICLES:MSG="+ef2.getMessage());}		
		//### NEXT ERROR BLOCK CLOSED
	}
	//################################### METHOD ERROR VEHICLES CLOSED ##############################################
}
