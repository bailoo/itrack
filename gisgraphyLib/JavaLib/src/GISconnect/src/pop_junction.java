import java.util.ArrayList;


public class pop_junction {
	public static void main(String[] argv){
		String lt1= "26.4999750143724";
		String lg1= "80.295552482605";
		
		System.out.println("VIA lat lng ");
		class_pop_junction jct_lat_lng= new class_pop_junction(lt1, lg1);
		ArrayList<String> values1 = jct_lat_lng.Data();
		for(String data : values1){
			System.out.println("data : "+data);
		}
		
		String cde1="20000006";
		System.out.println("VIA code ");
		class_pop_junction jct_code= new class_pop_junction(cde1);
		ArrayList<String> values2 = jct_code.Data();
		for(String data : values2){
			System.out.println("data : "+data);
		}
		
	}
}
