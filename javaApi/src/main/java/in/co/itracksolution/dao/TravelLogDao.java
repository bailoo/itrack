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

import in.co.itracksolution.model.TravelLog;

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

public class TravelLogDao {

	protected PreparedStatement insertStatement, deleteStatement, getTravelLogByDateStatement, getTravelLogByDateInStatement, getTravelLogByDateTimeStatement;
	protected Session session;
	 
	public TravelLogDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getTravelLogByDateStatement = session.prepare(getGetTravelLogByDateStatement());
		getTravelLogByDateInStatement = session.prepare(getGetTravelLogByDateInStatement());
		getTravelLogByDateTimeStatement = session.prepare(getGetTravelLogByDateTimeStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+TravelLog.TABLE_NAME+
				" (imei, date, starttime, startlatitude, startlongitude, startlocation, endtime, endlatitude, endlongitude, endlocation, duration, avgspeed, distance, maxspeed, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
	}
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+TravelLog.TABLE_NAME+" WHERE imei = ? AND date = ? AND starttime = ?;";
	}

	protected String getGetTravelLogByDateStatement(){
		return "SELECT * FROM "+TravelLog.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetTravelLogByDateInStatement(){
		return "SELECT * FROM "+TravelLog.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetTravelLogByDateTimeStatement(){
		return "SELECT * FROM "+TravelLog.TABLE_NAME+" WHERE imei = ? AND date IN ? AND starttime >= ? AND starttime <= ? ;";
	}
	
	public void insert(TravelLog data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getStartTime(),
				data.getStartLatitude(),
				data.getStartLongitude(),
				data.getStartLocation(),
				data.getEndTime(),
				data.getEndLatitude(),
				data.getEndLongitude(),
				data.getEndLocation(),
				data.getDuration(),
				data.getAvgSpeed(),
				data.getDistance(),
				data.getMaxSpeed(),
				data.getLogTime()
				));
	}
	
	public void delete(TravelLog data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getStartTime()));
	}
	
	private ArrayList<TravelLog> getTravelLogList(List<Row> rowList)
	{
		ArrayList<TravelLog> travelLogList = new ArrayList<TravelLog>();
		TravelLog travelLog = new TravelLog();
		
		for (Row row : rowList)
		{
			travelLog.setImei(row.getString("imei"));
			travelLog.setDate(row.getString("date"));
			travelLog.setStartTime(row.getDate("starttime"));
			travelLog.setStartLatitude(row.getString("startlatitude"));
			travelLog.setStartLongitude(row.getString("startlongitude"));
			travelLog.setStartLocation(row.getString("startlocation"));
			travelLog.setEndTime(row.getDate("endtime"));
			travelLog.setEndLatitude(row.getString("endlatitude"));
			travelLog.setEndLongitude(row.getString("endlongitude"));
			travelLog.setEndLocation(row.getString("endlocation"));
			travelLog.setDuration(row.getInt("duration"));
			travelLog.setAvgSpeed(row.getFloat("avgspeed"));
			travelLog.setDistance(row.getFloat("distance"));
			travelLog.setMaxSpeed(row.getFloat("maxspeed"));
			travelLog.setLogTime(row.getDate("logtime"));
	
			/* now add travelLog object to the list */		
			travelLogList.add(new TravelLog(travelLog));
		}
		
		return travelLogList;
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

	public ArrayList<TravelLog> getTravelLogByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<TravelLog> travelLogList = new ArrayList<TravelLog>();
	
		BoundStatement boundStatement = new BoundStatement(getTravelLogByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		travelLogList =  getTravelLogList(rowListOrdered);
		return travelLogList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<TravelLog> getTravelLogByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<TravelLog> travelLogList = new ArrayList<TravelLog>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getTravelLogByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		travelLogList =  getTravelLogList(rowListOrdered);
		return travelLogList;
	}

	public ArrayList<TravelLog> getTravelLogByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<TravelLog> travelLogList = new ArrayList<TravelLog>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getTravelLogByDateTimeStatement);
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
		List<Row> rowList = new ArrayList<Row>(); 
		/* keep only those rows that satisfy endtime <= given endDateTime */
		for (Row row : rs.all())
			if ( row.getDate("endtime").before(eDateTime) )
				rowList.add(row);	
						
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		travelLogList =  getTravelLogList(rowListOrdered);
		return travelLogList;
	}

	public void insertTravelLog(String imei, String starttime, String startlatitude, String startlongitude, String startlocation, String endtime, String endlatitude, String endlongitude, String endlocation, int duration, float avgspeed, float distance, float maxspeed) 
	{
		//TimeZone IST = TimeZone.getTimeZone("Asia/Kolkata");
		Calendar now = Calendar.getInstance(); //gets a calendar using time zone and locale
		//Calendar now = Calendar.getInstance(IST); //gets a calendar using time zone and locale
		//now.setTimeZone(IST);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		sdf.setTimeZone(TimeZone.getTimeZone("Asia/Kolkata"));
		
		String date = starttime.substring(0,10);
		Date starttimeObj = new Date();	
		Date endtimeObj = new Date();	
		try { 
			starttimeObj = sdf.parse(starttime);
			endtimeObj = sdf.parse(endtime);
		}
		catch (Exception e) {
			e.printStackTrace();
		}
	
		TravelLog travelLog = new TravelLog(imei, date, starttimeObj, startlatitude, startlongitude, startlocation, endtimeObj, endlatitude, endlongitude, endlocation, duration, avgspeed, distance, maxspeed, now.getTime());
		insert(travelLog);
		System.out.println("Inserted TravelLog with imei: "+imei);
	}
	
}
