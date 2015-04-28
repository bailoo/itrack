package in.co.itracksolution.dao;

import java.util.Date;
import java.util.List;

import in.co.itracksolution.model.FullData;

import com.datastax.driver.core.BoundStatement;
import com.datastax.driver.core.PreparedStatement;
import com.datastax.driver.core.ResultSet;
import com.datastax.driver.core.Row;
import com.datastax.driver.core.Session;

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
		return "INSERT INTO "+FullData.TABLE_NAME+
				" (imeih, dtime, stime, data)"
				+ " VALUES ("+
				"?,?,?,?);";
	}
	
	protected String getDeleteStatement(){
		return "DELETE FROM "+FullData.TABLE_NAME+" WHERE imeih = ? AND dtime=?;";
	}

	protected String getSelectByImeiAndDateHourStatement(){
		return "SELECT * FROM "+FullData.TABLE_NAME+" WHERE imeih=? AND dtime=?;";
	}

	
	public void insert(FullData data){
		BoundStatement boundStatement = new BoundStatement(insertStatement);
		session.execute(boundStatement.bind(
				data.getImeih(),
				data.getDTime(),
				data.getSTime(),
				data.getData()
				) );
	}
	
	public void delete(FullData data){
		BoundStatement boundStatement = new BoundStatement(deleteStatement);
		session.execute(boundStatement.bind(data.getImeih(), data.getDTime()));
	}
	
	public List<Row> selectByImeiAndDateHour(String imei, String dTime){
		BoundStatement boundStatement = new BoundStatement(selectbyImeiAndDateHourStatement);
		ResultSet rs = session.execute(boundStatement.bind(imei, dTime));
		return rs.all();
	}
	
}
