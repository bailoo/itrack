package in.co.itracksolution.AlertsModel;

import java.util.Date;

public class SpeedAlert {
	private String imei, date, location, latitude, longitude, roadId; 
  	private float speed;
	private Date sTime, dTime, logTime;
	
	public static final String TABLE_NAME = "speedlog";
	
	public SpeedAlert()
	{
		super();
	}
	
	public SpeedAlert(String imei, String date, Date dTime, Date sTime, float speed, String location, String latitude, String longitude, String roadId, Date logTime ) 
	{
		super();
		this.imei = imei;
		this.date = date;
		this.dTime = dTime;
		this.sTime = sTime;
		this.speed = speed;
		this.location = location;
		this.latitude = latitude;
		this.longitude = longitude;
		this.roadId = roadId;
		this.logTime = logTime;
	}

	public SpeedAlert(SpeedAlert f)
	{
		this.imei = f.imei;
		this.date = f.date;
		this.dTime = f.dTime;
		this.sTime = f.sTime;
		this.speed = f.speed;
		this.location = f.location;
		this.latitude = f.latitude;
		this.longitude = f.longitude;
		this.roadId = f.roadId;
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
	public String getRoadId() {
		return roadId;
	}
	public void setRoadId(String roadId) {
		this.roadId = roadId;
	} 
	public Date getLogTime() {
		return logTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
