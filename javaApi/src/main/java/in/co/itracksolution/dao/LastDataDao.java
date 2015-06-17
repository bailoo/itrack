package in.co.itracksolution.dao;

import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.List;
import java.util.Date;
import java.util.ArrayList;
import java.text.SimpleDateFormat;

import in.co.itracksolution.model.LastData;
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

public class LastDataDao extends FullDataDao{

	protected PreparedStatement insertStatement, deleteStatement, selectbyImeiStatement, selectbyImeiAndDateTimeStatement;

	public LastDataDao(Session session) {
		super(session);
		prepareStatement();
	}

	//@Override
	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		selectbyImeiStatement = session.prepare(getSelectByImeiStatement());
		selectbyImeiAndDateTimeStatement = session.prepare(getSelectByImeiAndDateTimeStatement());
	}
	
	//@Override
	protected String getInsertStatement(){
		return "INSERT INTO "+LastData.TABLE_NAME+" (imei, stime, data) VALUES ("+
				"?,?,?);";
	}

	//@Override
	protected String getDeleteStatement(){
		return "DELETE FROM "+LastData.TABLE_NAME+" WHERE imei = ?;";
	}

	protected String getSelectByImeiStatement(){
		return "SELECT * FROM "+LastData.TABLE_NAME+" WHERE imei=?;";
	}
	
	protected String getSelectByImeiAndDateTimeStatement(){
		return "SELECT * FROM "+FullData.TABLE_NAME1+" WHERE imei = ? AND date = ? AND dtime <= ? LIMIT 1;";
	}
	
	public void insert(LastData data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getSTime(),
				data.getData()
				) );
	}
	
	public void delete(LastData data){
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei()));
	}
	
	public LastData selectByImei(String imei){
		LastData lastData = new LastData();
		BoundStatement boundStatement = new BoundStatement(selectbyImeiStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei));
		Row row = rs.one();
		String data = row.getString("data");
		Date stime = row.getDate("stime");
			
		final String DELIMITER = ";";
		String[] tokens = data.split(DELIMITER);
		int i = 0;
		for(String token : tokens)
			lastData.pMap.put(lastData.lastParams[i++], token);

		lastData.setImei(imei);
		lastData.setSTime(stime);
		lastData.setData(data);
		return lastData;

	}

	public FullData selectByImeiAndDateTime(String imei, String endDateTime)
	{
		FullData fullData = new FullData();
		BoundStatement boundStatement = new BoundStatement(selectbyImeiAndDateTimeStatement);

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		// yyyy-mm-dd hh:mm:ss
		Calendar cal = Calendar.getInstance();
		cal.add(Calendar.YEAR, -1);
		String startDateTime = sdf.format(cal.getTime());

		int days = 1;
		LocalDate sDate = new LocalDate();
		LocalDate eDate = new LocalDate();
		long sEpoch=0, eEpoch=0;
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

		String date;
		days = Days.daysBetween(sDate, eDate).getDays();
		for (int i = days; i >= 0; i--)
		{
			LocalDate d = sDate.plusDays(i);
			date = d.toString("yyyy-MM-dd");
			//System.out.println(date);
			ResultSet rs = session.execute(boundStatement.bind(imei, date, eDateTime));
			Row row = rs.one();
			if (row != null)
			{
				//return row;
				fullData.setImei(row.getString("imei"));
				fullData.setDTime(row.getDate("dtime"));
				fullData.setSTime(row.getDate("stime"));
			
				String data = row.getString("data");
				final String DELIMITER = ";";
				String[] tokens = data.split(DELIMITER);
				int j = 0;
				for(String token : tokens)
					fullData.pMap.put(fullData.fullParams[j++], token);
				
				return fullData;
			}
		}	
		
		return null;
	}
	
	
}
