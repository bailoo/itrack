package in.co.itracksolution;

import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;

public class Test
{

	public static class FullData
	{
		public String imei;
		public FullData()
		{
			super();
		}
		public String getImei() {
			return this.imei;
		}
		public void setImei(String imei) {
			this.imei = imei;
		}
		public FullData(FullData f)
		{
			this.imei = f.imei;			
		}
	}	

	public static void main(String[] args) 
	{
		ArrayList<FullData> fullDataList = new ArrayList<FullData>();
		
		FullData x = new FullData();
		x.setImei("111");
		System.out.println("x imei = "+x.getImei());
		fullDataList.add(new FullData(x));

		x.setImei("222");
		System.out.println("x imei = "+x.getImei());
		fullDataList.add(new FullData(x));

		FullData f = fullDataList.get(0);	
		System.out.println("f imei = "+f.getImei());
	
	}
}
