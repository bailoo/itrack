import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.nio.charset.Charset;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Paths;


public class HelloWorld {
	public static void main(String args[]) throws IOException
	{
		System.out.println("TEST ANT PROJECT");
		//String Request = "http%3B//webservice.noviretechnologies.com/ws3/rest/controller/services/{method%3B\"ref_RealTimeDataFetch\",username%3B\"raman_itc\",password%3B\"novire1308\"}/{from_dateTime%3B\"23022015112000\",to_dateTime%3B\"23022015113000\"}";
		//String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method%3A\"ref_RealTimeDataFetch\",username%3A\"raman_itc\",password%3A\"novire1308\"}/{from_dateTime%3A\"23022015112000\",to_dateTime%3A\"23022015113000\"}";
		//String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method%3A\"ref_RealTimeDataFetch\",username%3A\"raman_itc\",password%3A\"novire1308\"}/{from_dateTime%3A\"23022015112000\",to_dateTime%3A\"23022015113000\"}";
		//String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method:\"ref_RealTimeDataFetch\",username:\"raman_itc\",password:\"novire1308\"}/{from_dateTime:\"23022015112000\",to_dateTime:\"23022015113000\"}";
//		String Request = "http://webservice.noviretechnologies.com%2Fws3%2Frest%2Fcontroller%2Fservices%2F%7Bmethod%3A%22ref_RealTimeDataFetch%22%2Cusername%3A%22raman_itc%22%2Cpassword%3A%22novire1308%5C%22%7D%2F%7Bfrom_dateTime%3A%2223022015112000%22%2Cto_dateTime%3A%2223022015113000%22%7D";
		String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/%7Bmethod:%22ref_RealTimeDataFetch%22,username:%22raman_itc%22,password:%22novire1308%22%7D/%7Bfrom_dateTime:%2223022015112000%22,to_dateTime:%2223022015113000%22%7D";


		//Request=Request.toString();
		//String content = readFile("test.txt", Charset.defaultCharset());
		//URLEncoder.encode(Request, "UTF-8");
		//System.out.println(readFile(Request, StandardCharsets.UTF_8));
		
		//String Request = "/{from_dateTime:\"23022015112000\",to_dateTime:\"23022015113000\"}";
		//Request = Request.replace(":","%3A");
		//String encodedID = URLEncoder.encode(Request, "UTF-8").replace("+", "%20");
		//String endpoint="https://127.0.0.1/getResourceNameToUse?id=" + encodedID;
		//String endpoint="http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method:\"ref_RealTimeDataFetch\",username:\"raman_itc\",password:\"novire1308\"}" + encodedID;
	    //URL my_url = null;
		/*try {
			my_url = new URL(endpoint);
		} catch (MalformedURLException e) {
			// TODO Auto-generated catch block
			//e.printStackTrace();
		}*/
        URL my_url = null;
		try {
			my_url = new URL(Request);
		} catch (MalformedURLException e) {
			// TODO Auto-generated catch block
			//e.printStackTrace();
		}
        
	    System.out.println("my_url:"+my_url);
        BufferedReader br = null;
        try{
        	br = new BufferedReader(new InputStreamReader(my_url.openStream()));
        }catch(Exception b){System.out.println(b.getMessage());}
        System.out.println("br:"+br);
        String strTemp = "";
        try {
			while(null != (strTemp = br.readLine())){
				System.out.println("Data1:"+strTemp);            	
			}
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		
		//System.out.println(readFile(endpoint, StandardCharsets.UTF_8));
	}
	
	static String readFile(String Request, Charset encoding) 
	{
		Charset characterSet = Charset.forName("US-ASCII");
		byte[] bytes = Request.getBytes(characterSet);
	    System.out.println("Len="+bytes.length);
		for(int k = 0; k < bytes.length; k++){
	        System.out.write(bytes[k]);
	    }
	    
		//byte[] encoded = null;
		try {
			bytes = Files.readAllBytes(Paths.get(Request));
		} catch (IOException e) {
			// TODO Auto-generated catch block
			//e.printStackTrace();
			System.out.println(e.getMessage());
		}
		//String new_req = new String(encoded, encoding);
		return new String(bytes, encoding);	
		
        /*URL my_url = null;
		try {
			my_url = new URL(Request);
		} catch (MalformedURLException e) {
			// TODO Auto-generated catch block
			//e.printStackTrace();
		}
        System.out.println("my_url:"+my_url);
        BufferedReader br = null;
        try{
        	br = new BufferedReader(new InputStreamReader(my_url.openStream()));
        }catch(Exception b){System.out.println(b.getMessage());}
        System.out.println("br:"+br);
        String strTemp = "";
        try {
			while(null != (strTemp = br.readLine())){
				System.out.println("Data1:"+strTemp);            	
			}
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}*/
        	//return null;
	}
}
