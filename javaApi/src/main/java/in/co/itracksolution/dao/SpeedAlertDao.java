package in.co.itracksolution.AlertsDao;

import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.List;
import java.util.Iterator;
import java.util.ArrayList;
import java.text.SimpleDateFormat;

import in.co.itracksolution.AlertsModel.SpeedAlert;

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

	protected PreparedStatement insertStatement, deleteStatement, getSpeedAlertByDateStatement, getSpeedAlertByDateRoadIdStatement, getSpeedAlertByDateTimeStatement, getSpeedAlertByDateTimeRoadIdStatement;
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
		getSpeedAlertByDateRoadIdStatement = session.prepare(getGetSpeedAlertByDateRoadIdStatement());
		getSpeedAlertByDateTimeStatement = session.prepare(getGetSpeedAlertByDateTimeStatement());
		getSpeedAlertByDateTimeRoadIdStatement = session.prepare(getGetSpeedAlertByDateTimeRoadIdStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+SpeedAlert.TABLE_NAME+
				" (imei, date, dtime, stime, speed, location, lattitude, longitude, roadId, logtime)"
				+ " VALUES ("+
				"?,?,?,?,?,?,?,?,?,?);";
	}
	
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date = ? AND dtime = ?;";
	}

	protected String getGetSpeedAlertByDateStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date IN ?;";
	}

	protected String getGetSpeedAlertByDateRoadIdStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date IN ? AND roadid IN ?;";
	}

	protected String getGetSpeedAlertByDateTimeStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date IN ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	protected String getGetSpeedAlertByDateTimeRoadIdStatement(){
		return "SELECT * FROM "+SpeedAlert.TABLE_NAME+" WHERE imei = ? AND date IN ? AND roadid IN ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	public void insert(SpeedAlert data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getDTime(),
				data.getSTime(),
				data.getSpeed(),
				data.getLocation(),
				data.getLattitude(),
				data.getLongitude(),
				data.getRoadId(),
				data.getLogTime()
				));
	}
	
	public void delete(SpeedAlert data)
	{
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getDTime()));
	}
	
	private ArrayList<speedAlert> getSpeedAlertList(List<Row> rowList)
	{
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();
		SpeedAlert speedAlert = new SpeedAlert();
		
		for (Row row : rowList)
		{
			speedAlert.setImei(row.getString("imei"));
			speedAlert.setDate(row.getString("date"));
			speedAlert.setDTime(row.getDate("dtime"));
			speedAlert.setSTime(row.getDate("stime"));
			speedAlert.setSpeed(row.getSpeed("speed"));
			speedAlert.setLocation(row.getLocation("location"));
			speedAlert.setLattitude(row.getLattitude("lattitude"));
			speedAlert.setLongitude(row.getLongitude("longitude"));
			speedAlert.setRoadId(row.getRoadId("roadid"));
			speedAlert.setLogTime(row.getLogTime("logtime"));
	
			/* now add speedAlert object to the list */		
			speedAlertList.add(new SpeedAlert(speedAlert));
		}
		
		return speedAlertList;
	}

	private ArrayList getDateList(String startDateTime, String endDateTime)
	{
		int days = 1;
		LocalDate sDate = new LocalDate();
		LocalDate eDate = new LocalDate();
		try {	
			sDate = LocalDate.parse(startDateTime.substring(0,10));
			eDate = LocalDate.parse(endDateTime.substring(0,10));
		}
		catch (Exception e) {
			e.printStackTrace();
		}

		days = Days.daysBetween(sDate, eDate).getDays();
		for (int i=0; i<days+1; i++)
		{
			LocalDate d = sDate.plusDays(i);
			dateList.add(d.toString("yyyy-MM-dd"));
		}	

		//System.out.println(dateList);

	}

	public ArrayList<SpeedAlert> getSpeedAlert(String imei, String date, boolean orderAsc)
	{
		BoundStatement boundStatement = new BoundStatement(getSpeedAlertByDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		List<Row> rowList = rs.all();
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
	
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();
		speedAlertList =  getSpeedAlertList(rowListOrdered);

		return speedAlertList;
	}
	
	public ArrayList<SpeedAlert> getSpeedAlert(String imei, String startDateTime, String endDateTime, Boolean orderAsc)
	{
		BoundStatement boundStatement = new BoundStatement(getSpeedAlertByDateTimeStatement);
		ArrayList dateList = new ArrayList();
		dateList = getDateList(startDateTime, endDateTime)

		long sEpoch=0, eEpoch=0;
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
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
	
		private ArrayList getDateList(String startDateTime, String endDateTime)

		ResultSet rs = session.execute(boundStatement.bind(imei, dateList, sDateTime, eDateTime));
		List<Row> rowList = rs.all();
	
		SpeedAlert speedAlert = new SpeedAlert();
		String[] tokens = null ;
		ArrayList<SpeedAlert> speedAlertList = new ArrayList<SpeedAlert>();

		String data;
		final String DELIMITER = ";";
		List<Row> rowListOrdered = (orderAsc)?Lists.reverse(rowList):rowList;
		for (Row row : rowListOrdered)
		{
			speedAlert.setImei(row.getString("imei"));
			speedAlert.setDTime(row.getDate("dtime"));
			speedAlert.setSTime(row.getDate("stime"));
			
			data = row.getString("data");
			//System.out.println("dtime = "+speedAlert.getDTime());
			tokens = data.split(DELIMITER);
		
			TreeMap pMap1 = new TreeMap();
			int i = 0;
			for(String token : tokens)
			{
				pMap1.put(speedAlert.fullParams[i++], token);
			}
			speedAlert.setPMap(pMap1);
			speedAlertList.add(new SpeedAlert(speedAlert));
		}

		return speedAlertList;
	}
}
