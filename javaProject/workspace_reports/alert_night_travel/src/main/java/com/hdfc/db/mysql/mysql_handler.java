package com.hdfc.db.mysql;

import java.sql.ResultSet;
import java.sql.SQLException;

import com.hdfc.init.init;

public class mysql_handler {
	

	//############ ALERT STATUS
	public static String getVehicleInformation(connection conn, int account_id)
	{
	   /*conn.stmt = null;
	   //int vehicle_id = 0;
	   //float max_speed = 0.0f;
	   //String device_imei_no ="", vehicle_name="";
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	//    System.out.println("Selecting data...");
		  conn.stmt = conn.conn.createStatement();

	      String sql;
	      sql = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name,vehicle.max_speed,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,"+
	    		  "vehicle_grouping WHERE vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND "+
	    		  "vehicle_grouping.account_id="+account_id+" AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";
	      //System.out.println("SQL="+sql);
	      ResultSet rs = conn.stmt.executeQuery(sql);

	      //STEP 5: Extract data from result set
	      //init init_var = new init();
	      while(rs.next()){
	         //Retrieve by column name
	    	  //init.vehicle_id.add(rs.getInt("vehicle_id"));
	    	  init.vehicle_name.add(rs.getString("vehicle_name"));
	    	  init.max_speed.add(rs.getFloat("max_speed"));
	    	  init.device_imei_no.add(rs.getString("device_imei_no"));
	         //Display values
	         //System.out.print("device_imei_no=" + rs.getString("device_imei_no"));
	      }
	      //STEP 6: Clean-up environment
	      rs.close();
	      conn.stmt.close();
	      }catch(SQLException se2){}*/
	    	  
	 	  //init.vehicle_id.add(10);
	 	  init.vehicle_name.add("test_hdfc1");
	 	  init.max_speed.add(30.0f);
	 	  init.device_imei_no.add("862170018383602");
	 	  
	 	  //init.vehicle_id.add(20);
	 	  init.vehicle_name.add("test_hdfc2");
	 	  init.max_speed.add(30.0f);
	 	  init.device_imei_no.add("862170018368132");

	 	  /*init.vehicle_id.add(30);
	 	  init.vehicle_name.add("test_hdfc3");
	 	  init.max_speed.add(30.0f);
	 	  init.device_imei_no.add("865733021569959");*/	
	   
	 	  /*init.vehicle_name.add("DL1LR7112");
	 	  init.max_speed.add(80.0f);
	 	  init.device_imei_no.add("862170018382109");*/	   
 	 	  
	   return null;
	}
	
}
