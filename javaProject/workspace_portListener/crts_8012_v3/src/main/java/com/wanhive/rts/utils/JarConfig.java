package com.wanhive.rts.utils;

import java.io.InputStream;
import java.util.Properties;

public class JarConfig {
	public JarConfig(String path) {
		this.path=path;
		properties=new Properties();
	}
	
	private void load() {
		if(error) return;
		try {
			properties.load(in=this.getClass().getClassLoader().getResourceAsStream(path));
			in.close();
			error=false;
		}
		catch (Exception e) {
			System.out.println("JarConfig[load]: "+e.getMessage());
			error=true;
		}
	}
	
	public String getValue(String key) {
		load();
		return properties.getProperty(key);
	}
	private String path;
	private Properties properties;
	private InputStream in;
	private boolean error=false;
}
