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
	public $AccountUserType;	
	public $AccountGroupID;
	public $AccountGroupname;
	public $AccountHierarchyLevel;
	public $AccountCreateID;
	Public $AccountCreateName;
	Public $AdminID;
	Public $AccountAdminID;
	Public $VehicleCnt;
	//Public $VehicleGroupID;
	//----updated on 30032015-------//
	public $AccountTypeThirdParty;
	//-----------------------------//
	
	Public $VehicleID=array();
	Public $Vehicle_LDT=array();
	Public $DeviceIMEINo=array();
        Public $DeviceRunningStatus=array();
	Public $DeviceIOTypeValue=array();
	Public $VehicleName=array();
	Public $VehicleNumber=array();
	Public $MobileNumber=array();
	Public $VehicleType=array();
	Public $VehicleCategory=array();
	Public $VehicleMaxSpeed=array();
	Public $VehicleFuelVoltage=array();
	Public $VehicleTankCapacity=array();
	Public $VehicleTag=array();
	Public $VehicleGroup=array();
	Public $VehicleGroupName=array();
	//==code updated 27032015==//
	Public $VehicleTypeThirdParty=array();
	Public $VehicleActiveDate=array();
	//==end====================//
	
	function __construct()
	{
		$this->AccountName = NULL;
		$this->AccountID = NULL;
		$this->AccountType = NULL;
		$this->AccountUserType = NULL;
		$this->AdminID = NULL;
		$this->AccountGroupname = NULL;
		$this->AccountGroupID = NULL;
		$this->AccountHierarchyLevel = NULL;
		$this->AccountCreateID = NULL;
		$this->AccountCreateName = NULL;
		$this->AccountGroupID = NULL; 		   
		$this->VehicleCnt = 0;
		//----updated on 30032015-------//
		$this->AccountTypeThirdParty= NULL;
		//-----------------------------//
	}
}

?>