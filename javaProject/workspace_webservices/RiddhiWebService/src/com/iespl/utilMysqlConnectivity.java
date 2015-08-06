package com.iespl;
import java.sql.*;

public class utilMysqlConnectivity 
{
	private static utilMysqlConnectivity instance = new utilMysqlConnectivity();
//	public static final String DB_URL_remote = "jdbc:mysql://localhost/iespl_vts_beta";
	public static final String DB_URL_remote = "jdbc:mysql://111.118.181.156/iespl_vts_beta";
	public static final String USER_remote = "root";
	public static final String PASS_remote = "mysql";
	public static final String DRIVER_CLASS = "com.mysql.jdbc.Driver"; 
	
	
	private utilMysqlConnectivity() 
	{
		try 
		{
			//Step 2: Load MySQL Java driver
			Class.forName(DRIVER_CLASS);
		} 
		catch (ClassNotFoundException e)
		{
			e.printStackTrace();
		}
	}
	private Connection createConnection() 
	{

		Connection connection = null;
		try 
		{
			//Step 3: Establish Java MySQL connection
			connection = DriverManager.getConnection(DB_URL_remote, USER_remote, PASS_remote);
		} 
		catch (SQLException e) 
		{
			System.out.println("ERROR: Unable to Connect to Database.");
		}
		return connection;
	}	
	
	public static Connection getConnection() 
	{
		return instance.createConnection();
	}

}
