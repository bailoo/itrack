<?php

	function get_xml_data($reg, $line)
	{
		$data = "";
		if(preg_match($reg, $line, $data_match))
		{
			$data = explode_i('"', $data_match[0], 1);
		}
		return $data;
	}

	function explode_i($reg, $str, $i)
	{
		$tmp = explode($reg, $str);
		return $tmp[$i];
	}	
  			
?>			