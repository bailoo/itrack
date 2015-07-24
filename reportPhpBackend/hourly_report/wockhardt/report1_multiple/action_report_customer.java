//display
	for(i=0;i<av_pc.geo_coord_station.size();i++)
	{								
		av_pc.stringData = av_pc.stringData + (String)av_pc.date1_csv.get(i) + "," + (String) av_pc.time1_csv.get(i) +"," + (String) av_pc.date2_csv.get(i) +"," + (String) av_pc.time2_csv.get(i) +"," + (String) av_pc.doctype.get(i) +"," + (String) av_pc.plant.get(i) +"," + (String) av_pc.route.get(i) +"," + (String) av_pc.vname.get(i) + "," + (String) av_pc.vendor_name.get(i) + "," + (Integer) av_pc.customer_no.get(i);					
		av_pc.stringData = av_pc.stringData + "," + (String) av_pc.google_location.get(i)+","+sts+","+devicetime;

			System.out.println("\nDatetimeCounter="+av_pc.datetime_counter.get(i));

			for(int j=0;j<(av_pc.datetime_counter.get(i));j++)
			{		
				valid_record = true;
									
				String[] in_date_arr = av_pc.intime_halt_2d[i][j].split(" ");
				String[] out_date_arr = av_pc.outime_halt_2d[i][j].split(" ");
				
				//FORMAT DATE TO DD-MM-YY
				String[] in_date = in_date_arr[0].split("-");              
				String in_date_tmp = in_date[2]+"-"+in_date[1]+"-"+in_date[0];
				
				String[] out_date = out_date_arr[0].split("-");              
				String out_date_tmp = out_date[2]+"-"+out_date[1]+"-"+out_date[0];
				//echo "<br>in_date=".$in_date_tmp." ,out_date=".$out_date_tmp;
				/////////////////////////        
				float in_dist_tmp = utility_classes.Round(av_pc.in_distance_2d[i][j],2);
				float out_dist_tmp = utility_classes.Round(av_pc.out_distance_2d[i][j],2);
				String time_delay = "",diff_out_time="";						
				//System.out.println("in_date_tmp="+in_date_tmp+" , in_date_arr[1]="+in_date_arr[1]+", out_date_tmp="+out_date_tmp+" ,out_date_arr[1]="+out_date_arr[1]+" ,av.time_dur_halt2d[i][j]="+av.time_dur_halt_2d[i][j]+" ,in_dist_tmp="+in_dist_tmp+" ,out_dist_tmp="+out_dist_tmp);
				
				if(j == 0)
				{							
					String in_time_str_report = av_pc.intime_halt_2d[i][j]; 		//REPORT TIME STR
					String[] in_date1 = ((String)av_pc.input_date1.get(i)).split(" ");							
					String in_time_str_master = in_date1[0]+" "+schedule_in_time;   //SCHEDULE TIME STR 					

				else
				{
					//############# FINAL STRING
					av_pc.stringData = av_pc.stringData+","+in_date_tmp+","+in_date_arr[1]+","+out_date_tmp+","+out_date_arr[1]+","+av_pc.time_dur_halt_2d[i][j]+","+in_dist_tmp+","+out_dist_tmp;					
				}						
			}  // FOR DATETIME COUNTER CLOSED
		} catch(Exception ef) { System.out.println("EXCEPTION IN FINAL BLOCK(STATION)::CUSTOMER:MSG="+ef.getMessage());}   
						
		av_pc.stringData = av_pc.stringData+"\n";
		}   // FOR GEO COORD SIZE CLOSED	
	}catch(Exception ef2) { System.out.println("EXCEPTION IN GEO BLOCK(STATION)::CUSTOMER:MSG="+ef2.getMessage());}
	
	//System.out.println("av.stringData ="+av.stringData);
}


	public static void get_station_xml_data(String vehicle_serial, String vname1, String startdate, String enddate, alert_variable_plant_customer av_pc)
	{		
		for(int i=0;i<=(date_size-1);i++)
		{  		
			while ((strLine = br.readLine()) != null) {

				if(xml_date!=null)
				{				    					
					xml_date_sec = utility_classes.get_seconds(xml_date, 2);
					startdate_sec = utility_classes.get_seconds(startdate, 2);
					enddate_sec = utility_classes.get_seconds(enddate, 2);

					try{
						if( (xml_date_sec >= startdate_sec && xml_date_sec <= enddate_sec) && (!xml_date.equals("-")) && (fix.equals("1")) )
						{							           	              																													
							lat = sort_xml.getXmlAttribute(strLine,"lat=\"[^\" ]+");
							lng  = sort_xml.getXmlAttribute(strLine,"lng=\"[^\" ]+");											
							vserial = vehicle_serial; 
							
							lat = lat.substring(0, lat.length() - 1);
							lng = lng.substring(0, lng.length() - 1);
																				
							if( (!lat.equals("")) && (!lng.equals("")) )
							{
								no_gps = false;
							}													
							//System.out.println("VSERIAL="+vserial+" ,LAT="+lat+" ,LNG="+lng);
							//System.out.println("GEO_SIZE="+av.geo_coord_station.size());
							//###### LOGIC PART STARTED

							if(primary_halt_firstdata_flag==0)
							{							
								primary_halt_flag = 0;
								primary_halt_firstdata_flag = 1;

								primary_halt_lat_ref = lat;		
								primary_halt_lng_ref = lng;							                	
								primary_halt_xml_data_sec_ref = xml_date_sec;
								date_ref = xml_date;
							}        
							else
							{														
								primary_halt_lat_cr = lat;		
								primary_halt_lng_cr = lng;							                	
								primary_halt_xml_data_sec_cr = xml_date_sec;
								date_cr = xml_date;

								primary_halt_distance = utility_classes.calculateDistance(Float.parseFloat(primary_halt_lat_ref),Float.parseFloat(primary_halt_lat_cr),Float.parseFloat(primary_halt_lng_ref),Float.parseFloat(primary_halt_lng_cr));
										
								//if((primary_halt_distance > 0.150) || (f == total_lines-10) )														
								if(primary_halt_distance>0.1)
								{
									primary_halt_lat_ref = primary_halt_lat_cr;
									primary_halt_lng_ref = primary_halt_lng_cr;
									primary_halt_xml_data_sec_ref = primary_halt_xml_data_sec_cr; 
									halt_complete=0;
								}																												
								
								if(((primary_halt_xml_data_sec_cr - primary_halt_xml_data_sec_ref)>60) && (halt_complete==0))   // IF VEHICLE STOPS FOR 2 MINS 
								{            			
									//System.out.println("DateREf:"+date_ref+" ,DateCr:"+date_cr);
									//System.out.println("Vehicle STOPEED more than 1 min");
									primary_halt_flag = 1;
								}
								
								if((primary_halt_flag == 1) && (halt_occured == false))
								{
									//System.out.println("HALT FLAG1");
									
									halt_complete = 1;
									try
									{
										//System.out.println("Halt Duration above 60 sec");																		
										if((av_pc.geo_coord_station.size())>0)
										{                
											//System.out.println("IN GEO_COORD");              
									
											//System.out.println("STATION SIZE="+av.geo_coord_station.size());
											try
											{
												for(int g=0; g<av_pc.geo_coord_station.size();g++)
												{																						
													if(!vname1.equals( (String)av_pc.vname.get(g)))
													{
														continue;
													}
																
													input_date1_sec = utility_classes.get_seconds(((String)av_pc.input_date1.get(g)), 2);
													input_date2_sec = utility_classes.get_seconds(((String)av_pc.input_date2.get(g)), 2);
																
													try
													{
														if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
														{
															//System.out.println("DATE MATCHED: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
															status_geo = false;
																		
															try
															{
																if(!((String) av_pc.geo_coord_station.get(g)).equals(""))
																{
																	String[] geo_data = av_pc.geo_coord_station.get(g).split(",");
																	geo_lat = geo_data[0];
																	geo_lng = geo_data[1];
																				
																	//System.out.println("geo_lat="+geo_lat+" ,geo_lng="+geo_lng+ " ,lat="+lat+" ,lng="+lng+" ,distance_variable="+((Float)av_pc.distance_variable.get(g))+" ,av_pc.customer_no_db.get(g)="+av_pc.customer_no_db.get(g));			
																	status_geo = utility_classes.check_with_range_landmark(lat, lng, geo_lat, geo_lng, ((Float)av_pc.distance_variable.get(g)));
																	//System.out.println("status_geo="+status_geo);
																} 
															} catch(Exception e3) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN1:"+e3.getMessage());}
																										
															try
															{
																if( (status_geo==true) && ((Integer)av_pc.entered_flag.get(g) == 0) )
																{                                            
																	//System.out.println("HALT_OCCURRED_BEFORE_SET:"+vname1+" ,customer="+av_pc.customer_no_db.get(g)+", dist_variable="+av_pc.distance_variable.get(g)+" ,xml_date="+xml_date+" ,entered_flag="+(Integer)av_pc.entered_flag.get(g));															
																	halt_occured = true;
																	last_customer_no = av_pc.customer_no_db.get(g);
																					
																	av_pc.entered_flag.set(g,1);	//corresponding to g
																				
																	//System.out.println("HALT_OCCURRED_AFTER_SET: ,entered_flag="+(Integer)av.entered_flag.get(g));                     
																	enter_time = xml_date;                                              
																	in_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																				
																	//System.out.println("Final:DateREf:"+date_ref+" ,DateCr:"+date_cr);
																	//System.out.println("STATION_GEO: TRUE1, enter_time="+enter_time);
																	//System.out.println("indist_BEFORE_SET="+in_dist);
																					
																	av_pc.intime_halt_2d[g][(Integer)av_pc.datetime_counter.get(g)] = enter_time;
																	av_pc.in_distance_2d[g][(Integer)av_pc.datetime_counter.get(g)] = in_dist;
																				
																	//System.out.println("STATION_GEO TRUE2, enter_time="+av.intime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																	//System.out.println("indist_AFTER_SET="+av.in_distance_2d[g][(Integer)av.datetime_counter.get(g)]);
																	//System.out.println("HaltOccured-2");                      
																}
															} catch(Exception e2) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN2:"+e2.getMessage());}
														} //IF XML_DATE
													} catch(Exception ec1) {System.out.println("Catch in Halt1:"+ec1.getMessage());}
												} //FOR GEO COORD CLOSED
											} catch(Exception ec2) {System.out.println("Catch in Halt2:"+ec2.getMessage());}
										} //IF GEO CORRD
									} catch(Exception ec2) {System.out.println("Catch in Halt3:"+ec2.getMessage());}
									
									//primary_halt_lat_ref = primary_halt_lat_cr;
									//primary_halt_lng_ref = primary_halt_lng_cr;
									//primary_halt_xml_data_sec_ref = primary_halt_xml_data_sec_cr;            				
									primary_halt_flag = 0;
									
								} //IF PRIMARY HALT FLAG CLOSED
								
																								                
											//###### SECOND BLOCK -OUTSIDE HALT										
											try{
												
												//if(halt_occured)	//IF PREVIOUS HALT OCCURED TRUE
												{
													try{
														for(int g2=0; g2<av_pc.geo_coord_station.size();g2++)
														{
															if(((Integer)av_pc.entered_flag.get(g2)) ==0)
															{
																continue;
															}
															
															if(!vname1.equals( (String)av_pc.vname.get(g2)))
															{
																continue;
															}
															
															input_date1_sec = utility_classes.get_seconds(((String)av_pc.input_date1.get(g2)), 2);
															input_date2_sec = utility_classes.get_seconds(((String)av_pc.input_date2.get(g2)), 2);
															
															try{
																if( (xml_date_sec >= input_date1_sec) && (xml_date_sec <= input_date2_sec) )  //** CSV INPUT DATE COMPARISON
																{
																			//System.out.println("DATE MATCHED-2: lat="+lat+" ,lng="+lng+" ,xml_date="+xml_date);
																	status_geo = false;
																	
																	try{
																		if(!((String) av_pc.geo_coord_station.get(g2)).equals(""))
																		{
																			int current_customer = av_pc.customer_no_db.get(g2);
																			
																			//if( (last_customer_no>0) && (last_customer_no == current_customer) ) 
																			{																						
																				String[] geo_data = av_pc.geo_coord_station.get(g2).split(",");
																				geo_lat = geo_data[0];
																				geo_lng = geo_data[1];
																																											
																				//System.out.println("geo_lat="+geo_lat+" ,geo_lng="+geo_lng+ " ,lat="+lat+" ,lng="+lng+" ,distance_variable="+((Float)av_pc.distance_variable.get(g2))+"  ,xml_date="+xml_date+", date1="+(String)av_pc.input_date1.get(g2)+" ,date2="+(String)av_pc.input_date2.get(g2));
																				float tmpdistance = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																				//System.out.println("tmpdistance="+tmpdistance);
																				status_geo = utility_classes.check_with_range_landmark(lat, lng, geo_lat, geo_lng, ((Float)av_pc.distance_variable.get(g2)));
																				//System.out.println("status_geo="+status_geo);
																				//last_customer_no = 0;
																				
																				long enter_time_sec = utility_classes.get_seconds(enter_time, 2);																							
																				long diff_halt = xml_date_sec - enter_time_sec;
																				
																				if( (status_geo == false) && ( ((Integer)av_pc.entered_flag.get(g2)) ==1) && (diff_halt>60) )
																				{                    
																					//System.out.println("HALT COMPLETED1:"+vname1+" ,customer="+av_pc.customer_no_db.get(g2)+" ,dist_variable="+av_pc.distance_variable.get(g2)+"  ,xml_date="+xml_date);
																					halt_occured = false;
																					last_customer_no = 0;
																				 
																					av_pc.entered_flag.set(g2,0);	//corresponding to g
																					leave_time = xml_date;
																					av_pc.outime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = leave_time;
																				  
																					//System.out.println("HALT COMPLETED2: entered_flag="+(Integer)av_pc.entered_flag.get(g2));
																					//System.out.println("HC:: outime_halt="+av.outime_halt_2d[g][(Integer)av.datetime_counter.get(g)]);
																 
																					out_dist = utility_classes.calculateDistance(Float.parseFloat(lat),Float.parseFloat(geo_lat),Float.parseFloat(lng),Float.parseFloat(geo_lng));
																				  
																					//System.out.println("HC::outdist_BEFORE_SET="+out_dist);
																					
																					av_pc.out_distance_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = out_dist;
																				  
																					//System.out.println("HC::outdist_AFTER_SET="+av.out_distance_2d[g][(Integer)av.datetime_counter.get(g)]);
																				  
																					enter_time_tmp = av_pc.intime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)];
																					leave_time_tmp = av_pc.outime_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)];   
																				  
																					//System.out.println("FINAL:COMPLETE::enter_time_tmp="+enter_time_tmp+" ,leave_time_tmp="+leave_time_tmp);
																				  
																					input_date1_sec = utility_classes.get_seconds(leave_time_tmp, 2);
																					time = (utility_classes.get_seconds(leave_time_tmp, 2)) - (utility_classes.get_seconds(enter_time_tmp, 2));  
																				  
																						//System.out.println("HC::input_date1_sec="+input_date1_sec+" ,time="+time);
																					//$hms = secondsToTime($time);
																					duration = utility_classes.get_hms(time);	//$hms[h].":".$hms[m].":".$hms[s];
																				  
																						//System.out.println("HC::duration_BEFORE="+duration);
																				  
																					av_pc.time_dur_halt_2d[g2][(Integer)av_pc.datetime_counter.get(g2)] = duration;
																				  
																						//System.out.println("HC::duration_AFTER="+av.time_dur_halt_2d[g][(Integer)av.datetime_counter.get(g)]);														  													 
																				  
																						//System.out.println("HC::datetime_counter_BEFORE=" +(Integer)av.datetime_counter.get(g));
																						
																					av_pc.datetime_counter.set(g2,((Integer)av_pc.datetime_counter.get(g2)) + 1);	//corresponding to g
																				  
																						//System.out.println("HC::datetime_counter_AFTER=" +(Integer)av.datetime_counter.get(g));
																				  
																				} //IF GEO STATUS CLOSED
																			}
																		} 
																	} catch(Exception e3) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN1:"+e3.getMessage());}																
																}																			
																 
															} catch(Exception e1) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IN3:"+e1.getMessage());}
														} // IF INPUT CSV DATE COMPARISON               
													} catch(Exception e4) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IF INPUT CSV DATE COMPARISON :"+e4.getMessage());}
												}  // GEO COORD LOOP 
											} catch(Exception e5) {System.out.println("Exception in Main File(STATION)::CUSTOMER:GEO COORD LOOP  :"+e5.getMessage());}
																
										}  //else closed																
										//#####NORMAL HALT CLOSED																		
									   //#### LOGIC PART CLOSED										
									} //IF XML_DATE_SEC > STARTDATE CLOSED
								} catch(Exception e7) {System.out.println("Exception in Main File(STATION)::CUSTOMER:IF XML_DATE_SEC > STARTDATE:"+e7.getMessage());}
							} // IF XML_DATE!=NULL CLOSED
						} catch(Exception e8) {System.out.println("Exception in Main File(STATION)::CUSTOMER: XML_DATE:"+e8.getMessage());}
					} catch(Exception e9) {System.out.println("Exception in Main File(STATION)::CUSTOMER: IF LEN CLOSED:"+e9.getMessage());}
				}	// if len closed
				
						
}
