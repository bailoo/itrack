package in.co.itracksolution.model;

import java.util.Date;

public class FullData {
	private String imeih, data;
	private Date sTime, dTime;
	
	public static final String TABLE_NAME = "log";
	
	public FullData(){
		super();
	}
	
	public FullData(String imeih, Date dTime, Date sTime, String data) {
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
