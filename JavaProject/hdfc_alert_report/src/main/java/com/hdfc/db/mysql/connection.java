package com.hdfc.db.mysql;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

public class connection {
	   static final String JDBC_DRIVER = "com.mysql.jdbc.Driver";  
	   //static final String DB_URL = "jdbc:mysql://localhost/insurance_vts";
	   static final String DB_URL = "jdbc:mysql://localhost/iespl_vts_beta";

	   //  Database credentials
	   static final String USER = "root";
	   static final String PASS = "neon04$VTS";
	   //static final String PASS = "mysql";
	   public static Connection conn = null;
	   public static Statement stmt = null;
	   
	   public connection()
	   {
			conn = null;
			try{
			      //STEP 2: Register JDBC driver
			  try {
				Class.forName("com.mysql.jdbc.Driver");
				System.out.println("AfterClassforName");
			  } catch (ClassNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			  }
			  //STEP 3: Open a connection
			  	System.out.println("Connecting to database");
			      conn = DriverManager.getConnection(DB_URL,USER,PASS);
			 }catch(SQLException se){}		   
	   }
}