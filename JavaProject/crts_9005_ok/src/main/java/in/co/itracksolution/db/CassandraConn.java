package in.co.itracksolution.db;

import com.datastax.driver.core.Cluster;
import com.datastax.driver.core.Host;
import com.datastax.driver.core.HostDistance;
import com.datastax.driver.core.Metadata;
import com.datastax.driver.core.PoolingOptions;
import com.datastax.driver.core.Session;
import com.datastax.driver.core.SocketOptions;

public class CassandraConn {
	   private Cluster cluster;
	   private Session session;
	   
	   private int maxRequestPerConnection = 128;
	   private int minRequestPerConnection = 128;
	   private int maxConnectionLocalPerHost = 8;
	   private int maxConnectionRemotePerHost = 2;
	   private int coreConnectionLocalPerHost = 2;
	   private int coreConnectionRemotePerHost = 1;

	   public Session getSession() {
	      return this.session;
	   }
	   
	   public CassandraConn(String node, String keyspace, String username, String password) {
		   
		  PoolingOptions pools = new PoolingOptions();
	      pools.setMaxSimultaneousRequestsPerConnectionThreshold(HostDistance.LOCAL, maxRequestPerConnection);
	      pools.setMaxSimultaneousRequestsPerConnectionThreshold(HostDistance.LOCAL, minRequestPerConnection);
	      pools.setCoreConnectionsPerHost(HostDistance.LOCAL, coreConnectionLocalPerHost);
	      pools.setMaxConnectionsPerHost(HostDistance.LOCAL, maxConnectionLocalPerHost);
	      pools.setCoreConnectionsPerHost(HostDistance.REMOTE, coreConnectionRemotePerHost);
	      pools.setMaxConnectionsPerHost(HostDistance.REMOTE, maxConnectionRemotePerHost);

	        
	      cluster = Cluster.builder()
	            .addContactPoint(node)
	            .withPoolingOptions(pools)
		.withCredentials(username, password)
                .withSocketOptions(new SocketOptions().setTcpNoDelay(true))
                .build();
	      
	      Metadata metadata = cluster.getMetadata();
	      System.out.printf("Connected to cluster: %s\n", 
	            metadata.getClusterName());
	      for ( Host host : metadata.getAllHosts() ) {
	         System.out.printf("Datatacenter: %s; Host: %s; Rack: %s\n",
	               host.getDatacenter(), host.getAddress(), host.getRack());
	      }
	      session = cluster.connect(keyspace);
	   }
	   
	   public void close() {
	      session.close();
	      cluster.close();
	   }

}
