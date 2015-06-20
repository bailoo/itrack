package com.hdfc.db.gis;
import java.sql.DriverManager;
import java.sql.Connection;
import java.sql.SQLException;
import java.sql.*;
 
public class TestConnection {
	public static void main(String[] argv){
		System.out.println("-----PostgreSQL JDBC Connection Testing");
		try{
			Class.forName("org.postgresql.Driver");
		}catch(ClassNotFoundException e){
			System.out.println("Where is your PostgreSQL JDBC Driver? Included in your library path!");
			e.printStackTrace();
			return;
		}
		System.out.println("PostgreSQL JDBC Driver Registered!");
		Connection connection=null;
		try{
			connection = DriverManager.getConnection("jdbc:postgresql://127.0.0.1:5432/gisgraphy","postgres","neon04$");
			//connection = DriverManager.getConnection("jdbc:postgresql://52.74.144.159:5432/gisgraphy","postgres","neon04$");
		}catch(SQLException e){
			System.out.println("Connection Failed!Check output console");
			e.printStackTrace();
			return;
		}
		if(connection !=null){
			System.out.println("You made it, take control your database now!");
			//Create a Statement class to execute the SQL statement
		    try {
				Statement stmt = connection.createStatement();
				 //Execute the SQL statement and get the results in a Resultset
			    ResultSet rs = stmt.executeQuery("select * from Road LIMIT 1");
			    // Iterate through the ResultSet, displaying two values
			    // for each row using the getString method
			 
			    while (rs.next())
			        System.out.println("Name= " + rs.getString("name") + " Code= " + rs.getString("featureId"));
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
		}
		else{
			System.out.print("Failed to make connection");
		}
	}
}
