package com.hdfc.db.mysql;

import java.sql.ResultSet;
import java.sql.SQLException;

import com.hdfc.init.init;

public class mysql_handler {
	

	//############ ALERT STATUS
	public static String getVehicleInformation(int account_id)
	{		
	   connection.stmt = null;
	   //int vehicle_id = 0;
	   //float max_speed = 0.0f;
	   //String device_imei_no ="", vehicle_name="";
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	//      System.out.println("Selecting data...");
		   connection.stmt = connection.conn.createStatement();
	      String sql;
	      sql = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name,vehicle.max_speed,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,"+
	    		  "vehicle_grouping WHERE vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND "+
	    		  "vehicle_grouping.account_id="+account_id+" AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";
	      //System.out.println("SQL="+sql);
	      ResultSet rs = connection.stmt.executeQuery(sql);

	      //STEP 5: Extract data from result set
	      init init_var = new init();
	      while(rs.next()){
	         //Retrieve by column name
	    	  init_var.vehicle_id.add(rs.getInt("vehicle_id"));
	    	  init_var.vehicle_name.add(rs.getString("vehicle_name"));
	    	  init_var.max_speed.add(rs.getFloat("max_speed"));
	    	  init_var.device_imei_no.add(rs.getString("device_imei_no"));
	         //Display values
	         //System.out.print("Alert_str=" + alert_str);
	      }
	      //STEP 6: Clean-up environment
	      rs.close();
	      connection.stmt.close();
	      }catch(SQLException se2){}
	    	  
		return null;
	}
	
	public static void update_database_alert_status(String imei, String alert_string, String alert_type)
	{		
		connection.stmt = null;
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	//      System.out.println("Updating data...");
	      try {
	    	  connection.stmt = connection.conn.createStatement();
	      } catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
	      }
	      String sql;
	      sql = "UPDATE alert_detail_java SET alert_string='"+alert_string+"' WHERE imei='"+imei+"' AND alert_type='"+alert_type+"'";
	      connection.stmt.executeUpdate(sql);
	   }catch(SQLException e){}
	}		   
	
	public static void insert_database_alert_status(String imei, String alert_string, String alert_type)
	{		
		connection.stmt = null;
	   try{
	      //STEP 2: Register JDBC driver

	      //STEP 4: Execute a query
	    //  System.out.println("Inserting data...");
	      try {
	    	  connection.stmt = connection.conn.createStatement();
	      } catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
	      }
	      String sql;
	      sql = "INSERT INTO alert_detail_java(imei,alert_string,alert_type) values('"+imei+"','"+alert_string+"','"+alert_type+"')";
	      connection.stmt.executeUpdate(sql);
	   }catch(SQLException e){}
	}	
	
}
