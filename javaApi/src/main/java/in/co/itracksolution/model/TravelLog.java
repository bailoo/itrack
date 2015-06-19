package in.co.itracksolution.model;

import java.util.Date;

public class TravelLog {
	private String imei, date, startLatitude, startLongitude, startLocation, endLatitude, endLongitude, endLocation; 
	private int duration;
  	private float avgSpeed, distance, maxSpeed;
	private Date startTime, endTime, logTime;
	
	public static final String TABLE_NAME = "travellog";
	
	public SpeedAlert()
	{
		super();
	}
	
	public SpeedAlert(String imei, String date, Date startTime, String startLatitude, String startLongitude, String startLocation, String endLatitude, String endLongitude, String endLocation, Date endTime, int duration, float avgSpeed, float distance, float maxSpeed, Date logTime ) 
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
		this.duration 		= duration;
		this.avgSpeed 		= avgSpeed;
		this.distance 		= distance;
		this.maxSpeed 		= maxSpeed;
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
		this.duration 		= f.duration;
		this.avgSpeed 		= f.avgSpeed;
		this.distance 		= f.distance;
		this.maxSpeed 		= f.maxSpeed;
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
	public float getDuration() {
		return duration;
	}
	public void setDuration(int duration) {
		this.duration = durationa;
	} 
	public float getAvgSpeed() {
		return avgSpeed;
	}
	public void setAvgSpeed(float avgSpeed) {
		this.avgSpeed = avgSpeed;
	} 
	public float getDistance() {
		return distance;
	}
	public void setDistance(float distance) {
		this.distance = distance;
	} 
	public float getMaxSpeed() {
		return maxSpeed;
	}
	public void setMaxSpeed(float maxSpeed) {
		this.maxSpeed = maxSpeed;
	} 
	public float getLogTime() {
		return LogTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
