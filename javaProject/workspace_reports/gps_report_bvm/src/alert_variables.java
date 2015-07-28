import java.util.ArrayList;

public class alert_variables {
		
	//public customer_no = null;
	static int spc_count1=-1;		// VARIABLE FOR GETTING DATA DIRECTORY NAME
	static int spc_count2=-1;		// VARIABLE FOR GETTING DATA DIRECTORY NAME
	
	public static  ArrayList<String> station_account_diretories = new ArrayList<String>();
	public static  ArrayList<String> station_date_diretories = new ArrayList<String>();
	//public static  ArrayList<String> station_files = new ArrayList<String>();
	
	public String date = null;
	public String stringData = "";
	
	public ArrayList<Integer>  entered_flag = new ArrayList<Integer>(); 
	public ArrayList<Integer>  datetime_counter = new ArrayList<Integer>();

	public ArrayList<String>  vserial = new ArrayList<String>();
	public ArrayList<String>  vname1 = new ArrayList<String>();
	
	public ArrayList<String> userdates = new ArrayList<String>();

	public ArrayList<String>  in_min_date_final = new ArrayList<String>();
	public ArrayList<String>  in_max_date_final = new ArrayList<String>();

	public ArrayList<String>  out_min_date_final = new ArrayList<String>();
	public ArrayList<String>  out_max_date_final = new ArrayList<String>();

	public ArrayList<String>  geo_coord_station = new ArrayList<String>();
	public ArrayList<String>  geo_station = new ArrayList<String>();
	public ArrayList<String>  google_location = new ArrayList<String>();
	public ArrayList<Integer>  customer_no_db = new ArrayList<Integer>();	
	public ArrayList<Float>  distance_variable = new ArrayList<Float>();

	/*public ArrayList<String>  intime_halt_2d = new ArrayList<String>();
	public ArrayList<String>  outime_halt_2d = new ArrayList<String>();
	public ArrayList<String>  in_distance_2d = new ArrayList<String>();
	public ArrayList<String>  out_distance_2d = new ArrayList<String>();
	public ArrayList<String>  time_dur_halt_2d = new ArrayList<String>();*/
	
	/*public String[][] intime_halt_2d = new String[1000][1000];
	public String[][] outime_halt_2d = new String[1000][1000];
	public Float[][] in_distance_2d = new Float[1000][1000];
	public Float[][] out_distance_2d = new Float[1000][1000];
	public String[][] time_dur_halt_2d = new String[1000][1000];*/
	
	public String[][] intime_halt_2d = new String[3000][50];
	public String[][] outime_halt_2d = new String[3000][50];
	public Float[][] in_distance_2d = new Float[3000][50];
	public Float[][] out_distance_2d = new Float[3000][50];
	public String[][] time_dur_halt_2d = new String[3000][50];
		
	//public ArrayList<String>  vname_halt_2d = new ArrayList<String>();
	//public ArrayList<String>  cust_no_halt_2d = new ArrayList<String>();
	public ArrayList<String>  input_date1 = new ArrayList<String>();
	public ArrayList<String>  input_date2 = new ArrayList<String>();
	
	public ArrayList<String>  date1_csv = new ArrayList<String>();
	public ArrayList<String>  time1_csv = new ArrayList<String>();

	public ArrayList<String>  date2_csv = new ArrayList<String>();
	public ArrayList<String>  time2_csv = new ArrayList<String>();

	public ArrayList<String>  doctype = new ArrayList<String>();
	public ArrayList<String>  plant = new ArrayList<String>();

	public ArrayList<String>  route = new ArrayList<String>();
	public ArrayList<String>  vname = new ArrayList<String>();

	public ArrayList<String>  vendor_name = new ArrayList<String>();
	//public ArrayList<String>  customer_no = new ArrayList<String>();
	public ArrayList<Integer>  customer_no = new ArrayList<Integer>();
	public ArrayList<String>  customer_time = new ArrayList<String>();
	
	//FORMAT1	
	public ArrayList<String>  trip_date = new ArrayList<String>();
	public ArrayList<String>  input_trip_date1 = new ArrayList<String>();
	public ArrayList<String>  input_trip_date2 = new ArrayList<String>();
	public ArrayList<String>  dcsm_name = new ArrayList<String>();
	public ArrayList<String>  vehicle_route1 = new ArrayList<String>();
	public ArrayList<String>  vehicle_name1 = new ArrayList<String>();
	public ArrayList<String>  imei1_db = new ArrayList<String>();
	public ArrayList<String>  vname1_db = new ArrayList<String>();
	public ArrayList<String>  activity_time1 = new ArrayList<String>();
	public ArrayList<String>  activity_time2 = new ArrayList<String>();
		
	//SCHEDULE	
	public ArrayList<String>  schedule_time = new ArrayList<String>();
	public ArrayList<String>  schedule_in_time = new ArrayList<String>();
	public ArrayList<String>  schedule_out_time = new ArrayList<String>();	

	//FORMAT2	
	/*
	public ArrayList<String>  billing_date = new ArrayList<String>();
	public ArrayList<String>  vehicle_route2 = new ArrayList<String>();
	public ArrayList<String>  time1 = new ArrayList<String>();
	public ArrayList<String>  time2 = new ArrayList<String>();
	public ArrayList<String>  vehicle_name2 = new ArrayList<String>();
	public ArrayList<String>  imei2_db = new ArrayList<String>();
	public ArrayList<String>  vname2_db = new ArrayList<String>();
	public ArrayList<Integer>  customer_no2 = new ArrayList<Integer>();
	*/
}
