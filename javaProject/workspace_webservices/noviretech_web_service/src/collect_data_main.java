
import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.RandomAccessFile;
import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Timestamp;
import java.text.DateFormat;
import java.text.ParseException;
//import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Locale;
import java.util.Scanner;
import java.util.StringTokenizer;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.json.JSONArray;
import org.json.JSONObject;

/*import org.json.simple.JSONObject;
import org.json.simple.JSONArray;
import org.json.simple.parser.ParseException;
import org.json.simple.parser.JSONParser;*/

public class collect_data_main {
	
	//public static DateFormat sDF = new SimpleDateFormat("dd MMM yyyy HH:mm:ss", Locale.ENGLISH);
	public static SimpleDateFormat dDF = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	public static SimpleDateFormat dateOnly = new SimpleDateFormat("yyyy-MM-dd");
	public static SimpleDateFormat inputDateFormat = new SimpleDateFormat("ddMMyyyyHHmmss");
	/*public static String ServerTS = ""; 
	public static String DateTime = "";
	public static String ReceiveDateFormat="";
//	String VehicleID = "";
	public static String DeviceIMEINo = "";
	public static String MsgType = "";
	public static String Version = "";
	public static String Fix = "1";
//	String SendMode = "";
	public static String Latitude = "";
	public static String Longitude = "";
	public static String Altitude = "";
	public static String Speed = "";		
	public static String Signal_Strength = "";
	public static String No_Of_Satellites = "";
	public static String line="",serverdatetime="",devicedatetime="";
//	String CBC = "";
	public static String CellName = "";
//	String min_speed = "";
//	String max_speed = "";
	public static String distance = "";
	public static String strOrig = "";

	public static String io_value1 = "";
	public static String io_value2 = "";
	public static String io_value3 = "";
	public static String io_value4 = "";
	public static String io_value5 = "";
	public static String io_value6 = "";
	public static String io_value7 = "";
	public static String io_value8 = "";
	public static String last_date = "";
	
	public static String SupplyVoltage = "";*/

//	public static String LogDataPath = "D:\\itrack_vts/xml_data";
//	public static String RealDataPath = "D:\\itrack_vts/last_location";
	public static String LogDataPath = "/home/current_data/xml_data";
	public static String RealDataPath = "/home/current_data/last_location";
	public static String last_datetime = "";
	public static String vehicle_imei ="";
	
	//#### DATABASE CONNECTION VARIABLES
	public static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";  
	public static final String DB_URL = "jdbc:mysql://localhost/alert_session";
	public static final String DB_URL_remote = "jdbc:mysql://111.118.181.156/iespl_vts_beta";
	//public static final String DB_URL_remote = "jdbc:mysql://localhost/iespl_vts_beta";
	
	//  Database credentials
	public static final String USER = "root";
	public static final String PASS = "mysql";
	public static Connection conn = null;
	public static Statement stmt = null;
	   
	public static final String USER_remote = "root";
	public static final String PASS_remote = "mysql";
	public static Connection conn_remote = null;
	public static Statement stmt_remote = null;
	  
	public static void get_connection()			//##### CONSTRUCTOR
	{		 
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
		  	  //System.out.println("Connection to 147 -local database");
		      conn = DriverManager.getConnection(DB_URL,USER,PASS);
		 }catch(SQLException se){}
		 //######### DATABASE CONNECTION		 
	}
	
	public static void main(String args[])
	{
		get_connection();
		//System.out.println("BEFORE GET ESCALATION DETAIL");
		alert_module.get_escalation_detail();
		
		System.out.println("INITIALIZATION-1 COMPLETE");
		//######### GET VENDOR VEHICLES
		/*try{
			get_vendor_vehicles();
		}catch(Exception gv){System.out.println("Error:156");}*/
		//######### CALL WEBSERVICE (NORMAL JSON RESPONSE)
		read_json_data();
	}
	
	public static void read_json_data()
	{
		HashMap<String, String>  last_data_bin = new HashMap(new Hashtable<String, String>());
		HashMap<String, String>  max_device_time = new HashMap(new Hashtable<String, String>());
		//String vehicle_imei = "861001003846728,861001005344862,861001005327420,861001005500422";       
		//####### GET LAST TIME			
		//String last_time_tmp="";
		//last_time_tmp = get_last_time();	
		//System.out.println("VehicleIMEI="+vehicle_imei+" ,last_time_tmp="+last_time_tmp);
		String startdate = "", enddate="", startdate1="", enddate1="";
		//##### GET PREVIOUS AND CURRENT TIME
						
		//if(last_time_tmp.equals("-"))
		{
			//startdate = "2015-01-30%2005:00:00";
			Date currentDate = new Date();
			Calendar cal = Calendar.getInstance();
			cal.setTime(currentDate);
			cal.add(Calendar.MINUTE, -5);
			Date tenMinutesBack = cal.getTime();	   
			//System.out.println("tenMinutesBack="+tenMinutesBack);
			//startdate = dDF.format(tenMinutesBack);	//Uncomment if DateTime Taken 10 mins Back
			startdate = dDF.format(tenMinutesBack);		//Get StartTime as 00:00:00 of the date			
			//startdate = startdate + " 00:00:00";
			//System.out.println("startdate="+startdate);
		}
		//else
		{
			//startdate = last_time_tmp;
		}		
        //String enddate = "2015-01-30%2006:10:00";
		Date date = new Date();
		enddate = dDF.format(date);
		
		//enddate = "2015-02-12 01:55:00";
		//System.out.println("StartDateFromDB="+startdate);

		Date sd_tmp = null, ed_tmp = null;
		try {
			sd_tmp = dDF.parse(startdate);
			ed_tmp = dDF.parse(enddate);
		} catch (ParseException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		Calendar cal1 = Calendar.getInstance();
		cal1.setTime(sd_tmp);		
		startdate1 = inputDateFormat.format(cal1.getTime());
		
		Calendar cal2 = Calendar.getInstance();
		cal2.setTime(ed_tmp);		
		enddate1 = inputDateFormat.format(cal2.getTime());		
		
		System.out.println("startdate1="+startdate1+" ,enddate1="+enddate1);		
		//startdate1 ="23022015112000";
		//enddate1 = "23022015113000";
		
		startdate1 = startdate1.trim();
		enddate1 = enddate1.trim();		
		//startdate1 = inputDateFormat.format(startdate);
		//enddate1 = inputDateFormat.format(enddate);
		
        String DeviceIMEINo="",VehicleName="",DateTime="",Latitude="",Longitude="",Speed="";
        String all_data="";        
        
        int x=0,y=0;        
        
        try {
        	//String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method:\"ref_RealTimeDataFetch\",username:\"raman_itc\",password:\"novire1308\"}/{from_dateTime:\"23022015112000\",to_dateTime:\"23022015113000\"}";
//        	String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/%7Bmethod:%22ref_RealTimeDataFetch%22,username:%22raman_itc%22,password:%22novire1308%22%7D/%7Bfrom_dateTime:%2209072015163000%22,to_dateTime:%2209072015163400%22%7D";
        	String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/%7Bmethod:%22ref_RealTimeDataFetch%22,username:%22raman_itc%22,password:%22novire1308%22%7D/%7Bfrom_dateTime:%22"+startdate1+"%22,to_dateTime:%22"+enddate1+"%22%7D";

        	//http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method:"ref_RealTimeDataFetch",username:"raman_itc",password:"novire1308"}/{from_dateTime:"08072015103100",to_dateTime:"08072015104100"}        	
        	Request = Request.trim();
            System.out.println("Request="+Request);
            //URL my_url = new URL("http://www.placeofjo.blogspot.com/");
            URL my_url = new URL(Request);
            //System.out.println("my_url:"+my_url);
            BufferedReader br = null;
            try{
            	br = new BufferedReader(new InputStreamReader(my_url.openStream()));
            }catch(Exception b){System.out.println(b.getMessage());}
            //System.out.println("br:"+br);
            String strTemp = "", max_device_time_tmp="";
            while(null != (strTemp = br.readLine())){	        	
	        	//startdate1 = startdate.replace(" ","%20");
	        	//strTemp = strTemp.replace("/\\","-");
	        	System.out.println("Data1="+strTemp);
	        	
	        	String[] tmp1 = strTemp.split("\\[");
	        	//System.out.println("tmp1[1]="+tmp1[1]);
	        	String[] tmp2 = tmp1[1].split("\\]");
	        	//System.out.println("tmp2[0]="+tmp2[0]);
	        	strTemp = "["+tmp2[0]+"]";
	        	//System.out.println("strTemp="+strTemp);
	        	//strTemp = strTemp.trim();
	        	//strTemp = strTemp.trim();
	        	try{
	        		//String reader = "[{\"IMEINo\":\"123\",\"Speed\":30},{\"IMEINo\":\"456\",\"Speed\":40}]";
	        		JSONArray jArray = new JSONArray(strTemp);

	        		//System.out.println("jArray.length()="+jArray.length());
	        		for(int i = 0 ; i <jArray.length();i++){
	
	        			JSONObject jObject = jArray.getJSONObject(i);
	        			//System.out.println("IMEI="+jObject.getString("IMEINo"));
	        			//System.out.println("Speed="+jObject.getInt("Speed"));
	        			
	        			DeviceIMEINo = jObject.getString("ref_deviceNo");
	        			//System.out.println("DeviceIMEINo="+DeviceIMEINo);	        			
	        			VehicleName = jObject.getString("ref_vehicleNo");
	        			System.out.println(DeviceIMEINo+"::"+VehicleName);
	        			String tmpd0 = jObject.getString("ref_date");
	        			String[] tmpd1 = tmpd0.split(" ");
	        			String[] tmpd2 = tmpd1[0].split("/");
	        			
	        			DateTime = tmpd2[2]+"-"+tmpd2[0]+"-"+tmpd2[1]+" "+tmpd1[1];
	        			
	        			Latitude = Double.toString(jObject.getDouble("ref_latitude"));
	        			Longitude = Double.toString(jObject.getDouble("ref_longitude"));
	        			Speed = Double.toString(jObject.getDouble("ref_velocity"));
	        			//System.out.println("Spd="+jObject.getDouble("CurrentSpeed"));
	        			//all_data = all_data+""+DeviceIMEINo+"#"+DateTime+"#"+Latitude+"#"+Longitude+"#"+Speed+";";
	        		//	System.out.println("AllData="+all_data);
	        			//max_device_time.put(DeviceIMEINo, DateTime);
	        			//System.out.println("DeviceIMEINo="+DeviceIMEINo+" ,DateTime="+DateTime+" ,Latitude="+Latitude+" ,Longitude="+Longitude+", Speed="+Speed);
	        			
	        			createXmlFile(DeviceIMEINo, DateTime, Latitude, Longitude, Speed);
	        			
	        			//###### UPDATE LAST LOCATION
	        			//System.out.println("BeforeLastLoc");
	        			write_last_location(DeviceIMEINo, DateTime, Latitude, Longitude, Speed);
	        			//System.out.println("AfterLastLoc");
	        			
	        			//##### CALL ALERT MODULE
	        			alert_module.write_final_alert_data(DeviceIMEINo, DateTime, enddate, Latitude, Longitude, Speed, "0", "0");
	        		}
	
        		}catch (Exception e) {
        			e.printStackTrace();
        			//return;
        		}
			}            
        }catch(Exception e){}

        try 
		{
			//Thread.sleep(600000);  //10 mins
			//Thread.sleep(60000); //1 mins
			Thread.sleep(300000);  //5 mins
			read_json_data();
		} 
		catch (InterruptedException ie) 
		{
			//Handle exception
		}        
	}
	
	
	public static void createXmlFile(String DeviceIMEINo, String DateTime, String Latitude, String Longitude, String Speed)
	{	
		//String RealDataPath = "D:\\itrack_vts/xml_vts_java/last_location";		
		//String LogDataPath = "/home/current_data/xml_data";
		//String RealDataPath = "/home/current_data/last_location";
		String MsgType = "", strOrig="", Version = "";
		String Fix = "1", io_value1 ="0",io_value2 ="0",io_value3="0",io_value4="0",io_value5="0",io_value6="0",io_value7="0",io_value8="0", Signal_Strength="0";
		
		GregorianCalendar calendar = new GregorianCalendar();
	
		/*Calendar cal = Calendar.getInstance(); // creates calendar
		cal.setTime(new Date()); // sets calendar time/date
		cal.add(Calendar.HOUR_OF_DAY, 1); // adds one hour
		date=(Date) cal.getTime();*/
		
		//formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	
		Date result;
		String serverdatetime="";
		try 		
		{
			Date date = new Date();
			serverdatetime = dDF.format(date);
			//result = sDF.parse(ReceiveDateFormat);			
			//DateTime = dDF.format(result);			
		}
		catch(Exception e) 
		{
			// TODO Auto-generated catch block
			e.printStackTrace();
		}  
	   	   		
		//Statement statement;
		//ResultSet result;
		//result=null;
		//statement=null;
		//ResultSetMetaData rsmd;
		//StringTokenizer st,st4;	
		float speed_f = 0.F;
		float supv_f = 0.F;
		
		strOrig = Speed;   
		char[] stringArray1;
		stringArray1 = strOrig.toCharArray();
		   
		//display the array    
		for(int index=0; index < stringArray1.length; index++)   
		{
		  if( (stringArray1[index]=='0' || stringArray1[index]=='1'  || stringArray1[index]=='2'  || stringArray1[index]=='3' || stringArray1[index]=='4' || stringArray1[index]=='5' || stringArray1[index]=='6' || stringArray1[index]=='7' || stringArray1[index]=='8' || stringArray1[index]=='9') )
		  {
			//System.out.print(stringArray[index]);
		  }  
		  else
		  {
			stringArray1[index] = '.';
		  }    
		  //System.out.print(stringArray[index]);
		}
				
		Speed = new String(stringArray1);
		speed_f = Float.parseFloat(Speed);
		speed_f = (float) (Math.round(speed_f*100.0)/100.0);	
		//int NoofToken,i=0,j=0,k=0;
		int GPSYear,GPSMonth,GPSDay,GPSHr,GPSMin,GPSSec;
		double Latitudetmp=0.0,Longitudetmp=0.0;
		String marker_a1="",marker_a2="";
		String folderDate="",RFID="";
		StringTokenizer st;
//		int SerialNo = 0;
		//String[] data = new String[30];
		//System.out.println(Response);
		//st = new StringTokenizer(Response,";");		//Parsing Input
		//NoofToken = st.countTokens();
		//System.out.println("No of Token="+NoofToken);
//	
		st = new StringTokenizer(serverdatetime," ");
		folderDate = st.nextToken();
		
		String q="\"";
		String mydir1 = LogDataPath;
		boolean success1 = (new File(mydir1 + "/" + folderDate)).mkdir();
		//System.out.println("Success="+success1);							
		 
		 RandomAccessFile raf1 =null;
		// boolean FilehandlerReceived = false;
		 //FileWriteHandler CurrentFileWriteHandler = null;
		 //BufferedWriter out_a1 =null;
		 //BufferedWriter out_a2 =null;
		 String SFile = LogDataPath+"/"+folderDate+"/"+DeviceIMEINo+".xml";
		 try 
		 {
			raf1 = new RandomAccessFile(SFile, "rwd");
		 } 
		 catch (FileNotFoundException e)
		 {
			// TODO Auto-generated catch block
			e.printStackTrace();
		 }
		 long length1=0;
		try 
		{
			length1 = raf1.length();
		} 
		catch (IOException e) 
		{
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		if(length1<10)
		{			
			 try 
			 {
				raf1.seek(0);
				marker_a2 = "<t1>\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+speed_f+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+io_value1+q+" j="+q+io_value2+q+" k="+q+io_value3+q+" l="+q+io_value4+q+" m="+q+io_value5+q+" n="+q+io_value6+q+" o="+q+io_value7+q+" p="+q+io_value8+q+" q="+q+Signal_Strength+q+" r="+q+supv_f+q+"/>\n</t1>";
				raf1.writeBytes(marker_a2);
			 } 
			catch (IOException e) 
			{
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}				
		else
		{
			try 
			{
				raf1.seek(length1-6);										
				//marker_a2 = "\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+speed_f+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+ln_io_value1+q+" j="+q+ln_io_value2+q+" k="+q+ln_io_value3+q+" l="+q+ln_io_value4+q+" m="+q+ln_io_value5+q+" n="+q+ln_io_value6+q+" o="+q+ln_io_value7+q+" p="+q+ln_io_value8+q+" q="+q+Signal_Strength+q+" r="+q+supv_f+q+"/>\n</t1>";
				marker_a2 = "\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+speed_f+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+io_value1+q+" j="+q+io_value2+q+" k="+q+io_value3+q+" l="+q+io_value4+q+" m="+q+io_value5+q+" n="+q+io_value6+q+" o="+q+io_value7+q+" p="+q+io_value8+q+" q="+q+Signal_Strength+q+" r="+q+supv_f+q+"/>\n</t1>";
				//CurrentFileWriteHandler.StrBuf.append(marker_a2);
				//if(((System.currentTimeMillis()-CurrentFileWriteHandler.UpdateTime)>120000) || (CurrentFileWriteHandler.StrBuf.length()>6000))
				{
					//CurrentFileWriteHandler.StrBuf.append("\n</t1>");
					//raf1.writeBytes(CurrentFileWriteHandler.StrBuf.toString());
					//System.out.println("BeforeWrite2");
					raf1.writeBytes(marker_a2);
					//CurrentFileWriteHandler.UpdateTime = System.currentTimeMillis();
					//CurrentFileWriteHandler.StrBuf.setLength(0);
					//System.out.println("AfterWrite2");
				}
			} 
			catch (IOException e) 
			{
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
				//System.out.println("FilehandlerReceived1:"+FilehandlerReceived+" "+SFile);
		}
	}
	
	public static void write_last_location(String DeviceIMEINo, String DateTime, String Latitude, String Longitude, String Speed)
	//public void write_last_location(String filename, String MsgType, String vserial, String Version, String Fix, String Latitude, String Longitude, String Speed, String serverdatetime, String DateTime, String io_value1, String io_value2, String io_value3, String io_value4, String io_value5, String io_value6, String io_value7, String io_value8, String Signal_Strength, String SupplyVoltage)
	{
		RandomAccessFile out_a2 =null;
		//************WRITE LAST LOCATION FILE **********************
		//String filename = "/var/www/html/itrack_vts/xml_vts/last_location/"+vserial+".xml";
		String marker_a2="", q="\"", xml_date="", xml_last_halt_time="", xml_day_max_speed="", xml_day_max_speed_time="", xml_lat="", xml_lng="";
		String last_halt_time = "", day_max_speed="", day_max_speed_time="", xml_lat_s = "", xml_lng_s = "", Latitude_s="", Longitude_s="";
		float speed_f = 0.F;
		float supv_f = 0.F;
		
		String MsgType = "", strOrig="", Version = "", strLine1="", io_pwrvolt="";
		String Fix = "1", io_value1 ="0",io_value2 ="0",io_value3="0",io_value4="0",io_value5="0",io_value6="0",io_value7="0",io_value8="0", Signal_Strength="0";
		
		strOrig = Speed;   
		char[] stringArray1;
		stringArray1 = strOrig.toCharArray();
		   
		//display the array    
		for(int index=0; index < stringArray1.length; index++)
		{
		  if( (stringArray1[index]=='0' || stringArray1[index]=='1'  || stringArray1[index]=='2'  || stringArray1[index]=='3' || stringArray1[index]=='4' || stringArray1[index]=='5' || stringArray1[index]=='6' || stringArray1[index]=='7' || stringArray1[index]=='8' || stringArray1[index]=='9') )
		  {
			//System.out.print(stringArray[index]);
		  }  
		  else
		  {
			stringArray1[index] = '.';
		  }    
		  //System.out.print(stringArray[index]);
		}
		
		
		Speed = new String(stringArray1);
		speed_f = Float.parseFloat(Speed);
		speed_f = (float) (Math.round(speed_f*100.0)/100.0);
		float tmp_speed = 0.0f;

		day_max_speed = Speed;
	
		/*try {
			Date result;
			result = sDF.parse(ReceiveDateFormat);
			DateTime = dDF.format(result);	
		} catch (ParseException e2) {
			// TODO Auto-generated catch block
			e2.printStackTrace();
		}*/			
		
		day_max_speed_time = DateTime; //DEFAULT ASSINGMENT
		Date date_last_loc1=null, date_last_loc2=null, date_servertime2=null;
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

		String tmpstr2="";
		//tmpstr2 = "/var/www/html/itrack_vts/xml_vts/last_location/"+vserial+".xml";
		tmpstr2 = RealDataPath+"/"+DeviceIMEINo+".xml";
		
		//ADD ONE HOUR TO SERVERTIME
		int minutesToAdd = 60; // 1 hrs
		
		String serverdatetime="";
		try
		{
			//System.out.println("Date:"+DateTime+" ,serverdatetime="+serverdatetime);
			date_last_loc2 = (Date) sdf.parse(DateTime); //PARSE DATE LAST LOCATION
			
			Calendar cal = Calendar.getInstance();
			Date date = new Date();
			serverdatetime = dDF.format(date);
			cal.setTime(sdf.parse(serverdatetime));
			cal.add(Calendar.MINUTE, minutesToAdd);
			//System.out.println(cal.getTime());
		
			String TimeStop_Str = sdf.format(cal.getTime());
			//System.out.println(TimeStop_Str);
			date_servertime2 = (Date) sdf.parse(TimeStop_Str); //PARSE SERVER DATETIME
		} 
		catch(Exception e1)
		{
			System.out.println("ErrorLastLocation1="+e1.getMessage());
		}
		
		boolean FileNew = false;
		long FileLength=0;

		//System.out.println("date_last_loc2="+date_last_loc2+" ,date_servertime2="+date_servertime2);
		try
		{
			out_a2 = new RandomAccessFile(tmpstr2, "rwd");
			//out_a2 = new RandomAccessFile(filename, "rw");
			FileLength = out_a2.length();
			out_a2.seek(0);
			
			if(FileLength<10)
			{
				//System.out.println("IF");
				last_halt_time = DateTime;
				if((date_last_loc2.compareTo(date_servertime2) < 0) && (!Latitude.equals("")) && (!Longitude.equals("")) && (!Latitude.equals("0.0")) && (!Longitude.equals("0.0")) )
				{					
					marker_a2 = "<t1>\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+speed_f+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+io_value1+q+" j="+q+io_value2+q+" k="+q+io_value3+q+" l="+q+io_value4+q+" m="+q+io_value5+q+" n="+q+io_value6+q+" o="+q+io_value7+q+" p="+q+io_value8+q+" q="+q+Signal_Strength+q+" r="+q+supv_f+q+"/>\n</t1>";				
					//System.out.println("WriteFile:FirstTime="+marker_a2);
					out_a2.seek(0);
					out_a2.writeBytes(marker_a2);
				
					//System.out.println("T1");
					//out_a2.close();
				}
			}
			//########## BLANK FILE CLOSED
			else
			{
				//System.out.println("ELSE");
				while ((strLine1 = out_a2.readLine()) != null)
				{
					int len = strLine1.length();
				
					if(len < 100)
					{
						continue;
					}
					xml_lat = getXmlAttribute(strLine1,"d=\"[^\"]+");
					xml_lng = getXmlAttribute(strLine1,"e=\"[^\"]+");
					xml_date = getXmlAttribute(strLine1,"h=\"[^\"]+");
					//last_date = xml_date;
					xml_day_max_speed = getXmlAttribute(strLine1,"s=\"[^\"]+");
					xml_day_max_speed_time = getXmlAttribute(strLine1,"t=\"[^\"]+");
					xml_last_halt_time = getXmlAttribute(strLine1,"u=\"[^\"]+");

					//####### HANDLE EMPTY VARIABELS

					if(Latitude.equals(""))
					{
						Latitude = "0.0";
					}
					if(Longitude.equals(""))
					{
						Longitude = "0.0";
					}
					if(xml_lat.equals(""))
					{
						xml_lat = "0.0";
					}
					if(xml_lng.equals(""))
					{
						xml_lng = "0.0";
					}
					if(xml_day_max_speed.equals(""))
					{
						xml_day_max_speed = "0.0";
					}
					if(Speed.equals(""))
					{
						Speed = "0.0";
					}
					if(xml_day_max_speed_time.equals(""))
					{
						xml_day_max_speed_time = DateTime;
					}
					//############################
				
					//######LAST HALT TIME BLOCK
					Latitude_s = Latitude.substring(0, Latitude.length() - 1);
					Longitude_s = Longitude.substring(0, Longitude.length() - 1);
				
					xml_lat_s = xml_lat.substring(0, xml_lat.length() - 1);
					xml_lng_s = xml_lng.substring(0, xml_lng.length() - 1);
					//System.out.println("One");
					float distance1 = calculateDistance(Float.parseFloat(Latitude_s), Float.parseFloat(xml_lat_s), Float.parseFloat(Longitude_s), Float.parseFloat(xml_lng_s) );
					long time_diff = calculateTimeDiff(DateTime, xml_date); //Seconds
					time_diff = time_diff / 3600;
					//System.out.println("Two");
					//$tmp_time_diff1 = (strtotime($datetime) - strtotime($last_time1)) / 3600;
					if(time_diff>0)
					{
						tmp_speed = distance1 / (float) time_diff;
					}

					//System.out.println("tmp_speed="+tmp_speed+" ,distance="+distance1+" ,time_diff="+time_diff);
					if(tmp_speed>100.0 && distance1>0.1 && time_diff>0)
					{
				
					}
					else
					{
						//##### LAST HALT TIME
						if(Float.parseFloat(Speed) > 10.0)
						{
							last_halt_time = DateTime;
						}
						else
						{
						if(xml_last_halt_time.equals(""))
						{
							last_halt_time = DateTime;
						}
						else
						{
							last_halt_time = xml_last_halt_time;
						}
					}

					//###### DAY MAX SPEED AND TIME
					Float f1 = new Float(xml_day_max_speed);
					double d1 = f1.doubleValue();
				
					Float f2 = new Float(Speed);
					double d2 = f2.doubleValue();

					//System.out.println("xml_day_max_speed="+xml_day_max_speed+", Speed="+Speed);
					//System.out.println("d1="+d1+", d2="+d2);
				
					if(d2 > d1)
					{
						//System.out.println("condition if");
						day_max_speed = Speed;
						day_max_speed_time = DateTime;
					}
					else
					{
						//System.out.println("condition else");
						day_max_speed = xml_day_max_speed;
						day_max_speed_time = xml_day_max_speed_time;
					}

					//## RESET SPEED IF DAY CHANGES
					String[] daytmp1,day1,daytmp2,day2;
					String delimiter1 = " ",delimiter2="-";
					daytmp1 = xml_date.split(delimiter1);
					daytmp2 = DateTime.split(delimiter1);
				
					day1 = daytmp1[0].split(delimiter2);
					day2 = daytmp2[0].split(delimiter2);

					//System.out.println("day1="+day1[2]+" ,day2="+day2[2]);
					if(!(day1[2].equals(day2[2])))
					{
						//System.out.println("IN day1,day2");
						day_max_speed = "0.0";
						day_max_speed_time = DateTime;
						//System.out.println("day1="+day1[2]+" ,day2="+day2[2]);
					}
				}
				try
				{
					date_last_loc1 = (Date) sdf.parse(xml_date); //XML DATETIME
				}
				catch(Exception e)
				{
					System.out.println(e.getMessage());
				}
				//if( (date_last_loc2.compareTo(date_last_loc1) > 0) && (date_last_loc2.compareTo(date_servertime2) < 0) && (date_last_loc2.compareTo(valid_date_min) > 0) && (date_last_loc2.compareTo(valid_date_max) < 0) )
				if( (date_last_loc2.compareTo(date_last_loc1) > 0) && (date_last_loc2.compareTo(date_servertime2) < 0) && (!Latitude.equals("")) && (!Longitude.equals("")) && (!Latitude.equals("0.0")) && (!Longitude.equals("0.0")) )
				{
					//System.out.println("WRITE TO LAST LOCATION FILE:"+filename);
					out_a2.seek(0);
					//marker_a2 = "<t1>\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+speed_f+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+io_value1+q+" j="+q+io_value2+q+" k="+q+io_value3+q+" l="+q+io_value4+q+" m="+q+io_value5+q+" n="+q+io_value6+q+" o="+q+io_value7+q+" p="+q+io_value8+q+" q="+q+Signal_Strength+q+" r="+q+supv_f+q+"/>\n</t1>";
					marker_a2 = "<t1>\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+speed_f+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+io_value1+q+" j="+q+io_value2+q+" k="+q+io_value3+q+" l="+q+io_value4+q+" m="+q+io_value5+q+" n="+q+io_value6+q+" o="+q+io_value7+q+" p="+q+io_value8+q+" q="+q+Signal_Strength+q+" r="+q+io_pwrvolt+q+" s="+q+day_max_speed+q+" t="+q+day_max_speed_time+q+" u="+q+last_halt_time+q+"/>\n</t1>";
					out_a2.writeBytes(marker_a2);			
					//System.out.println("T2");
					//System.out.println("WRITE TO LAST LOCATION FILE:"+marker_a2);
					break;
					//out_a2.close();
					//System.out.println(marker_a2);
				}
			}
		}
		out_a2.close();	
	}
	catch (IOException e)
	{
		e.printStackTrace();
		try
		{
			out_a2.close();
		}
		catch (Exception e1)
		{
	
		}
	}	
	}
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
		return new Float(dist * meterConversion).floatValue(); //KM
	}
	
	public static String getXmlAttribute(String line, String param)
	{
		//System.out.println("In getXmlAttrib: line="+line+" ,param="+param);
		String str1 ="";
		String value ="";
		String[] str2;	
		try 
		{
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
		} 
		catch(Exception e) 
		{ 
			System.out.println("Error in function-Xml Attribute"+e.getMessage());
		}
	
		return value;
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
		long diffSeconds= diffMinutes * 60; //IN SECONDS
		return diffSeconds;
	}
	
	public static String read_last_date_xml(String imei)
	{
		String strLine1="", xml_date="";
		RandomAccessFile out_a2 =null;
		String tmpstr2="";
		tmpstr2 = RealDataPath+"/"+imei+".xml";

		try
		{
			out_a2 = new RandomAccessFile(tmpstr2, "rwd");
			long FileLength = out_a2.length();
			
			if(FileLength<10)
			{							
				while ((strLine1 = out_a2.readLine()) != null)
				{
					int len = strLine1.length();
				
					if(len < 100)
					{
						continue;
					}
					xml_date = getXmlAttribute(strLine1,"h=\"[^\"]+");
					return xml_date;
				}
			}
		} catch(Exception e){}
		return null;
	}
	
	public static void get_vendor_vehicles()
	{		
		try{
		      //STEP 2: Register JDBC driver
		  try {
			Class.forName("com.mysql.jdbc.Driver");
		  } catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		  }
		  //STEP 3: Open a connection	  	  
		  conn_remote = DriverManager.getConnection(DB_URL_remote,USER,PASS);
	  	  //System.out.println("Connection to 156 -Remote database-ok:"+conn_remote+" ,DB_URL_remote="+DB_URL_remote+",USER="+USER+" PASS="+PASS);
		}catch(SQLException se){}
		
		try{
			//System.out.println("In GetVendor vehicles");
			stmt_remote = conn_remote.createStatement();
			String sql_remote;
			sql_remote = "select vehicle.vehicle_name from vehicle,vehicle_gpsvendor_assignment,gps_vendor,vehicle_assignment where "+
			"vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 "+
			"AND vehicle_assignment.device_imei_no= vehicle_gpsvendor_assignment.device_imei_no AND "+
			"vehicle_gpsvendor_assignment.gps_vendor_id = 16 AND vehicle_gpsvendor_assignment.status=1 "+
			"AND gps_vendor.gps_vendor_id= vehicle_gpsvendor_assignment.gps_vendor_id AND gps_vendor.status=1 AND vehicle.status=1";
			
			//select DISTINCT gps_vendor_name,gps_vendor_id from vehicle_gpsvendor_assignment where status=1;
			//System.out.println("SQL="+sql_remote);
			
			ResultSet rs_remote = stmt_remote.executeQuery(sql_remote);
		    while(rs_remote.next()){
		         try{						
					//System.out.println("IN 156");
		        	vehicle_imei = vehicle_imei+""+rs_remote.getString("device_imei_no")+",";
		         }catch(SQLException sq) {System.out.println("Error in Con:156");}
		    }
		    vehicle_imei = vehicle_imei.substring(0,vehicle_imei.length()-1);	
			//System.out.println("SQL="+sql);			
			rs_remote.close();
			stmt_remote.close();
			conn_remote.close();
		}catch(SQLException se2){System.out.println("conn_remote error");}		
	}
	
	public static String get_last_time()
	{
		stmt = null;  
		try {
			stmt = conn.createStatement();
			//System.out.println("STMT="+stmt);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			//System.out.println(e.getMessage());
			e.printStackTrace();
		}
		String sql;
		sql = "SELECT last_time FROM webservice_last_time WHERE vendor='noviretech'";
		//System.out.println("SQL="+sql);
		ResultSet rs = null;
		try {
			rs = stmt.executeQuery(sql);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		Timestamp date_db;
		//STEP 5: Extract data from result set
		try {
			if(rs.next()){
				//Retrieve by column name
				//String alert_str = rs.getString("last_time");
				date_db = rs.getTimestamp("last_time");
				String alert_str = sdf.format(date_db);
				return alert_str;
				//Display values
				//System.out.print("Alert_str=" + alert_str);
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		//STEP 6: Clean-up environment
		try {
			rs.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		try {
			stmt.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		
		return null;
	}

	public static void update_last_time(String last_time)
	{
		collect_data_main.stmt = null;
		try{
		  //STEP 2: Register JDBC driver

		  //STEP 4: Execute a query
//		      System.out.println("Updating data...");
		  try {
			  collect_data_main.stmt = collect_data_main.conn.createStatement();
		  } catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		  }
		  String sql;
		  sql = "UPDATE webservice_last_time SET last_time='"+last_time+"' WHERE vendor='noviretech'";
		  collect_data_main.stmt.executeUpdate(sql);
		}catch(SQLException e){}
	}
	
	private static String getYesterdayDateString() {
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Calendar cal = Calendar.getInstance();
        cal.add(Calendar.DATE, -1);    
        return dateFormat.format(cal.getTime());
	}
	
}