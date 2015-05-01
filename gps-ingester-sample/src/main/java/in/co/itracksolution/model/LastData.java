package in.co.itracksolution.model;

import java.util.Date;

public class LastData {

	private String imei, data, day;
	private Date sTime;
	
	public static final String TABLE_NAME = "lastlog";
	
	public LastData(){
		super();
	}
	
	public LastData(String imei, String day, Date sTime, String data) {
		super();
		this.imei = imei;
		this.day = day;
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
	public String getDay() {
		return day;
	}
	public void setDay(String day) {
		this.day = day;
	}
	public Date getSTime() {
		return sTime;
	}
	public void setSTime(Date serverTime) {
		this.sTime = serverTime;
	}
	
}
