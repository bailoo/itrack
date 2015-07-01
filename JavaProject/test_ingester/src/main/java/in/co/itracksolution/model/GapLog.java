package in.co.itracksolution.model;

import java.util.Date;

public class GapLog {
	private String imei, date, type, startLatitude, startLongitude, startLocationId, startLocationName, endLatitude, endLongitude, endLocationId, endLocationName; 
	private Date startTime, endTime, logTime;
	
	public static final String TABLE_NAME = "gaplog";
	
	public GapLog()
	{
		super();
	}
	
	public GapLog(String imei, String date, String type, Date startTime, String startLatitude, String startLongitude, String startLocationId, String startLocationName, Date endTime, String endLatitude, String endLongitude, String endLocationId, String endLocationName, Date logTime ) 
	{
		super();
		this.imei 		= imei;
		this.date 		= date;
		this.type 		= type;
		this.startTime 		= startTime;
		this.startLatitude 	= startLatitude;
		this.startLongitude 	= startLongitude;
		this.startLocationId 	= startLocationId;
		this.startLocationName 	= startLocationName;
		this.endTime 		= endTime;
		this.endLatitude 	= endLatitude;
		this.endLongitude 	= endLongitude;
		this.endLocationId 	= endLocationId;
		this.endLocationName 	= endLocationName;
		this.logTime 		= logTime;
	}

	public GapLog(GapLog f)
	{
		this.imei 		= f.imei;
		this.date 		= f.date;
		this.type 		= f.type;
		this.startTime 		= f.startTime;
		this.startLatitude 	= f.startLatitude ;
		this.startLongitude 	= f.startLongitude ;
		this.startLocationId 	= f.startLocationId;
		this.startLocationName 	= f.startLocationName;
		this.endLatitude 	= f.endLatitude;
		this.endLongitude 	= f.endLongitude;
		this.endTime 		= f.endTime;
		this.endLocationId 	= f.endLocationId;
		this.endLocationName 	= f.endLocationName;
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
	public String getType() {
		return type;
	}
	public void setType(String type) {
		this.type = type;
	}
	public Date getStartTime() {
		return startTime;
	}
	public void setStartTime(Date startTime) {
		this.startTime = startTime;
	}
	public String getStartLatitude() {
		return startLatitude;
	}
	public void setStartLatitude(String startLatitude) {
		this.startLatitude= startLatitude;
	} 
	public String getStartLongitude() {
		return startLongitude;
	}
	public void setStartLongitude(String startLongitude) {
		this.startLongitude= startLongitude;
	} 
	public String getStartLocationId() {
		return startLocationId;
	}
	public void setStartLocationId(String startLocationId) {
		this.startLocationId = startLocationId;
	} 
	public String getStartLocationName() {
		return startLocationName;
	}
	public void setStartLocationName(String startLocationName) {
		this.startLocationName = startLocationName;
	} 
	public Date getEndTime() {
		return endTime;
	}
	public void setEndTime(Date endTime) {
		this.endTime = endTime;
	}
	public String getEndLatitude() {
		return endLatitude;
	}
	public void setEndLatitude(String endLatitude) {
		this.endLatitude= endLatitude;
	} 
	public String getEndLongitude() {
		return endLongitude;
	}
	public void setEndLongitude(String endLongitude) {
		this.endLongitude= endLongitude;
	} 
	public String getEndLocationId() {
		return endLocationId;
	}
	public void setEndLocationId(String endLocationId) {
		this.endLocationId = endLocationId;
	} 
	public String getEndLocationName() {
		return endLocationName;
	}
	public void setEndLocationName(String endLocationName) {
		this.endLocationName = endLocationName;
	} 
	public Date getLogTime() {
		return logTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
