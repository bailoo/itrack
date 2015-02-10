package in.co.itracksolution.dao;

import in.co.itracksolution.model.LastData;

import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.Session;

public class LastDataInsertDao extends FullDataInsertDao{

	

	public LastDataInsertDao(Session session) {
		super(session);
	}

	@Override
	protected String getInsertStatement(){
		return "INSERT INTO gps_last_data (imei, message_type, version, fix, lat, lon, speed, device_time, "+
				"server_time, io_one, io_two, io_three, io_four, io_five, io_six, io_seven, io_eight, "+
				"signal_strength, supply_voltage, day_max_speed, day_max_speed_time, last_halt_time) VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?"+
				");";
	}

	
	public void insert(LastData data){
		BoundStatement boundStatement = new BoundStatement(statement);
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
	
}