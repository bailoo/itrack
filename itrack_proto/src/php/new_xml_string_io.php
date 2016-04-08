<?php
function get_io_to_new_method($date_new,$date_old,$io_value)
{
	if($date_new>$date_old)  /// for sorted xml
	{
		if($io_value=='io1')
		{
			$io_value='i';
		}
		if($io_value=='io2')
		{
			$io_value='j';
		}
		if($io_value=='io3')
		{
			$io_value='k';
		}
		if($io_value=='io4')
		{
			$io_value='l';
		}
		if($io_value=='io5')
		{
			$io_value='m';
		}
		if($io_value=='io6')
		{
			$io='n';
		}
		if($io_value=='io7')
		{
			$io_value='o';
		}
		if($io_value=='io8')
		{
			$io_value='p';
		}
		return $io_value;
	}
	else
	{
		return $io_value;
	}
}
//for ac
function get_io_ac_to_new_method($date_new,$date_old,$io_value)
{
	if($date_new>$date_old)  /// for sorted xml
	{
			if($io_value=='io1')
			{
				$io_value='i';
			}
			if($io_value=='io2')
			{
				$io_value='j';
			}
			if($io_value=='io3')
			{
				$io_value='k';
			}
			if($io_value=='io4')
			{
				$io_value='l';
			}
			if($io_value=='io5')
			{
				$io_value='m';
			}
			if($io_value=='io6')
			{
				$io_value='n';
			}
			if($io_value=='io7')
			{
				$io_value='o';
			}
			if($io_value=='io8')
			{
				$io_value='p';
			}
	
		return $io_value;
	}
	else
	{
		return $io_value;
	}
}
?>