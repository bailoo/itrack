/**********************************************************
 * Application setup, configuration and management(database, user-interface, properties)
 * Copyright (C) 2010  Amit Kumar(amitkriit@gmail.com)
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
package com.wanhive.rts.utils;

import in.co.itracksolution.SampleInsert;

import java.io.File;
import java.util.Map;

import com.wanhive.rts.TransactionServer;
import com.wanhive.rts.alert_module;

public class Application {
	public static void init(String path)
	{
		JarConfig conf=new JarConfig(path);
		
		//Product Information
		//PRODUCTNAME=conf.getValue("PRODUCTNAME");
		//System.out.println(PRODUCTNAME);
		//PRODUCTID=conf.getValue("PRODUCTID");
		//PRODUCTVERSION=conf.getValue("PRODUCTVERSION");
		//RELEASEYEAR=conf.getValue("RELEASEYEAR");
		//COMPANYNAME=conf.getValue("COMPANY");
		//COMPANYWEBSITE=conf.getValue("COMPANYWEBSITE");
		//PRODUCTWEBSITE=conf.getValue("PRODUCTWEBSITE");
		
		//Database and System settings
		ENVVARNAME=conf.getValue("ENVVAR");
		DBFILENAME=conf.getValue("DBFILENAME");
		DBPOOLNAME=conf.getValue("DBPOOL");
		CONFIGFILENAME=conf.getValue("CONFIGFILENAME");
		//LOGFILENAME=conf.getValue("LOGFILENAME");
		HANDLERNAME=conf.getValue("HANDLERNAME");
		
		//Initialize Database Connection
		//try{if(connectionPool!=null) connectionPool.release();connectionPool=null;}catch(Exception e){System.out.println("Application.init: "+e.getMessage());}
		
		//Initialize a server instance
		String configFilePath=getApplicationEnv(Application.ENVVARNAME);
		configFilePath+=File.separator+CONFIGFILENAME;
		//System.out.println("configFilePath:"+configFilePath);
		RTSINSTANCE=TransactionServer.initServer(configFilePath);
		//For testing the shutdown
		/*try{Thread.sleep(5000);RTSINSTANCE.shutDown();Thread.sleep(5000);}catch (Exception e) {}
		RTSINSTANCE=TransactionServer.initServer(configFilePath);*/
	//	System.out.println("INITIALIZATION COMPLETE1");
		//######## GET ESCALATION DETAIL
		//System.out.println("BEFORE GET ESCALATION DETAIL");		
		//alert_module.get_escalation_detail();
		
		System.out.println("INITIALIZATION-1 COMPLETE");
	}
	public static String getApplicationEnv(String key) {
		if(environment!=null)
		{
			return environment.get(key);
		}
		else
		{
			environment=System.getenv();
			return environment.get(key);
		}
	}
	
	private static Map<String, String> environment=null;

	//public static String PRODUCTNAME;
	//public static String PRODUCTID;
	//public static String PRODUCTVERSION;
	//public static String RELEASEYEAR;
	//public static String COMPANYNAME;
	//public static String COMPANYWEBSITE;
	//public static String PRODUCTWEBSITE;
	
	public static String ENVVARNAME;
	public static String DBFILENAME;
	public static String DBPOOLNAME;
	public static String CONFIGFILENAME;
	
	//public static String LOGFILENAME;
	public static String HANDLERNAME;
	
	//Handle to server object
	public static TransactionServer RTSINSTANCE=null;
}
