package in.co.itracksolution.model;

import java.util.Date;

public class GapLog {
	private String imei, date, startLatitude, startLongitude, startLocation, endLatitude, endLongitude, endLocation; 
	private Date startTime, endTime, logTime;
	
	public static final String TABLE_NAME = "gaplog";
	
	public SpeedAlert()
	{
		super();
	}
	
	public SpeedAlert(String imei, String date, Date startTime, String startLatitude, String startLongitude, String startLocation, String endLatitude, String endLongitude, String endLocation, Date endTime, Date logTime ) 
	{
		super();
		this.imei 		= imei;
		this.date 		= date;
		this.startTime 		= startTime;
		this.startLatitude 	= startLatitude ;
		this.startLongitude 	= startLongitude ;
		this.startLocation 	= startLocation ;
		this.endTime 		= endTime;
		this.endLatitude 	= endLatitude;
		this.endLongitude 	= endLongitude;
		this.endLocation 	= endLocation;
		this.logTime 		= logTime;
	}

	public SpeedAlert(SpeedAlert f)
	{
		this.imei 		= f.imei;
		this.date 		= f.date;
		this.startTime 		= f.startTime;
		this.startLatitude 	= f.startLatitude ;
		this.startLongitude 	= f.startLongitude ;
		this.startLocation 	= f.startLocation ;
		this.endLatitude 	= f.endLatitude;
		this.endLongitude 	= f.endLongitude;
		this.endLocation 	= f.endLocation;
		this.endTime 		= f.endTime;
		this.logTime 		= f.logTime;
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
	public Date getStartTime() {
		return startTime;
	}
	public void setStartTime(Date startTime) {
		this.startTime = startTime;
	}
	public float getStartLatitude() {
		return startLatitude;
	}
	public void setStartLatitude(String startLatitude) {
		this.startLatitude= startLatitude;
	} 
	public float getStartLongitude() {
		return startLongitude;
	}
	public void setStartLongitude(String startLongitude) {
		this.startLongitude= startLongitude;
	} 
	public float getStartLocation() {
		return startLocation;
	}
	public void setStartLocation(String startLocation) {
		this.startLocation = startLocation ;
	} 
	public Date getEndTime() {
		return endTime;
	}
	public void setEndTime(Date endTime) {
		this.endTime = endTime;
	}
	public float getEndLatitude() {
		return endLatitude;
	}
	public void setEndLatitude(String endLatitude) {
		this.endLatitude= endLatitude;
	} 
	public float getEndLongitude() {
		return endLongitude;
	}
	public void setEndLongitude(String endLongitude) {
		this.endLongitude= endLongitude;
	} 
	public float getEndLocation() {
		return endLocation;
	}
	public void setEndLocation(String endLocation) {
		this.endLocation = endLocation ;
	} 
	public float getLogTime() {
		return LogTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
