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

import in.co.itracksolution.model.XroadLog;

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

public class XroadLogDao {

	protected PreparedStatement insertStatement, deleteStatement, getXroadLogByDateStatement, getXroadLogByDateInStatement, getXroadLogByDateTimeStatement;
	protected Session session;
	 
	public XroadLogDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getXroadLogByDateStatement = session.prepare(getGetXroadLogByDateStatement());
		getXroadLogByDateInStatement = session.prepare(getGetXroadLogByDateInStatement());
		getXroadLogByDateTimeStatement = session.prepare(getGetXroadLogByDateTimeStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+XroadLog.TABLE_NAME+
				" (imei, date, dtime, stime, xroadid, xroadcode, xroadname, haltduration, speed, location, latitude, longitude, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?);";
	}
	
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+XroadLog.TABLE_NAME+" WHERE imei = ? AND date = ? AND dtime = ?;";
	}

	protected String getGetXroadLogByDateStatement(){
		return "SELECT * FROM "+XroadLog.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetXroadLogByDateInStatement(){
		return "SELECT * FROM "+XroadLog.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetXroadLogByDateTimeStatement(){
		return "SELECT * FROM "+XroadLog.TABLE_NAME+" WHERE imei = ? AND date IN ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	public void insert(XroadLog data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getDTime(),
				data.getSTime(),
				data.getXroadId(),
				data.getXroadCode(),
				data.getXroadName(),
				data.getHaltDuration(),
				data.getSpeed(),
				data.getLocation(),
				data.getLatitude(),
				data.getLongitude(),
				data.getLogTime()
				));
	}
	
	public void delete(XroadLog data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getDTime()));
	}
	
	private ArrayList<XroadLog> getXroadLogList(List<Row> rowList)
	{
		ArrayList<XroadLog> xroadLogList = new ArrayList<XroadLog>();
		XroadLog xroadLog = new XroadLog();
		
		for (Row row : rowList)
		{
			xroadLog.setImei(row.getString("imei"));
			xroadLog.setDate(row.getString("date"));
			xroadLog.setDTime(row.getDate("dtime"));
			xroadLog.setSTime(row.getDate("stime"));
			xroadLog.setXroadId(row.getString("xroadid"));
			xroadLog.setXroadCode(row.getString("xroadcode"));
			xroadLog.setXroadName(row.getString("xroadname"));
			xroadLog.setHaltDuration(row.getInt("haltduration"));
			xroadLog.setSpeed(row.getFloat("speed"));
			xroadLog.setLocation(row.getString("location"));
			xroadLog.setLatitude(row.getString("latitude"));
			xroadLog.setLongitude(row.getString("longitude"));
			xroadLog.setLogTime(row.getDate("logtime"));
	
			/* now add xroadLog object to the list */		
			xroadLogList.add(new XroadLog(xroadLog));
		}
		
		return xroadLogList;
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

	public ArrayList<XroadLog> getXroadLogByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<XroadLog> xroadLogList = new ArrayList<XroadLog>();
	
		BoundStatement boundStatement = new BoundStatement(getXroadLogByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		xroadLogList =  getXroadLogList(rowListOrdered);
		return xroadLogList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<XroadLog> getXroadLogByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<XroadLog> xroadLogList = new ArrayList<XroadLog>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getXroadLogByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		xroadLogList =  getXroadLogList(rowListOrdered);
		return xroadLogList;
	}

	public ArrayList<XroadLog> getXroadLogByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<XroadLog> xroadLogList = new ArrayList<XroadLog>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getXroadLogByDateTimeStatement);
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
		xroadLogList =  getXroadLogList(rowListOrdered);
		return xroadLogList;
	}

	public void insertXroadLog(String imei, String dtime, String stime, String xroadid, String xroadcode, String xroadname, int haltduration, float speed, String location, String latitude, String longitude) 
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
	
		XroadLog xroadLog = new XroadLog(imei, date, dtimeObj, stimeObj, xroadid, xroadcode, xroadname, haltduration, speed, location, latitude, longitude, now.getTime());
		insert(xroadLog);
		System.out.println("Inserted XroadLog with imei: "+imei);
	}

}
