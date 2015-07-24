//import java.beans.Statement;
//import java.net.URL;
//import java.sql.Statement;
//import java.io.BufferedReader;
//import java.io.BufferedWriter;
//import java.io.DataInputStream;
import java.io.File;
//import java.io.FileInputStream;
//import java.io.FileWriter;
import java.io.IOException;
//import java.io.InputStreamReader;
//import java.sql.Connection;
//import java.sql.ResultSet;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
//import org.w3c.dom.NodeList;


public class gps_report_action {

	public static void main(String[] args) throws IOException
	{
		System.out.println("GPS REPORT MAIN");
		//START ACCOUNT_ID LOOP		
		alert_variables avd = new alert_variables();
		//String top_directory = "C:\\gps_report";
		String top_directory = "/var/www/html/vts/beta/src/php/gps_report";	//UPLOADED STATION FILE PATH
		//File top_dir = new File(top_directory);
		///GET ALL DIR IN XML_DATA
		ArrayList<String> MainDiretories = new ArrayList<String>();
		utility_classes.get_all_directories(top_directory, "account_dir", MainDiretories);
		int account_dir_count = MainDiretories.size();					//GET TOTAL ACCOUNT DIRECTORIES				
		
		String account_folder = "", date_folder_path="", date_folder ="", file_path="", file_name ="", file_name_actual="", read_excel_filepath = "";
		String format="", sequence="", tmp_str = "", serverdatetime ="", unique_id="";
		int account_id = 0, format_type = 0;
		//boolean format_valid = false;
		
		SimpleDateFormat formatter;
		Date date = new Date();
		formatter = new SimpleDateFormat("yyyy-MM-dd");
		serverdatetime = formatter.format(date);
		
		try{
			for(int x=0; x<account_dir_count; x++)
			{
				ArrayList<String> SubDiretories = new ArrayList<String>();
				account_folder = (String)MainDiretories.get(x);
				
				date_folder_path = top_directory+"/"+account_folder+"/upload";
				//File date_dir = new File(date_folder_path);
	
				utility_classes.get_all_directories(date_folder_path, "date_dir", SubDiretories);
				//date_folder = (String)av.station_date_diretories.get(i);				
				int date_dir_count = 0;
				try
				{
					date_dir_count = SubDiretories.size();
				}catch(Exception es){
					continue;
					}
				
				//System.out.println("Account_dir_count="+account_dir_count+" ,date_dir_count="+date_dir_count+" ,date_folder_path="+date_folder_path);
				//System.out.println("account_folder="+account_folder+" ,date_dir_count="+date_dir_count);	//GET TOTAL DATE DIRECTORIES
				/*if(account_folder=="568")
				{
					System.out.println("Account 568");
				}*/
				try{
					//System.out.println("ONE");
					for(int y=0; y<date_dir_count; y++)
					{			
						//System.out.println("TWO");					
						date_folder = (String)SubDiretories.get(y);	
						file_path = top_directory+"/"+account_folder+"/upload/"+date_folder;
						
						System.out.println("Date_folder="+date_folder+" ,STS="+serverdatetime);
						
						File file_folder = new File(file_path);
						System.out.println("FileFound11="+file_folder.exists());
						if(!file_folder.exists())
						{
							System.out.println("Continue");
							continue;
						}
						else
						{
							System.out.println("Not Continue");
						}
						System.out.println("FileFound2="+file_folder.exists());
						serverdatetime = serverdatetime.trim();
						date_folder = date_folder.trim();
						System.out.println("FileFound3="+file_folder.exists());
						System.out.println("serverdatetime="+serverdatetime+" ,date_folder="+date_folder);						
						if(serverdatetime.equals(date_folder))
						{
							//File file_folder = new File(file_path);
							System.out.println("File_path:"+file_path);
							File[] listOfFiles = file_folder.listFiles();
							//System.out.println("testA");
							//System.out.println("FilesLen="+listOfFiles.length);			
							 
							try{
								for (int z = 0; z < listOfFiles.length; z++)
								{						// CURRENT FILES
									if (listOfFiles[z].isFile())
									{
										format_type = 1;
										//format_valid = false;
																								
										file_name = listOfFiles[z].getName();
										read_excel_filepath = file_path+"/"+file_name;
										//System.out.println("ACCOUNT_DIR="+account_folder+" ,DATE_FOLDER="+date_folder+" ,FileName ="+file_name);						
										//int file_length = file_name.length();
								
										account_id = Integer.parseInt(account_folder);
										//int local_account_id = 2; // WILL BE FOUND BY FOLDER NAME
										//customer_no =null;
										//date =null;
										//stringData ="";
										
										//GET THE FILE EXTENSION
										//System.out.println("FILE="+file_name);
										String[] tmp = file_name.split("\\.");		
										//System.out.println("tmp0="+tmp[0]+" ,tmp1="+tmp[1]);
										
										tmp_str = "";
										String extension ="";
										
										for(int len1=0;len1<tmp.length;len1++)
										{			
											if(len1==tmp.length-1)
											{
												extension = tmp[len1].trim();
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
																							
										System.out.println("file_name="+file_name+ ",tmp_str="+tmp_str);
												
										//read_excel_filepath = file_path + "/"+file_name; 
																
										String[] name_location = tmp_str.split("#");	//TMP[0]= FILENAME WITHOUT EXTENSION
				
										file_name_actual = name_location[0];
										System.out.println("READ_EXCEL_FILE_PATH="+read_excel_filepath+" ,file_name_actual="+file_name_actual+ ",unique_id="+name_location[1]+" ,sequence="+name_location[2]+" ,FORMAT="+name_location[3]);
																	
										format = name_location[3];
										sequence = name_location[2];
										unique_id = name_location[1];
										//##### NOTE ######### 
										//=>FORMAT1 = PLANT AND CUSTOMER
										//=>FORMAT2 = BVM TANKER
										////////FORMAT 1///////////						
										if(format.equals("1")) 			// FORMAT=1 ( CUSTOMER AND PLANT )
										{								
											//READ REPORT FILE								
											if(sequence.equals("1"))		//IF SEQUENCE=1 (DISTANCE FILE) 
											{
												alert_variable_plant_customer av_pc = new alert_variable_plant_customer();
												
												System.out.println("av_pc="+av_pc+" ,extension="+extension);
												
												if(extension.equals("csv"))
												{  
													///#### INCLUDE READ STATION CSV FILE
													try{
														//read_write_excel_file2 rw = new read_write_excel_file();
														read_plant_customer_input.readCSVFile(read_excel_filepath, av_pc);
													} catch(Exception r1) {System.out.println("Err:Format1"+r1.getMessage());}
													//include_once("read_station_csv.php");
													//////////////////////////////////////////////////
												}
												else if(extension.equals("xls"))
												{
													 ///#### INCLUDE READ STATION XLS FILE		
													try{
														//read_write_excel_file rw = new read_write_excel_file();
														read_plant_customer_input.readXLSFile(read_excel_filepath, av_pc);
													} catch(Exception r2) {System.out.println("Err:Format2"+r2.getMessage());}
													//writeXLSFile();				
													//include_once("read_station_xls.php");
													//////////////////////////////////////////////////
												}
												else if(extension.equals("xlsx"))
												{
													///#### INCLUDE READ STATION XLSX FILE *******					
													try{
														//System.out.println("Before Read");
														//read_write_excel_file rw = new read_write_excel_file();
														read_plant_customer_input.readXLSXFile(read_excel_filepath, av_pc);
														//System.out.println("After Read");
													} catch(Exception r3) {System.out.println("Err:Format3"+r3.getMessage());}
													//writeXLSXFile();
													//include_once("read_station_xlsx.php");
													///////////////////////////////////////////////////////////////
												}	
												
												//1.CALL CUSTOMER FILE TO GENERATE CUSTOMER REPORT								
												try{
													action_report_customer.get_report(account_id, date_folder, date_folder_path, file_name, file_path, file_name_actual, format, sequence, av_pc, extension);
												}catch(Exception e) {System.out.println("Err:Calling customer_action");}
												
												//2.CALL PLANT FILE TO GENERATE CUSTOMER REPORT
												try{
													action_report_plant.get_report(account_id, date_folder, date_folder_path, file_name, file_path, file_name_actual, format, sequence, av_pc, extension);
												}catch(Exception e) {System.out.println("Err:Calling plant_action");}
											} // IF SEQUENCE CLOSED
											
											String upload_file_path = "/var/www/html/vts/beta/src/php/gps_report/"+account_id+"/upload/"+date_folder+"/"+file_name;				
											//String upload_file_path = "C:\\gps_report/"+account_id+"/upload/"+date_folder+"/"+file_name;										
											
											sort_xml.deleteFile(upload_file_path);// DELETE UNSORTED TMP FILE								
										}  // FORMAT 1 CLOSED																		
										else if(format.equals("2"))		// FORMAT=2 (BVM)				
										{							
											//CALL BVM CLASS FILE
											alert_variable_bvm av_bvm = new alert_variable_bvm();								
											action_report_bvm.get_report(account_id, date_folder, date_folder_path, file_name, file_path, file_name_actual, format, sequence, unique_id, av_bvm, extension);
											
										}  // FORMAT 2 CLOSED														
										
									} // IF IS FILE CLOSED
								} //FILE LOOP CLOSED 
							} catch(Exception em3) { System.out.println("Exception:GPS_REPORT_ACTION:FILE LOOP");}
						} // IF CURRENT DATE FOLDER FOUND (SERVERTIME=DATE FOLDER)
					} // DATE DIR LOOP CLOSED
				} catch(Exception em2) { System.out.println("Exception:GPS_REPORT_ACTION:DATE DIR LOOP");}
			} // ACCOUNT DIR LOOP CLOSED
		} catch(Exception em1) { System.out.println("Exception:GPS_REPORT_ACTION:ACCOUNT_DIR-GPS_REPORT_ACTION");}
	}  // MAIN CLOSED
}
