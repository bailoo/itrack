package in.co.itracksolution.model;

import java.util.Date;
import java.util.TreeMap;

public class LastData {

	private String imei, data;
	private Date sTime;
	private TreeMap pMap = new TreeMap();
	public String[] lastParams = { "a","b","c","d","e","f","h","i","j","k","l","m","n","o","p","q","r","s","t","u","ci","ax","ay","az","mx","my","mz","bx","by","bz" };
	
	public static final String TABLE_NAME = "lastlog";
	
	public LastData(){
		super();
	}
	
	public LastData(String imei, Date sTime, String data) {
		super();
		this.imei = imei;
		this.sTime = sTime;
		this.data = data;
	}

	public String getImei() {
		return imei;
	}
	public void setImei(String imei) {
		this.imei = imei;
	}
	public String getData() {
		return data;
	}
	public void setData(String data) {
		this.data = data;
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
