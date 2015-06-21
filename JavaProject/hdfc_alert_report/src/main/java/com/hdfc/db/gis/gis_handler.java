package com.hdfc.db.gis;

public class gis_handler {	
	
	public String location="";
	public int chaurahaID = 0, roadID =0, routeID=0;
	
	public String PullLocationGis(String lat, String lng)
	{
		return location;
	}
	
	public int PullCharurahaGis(String lat, String lng)
	{		
		return chaurahaID;
	}
	
	public int PullRoadGis(String lat, String lng)
	{
		return roadID;
	}
	
	public int PullRouteGis(String lat, String lng)
	{
		return routeID;
	}

	public void PushCharahaGis(String chaurahaName, String lat, String lng)
	{
		//PUSH CHAURAHA TO GIS DB
	}
	
	public void PushRoadGis(String chaurahaname, String lat, String lng)
	{
		//PUSH ROAD TO GIS DB
	}
}
