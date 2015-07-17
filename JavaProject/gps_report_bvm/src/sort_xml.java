import javax.print.attribute.standard.Destination;
import java.lang.reflect.Array;
import java.io.File;
import java.util.regex.*;

import org.w3c.dom.Document;
import org.w3c.dom.*;

/*import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException; */

import java.io.*;
import java.net.*;
import java.util.*;
import java.util.Date;
import java.sql.*;
import java.text.*;
import java.lang.*;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;

import java.util.*;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.text.ParseException;
import java.util.Date;


public class sort_xml 
{
	private Connection  connection;

	static int spc_count1=-1;		// VARIABLE FOR GETTING XML_FLEET/XML_DATA DIRECTORY NAME
	static int spc_count2=-1;		// VARIABLE FOR GETTING XML_FLEET/XML_DATA DIRECTORY NAME
		
	//public static String originalpath = "/var/www/html/";
	//public static String originalpath = "C:\\";
	//public static String originalpath = "/var/www/html/testwebcodes/testvts/xmltransfercodes/";
	
	/*public static void main(String[] args)
	{	   
		String folderDate="";
		String nam1="";		
		File aFile = null;
		int p=0;
		boolean DirMatch = false;
		int i=0,x=0;
		int file_length=0;
		
		//nam1 = "C:\\XMLTransferCodeNew/xml_data";
		nam1 = originalpath+"itrack_vts/xml_vts/xml_data";			//UNSORTED XML PATH
		aFile = new File(nam1);
		///GET ALL DIR IN XML_DATA

		folderDate = "2012-06-08";
		String SourceFolderPath = originalpath+"itrack_vts/xml_vts/xml_data/"+folderDate;
		String xml_original_folder = originalpath+"itrack_vts/java_xml_tmp/original_xml/";
		//String OtherDateFolderPath =  originalpath+"itrack_vts/OtherDate_xml_data/";
		String xml_unsorted_folder = originalpath+"itrack_vts/java_xml_tmp/unsorted_xml/";
		String xml = "359231039567951";
		String filename = xml+".xml";
		
		//CALL SORT XML METHOD
		String xml_file = SourceFolderPath+"/"+filename;
		String filename_tmp = xml+"_tmp123.xml";
		String xml_unsorted_file = xml_unsorted_folder+"/"+filename_tmp;
		copyfile(xml_file, xml_unsorted_file);
				
		//sortfile(SourceFolderPath, filename, xml_original_tmp, folderDate, TmpFolderPath);
		sortfile(xml_unsorted_folder, filename_tmp, xml_original_folder, folderDate);
		
		deleteFile(xml_unsorted_file);
	}  // METHOD CLOSED
	*/


///////////////// COPY FILE ////////////////////
private static void copyfile(String srFile, String dtFile)
{
	try
	{
		//System.out.println("srFile="+srFile+" ,dtFile="+dtFile);
		File f1 = new File(srFile);
		File f2 = new File(dtFile);

		//System.out.println("test1");
		FileReader in = new FileReader(f1);

		//For Append the file.
		//      OutputStream out = new FileOutputStream(f2,true);

		//For Overwrite the file.
		FileWriter out = new FileWriter(f2);

		int c;
		while ((c = in.read()) != -1)
		out.write(c);

		in.close();
		out.close();
		//System.out.println("File copied.");
	}
	catch(FileNotFoundException ex)
	{
		System.out.println(ex.getMessage() + " in the specified directory.");
		System.exit(0);
	}
	catch(IOException e)
	{
		System.out.println(" ERROR in copying="+e.getMessage());      
	}
}


///////// DELETE FILE ///////////////
static public boolean deleteFile(String filename)
{
	System.out.println("FileNameUpload="+filename);
	File f1 = new File(filename);
	boolean success = f1.delete();
	if (!success){
		System.out.println("Deletion failed.");
		return false;
	}else{
		System.out.println("xml_unsorted_tmp file deleted successfully.");
		return true;
	}
}


///////// DELETE DIRECTORY //////////////////////
  static public boolean deleteDirectory(File path) {
    if( path.exists() ) {
      File[] files = path.listFiles();
	  //System.out.println("len="+files.length);

      for(int i=0; i<files.length; i++) {
         if(files[i].isDirectory()) {
           deleteDirectory(files[i]);
         }
         else {
           files[i].delete();
         }
      }
    }
    return( path.delete() );
  }
	/////////////////////////////////////////////////

	static public void read_write_original_xml(String filename, String filename_tmp, boolean status)	//filename = SOURCE, filename_tmp= TMP DESTINATION
	{
		//System.out.println("filename="+filename+" ,fname_tmp="+filename_tmp);
		File f1 = new File(filename);
		String file2 = filename_tmp;
		BufferedWriter out=null;
		//File f2 = new File(file2);
		
		//File f2 = new File(file2);
		//System.out.println("file2="+file2);
		try
		{
			out = new BufferedWriter(new FileWriter(file2,status));			
			//System.out.println("status="+status);
			if (status==false)
			{				
				out.write("<t1>"+"\n");
			}
		}
		catch (Exception e)
		{
			//Catch exception if any
			System.err.println("TMP write Error1: " + e.getMessage());
		}
		
		if(f1.exists())
		{
			//System.out.println("In Read XML");
			 try
			 {
				// Open the file that is the first 
				// command line parameter
				FileInputStream fstream = new FileInputStream(filename);
				// Get the object of DataInputStream
				DataInputStream in = new DataInputStream(fstream);
				BufferedReader br = new BufferedReader(new InputStreamReader(in));
			
				String strLine;
				//Read File Line By Line
				int strlen=0;								
									
				while ((strLine = br.readLine()) != null)   
				{					
					// Print the content on the console
					//System.out.println (strLine);
												
					strlen = strLine.length();
					//System.out.println (" strlen="+strlen);
					
					char[] stringArray;
					//convert string into array using toCharArray() method of string class
					stringArray = strLine.toCharArray(); 
					//System.out.println (" strlen="+stringArray[0]);
					//System.out.println (" strlen="+stringArray[strlen-1]);

					//if((stringArray[0]=='<') && (stringArray[strlen-1]=='>'))

					if((stringArray[0]=='<') && (stringArray[strlen-1]=='>') && (strLine.equals("<t1>")==false) && (strLine.equals("</t1>")==false))
					{
					 //str2[k] = str2[j];
					 //k++;
					 //System.out.println("IN Str Array="+strLine);
					 out.write(strLine+"\n");
					}						
				}		
				//Close the input stream
				in.close();
				//f1.delete();
				//return file2;	
			}
			catch (Exception e)
			{
				//Catch exception if any
				System.err.println("TMP write Error2:" + e.getMessage());
			}				
		}
		
		try
		{
			if (status==true)
			{
				out.write("</t1>");
			}
			out.close();
		}
		catch (Exception e)
		{
			//Catch exception if any
			System.err.println("TMP write Error3:" + e.getMessage());
		}
		//return null;
	}

	
	//************* SORT XML FILE ********************//
	public static void sortfile(String SourceFolderPath, String SourceFileName, String DestinationFolderPath, String folderDate, String conditional_date)
	{
		//System.out.println("\nSourceFolderPath="+SourceFolderPath+" \nSourceFileName="+SourceFileName+" \nDestinationFolderPath="+DestinationFolderPath+"\nDestinationFolderPath="+DestinationFolderPath);
		String a1="",a2="",a3="",a4="",a5="", a6="", a7="",a8="";	//format_type1
		String a9="",a10="",a11="",a12="",a13="", a14="", a15="",a16="";	//format_type2
		String a17="",a18="",a19="",a20="",a21="", a22="", a23="",a24="";
		String a25="",a26="",a27="",a28="",a29="", a30="", a31="",a32="";
		String filename ="";
		
		boolean fileexists;
		int i=0;
		
		DateFormat formatter ; 
		Date date=null;
		formatter = new SimpleDateFormat("yyyy-MM-dd");
		Calendar cal1=Calendar.getInstance();
		Calendar cal2=Calendar.getInstance();
		
		//System.out.println("folderDate ="+folderDate);
		
		try
		{
			date = (Date)formatter.parse(folderDate);
			//System.out.println("test1"+date);
			cal2.setTime(date);
			//System.out.println("test2");
		}
		catch(Exception e)
		{
			System.out.println("Exception1: ="+e);
			return;
		}						
		
		//File DestinationFolder = new File(TmpFolderPath);
		/*File[] listOfFiles = DestinationFolder.listFiles();
		for (i = 0; i < listOfFiles.length; i++)	
		{
			File filetmp = new File(listOfFiles[i].getName());
			filetmp.delete();
		}*/
		
		//boolean success1 = (new File(DestinationFolderPath)).mkdir();
		//System.out.println("Folder Exists="+success1);
		
		//DestinationFolder = new File(DestinationFolderPath);
		//listOfFiles = DestinationFolder.listFiles();
		
		fileexists = false;

		int i2=0;
		//System.out.println("listOfFiles.length " + listOfFiles.length);
								
		//******************* READ XML AND UPDATE TO SORTED TMP FILE ***************************
		//******************************************************************************													
		String SourceFile = SourceFolderPath+"/"+SourceFileName;
		
		try 
		{
			//////////// xml file read //////////
			//filename = (TmpFolderPath+"/"+SourceFileName+"_tmp");
			
			/*String filesource = SourceFolderPath+"/"+SourceFileName; 
			String filedest = TmpFolderPath+"/"+SourceFileName+"_tmp";
				
			copyfile(filesource,filedest);
			//Destination folder path=original tmp xml
			//read_write_original_xml((DestinationFolderPath+"/"+SourceFileName),filename,false);
			//System.out.println("filename before================================="+filename);
			read_write_original_xml((SourceFolderPath+"/"+SourceFileName),filename,true);
			System.out.println("filename ================================="+filename);*/
			
			////////////////////////////////////
			ArrayList<String> f1 = new ArrayList<String>();
			ArrayList<String> f2 = new ArrayList<String>();
			ArrayList<String> f3 = new ArrayList<String>();
			ArrayList<String> f4 = new ArrayList<String>();
			ArrayList<String> f5 = new ArrayList<String>();
			ArrayList<String> f6 = new ArrayList<String>();
			ArrayList<String> f7 = new ArrayList<String>();
			ArrayList<String> f8 = new ArrayList<String>();		// Format 1 limit
			ArrayList<String> f9 = new ArrayList<String>();
			ArrayList<String> f10 = new ArrayList<String>();
			ArrayList<String> f11 = new ArrayList<String>();
			ArrayList<String> f12 = new ArrayList<String>();
			ArrayList<String> f13 = new ArrayList<String>();
			ArrayList<String> f14 = new ArrayList<String>();
			ArrayList<String> f15 = new ArrayList<String>();
			ArrayList<String> f16 = new ArrayList<String>();
			ArrayList<String> f17 = new ArrayList<String>();
			ArrayList<String> f18 = new ArrayList<String>();
			ArrayList<String> f19 = new ArrayList<String>();
			ArrayList<String> f20 = new ArrayList<String>();
			ArrayList<String> f21 = new ArrayList<String>();

			String marker="";
			//System.out.println("In One");

			int total_t1=0;
			NodeList listOf_t1 = null;														
			
			
			try
			{			
				common_xml_element cx = new common_xml_element();
				cx.set_master_variable(conditional_date, cx);
				
				System.out.println("va="+cx.va+" ,vb="+cx.vb+" ,vc="+cx.vc+" ,vd="+cx.vd+" ,ve="+cx.ve+" ,vf="+cx.vf+" ,vg="+cx.vg);				
				
				///////////////								
				String final_value ="";
				try{
					// Open the file that is the first 
					// command line parameter
					FileInputStream fstream = new FileInputStream(SourceFile);
					// Get the object of DataInputStream
					DataInputStream in = new DataInputStream(fstream);
					BufferedReader br = new BufferedReader(new InputStreamReader(in));
					String strLine;
					//Read File Line By Line
					//System.out.println("Read File Started ..");
					
					//System.out.println("va="+cx.va+" ,vb="+cx.vb+" ,vc="+cx.vc+" ,vd="+cx.vd+" ,ve="+cx.ve+" ,vf="+cx.vf+" ,vg="+cx.vg);
					//System.out.println("a9="+a9+" ,a10="+a10+" ,a11="+a11+" ,a12="+a12+" ,a13="+a13+" ,a14="+a14+" ,a15="+a15);
					
					while ((strLine = br.readLine()) != null) {
						
						int len = strLine.length();
						
						if(len > 50)
						{
							try{
								a1 = getXmlAttribute(strLine,""+cx.va+"=\"[^\"]+");	
								a2  = getXmlAttribute(strLine,""+cx.vb+"=\"[^\" ]+");							
								a3 = getXmlAttribute(strLine,""+cx.vc+"=\"[^\"]+");
								a4 = getXmlAttribute(strLine,""+cx.vd+"=\"[^\" ]+");
								a5 = getXmlAttribute(strLine,""+cx.ve+"=\"[^\" ]+");
								a6  = getXmlAttribute(strLine,""+cx.vf+"=\"[^\" ]+");
								a7  = getXmlAttribute(strLine,""+cx.vg+"=\"[^\"]+");
								a8 = getXmlAttribute(strLine,""+cx.vh+"=\"[^\"]+");	
								a9  = getXmlAttribute(strLine,""+cx.vi+"=\"[^\"]+");
								a10  = getXmlAttribute(strLine,""+cx.vj+"=\"[^\" ]+");							
								a11 = getXmlAttribute(strLine,""+cx.vk+"=\"[^\" ]+");
								a12 = getXmlAttribute(strLine,""+cx.vl+"=\"[^\"]+");
								a13 = getXmlAttribute(strLine,""+cx.vm+"=\"[^\" ]+");
								a14  = getXmlAttribute(strLine,""+cx.vn+"=\"[^\" ]+");
								a15  = getXmlAttribute(strLine,""+cx.vo+"=\"[^\" ]+");
								a16 = getXmlAttribute(strLine,""+cx.vp+"=\"[^\" ]+");	
								a17  = getXmlAttribute(strLine,""+cx.vq+"=\"[^\" ]+");
								a18  = getXmlAttribute(strLine,""+cx.vr+"=\"[^\" ]+");
								a19 = getXmlAttribute(strLine,""+cx.vs+"=\"[^\" ]+");
								a20 = getXmlAttribute(strLine,""+cx.vt+"=\"[^\" ]+");
								a21 = getXmlAttribute(strLine,""+cx.vu+"=\"[^\" ]+");												

								//System.out.println("a1="+a1+" ,a2="+a2+" ,a3="+a3+" ,a4="+a4+" ,a5="+a5+" ,a6="+a6+" ,a7="+a7);
								//System.out.println("a9="+a9+" ,a10="+a10+" ,a11="+a11+" ,a12="+a12+" ,a13="+a13+" ,a14="+a14+" ,a15="+a15);
								f1.add(a1);
								f2.add(a2);
								f3.add(a3);
								f4.add(a4);
								f5.add(a5);
								f6.add(a6);
								f7.add(a7);
								f8.add(a8);
								f9.add(a9);
								f10.add(a10);
								f11.add(a11);
								f12.add(a12);
								f13.add(a13);
								f14.add(a14);
								f15.add(a15);
								f16.add(a16);
								f17.add(a17);
								f18.add(a18);
								f19.add(a19);
								f20.add(a20);
								f21.add(a21);

							} catch(Exception e) {System.out.println("Exception SORTING : in middle of Read file:"+e.getMessage());}
							//System.out.println("Read Line completed");
							////////////////////
						}	// if len closed
					}	// while readline closed
					//System.out.println("Read File Completed.");
					//Close the input stream
					in.close();
				}catch (Exception e){ System.err.println("ERROR IN GETTING XML ATTRIBUTE: " + e.getMessage());}			
				/////////////// E			
			}
			catch(Exception e)			////CATCH BLOCK
			{
				System.out.println("INSIDE CATCH++++++++++++++++ EXCEPTION OCCURRED");
				//System.out.println("Total no of t1: " + total_t1);
				return;									
			}
										
			////////////////////// SORTING DATA ///////////////////////									
			String dateprev="";
			String datenext="";
			String f1_tmp="";
			String f2_tmp="";
			String f3_tmp="";
			String f4_tmp="";
			String f5_tmp="";
			String f6_tmp="";
			String f7_tmp="";
			String f8_tmp="";
			
			String f9_tmp="";
			String f10_tmp="";
			String f11_tmp="";
			String f12_tmp="";
			String f13_tmp="";
			String f14_tmp="";
			String f15_tmp="";
			String f16_tmp="";

			String f17_tmp="";
			String f18_tmp="";
			String f19_tmp="";
			String f20_tmp="";
			String f21_tmp="";
			
			DateFormat df = new SimpleDateFormat("yyyy-MM-dd H:m:s");
			Date time1;
			Date time2;
			double t1,t2;
			boolean done = false;
			int n=0,SortCnt=0;
			long value=0,value1=0;

			//System.out.println("Stack Size:"+f1.size());
				
			for(int m=1;m<f1.size();m++)
			{
				try{	
					dateprev = (String)f8.get(m);
					time1 = df.parse(dateprev);		
					value = time1.getTime();

					f1_tmp = (String)f1.get(m);			
					f2_tmp = (String)f2.get(m);			
					f3_tmp = (String)f3.get(m);			
					f4_tmp = (String)f4.get(m);			
					f5_tmp = (String)f5.get(m);			
					f6_tmp = (String)f6.get(m);			
					f7_tmp = (String)f7.get(m);			
					f8_tmp = (String)f8.get(m);	
					f9_tmp = (String)f9.get(m);			
					f10_tmp = (String)f10.get(m);
					f11_tmp = (String)f11.get(m);			
					f12_tmp = (String)f12.get(m);			
					f13_tmp = (String)f13.get(m);			
					f14_tmp = (String)f14.get(m);			
					f15_tmp = (String)f15.get(m);			
					f16_tmp = (String)f16.get(m);			
					f17_tmp = (String)f17.get(m);			
					f18_tmp = (String)f18.get(m);			
					f19_tmp = (String)f19.get(m);			//DATETIME
					f20_tmp = (String)f20.get(m);			
					f21_tmp = (String)f21.get(m);		

					n = m - 1;
					done = false;

					while(done == false)
					{
						try {		
							datenext = (String)f8.get(n);
							time2 = df.parse(datenext);
							value1 = time2.getTime();
							//System.out.println("value1="+value1+" ,value="+value);
							if (value1 > value)
							{
								f1.set(n+1,(String)f1.get(n));
								f2.set(n+1,(String)f2.get(n));
								f3.set(n+1,(String)f3.get(n));
								f4.set(n+1,(String)f4.get(n));
								f5.set(n+1,(String)f5.get(n));
								f6.set(n+1,(String)f6.get(n));
								f7.set(n+1,(String)f7.get(n));
								f8.set(n+1,(String)f8.get(n));
								f9.set(n+1,(String)f9.get(n));
								f10.set(n+1,(String)f10.get(n));
								f11.set(n+1,(String)f11.get(n));
								f12.set(n+1,(String)f12.get(n));
								f13.set(n+1,(String)f13.get(n));
								f14.set(n+1,(String)f14.get(n));
								f15.set(n+1,(String)f15.get(n));
								f16.set(n+1,(String)f16.get(n));
								f17.set(n+1,(String)f17.get(n));
								f18.set(n+1,(String)f18.get(n));
								f19.set(n+1,(String)f18.get(n));
								f20.set(n+1,(String)f20.get(n));
								f21.set(n+1,(String)f21.get(n));

								n = n - 1;
								if (n < 0)
								{
									done = true;
								}
							}
							else
							{
								done = true;
							}						
						} catch(Exception e1) { System.out.println("Error in inner WHILE during SORTING :"+e1.getMessage()); done=true; }
					}

					f1.set(n+1,f1_tmp);
					f2.set(n+1,f2_tmp);
					f3.set(n+1,f3_tmp);
					f4.set(n+1,f4_tmp);
					f5.set(n+1,f5_tmp);
					f6.set(n+1,f6_tmp);
					f7.set(n+1,f7_tmp);
					f8.set(n+1,f8_tmp);
					f9.set(n+1,f9_tmp);
					f10.set(n+1,f10_tmp);
					f11.set(n+1,f11_tmp);
					f12.set(n+1,f12_tmp);
					f13.set(n+1,f13_tmp);
					f14.set(n+1,f14_tmp);
					f15.set(n+1,f15_tmp);
					f16.set(n+1,f16_tmp);
					f17.set(n+1,f17_tmp);
					f18.set(n+1,f18_tmp);
					f19.set(n+1,f19_tmp);
					f20.set(n+1,f20_tmp);
					f21.set(n+1,f21_tmp);

					if(SortCnt>1000)
					{
						SortCnt=0;
						//System.out.println("m:"+m);
					}
					SortCnt++;
					
				} catch(Exception e2) { System.out.println("Error in outer m during SORTING :"+e2.getMessage()); }
			}

			//System.out.println("File Sorted");
																		
			//////////////////////// COPY UNSORTED ORIGINAL DATA TO OTHER LOCATION ////
			
			/*if(CriticalFile==false)
			{
				//String orig_dir = "C:\\XMLTransferCodeNew/xml_unsorted_original_data";
				System.out.println("orig_dir="+orig_dir);
				
				success1 = (new File(orig_dir + "/" + folderDate)).mkdir();
				System.out.println("Success1="+success1);
				
				String filesource = SourceFolderPath+"/"+SourceFileName;									

				System.out.println("filesource="+filesource+" filedest="+filedest);								
				copyfile(filesource,filedest);
			}*/			
			
			//////////////////////// WRITE SORTED DATA TO NEW XML FILE ////////
			
			//String mydir1 = "C:\\XMLTransferCodeNew/sorted_xml_data/"+folderDate;
			//String mydir1 = DestinationFolderPath;

			//boolean success2 = (new File(mydir1)).mkdir();
			//System.out.println("Success_sorted move="+success2+" dir="+mydir1);

			String q="\"";					
			
			BufferedWriter out = null;

			//System.out.println(" format_type="+format_type+" a1="+a1+" io1="+a9);

			String tmpdate = "",previoustmpdate = "";
			boolean firstdata = false;
			
			//System.out.println("Folder Date="+cal2.get(Calendar.YEAR)+"/"+cal2.get(Calendar.MONTH)+"/"+cal2.get(Calendar.DATE));
			
			out = new BufferedWriter(new FileWriter(DestinationFolderPath+"/"+SourceFileName));
			//System.out.println("SORTED FILE NAME="+DestinationFolderPath+"/"+SourceFileName);

			for(int k=0;k<f1.size();k++)
			{
				/*System.out.println("f1="+((String)f1.get(k)));		
				System.out.println("f2="+((String)f2.get(k)));
				System.out.println("f3="+((String)f3.get(k)));
				System.out.println("f4="+((String)f4.get(k)));
				System.out.println("f5="+((String)f5.get(k)));
				System.out.println("f6="+((String)f6.get(k)));
				System.out.println("f7="+((String)f7.get(k)));
				System.out.println("f8="+((String)f8.get(k)));	*/
								
				tmpdate = f8.get(k);
				StringTokenizer ST = new StringTokenizer(tmpdate," ");
				String tmpdate1 = ST.nextToken();
				date = (Date)formatter.parse(tmpdate1);
				cal1.setTime(date);
				
				if((k==0) || ((tmpdate1.equals(previoustmpdate)==false)))
				{
					/*System.out.println(k+":"+"Current Date="+cal1.get(Calendar.YEAR)+"/"+cal1.get(Calendar.MONTH)+"/"+cal1.get(Calendar.DATE));
					if(k!=0)
					{
						out.close();
					}
					if(((cal1.get(Calendar.YEAR)==cal2.get(Calendar.YEAR)) && (cal1.get(Calendar.MONTH)==cal2.get(Calendar.MONTH)) && (cal1.get(Calendar.DATE)==cal2.get(Calendar.DATE))) || (cal1.get(Calendar.YEAR)==0) )
					{
						File fdest = new File(DestinationFolderPath+"/"+SourceFileName);
						if(fdest.exists())
						{
							fdest.delete();
						}
						out = new BufferedWriter(new FileWriter(DestinationFolderPath+"/"+SourceFileName));
						System.out.println("SORTED FILE NAME="+DestinationFolderPath+"/"+SourceFileName);
					}*/
					firstdata = true;
				}
				else
				{
					firstdata = false;
				}						
				//System.out.println("FIRST_DATA="+firstdata+" K="+k);				
				/*a=MsgType	1
				b=Version	2
				c=Fix		3
				d=Latitude	4
				e=Longitude	5
				f=Speed		6
				g=serverdatetime	7
				h=DateTime	8
				i=io_value1 9
				j=io_value2	10
				k=io_value3	11
				l=io_value4	12
				m=io_value5	13
				n=io_value6	14
				o=io_value7	15
				p=io_value8	16
				q=Signal_Strength 17
				r=SupplyVoltage 18*/
				
				if(firstdata==true)
				{										
					//System.out.println("SORT:ONE");
					try{
					marker = "<t1>\n<x "+"a="+q+((String)f1.get(k))+q+" b="+q+((String)f2.get(k))+q+" c="+q+((String)f3.get(k))+q+" d="+q+((String)f4.get(k))+q+" e="+q+((String)f5.get(k))+q+			
					" f="+q+((String)f6.get(k))+q+" g="+q+((String)f7.get(k))+q+" h="+q+tmpdate+q+" i="+q+((String)f9.get(k))+q+			
					" j="+q+((String)f10.get(k))+q+" k="+q+((String)f11.get(k))+q+" l="+q+((String)f12.get(k))+q+" m="+q+((String)f13.get(k))+q+" n="+q+((String)f14.get(k))+q+" o="+q+((String)f15.get(k))+q+" p="+q+((String)f16.get(k))+q+"/>";
					} catch(Exception ee1) {System.out.println("ee1="+ee1.getMessage());}
					out.write(marker);					
				}
				else if(k == ((f1.size())-1) )
				{											
					//System.out.println("SORT:TWO");
					marker = "\n<x "+"a="+q+((String)f1.get(k))+q+" b="+q+((String)f2.get(k))+q+" c="+q+((String)f3.get(k))+q+" d="+q+((String)f4.get(k))+q+" e="+q+((String)f5.get(k))+q+			
					" f="+q+((String)f6.get(k))+q+" g="+q+((String)f7.get(k))+q+" h="+q+tmpdate+q+" i="+q+((String)f9.get(k))+q+			
					" j="+q+((String)f10.get(k))+q+" k="+q+((String)f11.get(k))+q+" l="+q+((String)f12.get(k))+q+" m="+q+((String)f13.get(k))+q+" n="+q+((String)f14.get(k))+q+" o="+q+((String)f15.get(k))+q+" p="+q+((String)f16.get(k))+q+"/>\n</t1>";
					out.write(marker);											
				}

				else
				{											
					//System.out.println("SORT:THREE");
					marker = "\n<x "+"a="+q+((String)f1.get(k))+q+" b="+q+((String)f2.get(k))+q+" c="+q+((String)f3.get(k))+q+" d="+q+((String)f4.get(k))+q+" e="+q+((String)f5.get(k))+q+			
					" f="+q+((String)f6.get(k))+q+" g="+q+((String)f7.get(k))+q+" h="+q+tmpdate+q+" i="+q+((String)f9.get(k))+q+			
					" j="+q+((String)f10.get(k))+q+" k="+q+((String)f11.get(k))+q+" l="+q+((String)f12.get(k))+q+" m="+q+((String)f13.get(k))+q+" n="+q+((String)f14.get(k))+q+" o="+q+((String)f15.get(k))+q+" p="+q+((String)f16.get(k))+q+"/>";
					out.write(marker);
				}
				
				previoustmpdate = tmpdate1;
				
				//System.out.println(marker);
				////////// DATA WRITE CODE CLOSED ///////////////////
			}

			out.close();
			
			//System.out.println("Delete Tmp File: " +filename);			
			//File filetmp = new File(filename);
			//filetmp.delete();									
		}
		catch (Exception err)
		{
			System.out.println ("** Read xml file error"+err.getMessage ());
		}
		
		catch (Throwable t)
		{
			t.printStackTrace ();
		}
		 //System.exit (0);
	}
		
	//************* GET XML ATTRIBUTES FROM LINE STRING ************//
	public static String getXmlAttribute(String line, String param)
	{
		String str1 ="";
		String value ="";
		String[] str2;
		
		try {
			Pattern p = Pattern.compile(param);
			Matcher matcher = p.matcher(line);				
			
			while(matcher.find()){
						
				str1 = matcher.group().toString().replace("\"","");
				str2 = str1.split("=");
				//System.out.println(str2[1]);
				value = str2[1];
				//System.out.println("val="+value);
				break;
			}
		} catch(Exception e) { System.out.println("Error in function-Xml Attribute"+e.getMessage());}
		
		return value;		
	}

    public static boolean containsOnlyNumbers(String str) {
        
        //It can't contain only numbers if it's null or empty...
        if (str == null || str.length() == 0)
            return false;
        
        for (int i = 0; i < str.length(); i++) {

            //If we find a non-digit character we return false.
            if (!Character.isDigit(str.charAt(i)))
                return false;
        }
        
        return true;
    }	
}
                        
