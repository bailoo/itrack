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


public class read_plant_customer_master {

	//######################################################################################################################
	//############################################# READ PLANT MASTER EXCEL ################################################
	//###################################################################################################################### 
	
	//#### READ CSV
	public static void readCSVFile_PLANT_MASTER(String file_path, alert_variable_plant_customer av_pc) throws IOException {	
		 //String writeString = "";		
		int i=0, route_no=0, plant=0;
		String schedule_in="", schedule_out="", record="";
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
				  	route_no = 0;
				  	plant=0;					
				  	schedule_in="";
				  	schedule_out="";					
				  
				  //Print the content on the console				  
				  //System.out.println("csv3");
				  String[] cell_data = strLine.split(",");
				  
				  if(i>0)	//SKIP HEADER
				  {					  					 
		  			for (int c=0; c < cell_data.length; c++) 
		  			{            		  				
		  				//echo "In Else=".$data[$c] . "<br />\n";		  				
		  				//customer_no1 = 0;
		  					
		  				record = cell_data[c];
		  				//echo "<br>record=".$record;               
		  				if(c==0)
		  				{
		  					route_no = Integer.parseInt(record);
		  				  //System.out.println("c0="+record);
		  				}
		  				else if(c==1)
		  				{
		  					plant = Integer.parseInt(record);
		  				  //System.out.println("c1="+record);
		  				}
		  				else if(c==2)
		  				{
		  					schedule_in = record;
		  					schedule_in = schedule_in+":00";
		  				  //System.out.println("c2="+record);
		  				}
		  				else if(c==3)
		  				{
		  					schedule_out = record;
		  					schedule_out = schedule_out+":00";
		  				  //System.out.println("c3="+record);
		  				}                                		  				
		  			} //for closed					  
				 } //ELSE CLOSED				  
				//i++;
				  				
				//System.out.println("plant >0="+plant);				  
				if(plant >0)
				{  		        									
					av_pc.mp_route_no.add(route_no);					
		  			//System.out.println("PlantAdd="+plant);
					av_pc.mp_plant.add(plant);		  
		  			av_pc.mp_schedule_in_time.add(schedule_in);
		  			av_pc.mp_schedule_out_time.add(schedule_out);	
				}  // IF CUSTOMER NO1 CLOSED                  					  
				i++;  
			 }  // WHILE CLOSED
			  
			  System.out.println("av_pc.mp_plant.size()="+av_pc.mp_plant.size());	
			  //Close the input stream
			  in.close();
		}catch (Exception e1){//Catch exception if any
			System.err.println("Error: " + e1.getMessage());}		
	}

	
	//#### READ XLS
	public static void readXLSFile_PLANT_MASTER(String file_path, alert_variable_plant_customer av_pc) throws IOException {
		
		int r=0, c=0, i=0, route_no=0, plant=0;
		String schedule_in="", schedule_out="", record="";
		
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
		  	record="";
		  	route_no = 0;
		  	plant=0;					
		  	schedule_in="";
		  	schedule_out="";					
			
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
				
				if(r>0)
				{					  				 
					//System.out.println("In Else");
					
					//for (int c=0; c < cell_data.length; c++) 
					//{            					
					//echo "In Else=".$data[$c] . "<br />\n";		  									
						
						//System.out.println("C="+c);
						
						record = cell_data;
						
						if(c==0)
						{						
							//route_no = Integer.parseInt(record);
							route_no = (int)(Float.parseFloat(record));
							//System.out.println("route_no====="+route_no);																		
						}						
						else if(c==1)
						{
							//plant = Integer.parseInt(record);
							plant = (int)(Float.parseFloat(record));
							//System.out.println("plant====="+plant);																		
						}                                						
						if(c==2)
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
									schedule_in = formatter.format(time1tmp);
									//schedule_in = schedule_in+":00";
									//System.out.println("C1TIME====="+time1);										
								} 
								break;
								default:
								//System.out.println("SIX");
							}				
							
							//System.out.println("C1="+schedule_in);
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
									schedule_out = formatter.format(time1tmp);
									//schedule_out = schedule_out+":00";
									//System.out.println("C1TIME====="+time1);										
								} 
								break;
								default:
								//System.out.println("SIX");
							}				
						  	//System.out.println("c3="+schedule_out);
						}                         						
					} //ELSE CLOSED				  
				  
					c++;
				}	//INNER WHILE CLOSED
			
			//System.out.println("InWrite plant1");
			
			if(plant >0)					
				{  
					//System.out.println("InWrite plant2");
					av_pc.mp_route_no.add(route_no);					
		  			av_pc.mp_plant.add(plant);		  
		  			av_pc.mp_schedule_in_time.add(schedule_in);
		  			av_pc.mp_schedule_out_time.add(schedule_out);		  		
				}  // IF CUSTOMER NO1 CLOSED  
										
				//System.out.println("outer closed");
				r++;
		} //OUTER WHILE
	}
		
	
	//###### READ XLSX
	public static void readXLSXFile_PLANT_MASTER(String file_path, alert_variable_plant_customer av_pc) throws IOException
	{
		int r=0, c=0, route_no=0, plant=0;
		String schedule_in="", schedule_out="", record="";		
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
		  	record="";
		  	route_no = 0;
		  	plant=0;					
		  	schedule_in="";
		  	schedule_out="";					
			
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
				
				if(r>0)
				{					  				  
					//for (int c=0; c < cell_data.length; c++) 
					//{            					
					//echo "In Else=".$data[$c] . "<br />\n";		  				
					//customer_no1 = 0;						
					record = cell_data;
					//echo "<br>record=".$record;               
					if(c==0)
					{					  
						route_no = (int)(Float.parseFloat(record));
						//route_no = Integer.parseInt(record);
						//System.out.println("route="+route_no);		
					}
					else if(c==1)
					{						
						plant = (int)(Float.parseFloat(record));
						//plant = Integer.parseInt(record);	
						//System.out.println("plant="+plant);
					}					
					else if(c==2)
					{
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								schedule_in = formatter.format(time1tmp);
								//schedule_in = schedule_in+":00";
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("schedule_in="+schedule_in);
						}
						//System.out.println("schedule_in="+schedule_in);
					}
					else if(c==3)
					{
						switch (cell.getCellType()) 
						{						
							case Cell.CELL_TYPE_NUMERIC:
							if (DateUtil.isCellDateFormatted(cell)) 
							{
								Date time1tmp = cell.getDateCellValue();
								SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
								schedule_out = formatter.format(time1tmp);
								//schedule_out = schedule_out+":00";
								//System.out.println("C1TIME====="+time1);										
							} 
							break;
							default:
							//System.out.println("SIX");
						}
						//System.out.println("schedule_out="+schedule_out);
					}
				} //ELSE CLOSED				  
				  
				c++;				
				
			} // INNER WHILE
			
			//System.out.println("IN WIRTE1:PLANT MASTER");
			if(plant >0)
			{  		        									
				//System.out.println("IN WRITE2: PLANT MASTER");
				av_pc.mp_route_no.add(route_no);						  			
				av_pc.mp_plant.add(plant);		  
	  			av_pc.mp_schedule_in_time.add(schedule_in);
	  			av_pc.mp_schedule_out_time.add(schedule_out);	
			}  // IF CUSTOMER NO1 CLOSED  								
			//System.out.println();
			r++;
		} //OUTER WHILE
		
		//System.out.println("TOTAL_ROWS_READ="+r+" ,vendor_name1="+vendor_name1);
	}
	


	//######################################################################################################################
	//############################################# READ CUSTOMER MASTER EXCEL #############################################
	//###################################################################################################################### 
	
	//#### READ CSV
	public static void readCSVFile_CUSTOMER_MASTER(String file_path, alert_variable_plant_customer av_pc) throws IOException {	
		 //String writeString = "";		
		int i=0, point=0;
		String shift="", timing="", record="";
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
				  	point = 0;
				  	shift="";
				  	timing="";					
				  
				  //Print the content on the console				  
				  //System.out.println("csv3");
				  String[] cell_data = strLine.split(",");
				  
				  if(i>0)	//SKIP HEADER
				  {					  					 
		  			for (int c=0; c < cell_data.length; c++) 
		  			{            		  				
		  				//echo "In Else=".$data[$c] . "<br />\n";		  				
		  				//customer_no1 = 0;
		  					
		  				record = cell_data[c];
		  				//echo "<br>record=".$record;               
		  				if(c==0)
		  				{
		  					shift = record;
		  				  //System.out.println("c0="+record);
		  				}
		  				else if(c==1)
		  				{
		  					//point = Integer.parseInt(record);
		  					point = (int)(Float.parseFloat(record));
		  				  //System.out.println("c1="+record);
		  				}
		  				else if(c==2)
		  				{
		  					timing = record;
		  					//System.out.println("cA1="+record);
		  					timing = timing+":00";
		  					//System.out.println("cA2="+record);
		  				}                               		  				
		  			} //for closed					  
				 } //ELSE CLOSED				  
				//i++;
				  				
				//System.out.println("customer_no1="+customer_no1);				  
				if(point >0)
				{  		        									
					av_pc.mc_shift.add(shift);					
		  			av_pc.mc_point.add(point);		  
		  			av_pc.mc_timing.add(timing);
				}  // IF CUSTOMER NO1 CLOSED                  					  
				i++;  
			 }  // WHILE CLOSED
			  //Close the input stream
			  in.close();
		}catch (Exception e1){//Catch exception if any
			System.err.println("Error: " + e1.getMessage());}		
	}

	
	//#### READ XLS
	public static void readXLSFile_CUSTOMER_MASTER(String file_path, alert_variable_plant_customer av_pc) throws IOException {
		
		//System.out.println("READ MASTER");
		
		int r=0, c=0, point=0;
		String shift="", timing="", record="";
		
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
		  	record="";
		  	point = 0;
		  	shift="";
		  	timing="";					
			
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
				
				if(r>0)
				{					  				 
					//System.out.println("In Else");					
					//for (int c=0; c < cell_data.length; c++) 
					//{            					
					//echo "In Else=".$data[$c] . "<br />\n";		  									
						
						//System.out.println("C="+c);
						
						record = cell_data;
						
						if(c==0)
						{
							shift = record;
							//System.out.println("shift="+shift);
						}						
						else if(c==1)
						{
							//System.out.println("point1="+record);
							//point = Integer.parseInt(record);	
							point = (int)(Float.parseFloat(record));
						  	//System.out.println("point2="+point);
						}                                
						else if(c==2)
						{
							//if (DateUtil.isCellDateFormatted(cell))
							//{
								switch (cell.getCellType()) 
								{						
									case Cell.CELL_TYPE_NUMERIC:
									if (DateUtil.isCellDateFormatted(cell)) 
									{
										Date time1tmp = cell.getDateCellValue();
										SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
										//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
										timing = formatter.format(time1tmp);
										//timing = timing+":00";
										//System.out.println("C1TIME====="+time1);										
									} 
									break;
									default:
									//System.out.println("SIX");
								}					
							//}																	
						  //System.out.println("c4="+record);
						}
						
					} //ELSE CLOSED				  
				  
					c++;
				}	//INNER WHILE CLOSED
			
				if(point >0)
				{  		        									
					av_pc.mc_shift.add(shift);					
		  			av_pc.mc_point.add(point);		  
		  			av_pc.mc_timing.add(timing);
				}  // IF CUSTOMER NO1 CLOSED                  					  
										
				//System.out.println("outer closed");
				r++;
		} //OUTER WHILE
	}
		
	
	//###### READ XLSX
	public static void readXLSXFile_CUSTOMER_MASTER(String file_path, alert_variable_plant_customer av_pc) throws IOException
	{		
		int r=0, c=0, point=0;
		String shift="", timing="", record="";		
		//InputStream ExcelFileToRead = new FileInputStream("C:/Test.xlsx");
		InputStream ExcelFileToRead = new FileInputStream(file_path);
		XSSFWorkbook wb = new XSSFWorkbook(ExcelFileToRead);
	
		XSSFWorkbook test = new XSSFWorkbook();
	
		XSSFSheet sheet = wb.getSheetAt(0);
		XSSFRow row;
		XSSFCell cell;
	
		Iterator rows = sheet.rowIterator();

		r =0;
		//System.out.println("READ xlsx");
		
		while (rows.hasNext())
		{
			//System.out.println("READ xlsx1");
			record="";
		  	point = 0;
		  	shift="";
		  	timing="";					
			
			row=(XSSFRow) rows.next();
			Iterator cells = row.cellIterator();
			
			c=0;
			while (cells.hasNext())
			{
				//System.out.println("READ xlsx2");
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
				
				//System.out.println("type="+type);
				
				if(type ==1)
				{
					tmp_str = cell.getStringCellValue();
					//System.out.println("tmp_str1="+tmp_str);
					cell_data = tmp_str;
				}
				else if(type==2)
				{
					tmp_str = String.valueOf(cell.getNumericCellValue());
					//System.out.println("tmp_str2="+tmp_str);
					cell_data = tmp_str;
				}				
				
				if(r>0)
				{					  				  
					//for (int c=0; c < cell_data.length; c++) 
					//{            					
					//echo "In Else=".$data[$c] . "<br />\n";		  				
					//customer_no1 = 0;
						
					record = cell_data; 
					//System.out.println("record="+record+" c="+c);
					if(c==0)
					{					  
						shift = record;									
					  //System.out.println("shift="+shift);
					}
					else if(c==1)
					{
						//System.out.println("record1="+record);
						point = (int)(Float.parseFloat(record));									
					  //System.out.println("point="+point);
					}					
					else if(c==2)
					{
						if (DateUtil.isCellDateFormatted(cell))
						{
							switch (cell.getCellType()) 
							{						
								case Cell.CELL_TYPE_NUMERIC:
								if (DateUtil.isCellDateFormatted(cell)) 
								{
									Date time1tmp = cell.getDateCellValue();
									SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
									//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
									timing = formatter.format(time1tmp);
									//timing = timing+":00";
									//System.out.println("C1TIME====="+time1);										
								} 
								break;
								default:
								//System.out.println("SIX");
							}					
						}
						//System.out.println("timing="+timing);
					}					      						
				} //if CLOSED				  				  
				c++;				
				
			} // INNER WHILE
			
			if(point >0)
			{  		        									
				av_pc.mc_shift.add(shift);					
	  			av_pc.mc_point.add(point);		  
	  			av_pc.mc_timing.add(timing);
			}  // IF CUSTOMER NO1 CLOSED                  					  

			//System.out.println();
			r++;
		} //OUTER WHILE
		
		//System.out.println("TOTAL_ROWS_READ="+r+" ,shift="+shift);
	}
	
	//CUSTOMER MASTER READING CLOSED
	
	
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
