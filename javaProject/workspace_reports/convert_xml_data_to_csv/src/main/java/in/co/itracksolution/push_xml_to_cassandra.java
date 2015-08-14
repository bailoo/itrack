package in.co.itracksolution;

import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;

public class push_xml_to_cassandra {

   static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";
   //static final String DB_URL_remote = "jdbc:mysql://111.118.181.56/iespl_vts_beta";
   static final String DB_URL_remote = "jdbc:mysql://localhost/iespl_vts_beta";
   
   static final String USER_remote = "root";
   static final String PASS_remote = "mysql";
   public static Connection conn_remote = null;
   public static Statement stmt_remote = null;

   public static ArrayList<String> imei = new ArrayList<String>();
   public static ArrayList<String> userdates = new ArrayList<String>();
   public static push_device_data push_cassandra = null;
   public static String line ="", q = ";", xml_sorted="";
   public static String MsgType = "",Version = "",Fix = "",Latitude = "",Longitude = "",speed_f = "",DateTime = "", STS="", io_value1 = "",io_value2 = "",io_value3 = "",io_value4 = "",io_value5 = "",io_value6 = "",io_value7 = "",io_value8 = "",Signal_Strength = "",supv_f = "",ci = "",ax = "",ay = "",az = "",mx = "",my = "",mz = "",bx = "",by = "",bz = "";   
	
   public static void get_connection() {
		conn_remote = null;		   
		System.out.println("IN CON");	
		try{
		      //STEP 2: Register JDBC driver
		  try {
			Class.forName("com.mysql.jdbc.Driver");
		  } catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		  }
		  //STEP 3: Open a connection
		  	  System.out.println("A:Connection to Remote database-ok:"+conn_remote);	    
		      conn_remote = DriverManager.getConnection(DB_URL_remote,USER_remote,PASS_remote);	
		      System.out.println("B:Connection to Remote database-ok:"+conn_remote);	      
		 }catch(SQLException se){}
		 //######### DATABASE CONNECTION
   }
   
   public static void main(String args[]) {
	   
	   get_connection();
	   push_cassandra = new push_device_data();
		
	   //get_all_imeis(1180);
	   get_all_imeis();
	
	   String sdate = "2015-06-01", edate ="2015-07-30";
	   userdates = utility_classes.get_All_Dates(sdate,edate);
	
	   int date_size = userdates.size();
	   //date_size = 1; //COMMENT IT
		
	   String abspath_sorted = "/mnt/csv_creator/xml_hdfc_july";
	   //String abspath_sorted = "D:\\itrack_vts/sorted_xml_data";
	   FileWriter fw = null;
	   String newStr ="";
	   System.out.println("date_size:"+date_size);
	   //System.exit(0);
	   for(int i=0;i<=(date_size-1);i++) {
		   
		   System.out.println("Date:"+userdates.get(i));
		   for(int j=0;j<imei.size();j++) {
			   String xml_sorted = abspath_sorted+"/"+((String)userdates.get(i))+"/"+imei.get(j)+".xml";
			   System.out.println("PATH:"+xml_sorted);
			   
			   //CREATE FILE
			    //String filename= "D:\\itrack_vts/csv_hdfc_16Jun_15Jul/"+imei.get(j)+".csv";
			    String filename= "/mnt/csv_creator/csv_hdfc_july/"+imei.get(j)+".csv";
			    			    
				try {
					fw = new FileWriter(filename,true);
				} catch (IOException e3) {
					// TODO Auto-generated catch block
					e3.printStackTrace();
				} //the true will append the new data

						
				File file1 = new File(xml_sorted);
				boolean exist1 = file1.exists();
	
				System.out.println("Exist="+exist1);
				if (exist1) {    		
					try {
						FileInputStream fstream = new FileInputStream(xml_sorted);
						// Get the object of DataInputStream
						DataInputStream in = new DataInputStream(fstream);
						BufferedReader br = new BufferedReader(new InputStreamReader(in));
						String strLine;
						
						while ((strLine = br.readLine()) != null) {
							
							int len = strLine.length();
						
							if(len > 50) {
								try{
									/*a="NORMAL" b="3.3LF" c="1" d="29.94753N" e="78.16069E" f="0.56" g="2015-06-14 00:00:01" h="2015-06-13 23:59:59" i="4000" j="0" k="0" l="0" m="0" n="0" o="0" p="0" q="0" r="4.05" 
									ci="01B0#F232" ax="7" ay="34" az="1024" mx="7" my="35" mz="1025" bx="0" by="0" bz="0"*/
																	
									MsgType = utility_classes.getXmlAttribute(strLine,"a=\"[^\"]+");
									//System.out.println("MsgType="+MsgType);	
									Version = utility_classes.getXmlAttribute(strLine,"b=\"[^\"]+");
									Fix = utility_classes.getXmlAttribute(strLine,"c=\"[^\"]+");
									Latitude = utility_classes.getXmlAttribute(strLine,"d=\"[^\"]+");
									Longitude = utility_classes.getXmlAttribute(strLine,"e=\"[^\"]+");
									speed_f = utility_classes.getXmlAttribute(strLine,"f=\"[^\"]+");
									STS = utility_classes.getXmlAttribute(strLine,"g=\"[^\"]+");
									DateTime = utility_classes.getXmlAttribute(strLine,"h=\"[^\"]+");									
									io_value1 = utility_classes.getXmlAttribute(strLine,"i=\"[^\"]+");
									io_value2 = utility_classes.getXmlAttribute(strLine,"j=\"[^\"]+");
									io_value3 = utility_classes.getXmlAttribute(strLine,"k=\"[^\"]+");
									io_value4 = utility_classes.getXmlAttribute(strLine,"l=\"[^\"]+");
									io_value5 = utility_classes.getXmlAttribute(strLine,"m=\"[^\"]+");
									io_value6 = utility_classes.getXmlAttribute(strLine,"n=\"[^\"]+");
									io_value7 = utility_classes.getXmlAttribute(strLine,"o=\"[^\"]+");
									io_value8 = utility_classes.getXmlAttribute(strLine,"p=\"[^\"]+");
									Signal_Strength = utility_classes.getXmlAttribute(strLine,"q=\"[^\"]+");
									supv_f = utility_classes.getXmlAttribute(strLine,"r=\"[^\"]+");
									/*ci = utility_classes.getXmlAttribute(strLine,"ci=\"[^\"]+");
									ax = utility_classes.getXmlAttribute(strLine,"ax=\"[^\"]+");
									ay = utility_classes.getXmlAttribute(strLine,"ay=\"[^\"]+");
									az = utility_classes.getXmlAttribute(strLine,"az=\"[^\"]+");
									mx = utility_classes.getXmlAttribute(strLine,"mx=\"[^\"]+");
									my = utility_classes.getXmlAttribute(strLine,"my=\"[^\"]+");
									mz = utility_classes.getXmlAttribute(strLine,"mz=\"[^\"]+");
									bx = utility_classes.getXmlAttribute(strLine,"bx=\"[^\"]+");
									by = utility_classes.getXmlAttribute(strLine,"by=\"[^\"]+");
									bz = utility_classes.getXmlAttribute(strLine,"bz=\"[^\"]+");*/
									 
									//line = MsgType+q+Version+q+Fix+q+Latitude+q+Longitude+q+speed_f+q+DateTime+q+io_value1+q+io_value2+q+io_value3+q+io_value4+q+io_value5+q+io_value6+q+io_value7+q+io_value8+q+Signal_Strength+q+supv_f+q+ci+q+ax+q+ay+q+az+q+mx+q+my+q+mz+q+bx+q+by+q+bz+q; 		//without cellname
									line = MsgType+q+Version+q+Fix+q+Latitude+q+Longitude+q+speed_f+q+io_value1+q+io_value2+q+io_value3+q+io_value4+q+io_value5+q+io_value6+q+io_value7+q+io_value8+q+Signal_Strength+q+supv_f+q; 		//without cellname
									newStr = imei.get(j)+","+DateTime+","+line+","+STS;
									//imei,devicetime,data,servertime
								    fw.write(newStr+"\n");//appends the string to the file

									System.out.println("LINE="+line);
									//push_cassandra.insertFulldata(imei.get(j),DateTime,line);
									//push_cassandra.insertLastdata(imei.get(j), line);
									//System.out.println("After");							
								
								}catch(Exception e){}
							}
						}
					} catch (FileNotFoundException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					} catch (IOException e2) {
						// TODO Auto-generated catch block
						e2.printStackTrace();
					}
				}
				System.out.println("Exist End1="+exist1);
				
				   
			    try {
					fw.close();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}				
			}
		   System.out.println("Exist End2");
		}   
	   
	   push_device_data.close();
	   System.out.println("Exist End process-3");
	   System.exit(0);
	}   
	
	/*public static void get_all_imeis(int account_id) {
		
		stmt_remote = null;
		   try{
			  // System.out.println("Selecting data...");
		      stmt_remote = conn_remote.createStatement();
		      String sql_remote;
		      sql_remote = "SELECT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle_grouping,account WHERE "+
		    		  "account.account_id="+account_id+ " AND vehicle_grouping.account_id=account.account_id AND account.status=1 AND "+
		    		  "vehicle_assignment.status=1 AND vehicle_assignment.vehicle_id=vehicle_grouping.vehicle_id AND "+
		    		  "vehicle_grouping.status=1";
		    	
		      System.out.println("SQL="+sql_remote);
		      ResultSet rs_remote = stmt_remote.executeQuery(sql_remote);

		      //STEP 5: Extract data from result set
		      while(rs_remote.next()){
																					
		         try{						
					//System.out.println(strLine1);
		        	 imei.add(rs_remote.getString("device_imei_no"));				
		         } catch(Exception e){System.out.println("Inner:"+e.getMessage());}
		      }
		   } catch(Exception e1){System.out.println("Outer:"+e1.getMessage());}		      
	}*/
   
   public static void get_all_imeis() {		
	   
	   imei.add("865733021570411");
	   imei.add("865733021569447");
	   imei.add("865733021567698");
	   imei.add("865733021571229");
	   imei.add("865733021563051");
	   imei.add("865733021564257");
	   imei.add("865733021563374");
	   imei.add("865733021563481");
	   imei.add("865733021563622");
	   imei.add("865733021564133");
	   imei.add("865733021571099");
	   imei.add("865733021569413");
	   imei.add("865733021569123");
	   imei.add("865733021569959");
	   imei.add("865733021571088");
	   imei.add("865733021568787");
	   imei.add("865733021571237");
	   imei.add("865733021564323");
	   imei.add("865733021562939");
	   imei.add("865733021564000");
	   imei.add("865733021563729");
	   imei.add("865733021563747");
	   imei.add("865733021564125");
   }
	
}
