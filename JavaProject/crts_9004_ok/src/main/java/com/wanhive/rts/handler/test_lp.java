package com.wanhive.rts.handler;

import java.io.IOException;
import java.io.RandomAccessFile;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

import com.wanhive.rts.FileWriteHandler;
import com.wanhive.rts.TransactionServer;

public class test_lp {
	
	public void write_last_location(String filename, String MsgType, String vserial, String Version, String Fix, String Latitude, String Longitude, String Speed, String serverdatetime, String DateTime, String io_value1, String io_value2, String io_value3, String io_value4, String io_value5, String io_value6, String io_value7, String io_value8, String Signal_Strength, String SupplyVoltage,String cellname)	
	{				
		/*RandomAccessFile out_a2 =null;
		//************WRITE LAST LOCATION FILE **********************								
		//String filename = "/var/www/html/itrack_vts/xml_vts/last_location/"+vserial+".xml";		
		String marker_a2="", q="\"", xml_date="", xml_last_halt_time="", xml_day_max_speed="", xml_day_max_speed_time="", xml_lat="", xml_lng="";
		String last_halt_time = "", day_max_speed="", day_max_speed_time="", xml_lat_s = "", xml_lng_s = "", Latitude_s="", Longitude_s="";
		float tmp_speed = 0.0f;
		
		day_max_speed = Speed;
		day_max_speed_time = DateTime;	//DEFAULT ASSINGMENT
		
		Date date_last_loc1=null, date_last_loc2=null, date_servertime2=null;
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
						
		String tmpstr2="";
		//tmpstr2 = "/var/www/html/itrack_vts/xml_vts/last_location/"+vserial+".xml";
		tmpstr2 = filename;	
		
		//ADD ONE HOUR TO SERVERTIME
		int minutesToAdd = 60;  // 1 hrs

		try{
		
		date_last_loc2 = (Date) sdf.parse(DateTime);			//PARSE DATE LAST LOCATION
			
		Calendar cal = Calendar.getInstance();
		cal.setTime(sdf.parse(serverdatetime));
		cal.add(Calendar.MINUTE, minutesToAdd);
		//System.out.println(cal.getTime());
			
		String TimeStop_Str = sdf.format(cal.getTime());
			//System.out.println(TimeStop_Str);
			date_servertime2 = (Date) sdf.parse(TimeStop_Str);		//PARSE SERVER DATETIME
		} catch(Exception e1){System.out.println("ErrorLastLocation1="+e1.getMessage());}
		
		
		
		long FileLength=0;
		String strLine1="";
				 
		boolean record_exist = false;
		//### PULL check if record already exists, 
		 // if record_exist=false then go to this block 
		q = ";";
		 
		try
		{				
			if(TransactionServer.last_update_time.get(vserial)==null)
			{
				TransactionServer.last_update_time.put(vserial, 0L);
			}
			//if ((record_exist==false) || ((System.currentTimeMillis()-CurrentFileWriteHandler.UpdateTime)>60000))
			if ((record_exist==false) || ((System.currentTimeMillis() - TransactionServer.last_update_time.get(vserial))>60000))
			{
				out_a2 = new RandomAccessFile(filename, "rwd");
				//out_a2 = new RandomAccessFile(filename, "rw");
				FileLength = out_a2.length();
				out_a2.seek(0);
				
				if(out_a2.readLine()==null)
				{
					last_halt_time = DateTime;
					if((date_last_loc2.compareTo(date_servertime2) < 0) && (!Latitude.equals("")) && (!Longitude.equals("")) && (!Latitude.equals("0.0")) && (!Longitude.equals("0.0")) )
					{
						//System.out.println("WriteFile:FirstTime");
						marker_a2 = MsgType+q+Version+q+Fix+q+Latitude+q+Longitude+q+Speed+q+serverdatetime+q+DateTime+q+io_value1+q+io_value2+q+io_value3+q+io_value4+q+io_value5+q+io_value6+q+io_value7+q+io_value8+q+Signal_Strength+q+SupplyVoltage+q+day_max_speed+q+day_max_speed_time+q+last_halt_time+q+cellname+q;
						
						//String lastSeenData = "N;v1.45C;1;26.25858;79.82557;0.06;2015-01-22@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
						TransactionServer.push_cassandra.insertLastdata(vserial, marker_a2);

						TransactionServer.last_update_time.put(vserial, System.currentTimeMillis());
						//CurrentFileWriteHandler.StrBuf.setLength(0);
						//System.out.println("T1");
						//out_a2.close();
					}
				}
				//########## BLANK FILE CLOSED		
				else
				{																										
					//PULL LAST DATA FROM XML FILE																	
																							
					//####### HANDLE EMPTY VARIABELS
					if(Latitude.equals(""))
					{
						Latitude = "0.0";
					}
					if(Longitude.equals(""))
					{
						Longitude = "0.0";
					}
					if(xml_lat.equals(""))
					{
						xml_lat = "0.0";
					}
					if(xml_lng.equals(""))
					{
						xml_lng = "0.0";
					}
					if(xml_day_max_speed.equals(""))
					{
						xml_day_max_speed = "0.0";
					}
					if(Speed.equals(""))
					{
						Speed = "0.0";
					}
					if(xml_day_max_speed_time.equals(""))
					{
						xml_day_max_speed_time = DateTime;
					}						
					//############################
					
					//######LAST HALT TIME BLOCK
					Latitude_s = Latitude.substring(0, Latitude.length() - 1);
					Longitude_s = Longitude.substring(0, Longitude.length() - 1);
					
					xml_lat_s = xml_lat.substring(0, xml_lat.length() - 1);
					xml_lng_s = xml_lng.substring(0, xml_lng.length() - 1);									
					//System.out.println("One");													
					float distance1 = calculateDistance(Float.parseFloat(Latitude_s), Float.parseFloat(xml_lat_s), Float.parseFloat(Longitude_s), Float.parseFloat(xml_lng_s) );								
					long time_diff = calculateTimeDiff(DateTime, xml_date);  //Seconds
					time_diff = time_diff / 3600;
					//System.out.println("Two");	
					//$tmp_time_diff1 = (strtotime($datetime) - strtotime($last_time1)) / 3600;
					if(time_diff>0)
					{
						tmp_speed = distance1 / (float) time_diff;
					}
					
					//System.out.println("tmp_speed="+tmp_speed+" ,distance="+distance1+" ,time_diff="+time_diff);
					if(tmp_speed>100.0 && distance1>0.1 && time_diff>0)
					{

					}
					else
					{
						//##### LAST HALT TIME
						if(Float.parseFloat(Speed) > 10.0)
						{
							last_halt_time = DateTime;
						}
						else
						{
							if(xml_last_halt_time.equals(""))
							{
								last_halt_time = DateTime;
							}
							else
							{
								last_halt_time = xml_last_halt_time;
							}
						}
						
						//###### DAY MAX SPEED AND TIME
						Float f1 = new Float(xml_day_max_speed);
						double d1 = f1.doubleValue();
						
						Float f2 = new Float(Speed);
						double d2 = f2.doubleValue();								
						
						//System.out.println("xml_day_max_speed="+xml_day_max_speed+", Speed="+Speed);
						//System.out.println("d1="+d1+", d2="+d2);
						
						if(d2 > d1)
						{
							//System.out.println("condition if");
							day_max_speed = Speed;
							day_max_speed_time = DateTime;
						}
						else
						{
							//System.out.println("condition else");
							day_max_speed = xml_day_max_speed;
							day_max_speed_time = xml_day_max_speed_time;
						}
						
						//## RESET SPEED IF DAY CHANGES
						String[] daytmp1,day1,daytmp2,day2;								 
						String delimiter1 = " ",delimiter2="-";
						daytmp1 = xml_date.split(delimiter1);
						daytmp2 = DateTime.split(delimiter1);
						
						day1 = daytmp1[0].split(delimiter2);
						day2 = daytmp2[0].split(delimiter2);
						 
						//System.out.println("day1="+day1[2]+" ,day2="+day2[2]);
						if(!(day1[2].equals(day2[2])))
						{
							//System.out.println("IN day1,day2");
							day_max_speed = "0.0";
							day_max_speed_time = DateTime;									
							//System.out.println("day1="+day1[2]+" ,day2="+day2[2]);
						}								
					}
					try
					{							
						date_last_loc1 = (Date) sdf.parse(xml_date);		//XML DATETIME
							
					}
					catch(Exception e) 
					{
						System.out.println(e.getMessage());
					}
						
						
					//if(  (date_last_loc2.compareTo(date_last_loc1) > 0) && (date_last_loc2.compareTo(date_servertime2) < 0) && (date_last_loc2.compareTo(valid_date_min) > 0) && (date_last_loc2.compareTo(valid_date_max) < 0) ) 
					if(  (date_last_loc2.compareTo(date_last_loc1) > 0) && (date_last_loc2.compareTo(date_servertime2) < 0) && (!Latitude.equals("")) && (!Longitude.equals("")) && (!Latitude.equals("0.0")) && (!Longitude.equals("0.0")) )
					{																  
						//System.out.println("WRITE TO LAST LOCATION FILE:"+filename);								
						//out_a2 = new BufferedWriter(new FileWriter(tmpstr2, false) );
						//out_a2.seek(0);						
						//marker_a2 = "<t1>\n<x "+"a="+q+MsgType+q+" b="+q+Version+q+" c="+q+Fix+q+" d="+q+Latitude+q+" e="+q+Longitude+q+" f="+q+Speed+q+" g="+q+serverdatetime+q+" h="+q+DateTime+q+" i="+q+io_value1+q+" j="+q+io_value2+q+" k="+q+io_value3+q+" l="+q+io_value4+q+" m="+q+io_value5+q+" n="+q+io_value6+q+" o="+q+io_value7+q+" p="+q+io_value8+q+" q="+q+Signal_Strength+q+" r="+q+SupplyVoltage+q+" s="+q+day_max_speed+q+" t="+q+day_max_speed_time+q+" u="+q+last_halt_time+q+" ci="+q+cellname+q+"/>\n</t1>";
						//out_a2.writeBytes(marker_a2);
						//CurrentFileWriteHandler.UpdateTime = System.currentTimeMillis();
						
						marker_a2 = MsgType+q+Version+q+Fix+q+Latitude+q+Longitude+q+Speed+q+serverdatetime+q+DateTime+q+io_value1+q+io_value2+q+io_value3+q+io_value4+q+io_value5+q+io_value6+q+io_value7+q+io_value8+q+Signal_Strength+q+SupplyVoltage+q+day_max_speed+q+day_max_speed_time+q+last_halt_time+q+cellname+q;
						
						//String lastSeenData = "N;v1.45C;1;26.25858;79.82557;0.06;2015-01-22@00:00:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
						TransactionServer.push_cassandra.insertLastdata(vserial, marker_a2);
						TransactionServer.last_update_time.put(vserial, System.currentTimeMillis());
						
						break;
						//out_a2.close();															
						//System.out.println(marker_a2);														
					}
				}

			}
		}
		catch (IOException e)
		{
			e.printStackTrace();
			try
			{
				out_a2.close();
			}
			catch (Exception e1)
			{
				
			}
		}*/
	}	
}
