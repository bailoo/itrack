<?php 
/** 
* Title: Double linked list
* Description: Implementation of a double linked list in PHP 
* @author Sameer Borate | http://www.codediesel.com
* Reference : Data Structures & Algorithms in Java : Robert Lafore
* @version 1.0 20th June 2009
*/ 
class Tree 
{
	public $data;
	public $parents;
	public $child=array();
	public $ChildCnt; 
	function __construct($data)
	{
		$this->data = $data;
		$this->ChildCnt = 0;
		$this->parents = null;
	}
}

class Info {	
	public $AccountName;
	public $AccountID;
	public $AccountType;	
	public $AccountGroupID;
	public $AccountGroupname;
	public $AccountHierarchyLevel;
	public $AccountCreateID;
	Public $AccountCreateName;
	Public $AdminID;
	Public $VehicleCnt;
	//Public $VehicleGroupID;
	Public $VehicleID=array();
	Public $Vehicle_LDT=array();
	Public $DeviceIMEINo=array();
	Public $DeviceIOTypeValue=array();
	Public $VehicleName=array();
	Public $VehicleNumber=array();
	Public $VehicleType=array();
	Public $VehicleCategory=array();
	Public $VehicleMaxSpeed=array();
	Public $VehicleFuelVoltage=array();
	Public $VehicleTankCapacity=array();
	Public $VehicleTag=array();
	Public $VehicleGroup=array();
	Public $VehicleGroupName=array();
	
	function __construct()
	{
		$this->AccountName = NULL;
		$this->AccountID = NULL;
		$this->AccountType = NULL;
		$this->AdminID = NULL;
		$this->AccountGroupname = NULL;
		$this->AccountGroupID = NULL;
		$this->AccountHierarchyLevel = NULL;
		$this->AccountCreateID = NULL;
		$this->AccountCreateName = NULL;
		$this->AccountGroupID = NULL; 		   
		$this->VehicleCnt = 0;
	}
}

?>