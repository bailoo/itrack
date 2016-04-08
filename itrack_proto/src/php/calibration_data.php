<?php
  $calibration_id1 = $_POST['calibration_id'];
	$action_type1 = trim($_POST['action_type']); 
  $local_account_ids = $_POST['local_account_ids'];
  $calibration_name1 = trim($_POST['calibration_name']);	
  $calibration_data1 = trim($_POST['calibration_data']);
  //echo "cal_id=".$calibration_id1."action_type1=".$action_type1."local_acc=".$local_account_ids."cal_name=".$calibration_name1."cal_data=".$calibration_data1;
  class setgetpostvalue
  {
    public $calibrationID;
    public $actionType;
    public $localAccoutIDS;
    public $localCalibrationName;
    public $localCalibrationData;
    
    function __construct()
    {
      global $calibration_id1;
      global $action_type1;
      global $local_account_ids;
      global $calibration_name1;
      global $calibration_data1;
      $this->calibrationID = $calibration_id1;
      $this->actionType = $action_type1;
      $this->localAccoutIDS = $local_account_ids;
      $this->localCalibrationName = $calibration_name1;
      $this->localCalibrationData = $calibration_data1;   
    }
  }
?>
