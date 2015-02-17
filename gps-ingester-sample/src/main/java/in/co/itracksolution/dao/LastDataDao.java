package in.co.itracksolution.dao;

import java.util.List;

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
		return "INSERT INTO "+LastData.TABLE_NAME+" (imei, data) VALUES ("+
				"?,?);";
	}

	@Override
	protected String getDeleteStatement(){
		return "DELETE FROM "+LastData.TABLE_NAME+" WHERE imei = ?;";
	}

	
	protected String getSelectByImeiStatement(){
		return "SELECT * FROM "+LastData.TABLE_NAME+" WHERE imei=?;";
	}
	
	public void insert(LastData data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImei(),
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
	
}