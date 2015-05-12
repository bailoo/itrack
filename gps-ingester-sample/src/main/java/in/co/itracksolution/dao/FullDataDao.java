package in.co.itracksolution.dao;

import java.io.IOException;
import java.io.InputStream;
import java.util.Date;
import java.util.List;
import java.util.ArrayList;
import java.text.SimpleDateFormat;

import in.co.itracksolution.model.FullData;

import org.joda.time.DateTime;
import org.joda.time.Days;
import org.joda.time.LocalDate;
import org.joda.time.LocalDateTime;
import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.datastax.driver.core.Session;

public class FullDataDao {

	protected PreparedStatement insertStatement, deleteStatement, selectbyImeiAndDateStatement, selectbyImeiAndDateTimeSliceStatement;
	protected Session session;
	 
	public FullDataDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		selectbyImeiAndDateStatement = session.prepare(getSelectByImeiAndDateStatement());
		selectbyImeiAndDateTimeSliceStatement = session.prepare(getSelectByImeiAndDateTimeSliceStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO "+FullData.TABLE_NAME+
				" (imei, date, dtime, data, stime)"
				+ " VALUES ("+
				"?,?,?,?,?);";
	}
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+FullData.TABLE_NAME+" WHERE imei = ? AND date = ? AND dtime = ?;";
	}

	protected String getSelectByImeiAndDateStatement(){
		return "SELECT * FROM "+FullData.TABLE_NAME+" WHERE imei = ? AND date = ?;";
	}

	protected String getSelectByImeiAndDateTimeSliceStatement(){
		return "SELECT * FROM "+FullData.TABLE_NAME+" WHERE imei = ? AND date IN ? AND dtime >= ? AND dtime <= ? ;";
	}
	
	public void insert(FullData data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDate(),
				data.getDTime(),
				data.getData(),
				data.getSTime()
				) );
	}
	
	public void delete(FullData data){
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDate(), data.getDTime()));
	}
	
	public ResultSet selectByImeiAndDate(String imei, String date){
		BoundStatement boundStatement = new BoundStatement(selectbyImeiAndDateStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, date));
		return rs;
	}
	
	public ResultSet selectByImeiAndDateTimeSlice(String imei, String startDateTime, String endDateTime){
		BoundStatement boundStatement = new BoundStatement(selectbyImeiAndDateTimeSliceStatement);
		ArrayList dateList = new ArrayList();

		int days = 1;
		LocalDate sDate = new LocalDate();
		LocalDate eDate = new LocalDate();
		long sEpoch=0, eEpoch=0;
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		try {	
			sDate = LocalDate.parse(startDateTime.substring(0,10));
			eDate = LocalDate.parse(endDateTime.substring(0,10));
			sEpoch = sdf.parse(startDateTime).getTime();
			eEpoch = sdf.parse(endDateTime).getTime();
		}
		catch (Exception e) {
			e.printStackTrace();
		}
		Date sDateTime = new Date(sEpoch); // TODO TimeZone
		Date eDateTime = new Date(eEpoch);
		//System.out.println("sDateTime = "+sdf.format(sDateTime));
		//System.out.println("eDateTime = "+sdf.format(eDateTime));

		days = Days.daysBetween(sDate, eDate).getDays();
		for (int i=0; i<days+1; i++) {
			LocalDate d = sDate.plusDays(i);
			dateList.add(d.toString("yyyy-MM-dd"));
		}	

		//System.out.println(dateList);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateList, sDateTime, eDateTime));
		return rs;
	}
	
}
