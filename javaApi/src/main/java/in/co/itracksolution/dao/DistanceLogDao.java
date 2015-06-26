package in.co.itracksolution.dao;

import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.List;
import java.util.Iterator;
import java.util.ArrayList;
import java.text.SimpleDateFormat;

import in.co.itracksolution.model.DistanceLog;

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

public class DistanceLogDao {

	protected PreparedStatement insertStatement, deleteStatement, getDistanceLogByDateStatement, getDistanceLogByDateInStatement, getDistanceLogByDateTimeStatement;
	protected Session session;
	 
	public DistanceLogDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		getDistanceLogByDateStatement = session.prepare(getGetDistanceLogByDateStatement());
		getDistanceLogByDateInStatement = session.prepare(getGetDistanceLogByDateInStatement());
		getDistanceLogByDateTimeStatement = session.prepare(getGetDistanceLogByDateTimeStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+DistanceLog.TABLE_NAME+
				" (imei, date, starttime, enstarttime, avgspeed, distance, maxspeed, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?);";
	}
	
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+DistanceLog.TABLE_NAME+" WHERE imei = ? AND date = ? AND starttime = ?;";
	}

	protected String getGetDistanceLogByDateStatement(){
		return "SELECT * FROM "+DistanceLog.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getGetDistanceLogByDateInStatement(){
		return "SELECT * FROM "+DistanceLog.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetDistanceLogByDateTimeStatement(){
		return "SELECT * FROM "+DistanceLog.TABLE_NAME+" WHERE imei = ? AND date IN ? AND starttime >= ? AND starttime <= ? ;";
	}
	
	public void insert(DistanceLog data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getStartTime(),
				data.getEndTime(),
				data.getAvgSpeed(),
				data.getDistance(),
				data.getMaxSpeed(),
				data.getLogTime()
				));
	}
	
	public void delete(DistanceLog data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getStartTime()));
	}
	
	private ArrayList<DistanceLog> getDistanceLogList(List<Row> rowList)
	{
		ArrayList<DistanceLog> distanceLogList = new ArrayList<DistanceLog>();
		DistanceLog distanceLog = new DistanceLog();
		
		for (Row row : rowList)
		{
			distanceLog.setImei(row.getString("imei"));
			distanceLog.setDate(row.getString("date"));
			distanceLog.setStartTime(row.getDate("starttime"));
			distanceLog.setEndTime(row.getDate("endtime"));
			distanceLog.setAvgSpeed(row.getFloat("avgspeed"));
			distanceLog.setDistance(row.getFloat("distance"));
			distanceLog.setMaxSpeed(row.getFloat("maxspeed"));
			distanceLog.setLogTime(row.getDate("logtime"));
	
			/* now add distanceLog object to the list */		
			distanceLogList.add(new DistanceLog(distanceLog));
		}
		
		return distanceLogList;
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

	public ArrayList<DistanceLog> getDistanceLogByDate(String imei, String date, Boolean orderAsc)
	{
		ArrayList<DistanceLog> distanceLogList = new ArrayList<DistanceLog>();
	
		BoundStatement boundStatement = new BoundStatement(getDistanceLogByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		distanceLogList =  getDistanceLogList(rowListOrdered);
		return distanceLogList;
	}


	/* overloaded function with starting and ending date */
	
	public ArrayList<DistanceLog> getDistanceLogByDate(String imei, String startDate, String endDate, Boolean orderAsc)
	{
		ArrayList<DistanceLog> distanceLogList = new ArrayList<DistanceLog>();
		ArrayList dateList = new ArrayList();
	
		BoundStatement boundStatement = new BoundStatement(getDistanceLogByDateInStatement);
		dateList = getDateList(startDate, endDate);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		distanceLogList =  getDistanceLogList(rowListOrdered);
		return distanceLogList;
	}

	public ArrayList<DistanceLog> getDistanceLogByDateTime(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		ArrayList<DistanceLog> distanceLogList = new ArrayList<DistanceLog>();
		ArrayList dateList = new ArrayList();

		BoundStatement boundStatement = new BoundStatement(getDistanceLogByDateTimeStatement);
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
		distanceLogList =  getDistanceLogList(rowListOrdered);
		return distanceLogList;
	}
}
