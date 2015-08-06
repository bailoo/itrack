package com.iespl;
import java.sql.*;

public class utilMysqlConnectivity 
{
	private static utilMysqlConnectivity instance = new utilMysqlConnectivity();
	public static final String URL = "jdbc:mysql://localhost/iespl_vts_beta";
	public static final String USER = "root";
	public static final String PASSWORD = "mysql";
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
			connection = DriverManager.getConnection(URL, USER, PASSWORD);
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
