package com.iespl.gisgraphy;
import java.util.ArrayList;
public class pop_road {
	public static void main(String[] argv){
		String lt1= "26.506432598194902";
		String lg1= "80.24858236312866";
		int rad=200;
		System.out.println("Road VIA lat lng ");
		class_pop_road rd_lat_lng= new class_pop_road(lt1, lg1,rad);
		ArrayList<String> values1 = rd_lat_lng.Data();
		for(String data : values1){
			System.out.println("data : "+data);
		}
		
		String cde1="100220619";
		System.out.println("Road VIA code ");
		class_pop_road rd_code= new class_pop_road(cde1);
		ArrayList<String> values2 = rd_code.Data();
		for(String data : values2){
			System.out.println("data : "+data);
		}
		
	}
}
