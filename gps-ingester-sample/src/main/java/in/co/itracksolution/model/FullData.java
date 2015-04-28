package in.co.itracksolution.model;

import java.util.Date;

public class FullData {
	private String imeih, dTime, data;
	private Date sTime;
	
	public static final String TABLE_NAME = "log";
	
	public FullData(){
		super();
	}
	
	public FullData(String imeih, String dTime, Date sTime, String data) {
		super();
		this.imeih = imeih;
		this.dTime = dTime;
		this.sTime = sTime;
		this.data = data;
	}

	public String getImeih() {
		return imeih;
	}
	public void setImeih(String imeih) {
		this.imeih = imeih;
	}
	public String getData() {
		return data;
	}
	public void setData(String data) {
		this.data = data;
	}
	public String getDTime() {
		return dTime;
	}
	public void setDTime(String deviceTime) {
		this.dTime = deviceTime;
	}
	public Date getSTime() {
		return sTime;
	}
	public void setSTime(Date serverTime) {
		this.sTime = serverTime;
	}
	
}
