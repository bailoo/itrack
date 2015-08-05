import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;


public class test {
	public static SimpleDateFormat dDF = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	
	public static void main(String args[])
	{
		String last_datetime="",current_datetime="";
		Date currentDate = new Date();
		Calendar cal = Calendar.getInstance();
		cal.setTime(currentDate);
		cal.add(Calendar.MINUTE, -10);
		Date oneHourBack = cal.getTime();
		   
		System.out.println("oneHourBack="+oneHourBack);
		
		last_datetime = dDF.format(oneHourBack);
		System.out.println("last_datetime="+last_datetime);
		
		Date date = new Date();
		current_datetime = dDF.format(date);
		System.out.println("current_datetime="+current_datetime);
	}
}
