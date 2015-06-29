package in.co.itracksolution.model;

import java.util.Date;

public class XroadLog {
	private String imei, date, xRoadId, xRoadCode, xRoadName, location, latitude, longitude; 
  	private float speed;
	private int haltDuration;
	private Date dTime, sTime, logTime;
	
	public static final String TABLE_NAME = "xroadlog";
	
	public XroadLog()
	{
		super();
	}
	
	public XroadLog(String imei, String date, Date dTime, Date sTime, String xRoadId, String xRoadCode, String xRoadName, int haltDuration, float speed, String location, String latitude, String longitude, Date logTime ) 
	{
		super();
		this.imei = imei;
		this.date = date;
		this.dTime = dTime;
		this.sTime = sTime;
		this.xRoadId = xRoadId;
		this.xRoadCode = xRoadCode;
		this.xRoadName = xRoadName;
		this.haltDuration = haltDuration;
		this.speed = speed;
		this.location = location;
		this.latitude = latitude;
		this.longitude = longitude;
		this.logTime = logTime;
	}

	public XroadLog(XroadLog f)
	{
		this.imei = f.imei;
		this.date = f.date;
		this.dTime = f.dTime;
		this.sTime = f.sTime;
		this.xRoadId = f.xRoadId;
		this.xRoadCode = f.xRoadCode;
		this.xRoadName = f.xRoadName;
		this.haltDuration = f.haltDuration;
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
	public String getXroadId() {
		return xRoadId;
	}
	public void setXroadId(String xRoadId) {
		this.xRoadId = xRoadId;
	} 
	public String getXroadCode() {
		return xRoadCode;
	}
	public void setXroadCode(String xRoadCode) {
		this.xRoadCode = xRoadCode;
	} 
	public String getXroadName() {
		return xRoadName;
	}
	public void setXroadName(String xRoadName) {
		this.xRoadName = xRoadName;
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
	public String getLocation() {
		return location;
	}
	public void setLocation(String location) {
		this.location = location ;
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
