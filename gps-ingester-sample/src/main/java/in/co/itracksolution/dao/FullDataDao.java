package in.co.itracksolution.dao;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import in.co.itracksolution.model.FullData;

import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.datastax.driver.core.Session;
import com.datastax.driver.core.Statement;
import com.datastax.driver.core.querybuilder.QueryBuilder;

public class FullDataDao {

	protected PreparedStatement insertStatement, deleteStatement, selectbyImeiAndDateHourStatement;
	protected Session session;
	 
	public FullDataDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		selectbyImeiAndDateHourStatement = session.prepare(getSelectByImeiAndDateHourStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO gps_full_data (imei, date_hour, message_type, "
				+ "version, fix, lat, lon, speed, device_time, "+
				"server_time, io_one, io_two, io_three, io_four, io_five, io_six, io_seven, io_eight, "+
				"signal_strength, supply_voltage) VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?"+
				");";
	}
	
	protected String getDeleteStatement(){
		return "DELETE FROM gps_full_data WHERE imei = ? AND date_hour=?;";
	}

	protected String getSelectByImeiAndDateHourStatement(){
		return "SELECT * FROM gps_full_data WHERE imei=? and date_hour=?;";
	}

	
	public void insert(FullData data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
				data.getDateHour(),
				data.getMessageType(),
				data.getVersion(),
				data.getFix(),
				data.getLat(),
				data.getLon(),
				data.getSpeed(),
				data.getDeviceTime(),
				data.getServerTime(),
				data.getIoOne(),
				data.getIoTwo(),
				data.getIoThree(),
				data.getIoFour(),
				data.getIoFive(),
				data.getIoSix(),
				data.getIoSeven(),
				data.getIoEight(),
				data.getSignalStrength(),
				data.getSupplyVoltage()
				) );
	}
	
	public void delete(FullData data){
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImei(), data.getDateHour()));
	}
	
	public List<Row> selectByImeiAndDateHour(String imei, Date dateHour){
		BoundStatement boundStatement = new BoundStatement(selectbyImeiAndDateHourStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, dateHour));
		return rs.all();
	}
	
}
