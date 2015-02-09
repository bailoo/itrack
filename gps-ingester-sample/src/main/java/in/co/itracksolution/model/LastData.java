package in.co.itracksolution.model;

import java.util.Date;

public class LastData extends FullData{

	private float dayMaxSpeed;
	private Date dayMaxSpeedTime;
	private Date lastHaltTime;
	
	
	
	public LastData() {
		super();
	}

	public LastData(String imei, String message_type, String version,
			Date dateHour, Date deviceTime, Date serveTime, int fix,
			int signalStrength, double lat, double lon, double speed,
			float supplyVoltage, float ioOne, float ioTwo, float ioThree,
			float ioFour, float ioFive, float ioSix, float ioSeven,
			float ioEight, float dayMaxSpeed, Date dayMaxSpeedTime,
			Date lastHaltTime) {
	
		super(imei, message_type, version, dateHour, deviceTime, serveTime,
				fix, signalStrength, lat, lon, speed, supplyVoltage, ioOne,
				ioTwo, ioThree, ioFour, ioFive, ioSix, ioSeven, ioEight);
		this.dayMaxSpeed = dayMaxSpeed;
		this.dayMaxSpeedTime = dayMaxSpeedTime;
		this.lastHaltTime = lastHaltTime;
	}

	public float getDayMaxSpeed() {
		return dayMaxSpeed;
	}

	public void setDayMaxSpeed(float dayMaxSpeed) {
		this.dayMaxSpeed = dayMaxSpeed;
	}

	public Date getDayMaxSpeedTime() {
		return dayMaxSpeedTime;
	}

	public void setDayMaxSpeedTime(Date dayMaxSpeedTime) {
		this.dayMaxSpeedTime = dayMaxSpeedTime;
	}

	public Date getLastHaltTime() {
		return lastHaltTime;
	}

	public void setLastHaltTime(Date lastHaltTime) {
		this.lastHaltTime = lastHaltTime;
	}
	
}
