<script type="text/javascript">
/*
Script Name: Your Computer Information
Author: Harald Hope, Website: http://TechPatterns.com/
Script Source URI: http://TechPatterns.com/downloads/browser_detection.php
Version: 1.2.4
Copyright (C) 3 April 2010

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

Get the full text of the GPL here: http://www.gnu.org/licenses/gpl.txt

This script requires the Full Featured Browser Detection and the Javascript Cookies scripts
to function.
You can download them here.
http://TechPatterns.com/downloads/browser_detection_php_ar.txt
http://TechPatterns.com/downloads/javascript_cookies.txt

Please note: this version requires the php browser_detection script version 5.3.3 or
newer, because of the new full return of arrays $moz_array and $webkit_array as keys
10 and 11, and $webkit_array key 7, and use of the new array key 14, true_msie_version.
*/

/*
get client browser data
*/
function client_data(info)
{
	if (info == 'width')
	{
		width = (screen.width) ? screen.width:'';
		// check for windows off standard dpi screen res
		if (typeof(screen.deviceXDPI) == 'number') {
			width *= screen.deviceXDPI/screen.logicalXDPI;
		} 
		return width;
	}
	else if (info == 'hight')
	{
		height = (screen.height) ? screen.height:'';
		// check for windows off standard dpi screen res
		if (typeof(screen.deviceXDPI) == 'number') {
			height *= screen.deviceYDPI/screen.logicalYDPI;
		} 
		return height;
  }
	else if (info == 'js' )
	{
		return 'yes';
	}
	else if ( info == 'cookies' )
	{
		expires ='';
		Set_Cookie( 'cookie_test', 'it_worked' , expires, '', '', '' );
		if ( Get_Cookie( 'cookie_test' ) )
		{
		  return 'yes';
		}
		return 'no';
	}
	return 'no';
}
</script>

			<?php
			include_once("util_browser_detection.php");
      
      $os = '';
			$os_starter = '<h4 class="right-bar">Operating System:</h4><p class="right-bar">';
			// $os_finish = '</p>';
			// $full = '';
			// $handheld = '';

			$browser_info = browser_detection('full');
			
			// $mobile_device, $mobile_browser, $mobile_browser_number, $mobile_os, $mobile_os_number, $mobile_server, $mobile_server_number
			if ( $browser_info[8] == 'mobile' )
			{
				//$handheld = '<h4 class="right-bar">Handheld Device:</h4><p class="right-bar">';
				if ( $browser_info[13][0] )
				{
					//$handheld .= 'Type: ' . ucwords( $browser_info[13][0] );
					$type = ucwords( $browser_info[13][0] ); 
					if ( $browser_info[13][7] )
					{
						//$handheld = $handheld  . ' v: ' . $browser_info[13][7];
						$browser_v = $browser_info[13][7]; 
					}
					//$handheld = $handheld  . '<br />';
				}
				if ( $browser_info[13][3] )
				{
					// detection is actually for cpu os here, so need to make it show what is expected
					if ( $browser_info[13][3] == 'cpu os' )
					{
						$browser_info[13][3] = 'ipad os';
					}
					//$handheld .= 'OS: ' . ucwords( $browser_info[13][3] ) . ' ' .  $browser_info[13][4] . '<br />';
					$os = ucwords( $browser_info[13][3] ) . ' ' .  $browser_info[13][4]; 
					// don't write out the OS part for regular detection if it's null
					if ( !$browser_info[5] )
					{
						$os_starter = '';
						//$os_finish = '';
					}
				}
				// let people know OS couldn't be figured out
				if ( !$browser_info[5] && $os_starter )
				{
					$os_starter .= 'OS: N/A';
					$os = 'N/A'; 
				}
				if ( $browser_info[13][1] )
				{
					//$handheld .= 'Browser: ' . ucwords( $browser_info[13][1] ) . ' ' .  $browser_info[13][2] . '<br />';
					$browser = ucwords( $browser_info[13][1] ) . ' ' .  $browser_info[13][2];
				}
				if ( $browser_info[13][5] )
				{
					//$handheld .= 'Server: ' . ucwords( $browser_info[13][5] . ' ' .  $browser_info[13][6] ) . '<br />';
					$server = ucwords( $browser_info[13][5] . ' ' .  $browser_info[13][6] ); 
				}
				//$handheld .= '</p>';
			}

			switch ($browser_info[5])
			{
				case 'win':
					$os .= 'Windows ';
					break;
				case 'nt':
					$os .= 'Windows NT ';
					break;
				case 'lin':
					$os .= 'Linux ';
					break;
				case 'mac':
					$os .= 'Mac ';
					break;
				case 'iphone':
					$os .= 'Mac ';
					break;
				case 'unix':
					$os .= 'Unix Version: ';
					break;
				default:
					$os .= $browser_info[5];
			}

			if ( $browser_info[5] == 'nt' )
			{
				if ($browser_info[6] == 5)
				{
					$os .= '5.0 (Windows 2000)';
				}
				elseif ($browser_info[6] == 5.1)
				{
					$os .= '5.1 (Windows XP)';
				}
				elseif ($browser_info[6] == 5.2)
				{
					$os .= '5.2 (Windows XP x64 Edition or Windows Server 2003)';
				}
				elseif ($browser_info[6] == 6.0)
				{
					$os .= '6.0 (Windows Vista)';
				}
				elseif ($browser_info[6] == 6.1)
            {
               $os .= '6.1 (Windows 7)';
            }
            elseif ($browser_info[6] == 'ce')
            {
               $os .= 'CE';
            }
			}
			elseif ( $browser_info[5] == 'iphone' )
			{
				$os .=  'OS X (iPhone)';
			}
			// note: browser detection now returns os x version number if available, 10 or 10.4.3 style
			elseif ( ( $browser_info[5] == 'mac' ) && ( strstr( $browser_info[6], '10' ) ) )
			{
				$os .=  'OS X v: ' . $browser_info[6];
			}
			elseif ( $browser_info[5] == 'lin' )
			{
				$os .= ( $browser_info[6] != '' ) ? 'Distro: ' . ucwords($browser_info[6] ) : 'Smart Move!!!';
			}
			// default case for cases where version number exists
			elseif ( $browser_info[5] && $browser_info[6] )
			{
				$os .=  " " . ucwords( $browser_info[6] );
			}
			elseif ( $browser_info[5] && $browser_info[6] == '' )
			{
				$os .=  ' (version unknown)';
			}
			elseif ( $browser_info[5] )
			{
				$os .=  ucwords( $browser_info[5] );
			}
			// $os = $os_starter . $os . $os_finish;
			// $full .= $handheld . $os . '<h4 class="right-bar">Current Browser / UA:</h4><p class="right-bar">';
			if ($browser_info[0] == 'moz' )
			{
				$a_temp = $browser_info[10];// use the moz array
				// $full .= ($a_temp[0] != 'mozilla') ? 'Mozilla/ ' . ucwords($a_temp[0]) . ' ' : ucwords($a_temp[0]) . ' ';
				// $full .= $a_temp[1] . '<br />';
				// $full .= 'ProductSub: ';
				// $full .= ( $a_temp[4] != '' ) ? $a_temp[4] . '<br />' : 'Not Available<br />';
				// $full .= ($a_temp[0] != 'galeon') ? 'Engine: Gecko RV: ' . $a_temp[3] : '';
				$browser = ($a_temp[0] != 'mozilla') ? 'Mozilla/' . ucwords($a_temp[0]) . ' ' : ucwords($a_temp[0]) . ' ';
        $browser_v = $a_temp[1]; 
			}
			elseif ($browser_info[0] == 'ns' )
			{
				// $full .= 'Browser: Netscape<br />';
				// $full .= 'Full Version Info: ' . $browser_info[1];
				$browser = 'Netscape';
        $browser_v = $browser_info[1]; 
			}
			elseif ( $browser_info[0] == 'webkit' )
			{
				$a_temp = $browser_info[11];// use the webkit array
				// $full .= 'User Agent: ';
				// $full .= ucwords($a_temp[0]) . ' ' . $a_temp[1];
				// $full .= '<br />Engine: AppleWebKit v: ';
				// $full .= ( $browser_info[1] ) ? $browser_info[1] : 'Not Available';
				$browser = ucwords($a_temp[0]);
        $browser_v = $a_temp[1]; 
			}
			elseif ( $browser_info[0] == 'ie' )
			{
				// $full .= 'User Agent: ';
				// $full .= strtoupper($browser_info[7]);
				// $browser_info[14] will only be set if $browser_info[1] is also set
				$browser = strtoupper($browser_info[7]);
				if ( array_key_exists( '14', $browser_info ) && $browser_info[14] )
				{
					// $full .= '<br />(compatibility mode)';
					// $full .= '<br />Actual Version: ' . number_format( $browser_info[14], '1', '.', '' );
					// $full .= '<br />Compatibility Version: ' . $browser_info[1];
					$browser_v = $browser_info[1]; 
				}
				else
				{
					// $full .= '<br />Full Version Info: ';
					// $full .= ( $browser_info[1] ) ? $browser_info[1] : 'Not Available';
					$browser_v = ( $browser_info[1] ) ? $browser_info[1] : 'Not Available';
				}
			}
			else
			{
				// $full .= 'User Agent: ';
				// $full .= ucwords($browser_info[7]);
				// $full .= '<br />Full Version Info: ';
				// $full .= ( $browser_info[1] ) ? $browser_info[1] : 'Not Available';
				$browser = ucwords($browser_info[7]);
				$browser_v = ( $browser_info[1] ) ? $browser_info[1] : 'Not Available';
			}
			// echo $full . '</p>';
			?>
			<!--
			<script type="text/javascript">
				client_data('width');
			</script>
			<h4 class="right-bar">JavaScript</h4>
			<script type="text/javascript">
				client_data('js');
			</script>
			<noscript>
			<p class="right-bar">JavaScript is disabled</p>
			</noscript>
			<script type="text/javascript">
				client_data('cookies');
			</script>
      -->
      
      <?php
        $browser_number = browser_detection('browser_number');
        $browser_working = browser_detection('browser_working');
        $browser_name = browser_detection('browser_name');
        $os_name = browser_detection('os');
        $os_number = browser_detection('os_number');
        $ip = $_SERVER['REMOTE_ADDR'];
      ?>
      
      <?php
        // $browser_number $browser_working $browser_name $os_name $os_number $os $browser $browser_v

        /*
        echo "<table align=center border=0 cellpadding=2 cellspacing=2 >";
  
        echo "<tr><td>ip</td><td>".$ip."</td></tr>";
        
        echo "<tr><td>browser_number</td><td>".$browser_number."</td></tr>";
        echo "<tr><td>browser_working</td><td>".$browser_working."</td></tr>";
        echo "<tr><td>browser_name</td><td>".$browser_name."</td></tr>";
        echo "<tr><td>browser</td><td>".$browser."</td></tr>";
        echo "<tr><td>browser_v</td><td>".$browser_v."</td></tr>";
      
        echo "<tr><td>os_name</td><td>".$os_name."</td></tr>";
        echo "<tr><td>os_number</td><td>".$os_number."</td></tr>";
        echo "<tr><td>os</td><td>".$os."</td></tr>";

        echo "</table>";
        */
      ?>