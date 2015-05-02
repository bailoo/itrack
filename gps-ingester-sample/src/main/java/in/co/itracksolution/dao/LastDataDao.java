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

	protected PreparedStatement selectbyImeiStatement, selectbyImeiAndDateTimeStatement;

	public LastDataDao(Session session) {
		super(session);
	}

	@Override
	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		selectbyImeiStatement = session.prepare(getSelectByImeiStatement());
		selectbyImeiAndDateTimeStatement = session.prepare(getSelectByImeiAndDateTimeStatement());
	}
	
	@Override
	protected String getInsertStatement(){
		return "INSERT INTO "+LastData.TABLE_NAME+" (imei, stime, data) VALUES ("+
				"?,?,?);";
	}

	@Override
	protected String getDeleteStatement(){
		return "DELETE FROM "+LastData.TABLE_NAME+" WHERE imei = ?;";
	}

	protected String getSelectByImeiStatement(){
		return "SELECT * FROM "+LastData.TABLE_NAME+" WHERE imei=?;";
	}
	
	protected String getSelectByImeiAndDateTimeStatement(){
		return "SELECT * FROM "+FullData.TABLE_NAME+" WHERE imeih IN ? AND dtime <= ? LIMIT 1;";
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
	
	public List<Row> selectByImei(String imei){
		BoundStatement boundStatement = new BoundStatement(selectbyImeiStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei));
		return rs.all();
	}

	public List<Row> selectByImeiAndDateTime(String imei, String endDateTime){
		BoundStatement boundStatement = new BoundStatement(selectbyImeiAndDateTimeStatement);

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		// yyyy-mm-dd hh:mm:ss
		Calendar cal = Calendar.getInstance();
		cal.add(Calendar.YEAR, -1);
		String startDateTime = sdf.format(cal.getTime());

		ArrayList imeihList = new ArrayList();

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

		days = Days.daysBetween(sDate, eDate).getDays();
		for (int i=0; i<days+1; i++) {
			LocalDate d = sDate.plusDays(i);
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@00");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@01");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@02");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@03");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@04");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@05");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@06");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@07");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@08");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@09");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@10");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@11");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@12");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@13");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@14");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@15");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@16");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@17");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@18");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@19");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@20");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@21");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@22");
			imeihList.add(imei+"@"+d.toString("yyyy-MM-dd")+"@23");
		}	

		//System.out.println(imeihList);
		ResultSet rs = session.execute(boundStatement.bind(imeihList, eDateTime));
		return rs.all();
	}
	
	
}
