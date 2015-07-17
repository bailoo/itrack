import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
//import java.text.ParseException;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Iterator;

import org.apache.poi.hssf.usermodel.HSSFCell;
//import org.apache.poi.hssf.usermodel.HSSFDateUtil;
import org.apache.poi.hssf.usermodel.HSSFRow;
import org.apache.poi.hssf.usermodel.HSSFSheet;
import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.hssf.util.CellReference;
//import org.apache.poi.ss.usermodel.Cell;
//import org.apache.poi.ss.usermodel.CellValue;
//import org.apache.poi.ss.usermodel.DataFormatter;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.DateUtil;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;
//import org.apache.poi.ss.usermodel.FormulaEvaluator;
import org.apache.poi.xssf.usermodel.XSSFCell;
import org.apache.poi.xssf.usermodel.XSSFRow;
import org.apache.poi.xssf.usermodel.XSSFSheet;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;


public class read_bvm_input {

	//#### READ CSV
	public static void readCSVFile1(String file_path, alert_variable_bvm av) throws IOException {	
		 //String writeString = "";		
		int i=0, customer_no1=0;
		String date1="", record="", time1="", time2="";
		String vname_tmp1="", customer_time="", vname_tmp_str="";		
		String trip_date="", dcsm_name="", route="", vehicle_name1="",	activity_time1="", activity_time2="";
		
		//System.out.println("csv1:"+file_path);
		//Read code	
		 try{
			  //Open the file that is the first 
			  //FileInputStream fstream = new FileInputStream("C:\\input.csv");
			  FileInputStream fstream = new FileInputStream(file_path);
			  // Get the object of DataInputStream
			  DataInputStream in = new DataInputStream(fstream);
			  BufferedReader br = new BufferedReader(new InputStreamReader(in));
			  String strLine;
			  //Read File Line By Line
			  //System.out.println("csv2");
			  while ((strLine = br.readLine()) != null) {
				  
					/*customer_no1 = 0;
					date1="";
					record="";
					time1="";					
					time2="";					
					vname_tmp1="";
					customer_time ="";*/
					
					trip_date ="";
					dcsm_name ="";
					route ="";
					vehicle_name1 ="";
					activity_time1 ="";
					activity_time2 ="";
										
				  //Print the content on the console				  
				  //System.out.println("csv3");
				  String[] cell_data = strLine.split(",");
				  
				  if(i==0)
				  {					  
					  for(int c=0;c < cell_data.length;c++)
					  {
						 //System.out.println(cell_data[c]);
						 if(c==0)
							 av.stringData = av.stringData + cell_data[c];
						 else if(c<=5)
							 av.stringData = av.stringData + ","+ cell_data[c];
					  }					  
					  //writeString = writeString + "\n";
				  }
				  else
				  {
		  			for (int c=0; c < cell_data.length; c++) 
		  			{            		  				
		  				//echo "In Else=".$data[$c] . "<br />\n";		  				
		  				//customer_no1 = 0;		  					
		  				record = cell_data[c];
		  				//echo "<br>record=".$record;               
		  				if(c==0)
		  				{
		  				   trip_date = record;
						   trip_date = trip_date.replaceAll("\\.", "/");
		  				   //System.out.println("c0="+record);
		  				}
		  				else if(c==1)
		  				{
		  				   dcsm_name = record;
		  				   //System.out.println("c1="+record);
		  				}
		  				else if(c==2)
		  				{
		  				   route = record;
		  				   //System.out.println("c2="+record);
		  				}                                		  				
		  				else if(c==3)
		  				{
		  				   vehicle_name1 = record;
		  				   //System.out.println("c3="+record);
		  				   //echo "<br>vname=".$vname1;
		  				}		  				            
		  				else if(c==4)
		  				{
		  					activity_time1 = record;
			  				//System.out.println("c4="+record);		  				 
		  				}			  				
		  				else if(c==5)
		  				{
		  				   //customer_no1 = intval(record);
		  				   activity_time2 = record;
		  				   //System.out.println("c5="+record+ ",C="+customer_no1);
		  				   //echo "<br>customer_no1=".$customer_no1;
		  				}		  				
		  			}	// for loop closed				  
				 } //ELSE CLOSED				  
				//i++;
				  				
				///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
				//FIRST DATE        
				
				if(!trip_date.equals(""))
				{				
					String[] datetmp_a1 = new String[3];					
					//System.out.println("csv4="+date1);
					
					if(trip_date.indexOf("-") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = trip_date.split("-");
					}
					else if(trip_date.indexOf("/") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = trip_date.split("/");
					}
									
					//System.out.println("csv5="+datetmp_a1[0]+"-"+datetmp_a1[1]+"-"+datetmp_a1[2]);
					
					//DATE
					String year1 = datetmp_a1[2];
					int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
					int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
					String[] time_a1 = activity_time1.split(":");
					int tmp_hr1_int = Integer.parseInt(time_a1[0]);
					int tmp_min1_int = Integer.parseInt(time_a1[1]);
					int tmp_sec1_int = Integer.parseInt(time_a1[2]);
									
					String tmp_month1="",tmp_day1="",tmp_hr1="",tmp_min1="",tmp_sec1="";
					
					//System.out.println("csv6");
					if(tmp_month1_int < 10)
					{
					  tmp_month1 = "0"+tmp_month1_int;
					}
					else
					{
						tmp_month1 = ""+tmp_month1_int;
					}
					if(tmp_day1_int < 10)
					{
					  tmp_day1 = "0"+tmp_day1_int;
					}
					else
					{
						tmp_day1 = ""+tmp_day1_int;
					}
					//TIME1
					if(tmp_hr1_int < 10)
					{
					  tmp_hr1 = "0"+tmp_hr1_int;
					}
					else
					{
						tmp_hr1 = ""+tmp_hr1_int;
					}
					if(tmp_min1_int < 10)
					{
					  tmp_min1 = "0"+tmp_min1_int;
					}
					else
					{
						tmp_min1 = ""+tmp_min1_int;
					}
					if(tmp_sec1_int < 10)
					{
					  tmp_sec1 = "0"+tmp_sec1_int;
					}
					else
					{
						tmp_sec1 = ""+tmp_sec1_int;
					}
				
					String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
					String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
					String time_str1 = tmp_hr1+":"+tmp_min1+":"+tmp_sec1;
																						
					/////////////////////////////////////////////////////////////
					//System.out.println("date_str_display1 in CSV="+date_str_display1+" ,tmp_day1_int="+tmp_day1_int);	
					//System.out.println("csv8");
					
					//TIME2       					      			
					String[] time_a2 = activity_time2.split(":");
					int tmp_hr2_int = Integer.parseInt(time_a2[0]);
					int tmp_min2_int = Integer.parseInt(time_a2[1]);
					int tmp_sec2_int = Integer.parseInt(time_a2[2]);
									
					String tmp_month2="",tmp_day2="",tmp_hr2="",tmp_min2="",tmp_sec2="";
					
					if(tmp_hr2_int < 10)
					{
					  tmp_hr2 = "0"+tmp_hr2_int;
					}
					else
					{
						tmp_hr2 = ""+tmp_hr2_int;
					}
					if(tmp_min2_int < 10)
					{
					  tmp_min2 = "0"+tmp_min2_int;
					}
					else
					{
						tmp_min2 = ""+tmp_min2_int;
					}
					if(tmp_sec2_int < 10)
					{
					  tmp_sec2 = "0"+tmp_sec2_int;
					}
					else
					{
						tmp_sec2 = ""+tmp_sec2_int;
					}
															  
					String time_str2 = tmp_hr2+":"+tmp_min2+":"+tmp_sec2;	
																	
					String date1_tmp = date_str1+" "+time_str1;
					String date2_tmp = date_str1+" "+time_str2;

					//######### ADD NEXT DATE #########
					String datetmp = "";
					
					long date1_sec = utility_classes.get_seconds(date1_tmp, 2);
					long date2_sec = utility_classes.get_seconds(date2_tmp, 2);
					
					if(date2_sec < date1_sec)			//GET NEXT DATE IF ACTIVITY TIME IN IS SMALLER THAN ACTIVITY TIME OUT
					{						
						try{	
							SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
							Calendar c1 = Calendar.getInstance();
							c1.setTime(sdf.parse(date_str1));
							c1.add(Calendar.DATE, 1);  // number of days to add
							datetmp = sdf.format(c1.getTime());
							
							date2_tmp = datetmp+" "+time_str2;
						}catch(Exception ec) {System.out.println("Err: Check Next Date="+ec.getMessage());}
					}
					//#################################
					
					av.trip_date.add(date_str_display1);
					av.input_trip_date1.add(date1_tmp);
					av.input_trip_date2.add(date2_tmp);
					av.dcsm_name.add(dcsm_name);
					av.vehicle_route1.add(route);
					av.vehicle_name1.add(vehicle_name1);	
					av.activity_time1.add(time_str1);	  	  			
					av.activity_time2.add(time_str2);
					  
					if(i==1)
					{
					   vname_tmp_str = vname_tmp_str+"'"+vehicle_name1+"'";
					}
					else
					{
					   vname_tmp_str = vname_tmp_str+",'"+vehicle_name1+"'";
					}
	  			} // IF TRIP DATE CLOSED
	  			i++;  
			 }  // WHILE CLOSED			  			  			  			  
			  //Close the input stream
			  in.close();
			  			  
			//##### STORE IMEI				
			Connection con = null;
			Statement stmt1 = null,stmt2 = null;
			ResultSet res1 = null, res2 = null;
			
			//utility_classes util = new utility_classes();
			con = utility_classes.get_connection();
			
			String query = "SELECT DISTINCT device_imei_no,vehicle_id FROM vehicle_assignment WHERE "+
			"vehicle_id IN(SELECT vehicle_id FROM vehicle WHERE vehicle_name IN("+vname_tmp_str+") AND status=1)"+
			" AND status=1"; 
			
			//System.out.println(query);
			
			try{
				stmt1 = con.createStatement();
				res1 = stmt1.executeQuery(query);	
				
				String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="";
				while(res1.next())
				{
					imei_db = res1.getString("device_imei_no");
					av.imei1_db.add(imei_db);
	
					tmp_vid = res1.getString("vehicle_id");
					//av.vid.add(tmp_vid);
					
					query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id="+tmp_vid+"";	
	
					stmt2 = con.createStatement();
					res2 = stmt2.executeQuery(query2);
					
					if(res2.next())
					{
						tmp_vname1 = res2.getString("vehicle_name");
						av.vname1_db.add(tmp_vname1);
					}				
				}
			}catch (Exception e) { System.out.println("EXCEPTION IN GETTING ASSIGNED IMEI:"+e.getMessage()); }		
			  
		}catch (Exception e1){//Catch exception if any
			System.err.println("Error: " + e1.getMessage());}
				
		/*
		//#######  WRITE CODE-COMMENTED 
		  try{
			  // Create file 
			  FileWriter fstream = new FileWriter("C:\\output.csv");
			  BufferedWriter out = new BufferedWriter(fstream);			  
			  out.write(writeString);
			  //Close the output stream
			  out.close();
		  }catch (Exception e2){//Catch exception if any
			  System.err.println("Error: " + e2.getMessage());}	
		*/	  
	}
	
	//#### READ XLS
	public static void readXLSFile1(String file_path, alert_variable_bvm av) throws IOException {
		
		//System.out.println("READ_XLS_FILE1="+file_path);
		int r=0, c=0;
		String date1="", record="", vname_tmp_str="";
		//String vname_tmp1="", customer_time="";
		String trip_date="", dcsm_name="", route="", vehicle_no="",	activity_time1="", activity_time2="";
		
		//InputStream ExcelFileToRead = new FileInputStream("C:/Test.xls");		
		InputStream ExcelFileToRead = new FileInputStream(file_path);
		HSSFWorkbook wb = new HSSFWorkbook(ExcelFileToRead);
	
		HSSFSheet sheet=wb.getSheetAt(0);
		HSSFRow row;
		HSSFCell cell;
	
		Iterator rows = sheet.rowIterator();

		r=0;
		
		while (rows.hasNext())
		{
			/*customer_no1 = 0;
			date1="";
			record="";
			time1="";			
			time2="";			
			vname_tmp1="";*/
			
			trip_date="";
			dcsm_name="";
			route="";
			vehicle_no="";
			activity_time1="";
			activity_time2="";			
			
			row=(HSSFRow) rows.next();
			Iterator cells = row.cellIterator();
			c=0;
			
			while (cells.hasNext())
			{									
				cell=(HSSFCell) cells.next();

				//INCLUDED CODE FOR CONVERTING FLOAT TIME TO HH:MM:SS
				CellReference cellRef = new CellReference(row.getRowNum(), cell.getColumnIndex());
				///////////////////////////				
				
				int type =0;
				
				if (cell.getCellType() == HSSFCell.CELL_TYPE_STRING)
				{
					//System.out.print(cell.getStringCellValue()+" ");
					type=1;
				}
				else if(cell.getCellType() == HSSFCell.CELL_TYPE_NUMERIC)
				{
					//System.out.print(cell.getNumericCellValue()+" ");
					type=2;
				}
				else
				{
					//U Can Handel Boolean, Formula, Errors
				}
				
				String tmp_str = "";				
				String cell_data = "";
				
				if(type ==1)
				{
					tmp_str = cell.getStringCellValue();
					//System.out.println("StrVal="+tmp_str);
					cell_data = tmp_str;
				}
				else if(type==2)
				{
					tmp_str = String.valueOf(cell.getNumericCellValue());
					//System.out.println("NumericVal="+tmp_str);
					
					/*CellValue cValue = formulaEv.evaluate(cell);
					double dv = cValue.getNumberValue();
					if (HSSFDateUtil.isCellDateFormatted(cell)) {
					    Date date = HSSFDateUtil.getJavaDate(dv);

					    String dateFmt = cell.getCellStyle().getDataFormatString();
					    // strValue = new SimpleDateFormat(dateFmt).format(date); - won't work as 
					   // Java fmt differs from Excel fmt. If Excel date format is mm/dd/yyyy, Java 
					   // will always be 00 for date since "m" is minutes of the hour.

					    strValue = new CellDateFormatter(dateFmt).format(date); 
					    // takes care of idiosyncrasies of Excel
					}*/
					
					cell_data = tmp_str;
				}
				
				//System.out.println("r="+r+" ,cell_data="+cell_data);
				
				if(r==0)
				{					  
					//System.out.println("RECCCCC_IF="+record);
					
					if(c==0)
						 av.stringData = av.stringData + cell_data;
					 else if(c<=5)
						 av.stringData = av.stringData + ","+ cell_data;
				}
				else
				{
					record = cell_data;
					//System.out.println("RECCCCC_ELSE="+record);
					
					if(c==0)
					{
						//date1 = record;							  
						/*if (DateUtil.isCellDateFormatted(cell))
						{
						   try {
						
						    SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
						    trip_date = sdf.format(cell.getDateCellValue());
						
						   } catch (Exception e2) {
						        e2.printStackTrace();
						   }
						}*/
						
						trip_date = record;
						trip_date = trip_date.replaceAll("\\.", "/");
						//System.out.println("trip_date="+trip_date);						  						  
					}
					else if(c==1)
					{
					  dcsm_name = record;
					  //System.out.println("c1="+record);					  
					}						                 										
					else if(c==2)
					{
					  route = record;
					  //System.out.println("c2="+record);					  
					}
					else if(c==3)
					{
					  vehicle_no = record;
					  //System.out.println("c3="+record);					  
					}					
					else if(c==4)
					{
					    //time1 = record;
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								activity_time1 = formatter.format(time1tmp);
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}				
						
						//System.out.println("C4="+activity_time2);
					}
					else if(c==5)
					{
					   //time1 = record;
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								activity_time2 = formatter.format(time1tmp);
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}										
						//System.out.println("C5="+activity_time1);
					}														
					
				} //ELSE CLOSED				  
				
				c++;	
			}	//INNER WHILE CLOSED
				
 
			///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
			//FIRST DATE        						
			if(!trip_date.equals(""))
			{
				String[] datetmp_a1 = new String[3];
										
				if(trip_date.indexOf("-") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = trip_date.split("-");
				}
				else if(trip_date.indexOf("/") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = trip_date.split("/");
				}
	          
				//System.out.println("date[0]="+datetmp_a1[0]+" ,date[1]="+datetmp_a1[1]+" ,date[2]="+datetmp_a1[2]);
				           
				//DATE
				String year1 = datetmp_a1[2];
	  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
	  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
				
	  			//TIME1
	  			String[] time_a1 = activity_time1.split(":");
				int tmp_hr1_int = Integer.parseInt(time_a1[0]);
				int tmp_min1_int = Integer.parseInt(time_a1[1]);
				int tmp_sec1_int = Integer.parseInt(time_a1[2]);
						        
				String tmp_month1="",tmp_day1="",tmp_hr1="",tmp_min1="",tmp_sec1="";
				
				if(tmp_month1_int < 10)
				{
				  tmp_month1 = "0"+tmp_month1_int;
				}
				else
				{
					tmp_month1 = ""+tmp_month1_int;
				}
				if(tmp_day1_int < 10)
				{
				  tmp_day1 = "0"+tmp_day1_int;
				}
				else
				{
					tmp_day1 = ""+tmp_day1_int;
				}
				if(tmp_hr1_int < 10)
				{
				  tmp_hr1 = "0"+tmp_hr1_int;
				}
				else
				{
					tmp_hr1 = ""+tmp_hr1_int;
				}
				if(tmp_min1_int < 10)
				{
				  tmp_min1 = "0"+tmp_min1_int;
				}
				else
				{
					tmp_min1 = ""+tmp_min1_int;
				}
				if(tmp_sec1_int < 10)
				{
				  tmp_sec1 = "0"+tmp_sec1_int;
				}
				else
				{
					tmp_sec1 = ""+tmp_sec1_int;
				}
	        
				String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
				String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
				String time_str1 = tmp_hr1+":"+tmp_min1+":"+tmp_sec1;
				                                         			                				
				/////////////////////////////////////////////////////////////
				
				//TIME2        
				String[] time_a2 = activity_time2.split(":");
				int tmp_hr2_int = Integer.parseInt(time_a2[0]);
				int tmp_min2_int = Integer.parseInt(time_a2[1]);
				int tmp_sec2_int = Integer.parseInt(time_a2[2]);
						        
				String tmp_hr2="",tmp_min2="",tmp_sec2="";
										
				if(tmp_hr2_int < 10)
				{
				  tmp_hr2 = "0"+tmp_hr2_int;
				}
				else
				{
					tmp_hr2 = ""+tmp_hr2_int;
				}
				if(tmp_min2_int < 10)
				{
				  tmp_min2 = "0"+tmp_min2_int;
				}
				else
				{
					tmp_min2 = ""+tmp_min2_int;
				}
				if(tmp_sec2_int < 10)
				{
				  tmp_sec2 = "0"+tmp_sec2_int;
				}
				else
				{
					tmp_sec2 = ""+tmp_sec2_int;
				}
	              
				String time_str2 = tmp_hr2+":"+tmp_min2+":"+tmp_sec2;												
	  			/////////////////////////////////////////////////////////////////////			        				
				//System.out.println("date_str_display1="+date_str_display1+" ,time_str1="+time_str1+" ,date_str_display2="+date_str_display2+" ,time_str2="+time_str2);												
				
				String date1_tmp = date_str1+" "+time_str1;
				String date2_tmp = date_str1+" "+time_str2;
				
				//######### ADD NEXT DATE #########
				String datetmp = "";
				
				long date1_sec = utility_classes.get_seconds(date1_tmp, 2);
				long date2_sec = utility_classes.get_seconds(date2_tmp, 2);
				
				//System.out.println("date1_tmp="+date1_tmp+" ,date2_tmp="+date2_tmp);
				//System.out.println("date1_sec="+date1_sec+" ,date2_sec="+date2_sec);
				
				if(date2_sec < date1_sec)			//GET NEXT DATE IF ACTIVITY TIME IN IS SMALLER THAN ACTIVITY TIME OUT
				{						
					//System.out.println("Second is less: date1_tmp="+date1_tmp+" ,date2_tmp="+date2_tmp);
					try{	
						SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
						Calendar c1 = Calendar.getInstance();
						c1.setTime(sdf.parse(date_str1));
						c1.add(Calendar.DATE, 1);  // number of days to add
						datetmp = sdf.format(c1.getTime());
						
						date2_tmp = datetmp+" "+time_str2;
						
						//System.out.println("date2_tmpA=" +date2_tmp);
					}catch(Exception ec) {System.out.println("Err: Check Next Date="+ec.getMessage());}
				}
				//#################################
				
				//System.out.println("date2_tmpB=" +date2_tmp);
				
				av.trip_date.add(date_str_display1);
				av.input_trip_date1.add(date1_tmp);
				av.input_trip_date2.add(date2_tmp);
				
				av.dcsm_name.add(dcsm_name);
				av.vehicle_route1.add(route);
				av.vehicle_name1.add(vehicle_no);	
	  			av.activity_time1.add(time_str1);	  	  			
	  			av.activity_time2.add(time_str2);
									
				if(r==1)
				{
				   vname_tmp_str = vname_tmp_str+"'"+vehicle_no+"'";
				}
				else
				{
				   vname_tmp_str = vname_tmp_str+",'"+vehicle_no+"'";
				}
				
				//System.out.println("outer closed");
			} // if trip_date closed
												
			r++;
		} //OUTER WHILE
		
		
		//##### STORE IMEI1				
		Connection con = null;
		Statement stmt1 = null,stmt2 = null;
		ResultSet res1 = null, res2 = null;
		
		//utility_classes util = new utility_classes();
		con = utility_classes.get_connection();
		
		String query = "SELECT DISTINCT device_imei_no,vehicle_id FROM vehicle_assignment WHERE "+
		"vehicle_id IN(SELECT vehicle_id FROM vehicle WHERE vehicle_name IN("+vname_tmp_str+") AND status=1)"+
		" AND status=1"; 
		
		//System.out.println(query);
		
		try{
			stmt1 = con.createStatement();
			res1 = stmt1.executeQuery(query);	
			
			String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="";
			while(res1.next())
			{
				imei_db = res1.getString("device_imei_no");
				av.imei1_db.add(imei_db);

				tmp_vid = res1.getString("vehicle_id");
				//av.vid.add(tmp_vid);
				
				query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id="+tmp_vid+"";	

				stmt2 = con.createStatement();
				res2 = stmt2.executeQuery(query2);
				
				if(res2.next())
				{
					tmp_vname1 = res2.getString("vehicle_name");
					av.vname1_db.add(tmp_vname1);
				}				
			}
		}catch (Exception e) { System.out.println("EXCEPTION IN GETTING ASSIGNED IMEI:"+e.getMessage()); }
		// STORE IMEI1 CLOSED		
	}
		
	
	//###### READ XLSX
	public static void readXLSXFile1(String file_path, alert_variable_bvm av) throws IOException
	{
		//System.out.println("DISTANCE INPUT");
		int r=0, c=0;
		String date1="", record="", vname_tmp_str="";
		//String vname_tmp1="", customer_time1="";
		String trip_date="", dcsm_name="", route="", vehicle_no="",	activity_time1="", activity_time2="";
		
		//InputStream ExcelFileToRead = new FileInputStream("C:/Test.xlsx");
		InputStream ExcelFileToRead = new FileInputStream(file_path);
		XSSFWorkbook wb = new XSSFWorkbook(ExcelFileToRead);
	
		XSSFWorkbook test = new XSSFWorkbook();
	
		XSSFSheet sheet = wb.getSheetAt(0);
		XSSFRow row;
		XSSFCell cell;
	
		Iterator rows = sheet.rowIterator();

		r =0;
		while (rows.hasNext())
		{
			trip_date ="";
			dcsm_name ="";
			route ="";
			vehicle_no ="";
			activity_time2 ="";
			activity_time1 ="";
			
			row=(XSSFRow) rows.next();
			Iterator cells = row.cellIterator();
			
			c=0;
			while (cells.hasNext())
			{
				cell=(XSSFCell) cells.next();
				
				//INCLUDED CODE FOR CONVERTING FLOAT TIME TO HH:MM:SS
				CellReference cellRef = new CellReference(row.getRowNum(), cell.getColumnIndex());
				///////////////////////////								
				int type =0;
				
				if (cell.getCellType() == XSSFCell.CELL_TYPE_STRING)
				{
					//System.out.print(cell.getStringCellValue()+" ");
					type=1;
				}
				else if(cell.getCellType() == XSSFCell.CELL_TYPE_NUMERIC)
				{
					//System.out.print(cell.getNumericCellValue()+" ");
					type=2;
				}
				else
				{
					//U Can Handel Boolean, Formula, Errors
				}
				
				String tmp_str = "", cell_data = "";				
				
				if(type ==1)
				{
					tmp_str = cell.getStringCellValue();
					cell_data = tmp_str;
				}
				else if(type==2)
				{
					tmp_str = String.valueOf(cell.getNumericCellValue());
					cell_data = tmp_str;
				}				
				
				if(r==0)
				{					  
				  //for(int c=0;c < cell_data.length;c++)
				  //{
					 //System.out.println(cell_data[c]);
					 if(c==0)
						 av.stringData = av.stringData + cell_data;
					 else if(c<=5)
						 av.stringData = av.stringData + ","+ cell_data;
				  //}					  
				  //writeString = writeString + "\n";
				}
				else
				{
					record = cell_data;
					//System.out.println("record="+record);              
					if(c==0)
					{
					  //date1 = record;
						/*System.out.println("cell.getDateCellValue()="+cell.getDateCellValue());
						if (DateUtil.isCellDateFormatted(cell))
						{							
							try {
							
							SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
							trip_date = sdf.format(cell.getDateCellValue());
							
							} catch (Exception e2) {
							        e2.printStackTrace();
							}
						}*/
						trip_date = record;
						trip_date = trip_date.replaceAll("\\.", "/");
						//System.out.println("trip_date="+trip_date);						  						  
						
					  //System.out.println("trip_date="+trip_date);
					}
					else if(c==1)
					{
					  dcsm_name = record;
					  //System.out.println("dcsm_name="+dcsm_name);					 
					}
					else if(c==2)
					{
					  route = record;
					  //System.out.println("route="+route);					 
					}					                										
					else if(c==3)
					{
					  vehicle_no = record;
					  //System.out.println("vehicle_no="+vehicle_no);					 
					}					                																									
					else if(c==4)
					{
					  //time1 = record;
						//String excel_time = get_excel_time(record);
						//time1 = excel_time;						
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								activity_time1 = formatter.format(time1tmp);
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}										
						
					  //System.out.println("activity_time2="+activity_time2);
					}
					else if(c==5)
					{						
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								activity_time2 = formatter.format(time1tmp);
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}																
					  //System.out.println("activity_time1="+activity_time1);
					}										
				} //ELSE CLOSED				  
				  
				c++;	
			} // INNER WHILE
				
			///////////MAKE INDIVIDUAL START DATE AND END DATE//////////					        
			//DATE
			
			if(!trip_date.equals(""))
			{			
				String[] datetmp_a1 = new String[2];
				
				if(trip_date.indexOf("-") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = trip_date.split("-");
				}
				else if(trip_date.indexOf("/") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = trip_date.split("/");
				}
	          
				String year1 = datetmp_a1[2];
	  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
	  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
						  			
	  			String[] time_a1 = activity_time1.split(":");
				int tmp_hr1_int = Integer.parseInt(time_a1[0]);
				int tmp_min1_int = Integer.parseInt(time_a1[1]);
				int tmp_sec1_int = Integer.parseInt(time_a1[2]);
						        
				String tmp_month1="",tmp_day1="",tmp_hr1="",tmp_min1="",tmp_sec1="";
				
				if(tmp_month1_int < 10)
				{
				  tmp_month1 = "0"+tmp_month1_int;
				}
				else
				{
					tmp_month1 = ""+tmp_month1_int;
				}
				if(tmp_day1_int < 10)
				{
				  tmp_day1 = "0"+tmp_day1_int;
				}
				else
				{
					tmp_day1 = ""+tmp_day1_int;
				}
				
				//TIME1
				if(tmp_hr1_int < 10)
				{
				  tmp_hr1 = "0"+tmp_hr1_int;
				}
				else
				{
					tmp_hr1 = ""+tmp_hr1_int;
				}
				if(tmp_min1_int < 10)
				{
				  tmp_min1 = "0"+tmp_min1_int;
				}
				else
				{
					tmp_min1 = ""+tmp_min1_int;
				}
				if(tmp_sec1_int < 10)
				{
				  tmp_sec1 = "0"+tmp_sec1_int;
				}
				else
				{
					tmp_sec1 = ""+tmp_sec1_int;
				}
	        
				String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
				String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
				String time_str1 = tmp_hr1+":"+tmp_min1+":"+tmp_sec1;
				                                         			                
				/////////////////////////////////////////////////////////////
				
				//TIME2       		          					    			
				String[] time_a2 = activity_time2.split(":");
				int tmp_hr2_int = Integer.parseInt(time_a2[0]);
				int tmp_min2_int = Integer.parseInt(time_a2[1]);
				int tmp_sec2_int = Integer.parseInt(time_a2[2]);
						        
				String tmp_hr2="",tmp_min2="",tmp_sec2="";
									
				if(tmp_hr2_int < 10)
				{
				  tmp_hr2 = "0"+tmp_hr2_int;
				}
				else
				{
					tmp_hr2 = ""+tmp_hr2_int;
				}
				if(tmp_min2_int < 10)
				{
				  tmp_min2 = "0"+tmp_min2_int;
				}
				else
				{
					tmp_min2 = ""+tmp_min2_int;
				}
				if(tmp_sec2_int < 10)
				{
				  tmp_sec2 = "0"+tmp_sec2_int;
				}
				else
				{
					tmp_sec2 = ""+tmp_sec2_int;
				}		        
				String time_str2 = tmp_hr2+":"+tmp_min2+":"+tmp_sec2;
				                                         			                
	  			/////////////////////////////////////////////////////				
				String date1_tmp = date_str1+" "+time_str1;
				String date2_tmp = date_str1+" "+time_str2;

				//######### ADD NEXT DATE #########
				String datetmp = "";
				
				long date1_sec = utility_classes.get_seconds(date1_tmp, 2);
				long date2_sec = utility_classes.get_seconds(date2_tmp, 2);
				
				if(date2_sec < date1_sec)			//GET NEXT DATE IF ACTIVITY TIME IN IS SMALLER THAN ACTIVITY TIME OUT
				{						
					try{	
						SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
						Calendar c1 = Calendar.getInstance();
						c1.setTime(sdf.parse(date_str1));
						c1.add(Calendar.DATE, 1);  // number of days to add
						datetmp = sdf.format(c1.getTime());
						
						date2_tmp = datetmp+" "+time_str2;
					}catch(Exception ec) {System.out.println("Err: Check Next Date="+ec.getMessage());}
				}
				//#################################
				
				av.trip_date.add(date_str_display1);
				av.input_trip_date1.add(date1_tmp);
				av.input_trip_date2.add(date2_tmp);
				
				av.dcsm_name.add(dcsm_name);
				av.vehicle_route1.add(route);
				av.vehicle_name1.add(vehicle_no);	
	  			av.activity_time1.add(time_str1);	  	  			
	  			av.activity_time2.add(time_str2);
								
				if(r==1)
				{
				   vname_tmp_str = vname_tmp_str+"'"+vehicle_no+"'";
				}
				else
				{
				   vname_tmp_str = vname_tmp_str+",'"+vehicle_no+"'";
				}	
					
				//System.out.println();							
			} // if trip_date closed			
			r++;
		} //OUTER WHILE
		
		
		//##### STORE IMEI				
		Connection con = null;
		Statement stmt1 = null,stmt2 = null;
		ResultSet res1 = null, res2 = null;
		
		//utility_classes util = new utility_classes();
		con = utility_classes.get_connection();
		
		String query = "SELECT DISTINCT device_imei_no,vehicle_id FROM vehicle_assignment WHERE "+
		"vehicle_id IN(SELECT vehicle_id FROM vehicle WHERE vehicle_name IN("+vname_tmp_str+") AND status=1)"+
		" AND status=1"; 
		
		//System.out.println(query);	
		
		try{
			stmt1 = con.createStatement();
			res1 = stmt1.executeQuery(query);	
			
			String query2 ="", imei_db ="",tmp_vid="",tmp_vname1="";
			while(res1.next())
			{
				imei_db = res1.getString("device_imei_no");
				av.imei1_db.add(imei_db);

				tmp_vid = res1.getString("vehicle_id");
				//av.vid.add(tmp_vid);
				
				query2 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id="+tmp_vid+"";	

				stmt2 = con.createStatement();
				res2 = stmt2.executeQuery(query2);
				
				if(res2.next())
				{
					tmp_vname1 = res2.getString("vehicle_name");
					av.vname1_db.add(tmp_vname1);
				}				
			}
		}catch (Exception e) { System.out.println("EXCEPTION IN GETTING ASSIGNED IMEI:"+e.getMessage()); }				
		//IMEI1 CLOSED		
	}
	//FORMAT1 CLOSED

	
	//#### FORMAT2 OPENS ////////////
	
	//#### READ CSV
	public static void readCSVFile2(String file_path, alert_variable_bvm av, alert_variable_bvm av_dist) throws IOException {	
		 //String writeString = "";		
		int i=0, customer_no2=0;
		String record="", billing_date="", vehicle_name2="", route="", time1="", time2="";
		String vname_tmp1="";
		//System.out.println("csv1:"+file_path);
		//Read code	
		 try{
			  //Open the file that is the first 
			  //FileInputStream fstream = new FileInputStream("C:\\input.csv");
			  FileInputStream fstream = new FileInputStream(file_path);
			  // Get the object of DataInputStream
			  DataInputStream in = new DataInputStream(fstream);
			  BufferedReader br = new BufferedReader(new InputStreamReader(in));
			  String strLine;
			  //Read File Line By Line
			  //System.out.println("csv2");
			  while ((strLine = br.readLine()) != null) {
				  				  	
				  	record="";
				  	billing_date="";
				  	route="";
				  	time1="";					
					time2="";					
					vname_tmp1="";
					customer_no2 = 0;
				  //Print the content on the console				  
				  //System.out.println("csv3");
				  String[] cell_data = strLine.split(",");
				  
				  if(i==0)
				  {					  
					  for(int c=0;c < cell_data.length;c++)
					  {
						 //System.out.println(cell_data[c]);
						 if(c==0)
							 av.stringData = av.stringData + cell_data[c];
						 else if(c<=5)
							 av.stringData = av.stringData + ","+ cell_data[c];
					  }					  
					  //writeString = writeString + "\n";
				  }
				  else
				  {
		  			for (int c=0; c < cell_data.length; c++) 
		  			{            
		  				record = cell_data[c];
		  				//echo "<br>record=".$record;               
		  				if(c==0)
		  				{
							billing_date = record;
							billing_date = billing_date.replaceAll("\\.", "/");
							//System.out.println("billing_date="+billing_date);						  						  
		  				  
		  				  //System.out.println("c0="+record);
		  				}
		  				else if(c==1)
		  				{
		  				  route = record;
		  				  //System.out.println("c1="+record);
		  				}		  					            		  					  				
		  				else if(c==2)
		  				{
		  				  //customer_no1 = intval(record);
		  					customer_no2 = Integer.parseInt(record);
		  				  //System.out.println("c9="+record+ ",C="+customer_no1);
		  				  //echo "<br>customer_no1=".$customer_no1;
		  				}		  				
		  			}					  
				 } //ELSE CLOSED				  
				//i++;
				  				
				//System.out.println("customer_no1="+customer_no1);				  
				if(customer_no2 >0)
				{  
					///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
					//FIRST DATE        
					String[] datetmp_a1 = new String[3];					
					//System.out.println("csv4="+date1);
					
					if(billing_date.indexOf("-") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = billing_date.split("-");
					}
					else if(billing_date.indexOf("/") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = billing_date.split("/");
					}
		          					
					//System.out.println("csv5="+datetmp_a1[0]+"-"+datetmp_a1[1]+"-"+datetmp_a1[2]);
					
					//DATE
					String year1 = datetmp_a1[2];
		  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
		  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       								
							        
					String tmp_month1="",tmp_day1="",tmp_hr1="",tmp_min1="",tmp_sec1="";
					
					//System.out.println("csv6");
					if(tmp_month1_int < 10)
					{
					  tmp_month1 = "0"+tmp_month1_int;
					}
					else
					{
						tmp_month1 = ""+tmp_month1_int;
					}
					if(tmp_day1_int < 10)
					{
					  tmp_day1 = "0"+tmp_day1_int;
					}
					else
					{
						tmp_day1 = ""+tmp_day1_int;
					}
							        
					String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
					String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
					String time_str1 = "00:00:00";
					String time_str2 = "23:59:59";
					                                         			                										
					String date1_tmp = date_str1+" "+time_str1;
					String date2_tmp = date_str1+" "+time_str2;
																				
					for(int j=0;j<av_dist.vehicle_route1.size();j++)
					{
						if((((String)av_dist.vehicle_route1.get(j)).equalsIgnoreCase(route)))
						{
							vehicle_name2 = (String)av_dist.vehicle_name1.get(j);
							//###### REPLACE -BILLING DATE VARIABLES WITH DISTANCE DATE VARIABLE
							date1_tmp = (String)av_dist.input_trip_date1.get(j);
							date2_tmp = (String)av_dist.input_trip_date2.get(j);
							//##################################################################
							break;
						}
					}
					//av.vehicle_name2.add(vehicle_name2);	
		  			//av.customer_no2.add(customer_no2);
		  			
		  			/////////////////		  			
					av.date1_csv.add(date_str_display1);					
		  			av.time1_csv.add(time_str1);
		  
		  			av.date2_csv.add(date_str_display1);
		  			av.time2_csv.add(time_str2);
		              
		  			av.input_date1.add(date1_tmp);
		  			av.input_date2.add(date2_tmp);
		  			//echo "<br>input date1=".$date1_tmp." ,input date2=".$date2_tmp;              
		  			///////////////********************************************////////////////          	  
		  			/*av.doctype.add(doctype1);
		  			av.plant.add(plant1);*/
		  			av.route.add(route);
		  			av.vname.add(vehicle_name2);
		  			//av.vendor_name.add(vehicle_name2);		  			
		  			av.customer_no.add(customer_no2);
		  			////////////////

				}  // IF CUSTOMER NO1 CLOSED                  					  
				i++;  
			 }  // WHILE CLOSED
			  //Close the input stream
			  in.close();
		}catch (Exception e1){//Catch exception if any
			System.err.println("Error: " + e1.getMessage());}
				
		/*
		//#######  WRITE CODE-COMMENTED 
		  try{
			  // Create file 
			  FileWriter fstream = new FileWriter("C:\\output.csv");
			  BufferedWriter out = new BufferedWriter(fstream);			  
			  out.write(writeString);
			  //Close the output stream
			  out.close();
		  }catch (Exception e2){//Catch exception if any
			  System.err.println("Error: " + e2.getMessage());}	
		*/	  
	}
	
	//#### READ XLS
	public static void readXLSFile2(String file_path, alert_variable_bvm av, alert_variable_bvm av_dist) throws IOException {
		
		int r=0, customer_no2 = 0, c=0;
		String billing_date="", record="", time1="", time2="", route="", vehicle_name2="";
		String vname_tmp1="";
		
		//InputStream ExcelFileToRead = new FileInputStream("C:/Test.xls");		
		InputStream ExcelFileToRead = new FileInputStream(file_path);
		HSSFWorkbook wb = new HSSFWorkbook(ExcelFileToRead);
	
		HSSFSheet sheet=wb.getSheetAt(0);
		HSSFRow row;
		HSSFCell cell;
	
		Iterator rows = sheet.rowIterator();

		r=0;
		
		while (rows.hasNext())
		{
			customer_no2 = 0;
			billing_date="";
			route="";
			record="";
			time1="";			
			time2="";			
			vname_tmp1="";
			
			row=(HSSFRow) rows.next();
			Iterator cells = row.cellIterator();
			c=0;
			
			while (cells.hasNext())
			{									
				cell=(HSSFCell) cells.next();

				//INCLUDED CODE FOR CONVERTING FLOAT TIME TO HH:MM:SS
				CellReference cellRef = new CellReference(row.getRowNum(), cell.getColumnIndex());
				///////////////////////////				
				
				int type =0;
				
				if (cell.getCellType() == HSSFCell.CELL_TYPE_STRING)
				{
					//System.out.print(cell.getStringCellValue()+" ");
					type=1;
				}
				else if(cell.getCellType() == HSSFCell.CELL_TYPE_NUMERIC)
				{
					//System.out.print(cell.getNumericCellValue()+" ");
					type=2;
				}
				else
				{
					//U Can Handel Boolean, Formula, Errors
				}
				
				String tmp_str = "";				
				String cell_data = "";
				
				if(type ==1)
				{
					tmp_str = cell.getStringCellValue();
					//System.out.println("StrVal="+tmp_str);
					cell_data = tmp_str;
				}
				else if(type==2)
				{
					tmp_str = String.valueOf(cell.getNumericCellValue());
					//System.out.println("NumericVal="+tmp_str);
					

					/*CellValue cValue = formulaEv.evaluate(cell);
					double dv = cValue.getNumberValue();
					if (HSSFDateUtil.isCellDateFormatted(cell)) {
					    Date date = HSSFDateUtil.getJavaDate(dv);

					    String dateFmt = cell.getCellStyle().getDataFormatString();
					    // strValue = new SimpleDateFormat(dateFmt).format(date); - won't work as 
					   // Java fmt differs from Excel fmt. If Excel date format is mm/dd/yyyy, Java 
					   // will always be 00 for date since "m" is minutes of the hour.

					    strValue = new CellDateFormatter(dateFmt).format(date); 
					    // takes care of idiosyncrasies of Excel
					}*/
					
					cell_data = tmp_str;
				}				
				//System.out.println("r="+r+" ,cell_data="+cell_data);
				
				if(r==0)
				{					  
					 if(c==0)
						 av.stringData = av.stringData + cell_data;
					 else if(c<=5)
						 av.stringData = av.stringData + ","+ cell_data;
				}
				else
				{
					record = cell_data;
					      
					if(c==0)
					{
						//date1 = record;							  
						/*if (DateUtil.isCellDateFormatted(cell))
						{
						   try {
						
						    SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
						    billing_date = sdf.format(cell.getDateCellValue());
						
						   } catch (Exception e2) {
						        e2.printStackTrace();
						   }
						}*/
						billing_date = record;
						billing_date = billing_date.replaceAll("\\.", "/");
						//System.out.println("billing_date="+billing_date);						
					 
					    //System.out.println("C0="+billing_date);						  						  
					}											
					else if(c==1)
					{
					  route = record;
					  //System.out.println("c1="+record);					  
					}						                 									
					else if(c==2)
					{					  							
					  double customer_no_tmp = Double.parseDouble(record);
					  customer_no2 = (int)customer_no_tmp;
					  //System.out.println("c2="+customer_no2);					  
					}							
				} //ELSE CLOSED				  
				 
				c++;
			}	//INNER WHILE CLOSED
				
			if(customer_no2 >0)					
			{  
				///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
				//System.out.println("Valid Customer");
				//FIRST DATE        						
				String[] datetmp_a1 = new String[3];
										
				if(billing_date.indexOf("-") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = billing_date.split("-");
				}
				else if(billing_date.indexOf("/") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = billing_date.split("/");
				}
	          
				//System.out.println("date[0]="+datetmp_a1[0]+" ,date[1]="+datetmp_a1[1]+" ,date[2]="+datetmp_a1[2]);
				           
				//DATE
				String year1 = datetmp_a1[2];
	  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
	  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
				
						        
				String tmp_month1="",tmp_day1="",tmp_hr1="",tmp_min1="",tmp_sec1="";
				
				if(tmp_month1_int < 10)
				{
				  tmp_month1 = "0"+tmp_month1_int;
				}
				else
				{
					tmp_month1 = ""+tmp_month1_int;
				}
				if(tmp_day1_int < 10)
				{
				  tmp_day1 = "0"+tmp_day1_int;
				}
				else
				{
					tmp_day1 = ""+tmp_day1_int;
				}
						        
				String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
				String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
				String time_str1 = "00:00:00";
				String time_str2 = "23:59:59";
				                                         			                										
				String date1_tmp = date_str1+" "+time_str1;
				String date2_tmp = date_str1+" "+time_str2;
																			
				//System.out.println("vroute_size="+av_dist.vehicle_route1.size());
				for(int j=0;j<av_dist.vehicle_route1.size();j++)
				{
					//System.out.println("Vroute="+((String)av_dist.vehicle_route1.get(j))+" ,Route"+route);
					
					if((((String)av_dist.vehicle_route1.get(j)).equalsIgnoreCase(route)))
					{
						//System.out.println("MatchFound-distance_billing route");
						vehicle_name2 = (String)av_dist.vehicle_name1.get(j);
						
						//###### REPLACE -BILLING DATE VARIABLES WITH DISTANCE DATE VARIABLE
						date1_tmp = (String)av_dist.input_trip_date1.get(j);
						date2_tmp = (String)av_dist.input_trip_date2.get(j);
						//##################################################################
						
						break;
					}
				}
				//av.vehicle_name2.add(vehicle_name2);	
	  			//av.customer_no2.add(customer_no2);
	  			
	  			/////////////////		  			
				av.date1_csv.add(date_str_display1);					
	  			av.time1_csv.add(time_str1);
	  
	  			av.date2_csv.add(date_str_display1);
	  			av.time2_csv.add(time_str2);
	              
	  			av.input_date1.add(date1_tmp);
	  			av.input_date2.add(date2_tmp);
	  			///////////////********************************************////////////////          	  
	  			/*av.doctype.add(doctype1);
	  			av.plant.add(plant1);*/
	  			av.route.add(route);
	  			av.vname.add(vehicle_name2);
	  			//av.vendor_name.add(vehicle_name2);		  			
	  			av.customer_no.add(customer_no2);
			}  // IF CUSTOMER NO1 CLOSED  					
		
			//System.out.println("outer closed");
			r++;
		} //OUTER WHILE
	}
		
	
	//###### READ XLSX
	public static void readXLSXFile2(String file_path, alert_variable_bvm av, alert_variable_bvm av_dist) throws IOException
	{
		//System.out.println("BILLING_INPUT");
		int r=0, c=0, customer_no2 = 0;
		String billing_date="", record="", time1="", time2="", route="", vehicle_name2="";
		String vname_tmp1="";
		
		//InputStream ExcelFileToRead = new FileInputStream("C:/Test.xlsx");
		InputStream ExcelFileToRead = new FileInputStream(file_path);
		XSSFWorkbook wb = new XSSFWorkbook(ExcelFileToRead);
	
		XSSFWorkbook test = new XSSFWorkbook();
	
		XSSFSheet sheet = wb.getSheetAt(0);
		XSSFRow row;
		XSSFCell cell;
	
		Iterator rows = sheet.rowIterator();

		r =0;
		while (rows.hasNext())
		{
			customer_no2 = 0;
			billing_date="";
			record="";
			route="";
			time1="";			
			time2="";			
			vname_tmp1="";			
			
			row=(XSSFRow) rows.next();
			Iterator cells = row.cellIterator();
			
			c=0;
			while (cells.hasNext())
			{
				cell=(XSSFCell) cells.next();
				
				//INCLUDED CODE FOR CONVERTING FLOAT TIME TO HH:MM:SS
				CellReference cellRef = new CellReference(row.getRowNum(), cell.getColumnIndex());
				///////////////////////////								
				int type =0;
				
				if (cell.getCellType() == XSSFCell.CELL_TYPE_STRING)
				{
					//System.out.print(cell.getStringCellValue()+" ");
					type=1;
				}
				else if(cell.getCellType() == XSSFCell.CELL_TYPE_NUMERIC)
				{
					//System.out.print(cell.getNumericCellValue()+" ");
					type=2;
				}
				else
				{
					//U Can Handel Boolean, Formula, Errors
				}
				
				String tmp_str = "", cell_data = "";				
				
				if(type ==1)
				{
					tmp_str = cell.getStringCellValue();
					cell_data = tmp_str;
				}
				else if(type==2)
				{
					tmp_str = String.valueOf(cell.getNumericCellValue());
					cell_data = tmp_str;
				}				
				
				if(r==0)
				{					  
					 //System.out.println(cell_data[c]);
					 if(c==0)
						 av.stringData = av.stringData + cell_data;
					 else if(c<=5)
						 av.stringData = av.stringData + ","+ cell_data;
					 //}					  
					 //writeString = writeString + "\n";
				}
				else
				{						
					record = cell_data;
					//System.out.println("record="+record);              
					if(c==0)
					{
					  //date1 = record;
						/*System.out.println("cell.getDateCellValue()="+cell.getDateCellValue());
						if (DateUtil.isCellDateFormatted(cell))
						{							
							try {
							
							SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
							billing_date = sdf.format(cell.getDateCellValue());
							
							} catch (Exception e2) {
							        e2.printStackTrace();
							}
						}*/
						billing_date = record;
						billing_date = billing_date.replaceAll("\\.", "/");
						//System.out.println("billing_date="+billing_date);						  						  
						
					  //System.out.println("date1="+billing_date);
					}										       					
					else if(c==1)
					{
					  route = record;
					  //System.out.println("route="+route);					 
					}					                					
					else if(c==2)
					{
					  //customer_no1 = intval(record);
					  double customer_no_tmp = Double.parseDouble(record);
					  customer_no2 = (int)customer_no_tmp;
				
					  //System.out.println("customer_no2="+customer_no2);
					}		
				} //ELSE CLOSED				  				  				
				//System.out.println("Row="+r+" ,CT1="+customer_time);				
				c++;								
			} // INNER WHILE
			
			if(customer_no2 >0)
			{  					
				///////////MAKE INDIVIDUAL START DATE AND END DATE//////////					        
				//DATE
				String[] datetmp_a1 = new String[2];
				
				if(billing_date.indexOf("-") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = billing_date.split("-");
				}
				else if(billing_date.indexOf("/") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = billing_date.split("/");
				}
	          
				String year1 = datetmp_a1[2];
	  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
	  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
						  			
				String tmp_month1="",tmp_day1="",tmp_hr1="",tmp_min1="",tmp_sec1="";
				
				if(tmp_month1_int < 10)
				{
				  tmp_month1 = "0"+tmp_month1_int;
				}
				else
				{
					tmp_month1 = ""+tmp_month1_int;
				}
				if(tmp_day1_int < 10)
				{
				  tmp_day1 = "0"+tmp_day1_int;
				}
				else
				{
					tmp_day1 = ""+tmp_day1_int;
				}
	        
				String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
				String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
				String time_str1 = "00:00:00";
				String time_str2 = "23:59:59";
				                                         			                										
				String date1_tmp = date_str1+" "+time_str1;
				String date2_tmp = date_str1+" "+time_str2;
																			
				//System.out.println("vroute_size="+av_dist.vehicle_route1.size());
				for(int j=0;j<av_dist.vehicle_route1.size();j++)
				{
					//System.out.println("AV_DIST="+(String)av_dist.vehicle_route1.get(j)+" ,route="+route);
					
					if((((String)av_dist.vehicle_route1.get(j)).equalsIgnoreCase(route)))
					{
						//System.out.println("MatchFound-distance_billing route");
						vehicle_name2 = (String)av_dist.vehicle_name1.get(j);
						
						//###### REPLACE -BILLING DATE VARIABLES WITH DISTANCE DATE VARIABLE
						date1_tmp = (String)av_dist.input_trip_date1.get(j);
						date2_tmp = (String)av_dist.input_trip_date2.get(j);
						//##################################################################
						
						break;
					}
				}
				//av.vehicle_name2.add(vehicle_name2);	
	  			//av.customer_no2.add(customer_no2);
	  			
	  			//System.out.println("DIST INPUT::av_dist.vehicle_route1.size()="+av_dist.vehicle_route1.size()+" ,ROUTE="+route+" ,DIST_VNAME2="+vehicle_name2);
				/////////////////		  			
				av.date1_csv.add(date_str_display1);					
	  			av.time1_csv.add(time_str1);
	  
	  			av.date2_csv.add(date_str_display1);
	  			av.time2_csv.add(time_str2);
	              
	  			av.input_date1.add(date1_tmp);
	  			av.input_date2.add(date2_tmp);
	  			//echo "<br>input date1=".$date1_tmp." ,input date2=".$date2_tmp;              
	  			///////////////********************************************////////////////          	  
	  			/*av.doctype.add(doctype1);
	  			av.plant.add(plant1);*/
	  			av.route.add(route);
	  			av.vname.add(vehicle_name2);
	  			//av.vendor_name.add(vehicle_name2);		  			
	  			av.customer_no.add(customer_no2); 			
			}  // IF CUSTOMER NO1 CLOSED
							
			//System.out.println();
			r++;
		} //OUTER WHILE
	}
	//FORMAT2 CLOSED	
	//##############################

	/// EXCEL DATE
	public static String get_excel_date(String excel_date)
	{
		try {		
				String Request = "http://www.itracksolution.co.in/src/php/get_excel_date.php?excel_date="+excel_date;
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
	}
	
	/// EXCEL TIME
	public static String get_excel_time(String excel_time)
	{
		try {		
				String Request = "http://www.itracksolution.co.in/src/php/get_excel_time.php?excel_time="+excel_time;
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
	}	
}
