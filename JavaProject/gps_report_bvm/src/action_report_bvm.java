//import java.beans.Statement;

//################ THIS FILE IS NOT COMPLETED YET #########################

//####### NOT COMPLETED YET 
import java.net.URL;
import java.sql.Statement;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.ResultSet;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import org.w3c.dom.NodeList;


public class action_report_bvm {

	public static void get_report(int account_id, String date_folder, String date_folder_path, String file_name, String file_path, String file_name_actual, String format, String sequence, String unique_id, alert_variable_bvm av_bvm, String extension)
	{
		alert_variable_bvm av_dist = null;
		//START ACCOUNT_ID LOOP		
		//alert_variables avd = new alert_variables();
		//String top_directory = "C:\\gps_report";
		String top_directory = "/var/www/html/vts/beta/src/php/gps_report";	//UPLOADED STATION FILE PATH
		//File top_dir = new File(top_directory);
		///GET ALL DIR IN XML_DATA
		ArrayList<String> MainDiretories = new ArrayList<String>();
		utility_classes.get_all_directories(top_directory, "account_dir", MainDiretories);
		int account_dir_count = MainDiretories.size();					//GET TOTAL ACCOUNT DIRECTORIES				
		
		String read_excel_filepath = "", tmp_str = "";
		
		int format_type = 0;
		boolean format_valid_master1 = false, format_valid_master2 = false, format_valid = false;
		String distance_file = "", billing_file="", unique_id_distance = "", unique_id_billing="";
		String file_name2 = "", read_excel_filepath2="", file_name_actual2="", format_billing="", sequence_billing="";
		
		//String file_path_master = "C:\\gps_report/"+account_id+"/master";
		String file_path_master = "/var/www/html/vts/beta/src/php/gps_report/"+account_id+"/master";	//UPLOADED STATION FILE PATH

		//####################### READ BVM MASTER1 ##########################//		
		//read_excel_filepath = file_path + "/"+file_name; 
		
		File file_folder1 = new File(file_path_master);
		//System.out.println("file_path:"+file_path);
		File[] listOfFiles_master1 = file_folder1.listFiles();
		//System.out.println("testA");
		//System.out.println("files="+listOfFiles.length);			
		 
		String filename_master ="", extension_master="", file_format_master="";
		
		//###### CHECKING MASTER FILE FORMAT AND READING
		for (int z = 0; z < listOfFiles_master1.length; z++)
		{						// CURRENT FILES
			if (listOfFiles_master1[z].isFile())
			{
				filename_master = listOfFiles_master1[z].getName();
				
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
				String seq1 = name_location[1];
				
				if(file_format_master.equalsIgnoreCase("3") && (seq1.equalsIgnoreCase("1")))
				{
					//System.out.println("file_format_master1="+file_format_master);
					format_valid_master1 = true;
					//#######READ MASTER FILE
					String read_master_path = file_path_master+"/"+filename_master;
					//System.out.println("READ_MASTER_PATH_PLANT1="+read_master_path);
					
					if(extension_master.equals("csv"))
					{  
						///#### INCLUDE READ STATION CSV FILE
						try{
							read_bvm_master.readCSVFile_BVM_MASTER1(read_master_path, av_bvm);
						} catch(Exception rr1) {System.out.println("Err:PlantMaster1="+rr1.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xls"))
					{
						 ///#### INCLUDE READ STATION XLS FILE		
						try{
							read_bvm_master.readXLSFile_BVM_MASTER1(read_master_path, av_bvm);
						} catch(Exception rr2) {System.out.println("Err:PlantMaster2="+rr2.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xlsx"))
					{
						///#### INCLUDE READ STATION XLSX FILE *******					
						try{
							read_bvm_master.readXLSXFile_BVM_MASTER1(read_master_path, av_bvm);
						} catch(Exception rr3) {System.out.println("Err:PlantMaster3="+rr3.getMessage());}
						///////////////////////////////////////////////////////////////
					}					
				}				
			}
		}		
		//###################### READ BVM MASTER1 CLOSED ######################//
		
		
		//####################### READ BVM MASTER2 ##########################//		
		//read_excel_filepath = file_path + "/"+file_name; 
		
		File file_folder2 = new File(file_path_master);
		//System.out.println("file_path:"+file_path);
		File[] listOfFiles_master2 = file_folder2.listFiles();
		//System.out.println("testA");
		//System.out.println("files="+listOfFiles.length);			
		 
		String filename_master2 ="", extension_master2="", file_format_master2="";
		
		//###### CHECKING MASTER FILE FORMAT AND READING
		for (int z = 0; z < listOfFiles_master2.length; z++)
		{						// CURRENT FILES
			if (listOfFiles_master2[z].isFile())
			{
				filename_master = listOfFiles_master2[z].getName();
				
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
				String seq2 = name_location[1];
				
				if(file_format_master.equalsIgnoreCase("3") && (seq2.equalsIgnoreCase("2")))
				{
					//System.out.println("file_format_master2="+file_format_master);
					format_valid_master2 = true;
					//#######READ MASTER FILE
					String read_master_path = file_path_master+"/"+filename_master;
					//System.out.println("READ_MASTER_PATH_PLANT2="+read_master_path);
					
					if(extension_master.equals("csv"))
					{  
						///#### INCLUDE READ STATION CSV FILE
						try{
							read_bvm_master.readCSVFile_BVM_MASTER2(read_master_path, av_bvm);
						} catch(Exception rr1) {System.out.println("Err:PlantMaster1="+rr1.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xls"))
					{
						 ///#### INCLUDE READ STATION XLS FILE		
						try{
							read_bvm_master.readXLSFile_BVM_MASTER2(read_master_path, av_bvm);
						} catch(Exception rr2) {System.out.println("Err:PlantMaster2="+rr2.getMessage());}
						//////////////////////////////////////////////////
					}
					else if(extension_master.equals("xlsx"))
					{
						///#### INCLUDE READ STATION XLSX FILE *******					
						try{
							read_bvm_master.readXLSXFile_BVM_MASTER2(read_master_path, av_bvm);
						} catch(Exception rr3) {System.out.println("Err:PlantMaster3="+rr3.getMessage());}
						///////////////////////////////////////////////////////////////
					}					
				}				
			}
		}		
		//###################### READ BVM MASTER2 CLOSED ######################//
		

		//######################## CHECK SEQUENCE ############################		
		//System.out.println("SEQ="+sequence+" ,EXT="+extension);
		
		if(sequence.equals("1"))		//IF SEQUENCE=1 (DISTANCE FILE) 
		{			
			read_excel_filepath = file_path + "/"+file_name; 
			
			//alert_variable_bvm av_dist = new alert_variable_bvm();
			av_dist = new alert_variable_bvm();
											
			if(extension.equals("csv"))
			{  
				///#### INCLUDE READ STATION CSV FILE
				try{
					//read_write_excel_file2 rw = new read_write_excel_file();
					read_bvm_input.readCSVFile1(read_excel_filepath, av_dist);
				} catch(Exception r1) {System.out.println(r1.getMessage());}
				//include_once("read_station_csv.php");
				//////////////////////////////////////////////////
			}
			else if(extension.equals("xls"))
			{
				 ///#### INCLUDE READ STATION XLS FILE		
				try{
					//read_write_excel_file rw = new read_write_excel_file();
					read_bvm_input.readXLSFile1(read_excel_filepath, av_dist);
				} catch(Exception r2) {System.out.println(r2.getMessage());}
				//writeXLSFile();				
				//include_once("read_station_xls.php");
				//////////////////////////////////////////////////
			}
			else if(extension.equals("xlsx"))
			{
				///#### INCLUDE READ STATION XLSX FILE *******					
				try{
					//read_write_excel_file rw = new read_write_excel_file();
					read_bvm_input.readXLSXFile1(read_excel_filepath, av_dist);
				} catch(Exception r3) {System.out.println(r3.getMessage());}
				//writeXLSXFile();
				//include_once("read_station_xlsx.php");
				///////////////////////////////////////////////////////////////
			}
			
			//##CALL DISTANCE REPORT
			//av_dist.stringData = av_dist.stringData + ",Plant OutDate,Plant OutTime,Plant InDate,Plant InTime,Distance (km)\n";
			av_dist.stringData = av_dist.stringData + ",Distance (km)\n";
			
			write_distance_report(account_id, av_dist, av_bvm);								
			//###### WRITE STATION FILES -DISTANCE
			write_download_station_files(account_id, file_name_actual, date_folder, av_dist);								
			//###########################
			distance_file = file_name;	
			
			File file_folder3 = new File(file_path);
			//System.out.println("file_path:"+file_path);
			File[] listOfFiles3 = file_folder3.listFiles();
			
			//CHECK FOR MATCHING BILLING FILE
			file_name2 = "";
			billing_file = "";
			read_excel_filepath2="";
			file_name_actual2="";
			format_billing="";
		    sequence_billing="";
			unique_id_distance = unique_id; 
			
			//System.out.println("LIST SIZE="+listOfFiles3.length);
			
			for (int p = 0; p < listOfFiles3.length; p++)
			{	// CURRENT FILES
				if (listOfFiles3[p].isFile())
				{
					file_name2 = listOfFiles3[p].getName();
					//System.out.println("FILE2="+file_name2);
					String[] tmp2 = file_name2.split("\\.");
					
					tmp_str = "";
					String extension2 ="";
					
					for(int len2=0;len2<tmp2.length;len2++)
					{			
						if(len2==tmp2.length-1)
						{
							extension2 = tmp2[len2].trim();
							//System.out.println("EX="+tmp[len1]);
						}
						else
						{
							if(tmp2.length>2)
							{
								if(len2==0)
								{
									tmp_str = tmp_str+""+tmp2[len2];
								}
								else
								{
									tmp_str = tmp_str+"."+tmp2[len2];
								}
							}
							else
							{
								tmp_str = tmp_str+""+tmp2[len2];
							}
							//System.out.println(tmp[len1]);
						}
					}																
					
					//String extension2 = tmp2[1].trim();
					read_excel_filepath2 = file_path + "/"+file_name2;
					//System.out.println("ReadExcelfilepath="+read_excel_filepath2);
					
					//System.out.println("TMP_STR="+tmp_str);
					String[] name_location2 = tmp_str.split("#");
					
					format_billing = name_location2[3];
					sequence_billing = name_location2[2];
					unique_id_billing = name_location2[1];
					file_name_actual2 = name_location2[0];
					
					//System.out.println("INNNN FORMAT3:FILE2-A");
					if(unique_id_billing.equals(unique_id_distance))	//IF UNIQUE PAIR IS MATCHED
					{
						//System.out.println("INNNN FORMAT2:FILE2-B");
						if(format_billing.equals("2"))		//IF FORMAT=2					
						{
							//System.out.println("INNNN FORMAT2:FILE2-C, NAME="+file_name_actual2+" ,UNIQ="+unique_id_billing+" ,SEQ="+sequence_billing+" ,FORMAT="+format_billing);
							if(sequence_billing.equals("2"))	//IF SEQUENCE=2 (BILLING FILE) 
							{																								
								billing_file = file_name2;
								//System.out.println("INNNN FORMAT2:FILE2-D");
								if(extension2.equals("csv"))
								{  
									///#### INCLUDE READ STATION CSV FILE
									try{
										read_bvm_input.readCSVFile2(read_excel_filepath2, av_bvm, av_dist);
									} catch(Exception rr1) {System.out.println("Billing1="+rr1.getMessage());}
									//////////////////////////////////////////////////
								}
								else if(extension2.equals("xls"))
								{
									 ///#### INCLUDE READ STATION XLS FILE		
									try{
										read_bvm_input.readXLSFile2(read_excel_filepath2, av_bvm, av_dist);
									} catch(Exception rr2) {System.out.println("Billing2="+rr2.getMessage());}
									//////////////////////////////////////////////////
								}
								else if(extension2.equals("xlsx"))
								{
									///#### INCLUDE READ STATION XLSX FILE *******					
									try{
										read_bvm_input.readXLSXFile2(read_excel_filepath2, av_bvm, av_dist);
									} catch(Exception rr3) {System.out.println("Billing3="+rr3.getMessage());}
									///////////////////////////////////////////////////////////////
								}
								
								format_valid = true;
								break;  // BREAK THE LOOP
							} // IF INNER SEQUENCE CLOSED
						} // IF INNER FORMAT CLOSED	
																	
					}  // IF INNER UNIQUE PAIR IS MATCHED CLOSED										
				} // IF INNER LIST OF FILE CLOSED
			} // FOR INNER FILE LOOP CLOSED
						
		}  //IF SEQUENCE =1
													
							
		//########### PROCESS -SECOND FILE = BILLING ##############/
		if(format_valid)	
		{
			String vname_tmp ="";				
			//System.out.println("VNAME_SIZE="+av_bvm.vname.size());
			
			for(int i=0;i<av_bvm.vname.size();i++)
			{
				if(i==0)
				{
				   vname_tmp = vname_tmp+"'"+(String)av_bvm.vname.get(i)+"'";
				}
				else
				{
				  vname_tmp = vname_tmp+",'"+(String)av_bvm.vname.get(i)+"'";
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
			
			//System.out.println(query);
			
			try{
				stmt1 = con.createStatement();
				res1 = stmt1.executeQuery(query);	
				
				String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="";
				while(res1.next())
				{
					imei_db = res1.getString("device_imei_no");
					av_bvm.vserial.add(imei_db);
	
					tmp_vid = res1.getString("vehicle_id");
					//av.vid.add(tmp_vid);
					
					query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id="+tmp_vid+"";	
	
					stmt2 = con.createStatement();
					res2 = stmt2.executeQuery(query2);
					
					if(res2.next())
					{
						tmp_vname1 = res2.getString("vehicle_name");
						av_bvm.vname1.add(tmp_vname1);
					}				
				}
			}catch (Exception e) { System.out.println("EXCEPTION IN GETTING ASSIGNED IMEI:"+e.getMessage()); }		
					
			
			//CODE FOR MULTIPLE DATES
			//System.out.println("av.vname1.size()="+av_bvm.vname1.size());
			
			for(int i=0;i<av_bvm.vname1.size();i++)		//SIZE OF VNAME IN DATABASE 
			{			
				String in_min_date = "3000-00-00 00:00:00";
				String in_max_date = "0000-00-00 00:00:00";
				
				String out_min_date = "3000-00-00 00:00:00";		
				String out_max_date = "0000-00-00 00:00:00";
			
				//System.out.println("av.vname.size()="+av.vname1.size());
				
				String in_min_date_final_tmp="",in_max_date_final_tmp="",out_min_date_final_tmp = "",out_max_date_final_tmp ="";
				
				for(int j=0;j<av_bvm.vname.size();j++)	//SIZE OF VNAME IN CSV
				{				
					//System.out.println("av.vname1="+(String)av.vname1.get(i)+" ,av.vname="+(String)av.vname.get(j));
					
					if( ((String)av_bvm.vname1.get(i).trim()).equals(((String)av_bvm.vname.get(j).trim())) )
					{
						//System.out.println("VEHICLE MATCHED");
						
						/*
						//DATE1
						String[] datetmp_input1 = ((String)av_bvm.date1_csv.get(j)).split("-");
						String in_date_csv = datetmp_input1[2]+"-"+datetmp_input1[1]+"-"+datetmp_input1[0]+" "+((String)av_bvm.time1_csv.get(j));
						
						//DATE2
						String[] datetmp_input2 = ((String)av_bvm.date2_csv.get(j)).split("-");
						String out_date_csv = datetmp_input2[2]+"-"+datetmp_input2[1]+"-"+datetmp_input2[0]+" "+((String)av_bvm.time2_csv.get(j));			
						*/
						
						String in_date_csv = (String)av_bvm.input_date1.get(j);
						String out_date_csv = (String)av_bvm.input_date2.get(j);
						
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
				
				av_bvm.in_min_date_final.add(in_min_date);
				av_bvm.in_max_date_final.add(in_max_date);
				av_bvm.out_min_date_final.add(out_min_date);
				av_bvm.out_max_date_final.add(out_max_date);														
				
			} // OUTER FOR CLOSED
			/////////////////////////	
			
			//String datetmp_start_xls = (String)av.date1_csv.get(0);
			//String[] datetmp_input = datetmp_start_xls.split("-");
	
			//String date1 = datetmp_input[2]+"-"+datetmp_input[1]+"-"+datetmp_input[0]+" 00:00:00";
			//String date2 = datetmp_input[2]+"-"+datetmp_input[1]+"-"+datetmp_input[0]+" 23:59:59";
							
			int vsize = av_bvm.vserial.size();
			//System.out.println("vsize:"+vsize);
			
			if(vsize>0)
			{		  
				av_bvm.stringData = av_bvm.stringData + ",VehicleName";
				
				av_bvm.stringData = av_bvm.stringData+",IN TIME,OUT TIME,DURATION(H:m:s)\n";
								
				//System.out.println("AV.STRINGDATA="+av.stringData);
				
				write_station_report(account_id, format_type, av_bvm, av_dist);
								
				//###### WRITE STATION FILES -BILLING
				write_download_station_files(account_id, file_name_actual2, date_folder, av_bvm);
								
				//String processed_file_path = "/var/www/html/vts/beta/src/php/processed_data_files/"+file_name;											
				//String upload_file_path = "C:\\upload_data_files/"+account_folder+"/"+date_folder+"/"+file_name;
				//String processed_file_path = "C:\\processed_data_files/"+file_name;							
				
				//utility_classes.copyfile(upload_file_path, processed_file_path);
				//System.out.println("DELETE_PATH:DISTANCE_FILE="+distance_file);
				if(!distance_file.equals(""))
				{
					String upload_file_path1 = file_path+"/"+distance_file;
					//System.out.println("DELETE_PATH:DISTANCE_FILE="+upload_file_path1);
					sort_xml.deleteFile(upload_file_path1);// DELETE UNSORTED TMP FILE1
				}
				//DELETE BILLING FILE
				String upload_file_path = file_path+"/"+billing_file;
				//System.out.println("DELETE_PATH:BILLING_FILE="+upload_file_path);
				sort_xml.deleteFile(upload_file_path);// DELETE UNSORTED TMP FILE
				
			} // IF VSIZE CLOSED
		}	// IF FORMAT VALID
				
	}  // MAIN CLOSED
	

	
	//##### WRITE STATION REPORT XML
	public static void write_station_report(int account_id, int format_type, alert_variable_bvm av, alert_variable_bvm av_dist)
	{
		int maxPoints = 1000;
		boolean file_exist = false;
				
		//System.out.println("IN WRITE_STATION_REPORT");
		
		Connection con = null;
		Statement stmt1 = null;
		ResultSet res1 = null;
		String geo_coord = "";
		int i=0;
		con = utility_classes.get_connection();
		
		//System.out.println("CUST_SIZE="+av.customer_no.size());
		
		for(i=0;i<av.customer_no.size();i++)
		{
			String query_geo = "SELECT DISTINCT station_coord,station_name, CAST(customer_no AS UNSIGNED INTEGER) as customer_no_int,distance_variable,google_location FROM station WHERE user_account_id ="+account_id+" AND CAST(customer_no AS UNSIGNED INTEGER)="+(Integer)av.customer_no.get(i)+" AND status=1";				
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
					av.geo_coord_station.add(geo_coord);
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
					av.google_location.add(location);
										
					//System.out.println("location="+location);
					String tmp_station_name = res1.getString("station_name");					
			      	tmp_station_name = tmp_station_name.replaceAll("/", "by");
			      	tmp_station_name = tmp_station_name.replaceAll("\\\\", "by");
			      	tmp_station_name = tmp_station_name.replaceAll("&", "and"); 
			      	
			      	//System.out.println("IF STATION-4");
			      	av.geo_station.add(tmp_station_name);
					int customer_no_int = Integer.parseInt(res1.getString("customer_no_int"));
					av.customer_no_db.add(customer_no_int);
					
					//System.out.println("IF STATION-5");
					float distance_variable_tmp = 0.0f;
					distance_variable_tmp = res1.getFloat("distance_variable");
					//System.out.println("DistanceVariable="+distance_variable_tmp);
					
					av.distance_variable.add(distance_variable_tmp);
					av.entered_flag.add(0);
					av.datetime_counter.add(0);				
					//System.out.println("IF STATION-6");
				}
				else
				{
					//System.out.println("ELSE STATION");
					geo_coord = "";
					av.geo_coord_station.add("");
					av.geo_station.add("");
					av.google_location.add("");
					av.customer_no_db.add(0);
					av.distance_variable.add(0.0f);
					av.entered_flag.add(0);
					av.datetime_counter.add(0);      
				}
			}catch (Exception e2) { 
			
					//System.out.println("EXCEPTION STATION");
					geo_coord = "";
					av.geo_coord_station.add("");
					av.geo_station.add("");
					av.google_location.add("");
					av.customer_no_db.add(0);
					av.distance_variable.add(0.0f);
					av.entered_flag.add(0);
					av.datetime_counter.add(0);  
					
					//System.out.println("EXCEPTION IN SELECT DISTINCT station_coord:"+e2.getMessage()); 
			}		
		}
	  
		//System.out.println("vserial_size="+av.vserial.size());
		
		for(i=0;i<av.vserial.size();i++)
		{  	
			//System.out.println("Test1:"+i);
			String startdate = (String)av.in_min_date_final.get(i);
			String enddate = (String)av.out_max_date_final.get(i);		
			
			//System.out.println("startdate="+startdate+" ,enddate="+enddate);
			//###### GET XML DATA
			get_station_xml_data((String)av.vserial.get(i), (String)av.vname1.get(i), startdate, enddate, av);			
			//System.out.println("AFTER get_station_xml_data");
			//#################
		}
		//System.out.println("Test2:"+i);
		//System.out.println("STATION_SIZE="+av.geo_coord_station.size()+" ,format_type="+format_type);
		
		try{
			for(i=0;i<av.geo_coord_station.size();i++)
			{
				//$stringData = $stringData.$date1_csv[$i].",".$time1_csv[$i].",".$date2_csv[$i].",".$time2_csv[$i].",".$doctype[$i].",".$plant[$i].",".$route[$i].",".$vname[$i].",".$vendor_name[$i].",".$customer_no[$i].",".$qty[$i].",".$unit[$i];			
				av.stringData = av.stringData + (String)av.date1_csv.get(i) +"," + (String) av.route.get(i)+ "," + (Integer) av.customer_no.get(i)+ "," + (String) av.vname.get(i);
				
				//av.stringData = av.stringData + "," + (String) av.google_location.get(i);
				
				/*if((av.datetime_counter.get(i)) > 0)
				{
					System.out.println("av.datetime_counter.get(i)="+(av.datetime_counter.get(i))+" ,i="+i);
				}*/
				
				//System.out.println("CustomerNo="+(Integer) av.customer_no.get(i)+ ", VehicleName="+ (String) av.vname.get(i));
				//System.out.println("datetime_counter="+av.datetime_counter.get(i));
				
				try{
					for(int j=0;j<(av.datetime_counter.get(i));j++)
					{				
						if(j == 0)
						{
							/*//BILLING VARIABLES							
							(String) av.route.get(i) ,(String) av.vname.get(i)
							
							//DISTANCE VARIABLE
							(String) av_dist.vehicle_route1.get(i) ,(String) av_dist.vehicle_name1.get(i) 
							
							(Float) av_dist.distance_tmp.add(distance);	*/						

							//CHECK
							boolean found_route = false;
							
							try{
								//System.out.println("av_dist.vehicle_name1.size()="+av_dist.vehicle_name1.size());
								
								for(int x=0;x<(av_dist.vehicle_name1.size());x++)
								{									
									//System.out.println("VehicleNameA1="+(String)av_dist.vehicle_name1.get(x));
											
									//if( ((Float) av_dist.distance_tmp.get(x)) > 0f)									
									//{										
										//System.out.println("VehicleNameB1="+(String)av_dist.vehicle_name1.get(x)+" ,vname="+(String) av.vname.get(i));
										//System.out.println("Vehicleroute1="+(String) av_dist.vehicle_route1.get(x)+" ,route="+(String) av.route.get(i));											
										
										if( (((String)av_dist.vehicle_name1.get(x)).equals((String) av.vname.get(i))) && ((String) av_dist.vehicle_route1.get(x)).equals((String) av.route.get(i)) )
										{
											found_route = true;
											break;
										}
									//}
								}
							}catch(Exception e2) {System.out.println("Error in Inner loop:av_dist.vehicle_name1-"+e2.getMessage()); }
							
							//System.out.println("FoundRoute="+found_route);
							
							if(found_route)
							{
								//System.out.println("FoundRoute="+found_route);
								try{
									String[] in_date_arr = av.intime_halt_2d[i][j].split(" ");
									String[] out_date_arr = av.outime_halt_2d[i][j].split(" ");
									
									//FORMAT DATE TO DD-MM-YY
									//String[] in_date = in_date_arr[0].split("-");              
									//String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
									
									//String[] out_date = out_date_arr[0].split("-");              
									//String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];
									//echo "<br>in_date=".$in_date_tmp." ,out_date=".$out_date_tmp;
									/////////////////////////        
									
									//float in_dist_tmp = utility_classes.Round(av.in_distance_2d[i][j],2);
									//float out_dist_tmp = utility_classes.Round(av.out_distance_2d[i][j],2);								
																		
									//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][j]="+av.time_dur_halt_2d[i][j]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
		
									av.stringData = av.stringData+","+in_date_arr[1]+","+out_date_arr[1]+","+av.time_dur_halt_2d[i][j];
									
								}catch(Exception e3) { System.out.println("Error in found route:"+e3.getLocalizedMessage()); }
							}
						}
						//}
					}  // FOR DATETIME COUNTER CLOSED
				} catch(Exception ef) { System.out.println("EXCEPTION IN FINAL BLOCK(STATION):MSG="+ef.getMessage());}   
				
				/*if((av.datetime_counter.get(i))==0)
				{
					try{
						if(av.intime_halt_2d[i].length>0)
						{
							if( !(av.intime_halt_2d[i][0].equals("")) )
							{
								String[] in_date_arr = av.intime_halt_2d[i][0].split(" ");
								//String[] out_date_arr = av.outime_halt_2d[i][0].split(" ");
								
								//FORMAT DATE TO DD-MM-YY
								String[] in_date = in_date_arr[0].split("-");              
								String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
								
								//String[] out_date = out_date_arr[0].split("-");              
								//String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];							
								/////////////////////////        
								
								float in_dist_tmp = utility_classes.Round(av.in_distance_2d[i][0],2);
								//float out_dist_tmp = utility_classes.Round(av.out_distance_2d[i][0],2);
								
								String blank = "";
								//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][0]="+av.time_dur_halt_2d[i][0]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
		
								av.stringData = av.stringData+","+in_date_arr[1]+","+blank+","+blank;
							}	
						}					
						else
						{
							av.stringData = av.stringData+", , , ,";
						}
					}catch(Exception e0) { System.out.println("Exception in datetime counter zero"); }
				}*/
				av.stringData = av.stringData+"\n";
			}   // FOR GEO COORD SIZE CLOSED	
		}catch(Exception ef2) { System.out.println("EXCEPTION IN GEO BLOCK(STATION):MSG="+ef2.getMessage());}
		
		//System.out.println("av.stringData ="+av.stringData);
	}
	

	public static void get_station_xml_data(String vehicle_serial, String vname1, String startdate, String enddate, alert_variable_bvm av)
	{
		
		//############## SET START DATE AND END DATE TOLERANCE (5MINS)  ##################
		/*//STARTDATE
		try{	
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
			Calendar c1 = Calendar.getInstance();
			c1.setTime(sdf.parse(startdate));
			c1.add(Calendar.MINUTE, -2);  // number of days to add
			startdate = sdf.format(c1.getTime());
			//System.out.println("startdate=" +startdate);
		}catch(Exception ec) {System.out.println("Err: STARTDATE TOLERANCE="+ec.getMessage());}
		
		
		//ENDDATE
		try{	
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
			Calendar c1 = Calendar.getInstance();
			c1.setTime(sdf.parse(enddate));
			c1.add(Calendar.MINUTE, 2);  // number of days to add
			enddate = sdf.format(c1.getTime());
			//System.out.println("enddate=" +enddate);
		}catch(Exception ec) {System.out.println("Err: STARTDATE TOLERANCE="+ec.getMessage());}	
		*/	
		//################################################################################

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
		String abspath_current = "/mnt/volume3/current_data/xml_data";
		String abspath_sorted = "/mnt/volume3/current_data/sorted_xml_data";
		//String abspath_current = "C:\\itrack_vts/xml_data";
		//String abspath_sorted = "C:\\itrack_vts/sorted_xml_data";
		
		String xml_file ="", xml_original_tmp="", xml_unsorted_file="", xml_unsorted_file_path="";
		String filename_tmp="", xml_unsorted_folder="", xml_original_folder="", folderDate="";
		String enter_time = "", leave_time ="", enter_time_tmp="", leave_time_tmp="", duration ="";
		String fix="", vehicleserial="", lat="", lng="", xml_date="", xml_datetime="", vserial="", geo_lat="", geo_lng="";
		
		long xml_date_sec=0, startdate_sec=0, enddate_sec=0, input_date1_sec=0, input_date2_sec=0, time =0;
		long plant_in_sec1=0,plant_in_sec2=0;
		int halt_complete = 0;
		boolean valid_data=false, status_geo = false, status_plant=false, plant_outflag=false, plant_inflag=false;
		boolean second_inflag = false, plant_in_time_flag=false;
		float in_dist = 0.0f, out_dist= 0.0f;
		
		String plant_lat = "", plant_lng = "";

		try{
			//System.out.println("BILLING1::SizeBVM_Master1:"+av.mb1_plant.size());
			for(int x=0;x<av.mb1_plant.size();x++)	//FILE1 ARRAYLIST INPUT
			{
				boolean match_found = false;
				int mb1_plant =0, mb2_plant=0;
				String mb1_coordinate="", mb2_vehicle="";
				try{
					mb1_plant = (Integer)av.mb1_plant.get(x);
					mb1_coordinate = (String)av.mb1_coordinate.get(x);
					
					//System.out.println("BILLING2::SizeBVM_Master2:"+av.mb2_plant.size());
					
					for(int y=0;y<av.mb2_plant.size();y++)	//FILE2 ARRAYLIST INPUT
					{
						mb2_plant = (Integer)av.mb2_plant.get(y);
						mb2_vehicle = (String)av.mb2_vehicle.get(y);
						
						//System.out.println("BILLING3::mb1_plant="+mb1_plant+" ,mb2_plant="+mb2_plant+" ,mb2_vehicle="+mb2_vehicle+" ,vname1="+vname1);
						
						if( (mb1_plant == mb2_plant) && (mb2_vehicle.trim().equalsIgnoreCase(vname1.trim())))
						{
							//System.out.println("BILLING::Match Found BVM Master");
							String[] plant_coord = av.mb1_coordinate.get(x).split(",");
							plant_lat = plant_coord[0];
							plant_lng = plant_coord[1];							
							match_found = true;
							break;
						}
					}
					if(match_found)
					{
						break;
					}					
				} catch(Exception eb) {System.out.println("Err: Match Coordinate1"+eb.getMessage());}
			}
		}catch(Exception ec) {System.out.println("Err: Match Coordinate2"+ec.getMessage());}
		//get plant name,corrdinates
		
		//## ADD FIRST DATE
		//userdates.add(startdate);
		
		/*
		String datetmp = "";
		//### ADD NEXT DATE
		try{	
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
			Calendar c = Calendar.getInstance();
			c.setTime(sdf.parse(datefrom));
			c.add(Calendar.DATE, 1);  // number of days to add
			datetmp = sdf.format(c.getTime());
			//String date2[] = datetmp.split(" ");
			//datetmp = date2[0]+" 12:00:00";
			//System.out.println("Userdate="+datetmp);
			//userdates.add(datetmp);  // dt is now the new date
		}catch(Exception ec) {System.out.println("Err: Check Next Date="+ec.getMessage());}
		
		String date1 = datefrom;
		*/

		//####### NORMAL HALT ################
		int primary_halt_firstdata_flag=0,primary_halt_flag=0,total_lines = 0;
		float primary_halt_distance	=0.f;
		String primary_halt_lat_ref = "", primary_halt_lng_ref="", primary_halt_lat_cr="",primary_halt_lng_cr="";
		long primary_halt_xml_data_sec_ref=0, primary_halt_xml_data_sec_cr=0, halt_dur=0, f=0;
		//####################################
		//long future_date_sec = 0;
		//future_date_sec = utility_classes.get_seconds(error_future_date, 2);
		boolean halt_occured = false;
		int last_customer_no =0;
		
		//##### DATE LOOP
		for(int i=0;i<=(date_size-1);i++)
		{  
			/*if(i==1)
			{
				date1 = datetmp;
				//String[] d1 = ((String)userdates.get(i)).split(" ");			
				startdate = datetmp+" 00:00:00";
				enddate = datetmp+" 12:00:00";				
				//System.out.println("Date Changed:"+date1);
			}*/		

			//String xml_current = abspath_current+"/current_data/xml_data/"+((String)userdates.get(i))+"/"+vehicle_serial+".xml";
			String xml_current = abspath_current+"/"+((String)userdates.get(i))+"/"+vehicle_serial+".xml";
					
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
					cx.set_master_variable(((String)userdates.get(i)), cx);
					
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
						
						FileInputStream fstream = new FileInputStream(xml_original_tmp);
						// Get the object of DataInputStream
						DataInputStream in = new DataInputStream(fstream);
						BufferedReader br = new BufferedReader(new InputStreamReader(in));
						String strLine;
						//Read File Line By Line
						//System.out.println("Read File Started ..");
						
						int c=0;
						String date_ref = "", date_cr="";
						
						while ((strLine = br.readLine()) != null) {
							
							int len = strLine.length();
							
							//System.out.println("STR_LEN="+len);
							
							if(len > 50)
							{
								try{
									fix = sort_xml.getXmlAttribute(strLine,""+cx.vc+"=\"[^\" ]+");
									xml_date  = sort_xml.getXmlAttribute(strLine,""+cx.vh+"=\"[^\"]+");
									//sts  = sort_xml.getXmlAttribute(strLine,"sts=\"[^\"]+");
									
									//System.out.println("XML_DATE="+xml_date);																		
									try{
										if(xml_date!=null)
										{				    					
											xml_date_sec = utility_classes.get_seconds(xml_date, 2);
											startdate_sec = utility_classes.get_seconds(startdate, 2);
											enddate_sec = utility_classes.get_seconds(enddate, 2);
											
											//UPDATE LAST POSITION DATETIME AND SERVERTIME  
											for(int g=0; g<av.geo_coord_station.size();g++)
											{												
												if(!vname1.equals( (String)av.vname.get(g)))
												{
													continue;
												}																																
												
												/*if(xml_date_sec > future_date_sec)
												{
													continue;
												}
												av_pc.server_time_2d[g][0] = sts;
												av_pc.device_time_2d[g][0] = xml_date;*/
											}
											
											
											try{
												if( (xml_date_sec >= startdate_sec && xml_date_sec <= enddate_sec) && (!xml_date.equals("-")) && (fix.equals("1")) )
												{							           	              																													
													lat = sort_xml.getXmlAttribute(strLine,""+cx.vd+"=\"[^\" ]+");
													lng  = sort_xml.getXmlAttribute(strLine,""+cx.ve+"=\"[^\" ]+");											
													vserial = vehicle_serial; 
													
													lat = lat.substring(0, lat.length() - 1);
													lng = lng.substring(0, lng.length() - 1);
																										
													/*if( (!lat.equals("")) && (!lng.equals("")) )
													{
														no_gps = false;
													}*/													
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
																if((av.geo_coord_station.size())>0)
																{                
																	//System.out.println("IN GEO_COORD");              
															
																	//System.out.println("STATION SIZE="+av.geo_coord_station.size());
																	try
																	{
																		for(int g=0; g<av.geo_coord_station.size();g++)
																		{																						
																			if(!vname1.equals( (String)av.vname.get(g)))
																			{
																				continue;
																			}
																						
																			input_date1_sec = utility_classes.get_seconds(startdate, 2);
																			input_date2_sec = utility_classes.get_seconds(enddate, 2);
																						
																			try
																			{
																				if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
																				{
																					//System.out.println("DATE MATCHED: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
																					status_geo = false;
																								
																					try
																					{
																						if(!((String) av.geo_coord_station.get(g)).equals(""))
																						{
																							String[] geo_data = av.geo_coord_station.get(g).split(",");
																							geo_lat = geo_data[0];
																							geo_lng = geo_data[1];
																										
																							status_geo = utility_classes.check_with_range_landmark(lat, lng, geo_lat, geo_lng, ((Float)av.distance_variable.get(g)));
																							
																							/*if(av.customer_no_db.get(g)==43 || av.customer_no_db.get(g)==740)
																							{
																								//System.out.println("geo_lat="+geo_lat+" ,geo_lng="+geo_lng+ " ,lat="+lat+" ,lng="+lng+" ,distance_variable="+((Float)av.distance_variable.get(g))+" ,av_pc.customer_no_db.get(g)="+av.customer_no_db.get(g)+" ,status_geo="+status_geo);
																							}*/
																							//System.out.println("status_geo="+status_geo);
																						} 
																					} catch(Exception e3) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN1:"+e3.getMessage());}
																																
																					try
																					{
																						if( (status_geo==true) && ((Integer)av.entered_flag.get(g) == 0) )
																						{                                            
																							//System.out.println("HALT_OCCURRED_BEFORE_SET:"+vname1+" ,customer="+av.customer_no_db.get(g)+", dist_variable="+av.distance_variable.get(g)+" ,xml_date="+xml_date+" ,entered_flag="+(Integer)av.entered_flag.get(g));															
																							halt_occured = true;
																							last_customer_no = av.customer_no_db.get(g);
																											
																							av.entered_flag.set(g,1);	//corresponding to g
																										
																							//System.out.println("HALT_OCCURRED_AFTER_SET: ,entered_flag="+(Integer)av.entered_flag.get(g));                     
																							enter_time = xml_date;                                              
																							in_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																										
																							//System.out.println("Final:DateREf:"+date_ref+" ,DateCr:"+date_cr);
																							//System.out.println("STATION_GEO: TRUE1, enter_time="+enter_time);
																							//System.out.println("indist_BEFORE_SET="+in_dist);
																											
																							av.intime_halt_2d[g][(Integer)av.datetime_counter.get(g)] = enter_time;
																							av.in_distance_2d[g][(Integer)av.datetime_counter.get(g)] = in_dist;
																										
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
																	for(int g2=0; g2<av.geo_coord_station.size();g2++)
																	{
																		if(((Integer)av.entered_flag.get(g2)) ==0)
																		{
																			continue;
																		}
																		
																		if(!vname1.equals( (String)av.vname.get(g2)))
																		{
																			continue;
																		}
																		
																		input_date1_sec = utility_classes.get_seconds(startdate, 2);
																		input_date2_sec = utility_classes.get_seconds(enddate, 2);
																		
																		try{
																			if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
																			{
																						//System.out.println("DATE MATCHED-2: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
																				status_geo = false;
																				
																				try{
																					if(!((String) av.geo_coord_station.get(g2)).equals(""))
																					{
																						int current_customer = av.customer_no_db.get(g2);
																						
																						//if( (last_customer_no>0) && (last_customer_no == current_customer) ) 
																						{																						
																							String[] geo_data = av.geo_coord_station.get(g2).split(",");
																							geo_lat = geo_data[0];
																							geo_lng = geo_data[1];
																																														
																							//System.out.println("geo_lat="+geo_lat+" ,geo_lng="+geo_lng+ " ,lat="+lat+" ,lng="+lng+" ,distance_variable="+((Float)av_pc.distance_variable.get(g2))+"  ,xml_date="+xml_date+", date1="+(String)av_pc.input_date1.get(g2)+" ,date2="+(String)av_pc.input_date2.get(g2));
																							float tmpdistance = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																							//System.out.println("tmpdistance="+tmpdistance);
																							status_geo = utility_classes.check_with_range_landmark(lat, lng, geo_lat, geo_lng, ((Float)av.distance_variable.get(g2)));
																							//System.out.println("status_geo="+status_geo);
																							//last_customer_no = 0;
																							
																							long enter_time_sec = utility_classes.get_seconds(enter_time, 2);																							
																							//long diff_halt = xml_date_sec - enter_time_sec;
																							//System.out.println("Integer)av.entered_flag.get(g2)="+(Integer)av.entered_flag.get(g2));
																							
																							if( (status_geo == false) && ( ((Integer)av.entered_flag.get(g2)) ==1) )
																							{                    
																								//System.out.println("HALT COMPLETED1:"+vname1+" ,customer="+av.customer_no_db.get(g2)+" ,dist_variable="+av.distance_variable.get(g2)+"  ,xml_date="+xml_date);
																								halt_occured = false;
																								last_customer_no = 0;
																							 
																								av.entered_flag.set(g2,0);	//corresponding to g
																								leave_time = xml_date;
																								av.outime_halt_2d[g2][(Integer)av.datetime_counter.get(g2)] = leave_time;
																							  
																								//System.out.println("HALT COMPLETED2: entered_flag";
																								//System.out.println("HC:: outime_halt="+av.outime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																			 
																								out_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																							  
																								//System.out.println("HC::outdist_BEFORE_SET="+out_dist);
																								
																								av.out_distance_2d[g2][(Integer)av.datetime_counter.get(g2)] = out_dist;
																							  
																								//System.out.println("HC::outdist_AFTER_SET="+av.out_distance_2d[g][(Integer)av.datetime_counter.get(g)]);
																							  
																								enter_time_tmp = av.intime_halt_2d[g2][(Integer)av.datetime_counter.get(g2)];
																								leave_time_tmp = av.outime_halt_2d[g2][(Integer)av.datetime_counter.get(g2)];   
																							  
																								//System.out.println("FINAL:COMPLETE::enter_time_tmp="+enter_time_tmp+" ,leave_time_tmp="+leave_time_tmp);
																							  
																								input_date1_sec = utility_classes.get_seconds(leave_time_tmp, 2);
																								time = (utility_classes.get_seconds(leave_time_tmp, 2)) - (utility_classes.get_seconds(enter_time_tmp, 2));  
																							  
																									//System.out.println("HC::input_date1_sec="+input_date1_sec+" ,time="+time);
																								//$hms = secondsToTime($time);
																								duration = utility_classes.get_hms(time);	//$hms[h].":".$hms[m].":".$hms[s];
																							  
																									//System.out.println("HC::duration_BEFORE="+duration);
																							  
																								av.time_dur_halt_2d[g2][(Integer)av.datetime_counter.get(g2)] = duration;
																							  
																									//System.out.println("HC::duration_AFTER="+av.time_dur_halt_2d[g][(Integer)av.datetime_counter.get(g)]);														  													 
																							  
																									//System.out.println("HC::datetime_counter_BEFORE=" +(Integer)av.datetime_counter.get(g));
																									
																								av.datetime_counter.set(g2,((Integer)av.datetime_counter.get(g2)) + 1);	//corresponding to g
																							  
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
						System.out.println("Read File Completed.");
						//Close the input stream
						in.close();
					}catch (Exception e10){ System.err.println("ERROR IN GETTING XML ATTRIBUTE: " + e10.getMessage());}			
					/////////////// E			
				}
				catch(Exception e)			////CATCH BLOCK
				{
					//System.out.println("INSIDE CATCH++++++++++++++++ EXCEPTION OCCURRED");
					//System.out.println("Total no of t1: " + total_t1);
					return;									
				}							
				
				sort_xml.deleteFile(xml_original_tmp);// DELETE XML ORIGINAL TMP FILE
			} // IF ORIGINAL TEMP EXIST CLOSED
						
		}  // DATE FOR CLOSED 
		
		try{
			for(int g2=0; g2<av.geo_coord_station.size();g2++)
			{
				//System.out.println("T2");
				//System.out.println("ABCD:"+vname1+" ,customer="+av.customer_no_db.get(g2));
				if(((Integer)av.entered_flag.get(g2)) ==0)
				{
					continue;
				}
				//System.out.println("T1");
				av.entered_flag.set(g2,0);	//corresponding to g
				av.outime_halt_2d[g2][(Integer)av.datetime_counter.get(g2)] = "- -";
				av.out_distance_2d[g2][(Integer)av.datetime_counter.get(g2)] = 0f;
				av.time_dur_halt_2d[g2][(Integer)av.datetime_counter.get(g2)] = "-";
				av.datetime_counter.set(g2,((Integer)av.datetime_counter.get(g2)) + 1);	//corresponding to g
			}              
		} catch(Exception e4) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IF INPUT CSV DATE COMPARISON :"+e4.getMessage());}
	}	//METHOD CLOSED
	

	//##### WRITE DISTANCE REPORT XML
	public static void write_distance_report(int account_id, alert_variable_bvm av_dist, alert_variable_bvm av_bvm)
	{
		int maxPoints = 1000;
		boolean file_exist = false, matched_status=false;
				
		//System.out.println("IN WRITE_DISTANCE_REPORT");
		
		Connection con = null;
		Statement stmt1 = null;
		ResultSet res1 = null;
		String geo_coord = "";
		int i=0, j=0, k=0;
		con = utility_classes.get_connection();	
		String imei1 = "", vname_main="", vname_tmp="",vroute_main="";
		boolean duplicate_entry = false;
		//System.out.println("vserial_size="+av_dist.vserial.size());
				
		for(i=0;i<av_dist.vehicle_name1.size();i++)
		{  	
			//##### FILTER DUPLICATE RECORDS
			duplicate_entry = false;
			
			try{
				vname_main = (String)av_dist.vehicle_name1.get(i);
				//System.out.println("vname_main="+(String)av_dist.vehicle_name1.get(i));
				
				vroute_main = (String)av_dist.vehicle_route1.get(i);
				
				for(k=0;k<av_dist.vehicle_name_TMP.size();k++)
				{					
					vname_tmp = (String)av_dist.vehicle_name_TMP.get(k);
					vname_tmp = (String)av_dist.vehicle_route_TMP.get(k);
					
					if( (vname_main.trim()).equalsIgnoreCase(vname_tmp.trim()) && (vroute_main.trim()).equalsIgnoreCase(vname_tmp.trim()))
					{
						duplicate_entry = true;
						break;
					}
				}				
			}catch(Exception d1){System.out.println("EXCEPTION IN FILTERING DUPLICATE ENTRY::BVM:="+d1.getMessage());}
			//#############################
			
			//System.out.println("Duplicate Entry="+duplicate_entry);
			
			try{
				if(!duplicate_entry)
				{
					//System.out.println("vname_main(NoDuplicate)="+(String)av_dist.vehicle_name1.get(i));
					
					av_dist.vehicle_name_TMP.add(vname_main);	//ADD VNAME TEMPORARILY FOR FILTERING
					av_dist.vehicle_route_TMP.add(vroute_main);
					
					av_dist.stringData = av_dist.stringData + (String)av_dist.trip_date.get(i) + "," + (String) av_dist.dcsm_name.get(i) +"," + (String) av_dist.vehicle_route1.get(i) +"," + (String) av_dist.vehicle_name1.get(i) +"," + (String) av_dist.activity_time1.get(i) +"," + (String) av_dist.activity_time2.get(i);					
					
					String startdate = (String)av_dist.trip_date.get(i)+" "+(String)av_dist.activity_time1.get(i);
					String enddate = (String)av_dist.trip_date.get(i)+" "+(String)av_dist.activity_time1.get(i);	
					
					//System.out.println("startdate="+startdate+" ,enddate="+enddate+" ,vehicle_name1_size="+av_dist.vname1_db.size());
					//###### GET XML DATA
					
					matched_status = false;
					try{
						for(j=0;j<av_dist.vname1_db.size();j++)
						{
							//System.out.println("vehicle_name1="+(String)av_dist.vehicle_name1.get(i)+" ,vname1_db="+(String)av_dist.vname1_db.get(j));
							
							if( ((String)av_dist.vehicle_name1.get(i).trim()).equalsIgnoreCase((String)av_dist.vname1_db.get(j).trim()))
							{
								imei1 = (String)av_dist.imei1_db.get(j);
								matched_status = true;
								//System.out.println("IMEI MATCHED:DISTANCE="+imei1);
								break;
							}
						}
					}catch(Exception ed1){System.out.println("EXCEPTION IN GETTING MATCHED_IMEI(DISTANCE)="+ed1.getMessage());}				
					
					float distance = 0.0f;
									
	
					if(matched_status)
					{
						try{
							//System.out.println("INPUT_DATE2="+(String)av_dist.input_trip_date2.get(i));
							//System.out.println("BEFORE GET DIST ,IMEI="+imei1+" ,VNAME1="+((String)av_dist.vehicle_name1.get(i))+" ,DATE1="+((String)av_dist.input_trip_date1.get(i))+" ,DATE2="+((String)av_dist.input_trip_date2.get(i)));
							distance = 0.0f;
							
							av_dist.plant_out_date = "";
							av_dist.plant_out_time = "";
							av_dist.plant_in_date = "";
							av_dist.plant_in_time = "";
							
							/*String[] dateinput = ((String)av_dist.input_trip_date1.get(i)).split(" ");
							String sd = dateinput[0]+" 00:00:00";
							String ed = dateinput[0]+" 23:59:59";*/
							
							//System.out.println("Before Get distance-INPUT_DATE1="+((String)av_dist.input_trip_date1.get(i))+" ,INPUT_DATE2="+((String)av_dist.input_trip_date2.get(i)));
							
							distance = get_distance_xml_data(imei1, ((String)av_dist.vehicle_name1.get(i)), ((String)av_dist.input_trip_date1.get(i)), ((String)av_dist.input_trip_date2.get(i)), av_dist, av_bvm);
							//distance = get_distance_xml_data(imei1, ((String)av_dist.vehicle_name1.get(i)), sd, ed, av_dist, av_bvm);
							av_dist.distance_tmp.add(distance);	//STORE TEMPORARY FOR COMPARISON WITH BILLING DETAIL
						}catch(Exception ed2){System.out.println("EXCEPTION IN GETTING DISTANCE="+ed2.getMessage());}
					}
					//System.out.println("AFTER get_station_xml_data");
					
					distance = utility_classes.Round(distance,2);
					
					//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][j]="+av.time_dur_halt_2d[i][j]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
												
					//av_dist.stringData = av_dist.stringData+","+av_dist.plant_out_date+","+av_dist.plant_out_time+","+av_dist.plant_in_date+","+av_dist.plant_in_time+","+distance;
					av_dist.stringData = av_dist.stringData+","+distance;
					av_dist.stringData = av_dist.stringData+"\n";
				} //NOT DUPLICATE ENTRY
				
			} catch(Exception ef) { System.out.println("EXCEPTION IN FINAL BLOCK(DISTANCE):MSG="+ef.getMessage());}   
			//#################
		}
		
		/*if((av_dist.datetime_counter.get(i))==0)
		{
			av_dist.stringData = av_dist.stringData+",";
		}*/
				
		//System.out.println("Test2:"+i);
		//System.out.println("av.stringData ="+av.stringData);
	} // WRITE DISTANCE REPORT CLOSED
	
	//DISTANCE XML	
	public static float get_distance_xml_data(String vehicle_serial, String vname1, String startdate, String enddate, alert_variable_bvm av, alert_variable_bvm av_bvm)
	{
		/*
		//############## SET START DATE AND END DATE TOLERANCE (5MINS)  ##################
		//STARTDATE
		try{	
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
			Calendar c1 = Calendar.getInstance();
			c1.setTime(sdf.parse(startdate));
			c1.add(Calendar.MINUTE, -2);  // number of days to add
			startdate = sdf.format(c1.getTime());
			//System.out.println("startdate=" +startdate);
		}catch(Exception ec) {System.out.println("Err: STARTDATE TOLERANCE="+ec.getMessage());}
		
		
		//ENDDATE
		try{	
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
			Calendar c1 = Calendar.getInstance();
			c1.setTime(sdf.parse(enddate));
			c1.add(Calendar.MINUTE, 2);  // number of days to add
			enddate = sdf.format(c1.getTime());
			//System.out.println("enddate=" +enddate);
		}catch(Exception ec) {System.out.println("Err: STARTDATE TOLERANCE="+ec.getMessage());}		
		//################################################################################
		 */
				
		//System.out.println("DATA:vehicle_serial="+vehicle_serial+", vname1="+vname1+" ,startdate="+startdate+",enddate="+enddate);		
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
		String abspath_current = "/mnt/volume3/current_data/xml_data";
		String abspath_sorted = "/mnt/volume3/current_data/sorted_xml_data";		
		//String abspath_current = "C:\\itrack_vts/xml_data";
		//String abspath_sorted = "C:\\itrack_vts/sorted_xml_data";
		String xml_file ="", xml_original_tmp="", xml_unsorted_file="", xml_unsorted_file_path="";
		String filename_tmp="", xml_unsorted_folder="", xml_original_folder="", folderDate="";
		String enter_time = "", leave_time ="", enter_time_tmp="", leave_time_tmp="", duration ="";
		String fix="", vehicleserial="", lat="", lng="", lat1="", lng1="", lat2="", lng2="", latlast="", lnglast="", xml_date="", xml_datetime="", vserial="";
		
		long xml_date_sec=0, startdate_sec=0, enddate_sec=0, input_date1_sec=0, input_date2_sec=0, time =0, interval=0;
		int halt_complete = 0;
		boolean firstdata = false, valid_data=false;
		float dist = 0.0f, sum_dist = 0.0f;
		
		long date_sec2=0, last_time_sec=0, last_time1_sec=0;		
		float tmp_time_diff= 0.0f, tmp_time_diff1 = 0.f, tmp_speed = 0.f;  
		boolean status_geo = false, status_plant=false, plant_outflag=false, plant_inflag=false;
		boolean plant_out_time_flag = false, plant_in_time_flag = false, second_in_plant = false;
		boolean display_time_inflag = false, display_time_outflag = false, end_flag=false;
		long plant_in_sec1 = 0, plant_in_sec2 = 0;
		String plant_lat = "", plant_lng = "", plant_final_in_time="";
		
		try{
			//System.out.println("SizeBVM_Master1:DISTANCE:"+av_bvm.mb1_plant.size());
			for(int x=0;x<av_bvm.mb1_plant.size();x++)	//FILE1 ARRAYLIST INPUT
			{
				boolean match_found = false;
				int mb1_plant =0, mb2_plant=0;
				String mb1_coordinate="", mb2_vehicle="";
				try{
					mb1_plant = (Integer)av_bvm.mb1_plant.get(x);
					mb1_coordinate = (String)av_bvm.mb1_coordinate.get(x);
					
					//System.out.println("SizeBVM_Master2:DISTANCE:"+av_bvm.mb2_plant.size());
					
					for(int y=0;y<av_bvm.mb2_plant.size();y++)	//FILE2 ARRAYLIST INPUT
					{
						mb2_plant = (Integer)av_bvm.mb2_plant.get(y);
						mb2_vehicle = (String)av_bvm.mb2_vehicle.get(y);
						
						//System.out.println("mb1_plant="+mb1_plant+" ,mb2_plant="+mb2_plant+" ,mb2_vehicle="+mb2_vehicle+" ,vname1="+vname1);
						if( (mb1_plant == mb2_plant) && (mb2_vehicle.trim().equalsIgnoreCase(vname1.trim())))
						{
							//System.out.println("Match Found:DISTANCE-BVM Master");
							String[] plant_coord = av_bvm.mb1_coordinate.get(x).split(",");
							plant_lat = plant_coord[0];
							plant_lng = plant_coord[1];							
							match_found = true;
							break;
						}
					}
					if(match_found)
					{
						break;
					}					
				} catch(Exception eb) {System.out.println("Err: Match Coordinate1"+eb.getMessage());}
			}
		}catch(Exception ec) {System.out.println("Err: Match Coordinate2"+ec.getMessage());}
		//get plant name,corrdinates

		//## ADD FIRST DATE
		//userdates.add(startdate);
		
		/*String datetmp = "";
		//### ADD NEXT DATE
		try{	
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
			Calendar c = Calendar.getInstance();
			c.setTime(sdf.parse(datefrom));
			c.add(Calendar.DATE, 1);  // number of days to add
			datetmp = sdf.format(c.getTime());
			//String date2[] = datetmp.split(" ");
			//datetmp = date2[0]+" 12:00:00";
			//System.out.println("Userdate="+datetmp);
			//userdates.add(datetmp);  // dt is now the new date
		}catch(Exception ec) {System.out.println("Err: Check Next Date="+ec.getMessage());}
		*/
		
		//##### DATE LOOP
		sum_dist = 0.0f;
		//String date1 = datefrom;
		
		//for(int i=0;i<1;i++)
		for(int i=0;i<=(date_size-1);i++)
		{ 											
			valid_data = false;
			
			/*if(end_flag)
			{
				break;
			}
			if(i==1)
			{
				date1 = datetmp;			
				startdate = datetmp+" 00:00:00";
				enddate = datetmp+" 12:00:00";				
				//System.out.println("Date Changed:"+date1);
			}*/		
			
			//System.out.println("III="+i+" ,STARTDATE="+startdate+" ,ENDDATE="+enddate);
			
			String xml_current = abspath_current+"/"+((String)userdates.get(i))+"/"+vehicle_serial+".xml";	
					
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
			
			//System.out.println("XML_CURRENT="+xml_current+" ,exist1="+exist1+" ,xml_file="+xml_file+" ,Exist2="+exist2);
			
			if (exist2) 
			{			
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
					common_xml_element cx = new common_xml_element();
					cx.set_master_variable(((String)userdates.get(i)), cx);
					
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
						
						FileInputStream fstream = new FileInputStream(xml_original_tmp);
						// Get the object of DataInputStream
						DataInputStream in = new DataInputStream(fstream);
						BufferedReader br = new BufferedReader(new InputStreamReader(in));
						String strLine;					
						
						//Read File Line By Line
						//System.out.println("Read File Started ..");
						int p=0;
						while ((strLine = br.readLine()) != null) {
							
							p++;
							
							valid_data = false;
							
							int len = strLine.length();
							
							//System.out.println("STR_LEN="+len);
							
							if(len > 50)
							{
								try{
									//fix = sort_xml.getXmlAttribute(strLine,"c=\"[^\"]+");
									//xml_date  = sort_xml.getXmlAttribute(strLine,"h=\"[^\"]+");
									
									fix = sort_xml.getXmlAttribute(strLine,""+cx.vc+"=\"[^\" ]+");
									xml_date  = sort_xml.getXmlAttribute(strLine,""+cx.vh+"=\"[^\"]+");									
									
									//System.out.println("XML_DATE="+xml_date+" ,fix="+fix);
									
									try{
										if(xml_date!=null)
										{				    					
											//System.out.println("xml_date="+xml_date+" ,startdate="+startdate+" ,enddate="+enddate);
											xml_date_sec = utility_classes.get_seconds(xml_date, 2);
											startdate_sec = utility_classes.get_seconds(startdate, 2);
											enddate_sec = utility_classes.get_seconds(enddate, 2);
											//System.out.println("One");
											//System.out.println("STATUS_PLANT1:XML_DATE="+xml_date+" ,DIST="+status_plant+" ,PLANT_INFLAG="+plant_inflag+" ,PLANT_OUTFLAG="+plant_outflag+" ,status_plant="+status_plant+" ,IMEI="+vehicle_serial);

											//if(p<10)											
												//System.out.println("xml_date="+xml_date+" ,startdate="+startdate+" ,enddate="+enddate+" ,fix="+fix);
											
											try{
												if( ( (xml_date_sec >= startdate_sec) && (xml_date_sec <= enddate_sec) ) && (!xml_date.equals("-")) && (fix.equals("1")) )
												{
													//System.out.println("Valid Data");
													valid_data = true;
												}
											}catch(Exception ev) { System.out.println("Invalid Data:"+ev.getMessage()); }
											
											try{	
												if(valid_data)
												{							           	              																													
													//System.out.println("XML_DATE_VALID="+xml_date);
													
													//lat = sort_xml.getXmlAttribute(strLine,"d=\"[^\" ]+");
													//lng  = sort_xml.getXmlAttribute(strLine,"e=\"[^\" ]+");	
													
													lat = sort_xml.getXmlAttribute(strLine,""+cx.vd+"=\"[^\" ]+");
													lng  = sort_xml.getXmlAttribute(strLine,""+cx.ve+"=\"[^\"]+");
													
													vserial = vehicle_serial; 
													
													lat = lat.substring(0, lat.length() - 1);
													lng = lng.substring(0, lng.length() - 1);																										
													//System.out.println("IN XML_READ::VSERIAL="+vserial+" ,LAT="+lat+" ,LNG="+lng);

													//System.out.println("STATUS_PLANT1:XML_DATE="+xml_date+" ,DIST="+status_plant+" ,PLANT_INFLAG="+plant_inflag+" ,PLANT_OUTFLAG="+plant_outflag+" ,status_plant="+status_plant+" ,IMEI="+vehicle_serial);
													if(firstdata_flag==0)
										            {
										                //echo "<br>FirstData";
										                firstdata_flag = 1;
										                lat1 = lat;
										                lng1 = lng;										              
										                last_time1_sec = utility_classes.get_seconds(xml_date, 2);
										                //System.out.println("FirstFlag:Distance");
										                latlast = lat;
										                lnglast = lng;
										              }           	              	
										              else
										              {                           														                                                               
														lat2 = lat;
														lng2 = lng;  
														
														try{
															
															double disttmp = utility_classes.calculateDistance2((double)(Float.parseFloat(lat1)),(double)(Float.parseFloat(lat2)),Float.parseFloat(lng1),(double)(Float.parseFloat(lng2)));
															//distance = utility_classes.calculateDistance(Float.parseFloat(lat1),Float.parseFloat(lat2),Float.parseFloat(lng1),Float.parseFloat(lng2));
															//System.out.println("XML_DATE="+xml_date+" ,(lat1="+lat1+" ,lng1="+lng1+") ,lat2="+lat2+",lng2="+lng2+") mDIST="+distance);
															distance = (float)disttmp;
															//System.out.println("DISTANCE(KM)="+distance);
															//sum_dist = (float) (sum_dist + distance);															
															
															double disttmp1 = utility_classes.calculateDistance2((double)(Float.parseFloat(latlast)),(double)(Float.parseFloat(lat2)),Float.parseFloat(lnglast),(double)(Float.parseFloat(lng2)));
															float distance1 = (float) disttmp1;
															
															tmp_time_diff1 = ((float) (xml_date_sec - last_time1_sec)) / 3600.0f;
															if(tmp_time_diff1>0.0f)
															{
																tmp_speed = ((float) (distance1)) / tmp_time_diff1;
																last_time1_sec = utility_classes.get_seconds(xml_date, 2);
																
									        					latlast = lat2;
									        					lnglast =  lng2;        																						
															}
															tmp_time_diff =((float) (xml_date_sec - last_time_sec)) / 3600.0f;
															
															//System.out.println("DISTANCE(KM)="+distance+" tmp_speed="+tmp_speed+" tmp_time_diff="+tmp_time_diff+" xml_date="+xml_date+" xml_date_sec="+xml_date_sec+" last_time_sec="+last_time_sec);
															if(tmp_speed<500 && distance>0.1 && tmp_time_diff>0)
															{		              
																sum_dist = (float) (sum_dist + distance);	                  						                          
																                                  																
																lat1 = lat2;
																lng1 = lng2;
																
																last_time_sec = utility_classes.get_seconds(xml_date, 2);			
															}																
														
														} catch(Exception e6) {System.out.println("Exception in Main File(DISTANCE): CALCULATE DISTANCE :"+e6.getMessage());}
														//#### LOGIC PART CLOSED
										              } //ELSE CLOSED
												} //IF XML_DATE_SEC > STARTDATE CLOSED
											} catch(Exception e7) {System.out.println("Exception in Main File(DISTANCE):IF XML_DATE_SEC > STARTDATE:"+e7.getMessage());}
										} // IF XML_DATE!=NULL CLOSED
									} catch(Exception e8) {System.out.println("Exception in Main File(DISTANCE): XML_DATE:"+e8.getMessage());}
								} catch(Exception e9) {System.out.println("Exception in Main File(DISTANCE): IF LEN CLOSED:"+e9.getMessage());}
							}	// if len closed
						}	// while readline closed
						//System.out.println("Read File Completed-(DISTANCE)");
						//Close the input stream
						in.close();
					}catch (Exception e10){ System.err.println("ERROR IN GETTING XML ATTRIBUTE(DISTANCE): " + e10.getMessage());}			
					/////////////// E			
				}
				catch(Exception e)			////CATCH BLOCK
				{
					//System.out.println("INSIDE CATCH++++++++++++++++ EXCEPTION OCCURRED(DISTANCE)");
					//System.out.println("Total no of t1: " + total_t1);								
				}											
				sort_xml.deleteFile(xml_original_tmp);// DELETE XML ORIGINAL TMP FILE
			} // IF ORIGINAL TEMP EXIST CLOSED
			//System.out.println("DATE_DIST="+sum_dist);
			
		}  // DATE FOR CLOSED 
		
		//STORE TOTAL DISTANCE
		//System.out.println("SUM_DIST="+sum_dist);
		return sum_dist;
	}	//METHOD CLOSED	
	
	
//DISTANCE CLOSED	
	
	public static void write_download_station_files(int account_id, String filename, String folder_date, alert_variable_bvm av_local)
	{
		//System.out.println("IN DOWNLOAD STATION FILE::BVM-filename="+filename);		
		
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
		String final_file_name = path+"/"+filename+"_"+millisec+".csv";	//IN CSV
	
		//System.out.println("Download_file_name="+final_file_name);
	
		/*String final_file_path = path+"/"+final_file_name;									
		File file = new File(final_file_path);
		boolean exists = file.exists();*/		
		
		BufferedWriter out1 =null;
		
		try{
			out1 = new BufferedWriter(new FileWriter(final_file_name, false));		//	DO NOT UPDATE																												
			out1.write(av_local.stringData);
			out1.close();
		}catch(Exception e) {System.out.println("EXCEPTION IN STATION FILE WRITE:"+e.getMessage());}
		
	}
	
	//GET URL LOCATION
	/*public static String get_url_location(String lat, String lng)
	{
		try {		
				String Request = "http://www.itracksolution.co.in/src/php/get_url_location.php?lat="+lat+"&lng="+lng+"";
				//URL my_url = new URL("http://www.placeofjo.blogspot.com/");
				URL my_url = new URL(Request);
				BufferedReader br = new BufferedReader(new InputStreamReader(my_url.openStream()));
				String strTemp = "";
				while(null != (strTemp = br.readLine())){
					//System.out.println(strTemp);
					return strTemp;
				}
		} catch (Exception ex) {ex.printStackTrace();}
		
		return null;
	}*/	
}
