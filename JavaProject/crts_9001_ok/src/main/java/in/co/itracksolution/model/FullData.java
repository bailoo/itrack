package in.co.itracksolution.model;

import java.util.Date;
import java.util.TreeMap;

public class FullData {
	private String imei, date, data;
	private Date sTime, dTime;
	private TreeMap pMap = new TreeMap();
	public String[] fullParams = { "a","b","c","d","e","f","i","j","k","l","m","n","o","p","q","r","ci","ax","ay","az","mx","my","mz","bx","by","bz" };
	
	public static final String TABLE_NAME1 = "log1";
	public static final String TABLE_NAME2 = "log2";
	
	public FullData()
	{
		super();
	}
	
	//public FullData(String imei, String date, Date dTime, String data, Date sTime, TreeMap pMap) 
	public FullData(String imei, String date, Date dTime, String data, Date sTime) 
	{
		super();
		this.imei = imei;
		this.date = date;
		this.dTime = dTime;
		this.data = data;
		this.sTime = sTime;
		//this.pMap = pMap;
	}

	public FullData(FullData f)
	{
		this.imei = f.imei;
		this.date = f.date;
		this.dTime = f.dTime;
		this.data = f.data;
		this.sTime = f.sTime;
		this.pMap = f.pMap;
	}

	public String getImei() {
		return imei;
	}
	public void setImei(String imei) {
		this.imei = imei;
	}
	public String getDate() {
		return date;
	}
	public void setDate(String date) {
		this.date = date;
	}
	public String getData() {
		return data;
	}
	public void setData(String data) {
		this.data = data;
	}

	public Date getDTime() {
		return dTime;
	}
	public void setDTime(Date deviceTime) {
		this.dTime = deviceTime;
	}
	public Date getSTime() {
		return sTime;
	}
	public void setSTime(Date serverTime) {
		this.sTime = serverTime;
	}
	public TreeMap getPMap() {
		return pMap;
	}
	public void setPMap(TreeMap pMap) {
		this.pMap = pMap;
	}
	
}
