package in.co.itracksolution.model;

import java.util.Date;

public class LastData {

	private String imei, data;
	private Date sTime;
	
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
	
}
