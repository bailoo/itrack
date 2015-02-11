package in.co.itracksolution.dao;

import java.util.Date;
import java.util.List;

import in.co.itracksolution.model.LastData;
import in.co.itracksolution.model.LastData;

import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.datastax.driver.core.Session;

public class LastDataDao extends FullDataDao{

	protected PreparedStatement selectbyImeiStatement;

	public LastDataDao(Session session) {
		super(session);
	}

	@Override
	protected void prepareStatement(){
		insertStatement = session.prepare(getInsertStatement());
		deleteStatement = session.prepare(getDeleteStatement());
		selectbyImeiStatement = session.prepare(getSelectByImeiStatement());
	}
	
	@Override
	protected String getInsertStatement(){
		return "INSERT INTO gps_last_data (imei, message_type, version, fix, lat, lon, speed, device_time, "+
				"server_time, io_one, io_two, io_three, io_four, io_five, io_six, io_seven, io_eight, "+
				"signal_strength, supply_voltage, day_max_speed, day_max_speed_time, last_halt_time) VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?"+
				");";
	}

	@Override
	protected String getDeleteStatement(){
		return "DELETE FROM gps_last_data WHERE imei = ?;";
	}

	
	protected String getSelectByImeiStatement(){
		return "SELECT * FROM gps_last_data WHERE imei=?;";
	}
	
	public void insert(LastData data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
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
				data.getSupplyVoltage(),
				data.getDayMaxSpeed(),
				data.getDayMaxSpeedTime(),
				data.getLastHaltTime()
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
	
}