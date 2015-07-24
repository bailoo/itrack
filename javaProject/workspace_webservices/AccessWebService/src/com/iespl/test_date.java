package com.iespl;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class test_date {
	public static DateFormat sDF = new SimpleDateFormat("dd MM yy HH:mm:ss", Locale.ENGLISH);
	public static SimpleDateFormat dDF = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");

	public static void main(String args[])
	{
		//dd mm yy hh mm ss
		//3 4 5 6 7 8
		//String date_tmp = data[3]+" "+data[4]+" "+data[5]+" "+data[6]+":"+data[7]+":"+data[8];
		String date_tmp = "27 01 15 12:18:00";
		Date result = null;
		try {
			result = sDF.parse(date_tmp);
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		String DateTimeLast = "";
		DateTimeLast = dDF.format(result);	
		System.out.println(DateTimeLast);
		
	}
}
