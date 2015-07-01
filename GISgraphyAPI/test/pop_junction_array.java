package com.iespl.gisgraphy;
import java.util.ArrayList;
public class pop_junction_array {
	public static void main(String[] argv){
		//=====put number of lat lng here====//
		//String lt1= "26.506432598194902";
		//String lg1= "80.24858236312866";

		String[] dblngvalues = {"80.305552482605", "80.2527471313477", "80.2916157245636","80.2854895591736" ,"71.5136146"};
		String[] dblatvalues = {"26.4799750143724", "26.5027776489258", "26.487426915577","26.4910230533822","33.9916439"};
		
		//String[] dblngvalues = {"80.305552482605"};
		//String[] dblatvalues = {"26.4799750143724"};
		

		//===================================//		
		ArrayList<LatLng> latlngArray=new ArrayList<LatLng>();
		
		//LatLng obj = new LatLng(lt1, lg1,"","");
		//latlngArray.add(obj);
		for(int ln=0;ln<dblngvalues.length;ln++)
		{
			LatLng obj = new LatLng(dblatvalues[ln], dblngvalues[ln],"","","");
			latlngArray.add(obj);
		}
		
		int rad=200;
		System.out.println("Junction VIA latlng Array ");
		//System.out.println("start time latlng to get"+System.currentTimeMillis());
		//class_pop_road rd_lat_lng= new class_pop_road(latlngArray,rad);		
		class_pop_junction jct_lat_lng= new class_pop_junction(latlngArray,rad);
		
		ArrayList<LatLng>  data = jct_lat_lng.getLatlngData();
		
		for(LatLng obj1 : data){
			System.out.println("Lat : "+obj1.getLat());
			System.out.println("lng : "+obj1.getLng());
			System.out.println("location : "+obj1.getLocation());
			System.out.println("locationCode : "+obj1.getLocationCode());
		}
		//System.out.println("end time latlng to get"+System.currentTimeMillis());
		
		//=====put number of code here====//
		System.out.println("Jucti VIA code ");
		
		System.out.println("start time to get"+System.currentTimeMillis());
		String[] dbcodevalues = {"20000006", "20000005", "20000004","20000003"};
		
		
		//===================================//		
		ArrayList<LatLng> CodeArray=new ArrayList<LatLng>();
		
		//LatLng obj = new LatLng(lt1, lg1,"","");
		//latlngArray.add(obj);
		for(int ln=0;ln<dbcodevalues.length;ln++)
		{
			LatLng obj = new LatLng("","","",dbcodevalues[ln],"");
			CodeArray.add(obj);
		}
		
		class_pop_junction jct_code= new class_pop_junction(CodeArray);
		
		ArrayList<LatLng>  values2 = jct_code.getLatlngData();
		
		for(LatLng obj1 : values2){
			System.out.println("Lat : "+obj1.getLat());
			System.out.println("end time1 to get"+System.currentTimeMillis());
			System.out.println("lng : "+obj1.getLng());
			System.out.println("end time2 to get"+System.currentTimeMillis());
			System.out.println("location : "+obj1.getLocation());
			System.out.println("end time3 to get"+System.currentTimeMillis());
			System.out.println("locationCode : "+obj1.getLocationCode());
			System.out.println("end time4 to get"+System.currentTimeMillis());
		}
		System.out.println("end time to get"+System.currentTimeMillis());
		//===================================//
		/*
		String cde1="100220619";
		System.out.println("Jucti VIA code ");
		class_pop_road rd_code= new class_pop_road(cde1);
		ArrayList<String> values2 = rd_code.Data();
		for(String data : values2){
			System.out.println("data : "+data);
		}*/
		
	}
}
