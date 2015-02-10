package in.co.itracksolution.dao;

import java.text.SimpleDateFormat;

import in.co.itracksolution.model.FullData;

import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.Session;

public class FullDataInsertDao {

	protected PreparedStatement statement;
	protected Session session;
	
	public static final SimpleDateFormat DATE_HOUR_FORMAT = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss");
     
	public FullDataInsertDao(Session session) {
		super();
		this.session = session;
		prepareStatement();
	}

	private void prepareStatement(){
		statement = session.prepare(getInsertStatement());
	}

	protected String getInsertStatement(){
		return "INSERT INTO gps_full_data (imei, date_hour, message_type, "
				+ "version, fix, lat, lon, speed, device_time, "+
				"server_time, io_one, io_two, io_three, io_four, io_five, io_six, io_seven, io_eight, "+
				"signal_strength, supply_voltage) VALUES ("+
				"?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?"+
				");";
	}
	

	public void insert(FullData data){
		BoundStatement boundStatement = new BoundStatement(statement);
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
	
}
