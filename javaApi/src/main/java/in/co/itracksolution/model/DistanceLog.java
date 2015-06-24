package in.co.itracksolution.AlertModel;

import java.util.Date;

public class DistanceLog {
	private String imei, date; 
  	private float avgSpeed, distance, maxSpeed;
	private Date startTime, endTime, logTime;
	
	public static final String TABLE_NAME = "speedlog";
	
	public DistanceLog()
	{
		super();
	}
	
	public DistanceLog(String imei, String date, Date startTime, Date endTime, float avgSpeed, float distance, float maxSpeed, Date logTime ) 
	{
		super();
		this.imei = imei;
		this.date = date;
		this.startTime = startTime;
		this.endTime = endTime;
		this.avgSpeed = avgSpeed;
		this.distance = distance;
		this.maxSpeed = maxSpeed;
		this.logTime = logTime;
	}

	public DistanceLog(DistanceLog f)
	{
		this.imei = f.imei;
		this.date = f.date;
		this.startTime = f.startTime;
		this.endTime = f.endTime;
		this.avgSpeed = f.avgSpeed;
		this.distance = f.distance;
		this.maxSpeed = f.maxSpeed;
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
	public Date getStartTime() {
		return startTime;
	}
	public void setStartTime(Date startTime) {
		this.startTime = startTime;
	}
	public Date getEndTime() {
		return endTime;
	}
	public void setEndTime(Date endTime) {
		this.endTime = endTime;
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
	public Date getLogTime() {
		return logTime;
	}
	public void setLogTime(Date logTime ) {
		this.logTime = logTime;
	} 
	
}
