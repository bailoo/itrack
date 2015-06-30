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

import in.co.itracksolution.model.GapLog;

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

public class GapLogDao {

	protected PreparedStatement insertStatement, deleteStatement, getGapLogByDateStatement, getGapLogByDateInStatement, getGapLogByDateTimeStatement;
	protected Session session;
	 
	public GapLogDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getGapLogByDateStatement = session.prepare(getGetGapLogByDateStatement());
		getGapLogByDateInStatement = session.prepare(getGetGapLogByDateInStatement());
		getGapLogByDateTimeStatement = session.prepare(getGetGapLogByDateTimeStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+GapLog.TABLE_NAME+
				" (imei, date, type, starttime, startlatitude, startlongitude, startlocationid, startlocationname, endtime, endlatitude, endlongitude, endlocationid, endlocationname, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
	}
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+GapLog.TABLE_NAME+" WHERE imei = ? AND date = ? AND starttime = ?;";
	}

	protected String getGetGapLogByDateStatement(){
		return "SELECT * FROM "+GapLog.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetGapLogByDateInStatement(){
		return "SELECT * FROM "+GapLog.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetGapLogByDateTimeStatement(){
		return "SELECT * FROM "+GapLog.TABLE_NAME+" WHERE imei = ? AND date IN ? AND starttime >= ? AND starttime <= ? ;";
	}
	
	public void insert(GapLog data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getType(),
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
				data.getLogTime()
				));
	}
	
	public void delete(GapLog data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getStartTime()));
	}
	
	private ArrayList<GapLog> getGapLogList(List<Row> rowList)
	{
		ArrayList<GapLog> gapLogList = new ArrayList<GapLog>();
		GapLog gapLog = new GapLog();
		
		for (Row row : rowList)
		{
			gapLog.setImei(row.getString("imei"));
			gapLog.setDate(row.getString("date"));
			gapLog.setType(row.getString("type"));
			gapLog.setStartTime(row.getDate("starttime"));
			gapLog.setStartLatitude(row.getString("startlatitude"));
			gapLog.setStartLongitude(row.getString("startlongitude"));
			gapLog.setStartLocationId(row.getString("startlocationid"));
			gapLog.setStartLocationName(row.getString("startlocationname"));
			gapLog.setEndTime(row.getDate("endtime"));
			gapLog.setEndLatitude(row.getString("endlatitude"));
			gapLog.setEndLongitude(row.getString("endlongitude"));
			gapLog.setEndLocationId(row.getString("endlocationid"));
			gapLog.setEndLocationName(row.getString("endlocationname"));
			gapLog.setLogTime(row.getDate("logtime"));
	
			/* now add gapLog object to the list */		
			gapLogList.add(new GapLog(gapLog));
		}
		
		return gapLogList;
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

	public ArrayList<GapLog> getGapLogByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<GapLog> gapLogList = new ArrayList<GapLog>();
	
		BoundStatement boundStatement = new BoundStatement(getGapLogByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		gapLogList =  getGapLogList(rowListOrdered);
		return gapLogList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<GapLog> getGapLogByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<GapLog> gapLogList = new ArrayList<GapLog>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getGapLogByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		gapLogList =  getGapLogList(rowListOrdered);
		return gapLogList;
	}

	public ArrayList<GapLog> getGapLogByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<GapLog> gapLogList = new ArrayList<GapLog>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getGapLogByDateTimeStatement);
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
		gapLogList =  getGapLogList(rowListOrdered);
		return gapLogList;
	}

	public void insertGapLog(String imei, String type, String starttime, String startlatitude, String startlongitude, String startlocationid, String startlocationname, String endtime, String endlatitude, String endlongitude, String endlocationid, String endlocationname) 
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
	
		GapLog gapLog = new GapLog(imei, date, type, starttimeObj, startlatitude, startlongitude, startlocationid, startlocationname, endtimeObj, endlatitude, endlongitude, endlocationid, endlocationname, now.getTime());
		insert(gapLog);
		System.out.println("Inserted GapLog with imei: "+imei);
	}
	
}
