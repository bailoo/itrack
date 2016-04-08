<?php
  // Assmption: $postPars is having all the parameters name.

  $postData = array();
  
  // print_r($postPars);
  foreach($postPars as $parName)
  {
    $postData[$parName] = trim($_POST[$parName]);
  }
  // print_r($postData);
  
  class PostData
  {
    public $data;
    
    function __construct()
    {
      global $postData;
      $this->data = $postData;
    }
  }
?>
