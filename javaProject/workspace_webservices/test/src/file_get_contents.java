import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Paths;


public class file_get_contents {
	public static void main(String args[]) throws IOException
	{
		String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/%7Bmethod:%22ref_RealTimeDataFetch%22,username:%22raman_itc%22,password:%22novire1308%22%7D/%7Bfrom_dateTime:%2223022015112000%22,to_dateTime:%2223022015113000%22%7D";
		//String Request = "http://webservice.noviretechnologies.com/ws3/rest/controller/services/{method:\"ref_RealTimeDataFetch\",username:\"raman_itc\",password:\"novire1308\"}/{from_dateTime:\"23022015112000\",to_dateTime:\"23022015113000\"}";
		//String content = readFile("test.txt", Charset.defaultCharset());
		System.out.println(readFile(Request, StandardCharsets.UTF_8)); 
	}
	static String readFile(String Request, Charset encoding) 
	  throws IOException 
	{
		byte[] encoded = Files.readAllBytes(Paths.get(Request));
	  return new String(encoded, encoding);
	}
}
