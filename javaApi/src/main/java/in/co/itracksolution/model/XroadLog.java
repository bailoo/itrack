package in.co.itracksolution.model;

import java.util.Date;

public class XroadLog {
	private String imei, date, roadId, roadName, locationId, locationName, latitude, longitude; 
  	private float speed;
	private int haltDuration;
	private Date dTime, sTime, logTime;
	
	public static final String TABLE_NAME = "xroadlog";
	
	public XroadLog()
	{
		super();
	}
	
	public XroadLog(String imei, String date, Date dTime, Date sTime, String roadId, String roadName, int haltDuration, float speed, String locationId, String locationName, String latitude, String longitude, Date logTime ) 
	{
		super();
		this.imei 		= imei;
		this.date 		= date;
		this.dTime 		= dTime;
		this.sTime 		= sTime;
		this.roadId 		= roadId;
		this.roadName 		= roadName;
		this.haltDuration 	= haltDuration;
		this.speed 		= speed;
		this.locationId 	= locationId;
		this.locationName 	= locationName;
		this.latitude 		= latitude;
		this.longitude 		= longitude;
		this.logTime 		= logTime;
	}

	public XroadLog(XroadLog f)
	{
		this.imei 		= f.imei;
		this.date 		= f.date;
		this.dTime 		= f.dTime;
		this.sTime 		= f.sTime;
		this.roadId 		= f.roadId;
		this.roadName 		= f.roadName;
		this.haltDuration 	= f.haltDuration;
		this.speed 		= f.speed;
		this.locationId 	= f.locationId;
		this.locationName 	= f.locationName;
		this.latitude 		= f.latitude;
		this.longitude 		= f.longitude;
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
	public String getRoadId() {
		return roadId;
	}
	public void setRoadId(String roadId) {
		this.roadId = roadId;
	} 
	public String getRoadName() {
		return roadName;
	}
	public void setRoadName(String roadName) {
		this.roadName = roadName;
	} 
	public int getHaltDuration() {
		return haltDuration;
	}
	public void setHaltDuration(int haltDuration) {
		this.haltDuration = haltDuration;
	} 
	public float getSpeed() {
		return speed;
	}
	public void setSpeed(float speed) {
		this.speed = speed;
	} 
	public String getLocationId() {
		return locationId;
	}
	public void setLocationId(String locationId) {
		this.locationId = locationId;
	} 
	public String getLocationName() {
		return locationName;
	}
	public void setLocationName(String locationName) {
		this.locationName = locationName;
	} 
	public String getLatitude() {
		return latitude;
	}
	public void setLatitude(String latitude) {
		this.latitude= latitude;
	} 
	public String getLongitude() {
		return longitude;
	}
	public void setLongitude(String longitude) {
		this.longitude= longitude;
	} 
	public Date getLogTime() {
		return logTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
