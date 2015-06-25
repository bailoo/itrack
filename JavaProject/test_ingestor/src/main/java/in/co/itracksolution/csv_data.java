package in.co.itracksolution;

import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;

public class csv_data {
    public static ArrayList<String> IMEI_No = new ArrayList<String>();
    public static ArrayList<String> turningDeviceTime = new ArrayList<String>();
    public static ArrayList<String> turningServerTime = new ArrayList<String>();
    public static ArrayList<Double> turningSpeed = new ArrayList<Double>();
    public static ArrayList<Float> turningAngle = new ArrayList<Float>();
    public static ArrayList<Double> turningLatitude = new ArrayList<Double>();
    public static ArrayList<Double> turningLongitude = new ArrayList<Double>();
    public static ArrayList<String> locationCode = new ArrayList<String>();
    public static ArrayList<Integer> roadID = new ArrayList<Integer>();
    //String abspath_sorted = "D:\\ECLIPSE_WORKSPACE/JAR_FILES/159_AWS_JAR/final_csv/csv";
    String abspath_sorted = "D:\\ECLIPSE_WORKSPACE/JAR_FILES/159_AWS_JAR/final_csv/csv";
    FileWriter fw = null;

	public void read_csv_data() {
		
		get_all_imeis();	
		
	   for(int j=0;j<IMEI_No.size();j++) {
		   String csv_path = abspath_sorted+"/"+IMEI_No.get(j)+".csv";
		   System.out.println("PATH:"+csv_path);
					
			File file1 = new File(csv_path);
			boolean exist1 = file1.exists();

			System.out.println("Exist="+exist1);
			if (exist1) {    		
				try {
					FileInputStream fstream = new FileInputStream(csv_path);
					// Get the object of DataInputStream
					DataInputStream in = new DataInputStream(fstream);
					BufferedReader br = new BufferedReader(new InputStreamReader(in));
					String strLine;
					
					while ((strLine = br.readLine()) != null) {
						
						int len = strLine.length();
					
						if(len > 50) {
							try{
								/*a="NORMAL" b="3.3LF" c="1" d="29.94753N" e="78.16069E" f="0.56" g="2015-06-14 00:00:01" h="2015-06-13 23:59:59" i="4000" j="0" k="0" l="0" m="0" n="0" o="0" p="0" q="0" r="4.05" 
								ci="01B0#F232" ax="7" ay="34" az="1024" mx="7" my="35" mz="1025" bx="0" by="0" bz="0"*/
								String[] line = strLine.split(",");
								
								//DeviceTime,ServerTime,Speed (Km/hr),Angle (Deg),Latitude,Longitude
								IMEI_No.add(IMEI_No.get(j));
								turningDeviceTime.add(line[0]);
								turningServerTime.add(line[1]);
								turningSpeed.add(Double.parseDouble(line[2]));
								turningAngle.add(Float.parseFloat(line[3]));
								turningLatitude.add(Double.parseDouble(line[4]));
								turningLongitude.add(Double.parseDouble(line[5]));								
								
							} catch(Exception e2){}
						}
					}
				} catch(Exception e3){}
			}
		}
	}
	
   public static void get_all_imeis() {
	   
	   IMEI_No.add("862170017133743");
	   IMEI_No.add("862170018313864");
	   /*IMEI_No.add("862170018383289");
	   IMEI_No.add("862170017810241");
	   IMEI_No.add("862170018367480");
	   IMEI_No.add("862170018382455");
	   IMEI_No.add("862170017136738");
	   IMEI_No.add("862170017811629");
	   IMEI_No.add("862170014330466");
	   IMEI_No.add("862170017809201");
	   IMEI_No.add("861074025248769");
	   IMEI_No.add("862170018365773");
	   IMEI_No.add("862170017809789");
	   IMEI_No.add("862170017134105");
	   IMEI_No.add("862170017809896");
	   IMEI_No.add("862170017135763");
	   IMEI_No.add("862170017134725");
	   IMEI_No.add("862170017809383");
	   IMEI_No.add("862170017811306");
	   IMEI_No.add("862170017810308");
	   IMEI_No.add("861074025249445");
	   IMEI_No.add("862170017810704");
	   IMEI_No.add("862170014374043");
	   IMEI_No.add("862170018381903");
	   IMEI_No.add("862170017811488");
	   IMEI_No.add("862170017810456");
	   IMEI_No.add("862170017809169");
	   IMEI_No.add("862170017809136");
	   IMEI_No.add("862170018367365");
	   IMEI_No.add("862170017808674");
	   IMEI_No.add("862170017809714");
	   IMEI_No.add("862170017809359");
	   IMEI_No.add("862170017811165");
	   IMEI_No.add("862170018367134");
	   IMEI_No.add("862170018323202");
	   IMEI_No.add("862170018322881");
	   IMEI_No.add("862170017811645");
	   IMEI_No.add("862170017811611");
	   IMEI_No.add("862170018367217");
	   IMEI_No.add("862170017810852");
	   IMEI_No.add("862170017134287");*/		   	
   }			

}

