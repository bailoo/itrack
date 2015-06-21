package com.hdfc.db.gis;
import java.util.ArrayList;


public class pop_road_array {
	public static void main(String[] argv){
		//=====put number of lat lng here====//
		//String lt1= "26.506432598194902";
		//String lg1= "80.24858236312866";
		
		String[] dblngvalues = {"80.3013038635254", "80.2854919433594", "80.2916157245636","80.2854895591736"};
		String[] dblatvalues = {"26.4821117235667", "26.4910221099854", "26.487426915577","26.4910230533822"};
		
		//===================================//		
		ArrayList<LatLng> latlngArray=new ArrayList<LatLng>();
		
		//LatLng obj = new LatLng(lt1, lg1,"","");
		//latlngArray.add(obj);
		for(int ln=0;ln<dblngvalues.length;ln++)
		{
			LatLng obj = new LatLng(dblatvalues[ln], dblngvalues[ln],"","");
			latlngArray.add(obj);
		}
		
		int rad=200;//meter
		System.out.println("Road VIA latlng Array ");
		class_pop_road rd_lat_lng= new class_pop_road(latlngArray,rad);		
		
		ArrayList<LatLng>  data = rd_lat_lng.getLatlngData();
		
		for(LatLng obj1 : data){
			System.out.println("Lat : "+obj1.getLat());
			System.out.println("lng : "+obj1.getLng());
			System.out.println("location : "+obj1.getLocation());
			System.out.println("locationCode : "+obj1.getLocationCode());
		}
		/*
		String cde1="100220619";
		System.out.println("Road VIA code ");
		class_pop_road rd_code= new class_pop_road(cde1);
		ArrayList<String> values2 = rd_code.Data();
		for(String data : values2){
			System.out.println("data : "+data);
		}*/
		
	}
}
