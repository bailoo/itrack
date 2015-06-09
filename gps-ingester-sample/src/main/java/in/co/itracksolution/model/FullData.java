package in.co.itracksolution.model;

import java.util.Date;

public class FullData {
	private String imei, date, data;
	private Date sTime, dTime;
	
	public static final String TABLE_NAME1 = "log1";
	public static final String TABLE_NAME2 = "log2";
	
	public FullData(){
		super();
	}
	
	public FullData(String imei, String date, Date dTime, String data, Date sTime) {
		super();
		this.imei = imei;
		this.date = date;
		this.dTime = dTime;
		this.data = data;
		this.sTime = sTime;
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
	
}
