/**********************************************************
 * Routines for handling properties files in JAVA
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

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.util.Properties;

public class Config {
	public Config(String fileName)
	{
		properties=new Properties();
		file=new File(fileName);
	}
	
	private void load()
	{
		try
		{
			properties.load(input= new FileInputStream(file));
			input.close();
			error=false;
		}
		catch (Exception e) {
			//System.out.println("load: "+e.getMessage());
			error=true;
		}
	}
	
	private void store()
	{
		try
		{
			properties.store(output= new FileOutputStream(file), null);
			output.close();
			error=false;
		}
		catch (Exception e) {
			//System.out.println("store: "+e.getMessage());
			error=true;
		}
	}
	
	public boolean setValue(String key,String value)
	{
		load();
		properties.setProperty(key, value);
		store();
		return error;
	}
	public String getValue(String key)
	{
		load();
		return properties.getProperty(key); 
	}
	
	private Properties properties;
	private File file;
	private InputStream input;
	private java.io.OutputStream output;
	private boolean error;
}
