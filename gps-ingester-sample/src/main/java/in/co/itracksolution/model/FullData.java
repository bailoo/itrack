package in.co.itracksolution.model;

import java.util.Date;

public class FullData {
	private String imei, messageType, version;
	private Date dateHour, deviceTime, serverTime;
	private int fix, signalStrength;
	private double lat, lon, speed;
	private float supplyVoltage, ioOne, ioTwo, ioThree, ioFour, ioFive, ioSix, ioSeven, ioEight;
	
	public FullData(){
		super();
	}
	
	public FullData(String imei, String message_type, String version,
			Date dateHour, Date deviceTime, Date serveTime, int fix,
			int signalStrength, double lat, double lon, double speed,
			float supplyVoltage, float ioOne, float ioTwo, float ioThree,
			float ioFour, float ioFive, float ioSix, float ioSeven,
			float ioEight) {
		super();
		this.imei = imei;
		this.messageType = message_type;
		this.version = version;
		this.dateHour = dateHour;
		this.deviceTime = deviceTime;
		this.serverTime = serveTime;
		this.fix = fix;
		this.signalStrength = signalStrength;
		this.lat = lat;
		this.lon = lon;
		this.speed = speed;
		this.supplyVoltage = supplyVoltage;
		this.ioOne = ioOne;
		this.ioTwo = ioTwo;
		this.ioThree = ioThree;
		this.ioFour = ioFour;
		this.ioFive = ioFive;
		this.ioSix = ioSix;
		this.ioSeven = ioSeven;
		this.ioEight = ioEight;
	}
	public String getImei() {
		return imei;
	}
	public void setImei(String imei) {
		this.imei = imei;
	}
	public String getMessageType() {
		return messageType;
	}
	public void setMessageType(String message_type) {
		this.messageType = message_type;
	}
	public String getVersion() {
		return version;
	}
	public void setVersion(String version) {
		this.version = version;
	}
	public Date getDateHour() {
		return dateHour;
	}
	public void setDateHour(Date dateHour) {
		this.dateHour = dateHour;
	}
	public Date getDeviceTime() {
		return deviceTime;
	}
	public void setDeviceTime(Date deviceTime) {
		this.deviceTime = deviceTime;
	}
	public Date getServerTime() {
		return serverTime;
	}
	public void setServerTime(Date serveTime) {
		this.serverTime = serveTime;
	}
	public int getFix() {
		return fix;
	}
	public void setFix(int fix) {
		this.fix = fix;
	}
	public int getSignalStrength() {
		return signalStrength;
	}
	public void setSignalStrength(int signalStrength) {
		this.signalStrength = signalStrength;
	}
	public double getLat() {
		return lat;
	}
	public void setLat(double lat) {
		this.lat = lat;
	}
	public double getLon() {
		return lon;
	}
	public void setLon(double lon) {
		this.lon = lon;
	}
	public double getSpeed() {
		return speed;
	}
	public void setSpeed(double speed) {
		this.speed = speed;
	}
	public float getSupplyVoltage() {
		return supplyVoltage;
	}
	public void setSupplyVoltage(float supplyVoltage) {
		this.supplyVoltage = supplyVoltage;
	}
	public float getIoOne() {
		return ioOne;
	}
	public void setIoOne(float ioOne) {
		this.ioOne = ioOne;
	}
	public float getIoTwo() {
		return ioTwo;
	}
	public void setIoTwo(float ioTwo) {
		this.ioTwo = ioTwo;
	}
	public float getIoThree() {
		return ioThree;
	}
	public void setIoThree(float ioThree) {
		this.ioThree = ioThree;
	}
	public float getIoFour() {
		return ioFour;
	}
	public void setIoFour(float ioFour) {
		this.ioFour = ioFour;
	}
	public float getIoFive() {
		return ioFive;
	}
	public void setIoFive(float ioFive) {
		this.ioFive = ioFive;
	}
	public float getIoSix() {
		return ioSix;
	}
	public void setIoSix(float ioSix) {
		this.ioSix = ioSix;
	}
	public float getIoSeven() {
		return ioSeven;
	}
	public void setIoSeven(float ioSeven) {
		this.ioSeven = ioSeven;
	}
	public float getIoEight() {
		return ioEight;
	}
	public void setIoEight(float ioEight) {
		this.ioEight = ioEight;
	}
	
}
