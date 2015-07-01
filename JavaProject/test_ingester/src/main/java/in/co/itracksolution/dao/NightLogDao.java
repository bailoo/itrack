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

import in.co.itracksolution.model.NightLog;

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

public class NightLogDao {

	protected PreparedStatement insertStatement, deleteStatement, getNightLogByDateStatement, getNightLogByDateInStatement, getNightLogByDateTimeStatement;
	protected Session session;
	 
	public NightLogDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getNightLogByDateStatement = session.prepare(getGetNightLogByDateStatement());
		getNightLogByDateInStatement = session.prepare(getGetNightLogByDateInStatement());
		getNightLogByDateTimeStatement = session.prepare(getGetNightLogByDateTimeStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+NightLog.TABLE_NAME+
				" (imei, date, starttime, startlatitude, startlongitude, startlocationid, startlocationname, endtime, endlatitude, endlongitude, endlocationid, endlocationname, duration, avgspeed, distance, maxspeed, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
	}
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+NightLog.TABLE_NAME+" WHERE imei = ? AND date = ? AND starttime = ?;";
	}

	protected String getGetNightLogByDateStatement(){
		return "SELECT * FROM "+NightLog.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetNightLogByDateInStatement(){
		return "SELECT * FROM "+NightLog.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetNightLogByDateTimeStatement(){
		return "SELECT * FROM "+NightLog.TABLE_NAME+" WHERE imei = ? AND date IN ? AND starttime >= ? AND starttime <= ? ;";
	}
	
	public void insert(NightLog data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getStartTime(),
				data.getStartLatitude(),
				data.getStartLongitude(),
				data.getStartLocationId(),
				data.getStartLocationName(),
				data.getEndTime(),
				data.getEndLatitude(),
				data.getEndLongitude(),
				data.getEndLocationId(),
				data.getEndLocationName(),
				data.getDuration(),
				data.getAvgSpeed(),
				data.getDistance(),
				data.getMaxSpeed(),
				data.getLogTime()
				));
	}
	
	public void delete(NightLog data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getStartTime()));
	}
	
	private ArrayList<NightLog> getNightLogList(List<Row> rowList)
	{
		ArrayList<NightLog> nightLogList = new ArrayList<NightLog>();
		NightLog nightLog = new NightLog();
		
		for (Row row : rowList)
		{
			nightLog.setImei(row.getString("imei"));
			nightLog.setDate(row.getString("date"));
			nightLog.setStartTime(row.getDate("starttime"));
			nightLog.setStartLatitude(row.getString("startlatitude"));
			nightLog.setStartLongitude(row.getString("startlongitude"));
			nightLog.setStartLocationId(row.getString("startlocationid"));
			nightLog.setStartLocationName(row.getString("startlocationname"));
			nightLog.setEndTime(row.getDate("endtime"));
			nightLog.setEndLatitude(row.getString("endlatitude"));
			nightLog.setEndLongitude(row.getString("endlongitude"));
			nightLog.setEndLocationId(row.getString("endlocationid"));
			nightLog.setEndLocationName(row.getString("endlocationname"));
			nightLog.setDuration(row.getInt("duration"));
			nightLog.setAvgSpeed(row.getFloat("avgspeed"));
			nightLog.setDistance(row.getFloat("distance"));
			nightLog.setMaxSpeed(row.getFloat("maxspeed"));
			nightLog.setLogTime(row.getDate("logtime"));
	
			/* now add nightLog object to the list */		
			nightLogList.add(new NightLog(nightLog));
		}
		
		return nightLogList;
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

	public ArrayList<NightLog> getNightLogByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<NightLog> nightLogList = new ArrayList<NightLog>();
	
		BoundStatement boundStatement = new BoundStatement(getNightLogByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		nightLogList =  getNightLogList(rowListOrdered);
		return nightLogList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<NightLog> getNightLogByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<NightLog> nightLogList = new ArrayList<NightLog>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getNightLogByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		nightLogList =  getNightLogList(rowListOrdered);
		return nightLogList;
	}

	public ArrayList<NightLog> getNightLogByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<NightLog> nightLogList = new ArrayList<NightLog>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getNightLogByDateTimeStatement);
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
		nightLogList =  getNightLogList(rowListOrdered);
		return nightLogList;
	}

	public void insertNightLog(String imei, String starttime, String startlatitude, String startlongitude, String startlocationid, String startlocationname, String endtime, String endlatitude, String endlongitude, String endlocationid, String endlocationname, int duration, float avgspeed, float distance, float maxspeed) 
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
	
		NightLog nightLog = new NightLog(imei, date, starttimeObj, startlatitude, startlongitude, startlocationid, startlocationname, endtimeObj, endlatitude, endlongitude, endlocationid, endlocationname, duration, avgspeed, distance, maxspeed, now.getTime());
		insert(nightLog);
		System.out.println("Inserted NightLog with imei: "+imei);
	}
	
}
