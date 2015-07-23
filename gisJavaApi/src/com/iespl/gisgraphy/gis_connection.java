package com.iespl.gisgraphy;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class gis_connection {
	 
	public Connection connection=null;
	
	public gis_connection() {
		try{
			Class.forName("org.postgresql.Driver");
		}catch(ClassNotFoundException e){
			//System.out.println("Where is your PostgreSQL JDBC Driver? Included in your library path!");
			e.printStackTrace();
			return;
		}
		//System.out.println("PostgreSQL JDBC Driver Registered!");
		connection=null;
		try{
			//connection = DriverManager.getConnection("jdbc:postgresql://127.0.0.1:5432/gisgraphy","postgres","neon04$");
			connection = DriverManager.getConnection("jdbc:postgresql://127.0.0.1:5433/gisgraphy","postgres","neon04$");
			//connection = DriverManager.getConnection("jdbc:postgresql://52.74.144.159:5432/gisgraphy","postgres","neon04$");
		}catch(SQLException e1){
			//System.out.println("Connection Failed!Check output console");
			e1.printStackTrace();
			return;
		}
	}
}
