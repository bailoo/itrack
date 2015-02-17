package in.co.itracksolution.model;

import java.util.Date;

public class FullData {
	private String imeih, data;
	private Date dTime;
	
	public static final String TABLE_NAME = "full_data";
	
	public FullData(){
		super();
	}
	
	public FullData(String imeih, Date dTime, String data) {
		super();
		this.imeih = imeih;
		this.dTime = dTime;
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
	
}
