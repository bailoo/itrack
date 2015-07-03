package in.co.itracksolution.dao;

import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.TimeZone;
import java.util.Calendar;
import java.util.List;
import java.util.Iterator;
import java.util.ArrayList;
import java.text.SimpleDateFormat;

import in.co.itracksolution.model.SpeedAlert;

import org.joda.time.DateTime;
import org.joda.time.Days;
import org.joda.time.LocalDate;
import org.joda.time.LocalDateTime;
import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.datastax.driver.core.Session;
import com.google.common.collect.Lists;

public class SpeedAlertDao {

	protected PreparedStatement insertStatement, deleteStatement, getSpeedAlertByDateStatement, getSpeedAlertByDateInStatement, getSpeedAlertByDateRoadIdStatement, getSpeedAlertByDateTimeStatement, getSpeedAlertByDateTimeRoadIdStatement;
	protected Session session;
	 
	public SpeedAlertDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getSpeedAlertByDateStatement = session.prepare(getGetSpeedAlertByDateStatement());
		getSpeedAlertByDateInStatement = session.prepare(getGetSpeedAlertByDateInStatement());
		getSpeedAlertByDateRoadIdStatement = session.prepare(getGetSpeedAlertByDateRoadIdStatement());
		getSpeedAlertByDateTimeStatement = session.prepare(getGetSpeedAlertByDateTimeStatement());
		getSpeedAlertByDateTimeRoadIdStatement = session.prepare(getGetSpeedAlertByDateTimeRoadIdStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+SpeedAlert.TABLE_NAME+
				" (imei, date, dtime, stime, speed, locationid, locationname, latitude, longitude, roadid, roadname, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?);";
	}
	
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND dtime = ?;";
	}

	protected String getGetSpeedAlertByDateStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetSpeedAlertByDateInStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetSpeedAlertByDateRoadIdStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND roadid = ?;";
	}

	protected String getGetSpeedAlertByDateTimeStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date IN ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	protected String getGetSpeedAlertByDateTimeRoadIdStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND roadid = ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	public void insert(SpeedAlert data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getDTime(),
				data.getSTime(),
				data.getSpeed(),
				data.getLocationId(),
				data.getLocationName(),
				data.getLatitude(),
				data.getLongitude(),
				data.getRoadId(),
				data.getRoadName(),
				data.getLogTime()
				));
	}
	
	public void delete(SpeedAlert data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getDTime()));
	}
	
	private ArrayList<SpeedAlert> getSpeedAlertList(List<Row> rowList)
	{
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();
		SpeedAlert speedAlert = new SpeedAlert();
		
		for (Row row : rowList)
		{
			speedAlert.setImei(row.getString("imei"));
			speedAlert.setDate(row.getString("date"));
			speedAlert.setDTime(row.getDate("dtime"));
			speedAlert.setSTime(row.getDate("stime"));
			speedAlert.setSpeed(row.getFloat("speed"));
			speedAlert.setLocationId(row.getString("locationid"));
			speedAlert.setLocationName(row.getString("locationname"));
			speedAlert.setLatitude(row.getString("latitude"));
			speedAlert.setLongitude(row.getString("longitude"));
			speedAlert.setRoadId(row.getString("roadid"));
			speedAlert.setRoadName(row.getString("roadname"));
			speedAlert.setLogTime(row.getDate("logtime"));
	
			/* now add speedAlert object to the list */		
			speedAlertList.add(new SpeedAlert(speedAlert));
		}
		
		return speedAlertList;
	}

	private ArrayList getDateList(String startDateTime, String endDateTime)
	{
		ArrayList dateList = new ArrayList();
		LocalDate sDate = new LocalDate();
		LocalDate eDate = new LocalDate();
		try {	
			sDate = LocalDate.parse(startDateTime.substring(0,10));
			eDate = LocalDate.parse(endDateTime.substring(0,10));
		}
		catch (Exception e) {
			e.printStackTrace();
		}

		int days = Days.daysBetween(sDate, eDate).getDays();
		for (int i=days; i>=0; i--)
		{
			LocalDate d = sDate.plusDays(i);
			dateList.add(d.toString("yyyy-MM-dd"));
		}	

		//System.out.println(dateList);
		return dateList;
	}

	public ArrayList<SpeedAlert> getSpeedAlertByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();
	
		BoundStatement boundStatement = new BoundStatement(getSpeedAlertByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		speedAlertList =  getSpeedAlertList(rowListOrdered);
		return speedAlertList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<SpeedAlert> getSpeedAlertByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getSpeedAlertByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		speedAlertList =  getSpeedAlertList(rowListOrdered);
		return speedAlertList;
	}

	public ArrayList<SpeedAlert> getSpeedAlertByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getSpeedAlertByDateTimeStatement);
		dateList = getDateList(startDateTime, endDateTime);

		long sEpoch=0, eEpoch=0;
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ssZ");
		try {	
			sEpoch = sdf.parse(startDateTime+"+0530").getTime();
			eEpoch = sdf.parse(endDateTime+"+0530").getTime();
		}
		catch (Exception e) {
			e.printStackTrace();
		}
		Date sDateTime = new Date(sEpoch); // TODO TimeZone
		Date eDateTime = new Date(eEpoch);
		//System.out.println("sDateTime = "+sdf.format(sDateTime));
		//System.out.println("eDateTime = "+sdf.format(eDateTime));
	
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList, sDateTime, eDateTime));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		speedAlertList =  getSpeedAlertList(rowListOrdered);
		return speedAlertList;
	}

	public void insertSpeedAlert(String imei, String dtime, String stime, float speed, String locationId, String locationName, String latitude, String longitude, String roadId, String roadName ) 
	{
		//TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(); //gets a calendar using time zone and locale
		//Calendar now = Calendar.getInstance(IST); //gets a calendar using time zone and locale
		//now.setTimeZone(IST);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		sdf.setTimeZone(TimeZone.getTimeZone("Asia/Kolkata"));
		
		String date = dtime.substring(0,10);
		Date dtimeObj = new Date();	
		Date stimeObj = new Date();	
		try { 
			dtimeObj = sdf.parse(dtime);
			stimeObj = sdf.parse(stime);
		}
		catch (Exception e) {
			e.printStackTrace();
		}
	
		SpeedAlert speedAlert = new SpeedAlert(imei, date, dtimeObj, stimeObj, speed, locationId, locationName, latitude, longitude, roadId, roadName, now.getTime());
		insert(speedAlert);
		System.out.println("Inserted SpeedAlert with imei: "+imei);
	}

}
