/**********************************************************
 * RTS Implementation
 * Copyright (C) 2011  Amit Kumar(amitkriit@gmail.com)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ***********************************************************/
package com.wanhive.rts;

import java.io.BufferedReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.RandomAccessFile;
import java.io.StringReader;
import java.net.InetAddress;
import java.net.InetSocketAddress;
import java.net.URL;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.channels.SelectionKey;
import java.nio.channels.Selector;
import java.nio.channels.ServerSocketChannel;
import java.nio.channels.SocketChannel;
import java.nio.channels.spi.SelectorProvider;
import java.nio.charset.Charset;
import java.nio.charset.CharsetEncoder;
import java.util.Date;
import java.rmi.RemoteException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.Locale;
import java.util.Set;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.tempuri.GetVehicleLatestDataResponseGetVehicleLatestDataResult;
import org.tempuri.ServiceSoapProxy;
import org.tempuri.TrackSoapProxy;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;


//import com.wanhive.rts.utils.ASCII;
import com.wanhive.rts.alert_module;
import com.wanhive.rts.handler.RequestHandler;
import com.wanhive.rts.utils.Application;
import com.wanhive.rts.utils.Config;

import org.apache.axis.message.MessageElement;
import org.json.JSONArray;
import org.json.JSONObject;

public class TransactionServer implements Runnable {
	//==================================================================================
	/*
	 * Basic functions, initialisation of basic functionalities and data-structures
	 */
	//==================================================================================	
	public static RequestHandler handler = new RequestHandler();
	
	public static RandomAccessFile excptionf =null;
	public static int port_number = 0;

	//####### OMNITALK VARIABLES
	public static DateFormat sDF = new SimpleDateFormat("dd MMM yyyy HH:mm:ss", Locale.ENGLISH);
	//public static SimpleDateFormat dDF = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	public static String ServerTS = ""; 
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
	
	public static String SupplyVoltage = "";
//	public static String LogDataPath = "D:\\itrack_vts/xml_data";
//	public static String RealDataPath = "D:\\itrack_vts/last_location";
	
	//public static String LogDataPath = "/home/current_data/xml_data";
	//public static String RealDataPath = "/home/current_data/last_location";
	public static String exception_message = "";
	
	//####### INITIALIZE VARIABLES
	public static SimpleDateFormat dDF = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	public static SimpleDateFormat dateOnly = new SimpleDateFormat("yyyy-MM-dd");
	public static String last_datetime = "";
	public static String vehicle_imei ="";
	
	//#### DATABASE CONNECTION VARIABLES
	public static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";  
	public static final String DB_URL = "jdbc:mysql://localhost/iespl_vts_beta";
	//public static final String DB_URL_remote = "jdbc:mysql://111.118.181.156/iespl_vts_beta";
	//public static final String DB_URL_remote = "jdbc:mysql://localhost/iespl_vts_beta";
	//###### SET SQS IDS
	public static final int vendor_sno = 4;	
	public static final int gps_vendor_id = 3;
	//################
//	public static final String DB_URL_remote = "jdbc:mysql://localhost/iespl_vts_beta";
	//public static final String DB_URL_remote = "jdbc:mysql://www.itracksolution.co.in/iespl_vts_beta";
	public static ArrayList<String> vehicleNoArr = new ArrayList<String>();
	public static HashMap<String, String>  LastTime = new HashMap(new Hashtable<String, String>());	
	//HashMap<String, ArrayList<String>> map = new HashMap<String,ArrayList<String>>();	
	
	//  Database credentials
	public static final String USER = "root";
	public static final String PASS = "mysql";
	public static Connection conn = null;
	public static Statement stmt = null;
	   
	/*public static final String USER_remote = "root";
	public static final String PASS_remote = "mysql";
	public static Connection conn_remote = null;
	public static Statement stmt_remote = null;*/	
	//############################	
	
	//##########################
	
	public TransactionServer(Config conf, WorkerClass worker) throws IOException {
		//RTS Configuration
		this.hostAddress=null;
		try{this.port=Integer.parseInt(conf.getValue("SERVERPORT"));}catch (Exception e) {}
		try{this.messageDelimiter=conf.getValue("MESSAGEDELIMITER").getBytes()[0];}catch(Exception e){}
		try{this.selectorTimeout=Integer.parseInt(conf.getValue("SELECTORTIMEOUT"));}catch (Exception e) {}
		try{this.connectionIdleTimeout=Integer.parseInt(conf.getValue("CONNECTIONIDLETIMEOUT"));}catch (Exception e) {}
		try{this.readBufferSize=Integer.parseInt(conf.getValue("READBUFFERSIZE"));}catch (Exception e) {}
		port_number = this.port;
//		int socketPairBufferSize=8192;
//		try{socketPairBufferSize=Integer.parseInt(conf.getValue("SOCKETPAIRBUFFERSIZE"));}catch (Exception e) {}
//		this.socketPairReadBuffer=ByteBuffer.allocate(socketPairBufferSize);
//		String removePairOnExitStr=conf.getValue("REMOVEPAIRONEXIT");
		
//		if(removePairOnExitStr!=null && removePairOnExitStr.equalsIgnoreCase("true"))
//			this.removePairOnExit=true;
//		else
//			this.removePairOnExit=false;
//		
//		String serverConfiguration="PORT="+port+", MESSAGEDELIMITER="+ASCII.getDescription(messageDelimiter)+", SELECTORTIMEOUT="+selectorTimeout+", CONNECTIONIDLETIMEOUT="+connectionIdleTimeout+", READBUFFERSIZE="+readBufferSize+" ,SOCKETPAIRBUFFERSIZE="+socketPairBufferSize+", REMOVEPAIRONEXIT="+removePairOnExitStr;
		//Application.writeLog("Server has been initialized with following configuration:\n"+serverConfiguration);

		//First create a selector
		this.selector=this.initSelector();

		//Create a non-blocking listening channel channel and bind it to specific host:port
		//Also register a selector to listen to any incoming connections
		//createListeningSocket();
		
		//System.out.println("Timeout:"+this.selectorTimeout);

		//Register the worker
		this.worker=worker;
		
		//######## GET CONNECTION AND VENDOR DETAIL
		get_connection();

		/*try{
			get_vendor_vehicles();
		}catch(Exception gv){System.out.println("Error:156");}	*/
		//##### CONNECTION CLOSED
	}

	/**
	 * @param args
	 */
	/*public static void main(String[] args) {
		initServer(null, 5005, new RequestHandler());
	}*/
	
	public static TransactionServer initServer(String pathToConfigurationFile) {
		TransactionServer server=null;
		try {
			//Print the product information and copyright notice
			//String copyrightMessage="\n\n-----------------------------------------------------------------------\n";
			//copyrightMessage+="Welcome to "+Application.PRODUCTNAME+" v"+Application.PRODUCTVERSION+"\n";
			//copyrightMessage+="Copyright (C)"+Application.RELEASEYEAR+" "+Application.COMPANYNAME+", All rights reserved";
			//copyrightMessage+="\n-----------------------------------------------------------------------\n\n";
			//Application.writeLog(copyrightMessage);			
			
			/*try
			{
				//Date datex = new Date();
				Date datex = new Date();
				SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
				String exception_date = formatter.format(datex);
				//String filename= "D:\\EXCEPTION_LOG/9001_"+exception_date+".txt";
				String filename= "/home/VTS/GPRSLoggerLocal/exception_log/"+port_number+"_"+exception_date+".txt";
				System.out.println("after write="+filename);
				excptionf = new RandomAccessFile(filename, "rwd");
			    //System.out.println("after write="+filename);
			}
			catch(IOException ioe)
			{
			    System.err.println("IOException: " + ioe.getMessage());
			}*/				

			
			Config serverConfig=getServerConfiguration(pathToConfigurationFile);

//			System.out.println("a");
//			ExecutorService executor = Executors.newFixedThreadPool(5);
			//Start the worker thread
			WorkerClass worker=new WorkerClass(getHandler());			
			//System.out.println("Transaction Started");
			Thread workerThread=new Thread(worker);
			workerThread.start();

			//Start the server
			server=new TransactionServer(serverConfig, worker);

			try
			{
				//Date datex = new Date();
				Date datex = new Date();
				SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
				String exception_date = formatter.format(datex);
				//String filename= "D:\\EXCEPTION_LOG/9001_"+exception_date+".txt";
				String filename= "/home/VTS/GPRSLoggerLocal/webservice_exception_log/"+port_number+"_"+exception_date+".txt";
				//System.out.println("after write="+filename);
				excptionf = new RandomAccessFile(filename, "rwd");
			    //System.out.println("after write="+filename);
			}
			catch(IOException ioe)
			{
			    System.err.println("IOException: " + ioe.getMessage());
			}
			
			//Start the server thread
			Thread serverThread=new Thread(server);
			serverThread.start();
			return server;
		} catch (Exception e) {
//			Application.writeLog("TransactionServer[initServer]: "+e.getMessage(), SystemLogger.SEVERE);
		}
		return server;
	}

	private static Config getServerConfiguration(String pathToConfigurationFile) {
		Config conf=new Config(pathToConfigurationFile);
		return conf;
	}

	private static RequestHandler getHandler() {
		RequestHandler handler=null;
		try {
			String handlerName=Application.HANDLERNAME;
			//Application.writeLog("Initializing Request Handler: "+handlerName);
			//Class handlerClass=Class.forName(HANDLERNAME);
			Class <? extends RequestHandler>  handlerClass=Class.forName(handlerName).asSubclass(RequestHandler.class);
			handler=(RequestHandler)handlerClass.newInstance();
		}
		catch (Exception e) {
			System.out.println("TransactionServer[getHandler]: "+e.getMessage());
			//Application.writeLog("TransactionServer[getHandler]: RTS handler could not be registered", SystemLogger.SEVERE);
		}
		return handler;
	}

	private Selector initSelector() throws IOException {
		Selector socketSelector=SelectorProvider.provider().openSelector();
		return socketSelector;
	}

	private void createListeningSocket() throws IOException {
		//Create a new non-blocking server channel channel
		this.serverChannel=ServerSocketChannel.open();
				
		//ServerDataEvent.serverChannel_resp = this.serverChannel;		//## OBJECT UPDATED
		serverChannel.configureBlocking(false);

		//Bind the channel to host:port
		InetSocketAddress address=new InetSocketAddress(hostAddress, port);
		serverChannel.socket().setReuseAddress(true);
		serverChannel.socket().bind(address);

		//Register the server channel channel
		//Indicating an interest in arriving connections
		serverChannel.register(selector, SelectionKey.OP_ACCEPT);		
		//Application.writeLog("TransactionServer: Listening Socket Channel has been created on port: "+this.port);
	}

	/*
	 * Shutdown the running instance of the server
	 */
	public void shutDown() {
		running=false;
		this.selector.wakeup();
	}
	
	/*
	 * Clean up the running instance before shutting down
	 */
	private void cleanUpInstance() {
		//Application.writeLog("TransactionServer[cleanUp]: shutdown has been initiated", SystemLogger.WARN);
		
		//Stop the worker
		//this.worker.stop();
		
		//Cleanly close all active connections, including the listening channel
		Set<SelectionKey> keys=this.selector.keys();
		Iterator<SelectionKey> iterator=keys.iterator();

		while(iterator.hasNext()) {
			SelectionKey key=iterator.next();
			key.cancel();
			try {key.channel().close();}catch (Exception e) {}
		}
		
		//Close the selector
		try{this.selector.close();}catch (Exception e) {}
		
		//Reset all data structures
//		socketPairReadBuffer=null;
//		channelPair=null;
	}

	//==================================================================================
	/*
	 * The server thread and associated helper functions
	 * Only the functions being called from run must manipulate the selector and selection keys
	 */
	//==================================================================================

	@Override
	public void run() {
		//idleConnectionsRemovedOn=System.currentTimeMillis();
		running=true;
		Long CleanInitialTime = System.currentTimeMillis();
		while(running) {
			try {
				//System.out.println("e");
				//Process the events
				dispatch();
				//Remove all idle connections and those which have been marked for closure
				if((System.currentTimeMillis()-CleanInitialTime)>60000)
				{
					//System.out.println("Clean Connection");
					removeIdleConnections();
					removeIdleFileHandler();
					CleanInitialTime = System.currentTimeMillis();
				}
				System.out.println("C");
				Thread.sleep(600000);  //6 mins
			}
			catch (Exception e) {
//				Application.writeLog("TransactionServer[run] Exception: "+e.getMessage(), SystemLogger.SEVERE);
				e.printStackTrace();
			}
		}
		//System.out.println("f");
		cleanUpInstance();
//		Application.writeLog("TransactionServer[run]: SHUTDOWN COMPLETE", SystemLogger.WARN);
	}

	/*
	 * This is the worker block, it handles all the events and requests
	 */

	public static void get_connection()	{		//##### CONSTRUCTOR
		 
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
	
	public static void get_vendor_vehicles()
	{		
		/*try{
		      //STEP 2: Register JDBC driver
		  try {
			Class.forName("com.mysql.jdbc.Driver");
		  } catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		  }
		  //STEP 3: Open a connection	  	  
		  conn = DriverManager.getConnection(DB_URL,USER,PASS);
	  	  //System.out.println("Connection to 156 -Remote database-ok:"+conn_remote+" ,DB_URL_remote="+DB_URL_remote+",USER="+USER+" PASS="+PASS);
		}catch(SQLException se){}*/
		
		try{
			//System.out.println("In GetVendor vehicles");
			stmt = null;
			stmt = conn.createStatement();
			String sql;
			/*sql_remote = "select vehicle_gpsvendor_assignment.device_imei_no from vehicle,vehicle_gpsvendor_assignment,gps_vendor,vehicle_assignment where "+
			"vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 "+
			"AND vehicle_assignment.device_imei_no= vehicle_gpsvendor_assignment.device_imei_no AND "+
			"vehicle_gpsvendor_assignment.gps_vendor_name like '%SQS Technology%' AND vehicle_gpsvendor_assignment.status=1 "+
			"AND gps_vendor.gps_vendor_id= vehicle_gpsvendor_assignment.gps_vendor_id AND gps_vendor.status=1";
			//System.out.println("SQL="+sql_remote);*/
			
			sql = "select vehicle.vehicle_name from vehicle,vehicle_gpsvendor_assignment,gps_vendor,vehicle_assignment where "+
			"vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 "+
			"AND vehicle_assignment.device_imei_no= vehicle_gpsvendor_assignment.device_imei_no AND "+
			"vehicle_gpsvendor_assignment.gps_vendor_id="+gps_vendor_id+" AND vehicle_gpsvendor_assignment.status=1 "+
			"AND gps_vendor.gps_vendor_id= vehicle_gpsvendor_assignment.gps_vendor_id AND gps_vendor.status=1 AND vehicle.status=1";
			//13=Newwaves select DISTINCT gps_vendor_name,gps_vendor_id from vehicle_gpsvendor_assignment where status=1;
			//System.out.println("sql_remote="+sql_remote);
			ResultSet rs = stmt.executeQuery(sql);
		    while(rs.next()){
		         try{						
					//System.out.println("IN 156");
		        	//vehicle_imei = vehicle_imei+""+rs_remote.getString("device_imei_no")+",";
		        	vehicleNoArr.add(rs.getString("vehicle_name"));
		         }catch(SQLException sq) {System.out.println("Error in Con:156");}
		    }
		    //vehicle_imei = vehicle_imei.substring(0,vehicle_imei.length()-1);	
			//System.out.println("sql_remote="+sql_remote);			
			rs.close();
			stmt.close();
			//conn.close();
		}catch(SQLException se2){System.out.println("conn error");}		
	}
	
	public static void get_last_time()
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
		sql = "SELECT device_last_time.device, device_last_time.last_time FROM device_last_time,webservice_last_time WHERE "+
		"webservice_last_time.sno = device_last_time.vendor_sno AND webservice_last_time.sno="+vendor_sno+"";		//sno=6=sqs
		//System.out.println("SQL1="+sql);
		ResultSet rs = null;
		try {
			rs = stmt.executeQuery(sql);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		String imeidb = "", time="";
		//Timestamp date_db;
		//STEP 5: Extract data from result set
		try {
			while(rs.next()){
				//Retrieve by column name
				imeidb = rs.getString("device");
				time = rs.getString("last_time");
				//date_db = rs.getTimestamp("last_time");
				//alert_str = sdf.format(date_db);
				LastTime.put(imeidb, time);
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
	}

	public static void update_last_time()
	{
		TransactionServer.stmt = null;
		try{
			//STEP 2: Register JDBC driver
					
			//STEP 4: Execute a query
			//System.out.println("Updating data...");
			try {
				TransactionServer.stmt = TransactionServer.conn.createStatement();
			} catch (Exception e) {
			// TODO Auto-generated catch block
				e.printStackTrace();
			}
			String sql;
			Date convertedDate = null;
			DateFormat formatter = null;
			formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

			boolean update=false;
			if (!(vehicleNoArr.isEmpty())) {
				try{
					for(String vehicleNoString : vehicleNoArr) {
						update=false;
						sql = "SELECT device_last_time.device, device_last_time.last_time FROM device_last_time,webservice_last_time WHERE "+
						"webservice_last_time.sno = device_last_time.vendor_sno AND webservice_last_time.sno="+vendor_sno+" AND "+
						"device_last_time.device='"+vehicleNoString+"'";		//sno=6=sqs
						//System.out.println("SQL2="+sql);
						ResultSet rs = null;
						try {
							rs = stmt.executeQuery(sql);
						} catch (SQLException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
						//STEP 5: Extract data from result set
						try {
							if(rs.next()){
								update=true;
							}
						}catch(SQLException e0){}
					  
						/*try{
							//convertedDate = (Date) dDF.parse(LastTime.get(vehicleNoString));
							convertedDate = (Date) formatter.parse(LastTime.get(vehicleNoString));

						}catch(Exception c){System.out.println("C="+convertedDate);}*/

						if(update) {
							sql = "UPDATE device_last_time SET last_time='"+LastTime.get(vehicleNoString)+"' WHERE device='"+vehicleNoString+"' AND vendor_sno="+vendor_sno+"";
							//System.out.println("Update1:"+sql);
							TransactionServer.stmt.executeUpdate(sql);							
						} else {
							sql = "INSERT INTO device_last_time(vendor_sno,device,last_time) values("+vendor_sno+",'"+vehicleNoString+"','"+LastTime.get(vehicleNoString)+"')";
							//System.out.println("Update2:"+sql);
							TransactionServer.stmt.executeUpdate(sql);							
						}
					}
				} catch(SQLException e1){}
			} 
		} catch(Exception e2){}
	}
	
	private static String getYesterdayDateString() {
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Calendar cal = Calendar.getInstance();
        cal.add(Calendar.DATE, -1);    
        return dateFormat.format(cal.getTime());
	}
	
	public static String get_converted_time(String dateStr, int hour,int min) {
		
	    SimpleDateFormat simpleDateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	    Date myDateTime = null;

	    //Parse your string to SimpleDateFormat
	    try {
	        myDateTime = simpleDateFormat.parse(dateStr);
	    }
	    catch (Exception e) {
	         e.printStackTrace();
	    }
	    //System.out.println("This is the Actual Date:"+dateStr);
	    //Calendar cal = new GregorianCalendar();
	    Calendar cal = Calendar.getInstance();
	    cal.setTime(myDateTime);

	    //Adding 5:30 Hours to your Date	    
		cal.add(Calendar.HOUR_OF_DAY, hour); // adds one hour
		cal.add(Calendar.MINUTE, min); // adds one Minute
		
		String last_datetime = dDF.format(cal.getTime());
		
	    //System.out.println("This is Hours Added Date:"+last_datetime);	
	    return last_datetime;
	}
	
	private void dispatch() throws IOException {

		ResultSet rs = null;
		stmt = null;
		String line ="";
		String vehicleNoString="";
		vehicleNoArr=new ArrayList<String>();
		System.out.println("IN PullWebServiceData");
		/*Connection conn = null;
		try {
			conn = DriverManager.getConnection(DB_URL,USER,PASS);
		} catch (SQLException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}*/
		TrackSoapProxy webServiceClient = new TrackSoapProxy();	
		try {
			try {			
				/*String sql_remote = "select vehicle.vehicle_name from vehicle,vehicle_gpsvendor_assignment,gps_vendor,vehicle_assignment,trip_detail "+
				"WHERE vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND "+
				"vehicle_assignment.device_imei_no = vehicle_gpsvendor_assignment.device_imei_no AND "+
				"vehicle_gpsvendor_assignment.gps_vendor_id = 3 AND vehicle_gpsvendor_assignment.status=1 AND "+
				"vehicle.vehicle_name=trip_detail.vehicle_no AND trip_detail.trip_status=1 AND trip_detail.status=1 AND "+
				"gps_vendor.gps_vendor_id= vehicle_gpsvendor_assignment.gps_vendor_id AND gps_vendor.status=1 AND vehicle.status=1";*/
				String sql = "select vehicle.vehicle_name from vehicle,trip_detail,vehicle_assignment,vehicle_gpsvendor_assignment WHERE "+
				"vehicle.status=1 AND trip_detail.trip_status=1 AND trip_detail.status=1 AND trip_detail.vehicle_no=vehicle.vehicle_name AND "+
				"vehicle.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_assignment.status=1 AND "+
				"vehicle_gpsvendor_assignment.device_imei_no=vehicle_assignment.device_imei_no AND vehicle_gpsvendor_assignment.status=1 AND "+
				"vehicle_gpsvendor_assignment.gps_vendor_id = "+gps_vendor_id+"";				
				
				//select DISTINCT gps_vendor_name,gps_vendor_id from vehicle_gpsvendor_assignment where status=1;
				System.out.print("sql: " + sql);
				stmt = conn.createStatement();
				rs = stmt.executeQuery(sql);
				int i=0;
				if(rs.next())
				{
					System.out.println("Vehicle="+rs.getString("vehicle_name"));
					//vehicleNoArr.add(rs_remote.getString("vehicle_name"));
					vehicleNoString = vehicleNoString+rs.getString("vehicle_name");
				}
				while(rs.next())
				{
					System.out.println("Vehicle="+rs.getString("vehicle_name"));
					//vehicleNoArr.add(rs_remote.getString("vehicle_name"));
					vehicleNoString = vehicleNoString+","+rs.getString("vehicle_name");
			    }
				System.out.println("vehicleNoString1="+vehicleNoString);
				/*vehcileNoArr.add("MH04GF8892");
				vehcileNoArr.add("MH12HD4236");
				vehcileNoArr.add("MH12HD5996");
				vehcileNoArr.add("MH12KP3561");*/
				//System.out.print(", vehcileNoArr: " + vehicleNoArr);
				rs.close();
			} 
			catch (SQLException e) 
			{
				e.printStackTrace();
			}
			
			//if (!(vehicleNoArr.isEmpty()))
			System.out.println("vehicleNoString.length()="+vehicleNoString.length());
			if (vehicleNoString.length()>0)
			{
				System.out.println("vehicleNoString2="+vehicleNoString);
				//for(String vehicleNoString : vehicleNoArr)
				{
					GetVehicleLatestDataResponseGetVehicleLatestDataResult  GDVR = webServiceClient.getVehicleLatestData(vehicleNoString,"bnmuerwu90");
					//System.out.println("GDVR "+GDVR.toString());
					MessageElement[] massageElement = GDVR.get_any();
					System.out.println("Element : "+massageElement.toString());
					String response = "";
					for(MessageElement me : massageElement)
					{
						response=me.toString();
						System.out.println("Response : "+me.toString());
					}
					System.out.println("WebServiceResponse "+response);
					DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
					DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
					InputSource is = new InputSource();
					is.setCharacterStream(new StringReader(response));
					Document doc = dBuilder.parse(is);
					System.out.println("D1");
					doc.getDocumentElement().normalize();
					System.out.println("D2");
					NodeList checkValue = doc.getElementsByTagName("Table");
					System.out.println("D3");
					Node checkNode=checkValue.item(0);
					Element cElement = (Element) checkNode;
					System.out.println("D4");
					String Vehicle=cElement.getElementsByTagName("Vehicle_No").item(0).getTextContent();
					System.out.println("Vehicle="+Vehicle);
					
					if(!Vehicle.equals("NA"))
					{
						NodeList nList = doc.getElementsByTagName("Table");
						System.out.println("Total : " + nList.getLength());					
						for (int temp = 0; temp < nList.getLength(); temp++) 
						{
							Node nNode = nList.item(temp);
							//System.out.println("\nCurrent Element :" + nNode.getNodeName());
							if (nNode.getNodeType() == Node.ELEMENT_NODE)
							{
								Element eElement = (Element) nNode;
								//DeviceIMEINo=eElement.getElementsByTagName("IMEINO").item(0).getTextContent();
								DeviceIMEINo=Vehicle;
								//DeviceIMEINo=eElement.getElementsByTagName("VEHICLE_RTO_NO").item(0).getTextContent();						
								ReceiveDateFormat = eElement.getElementsByTagName("Date").item(0).getTextContent();
								//System.out.println("ReceiveDateFormat: "+ReceiveDateFormat);
								ReceiveDateFormat = ReceiveDateFormat.replaceAll("T", " ");
								//System.out.println("ReceiveDateFormat: "+ReceiveDateFormat);
								String[] dateS = ReceiveDateFormat.split("\\+");
								ReceiveDateFormat = dateS[0];
								String DateTimeLast = "";
								DateTimeLast = ReceiveDateFormat;
								System.out.println("ReceiveDateFormat: "+dateS[0]);
								//Date result;
								//result = sDF.parse(ReceiveDateFormat);
								
								//DateTimeLast = dDF.format(result);
								System.out.println("DateTimeLast: "+DateTimeLast);
								Latitude=eElement.getElementsByTagName("Latitude").item(0).getTextContent();
								Longitude=eElement.getElementsByTagName("Longitude").item(0).getTextContent();
								Speed=eElement.getElementsByTagName("Speed").item(0).getTextContent();
								Speed="0.0";
								
								line = DeviceIMEINo+","+DateTimeLast+","+Latitude+","+Longitude+","+Speed+";";
								//System.out.println("line="+line);
								ByteBuffer buffer=null;
								byte[] messageBytes=line.getBytes();
								if(line.length()!=0) {
									//this.worker.processData(messageBytes, line.length());
									handler.UpdateDatabase(line);
								}			
								
							}
						}
					}
				}
			}
		} 
		catch (Exception e) 
		{
			e.printStackTrace();
		}
		
		/*try {
			conn.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}*/
	}
	
	public static Charset charset = Charset.forName("UTF-8");
	
	public static ByteBuffer str_to_bb(String msg){
	    return ByteBuffer.wrap(msg.getBytes(charset));
	}	
	//----------------------------------------------------------------------------------
	/*
	 * Housekeeping, keeps everything neat and tidy
	 */
	//----------------------------------------------------------------------------------
	/*
	 * Called from the main server thread, cleans up the mess, from time to time
	 */
	private void removeIdleConnections() throws IOException{
		long currentTime=System.currentTimeMillis();
		//Run only if it has been a while since we cleaned up idle connections
		//System.out.println("currentTime:"+currentTime+" idleConnectionsRemovedOn:"+idleConnectionsRemovedOn+" connectionIdleTimeout:"+connectionIdleTimeout);
		//System.out.println("Diff:"+(currentTime-idleConnectionsRemovedOn));
		if((currentTime-idleConnectionsRemovedOn)>connectionIdleTimeout) 
		{
			idleConnectionsRemovedOn=currentTime;
			Set<SelectionKey> keys=this.selector.keys();
			Iterator<SelectionKey> iterator=keys.iterator();

			while(iterator.hasNext()) {
				//System.out.println("A7");
				SelectionKey key=iterator.next();
				//System.out.println("A6");
				if(!key.isValid()) {
					//System.out.println("A1");
					//closeChannel(key);
					continue;
				}
				ConnectionType connectionType=(ConnectionType)key.attachment();
				long timeOut=0;

				//If this connection's type is not set, and this one is not listening,
				//treat it as an illegal connection
				if(connectionType==null && key.interestOps()!=SelectionKey.OP_ACCEPT) {
					//System.out.println("Z5:"+key.interestOps()+" "+SelectionKey.OP_ACCEPT);
					timeOut=connectionIdleTimeout+1;
					//System.out.println("A2");
				}

				//If this connection did not authenticate itself within timeout limit
				//then close it
				else if(connectionType!=null && connectionType.getType()==ConnectionType.UNVERIFIED) {
					//System.out.println("Z6:"+connectionType.getType()+" "+ConnectionType.UNVERIFIED);
					timeOut=System.currentTimeMillis()-connectionType.getCreatedOn();
					//System.out.println("Z7:"+timeOut);
					//System.out.println("A3");
				}

				//This connection has been tagged as illegal, close it immediately
				else if(connectionType!=null && connectionType.getType()==ConnectionType.ILLEGAL) {
					//System.out.println("Z4:"+ConnectionType.ILLEGAL+" "+connectionType.getType());
					timeOut=connectionIdleTimeout+1;
					//System.out.println("A4");
				}

				if(timeOut>0 && timeOut>connectionIdleTimeout) {
					//System.out.println("Z1");
					closeChannel(key);
					//System.out.println("A5");
				}
			}
		}
	}
	
	private void removeIdleFileHandler () throws IOException
	{
		int ListSize=0;
		synchronized(this.worker.handler.RFileHandler)
		{
			ListSize = this.worker.handler.RFileHandler.size();
			System.out.println("B:"+this.port+" "+ListSize);
			for(int ListCnt=0;ListCnt<ListSize;ListCnt++)
			{
				try
				{
					//if((System.currentTimeMillis()-this.worker.handler.RFileHandler.get(ListCnt).CreateTime)>300000)
					if((System.currentTimeMillis()-this.worker.handler.RFileHandler.get(ListCnt).UpdateTime)>300000)
					{
						 if(this.worker.handler.RFileHandler.get(ListCnt).StrBuf.length()>100)
						 {
							 try
							 {
								 worker.handler.RFileHandler.get(ListCnt).StrBuf.append("\n</t1>");
								 this.worker.handler.RFileHandler.get(ListCnt).RFile.writeBytes(worker.handler.RFileHandler.get(ListCnt).StrBuf.toString());
							 }
							 catch (Exception e)
							 {
								 e.printStackTrace();
							 }
						 }
						 try
						 {
						 	this.worker.handler.RFileHandler.get(ListCnt).RFile.close();
						 }
						 catch (Exception e)
						 {
							 e.printStackTrace();
						 }
						 try
						 {
							 this.worker.handler.RFileHandler.get(ListCnt).StrBuf.setLength(0);
							 this.worker.handler.RFileHandler.remove(ListCnt);
							 ListCnt--;
							 ListSize--;
						 }
						 catch (Exception e)
						 {
							 e.printStackTrace();
						 }
					}				 
					 //System.out.println("Clean Handler"+ListCnt);
				}
				catch (Exception e1)
				{
					e1.printStackTrace();
				}
		 	}
			//ListSize = this.worker.handler.RFileHandler.size();
			//System.out.println("After::ListSize1 Log"+ListSize);
		 }
		
		//System.out.println("ListSize Instant"+ListSize);
		
		synchronized(this.worker.handler.RFileHandler2)
		{
			ListSize = this.worker.handler.RFileHandler2.size();
			System.out.println("B1"+this.port+" "+ListSize);
			for(int ListCnt=0;ListCnt<ListSize;ListCnt++)
			{
				try
				{
					if((System.currentTimeMillis()-this.worker.handler.RFileHandler2.get(ListCnt).UpdateTime)>300000)
					 {
						try
						{
							// this.worker.handler.RFileHandler2.get(ListCnt).RFile.close();
							this.worker.handler.RFileHandler2.get(ListCnt).StrBuf.setLength(0);
							this.worker.handler.RFileHandler2.remove(ListCnt);
							ListCnt--;
							ListSize--;
						}
						 catch (Exception e)
						 {
							 e.printStackTrace();
						 }
					 }
				}
				catch (Exception e)
				{
					 e.printStackTrace();
				}
			}
			//ListSize = this.worker.handler.RFileHandler2.size();
			//System.out.println("ListSize Instant1"+ListSize);
		 }
		
		//
	}

	//Clean-up the channel associated with the key, and all the resources
	private void closeChannel(SelectionKey key) {
		if(key==null) return;
		SocketChannel channel=(SocketChannel)key.channel();
		key.cancel();
		try {channel.close();activeConnections--;}catch (Exception e) {}

	}

	//==================================================================================
	/*
	 * All server IO functions, these cannot be called directly from outside
	 */
	//==================================================================================
	/*
	 * Accepts newly arrived connections
	 */	
	
	private void accept(SelectionKey key) throws IOException {
		//get the channel associated with the key
		ServerSocketChannel serverSocketChannel=(ServerSocketChannel)key.channel();

		//Accept the connection and make it non-blocking
		SocketChannel socketChannel=serverSocketChannel.accept();
		socketChannel.configureBlocking(false);

		//Register the new channel channel with our selector
		//indicating that we are interested in incoming data
		socketChannel.register(selector, SelectionKey.OP_READ);

		//put a time-stamp on the new connection
		ConnectionType connectionType=new ConnectionType(System.currentTimeMillis(), ConnectionType.UNVERIFIED, null, ByteBuffer.allocate(readBufferSize));
		//connectionType.setBuffer(ByteBuffer.allocate(this.channelReadBufferLimit));
		socketChannel.keyFor(selector).attach(connectionType);
		activeConnections++;
	}

	/*
	 * reading from the channel depends on the protocol of the messages between client and the server
	 * In this case each message must be acknowledged, so we expect just one message at a time
	 */
	private void readMessage(SelectionKey key) {
		SocketChannel socketChannel=(SocketChannel)key.channel();
		//Get the dedicated ByteBuffer of this channel
		ConnectionType connectionType=(ConnectionType)key.attachment();
		connectionType.setCreatedOn(System.currentTimeMillis());
		ByteBuffer buffer=null;
		if(connectionType!=null)
			buffer=connectionType.getBuffer();

		int numRead=0;
		boolean messageFound=false;
		//read the data while it is available
		try {
			if(buffer==null) {
				throw new IOException("TransactionServer[readMessage]: invalid channel");
			}
			while((numRead=socketChannel.read(buffer))>0) {
				//Prepare buffer for reading
				buffer.flip();
				if(buffer.hasRemaining()) {
					//Read the data in byte array
					for(int i=buffer.position();i<buffer.limit();i++) {
						byte currentByte= buffer.get(i);
						if(currentByte==messageDelimiter) {
							//end of message marker found, process the message
							
							//System.out.println("a");							
							//## set response here
						    //#### SEND OK RESPONSE TO DEVICE -ONLY IN 9001 PORT
							/*Charset charset = Charset.forName("ISO-8859-1");
						    CharsetEncoder encoder = charset.newEncoder();
						    socketChannel.write(encoder.encode(CharBuffer.wrap("ok")));*/			
							//#########
							messageFound=true;

							int messageLength=i-buffer.position();
							byte[] messageBytes=new byte[messageLength];
							//Read the message into bytes array
							buffer.get(messageBytes);

							//Read the end-of-message marker
							buffer.get();

							//we are ready to process the message
							//Send the message for processing and return
							ConnectionType type=new ConnectionType(connectionType.getCreatedOn(), connectionType.getType(), connectionType.getId(), null);
							type.setUid(connectionType.getUid());

//							this.worker.processData(messageBytes, messageLength, type);
						}
					}
				}
				//prepare buffer for the next read from the channel
				buffer.compact();
				//but if we have already got a message on this channel, give other channels a chance
				if(messageFound) break;

				if (!buffer.hasRemaining()) {
					//#SOLUTION 1:
					//there is no more space left, increase the buffer
					//or go bust!
					//throw new IOException("TransactionServer[readMessage]: Read buffer overflow");
					
					//#SOLUTION 2:
					//Just Clear the buffer and break out to give other channels a chance
					// We are not going to allocate more buffer due to security reasons
					buffer.clear();
					return;
				}

			}
			//System.out.println("Z3:"+numRead);
			if(numRead==-1) {
				//Remote entity closed the connection cleanly
//				Application.writeLog("TransactionServer[readMessage]: Remote client at ["+socketChannel.socket().getInetAddress()+":"+socketChannel.socket().getPort()+"] closed the connection cleanly", SystemLogger.WARN);
				//System.out.println("Z2");
				closeChannel(key);
				return;
			}

		}
		catch (Exception e) {
			//Remote entity dropped the connection
			//or something else went wrong
//			Application.writeLog("TransactionServer[readMessage]: "+e.getMessage(), SystemLogger.SEVERE);
			//e.printStackTrace();
			closeChannel(key);
			return;
		}
	}


	//==================================================================================
	/*
	 * Basic Server Configurations
	 */
	//==================================================================================
	//Basic server settings, host:port
	private InetAddress hostAddress=null;
	//private int port=5005;
	private int port=5005;

	//Server Socket channel on which we will be accepting new connections
	private ServerSocketChannel serverChannel;
	//public ServerSocketChannel serverChannel_resp;
	//Selector we will be monitoring
	private Selector selector;

	//Instance of WorkerClass
	WorkerClass worker;

	//==================================================================================
	/*
	 * Basic data-structures for handling events/requests on channels
	 * //Locking mechanism for deadlock avoidance
	 * this.changeRequests -> this.registeredSockets ->this.pendingJobs
	 */
	//==================================================================================
	//A list of pending change requests
	//private List<ChangeRequest> changeRequests=new ArrayList<ChangeRequest>();
	// Maps a SocketChannel to a list of ByteBuffer instances
	//This contains a list of data to be written on a particular channel
	//private Map<SocketChannel, List<ByteBuffer>> pendingSendData=new HashMap<SocketChannel, List<ByteBuffer>>();
	//Once the connection is verified, register it with unique ID
	//private Map<String, SocketChannel> registeredSockets=new HashMap<String, SocketChannel>();

	/*
	 * Server and Connection housekeeping
	 */
	//Set idle timeout for selector in milliseconds
	private long selectorTimeout=10000;
	//Set idle connectionIdleTimeout in milliseconds, for connections
	private long connectionIdleTimeout=10000;
	//Set buffer size limitation for each channel
	private int readBufferSize=1024;
	//Keep a time-stamp on when the idle connections were removed last time
	private long idleConnectionsRemovedOn=0;
	//Number of active connections at the moment
	private int activeConnections=0;
	//Protocol delimiter
	private byte messageDelimiter=';';
	
	//If true remove the other end of the pair, if one of the ends closes
	//private boolean removePairOnExit=false;
	//==================================================================================
	/*
	 * Socket Pair Data-Structures
	 */
	//==================================================================================
	//Buffer in which we will place the incoming data
	//private ByteBuffer socketPairReadBuffer;
	//Channel Pairs
	//private Map<SocketChannel, SocketChannel> channelPair=new HashMap<SocketChannel, SocketChannel>();
	private boolean running=false;
}
