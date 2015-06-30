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

import in.co.itracksolution.model.TurnAlert;

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

public class TurnAlertDao {

	protected PreparedStatement insertStatement, deleteStatement, getTurnAlertByDateStatement, getTurnAlertByDateInStatement, getTurnAlertByDateRoadIdStatement, getTurnAlertByDateTimeStatement, getTurnAlertByDateTimeRoadIdStatement;
	protected Session session;
	 
	public TurnAlertDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getTurnAlertByDateStatement = session.prepare(getGetTurnAlertByDateStatement());
		getTurnAlertByDateInStatement = session.prepare(getGetTurnAlertByDateInStatement());
		getTurnAlertByDateRoadIdStatement = session.prepare(getGetTurnAlertByDateRoadIdStatement());
		getTurnAlertByDateTimeStatement = session.prepare(getGetTurnAlertByDateTimeStatement());
		getTurnAlertByDateTimeRoadIdStatement = session.prepare(getGetTurnAlertByDateTimeRoadIdStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+TurnAlert.TABLE_NAME+
				" (imei, date, dtime, stime, speed, angle, locationid, locationname, latitude, longitude, roadid, roadname, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?);";
	}
	
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+TurnAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND dtime = ?;";
	}

	protected String getGetTurnAlertByDateStatement(){
		return "SELECT * FROM "+TurnAlert.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetTurnAlertByDateInStatement(){
		return "SELECT * FROM "+TurnAlert.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetTurnAlertByDateRoadIdStatement(){
		return "SELECT * FROM "+TurnAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND roadid = ?;";
	}

	protected String getGetTurnAlertByDateTimeStatement(){
		return "SELECT * FROM "+TurnAlert.TABLE_NAME+" WHERE imei = ? AND date IN ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	protected String getGetTurnAlertByDateTimeRoadIdStatement(){
		return "SELECT * FROM "+TurnAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND roadid = ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	public void insert(TurnAlert data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getDTime(),
				data.getSTime(),
				data.getSpeed(),
				data.getAngle(),
				data.getLocationId(),
				data.getLocationName(),
				data.getLatitude(),
				data.getLongitude(),
				data.getRoadId(),
				data.getRoadName(),
				data.getLogTime()
				));
	}
	
	public void delete(TurnAlert data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getDTime()));
	}
	
	private ArrayList<TurnAlert> getTurnAlertList(List<Row> rowList)
	{
		ArrayList<TurnAlert> turnAlertList = new ArrayList<TurnAlert>();
		TurnAlert turnAlert = new TurnAlert();
		
		for (Row row : rowList)
		{
			turnAlert.setImei(row.getString("imei"));
			turnAlert.setDate(row.getString("date"));
			turnAlert.setDTime(row.getDate("dtime"));
			turnAlert.setSTime(row.getDate("stime"));
			turnAlert.setSpeed(row.getFloat("speed"));
			turnAlert.setAngle(row.getFloat("angle"));
			turnAlert.setLocationId(row.getString("locationid"));
			turnAlert.setLocationName(row.getString("locationname"));
			turnAlert.setLatitude(row.getString("latitude"));
			turnAlert.setLongitude(row.getString("longitude"));
			turnAlert.setRoadId(row.getString("roadid"));
			turnAlert.setRoadName(row.getString("roadname"));
			turnAlert.setLogTime(row.getDate("logtime"));
	
			/* now add turnAlert object to the list */		
			turnAlertList.add(new TurnAlert(turnAlert));
		}
		
		return turnAlertList;
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

	public ArrayList<TurnAlert> getTurnAlertByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<TurnAlert> turnAlertList = new ArrayList<TurnAlert>();
	
		BoundStatement boundStatement = new BoundStatement(getTurnAlertByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		turnAlertList =  getTurnAlertList(rowListOrdered);
		return turnAlertList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<TurnAlert> getTurnAlertByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<TurnAlert> turnAlertList = new ArrayList<TurnAlert>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getTurnAlertByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		turnAlertList =  getTurnAlertList(rowListOrdered);
		return turnAlertList;
	}

	public ArrayList<TurnAlert> getTurnAlertByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<TurnAlert> turnAlertList = new ArrayList<TurnAlert>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getTurnAlertByDateTimeStatement);
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
		turnAlertList =  getTurnAlertList(rowListOrdered);
		return turnAlertList;
	}

	public void insertTurnAlert(String imei, String dtime, String stime, float speed, float angle, String locationId, String locationName, String latitude, String longitude, String roadId , String roadName ) 
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
	
		TurnAlert turnAlert = new TurnAlert(imei, date, dtimeObj, stimeObj, speed, angle, locationId, locationName, latitude, longitude, roadId, roadName, now.getTime());
		insert(turnAlert);
		System.out.println("Inserted TurnAlert with imei: "+imei);
	}
	
	
}
