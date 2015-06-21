package com.hdfc.init;

import java.util.ArrayList;

import com.hdfc.logic.worker;

public class init {
	
	public static int account_id = 1180; //HDFC ACCOUNT ID
	public ArrayList<Integer> vehicle_id = new ArrayList<Integer>();
	public ArrayList<String> vehicle_name = new ArrayList<String>();
	public ArrayList<Float> max_speed = new ArrayList<Float>();
	public ArrayList<String> device_imei_no = new ArrayList<String>();

	
	public static void main(String args[])
	{
		worker worker_class = new worker();
		worker_class.process_data(account_id);
	}
}
