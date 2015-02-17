package in.co.itracksolution.model;

public class LastData {

	private String imei, data;
	
	public static final String TABLE_NAME = "last_data";
	
	public LastData(){
		super();
	}
	
	public LastData(String imei, String data) {
		super();
		this.imei = imei;
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
	
}
