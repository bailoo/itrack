<?php
class parameterizeData
{
	Public $messageType;
	Public $version;
	Public $fix;
	Public $latitude;
	Public $longitude;
	Public $altitude;
	Public $speed;

	Public $io1;
	Public $io2;
	Public $io3;
	Public $io4;
	Public $io5;
	Public $io6;
	Public $io7;
	Public $io8;	
	
	Public $temperature;
	Public $batteryVoltage;	
	Public $engineRunHr;
	Public $acRunHr;
	Public $doorOpen1;
	Public $doorOpen2;
	Public $doorOpen3;
        Public $flowRate;
        Public $dispensing1;	
        Public $dispensing2;
        Public $dispensing3;
	
	Public $sigStr;
	Public $supVoltage;
	Public $dayMaxSpeed;
	Public $dayMaxSpeedTime;
	Public $lastHaltTime;
	Public $cellName;
	Public $fuel;
	
	Public $axParam;
	Public $ayParam;
	Public $azParam;
	Public $mxParam;
	Public $myParam;
	Public $mzParam;
	Public $bxParam;
	Public $byParam;
	Public $bzParam;
        Public $ci;
	Public $dataLog;

	function __construct()
	{
		$this->messageType=null;
		$this->version=null;
		$this->fix=null;
		$this->latitude=null;
		$this->longitude=null;
		$this->altitude=null;
		$this->speed=null;
		
		$this->io1 = null;
		$this->io2 = null;
		$this->io3 = null;
		$this->io4 = null;
		$this->io5 = null;
		$this->io6 = null;
		$this->io7 = null;
		$this->io8 = null;
		
		$this->temperature=null;
		$this->batteryVoltage=null;
		$this->acRunHr=null;
		$this->engineRunHr=null;
		$this->doorOpen1=null;
		$this->doorOpen2=null;
		$this->doorOpen3=null;
                $this->flowRate=null;
                $this->dispensing1=null;
                $this->dispensing2=null;
                $this->dispensing3=null;
		
		$this->sigStr=null;
		$this->supVoltage=null;		
		$this->dayMaxSpeed = null;
		$this->dayMaxSpeedTime = null;
		$this->lastHaltTime = null;
		$this->cellName=null;
		$this->fuel=null;
		
		$this->axParam = null;
		$this->ayParam = null;
		$this->azParam = null;
		$this->mxParam = null;
		$this->myParam = null;
		$this->mzParam = null;
		$this->bxParam = null;
		$this->byParam = null;
		$this->bzParam = null;	
                $this->ci = null;

		$this->dataLog = null;
	}
}
?>