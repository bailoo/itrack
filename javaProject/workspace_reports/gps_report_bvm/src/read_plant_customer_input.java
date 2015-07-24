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
import java.text.SimpleDateFormat;
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


public class read_plant_customer_input {

	//######################################################################################################################
	//############################################# READ REPORT EXCEL ######################################################
	//###################################################################################################################### 
	
	//#### READ CSV
	public static void readCSVFile(String file_path, alert_variable_plant_customer av) throws IOException {	
		 //String writeString = "";		
		int i=0, customer_no1=0;
		String date1="", record="", time1="", date2="", time2="";
		String doctype1="",plant1="",route1="",vname_tmp1="",vendor_name1="";
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
				  
					customer_no1 = 0;
					date1="";
					record="";
					time1="";
					date2="";
					time2="";
					doctype1="";
					plant1="";
					route1="";
					vname_tmp1="";
					vendor_name1="";
				  
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
						 else if(c<=9)
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
		  				  date1 = record;
		  				  //System.out.println("c0="+record);
		  				}
		  				else if(c==1)
		  				{
		  				  time1 = record;
		  				  //System.out.println("c1="+record);
		  				}
		  				else if(c==2)
		  				{
		  				  date2 = record;
		  				  //System.out.println("c2="+record);
		  				}
		  				else if(c==3)
		  				{
		  				  time2 = record;
		  				  //System.out.println("c3="+record);
		  				}                                
		  				else if(c==4)
		  				{
		  				  doctype1 = record;
		  				  //System.out.println("c4="+record);
		  				}
		  				else if(c==5)
		  				{
		  				  plant1 = record;
		  				  //System.out.println("c5="+record);
		  				}
		  				else if(c==6)
		  				{
		  				  route1 = record;
		  				  //System.out.println("c6="+record);
		  				}
		  				else if(c==7)
		  				{
		  				  vname_tmp1 = record;
		  				  //System.out.println("c7="+record);
		  				  //echo "<br>vname=".$vname1;
		  				}
		  				else if(c==8)
		  				{
		  				  vendor_name1 = record;
		  				  //System.out.println("c8="+record);
		  				}                     
		  				else if(c==9)
		  				{
		  				  //customer_no1 = intval(record);
		  				  customer_no1 = Integer.parseInt(record);
		  				  //System.out.println("c9="+record+ ",C="+customer_no1);
		  				  //echo "<br>customer_no1=".$customer_no1;
		  				}
		  				/*else if($c==10)
		  				{
		  				  $qty1 = $record;
		  				}
		  				else if($c==11)
		  				{
		  				  $unit1 = $record;
		  				}*/                                
		  			} //for closed					  
				 } //ELSE CLOSED				  
				//i++;
				  
				
				//System.out.println("customer_no1="+customer_no1);
				  
				if(customer_no1 >0)
				{  
					///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
					//FIRST DATE        
					String[] datetmp_a1 = new String[3];
					
					//System.out.println("csv4="+date1);
					
					if(date1.indexOf("-") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = date1.split("-");
					}
					else if(date1.indexOf("/") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = date1.split("/");
					}
		          					
					//System.out.println("csv5="+datetmp_a1[0]+"-"+datetmp_a1[1]+"-"+datetmp_a1[2]);
					
					String year1 = datetmp_a1[2];
		  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
		  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
					String[] time_a1 = time1.split(":");
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
		        
					//System.out.println("csv7");
					String date_str_display1 = tmp_day1+"-"+tmp_month1+"-"+year1;
					String date_str1 = year1+"-"+tmp_month1+"-"+tmp_day1;        
					String time_str1 = tmp_hr1+":"+tmp_min1+":"+tmp_sec1;
					                                         			                
					String date1_tmp = date_str1+" "+time_str1;
					/////////////////////////////////////////////////////////////
					//System.out.println("date_str_display1 in CSV="+date_str_display1+" ,tmp_day1_int="+tmp_day1_int);	
					//System.out.println("csv8");
					
					//SECOND DATE        
					String[] datetmp_a2 = new String[2];
					
					if(date2.indexOf("-") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a2 = date2.split("-");
					}
					else if(date2.indexOf("/") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a2 = date2.split("/");
					}
		          
					String year2 = datetmp_a2[2];
		  			int tmp_month2_int = Integer.parseInt(datetmp_a2[1]);
		  			int tmp_day2_int = Integer.parseInt(datetmp_a2[0]);       			
					String[] time_a2 = time2.split(":");
					int tmp_hr2_int = Integer.parseInt(time_a2[0]);
					int tmp_min2_int = Integer.parseInt(time_a2[1]);
					int tmp_sec2_int = Integer.parseInt(time_a2[2]);
							        
					String tmp_month2="",tmp_day2="",tmp_hr2="",tmp_min2="",tmp_sec2="";
					
					if(tmp_month2_int < 10)
					{
					  tmp_month2 = "0"+tmp_month2_int;
					}
					else
					{
						tmp_month2 = ""+tmp_month2_int;
					}
					if(tmp_day2_int < 10)
					{
					  tmp_day2 = "0"+tmp_day2_int;
					}
					else
					{
						tmp_day2 = ""+tmp_day2_int;
					}
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
		        
					String date_str_display2 = tmp_day2+"-"+tmp_month2+"-"+year2;
					String date_str2 = year2+"-"+tmp_month2+"-"+tmp_day2;        
					String time_str2 = tmp_hr2+":"+tmp_min2+":"+tmp_sec2;
					                                         			                
					String date2_tmp = date_str2+" "+time_str2;
		  			///////////////////////////////////////////////////////////////////// 			
		        									
					av.date1_csv.add(date_str_display1);					
		  			av.time1_csv.add(time_str1);
		  
		  			av.date2_csv.add(date_str_display2);
		  			av.time2_csv.add(time_str2);
		              
		  			av.input_date1.add(date1_tmp);
		  			av.input_date2.add(date2_tmp);
		  			//echo "<br>input date1=".$date1_tmp." ,input date2=".$date2_tmp;              
		  			///////////////********************************************////////////////          	  
		  			av.doctype.add(doctype1);
		  			av.plant.add(plant1);
		  			av.route.add(route1);
		  			av.vname.add(vname_tmp1);
		  			av.vendor_name.add(vendor_name1);
		  			av.customer_no.add(customer_no1);
		  			//echo "<br>customer_no1=".$customer_no1."<br>";              
		  			//$qty[] = $qty1;
		  			//$unit[] = $unit1;      	  
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
	public static void readXLSFile(String file_path, alert_variable_plant_customer av) throws IOException {
		
		int r=0, customer_no1 = 0, c=0;
		String date1="", record="", time1="", date2="", time2="";
		String doctype1="",plant1="",route1="",vname_tmp1="",vendor_name1="";
		
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
			customer_no1 = 0;
			date1="";
			record="";
			time1="";
			date2="";
			time2="";
			doctype1="";
			plant1="";
			route1="";
			vname_tmp1="";
			vendor_name1="";		
			
			row=(HSSFRow) rows.next();
			Iterator cells = row.cellIterator();
			c=0;
			
			while (cells.hasNext())
			{									
				//System.out.println("Row="+r+" ,Col="+c);
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
				  //for(int c=0;c < cell_data.length;c++)
				  //{
					 //System.out.println(cell_data);
					 if(c==0)
						 av.stringData = av.stringData + cell_data;
					 else if(c<=9)
						 av.stringData = av.stringData + ","+ cell_data;
				  //}
				  
				  //System.out.println("StringData="+av.stringData); 
				  //writeString = writeString + "\n";
				}
				else
				{
					//System.out.println("In Else");
					
					//for (int c=0; c < cell_data.length; c++) 
					//{            					
					//echo "In Else=".$data[$c] . "<br />\n";		  									
						
						//System.out.println("C="+c);
						
						record = cell_data;
						//echo "<br>record=".$record;               
						if(c==0)
						{
							//date1 = record;							  
							if (DateUtil.isCellDateFormatted(cell))
							{
							   try {
							
							    SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
							    date1 = sdf.format(cell.getDateCellValue());
							
							   } catch (Exception e2) {
							        e2.printStackTrace();
							   }
							}
						  /*Date datetmp = utility_classes.ExcelDateParse(Float.parseFloat(date1));
							SimpleDateFormat formatter = new SimpleDateFormat("dd-MM-yyyy");
							//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
							date1 = formatter.format(datetmp);*/
							//System.out.println("C0Date="+date1);						  						  
						}
						else if(c==1)
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
									time1 = formatter.format(time1tmp);
									//System.out.println("C1TIME====="+time1);										
								} 
								break;
								default:
								//System.out.println("SIX");
							}				
							
							//System.out.println("C1="+time1);
						}
						else if(c==2)
						{
							//date2 = record;
							  
							if (DateUtil.isCellDateFormatted(cell))
							{
								try {
								
								SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
								date2 = sdf.format(cell.getDateCellValue());
								
								} catch (Exception e2) {
								        e2.printStackTrace();
								}
							}
												  
						  //System.out.println("c2="+date2);
						}
						else if(c==3)
						{
							//time2 = record;							
							switch (cell.getCellType()) 
							{						
								case Cell.CELL_TYPE_NUMERIC:
								if (DateUtil.isCellDateFormatted(cell)) 
								{
									Date time1tmp = cell.getDateCellValue();
									SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
									//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
									time2 = formatter.format(time1tmp);
									//System.out.println("C1TIME====="+time1);										
								} 
								break;
								default:
								//System.out.println("SIX");
							}				
						  	//System.out.println("c3="+time2);
						}                                
						else if(c==4)
						{
						  doctype1 = record;
						  //System.out.println("c4="+record);
						}
						else if(c==5)
						{
						  plant1 = record;
						  //System.out.println("c5="+record);
						}
						else if(c==6)
						{
						  route1 = record;
						  //System.out.println("c6="+record);
						}
						else if(c==7)
						{
						  vname_tmp1 = record;
						  //System.out.println("c7="+record);					  
						}
						else if(c==8)
						{
						  vendor_name1 = record;
						  //System.out.println("c8="+record);
						}                     
						else if(c==9)
						{
						  //System.out.println("c9="+record);	
						  //customer_no1 = Integer.parseInt(record);							
						  double customer_no_tmp = Double.parseDouble(record);
						  customer_no1 = (int)customer_no_tmp;
						  //System.out.println("c9="+customer_no1);					  
						}
						/*else if($c==10)
						{
						  $qty1 = $record;
						}
						else if($c==11)
						{
						  $unit1 = $record;
						}*/                                
					//	}					  
					} //ELSE CLOSED				  
				  
					c++;
				}	//INNER WHILE CLOSED
			
				if(customer_no1 >0)					
				{  
					///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
					//FIRST DATE        						
					String[] datetmp_a1 = new String[3];
											
					if(date1.indexOf("-") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = date1.split("-");
					}
					else if(date1.indexOf("/") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a1 = date1.split("/");
					}
		          
					//System.out.println("date[0]="+datetmp_a1[0]+" ,date[1]="+datetmp_a1[1]+" ,date[2]="+datetmp_a1[2]);
					           
					String year1 = datetmp_a1[2];
		  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
		  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
					String[] time_a1 = time1.split(":");
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
					                                         			                
					String date1_tmp = date_str1+" "+time_str1;
					/////////////////////////////////////////////////////////////
					
					//SECOND DATE        
					String[] datetmp_a2 = new String[2];
					
					if(date2.indexOf("-") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a2 = date2.split("-");
					}
					else if(date2.indexOf("/") > 0)
					{
						//datetmp_a1 = explode('-',$date1);
						datetmp_a2 = date2.split("/");
					}
		          
					String year2 = datetmp_a2[2];
		  			int tmp_month2_int = Integer.parseInt(datetmp_a2[1]);
		  			int tmp_day2_int = Integer.parseInt(datetmp_a2[0]);       			
					String[] time_a2 = time2.split(":");
					int tmp_hr2_int = Integer.parseInt(time_a2[0]);
					int tmp_min2_int = Integer.parseInt(time_a2[1]);
					int tmp_sec2_int = Integer.parseInt(time_a2[2]);
							        
					String tmp_month2="",tmp_day2="",tmp_hr2="",tmp_min2="",tmp_sec2="";
					
					if(tmp_month2_int < 10)
					{
					  tmp_month2 = "0"+tmp_month2_int;
					}
					else
					{
						tmp_month2 = ""+tmp_month2_int;
					}
					if(tmp_day2_int < 10)
					{
					  tmp_day2 = "0"+tmp_day2_int;
					}
					else
					{
						tmp_day2 = ""+tmp_day2_int;
					}
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
		        
					String date_str_display2 = tmp_day2+"-"+tmp_month2+"-"+year2;
					String date_str2 = year2+"-"+tmp_month2+"-"+tmp_day2;        
					String time_str2 = tmp_hr2+":"+tmp_min2+":"+tmp_sec2;
					
					String date2_tmp = date_str2+" "+time_str2;
		  			///////////////////////////////////////////////////////////////////// 			
		        
					//System.out.println("date_str_display1="+date_str_display1+" ,time_str1="+time_str1+" ,date_str_display2="+date_str_display2+" ,time_str2="+time_str2);
					//System.out.println("ROW="+r);						
					av.date1_csv.add(date_str_display1);					
		  			av.time1_csv.add(time_str1);
		  
		  			av.date2_csv.add(date_str_display2);
		  			av.time2_csv.add(time_str2);
		              
		  			av.input_date1.add(date1_tmp);
		  			av.input_date2.add(date2_tmp);
		  			//echo "<br>input date1=".$date1_tmp." ,input date2=".$date2_tmp;              
		  			///////////////////////////////          	  
		  			av.doctype.add(doctype1);
		  			av.plant.add(plant1);
		  			av.route.add(route1);
		  			av.vname.add(vname_tmp1);
		  			av.vendor_name.add(vendor_name1);
		  			av.customer_no.add(customer_no1);			  			
		  			//echo "<br>customer_no1=".$customer_no1."<br>";              
		  			//$qty[] = $qty1;
		  			//$unit[] = $unit1;      	  
				}  // IF CUSTOMER NO1 CLOSED  
										
				//System.out.println("outer closed");
				r++;
		} //OUTER WHILE
	}
		
	
	//###### READ XLSX
	public static void readXLSXFile(String file_path, alert_variable_plant_customer av) throws IOException
	{
		int r=0, c=0, customer_no1 = 0;
		String date1="", record="", time1="", date2="", time2="";
		String doctype1="",plant1="",route1="",vname_tmp1="",vendor_name1="";
		
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
			customer_no1 = 0;
			date1="";
			record="";
			time1="";
			date2="";
			time2="";
			doctype1="";
			plant1="";
			route1="";
			vname_tmp1="";
			vendor_name1="";
			
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
					 else if(c<=9)
						 av.stringData = av.stringData + ","+ cell_data;
				  //}					  
				  //writeString = writeString + "\n";
				}
				else
				{
					//for (int c=0; c < cell_data.length; c++) 
					//{            					
					//echo "In Else=".$data[$c] . "<br />\n";		  				
					//customer_no1 = 0;
						
					record = cell_data;
					//echo "<br>record=".$record;               
					if(c==0)
					{
					  //date1 = record;
						if (DateUtil.isCellDateFormatted(cell))
						{
							try {
							
							SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
							date1 = sdf.format(cell.getDateCellValue());
							
							} catch (Exception e2) {
							        e2.printStackTrace();
							}
						}						
					  //System.out.println("date1="+date1);
					}
					else if(c==1)
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
								time1 = formatter.format(time1tmp);
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}										
						
					  //System.out.println("time1="+time1);
					}
					else if(c==2)
					{
					  //date2 = record;
						if (DateUtil.isCellDateFormatted(cell))
						{
							try {
							
							SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
							date2 = sdf.format(cell.getDateCellValue());
							
							} catch (Exception e2) {
							        e2.printStackTrace();
							}
						}						
					  //System.out.println("date2="+date2);
					}
					else if(c==3)
					{
					  //time2 = record;
						//String excel_time = get_excel_time(record);
						//time2 = excel_time;
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								time2 = formatter.format(time1tmp);
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}										
						
					  //System.out.println("time2="+time2);
					}                                
					else if(c==4)
					{
					  doctype1 = record;
					  //System.out.println("doctype1="+doctype1);
					}
					else if(c==5)
					{
					  plant1 = record;
					  //System.out.println("plant1="+plant1);
					}
					else if(c==6)
					{
					  route1 = record;
					  //System.out.println("route1="+route1);
					}
					else if(c==7)
					{
					  vname_tmp1 = record;
					  //System.out.println("vname_tmp1="+vname_tmp1);					 
					}
					else if(c==8)
					{
					  vendor_name1 = record;
					  //System.out.println("vendor_name1="+vendor_name1);
					}                     
					else if(c==9)
					{
					  //customer_no1 = intval(record);
					  double customer_no_tmp = Double.parseDouble(record);
					  customer_no1 = (int)customer_no_tmp;
				
					  //System.out.println("customer_no1="+customer_no1);
					}
					/*else if($c==10)
					{
					  $qty1 = $record;
					}
					else if($c==11)
					{
					  $unit1 = $record;
					}*/                                
					//}					  
				} //ELSE CLOSED				  
				  
				c++;				
				
			} // INNER WHILE
			
			if(customer_no1 >0)
			{  
				///////////********MAKE INDIVIDUAL START DATE AND END DATE*******//////////              
				//FIRST DATE        
				String[] datetmp_a1 = new String[2];
				
				if(date1.indexOf("-") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = date1.split("-");
				}
				else if(date1.indexOf("/") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a1 = date1.split("/");
				}
	          
				String year1 = datetmp_a1[2];
	  			int tmp_month1_int = Integer.parseInt(datetmp_a1[1]);
	  			int tmp_day1_int = Integer.parseInt(datetmp_a1[0]);       			
				String[] time_a1 = time1.split(":");
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
				                                         			                
				String date1_tmp = date_str1+" "+time_str1;
				/////////////////////////////////////////////////////////////
				
				//SECOND DATE        
				String[] datetmp_a2 = new String[2];
				
				if(date2.indexOf("-") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a2 = date2.split("-");
				}
				else if(date2.indexOf("/") > 0)
				{
					//datetmp_a1 = explode('-',$date1);
					datetmp_a2 = date2.split("/");
				}
	          
				String year2 = datetmp_a2[2];
	  			int tmp_month2_int = Integer.parseInt(datetmp_a2[1]);
	  			int tmp_day2_int = Integer.parseInt(datetmp_a2[0]);       			
				String[] time_a2 = time2.split(":");
				int tmp_hr2_int = Integer.parseInt(time_a2[0]);
				int tmp_min2_int = Integer.parseInt(time_a2[1]);
				int tmp_sec2_int = Integer.parseInt(time_a2[2]);
						        
				String tmp_month2="",tmp_day2="",tmp_hr2="",tmp_min2="",tmp_sec2="";
				
				if(tmp_month2_int < 10)
				{
				  tmp_month2 = "0"+tmp_month2_int;
				}
				else
				{
					tmp_month2 = ""+tmp_month2_int;
				}
				if(tmp_day2_int < 10)
				{
				  tmp_day2 = "0"+tmp_day2_int;
				}
				else
				{
					tmp_day2 = ""+tmp_day2_int;
				}
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
				String date_str_display2 = tmp_day2+"-"+tmp_month2+"-"+year2;
				String date_str2 = year2+"-"+tmp_month2+"-"+tmp_day2;        
				String time_str2 = tmp_hr2+":"+tmp_min2+":"+tmp_sec2;
				                                         			                
				String date2_tmp = date_str2+" "+time_str2;
	  			///////////////////////////////////////////////////////////////////// 			
	        
				av.date1_csv.add(date_str_display1);					
	  			av.time1_csv.add(time_str1);
	  
	  			av.date2_csv.add(date_str_display2);
	  			av.time2_csv.add(time_str2);
	              
	  			av.input_date1.add(date1_tmp);
	  			av.input_date2.add(date2_tmp);
	  			///////////////********************************************////////////////          	  
	  			av.doctype.add(doctype1);
	  			av.plant.add(plant1);
	  			av.route.add(route1);
	  			av.vname.add(vname_tmp1);
	  			av.vendor_name.add(vendor_name1);
	  			av.customer_no.add(customer_no1);
	  			//$qty[] = $qty1;
	  			//$unit[] = $unit1;      	  
			}  // IF CUSTOMER NO1 CLOSED     
								
			//System.out.println();
			r++;
		} //OUTER WHILE
		
		//System.out.println("TOTAL_ROWS_READ="+r+" ,vendor_name1="+vendor_name1);
	}	

	/*
	//### WRITE XLS FILE
	public static void writeXLSFile() throws IOException {

		String excelFileName = "C:/Test.xls";//name of excel file

		String sheetName = "Sheet1";//name of sheet

		HSSFWorkbook wb = new HSSFWorkbook();
		HSSFSheet sheet = wb.createSheet(sheetName) ;

		//iterating r number of rows
		for (int r=0;r < 5; r++ )
		{
			HSSFRow row = sheet.createRow(r);

			//iterating c number of columns
			for (int c=0;c < 5; c++ )
			{
				HSSFCell cell = row.createCell(c);

				cell.setCellValue("Cell "+r+" "+c);
			}
		}

		FileOutputStream fileOut = new FileOutputStream(excelFileName);

		//write this workbook to an Outputstream.
		wb.write(fileOut);
		fileOut.flush();
		fileOut.close();
	}
	
	//#### WRITE XLSX 
	public static void writeXLSXFile() throws IOException {

		String excelFileName = "C:/Test.xlsx";//name of excel file
	
		String sheetName = "Sheet1";//name of sheet
	
		XSSFWorkbook wb = new XSSFWorkbook();
		XSSFSheet sheet = wb.createSheet(sheetName) ;
	
		//iterating r number of rows
		for (int r=0;r < 5; r++ )
		{
			XSSFRow row = sheet.createRow(r);
	
			//iterating c number of columns
			for (int c=0;c < 5; c++ )
			{
				XSSFCell cell = row.createCell(c);
	
				cell.setCellValue("Cell "+r+" "+c);
			}
		}
	
		FileOutputStream fileOut = new FileOutputStream(excelFileName);
	
		//write this workbook to an Outputstream.
		wb.write(fileOut);
		fileOut.flush();
		fileOut.close();
	}	
	*/
	
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
