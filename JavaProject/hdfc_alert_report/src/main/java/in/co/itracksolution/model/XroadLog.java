package in.co.itracksolution.model;

import java.util.Date;

public class XroadLog {
	private String imei, date, xRoadId, xRoadCode, xRoadName, location, latitude, longitude; 
  	private float speed;
	private int haltDuration;
	private Date dTime, sTime, logTime;
	
	public static final String TABLE_NAME = "xroadlog";
	
	public SpeedAlert()
	{
		super();
	}
	
	public SpeedAlert(String imei, String date, Date dTime, Date sTime, String xRoadId, String xRoadCode, String xRoadName, int haltDuration, float speed, String location, String latitude, String longitude, Date logTime ) 
	{
		super();
		this.imei = imei;
		this.date = date;
		this.dTime = dTime;
		this.sTime = sTime;
		this.xRoadId = xRoadId;
		this.xRoadCode = xRoadCode;
		this.xRoadName = xRoadName;
		this.haltduration = haltduration;
		this.speed = speed;
		this.location = location;
		this.latitude = latitude;
		this.longitude = longitude;
		this.logTime = logTime;
	}

	public SpeedAlert(SpeedAlert f)
	{
		this.imei = f.imei;
		this.date = f.date;
		this.dTime = f.dTime;
		this.sTime = f.sTime;
		this.xRoadId = f.xRoadId;
		this.xRoadCode = f.xRoadCode;
		this.xRoadName = f.xRoadName;
		this.haltduration = f.haltduration;
		this.speed = f.speed;
		this.location = f.location;
		this.latitude = f.latitude;
		this.longitude = f.longitude;
		this.logTime = f.logTime;
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
	public float getxRoadId() {
		return xRoadId;
	}
	public void setxRoadId(String xRoadId) {
		this.xRoadId = xRoadId;
	} 
	public float getxRoadCode() {
		return xRoadCode;
	}
	public void setxRoadCode(String xRoadCode) {
		this.xRoadCode = xRoadCode;
	} 
	public float getxRoadName() {
		return xRoadName;
	}
	public void setxRoadName(String xRoadName) {
		this.xRoadName = xRoadName;
	} 
	public float getHaltDuration() {
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
	public float getLocation() {
		return location;
	}
	public void setLocation(String location) {
		this.location = location ;
	} 
	public float getLatitude() {
		return latitude;
	}
	public void setLatitude(String latitude) {
		this.latitude= latitude;
	} 
	public float getLongitude() {
		return longitude;
	}
	public void setLongitude(String longitude) {
		this.longitude= longitude;
	} 
	public float getLogTime() {
		return LogTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
