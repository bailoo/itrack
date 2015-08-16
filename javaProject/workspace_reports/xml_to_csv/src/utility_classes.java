
//import java.sql.Date;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
//import java.beans.Statement;
import java.sql.Statement;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.text.DateFormat;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;


public class utility_classes {
				
	public static Connection get_connection()
	{
		Connection connection = null;																							
	    String url = "jdbc:mysql://localhost:3306/";
	    //String url = "jdbc:mysql://111.118.181.156:3306/";
		String db = "iespl_vts_beta";
	    String driver = "com.mysql.jdbc.Driver";
	    String user = "root";
	    String pass = "mysql";

	    try 
		{
	        Class.forName(driver);
	        connection = DriverManager.getConnection(url + db, user, pass);
		} 
		catch (Exception e)		
		{
	        System.out.println("ERROR IN DATABASE CONNECTION");
	    }
		return connection;															
	}
	
	public static void main(String[] args)
	{
		/*String date1 = "01.12.2012#123#1#1.xlsx";
		String [] words = date1.split("\\.");
		System.out.println("size="+words.length);
		
		for(int len=0;len<words.length;len++)
		{			
			if(len==words.length-1)
			{
				System.out.println("EX="+words[len]);
			}
			else
			{
				System.out.println(words[len]);
			}
		}*/
		//String date_str = "2012-06-08";
		/*String date_str = "2012-06-08";
		long secs = get_seconds(date_str, 1);
		//System.out.println("secs="+secs);
		
		ArrayList<String> user_dates = get_All_Dates("2012-06-08","2012-06-10");
		
		for(int i=0;i<user_dates.size();i++)
		{
			String user_dates_str = (String)user_dates.get(i);
			//System.out.println("date"+i+"="+user_dates_str);
		}
		//m_array();
		
		String formated_time = get_hms(9620);
		System.out.println(formated_time);
		
		//final String DATE_FORMAT_NOW = "yyyy-MM-dd HH:mm:ss";
		Date date = new Date();
		SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		//SimpleDateFormat formatter = new SimpleDateFormat("HH:mm:ss");
		String serverdatetime = formatter.format(date);
		//System.out.println(serverdatetime);
		
		String abc = "hello-hi";
		
		String[] xyz = abc.split("-");

		//System.out.println("xyz[0]="+xyz[0]);
		*/
	}
	
	public static long get_seconds(String date_str, int type)
	{
		SimpleDateFormat format = null;
		long seconds = 0;
	
		//System.out.println("date_str="+date_str+" ,type="+type);	
		if(type == 1)
		{
			format = new SimpleDateFormat("yyyy-MM-dd");
		}
		
		else if(type == 2)
		{
			format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		}
		
		try{
		Date date = (Date) format.parse(date_str);	  		
		seconds = (long) ((date.getTime()) / 1000);
		//System.out.println("Seconds is " +seconds);
		}catch(Exception esec) {System.out.println("Error in date conversion to seconds="+esec.getMessage());}
		
		return seconds;		
	}
	
	public static ArrayList get_All_Dates(String fromDate, String toDate)
	{
		ArrayList<String> dateMonthYearArr = new ArrayList<String>();
		long fromDateTS = get_seconds(fromDate, 1);
		long toDateTS = get_seconds(toDate, 1);

		for (long currentDateTS = fromDateTS; currentDateTS <= toDateTS; currentDateTS += (60 * 60 * 24)) {
			// use date() and $currentDateTS to format the dates in between
			//String currentDateStr = date("Y-m-d",currentDateTS);
			long dateInMill = currentDateTS * 1000;
			DateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
	        String datef = formatter.format(dateInMill);
	       
	        //System.out.println("datef="+datef);
	        dateMonthYearArr.add(datef);
		}

		return dateMonthYearArr;		
	}
	
	/************* METHOD- CALCULATE DISTANCE ************/
	public static float calculateDistance(float lat1, float lat2, float lng1, float lng2) 
	{	    
		//System.out.println("In CACL DIST : lat1 : "+lat1+" lng1 : "+lng1+" lat2 : "+lat2 + " lng2 : "+lng2);
		double earthRadius = 3958.75;
		//double earthRadius = 6378.1;
		double dLat = Math.toRadians(lat2-lat1);
		double dLng = Math.toRadians(lng2-lng1);
		double a = Math.sin(dLat/2.0) * Math.sin(dLat/2.0) +
		Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) *
		Math.sin(dLng/2.0) * Math.sin(dLng/2.0);
		double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
		double dist = earthRadius * c;
		int meterConversion = 1609;
		return new Float(dist * meterConversion).floatValue()/1000.0f;		
    }

	public static double calculateDistance2(double lat1, double lat2, double lng1, double lng2)
	{
		double d2r = Math.PI / 180;
		double distance = 0;
	
		try{
			double dlong = (lng2 - lng1) * d2r;
			double dlat = (lat2 - lat1) * d2r;
			double a =
			Math.pow(Math.sin(dlat / 2.0), 2)
			+ Math.cos(lat1 * d2r)
			* Math.cos(lat2 * d2r)
			* Math.pow(Math.sin(dlong / 2.0), 2);
			double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
			double d = 6367 * c;
		
			return d;
		} catch(Exception e){
			e.printStackTrace();
		}
		return 0.0;
	}	
	
	/*public static void m_array()
	{
		//String[][] Data = Array;
		 String[][] Data = new String[4][4];
		 //Assign the values, do it either dynamically or statically
		 //For first fow
		 Data[0][0] = "S"; //lastname
		 Data[0][1] = "Pradeep"; //firstname
		 Data[0][2] = "Kolkata"; //location
		 
		 //Second row
		 Data[1][0] = "Bhimani"; //lastname
		 Data[1][1] = "Shabbir"; //firstname
		 Data[1][2] = "Kolkata"; //location
		 
		 //Add as many rows you want
		 
		 //printing
		 //System.out.print("Lastname\tFirstname\tLocation\n");
		 for(int i=0;i<2;i++)
		 {
		   for(int j=0;j<3;j++)
		   {
		     //System.out.print(Data[i][j]+"\t");
		   }
		   //move to new line
		   //System.out.print("\n");
		 }
	}*/
	
	   public static String get_hms(long timeInSeconds) {
		   
		  if(timeInSeconds>86400)
			  timeInSeconds = 86400;
		  
		  long hours, minutes, seconds;
	      hours = timeInSeconds / 3600;
	      timeInSeconds = timeInSeconds - (hours * 3600);
	      minutes = timeInSeconds / 60;
	      timeInSeconds = timeInSeconds - (minutes * 60);
	      seconds = timeInSeconds;
	      //System.out.println(hours + " hour(s) " + minutes + " minute(s) " + seconds + " second(s)");
	      return (hours+":"+minutes+":"+seconds);
	   }
	   
		
	   public static boolean check_with_range_landmark(String lat1, String lng1, String lat2, String lng2, float distance_var)
		{
		    boolean status_geo = false; 
		   
		    //System.out.println("HC1::DISTANCE_VAR1="+distance_var);
		    //System.out.println("IN DISTANCE::lat1="+ Float.parseFloat(lat1)+" ,lat2="+Float.parseFloat(lat2)+" ,lng1="+Float.parseFloat(lng1)+" ,lng2="+Float.parseFloat(lng2));
		    
		    float distance1 = calculateDistance(Float.parseFloat(lat1),Float.parseFloat(lat2),Float.parseFloat(lng1),Float.parseFloat(lng2));
  
		    //System.out.println("HC2::DISTANCE="+ distance1+" ,DISTANCE_VAR2="+distance_var);
		    		
		    if(distance1 < distance_var)	  
			{                                                        
				  status_geo = true; 
			}  
			else
			{
				  status_geo = false;
			}
			  
			return status_geo;
		}
	   
	   ///////////////// COPY FILE ////////////////////
	   public static void copyfile(String srFile, String dtFile)
	   {
	   	try
	   	{
	   		//System.out.println("srFile="+srFile+" ,dtFile="+dtFile);
	   		File f1 = new File(srFile);
	   		File f2 = new File(dtFile);

	   		//System.out.println("test1");
	   		FileReader in = new FileReader(f1);

	   		//For Append the file.
	   		//      OutputStream out = new FileOutputStream(f2,true);

	   		//For Overwrite the file.
	   		FileWriter out = new FileWriter(f2);

	   		int c;
	   		while ((c = in.read()) != -1)
	   		out.write(c);

	   		in.close();
	   		out.close();
	   		System.out.println("File copied.");
	   	}
	   	catch(FileNotFoundException ex)
	   	{
	   		System.out.println(ex.getMessage() + " in the specified directory.");
	   		System.exit(0);
	   	}
	   	catch(IOException e)
	   	{
	   		System.out.println(" ERROR in copying="+e.getMessage());      
	   	}
	   }	   
	  
		/************* METHOD- ROUND TO TWO DECIMAL ************/
	   public static float Round(float Rval, int Rpl) 
	   {
		   float p = (float)Math.pow(10,Rpl);
		   Rval = Rval * p;
		   float tmp = Math.round(Rval);
		   return (float)tmp/p;
	   } 
	   
		///////////// GET ALL STATION DIRECTORIES ///////////////
		/*static void GetAllDirectories(File aFile, String type, alert_variables avd) 
		{
		    System.out.println("type="+type+" ,avd.ACC_DIRSIZE="+avd.station_account_diretories.size()+"  ,avd.DATE_DIRSIZE="+avd.station_date_diretories.size());
			avd.spc_count1++;
		    String spcs = "";
		    for (int i = 0; i < avd.spc_count1; i++)
		      spcs += " ";
		    	//if(aFile.isFile())
		      	//System.out.println(spcs + "[FILE] " + aFile.getName());
		    	//else if (aFile.isDirectory()) {
			  if (aFile.isDirectory()) 
			  {		  
			      //System.out.println(spcs + "[DIR] " + aFile.getName());			
				  if(type.equalsIgnoreCase("account_dir"))
				  {
					  avd.station_account_diretories.add(aFile.getName());
				  }
				  
				  else if(type.equalsIgnoreCase("date_dir"))
				  {
					  avd.station_date_diretories.add(aFile.getName());
				  }
				  		
				  File[] listOfFiles = aFile.listFiles();
			      
				  if(listOfFiles!=null) 
			      {
					System.out.println("len="+listOfFiles.length);
					for (int i = 0; i < listOfFiles.length; i++)
			        {
			        	if(type.equalsIgnoreCase("account_dir"))
			        	{
			        		GetAllDirectories(listOfFiles[i], "account_dir", avd);
			        	}
			        	else if(type.equalsIgnoreCase("date_dir"))
			        	{
			        		GetAllDirectories(listOfFiles[i], "date_dir", avd);
			        	}
			        }
			      } 
			      else 
			      {
			    	  System.out.println(spcs + " [ACCESS DENIED]");
			      }
			  } // IF CLOSED
			  avd.spc_count1--;
		    }
		    */
	   
	   public static void get_all_directories(String fname, String type, ArrayList<String> DirBuffer)
	   {
	 	  File dir = new File(fname);
	 	    String[] chld = dir.list();
	 	  if(chld == null){
	 	  System.out.println("Specified directory does not exist or is not a directory.");
	 	  // System.exit(0);
	 	    }else{
		 	  for(int i = 0; i < chld.length; i++){
			 	  String fileName = chld[i];				 	  
			 	  
			 	  System.out.println(fileName+" ,type="+type);
				  
				  DirBuffer.add(fileName);
			 	  
				/* if(type.equalsIgnoreCase("account_dir"))
				  {
					  DirBuffer.add(fileName);
				  }
				  
				  else if(type.equalsIgnoreCase("date_dir"))
				  {
					  DirBuffer.add(fileName);
				  }*/
		 	  }
	 	  }
	   }
	   
	   public static Date ExcelDateParse(int ExcelDate){
		    Date result = null;
		    try{
		        GregorianCalendar gc = new GregorianCalendar(1900, Calendar.JANUARY, 1);
		        gc.add(Calendar.DATE, ExcelDate - 1);
		        result = gc.getTime();
		    } catch(RuntimeException e1) {}
		    return result;
	   }
	   
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
			} catch(Exception e) { System.out.println("Error in function-Xml Attribute"+e.getMessage());}
			
			return value;		
		}	   
}
