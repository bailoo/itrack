<?php
	session_start();
	//Setting Session variables
	while (list($Skey, $Svalue) = each ($_SESSION)) {
              $$Skey = $Svalue;
        } 
	//Setting Request variables
	while (list($key, $value) = each ($_REQUEST)) {
              $$key = $value;
        } 

	//Setting post variables [optional]
	while (list($Pkey, $Pvalue) = each ($_POST)) {
              $$Pkey = $Pvalue;
        } 
	//Setting get variables [optional]
	while (list($Gkey, $Gvalue) = each ($_GET)) {
              $$Gkey = $Gvalue;
        }

  //set_time_limit();                

?>
