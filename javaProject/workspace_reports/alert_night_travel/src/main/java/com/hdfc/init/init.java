package com.hdfc.init;

import java.util.ArrayList;

import com.hdfc.logic.worker;

public class init {
	
	//public static int account_id = 1180; //HDFC ACCOUNT ID
	public static int account_id = 231; //HDFC ACCOUNT ID
	
	public static ArrayList<Integer> vehicle_id = new ArrayList<Integer>();
	public static ArrayList<String> vehicle_name = new ArrayList<String>();
	public static ArrayList<Float> max_speed = new ArrayList<Float>();
	public static ArrayList<String> device_imei_no = new ArrayList<String>();
	
	public static void main(String args[]) {
		//worker worker_class = new worker();
		worker.process_data(account_id);
	}
}